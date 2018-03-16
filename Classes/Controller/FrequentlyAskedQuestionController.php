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

use Netcreators\NcExtbaseLib\Utility\ControllerUtility;
use Netcreators\NcgovPdc\Domain\Search\SearchParameter;
use Netcreators\NcgovPdc\Domain\Search\SearchParameterFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FrequentlyAskedQuestionController
 *
 * @package NcgovPdc
 * @subpackage Controller
 */
class FrequentlyAskedQuestionController extends BaseController
{

    /**
     * @var \Netcreators\NcExtbaseLib\Utility\FrontendUserUtility
     * @inject
     */
    protected $frontendUserUtility;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\SamenwerkendeCatalogi40\RemoteProductRepository
     * @inject
     */
    protected $remoteProductRepository = null;

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $localContentObject = false;

    /**
     * @var boolean
     */
    protected $hasMoreResults = false;

    /**
     * @var array
     */
    protected $time = array();

    /**
     * Initializes the current action
     *
     * @return void
     */
    public function initializeAction()
    {
        $this->initializeBase();
        $this->localContentObject = false;
        $this->productRepository->setSearchableColumns(
            GeneralUtility::trimExplode(
                ',',
                $this->localConfigurationManager->get('database.Product.searchableColumns')
            )
        );
        $this->frequentlyAskedQuestionRepository->setSearchableColumns(
            GeneralUtility::trimExplode(
                ',',
                $this->localConfigurationManager->get('database.FrequentlyAskedQuestion.searchableColumns')
            )
        );
    }

    /**
     * Shows one question.
     * @param    \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion the faq
     * @param    \Netcreators\NcgovPdc\Domain\Model\Product $product related product (if any)
     * @return    void
     */
    public function detailAction(
        \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion = null,
        \Netcreators\NcgovPdc\Domain\Model\Product $product = null
    ) {

        /**
         * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE
         */
        global $TSFE;

        if (!$frequentlyAskedQuestion) {
            $TSFE->pageNotFoundAndExit('Veelgestelde vraag- antwoord combinatie niet gevonden.');
        }

        $this->registrationManager->registerViewFrequentlyAskedQuestion($frequentlyAskedQuestion);
        $this->statisticsManager->registerViewFrequentlyAskedQuestion($frequentlyAskedQuestion);

        $destinations = $this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.showDestinations');
        if ($destinations != '') {
            $this->frequentlyAskedQuestionService->setDestinations(
                GeneralUtility::trimExplode(',', $destinations)
            );
        }

        $this->frequentlyAskedQuestionService->setActiveChannels(
            GeneralUtility::trimExplode(
                ',',
                $this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.activeChannels')
            )
        );

        $this->frequentlyAskedQuestionService->setAuthority($this->getUsergroups());

        $answers = array($frequentlyAskedQuestion);
        $this->frequentlyAskedQuestionService->removeFrequentlyAskedQuestionsOutOfScope($answers);

        $showQuestion = true;
        if (!count($answers)) {
            $showQuestion = false;
        } else {
            if ($this->localConfigurationManager->get(
                'controllers.FrequentlyAskedQuestion.title.useRecordAsPageTitle'
            )
            ) {
                $prefix = $this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.title.prefix');
                if ($prefix) {
                    $prefix .= ' ';
                }
                $postfix = $this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.title.postfix');
                if ($postfix) {
                    $postfix = ' ' . $postfix;
                }
                // set pagetitle
                $GLOBALS['TSFE']->page['title'] = $prefix . $frequentlyAskedQuestion->getOwmsCoreTitle() . $postfix;
                $GLOBALS['TSFE']->config['config']['noPageTitle'] = false;
                $GLOBALS['TSFE']->config['config']['pageTitleFirst'] = true;
                $GLOBALS['TSFE']->config['config']['pageTitleSeparator'] = ' -';

                // set pagetitle for indexed search
                $GLOBALS['TSFE']->indexedDocTitle = $prefix . $frequentlyAskedQuestion->getOwmsCoreTitle() . $postfix;
            }
        }
        $frequentlyAskedQuestion->setTargetPageIdentifier(
            $this->localConfigurationManager->get('pages.frequentlyAskedQuestionDetailPage'),
            $this->localConfigurationManager->get('pages.productDetailPage')
        );
        $frequentlyAskedQuestion->setCleanUrl($this->localConfigurationManager->get('produceCleanUrls'));
        $frequentlyAskedQuestion->setReferencesCleanUrl();

        if ($product != null) {
            $this->view->assign('product', $product);
        }
        $this->view->assign('frequentlyAskedQuestionContainer', $frequentlyAskedQuestion);

        $this->assignReferenceFrequentlyAskedQuestions($frequentlyAskedQuestion);

        $this->view->assign('showQuestion', $showQuestion);
        $this->view->assign('userAuthorisedAndLoggedIn', !is_null($this->frontendUserUtility->findLoggedInUser()));
    }

    /**
     * Assigns the FrequentlyAskedQuestion's referenced frequently asked questions to the View, taking into account
     * the appropriate filtering for destination, start and end date etc.
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return void
     */
    protected function assignReferenceFrequentlyAskedQuestions(
        \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion
    ) {

        $faqReferenceLinks = $frequentlyAskedQuestion->getReferenceFrequentlyAskedQuestionsFiltered();
        $referencedFrequentlyAskedQuestions = array();

        /** @var \Netcreators\NcgovPdc\Domain\Model\ReferenceLink $faqReferenceLink */
        foreach ($faqReferenceLinks as $faqReferenceLink) {
            $referencedFrequentlyAskedQuestions[] = $faqReferenceLink->getLinkFrequentlyAskedQuestion();
        }

        // Apply FAQ filters. (Filters have been set up by FrequentlyAskedQuestionController::detailAction().)

        $this->frequentlyAskedQuestionService->removeFrequentlyAskedQuestionsOutOfScope(
            $referencedFrequentlyAskedQuestions
        );

        // Keep only references to FAQs which have not been removed by the filtering process.

        $referencedFrequentlyAskedQuestionIds = array_map(
            function (\Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $referencedFrequentlyAskedQuestion) {
                return $referencedFrequentlyAskedQuestion->getUid();
            },
            $referencedFrequentlyAskedQuestions
        );

        $validFaqReferenceLinks = array_filter(
            $faqReferenceLinks,
            function (\Netcreators\NcgovPdc\Domain\Model\ReferenceLink $faqReferenceLink) use (
                $referencedFrequentlyAskedQuestionIds
            ) {

                if (!$faqReferenceLink->getLinkFrequentlyAskedQuestion()) {
                    return false;
                }
                return in_array(
                    $faqReferenceLink->getLinkFrequentlyAskedQuestion()->getUid(),
                    $referencedFrequentlyAskedQuestionIds
                );
            }

        );

        $this->view->assign('hasValidReferenceFrequentlyAskedQuestions', count($validFaqReferenceLinks) > 0);
        $this->view->assign('referenceFrequentlyAskedQuestions', $validFaqReferenceLinks);
    }

    /**
     * Action called when the question was not answered for the user.
     * @return void
     */
    public function myQuestionWasNotAnsweredAction()
    {
        $this->view->assign(
            'contentMyQuestionWasNotAnswered',
            $this->getContentObject('contentMyQuestionWasNotAnswered')
        );
    }

    /**
     * Action called to show the top X of frequently asked questions
     * @return void
     */
    public function topViewedFrequentlyAskedQuestionsAction()
    {
        $this->view->assign(
            'topViewedFrequentlyAskedQuestions',
            $this->statisticsManager->getTopViewedFrequentlyAskedQuestions()
        );
    }

    /**
     * Forwards the request to another action and / or controller.
     *
     * NOTE: This method only supports web requests and will thrown an exception
     * if used with other request types.
     *
     * @param string $actionName Name of the action to forward to
     * @param string $controllerName Unqualified object name of the controller to forward to. If not specified, the current controller is used.
     * @param string $extensionName Name of the extension containing the controller to forward to. If not specified, the current extension is assumed.
     * @param array|\TYPO3\CMS\Extbase\Mvc\Controller\Arguments $arguments Arguments to pass to the target action
     * @param integer $pageUid Target page uid. If NULL, the current page uid is used
     * @param integer $delay (optional) The delay in seconds. Default is no delay.
     * @param integer $statusCode (optional) The HTTP status code for the redirect. Default is "303 See Other"
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @return void
     */
    protected function redirect(
        $actionName,
        $controllerName = null,
        $extensionName = null,
        array $arguments = null,
        $pageUid = null,
        $delay = 0,
        $statusCode = 303
    ) {
        if (!$this->request instanceof \TYPO3\CMS\Extbase\Mvc\Web\Request) {
            throw new \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException('redirect() only supports web requests.', 1220539734);
        }

        if ($controllerName === null) {
            $controllerName = $this->request->getControllerName();
        }
        if ($pageUid === null && isset($GLOBALS['TSFE'])) {
            $pageUid = $GLOBALS['TSFE']->id;
        }

        $uri = $this->uriBuilder
            ->reset()
            ->setUseCacheHash(false)
            ->setTargetPageUid($pageUid)
            ->uriFor($actionName, $arguments, $controllerName, $extensionName);
        $this->redirectToURI($uri, $delay, $statusCode);
    }

    /**
     * A special action which is called if the originally intended action could
     * not be called, for example if the arguments were not valid.
     *
     * @return string
     */
    protected function errorAction()
    {
        $message = 'An error occurred while trying to call ' . get_class(
                $this
            ) . '->' . $this->actionMethodName . '().' . PHP_EOL;
        foreach ($this->argumentsMappingResults->getErrors() as $error) {
            $message .= 'Error:   ' . $error . PHP_EOL;
        }
        foreach ($this->argumentsMappingResults->getWarnings() as $warning) {
            $message .= 'Warning: ' . $warning . PHP_EOL;
        }
        return $message;
    }

    /**
     * Performs search for products / Frequently Asked Questions
     * @param \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return void
     */
    public function findAction(
        \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion = null
    ) {
        $this->pushTime('start');
        if ((boolean)$this->localConfigurationManager->get(
            'controllers.FrequentlyAskedQuestion.actions.find.showSingleFrequentlyAskedQuestion'
        )
        ) {
            // forward to detail page if configured to
            $this->forward(
                'detail',
                'FrequentlyAskedQuestion',
                $this->request->getControllerExtensionName(),
                $this->request->getArguments()
            );
        }
        /** @var SearchParameter $parameter */
        $parameter = $this->createSearchParameter();
        $this->registrationManager->registerViewFrequentlyAskedQuestionForSearch($parameter, $frequentlyAskedQuestion);
        $this->statisticsManager->registerViewFrequentlyAskedQuestionForSearch($parameter, $frequentlyAskedQuestion);
        $this->pushTime('searching ' . $parameter->getSearchQuery());
        $noResults = true;

        if (!$parameter->searchIsEmpty()) {
            // starts registration if configured && allowed
            if ($this->registrationManager->getRegistrationTrackingAllowed(
                ) && !$this->registrationManager->isRegistrationRunning()
            ) {
                $this->registrationManager->startNewRegistration($parameter->getSearchQuery());
            }
            // gets the required answers
            $results = $this->findProductsAndAnswers($parameter);
            $products = $results['products'];
            $answers = $results['answers'];
            // gets products from Samenwerkende Catalogi
            $remoteProducts = $this->findRemoteProducts($parameter);
            // activates the current question
            $this->frequentlyAskedQuestionService->activateFrequentlyAskedQuestion($frequentlyAskedQuestion);

            $noResults = count($products) == 0 && count($answers) == 0 && count($remoteProducts) == 0;
            $this->view->assign('frequentlyAskedQuestions', $answers);
            $this->view->assign('products', $products);
            $this->view->assign('remoteProducts', $remoteProducts);
        }
        $this->pushTime('done searching');
        foreach ($parameter->getProperties() as $name => $value) {
            $this->view->assign($name, $parameter->getProperty($name));
        }
        $this->view->assign('contentPoseQuestion', $this->getContentObject('contentPoseQuestion'));
        $this->view->assign('contentNoResultsFound', $this->getContentObject('contentNoResultsFound'));

        $this->view->assign('noResults', $noResults);
        $this->view->assign('searchIsEmpty', $parameter->searchIsEmpty());
        $this->view->assign('hasMoreResults', $this->hasMoreResults);

        $this->view->assign('userAuthorisedAndLoggedIn', $this->frontendUserUtility->isLoggedIn());

        $this->view->assign(
            'displaySearchOptionMatchExactPhrase',
            (boolean)intval(
                $this->localConfigurationManager->get(
                    'controllers.FrequentlyAskedQuestion.actions.find.searchOptions.display.matchExactPhrase'
                )
            )
        );
        $this->view->assign(
            'displaySearchOptionIncludePrivateResults',
            (boolean)intval(
                $this->localConfigurationManager->get(
                    'controllers.FrequentlyAskedQuestion.actions.find.searchOptions.display.includePrivateResults'
                )
            )
        );
        $this->view->assign(
            'displaySearchOptionIncludeBusinessResults',
            (boolean)intval(
                $this->localConfigurationManager->get(
                    'controllers.FrequentlyAskedQuestion.actions.find.searchOptions.display.includeBusinessResults'
                )
            )
        );
        $this->view->assign(
            'displaySearchOptionIncludeRemoteProducts',
            (boolean)intval(
                $this->localConfigurationManager->get(
                    'controllers.FrequentlyAskedQuestion.actions.find.searchOptions.display.includeRemoteProducts'
                )
            )
        );

        if ($this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.actions.find.logSearchTimes')) {
            $this->logSearchTimes();
        }
    }

    /**
     * Pushes current timing info onto timer stack
     * @param string $name name of the entry
     * @return void
     */
    protected function pushTime($name)
    {
        if (!isset($this->time)) {
            $this->time = array();
        }
        $this->time[] = array('name' => $name, 'microtime' => microtime());
    }

    /**
     * Logs the timing stack.
     * @return void
     */
    protected function logSearchTimes()
    {
        $lines = array();
        foreach ($this->time as $time) {
            $microTime = explode(' ', $time['microtime']);
            $lines[] = $time['name'] . ':' . date('Y-m-d H:i:s', $microTime[1]) . ' msec ' . $microTime[0];
        }
        $this->logRepository->log(implode(chr(10), $lines), time());
    }

    /**
     * Performs remote product search
     * @param    SearchParameter $parameter search parameters
     * @return    array                                the remote products found
     */
    protected function findRemoteProducts(SearchParameter $parameter)
    {
        $remoteProducts = array();
        if ($this->localConfigurationManager->get('samenwerkendeCatalogi.use') && $parameter->getIncludeRemoteProducts(
            )
        ) {
            $this->pushTime('searching remote products');

            $remoteProducts = $this->remoteProductRepository->findBySearch($parameter);

            $this->pushTime('/searching remote products');
        }
        return $remoteProducts;
    }

    /**
     * Performs the search.
     * @param SearchParameter $parameter the search parameters
     * @return array
     */
    protected function findProductsAndAnswers(SearchParameter $parameter)
    {
        $results = array();
        $weakProductResults = array(); // products found through synonym or otherwise
        $strongProductResults = array(); // products found through name or synonym

        $audiences = array();
        if ($parameter->includeBusinessResults()) {
            $audiences[] = 'ondernemer';
        }
        if ($parameter->includePrivateResults()) {
            $audiences[] = 'particulier';
        }

        // Neither 'particulier', nor 'ondernemer'. Possibly only remote products are requested.
        if (!count($audiences)) {
            // Return an empty result set.
            return array('products' => array(), 'answers' => array());
        }

        $this->pushTime('finding faq');
        $this->postProcessAnswers(
            $results,
            'bySearch',
            'controllers.FrequentlyAskedQuestion.actions.find.maxFrequentlyAskedQuestionResultCount',
            $this->frequentlyAskedQuestionRepository->findBySearch($parameter->getSearchWords()),
            $parameter
        );
        $this->pushTime('/finding faq');

        $this->pushTime('finding products by faq');
        $productsBySearch = $this->productRepository->findByFrequentlyAskedQuestions($results['bySearch']);
        $this->productRepository->addAllUniqueProducts($weakProductResults, $productsBySearch);
        $this->pushTime('/finding products by faq');

        $this->pushTime('finding products by keyword');
        $productsByKeyword = $this->productRepository->findByKeyword($parameter->getSearchWords());
        $this->postProcessAnswers(
            $results,
            'byKeyword',
            'controllers.FrequentlyAskedQuestion.actions.find.maxFrequentlyAskedQuestionKeywordResultCount',
            $this->frequentlyAskedQuestionRepository->getUniqueFrequentlyAskedQuestionsFromProducts(
                $productsByKeyword
            ),
            $parameter
        );
        $this->productRepository->addAllUniqueProducts($strongProductResults, $productsByKeyword->toArray());
        $this->pushTime('/finding products by keyword');

        $this->pushTime('finding products by synonym');
        $productsBySynonym = $this->productRepository->findBySynonym($parameter->getSearchWords());
        $this->postProcessAnswers(
            $results,
            'bySynonym',
            'controllers.FrequentlyAskedQuestion.actions.find.maxFrequentlyAskedQuestionSynonymResultCount',
            $this->frequentlyAskedQuestionRepository->getUniqueFrequentlyAskedQuestionsFromProducts(
                $productsBySynonym
            ),
            $parameter
        );
        $this->productRepository->addAllUniqueProducts($weakProductResults, $productsBySynonym->toArray());
        $this->pushTime('/finding products by synonym');

        $this->pushTime('finding products by content');
        $productsByContent = $this->productRepository->findBySearch($parameter->getSearchWords());
        $this->postProcessAnswers(
            $results,
            'byContent',
            'controllers.FrequentlyAskedQuestion.actions.find.maxFrequentlyAskedQuestionProductResultCount',
            $this->frequentlyAskedQuestionRepository->getUniqueFrequentlyAskedQuestionsFromProducts(
                $productsByContent
            ),
            $parameter
        );
        $this->productRepository->addAllUniqueProducts($weakProductResults, $productsByContent->toArray());
        $this->pushTime('/finding products by content');

        $this->pushTime('finding products by name');
        $productsByName = $this->productRepository->findByName($parameter->getSearchWords());
        $this->postProcessAnswers(
            $results,
            'byName',
            'controllers.FrequentlyAskedQuestion.actions.find.maxFrequentlyAskedQuestionProductResultCount',
            $this->frequentlyAskedQuestionRepository->getUniqueFrequentlyAskedQuestionsFromProducts(
                $productsByName
            ),
            $parameter
        );
        $this->productRepository->addAllUniqueProducts($strongProductResults, $productsByName->toArray());
        $this->pushTime('/finding products by name');

        $this->pushTime('creating product list');

        $this->productRepository->removeNotInAudience($weakProductResults, $audiences);
        $this->productRepository->removeNotInAudience($strongProductResults, $audiences);

        $maxProductResultCount = $this->localConfigurationManager->get(
            'controllers.FrequentlyAskedQuestion.actions.find.maxProductResultCount'
        );
        $weakProductResults = $this->productRepository->sortByWeight($weakProductResults);
        $strongProductResults = $this->productRepository->sortByWeight($strongProductResults);
        $products = $this->productRepository->combineUniqueProductsSortedWithMaximum(
            $strongProductResults,
            $weakProductResults,
            $maxProductResultCount
        );
        $products = array_slice(
            $products,
            ($parameter->getPageNumber() * $maxProductResultCount),
            $maxProductResultCount
        );
        $this->pushTime('/creating product list');

        $this->pushTime('creating faq list');

        $answers = $this->frequentlyAskedQuestionRepository->addFrequentlyAskedQuestionLists(
            $results['bySearch'],
            $results['byKeyword'],
            $results['byProduct'],
            $results['bySynonym']
        );

        $answers = $this->frequentlyAskedQuestionRepository->sortByWeight($answers);
        $resultCount = $this->localConfigurationManager->get(
            'controllers.FrequentlyAskedQuestion.actions.find.maxFrequentlyAskedQuestionResultCount'
        );
        $answers = array_slice($answers, ($parameter->getPageNumber() * $resultCount), $resultCount);
        $this->pushTime('/creating faq list');
        return array('products' => $products, 'answers' => $answers);
    }

    /**
     * Performs some post-processing on the answers found:
     *    - removing answers not authorized for current user
     *    - removing answers which do not match the current audience filter
     *
     * @param array &$results the results array (reference)
     * @param string $name name of the result group
     * @param string $key name of the configuration key
     * @param array $answers the answers found
     * @param SearchParameter $parameter the search parameters
     * @return void
     */
    protected function postProcessAnswers(array &$results, $name, $key, array $answers, SearchParameter $parameter)
    {

        $destinations = trim(
            $this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.showDestinations')
        );
        if ($destinations != '') {
            $this->frequentlyAskedQuestionService->setDestinations(
                GeneralUtility::trimExplode(
                    ',',
                    $destinations
                )
            );
        }
        $this->frequentlyAskedQuestionService->setActiveChannels(
            GeneralUtility::trimExplode(
                ',',
                $this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.activeChannels')
            )
        );
        $this->frequentlyAskedQuestionService->setAuthority($this->getUserGroups());
        $targetAudience = array();
        if ($parameter->includePrivateResults()) {
            $targetAudience[] = 'particulier';
        }
        if ($parameter->includeBusinessResults()) {
            $targetAudience[] = 'ondernemer';
        }
        $this->frequentlyAskedQuestionService->setTargetAudience($targetAudience);
        $this->frequentlyAskedQuestionService->removeFrequentlyAskedQuestionsOutOfScope($answers);
        $results[$name] = $answers;
    }

    /**
     * Creates search parameter
     * @return SearchParameter
     */
    public function createSearchParameter()
    {
        /** @var SearchParameterFactory $searchParameterFactory */
        $searchParameterFactory = $this->objectManager->get(SearchParameterFactory::class);
        $searchParameterFactory->setRequest($this->request);
        return $searchParameterFactory->build();
    }

    /**
     * Returns the content object specified by the config path (which in turn was specified in the flexform)
     * @param $configPath    string    the path for the content element
     * @return string
     */
    public function getContentObject($configPath)
    {
        // BAH!
        $contentUid = $this->localConfigurationManager->get($configPath);
        $this->localContentObject = $GLOBALS['TSFE']->cObj;

        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );

        $config = array(
            'table' => 'tt_content',
            'select.' => array(
                'pidInList' => $extbaseFrameworkConfiguration['persistence']['storagePid'],
                'where' => 'uid=' . $contentUid,
            )
        );
        $result = $this->localContentObject->cObjGetSingle('CONTENT', $config);

        return $result;
    }
}

