<?php
/***************************************************************
 *  Copyright notice
 *
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

namespace Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40;

use Netcreators\NcgovPdc\Domain\Model\Product;

/**
 * Xml
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Xml
 */
class Parameter
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     * @inject
     */
    protected $uriBuilder = null;

    /**
     * @var array
     */
    protected $products = array();

    /**
     * @var string
     */
    protected $spatial = '';

    /**
     * @var string
     */
    protected $spatialScheme = '';

    /**
     * @var string
     */
    protected $spatialResourceIdentifier = '';

    /**
     * @var string
     */
    protected $authority = '';

    /**
     * @var string
     */
    protected $authorityScheme = '';

    /**
     * @var string
     */
    protected $authorityResourceIdentifier = '';

    /**
     * @var integer
     */
    protected $productPageId = 0;

    /**
     * @return \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    public function getUriBuilder()
    {
        return $this->uriBuilder;
    }

    /**
     * @param    array $products
     * @return self
     */
    public function addAllProducts($products)
    {
        if (is_array($products) || (is_object($products) && $products instanceof \Iterator)) {
            foreach ($products as $product) {
                $this->addProduct($product);
            }
        }
        return $this;
    }

    /**
     * @param    Product $product
     * @return self
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param    string $spatial
     * @return    self
     */
    public function setSpatial($spatial)
    {
        $this->spatial = $spatial;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpatial()
    {
        return $this->spatial;
    }

    /**
     * @param    string $spatialScheme
     * @return    self
     */
    public function setSpatialScheme($spatialScheme)
    {
        $this->spatialScheme = $spatialScheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpatialScheme()
    {
        return $this->spatialScheme;
    }

    /**
     * @param    string $spatialResourceIdentifier
     * @return    self
     */
    public function setSpatialResourceIdentifier($spatialResourceIdentifier)
    {
        $this->spatialResourceIdentifier = $spatialResourceIdentifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpatialResourceIdentifier()
    {
        return $this->spatialResourceIdentifier;
    }

    /**
     * @param    string $authority
     * @return    self
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * @param    string $authorityScheme
     * @return    self
     */
    public function setAuthorityScheme($authorityScheme)
    {
        $this->authorityScheme = $authorityScheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorityScheme()
    {
        return $this->authorityScheme;
    }

    /**
     * @param    string $authorityResourceIdentifier
     * @return    self
     */
    public function setAuthorityResourceIdentifier($authorityResourceIdentifier)
    {
        $this->authorityResourceIdentifier = $authorityResourceIdentifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorityResourceIdentifier()
    {
        return $this->authorityResourceIdentifier;
    }

    /**
     * @param    integer $id
     * @return    self
     */
    public function setProductDetailPageId($id)
    {
        $this->productPageId = $id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getProductPageId()
    {
        return $this->productPageId;
    }
}

