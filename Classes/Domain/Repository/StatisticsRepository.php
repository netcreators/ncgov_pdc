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

namespace Netcreators\NcgovPdc\Domain\Repository;

use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;
use Netcreators\NcgovPdc\Domain\Model\Product;
use Netcreators\NcgovPdc\Domain\Model\Statistics;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Repository
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Model
 */
class StatisticsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var \Netcreators\NcExtbaseLib\Utility\FrontendUserUtility
     * @inject
     */
    protected $frontendUserUtility;

    /**
     * Increases product viewed count by one
     * @param Product $product
     * @param int $time time in given notation yyyymmddhh
     * @return void
     */
    public function addProductHit(Product $product, $time)
    {
        $query = $this->createQuery();
        $statisticsEntry = $query->matching(
            $query->logicalAnd(
                $query->equals('logtime', $time),
                $query->equals('product', $product)
            )
        )->setLimit(1)
            ->execute()
            ->getFirst();

        if ($statisticsEntry) {

            $statisticsEntry->setCount($statisticsEntry->getCount() + 1);
            if ($this->frontendUserUtility->isLoggedIn()) {
                $statisticsEntry->setLoggedinCount($statisticsEntry->getLoggedinCount() + 1);
            }
            $this->update($statisticsEntry);

        } else {
            /** @var Statistics $statisticsEntry */
            $statisticsEntry = $this->objectManager->get(Statistics::class);
            $statisticsEntry->setType(Statistics::TYPE_PRODUCT)
                ->setProduct($product)
                ->setLoggedinCount((int)$this->frontendUserUtility->isLoggedIn())
                ->setCount(1)
                ->setLogtime($time);
            $this->add($statisticsEntry);
        }
    }

    /**
     * Increases faq viewed count by one.
     *
     * @param FrequentlyAskedQuestion $faq the frequently asked question
     * @param int $time time in given notation yyyymmddhh
     * @return void
     */
    public function addFrequentlyAskedQuestionHit(FrequentlyAskedQuestion $faq, $time)
    {
        $query = $this->createQuery();
        $statisticsEntry = $query->matching(
            $query->logicalAnd(
                $query->equals('logtime', $time),
                $query->equals('frequentlyAskedQuestion', $faq)
            )
        )->setLimit(1)
            ->execute()
            ->getFirst();
        if ($statisticsEntry) {
            $statisticsEntry->setCount($statisticsEntry->getCount() + 1);
            if ($this->frontendUserUtility->isLoggedIn()) {
                $statisticsEntry->setLoggedinCount($statisticsEntry->getLoggedinCount() + 1);
            }
            $this->update($statisticsEntry);
        } else {
            /** @var Statistics $statisticsEntry */
            $statisticsEntry = $this->objectManager->get(Statistics::class);
            $statisticsEntry->setType(Statistics::TYPE_FREQUENTLYASKEDQUESTION)
                ->setFrequentlyAskedQuestion($faq)
                ->setLoggedinCount((int)$this->frontendUserUtility->isLoggedIn())
                ->setCount(1)
                ->setLogtime($time);
            $this->add($statisticsEntry);
        }
    }

    /**
     * Returns the top viewed records by given parameters.
     *
     * @param integer $type the type to find
     * @param integer $dateBegin the begin date
     * @param integer $dateEnd the end date
     * @param integer $numberOfRecords the maximum number of records to return
     * @return QueryResultInterface
     */
    public function findByTopViewed($type, $dateBegin, $dateEnd, $numberOfRecords)
    {

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $result = $query->statement(
            $s = '
			SELECT * , sum( count ) AS sumcount
			FROM `tx_ncgovpdc_domain_model_statistics`
			WHERE TYPE = ' . $type
                . '	AND logtime >= ' . $dateBegin
                . ' AND logtime <= ' . $dateEnd
                . '	GROUP BY frequently_asked_question, product
			ORDER BY sumcount DESC
			LIMIT ' . $numberOfRecords
        )->execute();

        return $result;
    }
}

