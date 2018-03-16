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

namespace Netcreators\NcgovPdc\Domain\Search\SamenwerkendeCatalogi40;

/**
 * Repository
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Domain
 */
class RemoteProduct
{
    protected $owmsCoreIdentifier = '';
    protected $owmsCoreTitle = '';
    protected $owmsCoreAuthority = '';
    protected $owmsCoreAuthorityScheme = '';
    protected $productHtml = '';

    /**
     * @param    string $owmsCoreIdentifier
     * @return self
     */
    public function setOwmsCoreIdentifier($owmsCoreIdentifier)
    {
        $this->owmsCoreIdentifier = $owmsCoreIdentifier;
        return $this;
    }

    /**
     * @return    string
     */
    public function getOwmsCoreIdentifier()
    {
        return $this->owmsCoreIdentifier;
    }

    /**
     * @param    string $owmsCoreTitle
     * @return self
     */
    public function setOwmsCoreTitle($owmsCoreTitle)
    {
        $this->owmsCoreTitle = $owmsCoreTitle;
        return $this;
    }

    /**
     * @return    string
     */
    public function getOwmsCoreTitle()
    {
        return $this->owmsCoreTitle;
    }

    /**
     * @param    string $owmsCoreAuthority
     * @return self
     */
    public function setOwmsCoreAuthority($owmsCoreAuthority)
    {
        $this->owmsCoreAuthority = $owmsCoreAuthority;
        return $this;
    }

    /**
     * @return    string
     */
    public function getOwmsCoreAuthority()
    {
        return $this->owmsCoreAuthority;
    }

    /**
     * @param    string $owmsCoreAuthorityScheme
     * @return self
     */
    public function setOwmsCoreAuthorityScheme($owmsCoreAuthorityScheme)
    {
        $this->owmsCoreAuthorityScheme = $owmsCoreAuthorityScheme;
        return $this;
    }

    /**
     * @return    string
     */
    public function getOwmsCoreAuthorityScheme()
    {
        return $this->owmsCoreAuthorityScheme;
    }

    /**
     * @param    string $productHtml
     * @return self
     */
    public function setProductHtml($productHtml)
    {
        $this->productHtml = $productHtml;
        return $this;
    }

    /**
     * @return    string
     */
    public function getProductHtml()
    {
        return $this->productHtml;
    }
}

