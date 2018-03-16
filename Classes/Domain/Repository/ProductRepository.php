<?php
/***************************************************************
 *  Copyright notice
 *
 *
 *  (c) 2009-2010 Frans van der Veen [Netcreators] <extensions@netcreators.com>
 *  (c) 2013 Leonie Philine Bitto [Netcreators] <leonie@netcreators.nl>
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
use Netcreators\NcgovPdc\Domain\Model\Exception\SearchableColumnsNotSetException;
use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;
use Netcreators\NcgovPdc\Domain\Model\Product;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Repository
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>, Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Model
 */
class ProductRepository extends BaseRepository
{

    /**
     * @var array    the columns which will be searched
     */
    protected $searchableColumns = array();

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseHandle;

    /**
     * Constructs a new ProductRepository
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        parent::__construct($objectManager);

        $this->setDefaultOrderings(
            array(
                'name' => QueryInterface::ORDER_ASCENDING
            )
        );

        $this->databaseHandle = $GLOBALS['TYPO3_DB'];
    }

    /**
     * FIXME: Urgently refactor absolutely unmaintained and unmaintainable methods, like, among others:
     *    - findAllLetters
     *  - sortByWeight
     *  - findByFirstLetter
     *  - findBySearch
     *  - ...
     */

    /**
     * Returns all first letters as an array.
     * @param string $activeLetter
     * @param string $audience
     * @throws \TYPO3\CMS\Extbase\Exception
     * @return array containing first letters
     */
    public function findAllLetters($activeLetter = '', $audience = '')
    {

        $letters = array();
        // Create a complete alphabet (with both lower and uppercase letters and a count)
        for ($index = 0; $index < 26; $index++) {
            $letter = chr(97 + $index);

            $letters[$letter] = array(
                'firstLetter' => $letter,
                'count' => 0,
                'active' => ($activeLetter == $letter)
            );
        }

        $where = 'deleted = 0 AND hidden = 0 AND ' . $this->getStoragePidConditionSqlStatement();
        if ($audience) {
            $where .= ' AND audience like \'%' . $audience . '%\'';
        }

        $resource = $this->databaseHandle->exec_SELECTquery(
            'LOWER(SUBSTRING(name, 1, 1)) as firstletter',
            'tx_ncgovpdc_domain_model_product',
            $where,
            '', // group
            'firstletter', // order
            '' // limit
        );

        if ($resource) {
            while ($row = $this->databaseHandle->sql_fetch_assoc($resource)) {
                $letters[$row['firstletter']]['count']++;
            }

            $this->databaseHandle->sql_free_result($resource);
        }

        return $letters;
    }

    /**
     * Finds all products in storageFolder, ordered by name
     * @param integer $storagePid
     * @return QueryResultInterface
     */
    public function findAllInStorageFolder($storagePid)
    {

        $query = $this->createQuery();
        $query->setOrderings(array('name' => QueryInterface::ORDER_ASCENDING));
        $query->getQuerySettings()->setStoragePageIds(array($storagePid));

        return $query->execute();
    }

    /**
     * Sorts FAQ's by weight.
     *
     * @param array $products
     * @return array
     */
    public function sortByWeight(array $products)
    {
        // TODO refactor to baseclass

        $newProducts = array();
        if (is_array($products) && $products) {

            /** @var Product $product */
            foreach ($products as $product) {
                // generate uique sortable id
                $key = sprintf('%d%d', $product->getWeight(), $product->getUid());
                $newProducts[$key] = $product;
            }
            krsort($newProducts);
        }
        return $newProducts;
    }

    /**
     * Returns all product objects starting with specified first letter.
     *
     * @param string $letter
     * @param string $audience
     * @return QueryResultInterface
     */
    public function findByFirstLetter($letter, $audience = '')
    {

        $audienceWhere = '';
        if ($audience) {
            $audienceWhere .= ' AND audience like \'%' . $audience . '%\'';
        }

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $q = 'SELECT * FROM tx_ncgovpdc_domain_model_product WHERE UPPER(SUBSTRING(name,1,1)) LIKE BINARY \'' . strtoupper(
                $letter
            ) . '\' AND deleted = 0 AND hidden = 0 AND ' . $this->getStoragePidConditionSqlStatement(
            ) . $audienceWhere . ' ORDER BY name ASC';
        $result = $query->statement($q)->execute();
        return $result;
    }

    /**
     * Returns products matching the search
     *
     * @param array $searchWords
     * @throws NoSearchSpecifiedException
     * @throws SearchableColumnsNotSetException
     * @return QueryResultInterface
     */
    public function findBySearch(array $searchWords)
    {
        if (!$this->searchableColumns) {
            throw new SearchableColumnsNotSetException();
        }
        if (!$searchWords) {
            throw new NoSearchSpecifiedException();
        }

        // create query
        $query = $this->createQuery();
        // create select parts
        $matches = array();
        $currentMatch = false;

        foreach ($this->searchableColumns as $searchField) {
            $useSearchField = 'use_' . $searchField;
            foreach ($searchWords as $search) {
                $searchQuery = str_replace(' ', '%', strtolower($search));
                $matches[] = $query->logicalAnd(
                    $query->equals($useSearchField, 1),
                    $query->like($searchField, '%' . $searchQuery . '%')
                );
            }
        }
        foreach ($matches as $match) {
            if ($currentMatch === false) {
                $currentMatch = $match;
            } else {
                $currentMatch = $query->logicalOr(
                    $currentMatch,
                    $match
                );
            }
        }

        return $query->matching($currentMatch)->execute();
    }

    /**
     * Returns products found search words matched against the product name
     *
     * @param array $searchWords words being searched for
     * @throws NoSearchSpecifiedException
     * @return QueryResultInterface
     */
    public function findByName(array $searchWords)
    {
        if (!$searchWords) {
            throw new NoSearchSpecifiedException();
        }

        $nameMatch = false;
        $query = $this->createQuery();

        foreach ($searchWords as $search) {
            //$searchQuery = str_replace(' ', '%', strtolower($search));
            if ($nameMatch == false) {
                $nameMatch = $query->like('name', '%' . $search . '%');
            } else {
                $nameMatch = $query->logicalAnd(
                    $nameMatch,
                    $query->like('name', '%' . $search . '%')
                );
            }
        }

        return $query->matching($nameMatch)->execute();
    }

    /**
     * Collects all products.
     *
     * @param array <\Netcreators\NcgovPdc\Domain\Model\Product> &$existingProducts
     * @param array $productsToAdd
     * @return void
     */
    public function addAllUniqueProducts(array &$existingProducts, array $productsToAdd)
    {

        /** @var Product $product */
        foreach ($productsToAdd as $product) {
            if (!isset($existingProducts[$product->getUid()])) {
                $existingProducts[$product->getUid()] = $product;
            }
        }
    }

    /**
     * Collects all products.
     *
     * @param array $existingProducts A list of products; keys being the product UIDs.
     * @param array $productsToAdd
     * @param integer $maximum
     * @return array
     */
    public function combineUniqueProductsSortedWithMaximum(array $existingProducts, array $productsToAdd, $maximum)
    {
        $productsToAddFiltered = array();
        foreach ($productsToAdd as $uid => $product) {
            if (!isset($existingProducts[$uid])) {
                $productsToAddFiltered[] = $product;
            }
        }

        $existingProductCount = count($existingProducts);
        $productsToAddCount = count($productsToAddFiltered);

        if ($existingProductCount + $productsToAddCount > $maximum) {
            if (round($maximum / 2) + $productsToAddCount <= $maximum) {
                $existingProductCount = round($maximum / 2) + ($maximum - round($maximum / 2) - $productsToAddCount);
            } else {
                $existingProductCount = round($maximum / 2);
            }
        }
        $result = array_slice($existingProducts, 0, $existingProductCount);
        $result += $productsToAddFiltered;

        return $result;
    }

    /**
     * Removes products from the list which are not in the given audience.
     *
     * @param array $existingProducts
     * @param array $audience
     * @return void
     */
    public function removeNotInAudience(array &$existingProducts, array $audience)
    {

        // If the count of the audience is 2 then both audiences are to be shown, we needn't do anything!
        if (count($audience) == 1) {
            if ($existingProducts) {
                foreach ($existingProducts as $index => $product) {
                    /** @var Product $product */
                    if (!in_array($audience[0], $product->getAudience())) {
                        unset($existingProducts[$index]);
                    }
                }
            }
        }
    }

    /**
     * Returns products associated with given keywords.
     *
     * @param array $keywords the keywords
     * @return QueryResultInterface
     */
    public function findByKeyword(array $keywords)
    {

        $result = array();
        if ($keywords) {
            $where = '(k.keyword LIKE \'%' . implode(
                    '%\' OR keyword LIKE \'%',
                    $keywords
                ) . '%\') AND deleted = 0 AND hidden = 0';
            $where .= ' AND k.uid = m.uid_foreign';
            $subQuery = 'SELECT m.uid_local as uid FROM tx_ncgovpdc_product_keyword_mm m, tx_ncgovpdc_domain_model_keyword k WHERE ' . $where;
            $where = 'uid IN (' . $subQuery . ') AND deleted = 0 AND hidden = 0 AND ' . $this->getStoragePidConditionSqlStatement(
                );

            /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
            $query = $this->createQuery();
            $result = $query->statement('SELECT * FROM tx_ncgovpdc_domain_model_product where ' . $where)->execute();
        }
        return $result;
    }

    /**
     * Returns products associated with given keywords. Exact match on keywords.
     *
     * @param string $name
     * @return array the objects
     */
    public function findLikeName($name)
    {
        $query = $this->createQuery();
        return $query->matching($query->like('name', '%' . $name . '%'))->execute();
    }

    /**
     * Returns products associated with given keywords. Exact match on keywords.
     *
     * @param string $keyword the keyword
     * @return array the objects
     */
    public function findByKeywordExact($keyword)
    {
        $query = $this->createQuery();
        return $query->matching($query->like('keywords.keyword', '%' . $keyword . '%'))->execute();
    }

    /**
     * Returns products associated with the related synonym (so not the actual synonym, but the word the synonym relates to).
     *
     * @param array $synonyms
     * @return QueryResultInterface
     */
    public function findBySynonym(array $synonyms)
    {

        if (!$synonyms) {
            return null;
        }

        // synonym a -> synonym b -> synonym c -> synonym d (how deep into the tree do we go?)
        // assume one level for now

        $where = 'SELECT uid FROM tx_ncgovpdc_domain_model_synonym WHERE (synonym LIKE \'%' . implode(
                '%\' OR synonym LIKE \'%',
                $synonyms
            ) . '%\') AND deleted = 0 AND hidden = 0';
        $where = sprintf(
            'SELECT s.uid AS uid FROM tx_ncgovpdc_domain_model_synonym s, tx_ncgovpdc_synonym_relatesto_mm m WHERE s.uid = m.uid_foreign AND m.uid_local IN (%s)',
            $where
        );
        $where = sprintf(
            'SELECT p.uid as uid FROM tx_ncgovpdc_domain_model_product p, tx_ncgovpdc_product_synonym_mm m WHERE p.uid = m.uid_local AND m.uid_foreign IN (%s)',
            $where
        );
        $where = sprintf(
            'uid IN (%s) AND deleted = 0 AND hidden = 0 AND %s',
            $where,
            $this->getStoragePidConditionSqlStatement()
        );

        //select * from `tx_ncgovpdc_domain_model_product` p, `tx_ncgovpdc_product_synonym_mm` m
        //WHERE p.uid = m.uid_local
        //AND m.uid_foreign IN
        //(
        //select s.uid as uid from `tx_ncgovpdc_domain_model_synonym` s, `tx_ncgovpdc_synonym_relatesto_mm` m
        //WHERE
        //s.uid = m.uid_foreign
        //AND
        //m.uid_local IN (SELECT uid FROM `tx_ncgovpdc_domain_model_synonym` WHERE synonym = 'kek')
        //)

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        return $query->statement('SELECT * FROM tx_ncgovpdc_domain_model_product where ' . $where)->execute();
    }

    /**
     * Returns the products associated with given FrequentlyAskedQuestions.
     *
     * @param array $questions
     * @return array
     */
    public function findByFrequentlyAskedQuestions(array $questions)
    {
        $products = array();
        $productIds = array();

        if ($questions) {
            /** @var FrequentlyAskedQuestion $question */
            foreach ($questions as $question) {

                $productsFound = $this->findByFrequentlyAskedQuestion($question);

                /** @var Product $product */
                foreach ($productsFound as $product) {

                    // FIXME: Change to SplObjectStorage for this kind of collections of unique domain objects.
                    if ($productIds[$product->getUid()] !== true) {
                        $productIds[$product->getUid()] = true;
                        $products[] = $product;
                    }
                }
            }
            $products = $this->sortByWeight($products);
        }

        return $products;
    }

    /**
     * Returns the products associated with the given frequently asked question.
     * FIXME: Why is this using \TYPO3\CMS\Extbase\Persistence\Generic\Query::statement()? This could easily be done using the
     *        provided abstraction layer.
     *
     * @param FrequentlyAskedQuestion $faq
     * @return QueryResultInterface
     */
    private function findByFrequentlyAskedQuestion(FrequentlyAskedQuestion $faq)
    {

        $where = sprintf(
            'SELECT p.uid as uid FROM tx_ncgovpdc_domain_model_product p, tx_ncgovpdc_product_frequently_asked_question_mm m WHERE p.uid = m.uid_local AND m.uid_foreign = %d AND p.deleted = 0 AND p.hidden = 0',
            $faq->getUid()
        );
        $where = sprintf(
            'uid IN (%s) AND %s',
            $where,
            $this->getStoragePidConditionSqlStatement()
        );
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        return $query->statement('SELECT * FROM tx_ncgovpdc_domain_model_product WHERE ' . $where)->execute();
    }

    /**
     * Returns all product records with given pid.
     * @param integer $pid the parent page id
     * @return QueryResultInterface
     */
    public function findByPid($pid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        return $query->matching($query->equals('pid', $pid))->execute();
    }

    /**
     * Sets the columns which can be searched
     * @param array $columns
     * @return void
     */
    public function setSearchableColumns(array $columns)
    {
        $this->searchableColumns = $columns;
    }

    /**
     * Adds relation to product, since extbase cannot handle cyclic relations yet.
     * FIXME: Is this still true?
     *
     * @param Product $product
     * @param Product $related
     * @throws Exception
     * @return void
     */
    public function addRelatedProduct(Product $product, Product $related)
    {
        /** @var t3lib_db $TYPO3_DB */
        global $TYPO3_DB;

        $record = array(
            'uid_local' => $product->getUid(),
            'uid_foreign' => $related->getUid(),
        );
        $TYPO3_DB->exec_INSERTquery('tx_ncgovpdc_product_relatedproducts_mm', $record);
        $sErr = $TYPO3_DB->sql_error();
        if (!empty($sErr)) {
            throw new Exception(
                'Query error:' . $sErr
            );
        }
    }

    /**
     * Returns product records which are imported and match the owmscore identifier.
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
     * Returns product records which are imported and match the SCMeta Product ID.
     *
     * @param string $scmetaProductId
     * @return QueryResultInterface
     */
    public function findImportedByScmetaProductId($scmetaProductId)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('imported', 1),
                $query->equals('scmetaProductId', $scmetaProductId)
            )
        )->execute();
    }

    /**
     * Returns product records which are imported and match the session number.
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
     * @param    string $tioTheme The TiO theme to match
     * @return QueryResultInterface
     */
    public function findByTioTheme($tioTheme)
    {
        return $this->findByValueInCSVField($tioTheme, 'tioThemes');
    }

    /**
     * @param    array $tioThemes The TiO theme to match
     * @return QueryResultInterface
     */
    public function findByTioThemes(array $tioThemes)
    {
        return $this->findByValueInCSVField($tioThemes, 'tioThemes');
    }

    /**
     * @param    array $tioThemes The TiO theme to match
     * @param    array $audiences The audiences to match
     * @return QueryResultInterface
     */
    public function findByTioThemesAndAudiences(array $tioThemes, array $audiences)
    {
        return $this->findByValuesInCSVFields(
            array(
                'tioThemes' => $tioThemes,
                'audience' => $audiences
            )
        );
    }

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
                throw new \InvalidArgumentException('The uid must be a positive integer', 1245071889);
            }
        }

        $query = $this->createQuery();
        $queryResult = $query->matching($query->in('uid', $uids))->execute();

        $result = array();
        foreach ($uids as $uid) {
            /** @var Product $product */
            foreach ($queryResult as $product) {
                if ($uid == $product->getUid()) {
                    $result[] = $product;
                    break;
                }
            }
        }
        return $result;
    }
}

