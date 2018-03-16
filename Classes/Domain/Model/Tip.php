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
class Tip extends Base
{

    CONST STATE_NONE = 0, STATE_ISSUED = 1, STATE_IN_PROGRESS = 2, STATE_PROCESSED = 3, STATE_APPROVED = 4;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime
     */
    protected $datetime;

    /**
     * @var integer
     */
    protected $state;

    /**
     * Timestamp of last modification
     * @var integer
     */
    protected $tstamp = 0;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $creator;

    /**
     * Checks if the tips is not approved
     * @return bool
     */
    public function getIsNotApproved()
    {
        return $this->state !== self::STATE_APPROVED;
    }

    /**
     * Gets the state
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the state
     * @param integer $state
     * @return \Netcreators\NcgovPdc\Domain\Model\Tip instance
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Sets the tstamp
     * @param integer $tstamp
     * @return \Netcreators\NcgovPdc\Domain\Model\Tip instance
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
        return $this;
    }

    /**
     * Gets the tstamp
     * @return integer
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Gets the datetime
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Sets the date time of the tip
     * @param \DateTime $datetime
     * @return \Netcreators\NcgovPdc\Domain\Model\Tip instance
     */
    public function setDatetime(\DateTime $datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * Sets the currently associated product.
     * @param Product $product the product
     * @return \Netcreators\NcgovPdc\Domain\Model\Tip instance
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Returns the associated product.
     * @return Product the currently associated product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Sets the user who created the tip.
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $creator
     * @return \Netcreators\NcgovPdc\Domain\Model\Tip instance
     */
    public function setCreator(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $creator)
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * Returns the user which created this record.
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser the user
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Gets the creators name
     * @return string
     */
    public function getCreatorName()
    {
        return $this->creator->getName();
    }

    /**
     * Gets the name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     * @param string $name
     * @return \Netcreators\NcgovPdc\Domain\Model\Tip instance
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the description
     * @param string $description
     * @return \Netcreators\NcgovPdc\Domain\Model\Tip instance
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets the description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns this record as a formatted string
     * @return string
     */
    public function __toString()
    {
        return $this->name . ';' . $this->description;
    }
}

