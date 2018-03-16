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

namespace Netcreators\NcgovPdc\Service\Backend\Form\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * tce forms helper
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 *
 * @copyright Netcreators
 * @package NcgovPdc
 */
class DatabaseRowModifier implements FormDataProviderInterface
{
    protected $extensionConfiguration = '';

    /**
     * Initialize this object, so configuration values can be read.
     * @return void
     */
    public function initialize()
    {
        $this->extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ncgov_pdc']);
    }

    /**
     * Pre-process databaseRecord
     *
     * @param array $result
     *
     * @return array
     */
    function addData(array $result)
    {
        switch ($result['tableName']) {
            case 'tx_ncgovpdc_domain_model_frequentlyaskedquestion':
                $this->initialize();
                return $this->preProcessDomainModelFrequentlyAskedQuestion($result);
            case 'tx_ncgovpdc_domain_model_product':
                $this->initialize();
                return $this->preProcessDomainModelProduct($result);
            case 'tx_ncgovpdc_domain_model_referencelink':
                return $this->preProcessReferenceLink($result);
            default:
                return $result;
        }
    }

    /**
     * @param array $result
     * @return array
     */
    protected function preProcessReferenceLink(array $result)
    {
        if ($_GET['type']) {

            $result['databaseRow']['type'] = $_GET['type'];

            if($_GET['subtype']) {
                $result['databaseRow']['subtype'] = $_GET['subtype'];
            }
        }

        return $result;
    }

    /**
     * Preprocess faq table
     * @param array $result
     * @return array
     */
    protected function preProcessDomainModelFrequentlyAskedQuestion(array $result)
    {
        $code = trim($this->extensionConfiguration['identifierUniqueCode']);

        if (empty($code)) {
            $code = '-----';
        }

        if (empty($result['databaseRow']['owms_core_identifier'])) {
            $result['databaseRow']['owms_core_identifier'] = $code . time();
        }
        if (empty($result['databaseRow']['owms_core_modified'])) {
            $result['databaseRow']['owms_core_modified'] = time();
        }
        if (empty($result['databaseRow']['owms_core_creator_scheme'])) {
            $result['databaseRow']['owms_core_creator_scheme'] = trim(
                $this->extensionConfiguration['defaultCreatorScheme']
            );
        }
        if (empty($result['databaseRow']['owms_core_creator'])) {
            $result['databaseRow']['owms_core_creator'] = trim($this->extensionConfiguration['defaultCreator']);
        }
        if (empty($result['databaseRow']['owms_core_spatial_scheme'])) {
            $result['databaseRow']['owms_core_spatial_scheme'] = trim(
                $this->extensionConfiguration['defaultSpatialScheme']
            );
        }
        if (empty($result['databaseRow']['owms_core_spatial'])) {
            $result['databaseRow']['owms_core_spatial'] = trim($this->extensionConfiguration['defaultSpatial']);
        }
        if (empty($result['databaseRow']['owms_mantle_authority'])) {
            $result['databaseRow']['owms_mantle_authority'] = trim($this->extensionConfiguration['defaultAuthority']);
        }
        if (empty($result['databaseRow']['owms_mantle_contributor'])) {
            $result['databaseRow']['owms_mantle_contributor'] = trim(
                $this->extensionConfiguration['defaultContributor']
            );
        }
        if (empty($result['databaseRow']['supplier_system'])) {
            $result['databaseRow']['supplier_system'] = trim($this->extensionConfiguration['defaultSupplierSystem']);
        }

        return $result;
    }

    /**
     * Preprocess product table
     * @param array $result
     * @return array
     */
    protected function preProcessDomainModelProduct(array $result)
    {

        $code = trim($this->extensionConfiguration['identifierUniqueCode']);

        if (empty($code)) {
            $code = '-----';
        }

        if (empty($result['databaseRow']['owms_core_identifier'])) {
            $result['databaseRow']['owms_core_identifier'] = $code . time();
        }

        if (isset($this->extensionConfiguration['dontShowAnyRteOnRecordLoad']) && (int)$this->extensionConfiguration['dontShowAnyRteOnRecordLoad']) {
            if (!isset($_POST['data'][$result['tableName']])) {
                $result['databaseRow']['show_fields'] = 0;
            }
        }

        if (isset($this->extensionConfiguration['showAllFields']) && (int)$this->extensionConfiguration['showAllFields']) {
            $result['databaseRow']['show_fields'] = 31;
        }

        $pageTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($result['databaseRow']['pid']);

        if (isset($pageTsConfig['tx_ncgovpdc.']) && isset($pageTsConfig['tx_ncgovpdc.']['alternativeShowItems'])) {
            $GLOBALS['TCA']['tx_ncgovpdc_domain_model_product']['types'][$result['databaseRow']['show_fields']]['showitem'] = $pageTsConfig['tx_ncgovpdc.']['alternativeShowItems'];
        }

        if (isset($pageTsConfig['tx_ncgovpdc.']) && isset($pageTsConfig['tx_ncgovpdc.']['dynamicLabelsFromProductData.'])) {
            $dynamicLabelsFromProductData = $pageTsConfig['tx_ncgovpdc.']['dynamicLabelsFromProductData.'];

            if (count($dynamicLabelsFromProductData) > 0) {

                $defaultText = '';
                if (isset($dynamicLabelsFromProductData['defaultLabel']) && $dynamicLabelsFromProductData['defaultLabel'] != '') {
                    $defaultText = $dynamicLabelsFromProductData['defaultLabel'];
                }

                foreach ($dynamicLabelsFromProductData as $key => $searchReplaceConfiguration) {

                    if ($key == 'defaultLabel') {
                        continue;
                    }

                    if (isset($result['databaseRow'][$searchReplaceConfiguration['replaceWithContentsOfColumn']]) && trim(
                            $result['databaseRow'][$searchReplaceConfiguration['replaceWithContentsOfColumn']]
                        ) != ''
                    ) {
                        $replace = strip_tags(
                            $result['databaseRow'][$searchReplaceConfiguration['replaceWithContentsOfColumn']]
                        );

                        if (strlen($replace) > 26) {
                            $replace = substr($replace, 0, 26) . '...';
                        }

                    } else {
                        $replace = $defaultText;
                    }

                    $subject = $GLOBALS['TCA']['tx_ncgovpdc_domain_model_product']['types'][$result['databaseRow']['show_fields']]['showitem'];
                    $search = $searchReplaceConfiguration['search'];
                    $subject = str_replace($search, $replace, $subject);
                    $GLOBALS['TCA']['tx_ncgovpdc_domain_model_product']['types'][$result['databaseRow']['show_fields']]['showitem'] = $subject;
                }
            }
        }

        return $result;
    }
}

