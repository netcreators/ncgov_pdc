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

namespace Netcreators\NcgovPdc\Service\Backend\Form;

use Netcreators\NcgovPdc\Utility\TioThemeClassificationReader;
use Netcreators\NcgovPdc\Utility\UniformProductNameListReader;
use TYPO3\CMS\Backend\Form\Element\UserElement;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Backend FormFieldProvider
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 */
class FormFieldProvider
{

    /**
     * Returns html for a content element which does not allow input.
     * @param array $PA Parameter Array
     * @param UserElement $backendFormDataProviderUserField
     * @return string
     */
    public function getDisabledRteField(array $PA, UserElement $backendFormDataProviderUserField)
    {
        return '
			<div style="width: 550px; height: 300px; overflow: scroll;">
				' . $PA['itemFormElValue'] . '
			</div>
		';
    }

    /**
     * Returns all themes of 'Thema-indeling Overheid' (TiO)
     * @param array &$params the params
     * @param TcaSelectItems &$requestingObject The requesting "parent" object (since TYPO3 7.5 this is the same class for FlexForms (e.g. PDCX), and for Product record editing.)
     * @return void
     */
    function getTioThemeClassifications(array &$params, TcaSelectItems &$requestingObject)
    {
        require_once(GeneralUtility::getFileAbsFileName(
            'EXT:ncgov_pdc/Classes/Utility/TioThemeClassificationReader.php'
        ));
        require_once(GeneralUtility::getFileAbsFileName('EXT:ncgov_pdc/Classes/Xml/TioThemeClassification/Parser.php'));
        require_once(GeneralUtility::getFileAbsFileName('EXT:ncgov_pdc/Classes/Exception.php'));
        require_once(GeneralUtility::getFileAbsFileName(
            'EXT:ncgov_pdc/Classes/Xml/TioThemeClassification/Exception.php'
        ));
        require_once(GeneralUtility::getFileAbsFileName(
            'EXT:ncgov_pdc/Classes/Xml/TioThemeClassification/Exception/XmlParseFailureException.php'
        ));

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** @var TioThemeClassificationReader $tioThemeReader */
        $tioThemeReader = $objectManager->get(TioThemeClassificationReader::class);
        $items = $tioThemeReader->getTioThemes();
        if (is_array($items)) {
            $params['items'] = array();
            foreach ($items as $key => $item) {
                $params['items'][] = array($item['indentedName'], $key);
            }
        }
    }

    /**
     * Returns all uniform product names of 'Uniforme Productnamenlijst' (UPL)
     * @param array &$params the params
     * @param TcaSelectItems &$requestingObject The requesting "parent" object (since TYPO3 7.5 this is the same class for FlexForms (e.g. PDCX), and for Product record editing.)
     * @return void
     */
    function getUniformProductNameItems(array &$params, TcaSelectItems &$requestingObject)
    {
        require_once(GeneralUtility::getFileAbsFileName(
            'EXT:ncgov_pdc/Classes/Utility/UniformProductNameListReader.php'
        ));
        require_once(GeneralUtility::getFileAbsFileName('EXT:ncgov_pdc/Classes/Xml/UniformProductNameList/Parser.php'));
        require_once(GeneralUtility::getFileAbsFileName('EXT:ncgov_pdc/Classes/Exception.php'));
        require_once(GeneralUtility::getFileAbsFileName(
            'EXT:ncgov_pdc/Classes/Xml/UniformProductNameList/Exception.php'
        ));
        require_once(GeneralUtility::getFileAbsFileName(
            'EXT:ncgov_pdc/Classes/Xml/UniformProductNameList/Exception/XmlParseFailureException.php'
        ));

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** @var UniformProductNameListReader $UPLReader */
        $UPLReader = $objectManager->get(UniformProductNameListReader::class);
        $items = $UPLReader->getUniformProductNames();
        if (is_array($items)) {
            $params['items'] = array();
            foreach ($items as $key => $item) {
                $params['items'][] = array($item['name'], $key);
            }
        }
    }

    /**
     * Get tip title
     * @param array $parameters
     * @param object $parentObject
     */
    public function getTipLabel(array &$parameters, $parentObject)
    {

        if ((isset($_GET['M']) && $_GET['M'] == 'web_list') || (!isset($_GET['M']) && isset($_GET['edit']) && !isset($_GET['edit']['tx_ncgovpdc_domain_model_product']))) {
            $parameters['title'] = $parameters['row']['name'];
        } else {
            $statusToIconTranslation = array(
                0 => 'warning',
                1 => 'error',
                2 => 'warning',
                3 => 'information',
                4 => 'ok'
            );
            $statusToMessageTranslation = array(
                0 => 'Geen status',
                1 => 'Aangemeld',
                2 => 'In behandeling',
                3 => 'Ter beoordeling voorgelegd',
                4 => 'Goedgekeurd'
            );
            $icon = $statusToIconTranslation[$parameters['row']['state']];
            $statusMessage = $statusToMessageTranslation[$parameters['row']['state']];
            $creationDate = date('d-m-Y', $parameters['row']['datetime']);
            $parameters['title'] = sprintf(
                '<div class="typo3-message message-%s">[%s] %s (sinds=%s)</div>',
                $icon,
                $statusMessage,
                $parameters['row']['name'],
                $creationDate
            );
        }
    }
}

