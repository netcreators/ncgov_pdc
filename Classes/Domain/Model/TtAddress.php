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

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class TtAddress extends \Netcreators\NcExtbaseLib\Domain\Model\TtAddress
{

    // FIXME: Map tx-prefixed column names to more readable properties and adjust setters and getters?

    /**
     * @var integer
     */
    protected $txNcgovpdcVacContactInstanceUid = 0;

    /**
     * @var string
     */
    protected $txNcgovpdcPostAddress = '';

    /**
     * @var string
     */
    protected $txNcgovpdcPostCity = '';

    /**
     * @var string
     */
    protected $txNcgovpdcPostZip = '';

    /**
     * @var string
     */
    protected $txNcgovpdcPostPOBox = '';

    /**
     * Returns property txNcgovpdcVacContactInstanceUid
     * @return integer
     */
    public function getTxNcgovpdcVacContactInstanceUid()
    {
        return $this->txNcgovpdcVacContactInstanceUid;
    }

    /**
     * Sets property txNcgovpdcVacContactInstanceUid
     * @param integer $txNcgovpdcVacContactInstanceUid the property
     * @return self for chaining
     */
    public function setTxNcgovpdcVacContactInstanceUid($txNcgovpdcVacContactInstanceUid)
    {
        $this->txNcgovpdcVacContactInstanceUid = $txNcgovpdcVacContactInstanceUid;
        return $this;
    }

    /**
     * Returns property txNcgovpdcPostAddress
     * @return string
     */
    public function getTxNcgovpdcPostAddress()
    {
        return $this->txNcgovpdcPostAddress;
    }

    /**
     * Sets property txNcgovpdcPostAddress
     * @param string $txNcgovpdcPostAddress the property
     * @return self for chaining
     */
    public function setTxNcgovpdcPostAddress($txNcgovpdcPostAddress)
    {
        $this->txNcgovpdcPostAddress = $txNcgovpdcPostAddress;
        return $this;
    }

    /**
     * Returns property txNcgovpdcPostCity
     * @return string
     */
    public function getTxNcgovpdcPostCity()
    {
        return $this->txNcgovpdcPostCity;
    }

    /**
     * Sets property txNcgovpdcPostCity
     * @param string $txNcgovpdcPostCity the property
     * @return self for chaining
     */
    public function setTxNcgovpdcPostCity($txNcgovpdcPostCity)
    {
        $this->txNcgovpdcPostCity = $txNcgovpdcPostCity;
        return $this;
    }

    /**
     * Returns property txNcgovpdcPostZip
     * @return string
     */
    public function getTxNcgovpdcPostZip()
    {
        return $this->txNcgovpdcPostZip;
    }

    /**
     * Sets property txNcgovpdcPostZip
     * @param string $txNcgovpdcPostZip the property
     * @return self for chaining
     */
    public function setTxNcgovpdcPostZip($txNcgovpdcPostZip)
    {
        $this->txNcgovpdcPostZip = $txNcgovpdcPostZip;
        return $this;
    }

    /**
     * Returns property txNcgovpdcPostPOBox
     * @return string
     */
    public function getTxNcgovpdcPostPOBox()
    {
        return $this->txNcgovpdcPostPOBox;
    }

    /**
     * Sets property txNcgovpdcPostPOBox
     * @param string $txNcgovpdcPostPOBox the property
     * @return self for chaining
     */
    public function setTxNcgovpdcPostPOBox($txNcgovpdcPostPOBox)
    {
        $this->txNcgovpdcPostPOBox = $txNcgovpdcPostPOBox;
        return $this;
    }

    /**
     * @return bool
     */
    public function getHasPostAddress()
    {
        return $this->getTxNcgovpdcPostAddress() || $this->getTxNcgovpdcPostZip() || $this->getTxNcgovpdcPostCity(
        ) || $this->getTxNcgovpdcPostPOBox();
    }
}

