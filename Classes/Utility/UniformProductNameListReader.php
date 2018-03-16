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

namespace Netcreators\NcgovPdc\Utility;

use Netcreators\NcgovPdc\Xml\UniformProductNameList\Parser;

/**
 * Utility which read the list of uniform product names (UPL) provided by the government.
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Utility
 */
class UniformProductNameListReader implements \TYPO3\CMS\Core\SingletonInterface
{

    const UPL_INPUT_FILE = 'EXT:ncgov_pdc/Resources/Private/Data/overheidUniformeProductnaam_v1.1.xml';

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    protected $uniformProductNames = array();

    protected $initialized = false;

    public function __construct()
    {
        // Call \Netcreators\NcgovPdc\Utility\UniformProductNameListReader::readUniformProductNames() lazily to initialize, after DI is done.
        $this->initialized = false;
    }

    /**
     * Check if the class was properly initialized and initialize if necessary.
     * @return void
     */
    public function checkInitialized()
    {
        if (!$this->initialized) {
            $this->readUniformProductNames();
        }
    }

    /**
     * Returns the names for the specified resourceIdentifiers
     * @param $uniformProductNameIdentifiers array    containing the resourceIdentifiers
     * @return array    containing the uniform product names
     */
    public function getUniformProductNamesByResourceIdentifiers($uniformProductNameIdentifiers)
    {
        $this->checkInitialized();
        $uniformProductNames = array();
        if (is_array($uniformProductNameIdentifiers) && count($uniformProductNameIdentifiers) > 0) {
            foreach ($uniformProductNameIdentifiers as $identifier) {
                $uniformProductNames[$identifier] = $this->getUniformProductNameByResourceIdentifier($identifier);
            }
        }
        return $uniformProductNames;
    }

    /**
     * Returns all uniform product names
     * @return array associative array with $theme[code] = name
     */
    public function getUniformProductNames()
    {
        $this->checkInitialized();
        return $this->uniformProductNames;
    }

    /**
     * Returns theme name through the given resourceIdentifier
     * @param string $resourceIdentifier the resourceIdentifier
     * @return string the uniform product name
     */
    public function getUniformProductNameByResourceIdentifier($resourceIdentifier)
    {
        $this->checkInitialized();
        return $this->uniformProductNames[$resourceIdentifier]['name'];
    }

    /**
     * Tests whether the given resourceIdentifier is a valid uniform product name resourceIdentifier.
     * @param $resourceIdentifier string the resourceIdentifier
     * @return boolean
     */
    public function isValidUniformProductNameResourceIdentifier($resourceIdentifier)
    {
        $this->checkInitialized();
        return isset($this->uniformProductNames[$resourceIdentifier]);
    }

    /**
     * Reads all uniform product names from the input file.
     * @return void
     */
    public function readUniformProductNames()
    {
        $absFile = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(self::UPL_INPUT_FILE);

        $contents = file_get_contents($absFile);
        if ($contents !== false) {
            /** @var Parser $parser */
            $parser = $this->objectManager->get(
                Parser::class,
                $contents
            );
            $parser->parse();
            $this->uniformProductNames = $parser->getUniformProductNames();
        } else {
            // iets gooien (hell, yeah!)
        }
        $this->initialized = true;
    }
}

