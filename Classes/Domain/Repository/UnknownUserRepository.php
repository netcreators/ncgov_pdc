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

use Netcreators\NcgovPdc\Domain\Model\Product;

/**
 * Repository
 *
 * FIXME: Is this still used? Remove or Cargo Cult?
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Model
 */
class UnknownUserRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var string
     */
    protected $lookupTable = '';
    protected $orderBy;

    /**
     * Sets the lookup table for the user availability
     * @param string $table
     * @return self for chaining
     */
    public function setLookupTable($table)
    {
        $this->lookupTable = $table;
        return $this;
    }

    /**
     * Returns the lookup table.
     * @return string
     */
    public function getLookupTable()
    {
        return $this->lookupTable;
    }

    /**
     * Set the user ordering
     * @param string $order
     * @return self for chaining
     */
    public function setOrderBy($order)
    {
        $this->orderBy = $order;
        return $this;
    }

    /**
     * Returns the ordering
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Finds users connected to product. The connected table is yet unknown.
     *
     * @param Product $product the product
     * @return array
     */
    public function findByProduct(Product $product)
    {
        $table = $this->getLookupTable();

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();

        $q = 'SELECT r.*'
            . ' FROM tx_ncgovpdc_domain_model_product as l, tx_ncgovpdc_product_users_available_mm as mm, ' . $table . ' as r'
            . ' WHERE mm.uid_local = l.uid AND mm.uid_foreign = r.uid AND r.deleted = 0 AND l.uid = ' . $product->getUid(
            );
        if ($this->getOrderBy() != '') {
            $q .= ' ORDER BY ' . $this->getOrderBy();
        }
        $rawQueryResult = $query->statement($q)->execute(true);
        return $rawQueryResult;
    }


    /**
     * Finds users connected to product. The connected table is yet unknown.
     *
     * @param Product $product the product
     * @return array
     */
    public function findFeUsersByProduct(Product $product)
    {

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();

        $q = 'SELECT r.*'
            . ' FROM tx_ncgovpdc_domain_model_product as l, tx_ncgovpdc_product_responsible_mm as mm, fe_users as r'
            . ' WHERE mm.uid_local = l.uid AND mm.uid_foreign = r.uid AND r.deleted = 0 AND l.uid = ' . $product->getUid(
            );
        if ($this->getOrderBy() != '') {
            $q .= ' ORDER BY ' . $this->getOrderBy();
        }
        $result = $query->statement($q)->execute(true);
        return $result;
    }
}

