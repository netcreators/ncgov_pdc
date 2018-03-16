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
class Destination extends Base
{
    /**
     * @var string
     */
    protected $hidden = '';
    /**
     * @var string
     */
    protected $name = '';

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
     * @return \Netcreators\NcgovPdc\Domain\Model\Destination instance
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
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
     * @return \Netcreators\NcgovPdc\Domain\Model\Destination instance
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns this blog as a formatted string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name . ';';
    }
}

