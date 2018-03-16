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

namespace Netcreators\NcgovPdc\Domain\Service;

use Netcreators\NcgovPdc\Domain\Model\Destination;
use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;

/**
 * FrequentlyAskedQuestion filtering service
 *
 * @package NcgovPdc
 * @subpackage Domain/Service
 */
class FrequentlyAskedQuestionDomainService
{ // FIXME: Should this possibly implement \TYPO3\CMS\Core\SingletonInterface? Unfortunately it's very stateful. :/
    /**
     * @var \Netcreators\NcgovPdc\Configuration\ConfigurationManager
     * @inject
     */
    protected $localConfigurationManager = null;

    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     * @inject
     */
    protected $uriBuilder = null;

    /**
     * @var array
     */
    protected $targetAudience = array();

    /**
     * @var array
     */
    protected $authority = array();

    /**
     * @var array
     */
    protected $destinations = array();

    /**
     * @var array
     */
    protected $activeChannels = array();

    /**
     * @var \Netcreators\NcExtbaseLib\Domain\Repository\LogRepository
     * @inject
     */
    protected $logRepository;

    /**
     * @var boolean
     */
    protected $debug = false;

    /**
     * @var integer
     */
    protected $maximumNumberOfFrequentlyAskedQuestionsToShow = false;

    /**
     * Perform debug logs?
     * @param boolean $state
     */
    public function setDebug($state)
    {
        $this->debug = $state;
    }

    /**
     * Sets the target audience
     * @param array $audience
     */
    public function setTargetAudience($audience)
    {
        $this->targetAudience = $audience;
    }

    /**
     * Sets the authority for which to show the faq
     * @param array $authority
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;
    }

    /**
     * Sets the active channels for which to show the faq
     * @param array $channels
     */
    public function setActiveChannels($channels)
    {
        $this->activeChannels = $channels;
    }

    /**
     * Sets the target destinations
     * @param array $destinations
     */
    public function setDestinations($destinations)
    {
        $this->destinations = $destinations;
    }

    /**
     * Sets the maximum number of faq
     * @param mixed $max
     */
    public function setMaximumNumberOfFrequentlyAskedQuestionsToShow($max)
    {
        $this->maximumNumberOfFrequentlyAskedQuestionsToShow = (int)$max;
    }

    /**
     * Returns the answers which are in the selected destination collection
     * @param array &$answers the answers
     */
    public function removeFrequentlyAskedQuestionsOutsideDestinations(&$answers)
    {
        if (!(is_array($this->destinations) || $this->destinations instanceof \Traversable)
            || !(is_array($answers) || $answers instanceof \Traversable)
        ) {
            return;
        }

        if (!count($this->destinations) || !count($answers)) {
            return;
        }

        /** @var FrequentlyAskedQuestion $answer */
        foreach ($answers as $index => $answer) {
            if ($answer->getImported() && !$answer->containsDestinations($this->destinations)) {

                if ($this->debug) {
                    $this->log(
                        'Removing answer because of not in destination (' . implode(',', $this->destinations) . ')',
                        $answer
                    );
                }
                unset($answers[$index]);
            }
        }
    }

    /**
     * Returns the answers which are in the selected destination collection
     * @param array &$answers the answers
     */
    public function removeFrequentlyAskedQuestionsNotPublished(&$answers)
    {
        if (!(is_array($answers) || $answers instanceof \Traversable)) {
            return;
        }

        $today = new \DateTime();

        /** @var FrequentlyAskedQuestion $answer */
        foreach ($answers as $index => $answer) {

            if (($answer->getOwmsMantleAvailableStart() && $answer->getOwmsMantleAvailableStart()->format(
                        'U'
                    ) != 0 && $answer->getOwmsMantleAvailableStart() > $today)
                || ($answer->getOwmsMantleAvailableEnd() && $answer->getOwmsMantleAvailableEnd()->format(
                        'U'
                    ) != 0 && $answer->getOwmsMantleAvailableEnd() < $today)
            ) {

                if ($this->debug) {
                    $this->log('Removing answer because of date (' . $today->format('r') . ')', $answer);
                }

                unset($answers[$index]);
            } else {
                if ($answer->getImported() && $answer->getEditorialState() == 'non-actief') {

                    if ($this->debug) {
                        $this->log('Removing answer because of editorial state (non-actief)', $answer);
                    }
                    unset($answers[$index]);
                }
            }
        }
    }

    /**
     * Returns the answers which are for the set channels
     * @param array &$answers the answers
     */
    public function removeFrequentlyAskedQuestionsWithoutActiveChannels(&$answers)
    {
        if (!(is_array($answers) || $answers instanceof \Traversable)) {
            return;
        }

        /** @var FrequentlyAskedQuestion $answer */
        foreach ($answers as $index => $answer) {

            $answer->setActiveChannels($this->activeChannels);
            $answers[$index] = $answer;

            if (!$answer->containsAnyChannel($this->activeChannels)) {
                if ($this->debug) {
                    $this->log(
                        'Removing answer because of not in channel (' . implode(',', $this->activeChannels) . ')',
                        $answer
                    );
                }
                unset($answers[$index]);
            }
        }
    }

    /**
     * Removes frequently asked questions which are not in scope.
     * @param array &$answers the answers
     */
    public function removeFrequentlyAskedQuestionsOutOfScope(&$answers)
    {
        $this->removeFrequentlyAskedQuestionsOutsideDestinations($answers);
        $this->removeFrequentlyAskedQuestionsNotHavingAuthority($answers);
        if (count($this->targetAudience)) {
            $this->removeFrequentlyAskedQuestionsNotInAudience($answers);
        }
        $this->removeFrequentlyAskedQuestionsNotPublished($answers);
        $this->removeFrequentlyAskedQuestionsWithoutActiveChannels($answers);
    }

    /**
     * Removes frequently asked questions which are not in range (set by setMaximumNumberOfFrequentlyAskedQuestionsToShow).
     * @param array &$answers the answers
     */
    public function removeFrequentlyAskedQuestionsOutOfRange(&$answers)
    {
        if ($this->maximumNumberOfFrequentlyAskedQuestionsToShow != false) {
            $answers = array_slice($answers, 0, $this->maximumNumberOfFrequentlyAskedQuestionsToShow);
        }
    }

    /**
     * Removes faq entries not in authority.
     * @param array &$answers
     */
    public function removeFrequentlyAskedQuestionsNotHavingAuthority(&$answers)
    {
        if (!(is_array($answers) || $answers instanceof \Traversable)) {
            return;
        }

        /** @var FrequentlyAskedQuestion $answer */
        foreach ($answers as $index => $answer) {

            $answerAuthorities = $answer->getAuthorities();
            if (!$answerAuthorities || !count($answerAuthorities)) {
                continue;
            }

            $containsAuthority = false;
            if (is_array($this->authority) || $this->authority instanceof \Traversable) {
                foreach ($this->authority as $authority) {

                    if ($answer->containsAuthority($authority)) {
                        $containsAuthority = true;
                        break;
                    }
                }
            }
            if (!$containsAuthority) {
                if ($this->debug) {
                    $this->log(
                        'Removing answer because missing authority (' . $this->getAuthoritiesList() . ')',
                        $answer
                    );
                }
                unset($answers[$index]);
            }
        }

    }

    /**
     * Returns the authority as a list
     * @return array
     */
    protected function getAuthoritiesList()
    {
        $list = array();
        /** @var \Netcreators\NcgovPdc\Domain\Model\Authority $authority */
        foreach ($this->authority as $authority) {
            $list[] = $authority->getTitle();
        }
        return implode(',', $list);
    }

    /**
     * Removes the answers which are not in the specified audience.
     * If BOTH audiences are set or NO audiences are set will return ALL results
     * If one audience is set, will remove the other
     * @param array $answers the answers
     * @return void
     */
    public function removeFrequentlyAskedQuestionsNotInAudience(&$answers)
    {
        if (count($this->targetAudience) != 1) {
            return;
        }

        // only one audience selected, remove other
        if (!(is_array($answers) || $answers instanceof \Traversable)) {
            return;
        }

        /** @var FrequentlyAskedQuestion $answer */
        foreach ($answers as $index => $answer) {
            $audience = $answer->getOwmsMantleAudience();
            // if has no audience, show.
            // if has both audiences, show.
            if (count($audience) == 1) {
                if ($this->targetAudience[0] != $audience[0]) {

                    if ($this->debug) {
                        $this->log(
                            'Removing answer because not in audience (' . implode(',', $this->targetAudience) . ')',
                            $answer
                        );
                    }
                    unset($answers[$index]);
                }
            }
        }
    }

    /**
     * Sets the currently frequentlyAskedQuestion active
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion reference to frequently asked question
     * @return void
     */
    public function activateFrequentlyAskedQuestion(FrequentlyAskedQuestion &$frequentlyAskedQuestion = null)
    {
        if (!$frequentlyAskedQuestion) {
            return;
        }

        $frequentlyAskedQuestion->setIsActive()
            ->setTargetPageIdentifier(
                $this->localConfigurationManager->get('pages.frequentlyAskedQuestionDetailPage'),
                null
            )
            ->setReferencesCleanUrl($this->uriBuilder);

        if ($this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.title.useRecordAsPageTitle')) {
            // set pagetitle
            $GLOBALS['TSFE']->page['title'] = $frequentlyAskedQuestion->getOwmsCoreTitle();
            // set pagetitle for indexed search to news title
            $GLOBALS['TSFE']->indexedDocTitle = $frequentlyAskedQuestion->getOwmsCoreTitle();
        }
    }

    /**
     * Logs a message for the given faq
     * @param string $message
     * @param FrequentlyAskedQuestion $answer
     */
    public function log($message, $answer)
    {
        $destinationNames = array();
        $destinations = $answer->getDestinations();
        /** @var Destination $destination */
        foreach ($destinations as $destination) {
            $destinationNames[] = $destination->getName();
        }

        $authorityNames = array();
        /** @var \Netcreators\NcgovPdc\Domain\Model\Authority $authority */
        foreach ($answer->getAuthorities() as $authority) {
            $authorityNames[] = $authority->getTitle();
        }

        $log = 'Not showing faq [' . $answer->getUid() . ']: ' . $answer->getOwmsCoreTitle() . chr(10);
        $log .= $message . chr(10);
        $log .= 'Answer: StartDate = ' . $answer->getOwmsMantleAvailableStart()->format('r');
        $log .= '; EndDate = ' . $answer->getOwmsMantleAvailableEnd()->format('r');
        $log .= '; Destinations = ' . implode(',', $destinationNames);
        $log .= '; EditorialState = ' . $answer->getEditorialState();
        $log .= '; Channels = ' . implode(',', $answer->getContainedChannelTypes());
        $log .= '; Audience = ' . implode(',', $answer->getOwmsMantleAudience());
        $log .= '; Authorities = ' . implode(',', $authorityNames);

        $this->logRepository->log($log, time());
    }
}

