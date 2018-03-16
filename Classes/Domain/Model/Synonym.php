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
class Synonym extends Base
{

    /**
     * @var string
     */
    public $synonym = '';

    /**
     * @var \Netcreators\NcgovPdc\Domain\Model\Synonym
     */
    public $relatesTo = '';

    /**
     * @param string $synonym
     * @return $this
     */
    public function setSynonym($synonym)
    {
        $this->synonym = $synonym;
        return $this;
    }

    /**
     * @return string
     */
    public function getSynonym()
    {
        return $this->synonym;
    }

    /**
     * @return Synonym
     */
    public function getRelatesTo()
    {
        return $this->relatesTo;
    }

    /**
     * @param Synonym $relatesTo
     */
    public function setRelatesTo($relatesTo)
    {
        $this->relatesTo = $relatesTo;
    }

    /**
     * Returns this blog as a formatted string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->synonym . 'l' . $this->relatesTo;
    }
}

