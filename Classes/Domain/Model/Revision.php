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
class Revision extends Base
{

    /**
     * @var string
     */
    public $version = '';
    /**
     * @var string
     */
    public $dateTime = '';
    /**
     * @var string
     */
    public $author = '';
    /**
     * @var string
     */
    public $comment = '';
    /**
     * @var string
     */
    public $title = '';
    /**
     * @var string
     */
    protected $revisionTypes = '';

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param $version
     * @return \Netcreators\NcgovPdc\Domain\Model\Revision instance
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return \Netcreators\NcgovPdc\Domain\Model\Revision instance
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return \Netcreators\NcgovPdc\Domain\Model\Revision instance
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return \Netcreators\NcgovPdc\Domain\Model\Revision instance
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return array
     */
    public function getRevisionTypes()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->revisionTypes);
    }

    /**
     * @param string $revisionTypes
     * @return \Netcreators\NcgovPdc\Domain\Model\Revision instance
     */
    public function setRevisionTypes($revisionTypes)
    {
        $this->revisionTypes = $revisionTypes;
        return $this;
    }

    /**
     * @return $this
     */
    public function removeAllRevisionTypes()
    {
        $this->revisionTypes = '';
        return $this;
    }

    /**
     * Adds revisionType
     * @param string $revisionType the identifier
     * @return \Netcreators\NcgovPdc\Domain\Model\Revision instance
     */
    public function addRevisionType($revisionType)
    {
        $revisionType = (string)$revisionType;
        $this->revisionTypes = implode(
            ',',
            array_unique(
                array_merge($this->getRevisionTypes(), array($revisionType))
            )
        );
        if ($this->revisionTypes[0] == ',') {
            $this->revisionTypes = substr($this->revisionTypes, 1);
        }
        return $this;
    }

    /**
     * @param $title
     */
    public function createTitle($title)
    {
        $this->title = sprintf(
            'versie:%s@%s:%s',
            $this->version,
            $this->dateTime->format('Y-m-d H:i'),
            $title
        );
    }

    /**
     * String representation
     * @return string
     */
    public function __toString()
    {
        return $this->version . ';' . $this->author . ';' . $this->comment;
    }
}

