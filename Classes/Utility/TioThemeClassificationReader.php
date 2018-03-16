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

use Netcreators\NcgovPdc\Xml\TioThemeClassification\Parser;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility which read the classification themes provided by the govenerment.
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Utility
 */
class TioThemeClassificationReader implements \TYPO3\CMS\Core\SingletonInterface
{

    const THEME_INPUT_FILE = 'EXT:ncgov_pdc/Resources/Private/Data/overheidThemaindelingOverheid_v1.6.xml';

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    protected $themes = array();
    protected $allThemes = array();

    protected $initialized = false;

    public function __construct()
    {
        // Call self::readTioThemes() lazily to initialize, after DI is done.
        $this->initialized = false;
    }

    /**
     * Check if the class was properly initialized.
     * @return void
     */
    public function checkInitialized()
    {
        if (!$this->initialized) {
            $this->readTioThemes();
        }
    }

    /**
     * Returns the names for the specified codes
     * @param $themeIdentifiers array    containing the codes
     * @return array    containing the names
     */
    public function getTioThemesByIdentifiers($themeIdentifiers)
    {
        $this->checkInitialized();
        $themeNames = array();
        if (is_array($themeIdentifiers) && count($themeIdentifiers) > 0) {
            foreach ($themeIdentifiers as $identifier) {
                $theme = $this->getTioThemeById($identifier);
                if (!$theme) {
                    continue;
                }
                $themeNames[] = $theme;
            }
        }
        return $themeNames;
    }

    /**
     * Returns all themes
     * @return array associative array with $theme[code] = name
     */
    public function getTioThemes()
    {
        $this->checkInitialized();
        return $this->allThemes;
    }

    /**
     * Returns all the child themes for the given parent id
     * @param string $parent the parent id
     * @return mixed the child themes (array) or false (boolean) if not found
     */
    public function getChildTioThemesFor($parent)
    {
        $this->checkInitialized();
        return $this->themes[$parent]['children'];
    }

    /**
     * Returns all the parent themes
     * @return array
     */
    public function getParentTioThemes()
    {
        $this->checkInitialized();
        return $this->themes;
    }

    /**
     * Returns id for selected theme
     * @param string $name
     * @return string the id
     */
    public function getTioThemeIdByUrlName($name)
    {
        $this->checkInitialized();
        $result = false;
        foreach ($this->allThemes as $theme) {
            if ($theme['urlName'] == $name) {
                $result = $theme['id'];
                break;
            }
        }
        return $result;
    }

    /**
     * Returns theme name through the given id
     * @param string $id the identifier
     * @return string the name
     */
    public function getTioThemeNameById($id)
    {
        $this->checkInitialized();
        return $this->allThemes[$id]['name'];
    }

    /**
     * Returns theme element through the given id
     * @param string $id the identifier
     * @return array the element
     */
    public function getTioThemeById($id)
    {
        $this->checkInitialized();
        return $this->allThemes[$id];
    }

    /**
     * Returns the parent theme from the specified id
     * @param string $id the identifier
     * @return mixed false if not found, array the theme element
     */
    public function getParentTioThemeById($id)
    {
        $this->checkInitialized();
        $id = (string)$id;
        $result = false;
        foreach ($this->themes as $parentTheme) {
            if (isset($parentTheme['children'][$id])) {
                $result = $parentTheme;
                break;
            }
        }
        return $result;
    }

    /**
     * Tests whether the given id is a valid theme id.
     * @param $id string the id
     * @return boolean
     */
    public function isValidTioThemeId($id)
    {
        $this->checkInitialized();
        return isset($this->allThemes[$id]);
    }

    /**
     * Reads all themes from the input file.
     * @return void
     */
    public function readTioThemes()
    {
        $absFile = GeneralUtility::getFileAbsFileName(self::THEME_INPUT_FILE);

        $contents = file_get_contents($absFile);
        if ($contents !== false) {
            /** @var Parser $parser */
            $parser = $this->objectManager->get(
                Parser::class,
                $contents
            );
            $parser->parse();
            $this->allThemes = $parser->getAllTioThemeClassifications();
            $this->themes = $parser->getTioThemeClassifications();
        } else {
            // iets gooien
        }
        $this->initialized = true;
    }
}

