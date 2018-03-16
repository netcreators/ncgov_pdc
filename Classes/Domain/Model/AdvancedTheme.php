<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2010 Frans van der Veen [Netcreators] <extensions@netcreators.com>
 *  (c) 2014 Leonie Philine Bitto <extensions@netcreators.nl>
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

namespace Netcreators\NcgovPdc\Domain\Model;

use Netcreators\NcExtbaseLib\Domain\Model\Base;

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class AdvancedTheme extends Base
{

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\AdvancedThemeRepository
     * @inject
     */
    protected $advancedThemeRepository = null;

    /**
     * @var string
     */
    protected $title = '';
    /**
     * @var string
     */
    protected $identifier = '';
    /**
     * @var \DateTime
     */
    protected $modified;
    /**
     * @var integer
     */
    protected $sessionNumber = 0;
    /**
     * @var boolean
     */
    protected $imported = false;
    /**
     * @var string
     */
    protected $keywords = '';
    /**
     * @var string
     */
    protected $withoutContext = '';
    /**
     * @var integer
     */
    protected $level = 0;
    /**
     * @var integer
     */
    protected $type = 0;
    /**
     * @var AdvancedTheme
     */
    protected $parent = null;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Product>
     * @lazy
     */
    protected $relatedProducts;

    /**
     * Initialization
     */
    public function initializeObject()
    {


        $this->relatedProducts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title property
     * @param string $title
     * @return self for chaining
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns the identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets the identifier property
     * @param string $identifier
     * @return self for chaining
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Sets the modified time
     * @param \DateTime $date
     */
    public function setModified($date)
    {
        $this->modified = $date;
    }

    /**
     * Gets the modified time
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Returns property sessionNumber
     * @return integer the session number
     */
    public function getSessionNumber()
    {
        return $this->sessionNumber;
    }

    /**
     * Sets property sessionNumber
     * @param integer $sessionNumber the property
     * @return self for chaining
     */
    public function setSessionNumber($sessionNumber)
    {
        $this->sessionNumber = $sessionNumber;
        return $this;
    }

    /**
     * Returns property imported
     * @return boolean
     */
    public function getImported()
    {
        return $this->imported;
    }

    /**
     * Sets property imported
     * @param boolean $imported the property
     * @return $this for chaining
     */
    public function setImported($imported)
    {
        $this->imported = $imported;
        return $this;
    }

    /**
     * Sets property keywords
     * @param mixed $keywords the property
     * @return self for chaining
     */
    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
            $this->keywords = implode(',', $keywords);
        } else {
            $this->keywords = $keywords;
        }
        return $this;
    }

    /**
     * Returns the keywords
     * @return array
     */
    public function getKeywords()
    {
        return \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(',', $this->keywords);
    }

    /**
     * Returns out of context
     */
    public function getWithoutContext()
    {
        return $this->withoutContext;
    }

    /**
     * Sets out of context
     * @param string $withoutContext
     * @return self for chaining
     */
    public function setWithoutContext($withoutContext)
    {
        $this->withoutContext = $withoutContext;
        return $this;
    }

    /**
     * Returns level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Sets level
     * @param integer $level
     * @return self for chaining
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Sets the type
     * @param integer $type the type
     * @return self for chaining
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets the type
     * @return integer the type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the currently associated parent
     * @param AdvancedTheme $parent the parent or NULL (for rootlevel)
     * @return self for chaining
     */
    public function setParent(AdvancedTheme $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Returns the associated parent advanced theme
     * @return AdvancedTheme
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getChildren()
    {
        return $this->advancedThemeRepository->findByParentOrderByTitle($this);
    }

    /**
     * Returns the related products
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Product>
     */
    public function getRelatedProducts()
    {
        return clone $this->relatedProducts;
    }

    /**
     * Returns the related products, ordered by name
     * @return array
     */
    public function getRelatedProductsOrderedByName()
    {
        $sorting = array();
        if ($this->relatedProducts && $this->relatedProducts->count() > 0) {
            /** @var Product $product */
            foreach ($this->relatedProducts as $product) {
                $identifier = $product->getUid();
                $name = $product->getName();
                $sorting[$name . $identifier] = $product;
            }
        }
        ksort($sorting);
        return $sorting;
    }

    /**
     * Sets property relatedProducts
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Product> $relatedProducts the property
     * @return self for chaining
     */
    public function setRelatedProducts($relatedProducts)
    {
        $this->relatedProducts = $relatedProducts;
        return $this;
    }

    /**
     * Returns wether the specific product is aready related to this product
     * @param Product $product
     * @return boolean true if the product is already related, false otherwise
     */
    public function hasRelatedProduct(Product $product)
    {
        return $this->relatedProducts->contains($product);
    }

    /**
     * Adds a related product to this product.
     * @param Product $product
     * @return self for chaining
     */
    public function addRelatedProduct(Product $product)
    {
        $this->relatedProducts->attach($product);
        return $this;
    }

    /**
     * Removes a related product from this product.
     * @param Product $product
     * @return self for chaining
     */
    public function removeRelatedProduct(Product $product)
    {
        $this->relatedProducts->detach($product);
        return $this;
    }

    /**
     * Removes all references to related products
     * @return self for chaining
     */
    public function removeAllRelatedProducts()
    {
        /*
         // read object storage?
         $product = new \Netcreators\NcgovPdc\Domain\Model\Product();
         $this->relatedProducts->attach($product);
         $this->relatedProducts->rewind();
         $this->relatedProducts->removeAll($this->relatedProducts);
         */
        $this->relatedProducts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

        // force dirty flag
        $product = new Product();
        $this->relatedProducts->attach($product);

        // keep empty
        $this->relatedProducts->removeAll($this->relatedProducts);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}

