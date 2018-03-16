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
class Keyword extends Base
{

    /**
     * The keyword
     *
     * @var string
     */
    protected $keyword = '';

    /**
     * Gets the keyword
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Sets the keyword
     * @param string $keyword
     * @return self this instance for chaining
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Returns this keyword as a formatted string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->keyword;
    }
}

