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

namespace Netcreators\NcgovPdc\Service\File\Record\Converter;

use Netcreators\NcExtbaseLib\Domain\Model\IntermediateObject;
use Netcreators\NcExtbaseLib\Service\File\Record\Converter\ConverterInterface;
use Netcreators\NcExtbaseLib\Service\File\Record\Extractor\XmlExtractor;
use Netcreators\NcExtbaseLib\Service\Xml\XPath\XPathHelper;
use Netcreators\NcgovPdc\Domain\Model\ReferenceLink;
use Netcreators\NcgovPdc\Service\MultiPageProcess\FaqSynchronizer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Interface for converting generic objects to intermediate objects
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcExtbaseLib
 * @subpackage Service
 */
class XmlFaqConverter extends XmlExtractor implements ConverterInterface
{
    protected $xmlToEntityMapping = array(
        'vac:meta/vac:owmskern/dcterms:creator[1]' => 'owms_core_creator',
        '@vac:meta/vac:owmskern/dcterms:creator[@scheme] scheme' => 'owms_core_creator_scheme',
        'vac:meta/vac:owmskern/dcterms:identifier[1]' => array('uppercase', 'owms_core_identifier'),
        'vac:meta/vac:owmskern/dcterms:modified[1]' => array('DateTime', 'owms_core_modified'),
        'vac:meta/vac:owmskern/dcterms:title[1]' => array('entity_decoded_special', 'owms_core_title'),
        'vac:meta/vac:owmskern/dcterms:language[1]' => 'owms_core_language',
        'vac:meta/vac:owmskern/dcterms:spatial[1]' => 'owms_core_spatial',
        '@vac:meta/vac:owmskern/dcterms:spatial[@scheme] scheme' => 'owms_core_spatial_scheme',
        'vac:meta/vac:owmskern/dcterms:temporal/start[1]' => array('NotEmptyDateTime', 'owms_core_temporal_start'),
        'vac:meta/vac:owmskern/dcterms:temporal/end[1]' => array('NotEmptyDateTime', 'owms_core_temporal_end'),
        'vac:meta/vac:owmskern/overheid:authority[1]' => 'owms_mantle_authority',
        'vac:meta/vac:owmsmantel/dcterms:contributor[1]' => 'owms_mantle_contributor',
        'vac:meta/vac:owmsmantel/dcterms:available/start[1]' => array(
            'NotEmptyDateTime',
            'owms_mantle_available_start'
        ),
        'vac:meta/vac:owmsmantel/dcterms:available/end[1]' => array('NotEmptyDateTime', 'owms_mantle_available_end'),
        // 'vac:meta/vac:vacmeta/vac:prioriteit[1]' => 'priority', // Not or no longer in SDU VAC XML.
        // 'vac:meta/vac:vacmeta/vac:leverancierSysteem[1]' => 'supplier_system',
        'vac:meta/vac:vacmeta/vac:statusRedactie[1]' => 'editorial_state',
        'vac:meta/vac:vacmeta/vac:datumControle[1]' => 'verification_date',
        'deleted' => 'deleted', // for partial imports
        //'' => '',
        'Audience' => 'special',
        'Revision' => 'special',
        'Subject' => 'special',
        'Destination' => 'special',
        'Channel' => 'special',
        'ReferenceProduct' => 'special',
        'ReferenceFaq' => 'special',
//		'Authorities' => 'special', // KCC or PDC user groups
    );

    /**
     * @var array
     */
    protected $excludedItems;
    /**
     * @var boolean
     */
    protected $isPartialImport;

    /**
     * @var array
     */
    protected $nodes = array();

    /**
     * @var \Netcreators\NcgovPdc\Configuration\ConfigurationManager
     * @inject
     */
    protected $localConfigurationManager;

    /**
     * Sets the excluded items for this import
     * @param array $value the items
     */
    public function setExcludedItems($value)
    {
        $this->excludedItems = $value;
    }

    /**
     * Sets isPartialImport
     * @param boolean $value state
     */
    public function setIsPartialImport($value)
    {
        $this->isPartialImport = $value;
    }

    /**
     * Prepares the xml tree
     */
    protected function prepareProcessing()
    {
        $root = $this->getSimpleXmlElementRoot();

        $this->removeExcludedMappingItems();

        $this->nodes = $root->xpath('/vac:vacs/vac:vac');
    }

    /**
     * Returns the number of objects(non-PHPdoc)
     * @see \Netcreators\NcExtbaseLib\Service\File\Record\Converter\ConverterInterface::getObjectCount()
     */
    public function getObjectCount()
    {
        if (!$this->nodes) {
            $this->prepareProcessing();
        }
        return count($this->nodes);
    }

    /**
     * Returns if the page was finished processing
     * @param integer $offset
     * @param integer $size
     * @return array
     */
    public function getConvertedObjects($offset = 0, $size = 0)
    {
        $objects = array();
        if (!$this->nodes) {
            $this->prepareProcessing();
        }
        if ($offset == 0) {
            $offset = 0;
        }
        if ($size == 0) {
            $size = count($this->nodes);
        }
        $nodes = array_slice($this->nodes, $offset, $size);
        foreach ($nodes as $node) {
            $objects[] = $this->createIntermediatesFromNode($node);
        }
        return $objects;
    }

    /**
     * Creates an IntermediateObject from the xml node
     * @param \SimpleXMLElement $node
     * @return IntermediateObject
     */
    protected function createIntermediatesFromNode($node)
    {

        /** @var IntermediateObject $intermediate */
        $intermediate = $this->objectManager->get(IntermediateObject::class);
        $intermediate->setType(FaqSynchronizer::FAQ_INTERMEDIATE_KEY);
        foreach ($this->xmlToEntityMapping as $xpath => $nameInRecord) {
            if ($nameInRecord == 'special') {
                $this->performSpecialMapping($xpath, $node, $intermediate);
            } else {
                // if it starts with @ we will want to get an attribute
                if ($xpath[0] == '@') {
                    $xpath = substr($xpath, 1);
                    $attribute = GeneralUtility::trimExplode(' ', $xpath);
                    $xpath = $attribute[0];
                    $attribute = $attribute[1];

                    $value = XPathHelper::getNodeAttributeValue($xpath, $node, $attribute);
                    $newType = 'string';
                    if (is_array($nameInRecord)) {
                        $newType = $nameInRecord[0];
                        $nameInRecord = $nameInRecord[1];
                    }
                    $intermediate->setData($nameInRecord, $this->transformValue($newType, $value));
                } else {
                    $newType = 'string';
                    if (is_array($nameInRecord)) {
                        $newType = $nameInRecord[0];
                        $nameInRecord = $nameInRecord[1];
                    }
                    $value = XPathHelper::getNodeValue($xpath, $node);
                    $value = $this->transformValue($newType, $value);

                    $intermediate->setData($nameInRecord, $value);
                }
            }
        }
        return $intermediate;
    }

    /**
     * Remove specified fields from mapping
     * @return void
     */
    protected function removeExcludedMappingItems()
    {
        if (is_array($this->excludedItems) && count($this->excludedItems)) {
            foreach ($this->excludedItems as $key) {
                if (array_key_exists($key, $this->xmlToEntityMapping)) {
                    unset($this->xmlToEntityMapping[$key]);
                }
            }
        }
    }

    /**
     * Performs special mapping.
     * @param string $key the key to map
     * @param \SimpleXMLElement $node the node to read
     * @param IntermediateObject $intermediate the intermediate object
     *
     */
    protected function performSpecialMapping($key, $node, &$intermediate)
    {
        switch ($key) {
            case 'Audience':
                $intermediate->setData('owms_mantle_audience', $this->getAudiencesFromNode($node));
                break;
            case 'Revision':
                $intermediate->setData('revision', $this->getRevisionsFromNode($node));
                break;
            case 'Subject':
                $intermediate->setData('subject', $this->getSubjectsFromNode($node));
                break;
            case 'Destination':
                $intermediate->setData('destination', $this->getDestinationsFromNode($node));
                break;
            case 'Channel':
                $intermediate->setData('channel', $this->getChannelsFromNode($node));
                break;
            case 'ReferenceProduct':
                $intermediate->setData(
                    'referenceProduct',
                    $this->getReferenceLinksFromNode(
                        $node,
                        'vac:body/vac:verwijzingProduct',
                        ReferenceLink::TYPE_PRODUCT
                    )
                );
                break;
            case 'ReferenceFaq':
                $intermediate->setData(
                    'referenceFaq',
                    $this->getReferenceLinksFromNode(
                        $node,
                        'vac:body/vac:verwijzingVac',
                        ReferenceLink::TYPE_FREQUENTLY_ASKED_QUESTION
                    )
                );
                break;
//			case 'Authorities':
//				$intermediate->setData('authorities', [...]);
//				break;
        }
    }

    /**
     * Transforms value to the given type and returns the transformed value
     * @param string $type the new type
     * @param string $value the value
     * @return mixed the new, transformed value. NULL if type is not detected
     */
    protected function transformValue($type, $value)
    {
        $globalTimeZone = date_default_timezone_get();
        date_default_timezone_set('Europe/Amsterdam');

        $result = null;
        switch ($type) {
            case 'boolean':
                $value = trim(strtolower($value));
                if ($value === 'true') {
                    $result = true;
                }
                if ($value === 'false') {
                    $result = false;
                }
                if ($value === '1') {
                    $result = true;
                }
                if ($value === '0') {
                    $result = false;
                }
                break;
            case 'specialtext':
                $result = str_replace(chr(10), ' ', $value);
                break;
            case 'entity_decoded_special':
                $result = html_entity_decode((string)$value, ENT_QUOTES, 'UTF-8');
                $result = str_replace(chr(10), ' ', $result);
                break;
            case 'string':
                $result = (string)$value;
                break;
            case 'uppercase':
                $result = strtoupper((string)$value);
                break;
            case 'DateTime':
                $result = new \DateTime($value);
                break;
            case 'NotEmptyDateTime':
                $result = '';
                if ($value) {
                    $result = new \DateTime($value);
                }
                break;
            case 'timestamp':
                // Timestamps are always to be interpreted as UTC.
                // date_default_timezone_set() (above) makes sure that strtotime() does the correct conversion.
                $result = strtotime($value);

                break;
            case 'cleanstring':
                $result = $value;
                break;
        }
        date_default_timezone_set($globalTimeZone);
        return $result;
    }

    /**
     * Returns audiences from node
     * @param \SimpleXMLElement $node
     * @return array
     */
    protected function getAudiencesFromNode(\SimpleXMLElement $node)
    {
        return $this->getItemListFromNode($node, 'vac:meta/vac:owmsmantel/dcterms:audience');
    }

    /**
     * Returns a array of items from a given node
     * @param \SimpleXMLElement $node
     * @param string $xpath
     * @return array
     */
    protected function getItemListFromNode(\SimpleXMLElement $node, $xpath)
    {
        $itemList = array();
        $items = $node->xpath($xpath);
        if (count($items) > 0) {
            foreach ($items as $item) {
                $itemList[] = (string)$item;
            }
        }
        return $itemList;
    }

    /**
     * Returns revisions from node
     * @param \SimpleXMLElement $node
     * @return array
     */
    protected function getRevisionsFromNode(\SimpleXMLElement $node)
    {
        $itemList = array();
        $items = $node->xpath('vac:meta/vac:vacmeta/vac:revisionHistory/vac:revision');
        if (count($items) > 0) {
            foreach ($items as $item) {
                $listItem = array(
                    'version' => XPathHelper::getNodeValue('vac:version', $item),
                    'date_time' => new \DateTime(XPathHelper::getNodeValue('vac:dateTime', $item)),
                    'author' => XPathHelper::getNodeValue('vac:author', $item),
                    'comment' => XPathHelper::getNodeValue('vac:comment', $item),
                    'revisionType' => XPathHelper::getNodeValue('vac:revisionType[1]', $item),
                );
                $itemList[] = $listItem;
            }
        }
        return $itemList;
    }

    /**
     * Returns subjects from node
     * @param \SimpleXMLElement $node
     * @return array
     */
    protected function getSubjectsFromNode(\SimpleXMLElement $node)
    {
        return $this->getItemListFromNode($node, 'vac:meta/vac:owmsmantel/dcterms:subject');
    }

    /**
     * Returns subjects from node
     * @param \SimpleXMLElement $node
     * @return array
     */
    protected function getDestinationsFromNode(\SimpleXMLElement $node)
    {
        return $this->getItemListFromNode($node, 'vac:meta/vac:vacmeta/vac:bestemming');
    }

    /**
     * Returns subjects from node
     * @param \SimpleXMLElement $node
     * @return array
     */
    protected function getChannelsFromNode(\SimpleXMLElement $node)
    {
        $itemList = array();
        $items = $node->xpath('vac:body/vac:kanaal');

        if (count($items) > 0) {
            foreach ($items as $item) {

                $listItem = array(
                    'kanaal' => XPathHelper::getNodeAttributeValue('@type', $item, 'type'),
                    'vraag' => $this->transformValue(
                            'entity_decoded_special',
                            XPathHelper::getNodeValue('vac:vraag', $item)
                        ),
                    // APlus Antwoord
                    'antwoord' => $this->transformValue(
                            'entity_decoded_special',
                            XPathHelper::getNodeValue('vac:antwoord', $item)
                        ),
                    // SDU Antwoord
                    'antwoordTekst' => $this->transformValue(
                            'entity_decoded_special',
                            XPathHelper::getNodeValue('vac:antwoord/vac:antwoordTekst', $item)
                        ),
                    'antwoordProductVeld' => $this->transformValue(
                            'entity_decoded_special',
                            XPathHelper::getNodeValue('vac:antwoord/vac:antwoordProductVeld', $item)
                        ),
                    'antwoordAdres' => $this->getContactInstancesFromNode(
                            $item,
                            'vac:antwoord/vac:antwoordAdres/vac:instantie'
                        ),
                    //	'kort_antwoord' => $this->transformValue( // Not or no longer in SDU VAC XML.
                    //		'entity_decoded_special',
                    //		\Netcreators\NcExtbaseLib\Service\Xml\XPath\Helper::getNodeValue('vac:kortAntwoord', $item)
                    //	),
                    // APlus onderwater antwoord
                    'onderwater_antwoord' => $this->transformValue(
                            'entity_decoded_special',
                            XPathHelper::getNodeValue('vac:onderwaterantwoord', $item)
                        ),
                    // SDU onderwater antwoord
                    'onderwater_antwoordTekst' => $this->transformValue(
                            'entity_decoded_special',
                            XPathHelper::getNodeValue('vac:onderwaterantwoord/vac:onderwaterantwoordTekst', $item)
                        ),
                    'onderwater_antwoordProductVeld' => $this->transformValue(
                            'entity_decoded_special',
                            XPathHelper::getNodeValue('vac:onderwaterantwoord/vac:onderwaterantwoordProductVeld', $item)
                        ),
                    'onderwater_antwoordAdres' => $this->getContactInstancesFromNode(
                            $item,
                            'vac:onderwaterantwoord/vac:onderwaterantwoordAdres/vac:instantie'
                        ),
                    'referenceOtherInfo' => $this->getReferenceLinksFromNode(
                            $item,
                            'vac:verwijzingOverigeInfo',
                            ReferenceLink::TYPE_URL
                        ),
                    'referenceContact' => $this->getReferenceLinksFromNode(
                            $item,
                            'vac:verwijzingContact',
                            ReferenceLink::TYPE_URL
                        ),
                    'contactAddresses' => $this->getContactInstancesFromNode(
                            $item,
                            'vac:contactinfo/vac:instantie'
                        )
                );

                $itemList[] = $listItem;
            }
        }
        return $itemList;
    }


    /**
     * Gets a list of references for the given node
     * @param \SimpleXMLElement $node
     * @param string $path
     * @param string $type
     * @return array
     */
    protected function getReferenceLinksFromNode(\SimpleXMLElement $node, $path, $type)
    {
        $listItems = array();
        $references = $node->xpath($path);
        if (count($references) > 0) {
            foreach ($references as $reference) {
                $listItems[] = array(
                    'id' => (string)$reference['resourceIdentifier'],
                    'title' => (string)$reference,
                    'url' => (string)$reference['resourceIdentifier'],
                    'type' => $type,
                );
            }
        }
        return $listItems;
    }


    /**
     * Gets a list of contact instances for the given node
     * @param \SimpleXMLElement $node
     * @param string $path
     * @return array
     */
    protected function getContactInstancesFromNode(\SimpleXMLElement $node, $path)
    {
        $contactInstancePropertyMapping = array(
            'vac:land' => 'country',
            'vac:naam' => 'name',
            'vac:b_straat' => 'visitorAddress_street',
            'vac:b_nummer' => 'visitorAddress_houseNumber',
            'vac:b_pcode' => 'visitorAddress_postCode',
            'vac:b_stad' => 'visitorAddress_city',
            'vac:p_straat' => 'postAddress_street',
            'vac:p_nummer' => 'postAddress_houseNumber',
            'vac:p_postcode' => 'postAddress_postCode',
            'vac:p_po_box' => 'postAddress_POBox',
            'vac:p_woonplaats' => 'postAddress_city',
            'vac:telefoon' => 'phone',
            'vac:fax' => 'fax',
            'vac:email' => 'email',
            'vac:url' => 'www',
            'vac:opmerkingen' => 'description',
        );
        $listItems = array();
        $contactInstanceNodes = $node->xpath($path);
        if (count($contactInstanceNodes) > 0) {
            foreach ($contactInstanceNodes as $contactInstanceNode) {
                $listItem = array(
                    'id' => (string)$contactInstanceNode['id']
                );
                foreach ($contactInstancePropertyMapping as $path => $targetFieldName) {
                    $listItem[$targetFieldName] = $this->transformValue(
                        'string',
                        XPathHelper::getNodeValue($path, $contactInstanceNode)
                    );
                }
                $listItems[] = $listItem;
            }
        }
        return $listItems;
    }
}

