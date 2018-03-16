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
use Netcreators\NcgovPdc\Domain\Search\SearchParameter;

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class RegistrationAction extends Base
{

    /**
     * @var boolean
     */
    protected $hidden = false;

    /**
     * @var integer
     */
    protected $type = 0;

    /**
     * @var Product
     */
    protected $product = null;

    /**
     * @var FrequentlyAskedQuestion
     */
    protected $frequentlyAskedQuestion = null;

    /**
     * @var string
     */
    protected $searchParameter = '';

    const    TYPE_PRODUCT = 0,
        TYPE_FREQUENTLYASKEDQUESTION = 1,
        TYPE_SEARCH = 2,
        TYPE_SEARCH_FREQUENTLYASKEDQUESTION = 3,
        TYPE_PRODUCT_FREQUENTLYASKEDQUESTION = 4;

    /**
     * Returns property hidden
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets property hidden
     * @param boolean $hidden the property
     * @return self for chaining
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

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
     * Returns property searchParameter
     * @return string
     */
    public function getSearchParameter()
    {
        return $this->searchParameter;
    }

    /**
     * Sets property searchParameter
     * @param SearchParameter $parameter
     * @return self for chaining
     */
    public function setSearchParameter(SearchParameter $parameter)
    {
        $this->searchParameter = $parameter->toXml();
        return $this;
    }
}

