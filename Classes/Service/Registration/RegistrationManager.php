<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2010 Frans van der Veen [Netcreators] <extensions@netcreators.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Netcreators\NcgovPdc\Service\Registration;

use Netcreators\NcgovPdc\Configuration\Exception\NotInitializedException;
use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;
use Netcreators\NcgovPdc\Domain\Model\Product;
use Netcreators\NcgovPdc\Domain\Model\Registration;
use Netcreators\NcgovPdc\Domain\Model\RegistrationResult;
use Netcreators\NcgovPdc\Domain\Search\SearchParameter;

/**
 * Registration
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Service
 */
class RegistrationManager
{
    /**
     * @var boolean
     */
    protected $registrationRunning = false;

    /**
     * @var \Netcreators\NcgovPdc\Configuration\ConfigurationManager
     * @inject
     */
    protected $localConfigurationManager = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\RegistrationResultRepository
     * @inject
     */
    protected $registrationResultRepository = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Model\Registration
     * @inject
     */
    protected $registration = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Factory\RegistrationActionFactory
     * @inject
     */
    protected $actionFactory = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\RegistrationRepository
     * @inject
     */
    protected $registrationRepository = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Factory\RegistrationFactory
     * @inject
     */
    protected $registrationFactory = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager = null;

    /**
     * @var \Netcreators\NcExtbaseLib\Utility\FrontendUserUtility
     * @inject
     */
    protected $frontendUserUtility;

    /**
     * Initializes the manager. Cleans up sessions which were not closed correctly or in time.
     * @return void
     */
    public function initialize()
    {
        if ($this->localConfigurationManager->get('registration.enabled') == 0
            || $this->localConfigurationManager->get('flexform.registration.disabledForThisInstance') == 1
        ) {
            $this->setRegistrationRunning(false);
        } else {
            $this->validateConfiguration();
            $this->loadRegistration();
        }
    }

    /**
     * Validates current configuration settings, throws exceptions if not valid
     * @throws Exception\ExpiredResultNotSetException
     * @throws Exception\ExpiredResultDoesNotExistException
     * @throws NotInitializedException
     * @throws Exception\InvalidSessionTimeoutException
     * @return void
     */
    protected function validateConfiguration()
    {
        if ($this->localConfigurationManager == null) {
            throw new NotInitializedException('Configuration manager not injected');
        }
        // < 5 mins not really valid session duration
        if ((int)$this->localConfigurationManager->get('registration.sessionTimeout') <= 300) {
            throw new Exception\InvalidSessionTimeoutException();
        }
        // > 1 week hours is not really valid too
        if ((int)$this->localConfigurationManager->get('registration.sessionTimeout') > 7 * 24 * 60 * 60) {
            throw new Exception\InvalidSessionTimeoutException();
        }
        if ((int)$this->localConfigurationManager->get('registration.expiredResult') == 0) {
            throw new Exception\ExpiredResultNotSetException();
        }
        // check if the content element is available
        $registrationResult = $this->registrationResultRepository->findByUid(
            (int)$this->localConfigurationManager->get('registration.expiredResult')
        );
        if (!$registrationResult) {
            throw new Exception\ExpiredResultDoesNotExistException();
        }
    }

    /**
     * Checks if there is a registration running for the currently logged in user.
     * @return boolean
     */
    public function isRegistrationRunning()
    {
        return $this->registrationRunning;
    }

    /**
     * Sets the registration is running state.
     * @param boolean $running state
     * @return self for chaining
     */
    public function setRegistrationRunning($running)
    {
        $this->registrationRunning = $running;
        return $this;
    }

    /**
     * Tests if it is allowed (for the current user) to track registrations
     * @return boolean true if it is allowed
     */
    public function getRegistrationTrackingAllowed()
    {
        return $this->frontendUserUtility->isLoggedIn() && (boolean)$this->localConfigurationManager->get(
            'registration.enabled'
        );
    }

    /**
     * Was the registration just started.
     * @return boolean true if the registration just started.
     */
    public function registrationJustStarted()
    {
        return $this->getRunningTime() < 10;
    }

    /**
     * Returns the amount of time the registration is running.
     * @return \DateTime
     */
    public function getRunningTime()
    {
        $duration = $this->registration->getDuration();
        if ($duration == 0) {
            $duration = time() - $this->registration->getStartTime()->format('U');
        }
        return $duration;
    }

    /**
     * Did the registration take longer than the session timeout?
     * @return boolean true if the registration timed out
     */
    protected function isRegistrationExpired()
    {
        return $this->getRunningTime() > $this->localConfigurationManager->get('registration.sessionTimeout');
    }

    /**
     * Starts a new registration session
     * @param string $subject the subject of the registration
     * @return void
     */
    public function startNewRegistration($subject)
    {
        $newRegistration = $this->registrationFactory->build($subject);
        // chain registrations
        if ($this->isRegistrationRunning()) {
            $this->registration->setNextRegistration($newRegistration)
                ->setClosed(true);
            $this->registrationRepository->update($this->registration);
        } else {
            $this->registrationRepository->add($newRegistration);
        }
        $this->persistenceManager->persistAll(); // make sure the updates are saved first
        $this->setRegistration($newRegistration);
        $this->setRegistrationRunning(true);
    }

    /**
     * Saves the currently running registration.
     * @return void
     */
    public function storeRegistration()
    {
        if ($this->isRegistrationRunning()) {
            $this->registrationRepository->update($this->registration);
        }
    }

    /**
     * Stops the current registration and saves the result.
     * @param RegistrationResult $result
     * @param string $remark
     * @return void
     */
    public function stopRegistration(RegistrationResult $result, $remark)
    {
        if ($this->isRegistrationRunning()) {
            $this->registration->setRemark($remark)
                ->setEndTime(date_create())
                ->setResult($result)
                ->setClosed(true);
            $this->registrationRepository->update($this->registration);
        }
    }

    /**
     * Restores previously saved registration
     * @return void
     */
    public function loadRegistration()
    {
        $this->setRegistrationRunning(false);
        if ($this->frontendUserUtility->isLoggedIn()) {
            $registrations = $this->registrationRepository->findOpenRegistrationsForUser(
                $this->frontendUserUtility->findLoggedInUser()
            );
            $registration = $this->getActiveRegistration($registrations);
            $this->closeOpenRegistrations($registrations);
            $this->setRegistration($registration);
            if ($registration !== null) {
                if (!$this->isRegistrationExpired()) {
                    $this->setRegistrationRunning(true);
                } else {
                    $this->expireRegistration($registration);
                }
            }
        }
    }

    /**
     * Returns the currently active registration
     * @param array $registrations
     * @return Registration
     */
    public function getActiveRegistration($registrations)
    {
        $biggestTime = 0;
        $keep = null;
        if (count($registrations) > 0) {
            /** @var Registration $registration */
            foreach ($registrations as $registration) {
                $time = $registration->getStartTime();
                if ($time > $biggestTime) {
                    $biggestTime = $time;
                    $keep = $registration;
                }
            }
        }
        return $keep;
    }

    /**
     * Closes unclosed registrations, sets the most current registration active
     * @param array $registrations found registrations
     * @return void
     */
    public function closeOpenRegistrations($registrations)
    {
        $biggestTime = 0;
        $keep = null;
        foreach ($registrations as $registration) {
            /** @var Registration $registration */
            $time = $registration->getStartTime();
            if ($time > $biggestTime) {
                $biggestTime = $time;
                $keep = $registration;
            }
        }
        foreach ($registrations as $registration) {
            if ($registration !== $keep) {
                $this->expireRegistration($registration);
                $this->registrationRepository->update($registration);
            }
        }
    }

    /**
     * Expires the registration and closes it.
     * @param Registration &$registration
     * @return void
     */
    protected function expireRegistration(Registration &$registration)
    {
        // singleton
        // is there a better way to get this singleton?
        $registrationResult = $this->registrationResultRepository->findByUid(
            (int)$this->localConfigurationManager->get('registration.expiredResult')
        );
        $registration->setResult($registrationResult)
            ->setClosed(true)
            ->setEndTime(date_create())
            ->setRemark($registration->getRemark() . chr(10) . date('-- Y-m-d H:i --'));
    }

    /**
     * Sets the current registration object
     * @param Registration $registration the active registration
     * @return self for chaining
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
        return $this;
    }

    /**
     * Returns the currently active registration
     * @return Registration the registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * Registers the currenlty performed search as a registration step, if a registration is running
     * @param SearchParameter $search the search settings
     * @return void
     */
    public function registerSearch(SearchParameter $search)
    {
        if ($this->isRegistrationRunning()) {
            if ($search->searchIsEmpty()) {
                if ($this->localConfigurationManager->get('registration.registerEmptySearch') == true) {
                    $this->registration->addRegistrationAction(
                        $this->actionFactory->createSearchAction($search)
                    );
                }
            } else {
                $this->registration->addRegistrationAction(
                    $this->actionFactory->createSearchAction($search)
                );
            }
        }
    }

    /**
     * Registers the currently performed view of a product as a registration step, if a registration is running
     * @param Product $product the product being viewed
     * @return void
     */
    public function registerViewProduct(Product $product)
    {
        if ($this->isRegistrationRunning()) {
            $this->registration->addRegistrationAction(
                $this->actionFactory->createViewProductAction($product)
            );
        }
    }

    /**
     * Registers the currently viewed faq as a registration step, if a registration is running
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion the faq being viewed
     * @return void
     */
    public function registerViewFrequentlyAskedQuestion(
        FrequentlyAskedQuestion $frequentlyAskedQuestion
    ) {
        if ($this->isRegistrationRunning()) {
            $this->registration->addRegistrationAction(
                $this->actionFactory->createViewFrequentlyAskedQuestionAction($frequentlyAskedQuestion)
            );
        }
    }

    /**
     * Registers the currently viewed faq for a product as a registration step, if a registration is running
     * @param Product $product the product
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion the faq
     * @return void
     */
    public function registerViewFrequentlyAskedQuestionForProduct(
        Product $product,
        FrequentlyAskedQuestion $frequentlyAskedQuestion = null
    ) {
        if ($this->isRegistrationRunning()) {
            if ($frequentlyAskedQuestion == null) {
                $this->registerViewProduct($product);
            } else {
                $this->registration->addRegistrationAction(
                    $this->actionFactory->createViewFrequentlyAskedQuestionForProductAction(
                        $product,
                        $frequentlyAskedQuestion
                    )
                );
            }
        }
    }

    /**
     * Registers the currently viewed faq for a search as a registration step, if a registration is running
     * @param SearchParameter $search the search settings
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion the faq
     * @return void
     */
    public function registerViewFrequentlyAskedQuestionForSearch(
        SearchParameter $search,
        FrequentlyAskedQuestion $frequentlyAskedQuestion = null
    ) {
        if ($this->isRegistrationRunning()) {
            if ($frequentlyAskedQuestion == null) {
                $this->registerSearch($search);
            } else {
                if ($this->localConfigurationManager->get(
                        'registration.registerFrequentlyAskedQuestionForSearch'
                    ) == true
                ) {
                    $this->registration->addRegistrationAction(
                        $this->actionFactory->createViewFrequentlyAskedQuestionForSearchAction(
                            $search,
                            $frequentlyAskedQuestion
                        )
                    );
                }
            }
        }
    }
}

