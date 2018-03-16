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

use Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40\Parameter;
use Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40\ScProducten;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller
 *
 * @package NcgovPdc
 * @subpackage Controller
 */
class ProductController extends BaseController
{

    /**
     * @var \Netcreators\NcExtbaseLib\Utility\FrontendUserUtility
     * @inject
     */
    protected $frontendUserUtility;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\ResponsibleRepository
     * @inject
     */
    protected $responsibleRepository = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\TipRepository
     * @inject
     */
    protected $tipRepository = null;

    /**
     * @var \Netcreators\NcgovPdc\Configuration\TableCreationArrayManager
     * @inject
     */
    protected $tableCreationArrayManager = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\UnknownUserRepository
     * @inject
     */
    protected $unknownUserRepository;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\AdvancedThemeRepository
     * @inject
     */
    protected $advancedThemeRepository = null;

    /**
     * @var \TYPO3\CMS\Fluid\View\TemplateView
     */
    protected $view;

    /**
     * Initializes the current action
     *
     * @return void
     */
    public function initializeAction()
    {
        $this->initializeBase();
    }

    /**
     * Shows product details page.
     *
     * Note the product has been specified typeless, because hidden products cannot be loaded and do not trigger an errorAction (yet)
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product the product to be displayed
     * @param \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion the product to be displayed (FIXME: Not actually used?)
     * @param boolean $showMore show more frequently asked questions
     * @return void
     */
    public function detailAction(
        \Netcreators\NcgovPdc\Domain\Model\Product $product = null,
        \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion = null,
        $showMore = false
    ) {

        $template = $this->localConfigurationManager->get('controllers.Product.detail.useTemplate');
        if (!empty($template)) {
            // Note: $this->view is defined as Tx_Extbase_MVC_View_ViewInterface, which does not provide setTemplatePathAndFilename().
            // However, if detailAction is called then $this->view will be Tx_Fluid_View_TemplateView, which implements
            // setTemplatePathAndFilename() with @api annotation.
            $this->view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($template));
        }

        if (!$product) {
            // 404 call is done in Detail fluid template.
            return;
        }

        // FIXME: $frequentlyAskedQuestion parameter does not seem to be set by any template-created link!
        $this->registrationManager->registerViewFrequentlyAskedQuestionForProduct($product, $frequentlyAskedQuestion);
        $this->statisticsManager->registerViewFrequentlyAskedQuestionForProduct($product, $frequentlyAskedQuestion);

        $this->frequentlyAskedQuestionService->activateFrequentlyAskedQuestion($frequentlyAskedQuestion);

        if ($this->localConfigurationManager->get('controllers.Product.title.useRecordAsPageTitle')) {
            $prefix = $this->localConfigurationManager->get('controllers.Product.title.prefix');
            if ($prefix) {
                $prefix .= ' ';
            }
            $postfix = $this->localConfigurationManager->get('controllers.Product.title.postfix');
            if ($postfix) {
                $postfix = ' ' . $postfix;
            }
            // set page title
            $GLOBALS['TSFE']->page['title'] = $prefix . $product->getName() . $postfix;
            $GLOBALS['TSFE']->config['config']['noPageTitle'] = false;
            $GLOBALS['TSFE']->config['config']['pageTitleFirst'] = true;
            $GLOBALS['TSFE']->config['config']['pageTitleSeparator'] = ' -';

            // set page title for indexed search
            $GLOBALS['TSFE']->indexedDocTitle = $prefix . $product->getName() . $postfix;
        }

        $controllerContext = $this->buildControllerContext();
        $product->setControllerContext($controllerContext);

        $this->assignProductDetailImageDimensions();
        $this->assignUser($product);
        $this->assignCombinedProperties($product);
        $this->assignFrequentlyAskedQuestions($product, $showMore);

        $this->view->assign('availableUsers', $this->getUserAvailability($product));
        $this->view->assign('product', $product);
    }

    /**
     * Shows product details for a flexform-predefined product.
     *
     * Note the product has been specified typeless, because hidden products cannot be loaded and do not trigger an errorAction (yet)
     *
     * @param boolean $showMore show more frequently asked questions
     * @return void
     */
    public function detailForPreconfiguredProductAction($showMore = false)
    {

        $preConfiguredProductId = $this->localConfigurationManager->get(
            'controllers.Product.detailForPreconfiguredProduct.preconfiguredProduct'
        );
        /** @var \Netcreators\NcgovPdc\Domain\Model\Product $product */
        $product = $this->productRepository->findByUid($preConfiguredProductId);

        if (!$product) {
            return;
        }

        $controllerContext = $this->buildControllerContext();
        $product->setControllerContext($controllerContext);

        $this->assignProductDetailImageDimensions();
        $this->assignUser($product);
        $this->assignCombinedProperties($product);
        $this->assignFrequentlyAskedQuestions($product, $showMore);

        $this->view->assign('availableUsers', $this->getUserAvailability($product));
        $this->view->assign('product', $product);
    }

    /**
     * Shows product not available action
     * @return void
     */
    public function productNotAvailableAction()
    {
        $this->redirectToURI($this->localConfigurationManager->get('pages.productNotAvailablePage'));
    }

    /**
     * Shows lists of products and their maintainers
     * @return void
     */
    public function productMaintainerAdminOverviewAction()
    {

        $id = (int)$_GET['id'];
        $products = array();
        $frontendViewPageNormal = '';
        $frontendViewPageRealUrl = '';
        $useRealUrl = false;
        $newTipPid = false;

        if ($id > 0) {
            $products = $this->productRepository->findAllInStorageFolder((int)$id);
            $products = $products->toArray();
            $pageTsConfig = BackendUtility::getPagesTSconfig($id);
            $frontendViewPageNormal = $pageTsConfig['tx_ncgovpdc.']['frontendViewPageNormal'];
            $frontendViewPageRealUrl = $pageTsConfig['tx_ncgovpdc.']['frontendViewPageRealUrl'];
            $useRealUrl = $frontendViewPageRealUrl != '';
            $newTipPid = $pageTsConfig['tx_ncgovpdc.']['newTipPid'];
        }

        if (count(
                $products
            ) > 0 && ($frontendViewPageNormal == '' || $frontendViewPageRealUrl == '' || $newTipPid == '')
        ) {
            $this->addFlashMessage(
                'Configuratie niet compleet. Stel de configuratie in de backend page TS config in!',
                'Configuratie fout',
                FlashMessage::ERROR
            );
        }

        $this->view->assign('products', $products);
        $this->view->assign(
            'settings',
            array(
                'frontendViewPageNormal' => $frontendViewPageNormal,
                'frontendViewPageRealUrl' => $frontendViewPageRealUrl,
                'useRealUrl' => $useRealUrl,
                'newTipPid' => $newTipPid
            )
        );
        $this->view->assign('productCount', count($products));
    }

    /**
     * Create a new tip
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product the product the tip belongs to
     * @return void
     */
    public function newTipAction(\Netcreators\NcgovPdc\Domain\Model\Product $product)
    {
        $user = $this->frontendUserUtility->findLoggedInUser();
        if (!$user) {
            return;
        }

        if ($this->localConfigurationManager->get('controllers.Product.restrictAccessToTips') != false) {
            $this->view->assign('userAuthorisedAndLoggedIn', $product->getIsUserAllowedToView($user));
        } else {
            $this->view->assign('userAuthorisedAndLoggedIn', 1);
        }

        $this->view->assign('product', $product);
        $this->clearPageCache();
    }

    /**
     * Creates a new tip for the product
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product the product the tip belongs to
     * @param \Netcreators\NcgovPdc\Domain\Model\Tip $tip A fresh TIP object which has not yet been added to the repository
     * @throws Exception\NotLoggedInException
     * @return void
     */
    public function createTipAction(
        \Netcreators\NcgovPdc\Domain\Model\Product $product,
        \Netcreators\NcgovPdc\Domain\Model\Tip $tip
    ) {
        $user = $this->frontendUserUtility->findLoggedInUser();
        if (!$user) {
            throw new Exception\NotLoggedInException();
        }
        $product->addTip($tip);
        $tip->setProduct($product);
        $tip->setCreator($user);
        $this->clearPageCache();
        $this->redirect(
            'detail',
            null,
            null,
            array('product' => $product),
            $this->localConfigurationManager->get('pages.productDetailPage')
        );
    }

    /**
     * Removes a tip from the product
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product the product the tip belongs to
     * @param \Netcreators\NcgovPdc\Domain\Model\Tip $tip the tip to remove
     * @return void
     */
    public function removeTipAction(
        \Netcreators\NcgovPdc\Domain\Model\Product $product,
        \Netcreators\NcgovPdc\Domain\Model\Tip $tip
    ) {
        $product->removeTip($tip);
        $this->clearPageCache();
        $this->redirect(
            'detail',
            null,
            null,
            array('product' => $product),
            $this->localConfigurationManager->get('pages.productDetailPage')
        );
    }

    /**
     * Edits a tip
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product the product the tip belongs to
     * @param \Netcreators\NcgovPdc\Domain\Model\Tip $tip the tip to edit
     * @return void
     */
    public function editTipAction(
        \Netcreators\NcgovPdc\Domain\Model\Product $product,
        \Netcreators\NcgovPdc\Domain\Model\Tip $tip
    ) {
        $user = $this->frontendUserUtility->findLoggedInUser();
        if (!$user) {
            return;
        }

        if ($this->localConfigurationManager->get('controllers.Product.restrictAccessToTips') != false) {
            $this->view->assign('userAuthorisedAndLoggedIn', $product->getIsUserAllowedToView($user));
        } else {
            $this->view->assign('userAuthorisedAndLoggedIn', 1);
        }

        $this->view->assign('product', $product);
        $this->view->assign('tip', $tip);
        $this->clearPageCache();
    }

    /**
     * Updates a tip
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product the product the tip belongs to
     * @param \Netcreators\NcgovPdc\Domain\Model\Tip $tip the tip to update
     * @return void
     */
    public function updateTipAction(
        \Netcreators\NcgovPdc\Domain\Model\Product $product,
        \Netcreators\NcgovPdc\Domain\Model\Tip $tip
    ) {
        $this->clearPageCache();
        $product->removeTip($tip);
        $product->addTip($tip);
        $this->redirect(
            'detail',
            null,
            null,
            array('product' => $product),
            $this->localConfigurationManager->get('pages.productDetailPage')
        );
    }

    /**
     * Shows a-z index overview.
     *
     * @param string $letter the letter to show
     * @return void
     */
    public function azIndexAction($letter = null)
    {

        $audience = $this->localConfigurationManager->get('controllers.Product.limitResultsToThisAudience');
        $letter = $this->getSanitizedLetter($letter);
        $letter = (string)$letter;
        $this->view->assign('settings', $this->settings);
        $this->view->assign('selectedLetter', $letter);
        $this->view->assign('letters', $this->productRepository->findAllLetters($letter, $audience));

        if ($letter !== '') {
            $this->view->assign('productDetailPage', $this->localConfigurationManager->get('pages.productDetailPage'));
            // Note: Need to call toArray() on the returned \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult, since
            //	   \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult::count() is broken for queries built with \TYPO3\CMS\Extbase\Persistence\Generic\Query::statement()!
            //	   The count() method is called by the fluid CountViewHelper {f:count(subject: '{products}')} in AzIndex.html.
            $this->view->assign('products', $this->productRepository->findByFirstLetter($letter, $audience)->toArray());
        } else {
            $this->view->assign('products', array());
        }
    }

    /**
     * Action called to show the top X of frequently asked questions
     * @return void
     */
    public function topViewedProductsAction()
    {
        $this->view->assign('topViewedProducts', $this->statisticsManager->getTopViewedProducts());
    }

    /**
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product
     */
    public function showRelatedAdvancedThemesForProductAction(
        \Netcreators\NcgovPdc\Domain\Model\Product $product = null
    ) {
        $this->view->assign('currentProduct', $product);
        if ($product) {
            $this->view->assign(
                'relatedAdvancedThemes',
                $this->advancedThemeRepository->findRelatedByProduct($product)
            );
        }
    }

    public function workInstructions()
    {
    }

    /**
     * Displays Process Tab Menu
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product
     * @return void
     */
    public function tabMenuAction(\Netcreators\NcgovPdc\Domain\Model\Product $product = null)
    {
        if ($product != null) {
            $this->view->assign('product', $product);
        }
    }

    /**
     * Displays product info by scmeta id
     *
     * @return void
     */
    public function showByScmetaAction()
    {
        $scMetaId = GeneralUtility::_GP('loginCancelled');
        if ($scMetaId) {
            $product = $this->productRepository->findByScmetaProductId((int)$scMetaId)->getFirst();
            $this->view->assign('product', $product);
        }
    }

    /**
     * Creates xml feed for samenwerkende catalogi
     * @return void
     */
    public function publishXmlAction()
    {
        $products = $this->productRepository->findAll();

        /** @var Parameter $parameter */
        $parameter = $this->objectManager->get(Parameter::class)
            ->addAllProducts(
                $products
            )
            ->setSpatial(
                $this->localConfigurationManager->get('samenwerkendeCatalogi.spatial.spatial')
            )
            ->setSpatialScheme(
                $this->localConfigurationManager->get('samenwerkendeCatalogi.spatial.scheme')
            )
            ->setSpatialResourceIdentifier(
                $this->localConfigurationManager->get('samenwerkendeCatalogi.spatial.resourceIdentifier')
            )
            ->setAuthority(
                $this->localConfigurationManager->get('samenwerkendeCatalogi.authority.authority')
            )
            ->setAuthorityScheme(
                $this->localConfigurationManager->get('samenwerkendeCatalogi.authority.scheme')
            )
            ->setAuthorityResourceIdentifier(
                $this->localConfigurationManager->get('samenwerkendeCatalogi.authority.resourceIdentifier')
            )
            ->setProductDetailPageId(
                $this->localConfigurationManager->get('pages.productDetailPage')
            );
        $parameter->getUriBuilder()->setRequest($this->request);

        /** @var string $output */
        $output = $this->objectManager->get(
            ScProducten::class,
            $parameter
        )->render();

        if ($this->localConfigurationManager->get('controllers.Product.dieOnOutputXml')) {
            header('Content-Type: text/xml', true);
            die('<?xml version="1.0" encoding="utf-8" ?>' . chr(10) . $output);
        } else {
            $this->response->setContent($output);
        }
    }


    /**
     * Returns a list (alternative) users connected with the product and their availability status.
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product the product to be displayed
     * @return array
     */
    protected function getUserAvailability(\Netcreators\NcgovPdc\Domain\Model\Product $product)
    {
        $result = array();
        $displayColumns = GeneralUtility::trimExplode(
            ',',
            $this->localConfigurationManager->get('database.Product.userAvailability.displayColumns')
        );
        $statusColumn = $this->localConfigurationManager->get(
            'database.Product.userAvailability.columnToDetermineAvailability'
        );
        $statuses = $this->localConfigurationManager->get('database.Product.userAvailability.columnStatusesAvailable.');

        if ($this->localConfigurationManager->get('database.Product.userAvailability.useUsersAvailableColumn')) {
            $this->unknownUserRepository->setLookupTable(
                $this->localConfigurationManager->get('database.Product.userAvailability.lookupTable')
            );
            $this->unknownUserRepository->setOrderBy(
                $this->localConfigurationManager->get('database.Product.userAvailability.orderBy')
            );
            $rawQueryResult = $this->unknownUserRepository->findByProduct($product);
            $users = $this->getPreparedUnknownUsersForDisplay($rawQueryResult, $statusColumn, $displayColumns);
        } else {
            // use default connected fe users
            $this->unknownUserRepository->setLookupTable('fe_users');
            $this->unknownUserRepository->setOrderBy('r.name');
            $rawQueryResult = $this->unknownUserRepository->findFeUsersByProduct($product);
            $users = $this->getPreparedUnknownUsersForDisplay($rawQueryResult, $statusColumn, $displayColumns);
        }
        if (count($users) > 0) {
            $result = $this->prepareAvailableUserListStatus($users, $statuses, $statusColumn);
        }
        return $result;
    }

    /**
     * Add status info to the user records.
     * @param array $users the list of users
     * @param array $statuses the list of $statuses
     * @param string $statusColumn the column which contains the status value
     * @return array
     */
    protected function prepareAvailableUserListStatus($users, $statuses, $statusColumn)
    {
        $newUsers = array();
        foreach ($users as $user) {
            $newUser = array();
            $matched = false;
            foreach ($statuses as $name => $status) {
                if ($name != 'noMatch' && preg_match($status['match'], $user[$statusColumn]) == 1) {
                    $matched = true;
                    $newUser['data'] = $user;
                    $newUser['special']['pdc_status_description'] = $status['description'];
                    $newUser['special']['pdc_status_icon'] = $status['icon'];
                }
            }
            if ($matched != true) {
                $newUser['data'] = $user;
                $newUser['special']['pdc_status_description'] = $statuses['noMatch']['description'];
                $newUser['special']['pdc_status_icon'] = $statuses['noMatch']['icon'];
            }
            unset($newUser['data'][$statusColumn]);
            $newUsers[] = $newUser;
        }
        return $newUsers;
    }

    /**
     * Prepares user from unknown table for display.
     *
     * @param array $userRows
     * @param string $statusColumn column containing the status
     * @param array $displayColumns columns to be shown
     * @return array containing the user columns
     */
    protected function getPreparedUnknownUsersForDisplay(array $userRows, $statusColumn, $displayColumns)
    {
        $newUsers = array();
        foreach ($userRows as $userRow) {
            if (is_object(
                $userRow
            )
            ) { // FIXME: $userRow is never supposed to be an object; always an array! @see \Netcreators\NcgovPdc\Domain\Repository\UnknownUserRepository::findByProduct and \Netcreators\NcgovPdc\Domain\Repository\UnknownUserRepository::findFeUsersByProduct
                $tempUser = array();
                foreach ($displayColumns as $column) {
                    // FIXME: WTF is ::getValue()? We are dealing with plain associative arrays (raw query results) here!
                    $tempUser[$column] = $userRow->getValue($column);
                }
                $tempUser[$statusColumn] = $userRow->getValue($statusColumn);
                $newUsers[] = $tempUser;
            }
        }
        return $newUsers;
    }

    /**
     * Returns the sanitized first letter of the given input
     * @param mixed $input
     * @return string the letter
     */
    protected function getSanitizedLetter($input)
    {
        $letter = (string)$input;
        $letter = $letter[0];
        if (!($letter >= 'a' && $letter <= 'z' || $letter >= 'A' && $letter <= 'Z')) {
            $letter = false;
        }
        return $letter;
    }

    /**
     * Clears the current page cache.
     * @return void
     */
    protected function clearPageCache()
    {
        if (TYPO3_MODE == 'FE') {
            /** @var \TYPO3\CMS\Extbase\Service\CacheService $cacheService */
            $cacheService = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Service\\CacheService');
            $cacheService->clearPageCache(
                $GLOBALS['TSFE']->id
            ); // \TYPO3\CMS\Extbase\Service\CacheService::clearPageCache swallows 'integer' as well as 'array<integer>'.
        }
    }

    /**
     * Assigns the product's combinable properties to the View.
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product
     * @return void
     */
    protected function assignCombinedProperties(\Netcreators\NcgovPdc\Domain\Model\Product $product)
    {
        // Test properties combined so we can use them in the products
        // Explanation: This allows PDC tabs which contain several different product fields (properties). The "combined
        //			  property" (named $group) makes sure that that tab is displayed if any of the fields (named
        //			  $properties) combined in this "combined property" is available.
        //			  If none of these fields are filled, then the entire "combined property"
        //			  ($combinedProperties[$group]) is marked as empty ($combinedProperties[$group]
        //			  = 0) and its tab remains hidden.
        $combinedProperties = array();
        $combinableProperties = $this->localConfigurationManager->get('controllers.Product.combinedProperties.');

        if (is_array($combinableProperties)) {
            foreach ($combinableProperties as $group => $properties) {
                $combinedPropertyAvailable = false;

                $properties = GeneralUtility::trimExplode(',', $properties);
                if (is_array($properties)) {

                    foreach ($properties as $property) {
                        if (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::isPropertyGettable($product, $property)) {
                            $propertyValue = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty(
                                $product,
                                $property
                            );

                            if ($propertyValue instanceOf \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {
                                $propertyValue = (boolean)count($propertyValue);
                            } else {
                                $propertyValue = (boolean)$propertyValue;
                            }
                            $combinedPropertyAvailable = $combinedPropertyAvailable || $propertyValue;
                        }
                    }
                }
                $combinedProperties[$group] = (int)$combinedPropertyAvailable;
            }
        }
        $this->view->assign('combinedProperties', $combinedProperties);
    }

    /**
     * Assigns the product's referenced frequently asked questions to the View.
     * By default, only (int)controllers.Product.maximumNumberOfFrequentlyAskedQuestionsToShow FAQs are shown.
     * If $showMore is TRUE then the amount of FAQs shown is not limited.
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product
     * @param boolean $showMore
     * @return void
     */
    protected function assignFrequentlyAskedQuestions(
        \Netcreators\NcgovPdc\Domain\Model\Product $product,
        $showMore = false
    ) {
        $hasMoreAnswers = false;

        $frequentlyAskedQuestions = $product->getFrequentlyAskedQuestions();
        if ($frequentlyAskedQuestions->count()) {

            $frequentlyAskedQuestions = $frequentlyAskedQuestions->toArray();

            $destinations = $this->localConfigurationManager->get(
                'controllers.FrequentlyAskedQuestion.showDestinations'
            );
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

            if (!$showMore) {
                $this->frequentlyAskedQuestionService->setMaximumNumberOfFrequentlyAskedQuestionsToShow(
                    $this->localConfigurationManager->get(
                        'controllers.Product.maximumNumberOfFrequentlyAskedQuestionsToShow'
                    )
                );
            }

            $user = $this->frontendUserUtility->findLoggedInUser();
            $this->frequentlyAskedQuestionService->setAuthority($user ? $user->getUsergroup()->toArray() : null);

            $this->frequentlyAskedQuestionService->removeFrequentlyAskedQuestionsOutOfScope($frequentlyAskedQuestions);

            $startSize = count($frequentlyAskedQuestions);
            $this->frequentlyAskedQuestionService->removeFrequentlyAskedQuestionsOutOfRange($frequentlyAskedQuestions);
            $endSize = count($frequentlyAskedQuestions);

            if ($startSize != $endSize) {
                $hasMoreAnswers = true;
            }

        } else {
            $frequentlyAskedQuestions = array();
        }

        $this->view->assign('hasMoreAnswers', $hasMoreAnswers);
        $this->view->assign('answers', $frequentlyAskedQuestions);
    }

    /**
     * Assigns the user currently being logged-in (if any) to the View.
     *
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product
     */
    protected function assignUser(\Netcreators\NcgovPdc\Domain\Model\Product $product)
    {
        /** @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user */
        $user = $this->frontendUserUtility->findLoggedInUser();
        if ($user) {
            $this->view->assign('userIsLoggedIn', true);
            $this->view->assign('userAuthorisedAndLoggedIn', $product->getIsUserAllowedToView($user));
            $this->view->assign('user', $user);
        }
    }

    protected function assignProductDetailImageDimensions()
    {
        $this->view->assign(
            'maxWidth',
            $this->localConfigurationManager->get('controllers.Product.detail.image.maxWidth')
        );
        $this->view->assign(
            'maxHeight',
            $this->localConfigurationManager->get('controllers.Product.detail.image.maxHeight')
        );
    }
}

