<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_frequentlyaskedquestion';

// NOTE: this table is being filtered throug tcemain filter -> see \Netcreators\NcgovPdc\Service\Backend\Form\DatabaseRowModifier
$tableDefinition = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_frequentlyaskedquestion',
        'label' => 'owms_core_title',
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
        'default_sortby' => 'ORDER BY owms_core_title',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'searchFields' => 'session_number,owms_core_title,owms_core_identifier,owms_core_spatial,'
            . 'owms_mantle_authority,owms_mantle_contributor,owms_mantle_audience,owms_mantle_subjects_122',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_frequentlyaskedquestion.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, question, answer'
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check'
            )
        ),
        'imported' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.imported',
            'config' => array(
                'type' => 'check',
            )
        ),
        'weight' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.weight',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,number',
            )
        ),
        // OHA OWMS fields
        'owms_core_title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_title',
            'config' => array(
                'type' => 'input',
                'size' => 60,
                'eval' => 'trim',
                'max' => 255,
            )
        ),
        'owms_core_identifier' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_identifier',
            'config' => array(
                'type' => 'input',
                'size' => 105,
                'eval' => 'trim',
            )
        ),
        'owms_mantle_audience' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_mantle_audience',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_ncgovpdc_domain_model_product.audience.I.0',
                        'ondernemer'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_ncgovpdc_domain_model_product.audience.I.1',
                        'particulier'
                    ),
                ),
                'minitems' => 1,
                'size' => 2,
                'maxitems' => 2,
            ),
        ),
        'owms_mantle_subjects_122' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_mantle_subjects',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'items' => array(
                    array(' -- Error: unable to load resource file -- ', 0)
                ),
                'itemsProcFunc' => 'Netcreators\\NcgovPdc\\Service\\Backend\\Form\\FormFieldProvider->getTioThemeClassifications',
                'minitems' => 0,
                'maxitems' => 999,
                'size' => 10,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
            ),
        ),
        'frequently_asked_question_channels' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.frequently_asked_question_channels',
            'config' => array(
                // relation with channel table
                'type' => 'inline',
                'foreign_table' => 'tx_ncgovpdc_domain_model_frequentlyaskedquestionchannel',
                'foreign_field' => 'frequently_asked_question_uid',
                'appearance' => array(
                    'newRecordLinkPosition' => 'top',
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ),
            ),
        ),
        'reference_products' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_products',
            'config' => array(
                // relation with link table
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 20,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => 'tx_ncgovpdc_domain_model_referencelink',
                'MM' => $_TXEXTKEY . '_frequently_asked_question_link_product_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 1 AND NOT ' . $_TXEXTKEY . '_domain_model_referencelink.link_product = \'\' ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => $_TXEXTKEY . '_domain_model_referencelink',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'append',
                            'type' => '1',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'reference_frequently_asked_questions' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_frequently_asked_questions',
            'config' => array(
                // relation with link table
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => 'tx_ncgovpdc_domain_model_referencelink',
                'MM' => $_TXEXTKEY . '_frequently_asked_question_link_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 2 AND NOT ' . $_TXEXTKEY . '_domain_model_referencelink.link_frequently_asked_question = \'\' ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => $_TXEXTKEY . '_domain_model_referencelink',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'append',
                            'type' => '2',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'owms_core_language' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_language',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_ncgovpdc_domain_model_product.language.I.0',
                        'nl-NL'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_ncgovpdc_domain_model_product.language.I.1',
                        'fy-NL'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_ncgovpdc_domain_model_product.language.I.2',
                        'en-GB'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_ncgovpdc_domain_model_product.language.I.3',
                        'fr-FR'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_ncgovpdc_domain_model_product.language.I.4',
                        'de-DE'
                    ),
                ),
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            )
        ),
        'owms_core_creator_scheme' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_creator_scheme',
            'config' => array(
                // TODO: add value lists
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'owms_core_creator' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_creator',
            'config' => array(
                // TODO: add value lists
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'owms_core_modified' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_modified',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'date',
            ),
        ),
        'owms_core_spatial_scheme' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_spatial_scheme',
            'config' => array(
                // TODO: add value list
                // TODO: add mm table
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'owms_core_spatial' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_spatial',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'owms_core_temporal_start' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_temporal_start',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'date',
            )
        ),
        'owms_core_temporal_end' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_temporal_end',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'date',
            )
        ),
        'owms_mantle_authority' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_mantle_authority',
            'config' => array(
                // TODO: add value lists
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'owms_mantle_contributor' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_mantle_contributor',
            'config' => array(
                // TODO: add value lists
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'owms_mantle_available_start' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_mantle_available_start',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'date',
            )
        ),
        'owms_mantle_available_end' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_mantle_available_end',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'date',
            )
        ),
        'priority' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.priority',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.priority.I.0',
                        'laag'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.priority.I.1',
                        'medium'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.priority.I.2',
                        'hoog'
                    ),
                ),
                'minitems' => 1,
                'size' => 1,
                'maxitems' => 1,
            )
        ),
        'supplier_system' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.supplier_system',
            'config' => array(
                // TODO: implement value list
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'editorial_state' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.editorial_state',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.editorial_state.I.0',
                        'actief'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.editorial_state.I.1',
                        'non-actief'
                    ),
                ),
                'minitems' => 1,
                'size' => 1,
                'maxitems' => 1,
            )
        ),
        'verification_date' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.verification_date',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'date',
            ),
        ),
        'revisions' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.revisions',
            'config' => array(
                // relation with channel table
                'type' => 'inline',
                'foreign_table' => 'tx_ncgovpdc_domain_model_revision',
                'foreign_field' => 'frequently_asked_question_uid',
                'appearance' => array(
                    'newRecordLinkPosition' => 'top',
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ),
            )
        ),
        'destinations' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.destinations',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'foreign_table' => $_TXEXTKEY . '_domain_model_destination',
                'MM' => $_TXEXTKEY . '_frequently_asked_question_destination_mm'
            ),
        ),
        'authorities' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.authorities',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'foreign_table' => 'fe_groups',
                'MM' => $_TXEXTKEY . '_frequently_asked_question_authority_mm'
            ),
        ),
        'session_number' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.session_number',
            'config' => array(
                'type' => 'passthrough',
            )
        ),
    ),
    'types' => array(
        '1' => array('showitem' => '')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);

// Insert a tab for OWMS data
$showItems = str_replace(
    'owms_core_language',
    '--div--;' . 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_owms,owms_core_language',
    implode(',', array_keys($tableDefinition['columns']))
);

$tableDefinition['types']['1']['showitem'] = $showItems;

return $tableDefinition;

