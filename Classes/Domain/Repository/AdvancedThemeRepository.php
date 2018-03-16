<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2010 Frans van der Veen [Netcreators] <extensions@netcreators.com>
 *  (c) 2014 Leonie Philine Bitto <extensions@netcreators.nl>
 *  All rights reserved
 *
 *  This extension can be used only with permission from Netcreators.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Netcreators\NcgovPdc\Domain\Repository;

use Netcreators\NcgovPdc\Domain\Model\AdvancedTheme;
use Netcreators\NcgovPdc\Domain\Model\Product;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Repository
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @author Leonie Philine Bitto <extensions@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Repository
 */
class AdvancedThemeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Returns all root elements.
     *
     * @return QueryResultInterface
     */
    public function findAllRoots()
    {
        return $this->findByParentOrderByTitle(null);
    }

    /**
     * Returns all imported advanced themes.
     *
     * @return QueryResultInterface
     */
    public function findAllImported()
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->equals('imported', 1)
        )->execute();
    }

    /**
     * Returns advanced theme records which are imported and match the identifier.
     *
     * @param string $identifier
     * @return QueryResultInterface
     */
    public function findImportedByIdentifier($identifier)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('imported', 1),
                $query->equals('identifier', $identifier)
            )
        )->execute();
    }

    /**
     * Returns advanced theme records which are imported and match the given title.
     *
     * @param string $title
     * @param integer|boolean $imported
     * @return AdvancedTheme
     */
    public function findOneByTitleAndImported($title, $imported = 1)
    {
        $query = $this->createQuery();
        $queryResult = $query->matching(
            $query->logicalAnd(
                $query->equals('title', $title),
                $query->equals('imported', $imported)
            )
        )->setLimit(1)
            ->execute();
        return $queryResult->getFirst();
    }

    /**
     * Returns advanced theme records which match the given related product.
     *
     * @param Product $product
     * @return QueryResultInterface
     */
    public function findRelatedByProduct(Product $product)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->contains('relatedProducts', $product)
        )->setOrderings(array('title' => QueryInterface::ORDER_ASCENDING))
            ->execute();
    }

    /**
     * Finds advanced themes by parent, orders them by title.
     *
     * @param AdvancedTheme $advancedTheme the parent advanced theme
     * @return QueryResultInterface
     */
    public function findByParentOrderByTitle(AdvancedTheme $advancedTheme = null)
    {
        $query = $this->createQuery();
        return $query->matching(
        // Why do I have to jump through this "?:"-hoop to avoid NULL? Is the TCA not right for extbase?
        // Otherwise, though, TYPO3 will create an "parent IS NULL" query, which cannot work, since
        // the parent column is defined as "NOT NULL" and "no parent advanced theme" is represented by "0".
        // Shouldn't TYPO3 know this? Is the configuration wrong?
            $query->equals('parent', $advancedTheme ? $advancedTheme : 0)
        )->setOrderings(array('title' => QueryInterface::ORDER_ASCENDING))
            ->execute();
    }

    /**
     * Returns advanced theme records which are imported and match the session number.
     *
     * @param integer $sessionNumber
     * @throws Exception\InvalidSessionNumberException
     * @return QueryResultInterface
     */
    public function findByImportedAndNotInSession($sessionNumber)
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
        )->execute();
    }

    /**
     * Returns advanced theme records which match the given keyword.
     *
     * @param string $keyword
     * @return QueryResultInterface
     */
    public function findByKeyword($keyword)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalOr(
                array(
                    $query->equals('keywords', $keyword),
                    $query->like('keywords', $keyword . ',%'),
                    $query->like('keywords', '%,' . $keyword),
                    $query->like('keywords', '%,' . $keyword . ',%'),
                )
            )
        )->setOrderings(array('title' => QueryInterface::ORDER_ASCENDING))
            ->execute();
    }


    /**
     * Returns advanced theme records which are imported and match the advanced theme type, while not being imported
     * in the current session.
     *
     * @param integer $sessionNumber
     * @param string $type
     * @throws Exception\InvalidSessionNumberException
     * @return QueryResultInterface
     */
    public function findByImportedAndNotInSessionInType($sessionNumber, $type)
    {
        if ($sessionNumber == 0) {
            throw new Exception\InvalidSessionNumberException();
        }
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('imported', 1),
                $query->equals('txNcgovpdcaplusType', $type),
                $query->logicalNot(
                    $query->equals('sessionNumber', $sessionNumber)
                )
            )
        )->execute();
    }
}

