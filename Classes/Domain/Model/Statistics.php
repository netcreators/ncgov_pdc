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

namespace Netcreators\NcgovPdc\Domain\Model;

use Netcreators\NcExtbaseLib\Domain\Model\Base;

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class Statistics extends Base
{
    /**
     * @var integer
     */
    protected $type = 0;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Model\Product
     */
    protected $product = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion
     */
    protected $frequentlyAskedQuestion = null;

    /**
     * @var string
     */
    protected $searchParameter = '';

    /**
     * @var integer
     */
    protected $loggedinCount = 0;
    /**
     * @var integer
     */
    protected $count = 0;
    /**
     * @var integer
     */
    protected $logtime = 0;

    const TYPE_PRODUCT = 0, TYPE_FREQUENTLYASKEDQUESTION = 1;

    /**
     * Returns property type
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets property type
     * @param integer $type the property
     * @return self for chaining
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns the uid of either the faq or the product.
     * @return integer
     */
    public function getRelatedUid()
    {
        $uid = null;
        switch ($this->type) {
            case self::TYPE_PRODUCT:
                if (isset($this->product) && $this->product != null) {
                    $uid = $this->product->getUid();
                }
                break;
            case self::TYPE_FREQUENTLYASKEDQUESTION:
                if (isset($this->frequentlyAskedQuestion) && $this->frequentlyAskedQuestion != null) {
                    $uid = $this->frequentlyAskedQuestion->getUid();
                }
                break;
        }
        return $uid;
    }

    /**
     * Returns property product
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Sets property product
     * @param Product $product the property
     * @return self for chaining
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Returns property frequentlyAskedQuestion
     * @return FrequentlyAskedQuestion
     */
    public function getFrequentlyAskedQuestion()
    {
        return $this->frequentlyAskedQuestion;
    }

    /**
     * Sets property frequentlyAskedQuestion
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion the property
     * @return self for chaining
     */
    public function setFrequentlyAskedQuestion(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        $this->frequentlyAskedQuestion = $frequentlyAskedQuestion;
        return $this;
    }

    /**
     * Returns property loggedin
     * @return integer
     */
    public function getLoggedinCount()
    {
        return $this->loggedinCount;
    }

    /**
     * Sets property loggedin
     * @param integer $loggedinCount the property
     * @return self for chaining
     */
    public function setLoggedinCount($loggedinCount)
    {
        $this->loggedinCount = $loggedinCount;
        return $this;
    }

    /**
     * Returns property count
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Sets property count
     * @param integer $count the property
     * @return self for chaining
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Returns property logtime
     * @return integer
     */
    public function getLogtime()
    {
        return $this->logtime;
    }

    /**
     * Sets property logtime
     * @param integer $logtime the property
     * @return self for chaining
     */
    public function setLogtime($logtime)
    {
        $this->logtime = $logtime;
        return $this;
    }

    /**
     * Returns this blog as a formatted string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->type . ';' . $this->product . ';' . $this->frequentlyAskedQuestion;
    }
}

