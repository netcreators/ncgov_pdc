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

namespace Netcreators\NcgovPdc\Xml\Search\SamenwerkendeCatalogi40;

use Netcreators\NcExtbaseLib\Service\Xml\XPath\XPathHelper;
use Netcreators\NcgovPdc\Domain\Search\SamenwerkendeCatalogi40\RemoteProduct;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Xml
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Xml
 */
class ResponseParser
{
    /**
     * @var string
     */
    protected $data = '';

    /**
     * @var \SimpleXMLElement
     */
    protected $xmlRoot = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    /**
     * @param    string $data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns the total result count
     * @return int
     */
    public function getTotalResultsCount()
    {
        if (!$this->xmlRoot) {
            $this->processData();
        }
        return intval((string)$this->xmlRoot->xpath('/searchRetrieveResponse/numberOfRecords'));
    }

    /**
     * Returns the start index of the next batch of records (next result page)
     * @return int
     */
    public function getNextStartRecordIndex()
    {
        if (!$this->xmlRoot) {
            $this->processData();
        }
        return intval((string)$this->xmlRoot->xpath('/searchRetrieveResponse/nextRecordPosition'));
    }

    /**
     * Extract remote products from the given data.
     * @return array
     */
    public function getRemoteProducts()
    {
        if (!$this->xmlRoot) {
            $this->processData();
        }

        $remoteProducts = array();
        $records = $this->xmlRoot->xpath(
            '/sru:searchRetrieveResponse/sru:records/sru:record/sru:recordData/overheidsru:gzd/overheidsru:originalData'
        );

        if (!count($records) && count($this->xmlRoot->diagnostic)) {
            foreach ($this->xmlRoot->diagnostic as $diagnostic) {
                GeneralUtility::devLog(
                    $diagnostic->details . ': ' . $diagnostic->message,
                    'ncgov_pdc',
                    GeneralUtility::SYSLOG_SEVERITY_ERROR,
                    (array)$diagnostic
                );
            }
        }

        /** @var \SimpleXmlElement $recordNode */
        foreach ($records as $recordNode) {
            $remoteProducts[] = $this->getRemoteProductFromNode($recordNode);
        }

        return $remoteProducts;
    }

    /**
     * Creates a RemoteProduct from a given XML node.
     * @param \SimpleXmlElement $node
     * @return RemoteProduct
     */
    protected function getRemoteProductFromNode(\SimpleXmlElement $node)
    {
        /** @var RemoteProduct $remoteProduct */
        $remoteProduct = $this->objectManager->get(RemoteProduct::class);
        $remoteProduct->setOwmsCoreIdentifier(
            XPathHelper::getNodeValue(
                'overheidproduct:scproduct/overheidproduct:meta/overheidproduct:owmskern/dcterms:identifier',
                $node
            )
        );
        $remoteProduct->setOwmsCoreTitle(
            XPathHelper::getNodeValue(
                'overheidproduct:scproduct/overheidproduct:meta/overheidproduct:owmskern/dcterms:title',
                $node
            )
        );
        $remoteProduct->setOwmsCoreAuthority(
            XPathHelper::getNodeValue(
                'overheidproduct:scproduct/overheidproduct:meta/overheidproduct:owmskern/overheid:authority',
                $node
            )
        );
        $remoteProduct->setOwmsCoreAuthorityScheme(
            XPathHelper::getNodeAttributeValue(
                'overheidproduct:scproduct/overheidproduct:meta/overheidproduct:owmskern/overheid:authority/@scheme',
                $node,
                'scheme'
            )
        );

        $productHtml = html_entity_decode(
            XPathHelper::getNodeValue(
                'overheidproduct:scproduct/overheidproduct:body/overheidproduct:productHTML',
                $node
            )
        );
        // Fallback
        if (!$productHtml) {
            $productHtml = html_entity_decode(
                XPathHelper::getNodeValue(
                    'overheidproduct:scproduct/overheidproduct:meta/overheidproduct:owmsmantel/dcterms:abstract',
                    $node
                )
            );
        }
        $remoteProduct->setProductHtml($productHtml);

        return $remoteProduct;
    }

    /**
     * Initializes SimpleXML to extract data from its nodes.
     * @throws \Netcreators\NcgovPdc\Xml\Search\SamenwerkendeCatalogi40\ResponseParser\Exception
     * @return void
     */
    protected function processData()
    {
        if (empty($this->data)) {
            throw new \Netcreators\NcgovPdc\Xml\Search\SamenwerkendeCatalogi40\ResponseParser\Exception('Error: No XML data supplied.');
        }

        libxml_clear_errors();
        $this->xmlRoot = simplexml_load_string($this->data, '\SimpleXmlElement', LIBXML_NOERROR);
        $lastError = libxml_get_last_error();
        if ($this->xmlRoot === false || $lastError !== false) {
            throw new \Netcreators\NcgovPdc\Xml\Search\SamenwerkendeCatalogi40\ResponseParser\Exception('Xml parsing failed with message: ' . $lastError->code . ': ' . $lastError->message);
        }

        $this->xmlRoot->registerXPathNamespace('sru', 'http://www.loc.gov/zing/srw/'); // __ns
        $this->xmlRoot->registerXPathNameSpace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->xmlRoot->registerXPathNamespace('overheidsru', 'http://standaarden.overheid.nl/sru');
        $this->xmlRoot->registerXPathNamespace('dcterms', 'http://purl.org/dc/terms/');
        $this->xmlRoot->registerXPathNamespace('overheid', 'http://standaarden.overheid.nl/owms/terms/');
        $this->xmlRoot->registerXPathNamespace('overheidproduct', 'http://standaarden.overheid.nl/product/terms/');
    }
}

