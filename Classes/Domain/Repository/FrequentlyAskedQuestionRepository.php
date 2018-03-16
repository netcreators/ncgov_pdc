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

use Netcreators\NcExtbaseLib\Domain\Repository\BaseRepository;
use Netcreators\NcgovPdc\Domain\Model\Exception\NoSearchSpecifiedException;
use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;
use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel;
use Netcreators\NcgovPdc\Domain\Model\Product;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Repository
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Model
 */
class FrequentlyAskedQuestionRepository extends BaseRepository
{

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\FrequentlyAskedQuestionChannelRepository
     * @inject
     */
    protected $frequentlyAskedQuestionChannelRepository = null;

    /**
     * Finds objects matching the given ids.
     *
     * @param array $uids array containing the uids
     * @throws \InvalidArgumentException
     * @return array
     */
    public function findByUids(array $uids)
    {

        foreach ($uids as $uid) {
            if (!is_int($uid) || $uid < 0) {
                throw new \InvalidArgumentException('All given UIDs must be positive integers!', 1245071889);
            }
        }

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $queryResult = $query->matching($query->in('uid', $uids))->execute();

        // FIXME: Why is this supposed to be necessary? Can't we just return $queryResult?
        $result = array();
        foreach ($uids as $uid) {

            /** @var FrequentlyAskedQuestion $faq */
            foreach ($queryResult as $faq) {
                if ($uid == $faq->getUid()) {
                    $result[] = $faq;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Sorts FAQ's by weight.
     *
     * @param array $answers
     * @return array
     */
    public function sortByWeight(array $answers)
    {

        // TODO refactor to baseclass?
        $newAnswers = array();

        if (is_array($answers) && $answers) {

            /** @var FrequentlyAskedQuestion $faq */
            foreach ($answers as $faq) {
                // generate uique sortable id
                $key = sprintf('%d%d', $faq->getWeight(), $faq->getUid());
                $newAnswers[$key] = $faq;
            }
            krsort($newAnswers);
        }

        return $newAnswers;
    }

    /**
     * Returns frequently asked questions from products, without duplicates
     * @param QueryResultInterface $products the products
     * @return array
     */
    public function getUniqueFrequentlyAskedQuestionsFromProducts(QueryResultInterface $products)
    {
        if (!$products) {
            return array();
        }

        $idList = array();
        $frequentlyAskedQuestions = array();

        /** @var Product $product */
        foreach ($products as $product) {
            $productFrequentlyAskedQuestions = $product->getAvailableFrequentlyAskedQuestions();

            /** @var FrequentlyAskedQuestion $faq */
            foreach ($productFrequentlyAskedQuestions as $faq) {
                if ($idList[$faq->getUid()] !== true) {
                    $frequentlyAskedQuestions[] = $faq;
                    $idList[$faq->getUid()] = true;
                }
            }
        }

        return $frequentlyAskedQuestions;
    }

    /**
     * Adds lists of faqs.
     *
     * @param <dynamic multiple arguments possible>
     * @return array
     */
    public function addFrequentlyAskedQuestionLists()
    {
        $arguments = func_get_args();
        if (!$arguments) {
            return array();
        }

        $result = array();
        if (count($arguments) == 1) {
            $result = $arguments[0];
        } else {
            foreach ($arguments as $argument) {
                $result = $this->addUniqueObjectsToList($result, $argument);
            }
        }

        return $result;
    }

    /**
     * Adds objects to the list, as long as they are not already in the list.
     * @param array $list the original list
     * @param array $objects the objects to be added
     * @return array    the list
     */
    public function addUniqueObjectsToList(array $list, $objects)
    {
        if (!is_array($objects) || !$objects) {
            return $list;
        }

        /** @var \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $object */
        foreach ($objects as $object) {
            $uid = $object->getUid();
            if (!isset($list[$uid])) {
                $list[$uid] = $object;
            }
        }

        return $list;
    }

    /**
     * Adds frequently asked questions from the given products to the given frequently asked questions. Checks for doubles.
     *
     * @param array $frequentlyAskedQuestions the frequently asked questions
     * @param array $products the products
     * @return array
     */
    public function addFrequentlyAskedQuestionsFromProducts(array $frequentlyAskedQuestions, array $products)
    {
        $newQuestions = array();
        $idList = array();

        if (is_array($frequentlyAskedQuestions) && $frequentlyAskedQuestions) {
            /** @var FrequentlyAskedQuestion $faq */
            foreach ($frequentlyAskedQuestions as $faq) {
                if ($idList[$faq->getUid()] !== true) {
                    $newQuestions[] = $faq;
                    $idList[$faq->getUid()] = true;
                }
            }
        }

        if (is_array($products) && $products) {
            /** @var Product $product */
            foreach ($products as $product) {
                $productFrequentlyAskedQuestions = $product->getAvailableFrequentlyAskedQuestions();
                if ($productFrequentlyAskedQuestions) {
                    foreach ($productFrequentlyAskedQuestions as $faq) {
                        if ($idList[$faq->getUid()] !== true) {
                            $newQuestions[] = $faq;
                            $idList[$faq->getUid()] = true;
                        }
                    }
                }
            }
        }
        return $newQuestions;
    }

    /**
     * Actually performs the frequently asked question search.
     *
     * @param array $searchWords
     * @throws NoSearchSpecifiedException
     * @return array
     */
    public function findBySearch(array $searchWords = array())
    {
        if (count($searchWords) == 0) {
            throw new NoSearchSpecifiedException();
        }

        $frequentlyAskedQuestions = $this->findByChannels(
            $this->frequentlyAskedQuestionChannelRepository->findBySearch($searchWords)
        );

        if ($frequentlyAskedQuestions) {
            return $frequentlyAskedQuestions->toArray();
        }

        return array();
    }

    /**
     * Finds frequently asked question records by channel.
     *
     * @param QueryResultInterface $channels the channels to search
     * @return QueryResultInterface|NULL
     */
    public function findByChannels(QueryResultInterface $channels = null)
    {
        $ids = '';
        $glue = '';
        $result = null;

        if (count($channels)) {
            /** @var FrequentlyAskedQuestionChannel $channel */
            foreach ($channels as $channel) {
                $ids .= $glue . $channel->getUid();
                if ($glue == '') {
                    $glue = ',';
                }
            }
            $statement = sprintf(
                'SELECT faq.*'
                . ' FROM tx_ncgovpdc_domain_model_frequentlyaskedquestion AS faq, tx_ncgovpdc_domain_model_frequentlyaskedquestionchannel AS channel'
                . ' WHERE channel.uid IN (%s)'
                . ' AND channel.frequently_asked_question_uid = faq.uid'
                . ' AND faq.deleted=0 AND faq.hidden=0 AND faq.owms_mantle_available_start <= %d',
                $ids,
                time()
            );
            /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
            $query = $this->createQuery();
            $result = $query->statement($statement)->execute();
        }
        return $result;
    }

    /**
     * Returns faq records which are imported and are marked with special session number.
     *
     * @param integer $sessionNumber
     * @return QueryResultInterface
     */
    public function findImportedNotHavingSessionNumber($sessionNumber)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('imported', 1),
                $query->logicalNot(
                    $query->equals('sessionNumber', $sessionNumber)
                )
            )
        )->execute();
    }

    /**
     * Returns faq records which are imported and match the owmscore identifier.
     *
     * @param string $owmsCoreIdentifier
     * @return QueryResultInterface
     */
    public function findImportedByOwmsCoreIdentifier($owmsCoreIdentifier)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('imported', 1),
                $query->equals('owmsCoreIdentifier', $owmsCoreIdentifier)
            )
        )->execute();
    }

    /**
     * Returns SQL for counting imported FAQs with links to products. This is necessary since extbase does not support
     * counting a query result while joining tables.
     *
     * @return string
     */
    protected function getImportedWithLinksToProductsSql()
    {

        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );

        $pidWhere = ' tx_ncgovpdc_domain_model_frequentlyaskedquestion.pid IN ('
            . implode(
                ', ',
                GeneralUtility::intExplode(',', $extbaseFrameworkConfiguration['persistence']['storagePid'])
            )
            . ')';

        $fields = 'tx_ncgovpdc_domain_model_frequentlyaskedquestion.*';

        $sql = 'SELECT ' . $fields . ' from tx_ncgovpdc_domain_model_frequentlyaskedquestion, tx_ncgovpdc_frequently_asked_question_link_product_mm, tx_ncgovpdc_domain_model_referencelink';
        $sql .= ' WHERE tx_ncgovpdc_domain_model_frequentlyaskedquestion.uid=tx_ncgovpdc_frequently_asked_question_link_product_mm.uid_local';
        $sql .= ' AND tx_ncgovpdc_domain_model_frequentlyaskedquestion.imported=1';
        $sql .= ' AND ' . $pidWhere;
        $sql .= BackendUtility::deleteClause(
                'tx_ncgovpdc_domain_model_frequentlyaskedquestion'
            ) . BackendUtility::BEenableFields('tx_ncgovpdc_domain_model_frequentlyaskedquestion');
        $sql .= ' AND tx_ncgovpdc_domain_model_referencelink.uid=tx_ncgovpdc_frequently_asked_question_link_product_mm.uid_foreign';
        $sql .= BackendUtility::deleteClause('tx_ncgovpdc_domain_model_referencelink') . BackendUtility::BEenableFields(
                'tx_ncgovpdc_domain_model_referencelink'
            );

        return $sql;
    }

    /**
     * Returns the count of records found (cannot manually add limit & count together :( ).
     *
     * @param integer $offset start
     * @param integer $size limit
     * @return integer the number of records
     */
    public function countImportedWithLinksToProductsLimited($offset, $size)
    {
        // 2013-11-19, ncleonie:


        // OPTION 1:
        //
        // The following OPTION 1 won't work, because \Tx_Extbase_Persistence_Storage_Typo3DbBackend::parseSource(),
        // called within \Tx_Extbase_Persistence_Storage_Typo3DbBackend::parseQuery()
        // only returns tx_ncgovpdc_domain_model_frequentlyaskedquestion as tableName, ignoring the implicit JOIN
        // created in \Netcreators\NcgovPdc\Domain\Repository\FrequentlyAskedQuestionRepository::getImportedWithLinksToProductsSql().
        // Therefore, ALL FAQs are counted instead of all these with links to products.

        //	$sql = $this->getImportedWithLinksToProductsSql();
        //	$query = $this->createQuery()->setOffset($offset)->setLimit($size);
        //	$result = $query->statement($sql)->execute(); // Counting this $result returns the wrong value!


        // OPTION 2:
        //
        // When working with $query->statement(), count() cannot be used on the QueryResult:
        // A new COUNT(*) would be created which does not include the LIMIT from the original query.
        // Therefore, the results need to first be retrieved by calling QueryResult::toArray().

        //	$result = $this->findImportedWithLinksToProductsLimited($offset, $size);
        //	$result = $result->toArray(); // This results in possibly thousands (Maastricht: 2032 FAQs!) of FAQ models being created... takes lots of time and memory. And probably out-of-memory errors.
        //	return count($result);


        // OPTION 3:
        //
        // Do not create domain models, but count the raw rows. This works more flexibly than using SQL COUNT(), as counted
        // fields can vary.
        $sql = $this->getImportedWithLinksToProductsSql();
        $sql .= ' LIMIT ' . $offset . ',' . $size;

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();

        // Do not reconstitute domain models just for counting (so $returnRawQueryResult -> TRUE).
        /** @var array $result */
        $result = $query->statement($sql)->execute(true);

        return count($result);
    }

    /**
     * Returns faq records which are imported and have a link to a product.
     *
     * @param integer $offset
     * @param integer $size
     * @return QueryResultInterface
     */
    public function findImportedWithLinksToProductsLimited($offset, $size)
    {
        $sql = $this->getImportedWithLinksToProductsSql();
        $sql .= ' LIMIT ' . $offset . ',' . $size;

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        return $query->statement($sql)->execute();
    }

    /**
     * Sets the columns which can be searched
     * @param array $columns
     * @return void
     */
    public function setSearchableColumns(array $columns)
    {
        $this->frequentlyAskedQuestionChannelRepository->setSearchableColumns($columns);
    }

    /**
     * Returns all faq records within the given window (offset, limit).
     *
     * @param integer $offset the offset
     * @param integer $limit max number of records
     * @return QueryResultInterface
     */
    public function findAllWithOffsetAndLimit($offset, $limit)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->equals('imported', 1)
        )->setOffset($offset)
            ->setLimit($limit)
            ->execute();
    }

    /**
     * Returns faq records which are imported and match the session number.
     * The result set is sliced by the given offset and limit.
     *
     * @param integer $sessionNumber
     * @param integer $offset
     * @param integer $limit
     * @throws Exception\InvalidSessionNumberException
     * @return QueryResultInterface
     */
    public function findByImportedAndNotInSessionWithLimit($sessionNumber, $offset, $limit)
    {
        if ($sessionNumber == 0) {
            throw new Exception\InvalidSessionNumberException();
        }
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('imported', 1),
                $query->logicalNot(
                    $query->equals('sessionNumber', $sessionNumber)
                )
            )
        )->setOffset($offset)
            ->setLimit($limit)
            ->execute();
    }

    /**
     * @param    string $tioTheme The TiO theme to match
     * @return QueryResultInterface
     */
    public function findByTioTheme($tioTheme)
    {
        return $this->findByValueInCSVField($tioTheme, 'owmsMantleSubjects122');
    }

    /**
     * @param    array $tioThemes The TiO theme to match
     * @return QueryResultInterface
     */
    public function findByTioThemes(array $tioThemes)
    {
        return $this->findByValueInCSVField($tioThemes, 'owmsMantleSubjects122');
    }
}

