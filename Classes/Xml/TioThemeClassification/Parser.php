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

namespace Netcreators\NcgovPdc\Xml\TioThemeClassification;

/**
 * Xml
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Xml
 */
class Parser
{
    protected $xml = '';

    /**
     * @var \SimpleXmlElement
     */
    protected $xmlRoot;
    protected $errors = array();
    protected $tioThemeClassifications = array();
    protected $allTioThemeClassifications = array();

    public function __construct($resultXml)
    {
        $this->xml = $resultXml;
    }

    public function parse()
    {
        if (empty($this->xml)) {
            $this->addError('Error: no XML supplied');
        } else {
            $this->parseXml();
            $this->processThemes();
        }
    }

    /**
     * Checks the errors.
     * @return boolean true when errors have occurred
     */
    public function hasErrorStatus()
    {
        return count($this->errors) > 0;
    }

    /**
     * Adds an errors to the internal error list.
     * @param string $error the error
     * @return self for chaining
     */
    protected function addError($error)
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * Returns the errors.
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns the theme classification array (nested)
     * @return array
     */
    public function getTioThemeClassifications()
    {
        return $this->tioThemeClassifications;
    }

    /**
     * Returns all theme classifications (flat)
     * @return array
     */
    public function getAllTioThemeClassifications()
    {
        return $this->allTioThemeClassifications;
    }

    /**
     * Creates themes from the xml.
     * @return void
     */
    protected function processThemes()
    {
        $results = $this->xmlRoot->xpath('value');
        if (!is_array($results)) {
            return;
        }

        foreach ($results as $node) {
            $element = $this->createTioThemeClassificationElement($node);
            $identifier = $element['id'];
            $element['indentedName'] = $element['name'];
            $this->allTioThemeClassifications[$identifier] = $element;
            $this->tioThemeClassifications[$identifier] = $element;
            $this->tioThemeClassifications[$identifier]['children'] = $this->getAndAddSubThemes($node);
            $this->allTioThemeClassifications[$identifier]['children'] = $this->tioThemeClassifications[$identifier]['children'];
        }
    }

    /**
     * Returns the themes in this node
     * @param \SimpleXmlElement $node the node
     * @return array
     */
    protected function getAndAddSubThemes($node)
    {
        $elements = array();
        $subNodes = $node->xpath('value');
        foreach ($subNodes as $subNode) {
            $element = $this->createTioThemeClassificationElement($subNode);
            $identifier = $element['id'];
            $element['indentedName'] = '- ' . $element['name'];
            $this->allTioThemeClassifications[$identifier] = $element;
            $elements[$identifier] = $element;
        }
        return $elements;
    }

    /**
     * Creates a theme classification element
     * @param $node
     * @return array
     */
    protected function createTioThemeClassificationElement($node)
    {
        return array(
            'id' => (string)$node['id'],
            'name' => (string)$node['prefLabel'],
            'resourceIdentifier' => (string)$node['resourceIdentifier'],
            'urlName' => $this->getUrlName((string)$node['prefLabel'])
        );
    }

    /**
     * Returns the theme name prepared for url usage
     * @param string $name the name to be URLified
     * @return string
     */
    protected function getUrlName($name)
    {
        return strtolower(str_replace(array(' ', ',', ':', '\'', '(', ')'), array('-', '', '', '', '', ''), $name));
    }

    /**
     * Parses the xml.
     * @throws Exception\XmlParseFailureException
     * @return void
     */
    protected function parseXml()
    {
        libxml_clear_errors();
        $this->xmlRoot = simplexml_load_string($this->xml, 'SimpleXMLElement', LIBXML_NOERROR);
        $lastError = libxml_get_last_error();
        if ($this->xmlRoot === false || $lastError !== false) {
            throw new Exception\XmlParseFailureException('Xml parsing failed with message: ' . $lastError->code . ': ' . $lastError->message);
        }
    }
}

