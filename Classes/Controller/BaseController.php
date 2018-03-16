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

namespace Netcreators\NcgovPdc\Controller;

use Netcreators\NcgovPdc\Domain\Service\FrequentlyAskedQuestionDomainService;
use Netcreators\NcgovPdc\Service\Registration\RegistrationManager;
use Netcreators\NcgovPdc\Service\Statistics\StatisticsManager;

/**
 * BaseController
 *
 * @package NcgovPdc
 * @subpackage Controller
 */
class BaseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var \Netcreators\NcExtbaseLib\Utility\FrontendUserUtility
     * @inject
     */
    protected $frontendUserUtility;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\FrequentlyAskedQuestionRepository
     * @inject
     */
    protected $frequentlyAskedQuestionRepository;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\ProductRepository
     * @inject
     */
    protected $productRepository;

    /**
     * @var \Netcreators\NcExtbaseLib\Domain\Repository\LogRepository
     * @inject
     */
    protected $logRepository;

    /**
     * @var \Netcreators\NcgovPdc\Configuration\ConfigurationManager
     * @inject
     */
    protected $localConfigurationManager;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Service\FrequentlyAskedQuestionDomainService
     * @inject
     */
    protected $frequentlyAskedQuestionService;

    /**
     * @var \Netcreators\NcgovPdc\Service\Registration\RegistrationManager
     * @inject
     */
    protected $registrationManager;

    /**
     * @var \Netcreators\NcgovPdc\Service\Statistics\StatisticsManager
     * @inject
     */
    protected $statisticsManager;

    /**
     * @var boolean
     */
    private $skipBaseConfigurationValidation = true;

    /**
     * Returns the default configuration for this extension
     * @return array
     */
    public static function getDefaultConfiguration()
    {
        return array(
            'flexform' => array(
                'registration' => array(
                    'disabledForThisInstance' => false,
                ),
                'statistics' => array(
                    'disabledForThisInstance' => false,
                ),
            ),
            'database' => array(
                'FrequentlyAskedQuestion' => array(
                    'searchableColumns' => 'question, short_answer, answer',
                ),
                'Product' => array(
                    'searchableColumns' => 'description',
                    'userAvailability' => array(
                        'useUsersAvailableColumn' => false,
                        'lookupTable' => 'fe_users',
                        'orderBy' => 'fe_users.name',
                        'columnToDetermineAvailability' => 'tx_ncgovpdc_availability_status',
                        'columnStatusesAvailable' => array(
                            'noMatch' => array(
                                'description' => 'Onbekend',
                                'icon' => 'EXT:ncgov_pdc/Resources/Public/Icons/status_unknown.jpg',
                            ),
                            'available' => array(
                                'match' => '/IN/',
                                'description' => 'Aanwezig',
                                'icon' => 'EXT:ncgov_pdc/Resources/Public/Icons/status_available.jpg',
                            ),
                            'unavailable' => array(
                                'match' => '/OUT/',
                                'description' => 'Afwezig',
                                'icon' => 'EXT:ncgov_pdc/Resources/Public/Icons/status_unavailable.jpg',
                            ),
                            'busy' => array(
                                'match' => '/BUSY/',
                                'description' => 'Bezet',
                                'icon' => 'EXT:ncgov_pdc/Resources/Public/Icons/status_busy.jpg',
                            ),
                        ),
                        'displayColumns' => 'name, phone, www',
                    ),
                ),
            ),
            'wordsNotRelevantForSearch' => 'is,de,een,het,met,wat,en,ik,kan',
            'controllers' => array(
                'Product' => array(
                    'title' => array(
                        'useRecordAsPageTitle' => true,
                        'prefix' => '',
                        'postfix' => ''
                    ),
                    'detail' => array(
                        'useTemplate' => '',
                        'image' => array(
                            'maxWidth' => 100,
                            'maxHeight' => 100,
                        ),
                    ),
                    'detailForPreconfiguredProduct' => array(
                        'preconfiguredProduct' => false,
                        // FIXME These should all be NULL! Check if there is a "=== FALSE" used anywhere!
                    ),
                    'dieOnOutputXml' => true,
//					For use with Resources/Private/Templates/Product/Detail.sdu.html:
//					combinedProperties {
//						description = hasFullDescription,hasFullTerms,referenceLaws
//						laws = hasFullTerms,referenceLaws,referenceLocalLaws
//						other = referenceExternal,attachments,requestForm,source,relatedRegulatory
//					}
                    'combinedProperties' => false,
                    'limitResultsToThisAudience' => false,
                    'maximumNumberOfFrequentlyAskedQuestionsToShow' => false,
                    'restrictAccessToTips' => false,
                    'actions' => array(
                        'publishXml' => array(
                            'OWMS' => array(
                                'core' => array(
                                    'spatial' => array(
                                        'scheme' => 'overheid:Gemeente'
                                    ),
                                    'authority' => array(
                                        'scheme' => 'overheid:Gemeente'
                                    )
                                )
                            )
                        )
                    )
                ),
                'FrequentlyAskedQuestion' => array(
                    'activeChannels' => 'generiek,website',
                    'showDestinations' => '',
                    'title' => array(
                        'useRecordAsPageTitle' => true,
                        'prefix' => '',
                        'postfix' => ''
                    ),
                    'actions' => array(
                        'find' => array(
                            'location' => '',
                            'showSingleFrequentlyAskedQuestion' => false,
                            // results count
                            'maxFrequentlyAskedQuestionResultCount' => false,
                            'maxFrequentlyAskedQuestionKeywordResultCount' => false,
                            'maxFrequentlyAskedQuestionProductResultCount' => false,
                            'maxFrequentlyAskedQuestionSynonymResultCount' => false,
                            'maxSamenwerkendeCatalogiResultCount' => false,
                            'maxProductResultCount' => false,
                            'maxResultPagesCount' => false,
                            // show own searchbox?
                            'showSearchBox' => true,
                            // needed for searching using another searchbox
                            'otherSearchFormElement' => false,
                            'otherSearchFormMethodIsPost' => false,
                            //
                            'logSearchTimes' => false,
                            'searchOptions' => array(
                                'display' => array(
                                    'matchExactPhrase' => false,
                                    'includePrivateResults' => true,
                                    'includeBusinessResults' => true,
                                    'includeRemoteProducts' => true,
                                ),
                                'defaultValues' => array(
                                    'matchExactPhrase' => false,
                                    'includePrivateResults' => true,
                                    'includeBusinessResults' => true,
                                    'includeRemoteProducts' => false,
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'registration' => array(
                'sessionTimeout' => 7200,
                'expiredResult' => false,
                'registerFrequentlyAskedQuestionForSearch' => true,
                'enabled' => false,
                'registerEmptySearch' => false,
            ),
            'statistics' => array(
                'className' => StatisticsManager::class,
                'enabled' => false,
                'timestampFormat' => 'Ym',
                'topViewed' => array(
                    'numberOfItems' => 10,
                    'timeOffset' => '-1 month'
                ),
                'top' => array( //
                ),
            ),
            'produceCleanUrls' => true,
            'debug' => false,
            'contentMyQuestionWasNotAnswered' => false,
            'contentNoResultsFound' => false,
            'contentPoseQuestion' => false,
            'minimumNumberOfNodeCount' => 5,
            'import' => array(
                'minimumNumberOfRecordsSafetyLimit' => 50,
                'faq' => array(
                    'forceFullUpdate' => false,
                    'useCurl' => false,
                    'type' => 'xml',
                    'pagesToClearCacheFor' => '',
                    'maximumNumberOfRecordsToProcessLimit' => 150,
//					'defaultUserGroups' => '', // KCC or PDC group ID
                    'xml' => array(
                        'excludedItems' => '',
                        'skipDownload' => false,
                        'url' => false,
                        'tempFile' => 'typo3temp/FaqImport.xml',
                        'isPartialImport' => false,
                        'useAuthentication' => false,
                        'username' => '',
                        'password' => '',
                    ),
                ),
            ),
            'samenwerkendeCatalogi' => array(
                'debug' => false,
                'use' => true,
                'spatial' => array(
                    'spatial' => '',
                    'scheme' => 'overheid:Andere',
                    'resourceIdentifier' => ''
                ),
                'authority' => array(
                    'authority' => '',
                    'scheme' => 'overheid:Andere',
                    'resourceIdentifier' => ''
                )
            ),
            'pages' => array(
                'productAZIndexPage' => false,
                'themeDetailPage' => false,
                'productNotAvailablePage' => '404/',
                'productDetailPage' => false,
                'frequentlyAskedQuestionDetailPage' => false,
                'closeRegistrationPage' => false,
                'searchPage' => false,
            ),
            'persistence' => array(
                'storagePid' => false,
            ),
        );
    }

    /**
     * Initializes the current action
     *
     * @return void
     */
    public function initializeBase()
    {
        date_default_timezone_set('Europe/Amsterdam');

        $this->logRepository->createSession();

        $this->localConfigurationManager->initialize(
            $this->settings,
            self::getDefaultConfiguration(),
            $this->request->getControllerExtensionKey(),
            array(
                'database.Product.userAvailability.columnStatussuesAvailable',
                'controllers.Product.combinedProperties',
                'statistics.top',
                'additionalDataProviders',
            )
        );

        // FIXME: Inject instances of classes which are not singletons (or factories, for that matter)?

        /** @var RegistrationManager */
        $this->registrationManager = $this->objectManager->get(RegistrationManager::class);
        $this->registrationManager->initialize();

        $this->statisticsManager = $this->objectManager->get(
            $this->localConfigurationManager->get('statistics.className')
        );
        $this->statisticsManager->initialize();

        $this->validateBaseConfiguration();
        $this->validateConfiguration();

        // FIXME: Make FAQService a singleton? The amount of state it has seems to make it discardable, but only one instance is used per Controller lifetime. (Seems like no good reason to make this a Singleton, really.)

        /** @var FrequentlyAskedQuestionDomainService */
        $this->frequentlyAskedQuestionService = $this->objectManager->get(FrequentlyAskedQuestionDomainService::class);
        $this->frequentlyAskedQuestionService->setDebug($this->localConfigurationManager->get('debug'));
    }

    /**
     * Validates the base configuration for all views.
     * @return void
     */
    public function validateBaseConfiguration()
    {
    }

    /**
     * Should the base validation be skipped?
     * @param boolean $newState true if the validation should be skipped
     * @return self instance for chaining
     */
    protected function setSkipBaseConfigurationValidation($newState)
    {
        $this->skipBaseConfigurationValidation = $newState;
        return $this;
    }

    /**
     * Returns if the base validation should be skipped.
     * @return boolean
     */
    protected function getSkipBaseConfigurationValidation()
    {
        return $this->skipBaseConfigurationValidation;
    }

    /**
     * Validates configuration for local install, throws exception when not valid
     * @return void
     */
    protected function validateConfiguration()
    {
    }

    /**
     * Returns the usergroups for the currently logged in user.
     * @return mixed usergroups or NULL
     */
    protected function getUsergroups()
    {
        $groups = null;
        if ($this->frontendUserUtility->isLoggedIn()) {
            $user = $this->frontendUserUtility->findLoggedInUser();
            if ($user) {
                $groups = $user->getUsergroup();
            }
        }
        return $groups;
    }

    /**
     * Makes it possible to handle exceptions thrown by the action. Also adds a shutdown method
     * @see \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::callActionMethod()
     */
    protected function callActionMethod()
    {
        parent::callActionMethod();
        $this->shutdownAction();
    }

    /**
     * Action to be called after the main action was run.
     */
    protected function shutdownAction()
    {
        $this->registrationManager->storeRegistration();
        $this->logRepository->commitLog();
    }
}

