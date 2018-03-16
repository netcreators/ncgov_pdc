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
namespace Netcreators\NcgovPdc\Service\Statistics;

use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;
use Netcreators\NcgovPdc\Domain\Model\Product;
use Netcreators\NcgovPdc\Domain\Model\Statistics;
use Netcreators\NcgovPdc\Domain\Search\SearchParameter;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Registration
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Service
 */
class StatisticsManager
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager = null;

    /**
     * @var \Netcreators\NcgovPdc\Configuration\ConfigurationManager
     * @inject
     */
    protected $localConfigurationManager = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\StatisticsRepository
     * @inject
     */
    protected $statisticsRepository = null;

    /**
     * @var boolean
     */
    protected $statisticsRegistrationEnabled = false;

    /**
     * Initializes the manager. Cleans up sessions which were not closed correctly or in time.
     * @return void
     */
    public function initialize()
    {
        $this->setStatisticsRegistrationEnabled(
            $this->localConfigurationManager->get('statistics.enabled') &&
            $this->localConfigurationManager->get('flexform.statistics.disabledForThisInstance') == 0
        );
        if ($this->getStatisticsRegistrationEnabled()) {
            $this->validateConfiguration();
        }
    }

    /**
     * Validates current configuration settings, throws exceptions if not valid
     * @throws \Netcreators\NcgovPdc\Service\Statistics\Exception\InvalidTopViewedOffsetTimeException
     * @throws \Netcreators\NcgovPdc\Configuration\Exception\NotInitializedException
     * @return void
     */
    protected function validateConfiguration()
    {
        if ($this->localConfigurationManager == null) {
            throw new \Netcreators\NcgovPdc\Configuration\Exception\NotInitializedException('Configuration manager not set');
        }

        $offset = $this->localConfigurationManager->get('statistics.topViewed.timeOffset');
        if (strtotime($this->localConfigurationManager->get('statistics.topViewed.timeOffset')) === false) {
            throw new \Netcreators\NcgovPdc\Service\Statistics\Exception\InvalidTopViewedOffsetTimeException($offset . ' is not a valid offset');
        }
    }

    /**
     * Should stats be registered?
     * @return boolean
     */
    public function getStatisticsRegistrationEnabled()
    {
        return $this->statisticsRegistrationEnabled;
    }

    /**
     * Sets if statistics should be registered
     * @param boolean $state running state
     * @return self for chaining
     */
    public function setStatisticsRegistrationEnabled($state)
    {
        $this->statisticsRegistrationEnabled = $state;
        return $this;
    }

    /**
     * Saves the currently running registration.
     * @param Product $product
     * @param integer $timestamp
     * @return void
     */
    public function registerProductHit(Product $product, $timestamp)
    {
        $this->statisticsRepository->addProductHit($product, $this->getZeroPaddedDate($timestamp));
    }

    /**
     * Saves the currently running registration.
     * @param FrequentlyAskedQuestion $faq
     * @param integer $timestamp
     * @return void
     */
    public function registerFrequentlyAskedQuestionHit(FrequentlyAskedQuestion $faq, $timestamp)
    {
        $this->statisticsRepository->addFrequentlyAskedQuestionHit($faq, $this->getZeroPaddedDate($timestamp));
    }

    /**
     * Zeroprepended date
     * @param integer $timestamp
     * @return integer
     */
    public function getZeroPaddedDate($timestamp)
    {
        $date = date($this->localConfigurationManager->get('statistics.timestampFormat'), $timestamp);
        $result = (int)str_pad($date, 10, '0');
        return $result;
    }

    /**
     * Returns the top viewed frequently asked questions.
     * @return QueryResultInterface statistics records
     */
    public function getTopViewedFrequentlyAskedQuestions()
    {
        return $this->getTopViewedByType(Statistics::TYPE_FREQUENTLYASKEDQUESTION);
    }

    /**
     * Returns the top viewed products
     * @return QueryResultInterface statistics records
     */
    public function getTopViewedProducts()
    {
        return $this->getTopViewedByType(Statistics::TYPE_PRODUCT);
    }

    /**
     * Gets the top viewed statistics for requested type and the configured timeoffset
     * @param integer $type
     * @return QueryResultInterface the statistics records
     */
    protected function getTopViewedByType($type)
    {
        if ($this->getStatisticsRegistrationEnabled()) {
            $result = $this->statisticsRepository->findByTopViewed(
                $type,
                $this->getZeroPaddedDate(
                    strtotime($this->localConfigurationManager->get('statistics.topViewed.timeOffset'))
                ), // start
                $this->getZeroPaddedDate(time()), // end
                $this->localConfigurationManager->get('statistics.topViewed.numberOfItems')
            );
            return $result;
        } else {
            return null;
        }
    }

    /**
     * NOT USED (for now)
     * Registers the currenlty performed search as a registration step, if a registration is running
     * @param SearchParameter $search the search settings
     * @return void
     */
    /*public function registerSearch(\Netcreators\NcgovPdc\Domain\Search\SearchParameter $search) {
        if($this->getStatisticsRegistrationEnabled()) {
            if($search->searchIsEmpty()) {
                if($this->localConfigurationManager->get('registration.registerEmptySearch') == TRUE) {
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
    }*/

    /**
     * Registers the currently performed view of a product as a registration step, if a registration is running
     * @param Product $product the product being viewed
     * @return void
     */
    public function registerViewProduct(Product $product)
    {
        if ($this->getStatisticsRegistrationEnabled()) {
            $this->registerProductHit($product, time());
        }
    }

    /**
     * Registers the currently viewed faq as a registration step, if a registration is running
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion the faq being viewed
     * @return void
     */
    public function registerViewFrequentlyAskedQuestion(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        if ($this->getStatisticsRegistrationEnabled()) {
            $this->registerFrequentlyAskedQuestionHit($frequentlyAskedQuestion, time());
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
        if ($this->getStatisticsRegistrationEnabled()) {
            if ($frequentlyAskedQuestion == null) {
                $this->registerViewProduct($product);
            } else {
                $this->registerFrequentlyAskedQuestionHit($frequentlyAskedQuestion, time());
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
        if ($this->getStatisticsRegistrationEnabled()) {
            if ($frequentlyAskedQuestion != null) {
                $this->registerFrequentlyAskedQuestionHit($frequentlyAskedQuestion, time());
            }
        }
    }
}

