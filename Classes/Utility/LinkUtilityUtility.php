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

namespace Netcreators\NcgovPdc\Utility;

/**
 * Utility
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Utility
 */
class LinkUtility
{
    /**
     * Checks if the link is a link to a TYPO3 page
     * @param string $link the link
     * @return boolean
     */
    public static function isLinkToPage($link)
    {
        return is_numeric($link);
    }

    /**
     * Checks if the link is a link to a file in this typo3 installation
     * @param string $link the link
     * @return boolean
     */
    public static function isLinkToFile($link)
    {
        return file_exists(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(urldecode($link))) || file_exists(
            \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($link)
        );
    }

    /**
     * Returns a valid link (with http://baseuri prepended).
     * @param string $link the link
     * @return string    valid link
     */
    public static function getInternalLink($link)
    {
        $baseURI = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        if (!\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($link, $baseURI)) {
            $link = $baseURI . $link;
        }
        $result = $link;
        if (!empty($link) && substr($link, 0, 7) != 'http://' && substr($link, 0, 8) != 'https://') {
            $result = 'http://' . $link;
        }
        return $result;
    }

    /**
     * Returns a valid link (with http:// prepended).
     * @param string $link the link
     * @return string    valid link
     */
    public static function getExternalLink($link)
    {
        $result = $link;
        if (!empty($link) && substr($link, 0, 7) != 'http://' && substr($link, 0, 8) != 'https://') {
            $result = 'http://' . $link;
        }
        return $result;
    }
}

