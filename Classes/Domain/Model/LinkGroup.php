<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Frans van der Veen, de juiste oplossing
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
 * Class LinkGroup
 */
class LinkGroup extends Base
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceLinks;

    /**
     * @var integer
     */
    protected $sorting;

    /**
     * Initializer
     */
    public function initializeObject()
    {
        $this->referenceLinks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * @param int $sorting
     * @return \Netcreators\NcgovPdc\Domain\Model\LinkGroup instance
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
        return $this;
    }

    /**
     * @return int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $referenceLinks
     * @return \Netcreators\NcgovPdc\Domain\Model\LinkGroup instance
     */
    public function setReferenceLinks($referenceLinks)
    {
        $this->referenceLinks = $referenceLinks;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceLinks()
    {
        return $this->referenceLinks;
    }

    /**
     * @param string $title
     * @return \Netcreators\NcgovPdc\Domain\Model\LinkGroup instance
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}

