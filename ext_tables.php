<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$_TXEXTKEY = 'tx_ncgovpdc';


// Register Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
// The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
    'Netcreators.' . $_EXTKEY,
    // A unique name of the plugin in UpperCamelCase
    'pi',
    // A title shown in the backend dropdown field
    'PDC: Product- en diensten catalogus'
);


// Add Plugin Flexform
$TCA['tt_content']['types']['list']['subtypes_excludelist']['ncgovpdc_pi'] = 'layout,select_key,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist']['ncgovpdc_pi'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'ncgovpdc_pi',
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi.xml'
);


// Add Plugin TypoScript Setup
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'Product en diensten catalogus'
);

if (TYPO3_MODE === 'BE') {

    if (\Netcreators\NcExtbaseLib\Utility\ExtConfUtility::getBooleanValue(
        $_EXTKEY,
        'enableProductMaintenanceModule',
        false
    )
    ) {

        /**
         * Registers the Product Maintenance Backend Module (if enabled via EXTCONF)
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'Netcreators.' . $_EXTKEY,
            'web', // Make module a submodule of 'file'
            'NcgovPdcProductMaintenance', // Submodule key
            '', // Position
            array(
                'Product' => 'productMaintainerAdminOverview,productMaintainerOverview'
            ),
            array(
                'access' => 'user,group',
                'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
                'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod_ProductMaintenance.xml',
            )
        );

    }

    if (\Netcreators\NcExtbaseLib\Utility\ExtConfUtility::getBooleanValue(
        $_EXTKEY,
        'enableFrequentlyAskedQuestionSynchronizerModule',
        true
    )
    ) {

        /**
         * Registers the Antwoord+ (FAQ) Synchronizer Backend Module (if enabled via EXTCONF)
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'Netcreators.' . $_EXTKEY,
            'tools', // Make module a submodule of 'admin'
            'NcgovPdcFrequentlyAskedQuestionSynchronizer', // Submodule key
            '', // Position
            array(
                'FrequentlyAskedQuestionSynchronizerModule' => 'status,initiateRunByUser,resetSynchronization'
            ),
            array(
                'access' => 'user,group',
                'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
                'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod_FrequentlyAskedQuestionSynchronizer.xml',
            )
        );
    }

}


// Define Control for Dynamic tables.
// All other tables are loaded via the preferred new [https://forge.typo3.org/issues/59048] mechanism, allowing TCA caching.
// @see http://docs.typo3.org/typo3cms/CoreApiReference/ExtensionArchitecture/FilesAndLocations/Index.html
// @see http://docs.typo3.org/typo3cms/TCAReference/ExtendingTca/StoringChanges/Index.html#storing-changes-extension-overrides

// NOTE: This table is being filtered throug tcemain filter -> see \Netcreators\NcgovPdc\Service\Backend\Form\DatabaseRowModifier
//       This means that we cannot make use of TCA caching for this table. (Or we would have to implement a different approach to the TCE form field display selection.)
$TCA['tx_ncgovpdc_domain_model_product'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_product',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'type' => 'show_fields',
        'versioningWS' => 2,
        'versioning_followPages' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
        'copyAfterDuplFields' => 'sys_language_uid',
        'useColumnsForDefaultValues' => 'sys_language_uid',
        'delete' => 'deleted',
        'default_sortby' => 'ORDER BY name',
        'dividers2tabs' => true,
        'searchFields' => 'session_number,name,owms_core_identifier,owms_mantle_abstract,'
            . 'scmeta_request_online_url,scmeta_contact_point,audience,scmeta_uniform_product_names,'
            . 'scmeta_related_uniform_product_names,tio_themes,costs,short_description,pre_description,description,'
            . 'post_description,pre_apply_info,apply_info,post_apply_info,pre_extra_info,extra_info,post_extra_info,'
            . 'pre_contact_info,contact_info,post_contact_info,pre_required_for_application,required_for_application,'
            . 'post_required_for_application,pre_legal_basis,legal_basis,post_legal_basis,pre_terms,terms,post_terms,'
            . 'pre_result,result,post_result,pre_appeal,appeal,post_appeal,attachments,directive,process_description,'
            . 'advanced_themes,life_phases,related_regulatory',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
                $_EXTKEY
            ) . 'Configuration/TCA/Dynamic/tx_ncgovpdc_domain_model_product.php',
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_product.gif'
    )
);

// This table is dynamically modified for BE editing. No TCA caching.
$TCA['tx_ncgovpdc_domain_model_linkgroup'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_linkgroup',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => 2,
        'versioning_followPages' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
        'copyAfterDuplFields' => 'sys_language_uid',
        'useColumnsForDefaultValues' => 'sys_language_uid',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'searchFields' => 'title',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
                $_EXTKEY
            ) . 'Configuration/TCA/Dynamic/tx_ncgovpdc_domain_model_linkgroup.php',
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_linkgroup.gif'
    ),
);


// NOTE: The following code can be removed in favour of Configuration/TCA/Overrides/tt_address.php as soon as
// EXT:tt_address makes use of TCA Caching, by using its own EXT:tt_address/Configuration/TCA/tt_address.php.
// For now, it still uses EXT:tt_address/ext_tables.php with dynamicConfigFile EXT:tt_address/tca.php.

// Therefore, unfortunately, we also still need to override the respective fields in our own ext_tables.php.

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_address',
    array(
        $_TXEXTKEY . '_vac_contact_instance_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_vac_contact_instance_uid',
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        $_TXEXTKEY . '_post_address' => array(
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_post_address',
            'config' => array(
                'type' => 'text',
                'cols' => '20',
                'rows' => '3'
            )
        ),
        $_TXEXTKEY . '_post_city' => array(
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_post_city',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        $_TXEXTKEY . '_post_zip' => array(
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_post_zip',
            'config' => array(
                'type' => 'input',
                'eval' => 'trim',
                'size' => '10',
                'max' => '20'
            )
        ),
        $_TXEXTKEY . '_post_p_o_box' => array(
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_post_p_o_box',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '128'
            )
        )
    )
);

$GLOBALS['TCA']['tt_address']['columns']['phone']['config']['max'] = 255;

if (isset($TCA['tt_address_group'])) {
    $TCA['tt_address_group']['ctrl']['sortby'] = 'tt_address_group.sorting';
}

