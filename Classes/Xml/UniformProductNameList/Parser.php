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

namespace Netcreators\NcgovPdc\Xml\UniformProductNameList;

/**
 * Xml
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Xml
 */
class Parser
{
    protected $xml = '';

    /**
     * @var \SimpleXMLElement
     */
    protected $xmlRoot = null;
    protected $errors = array();
    protected $uniformProductNames = array();

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
            $this->processUniformProductNames();
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
     * Returns the uniform product names as array
     * @return array
     */
    public function getUniformProductNames()
    {
        return $this->uniformProductNames;
    }

    /**
     * Creates uniform product names from the xml.
     * @return void
     */
    protected function processUniformProductNames()
    {
        $results = $this->xmlRoot->xpath('value');
        if (!is_array($results)) {
            return;
        }

        foreach ($results as $node) {
            list($resourceIdentifier, $element) = $this->createUniformProductNameElement($node);
            $this->uniformProductNames[$resourceIdentifier] = $element;
        }
    }

    /**
     * Creates a uniform product name element
     * @param $node
     * @return array
     */
    protected function createUniformProductNameElement($node)
    {
        return array(
            (string)$node->resourceIdentifier,
            array(
                'name' => (string)$node->prefLabel,
                'resourceIdentifier' => (string)$node->resourceIdentifier
            )
        );
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

