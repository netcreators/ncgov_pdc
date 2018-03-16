<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_product';

$_MAX_ALLOWED_IMAGE_FILESIZE = 51200;
$_ALLOWED_IMAGE_FILETYPES = 'jpg,gif,png,jpeg';
$_MAX_ALLOWED_ATTACHMENT_FILESIZE = 51200;
$_ALLOWED_ATTACHMENT_FILETYPES = 'doc,pdf,ppt,xls,zip';

// NOTE: this table is being filtered through Backend FormEngine filter -> see \Netcreators\NcgovPdc\Service\Backend\Form\DatabaseRowModifier
$TCA[$_TABLENAME] = array(
    'ctrl' => $TCA[$_TABLENAME]['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden, name, description'
    ),
    'columns' => array(
        // required for extbase
        'pid' => array(
            'label' => 'pid',
            'exclude' => 1,
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'copy_uid' => array(
            'label' => 'copy_uid',
            'exclude' => 1,
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'tstamp' => array(
            'label' => 'tstamp',
            'exclude' => 1,
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'crdate' => array(
            'label' => 'crdate',
            'exclude' => 1,
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check'
            )
        ),
        'imported' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.imported',
            'config' => array(
                'type' => 'check'
            )
        ),
        'session_number' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.session_number',
            'displayCond' => 'FIELD:imported:REQ:true',
            'config' => array(
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            )
        ),
        'show_fields' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.0',
                        '0'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.1',
                        '1'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.2',
                        '2'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.3',
                        '3'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.4',
                        '4'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.5',
                        '5'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.6',
                        '6'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.7',
                        '7'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.8',
                        '8'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.9',
                        '9'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.10',
                        '10'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.11',
                        '11'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.12',
                        '12'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.13',
                        '13'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.14',
                        '14'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.15',
                        '15'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.16',
                        '16'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.17',
                        '17'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.18',
                        '18'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.19',
                        '19'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.20',
                        '20'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.21',
                        '21'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.22',
                        '22'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.23',
                        '23'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.24',
                        '24'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.25',
                        '25'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.26',
                        '26'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.27',
                        '27'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.28',
                        '28'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.29',
                        '29'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.30',
                        '30'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_fields.I.31',
                        '31'
                    ),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
        'name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.name',
            'config' => array(
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required',
                'max' => 256
            )
        ),
        'type' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.0',
                        '0'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.1',
                        '1'
                    ),
                ),
                'size' => 1,
                'maxitems' => 1,
            )
        ),
        'language' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.language',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.language.I.0',
                        'nl-NL'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.language.I.1',
                        'fy-NL'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.language.I.2',
                        'en-GB'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.language.I.3',
                        'fr-FR'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.language.I.4',
                        'de-DE'
                    ),
                ),
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ),
        ),
        'custom_label' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.custom_label',
            'config' => array(
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'max' => 256
            )
        ),
        'show_dynamic_content' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.show_dynamic_content',
            'config' => array(
                'type' => 'check'
            )
        ),
        'owms_core_identifier' => array(
//			'exclude' => 0,
//			'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_identifier',
//			'config'  => array(
//				'type' => 'input',
//				'size' => 40,
//				'eval' => 'trim',
//			)
            // For SDU installations, a VIND URL is provided. However, in any case during creation of the SC 4.0 XML feed
            // a URL pointing to the product's detail page of the local Gemeente site is generated.
            'exclude' => 1,
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        // Imported and exported for Gemeentes using synchronization with a third-party PDC-management application.
        // For Gemeentes managing their products manually in the TYPO3 back end, the `uid` field is used when creating
        // the SC 4.0 XML publishing feed.
        'scmeta_product_id' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_product_id',
            'displayCond' => 'FIELD:imported:REQ:true',
            'config' => array(
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
                'readOnly' => true,
            )
        ),
        // Imported and exported for Gemeentes using synchronization with a third-party PDC-management application.
        // For Gemeentes managing their products manually in the TYPO3 back end, the `tstamp` field is used when creating
        // the SC 4.0 XML publishing feed.
        'owms_core_modified' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_core_modified',
            'displayCond' => 'FIELD:imported:REQ:true',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime',
            ),
        ),
        'owms_mantle_abstract' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.owms_mantle_abstract',
            'displayCond' => 'FIELD:imported:REQ:true',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 60,
            )
        ),
        'audience' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.audience',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.audience.I.0',
                        'ondernemer'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.audience.I.1',
                        'particulier'
                    ),
                ),
                'minitems' => 1,
                'size' => 2,
                'maxitems' => 2,
            ),
        ),
        'scmeta_request_online' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online.I.0',
                        0
                    ), // nee
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online.I.1',
                        1
                    ), // ja
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online.I.2',
                        2
                    ), // digid
                ),
                'size' => 1,
            ),
        ),
        'scmeta_request_online_url' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online_url',
            'config' => array(
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
            )
        ),
        'scmeta_request_online_single_sign_on' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online_single_sign_on',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online_single_sign_on.I.0',
                        0
                    ), // nee
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online_single_sign_on.I.1',
                        1
                    ), // ja
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_request_online_single_sign_on.I.2',
                        2
                    ), // undefined
                ),
                'size' => 1,
            ),
        ),
        'scmeta_uniform_product_names' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_uniform_product_names',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'items' => array(
                    array(' -- Error: unable to load resource file -- ', 0)
                ),
                'itemsProcFunc' => 'Netcreators\\NcgovPdc\\Service\\Backend\\Form\\FormFieldProvider->getUniformProductNameItems',
                'minitems' => 0,
                'maxitems' => 999,
                'size' => 10,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
            ),
        ),
        'scmeta_related_uniform_product_names' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_related_uniform_product_names',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'items' => array(
                    array(' -- Error: unable to load resource file -- ', 0)
                ),
                'itemsProcFunc' => 'Netcreators\\NcgovPdc\\Service\\Backend\\Form\\FormFieldProvider->getUniformProductNameItems',
                'minitems' => 0,
                'maxitems' => 999,
                'size' => 10,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
            ),
        ),
        'tio_themes' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tio_themes',
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
        'weight' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.weight',
            'config' => array(
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim,number',
            )
        ),
        'changes' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.changes',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'desk_memo' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.desk_memo',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'costs' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.costs',
            'config' => array(
                'type' => 'input',
                'size' => '15',
                'max' => '255',
                'checkbox' => '',
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'actions-insert-reference',
                        'module' => array(
                            'name' => 'wizard_element_browser',
                            'urlParameters' => array(
                                'mode' => 'wizard'
                            )
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1,resizable=1'
                    ),
                ),
            ),
        ),
        'costs_content' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.costs_content',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tt_content',
                'disallowed' => '',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'show_thumbs' => 1
            ),
        ),
        'turnaround' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.turnaround',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 256
            )
        ),
        'use_pre_description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_description',
            'config' => array(
                'type' => 'check',
                'default' => false,
            )
        ),
        'pre_description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_description',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_description',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.description',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_description',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_description',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_pre_apply_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_apply_info',
            'config' => array(
                'type' => 'check',
            )
        ),
        'pre_apply_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_apply_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_apply_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_apply_info',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'apply_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.apply_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_apply_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_apply_info',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_apply_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_apply_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_pre_extra_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_extra_info',
            'config' => array(
                'type' => 'check',
            )
        ),
        'pre_extra_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_extra_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_extra_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_extra_info',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'extra_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.extra_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_extra_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_extra_info',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_extra_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_extra_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_pre_contact_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_contact_info',
            'config' => array(
                'type' => 'check',
            )
        ),
        'pre_contact_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_contact_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_contact_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_contact_info',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'contact_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_contact_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_contact_info',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_contact_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_contact_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_pre_required_for_application' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_required_for_application',
            'config' => array(
                'type' => 'check',
            )
        ),
        'pre_required_for_application' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_required_for_application',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_required_for_application' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_required_for_application',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'required_for_application' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.required_for_application',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_required_for_application' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_required_for_application',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_required_for_application' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_required_for_application',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_pre_legal_basis' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_legal_basis',
            'config' => array(
                'type' => 'check',
            )
        ),
        'pre_legal_basis' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_legal_basis',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_legal_basis' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_legal_basis',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'legal_basis' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.legal_basis',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_legal_basis' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_legal_basis',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_legal_basis' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_legal_basis',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_pre_terms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_terms',
            'config' => array(
                'type' => 'check',
            )
        ),
        'pre_terms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_terms',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_terms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_terms',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'terms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.terms',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_terms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_terms',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_terms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_terms',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_pre_result' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_result',
            'config' => array(
                'type' => 'check',
            )
        ),
        'pre_result' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_result',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_result' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_result',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'result' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.result',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_result' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_result',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_result' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_result',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_pre_appeal' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_pre_appeal',
            'config' => array(
                'type' => 'check',
            )
        ),
        'pre_appeal' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.pre_appeal',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_appeal' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_appeal',
            'config' => array(
                'type' => 'check',
                'default' => true,
            )
        ),
        'appeal' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.appeal',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'use_post_appeal' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.use_post_appeal',
            'config' => array(
                'type' => 'check',
            )
        ),
        'post_appeal' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.post_appeal',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'contact_addresses' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_addresses',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => 'tt_address',
                'foreign_table_where' => 'ORDER BY tt_address.name',
                'MM' => $_TXEXTKEY . '_product_address_mm',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_addresses_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_addresses_new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => 'tt_address',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'append'
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'scmeta_contact_point' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.scmeta_contact_point',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'synonyms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.synonyms',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => $_TXEXTKEY . '_domain_model_synonym',
                'foreign_table_where' => 'ORDER BY ' . $_TXEXTKEY . '_domain_model_synonym.synonym',
                'MM' => $_TXEXTKEY . '_product_synonym_mm',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.synonyms_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.synonyms_new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => $_TXEXTKEY . '_domain_model_synonym',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'append'
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'keywords' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.keywords',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => $_TXEXTKEY . '_domain_model_keyword',
                'foreign_table_where' => 'ORDER BY ' . $_TXEXTKEY . '_domain_model_keyword.keyword',
                'MM' => $_TXEXTKEY . '_product_keyword_mm',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.keywords_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.keywords_new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => $_TXEXTKEY . '_domain_model_keyword',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'append'
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'frequently_asked_questions' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.frequently_asked_questions',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => $_TXEXTKEY . '_domain_model_frequentlyaskedquestion',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_frequentlyaskedquestion.pid = ###CURRENT_PID### ORDER BY ' . $_TXEXTKEY . '_domain_model_frequentlyaskedquestion.owms_core_title',
                'MM' => $_TXEXTKEY . '_product_frequently_asked_question_mm',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.frequently_asked_questions_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.frequently_asked_questions_new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => $_TXEXTKEY . '_domain_model_frequentlyaskedquestion',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'append'
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'frequently_asked_question_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.frequently_asked_question_info',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'image' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.image',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'file',
                'allowed' => $_ALLOWED_IMAGE_FILETYPES,
                'max_size' => $_MAX_ALLOWED_IMAGE_FILESIZE,
                'uploadfolder' => 'uploads/' . $_TXEXTKEY . '/image',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 1,
            )
        ),
        'attachments' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.attachments',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'file',
                'allowed' => '',
                'disallowed' => 'php',
                'max_size' => $_MAX_ALLOWED_ATTACHMENT_FILESIZE,
                'uploadfolder' => 'uploads/' . $_TXEXTKEY . '/attachment',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 999,
            )
        ),
        'related_products' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.related_products',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => $_TXEXTKEY . '_domain_model_product',
                'foreign_table_where' => 'AND ###THIS_UID### != tx_ncgovpdc_domain_model_product.uid AND ' . $_TXEXTKEY . '_domain_model_product.pid = ###CURRENT_PID### ORDER BY ' . $_TXEXTKEY . '_domain_model_product.name',
                'MM' => $_TXEXTKEY . '_product_relatedproducts_mm'
            ),
        ),
        'tips' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tips',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_ncgovpdc_domain_model_tip',
                'foreign_field' => 'product',
                'foreign_table_where' => 'ORDER BY ' . $_TXEXTKEY . '_domain_model_tip.tstamp',
                'appearance' => array(
                    'newRecordLinkPosition' => 'top',
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ),
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
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => 'fe_groups',
                'MM' => $_TXEXTKEY . '_product_authority_mm'
            ),
        ),
        'responsibles' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.responsibles',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => 'fe_users',
                'MM' => $_TXEXTKEY . '_product_responsible_mm'
            )
        ),
        'maintained_by' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.maintained_by',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => 'be_groups',
                'MM' => $_TXEXTKEY . '_product_maintainedby_mm'
            ),
        ),
        'request_form' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.request_form',
            'config' => array(
                'type' => 'input',
                'size' => '15',
                'max' => '255',
                'checkbox' => '',
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'actions-insert-reference',
                        'module' => array(
                            'name' => 'wizard_element_browser',
                            'urlParameters' => array(
                                'mode' => 'wizard'
                            )
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1'
                    ),
                ),
            ),
        ),
        'directive' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.directive',
            'config' => array(
                'type' => 'input',
                'size' => '15',
                'max' => '255',
                'checkbox' => '',
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'actions-insert-reference',
                        'module' => array(
                            'name' => 'wizard_element_browser',
                            'urlParameters' => array(
                                'mode' => 'wizard'
                            )
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1'
                    ),
                ),
            ),
        ),
        'process_description' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.process_description',
            'config' => array(
                'type' => 'input',
                'size' => '15',
                'max' => '255',
                'checkbox' => '',
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'actions-insert-reference',
                        'module' => array(
                            'name' => 'wizard_element_browser',
                            'urlParameters' => array(
                                'mode' => 'wizard'
                            )
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1'
                    )
                )
            )
        ),
        'related_regulatory' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.related_regulatory',
            'config' => array(
                'type' => 'input',
                'size' => '15',
                'max' => '255',
                'checkbox' => '',
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'actions-insert-reference',
                        'module' => array(
                            'name' => 'wizard_element_browser',
                            'urlParameters' => array(
                                'mode' => 'wizard'
                            )
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1'
                    )
                )
            )
        ),
        'source' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.source',
            'config' => array(
                'type' => 'input',
                'size' => '15',
                'max' => '255',
                'checkbox' => '',
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'actions-insert-reference',
                        'module' => array(
                            'name' => 'wizard_element_browser',
                            'urlParameters' => array(
                                'mode' => 'wizard'
                            )
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1'
                    )
                )
            )
        ),
        'reference_laws' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_laws',
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
                'MM' => $_TXEXTKEY . '_product_reference_laws_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 5 AND ' . $_TXEXTKEY . '_domain_model_referencelink.subtype = 1 ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
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
                            'type' => '5',
                            'subtype' => '1',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'reference_local_laws' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_local_laws',
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
                'MM' => $_TXEXTKEY . '_product_reference_local_laws_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 5 AND ' . $_TXEXTKEY . '_domain_model_referencelink.subtype = 2 ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
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
                            'type' => '5',
                            'subtype' => '2',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'reference_forms' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_forms',
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
                'MM' => $_TXEXTKEY . '_product_reference_forms_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 5 AND ' . $_TXEXTKEY . '_domain_model_referencelink.subtype = 3 ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
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
                            'type' => '5',
                            'subtype' => '3',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'reference_internal' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_internal',
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
                'MM' => $_TXEXTKEY . '_product_reference_internal_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 5 AND ' . $_TXEXTKEY . '_domain_model_referencelink.subtype = 4 ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
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
                            'type' => '5',
                            'subtype' => '4',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'reference_external' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_external',
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
                'MM' => $_TXEXTKEY . '_product_reference_external_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 5 AND ' . $_TXEXTKEY . '_domain_model_referencelink.subtype = 5 ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
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
                            'type' => '5',
                            'subtype' => '5',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'link_groups' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.link_groups',
            'config' => array(
                // relation with link table
                'type' => 'inline',
                'foreign_table' => 'tx_ncgovpdc_domain_model_linkgroup',
                'foreign_field' => 'product',
//				'foreign_table_where' => 'ORDER BY ' . $_TXEXTKEY . '_domain_model_linkgroup.tstamp',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_linkgroup.pid = ###CURRENT_PID###',
                'appearance' => array(
                    'newRecordLinkPosition' => 'top',
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ),
            ),
        ),
        'short_description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.short_description',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            )
        ),
        'users_available' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.users_available',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => 'fe_users',
                'foreign_table_where' => 'ORDER BY fe_users.name',
                'MM' => $_TXEXTKEY . '_product_users_available_mm',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_addresses_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_addresses_new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => 'fe_users',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'append'
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, name, description')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);

// find current PID
$id = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('edit');
if (isset($id[$_TABLENAME]) && is_array($id)) {
    $id = array_keys($id[$_TABLENAME]);
    $id = (int)$id[0];
} else {
    $id = 0;
}
$changedProductLabels = false;
$changedTabLabels = false;
if ($id > 0) {
    $product = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($_TABLENAME, $id);
    // get current pageTsConfig
    $pageTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($product['pid']);

    if (is_array($pageTsConfig) && $pageTsConfig['tx_ncgovpdc.']['useUsersAvailable'] == true) {
        $alternativeTable = $pageTsConfig['tx_ncgovpdc.']['usersAvailable.']['alternativeTable'];
        $alternativeWhere = $pageTsConfig['tx_ncgovpdc.']['usersAvailable.']['alternativeWhere'];
        $TCA[$_TABLENAME]['columns']['users_available']['config']['foreign_table'] = $alternativeTable;
        $TCA[$_TABLENAME]['columns']['users_available']['config']['foreign_table_where'] = $alternativeWhere;
        $TCA[$_TABLENAME]['columns']['users_available']['config']['wizards']['add']['params']['table'] = $alternativeTable;

    } elseif (is_array($pageTsConfig) && $pageTsConfig['tx_ncgovpdc.']['useUsersAvailable'] != true) {
        unset($TCA[$_TABLENAME]['columns']['users_available']);
    }

    $referenceTypes = array(
        'reference_laws' => 1,
        'reference_local_laws' => 2,
        'reference_forms' => 3,
        'reference_internal' => 4,
        'reference_external' => 5,
    );

    if (is_array(
            $pageTsConfig
        ) && $pageTsConfig['tx_ncgovpdc.']['setTypeRestrictionForReferences'] == true
    ) { // WTF? This is already added to foreign_table_where by default... (But subtypes not applied in any synchronizer, and no manual for where best to use EXT:ncgov_pdc/Resources/Private/Scripts/SetSubTypes.php:user_setReferenceLinkSubtypes->setSubtypesFromFieldFromWhichLinkedFromPdc)

        foreach ($referenceTypes as $columnName => $referenceType) {
            $TCA[$_TABLENAME]['columns'][$columnName]['config']['foreign_table_where'] = 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.subtype = ' . $referenceType . ' '
                . $TCA[$_TABLENAME]['columns'][$columnName]['config']['foreign_table_where'];
        }
    }

    // check tab mapping
    if (is_array($pageTsConfig) && count($pageTsConfig['tx_ncgovpdc.']['changeTabLabels.']) > 0) {
        $changedTabLabels = $pageTsConfig['tx_ncgovpdc.']['changeTabLabels.'];
    }

    // fix the limit for foreign tables
    // with the tx_ncgovpdc.columns.<columnname>.foreign_table_where_pid = (either empty, pid, pidlist or 'current')
    // with the tx_ncgovpdc.columns.<columnname>.wizards_add_pid = (either empty, pid or current) you can direct where new records of this kind will be stored
    if (is_array($pageTsConfig) && isset($pageTsConfig['tx_ncgovpdc.']['columns.'])) {

        if (count($pageTsConfig['tx_ncgovpdc.']['columns.']) > 0) {

            foreach ($pageTsConfig['tx_ncgovpdc.']['columns.'] as $column => $pidSetting) {

                if (isset($pidSetting['foreign_table_where_pid'])) {
                    $column = substr($column, 0, -1);
                    $pidLimit = $pidSetting['foreign_table_where_pid'];
                    $foreignTable = $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table'];
                    $foreignTableWhere = $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table_where'];
                    $originalPidLimit = sprintf('AND %s.pid = ###CURRENT_PID###', $foreignTable);

                    if ($foreignTable) {

                        if (empty($pidLimit)) {
                            $newPidLimit = '';
                        } else {

                            if (strpos($pidLimit, ',') === false) {

                                if ($pidLimit === 'current') {
                                    $newPidLimit = sprintf(
                                        'AND %s.pid = %s',
                                        $foreignTable,
                                        '###CURRENT_PID###'
                                    );
                                } else {
                                    $newPidLimit = sprintf(
                                        'AND %s.pid = \'%s\'',
                                        $foreignTable,
                                        $pidLimit
                                    );
                                }
                            } else {
                                $intList = \TYPO3\CMS\Extbase\Utility\ArrayUtility::integerExplode(',', $pidLimit);
                                $newPidLimit = sprintf(
                                    'AND %s.pid IN (%s)',
                                    $foreignTable,
                                    $pidLimit
                                );
                            }
                        }

                        if ($foreignTableWhere == '') {
                            $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table_where'] = $newPidLimit;

                        } elseif (strpos($foreignTableWhere, 'AND') === false) {
                            $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table_where'] = $newPidLimit . ' ' . $foreignTableWhere;

                        } else {
                            $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table_where'] = str_replace(
                                $originalPidLimit,
                                $newPidLimit,
                                $foreignTableWhere
                            );
                        }
                    }
                }

                if (isset($pidSetting['wizards_add_pid'])) {

                    if ($pidSetting['wizards_add_pid'] == 'current') {
                        $wizardAddPid = '###CURRENT_PID';
                    } else {
                        $wizardAddPid = $pidSetting['wizards_add_pid'];
                    }

                    $TCA[$_TABLENAME]['columns'][$column]['config']['wizards']['add']['params']['pid'] = $wizardAddPid;
                }
            }
        }
    }
}

$globalExtensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);

$count = 0;
$items = array();
$tabLabels = array(
    'tab_description' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_description',
    'tab_apply_info' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_apply_info',
    'tab_extra_info' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_extra_info',
    'tab_contact_info' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_contact_info',
    'tab_required_for_application' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_required_for_application',
    'tab_legal_basis' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_legal_basis',
    'tab_terms' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_terms',
    'tab_result' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_result',
    'tab_appeal' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_appeal',
    'tab_desk_memo' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_desk_memo',
    'synonyms' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_misc,synonyms',
    'request_form' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.tab_links,request_form',
);
if (is_array($changedTabLabels) && count($changedTabLabels)) {
    \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($tabLabels, $changedTabLabels);
}

// Define loadable tabs as '<tab name>,<column 1>,<column 2>,...'
$groupedRteColumns = array(
    // no rte's
    '',
    // description set
    'tab_description,short_description,description',
    'tab_description,pre_description,description',
    'tab_description,description,post_description',
    'tab_description,short_description,pre_description,description,post_description',
    // extra info set
    'tab_extra_info,pre_extra_info,extra_info',
    'tab_extra_info,extra_info,post_extra_info',
    'tab_extra_info,pre_extra_info,extra_info,post_extra_info',
    // apply info set
    'tab_apply_info,pre_apply_info,apply_info',
    'tab_apply_info,apply_info,post_apply_info',
    'tab_apply_info,pre_apply_info,apply_info,post_apply_info',
    // required for application set
    'tab_required_for_application,pre_required_for_application,required_for_application',
    'tab_required_for_application,required_for_application,post_required_for_application',
    'tab_required_for_application,pre_required_for_application,required_for_application,post_required_for_application',
    // contact info set
    'tab_contact_info,pre_contact_info,contact_info',
    'tab_contact_info,contact_info,post_contact_info',
    'tab_contact_info,pre_contact_info,contact_info,post_contact_info',
    // legal basis set
    'tab_legal_basis,pre_legal_basis,legal_basis',
    'tab_legal_basis,legal_basis,post_legal_basis',
    'tab_legal_basis,pre_legal_basis,legal_basis,post_legal_basis',
    // terms
    'tab_terms,pre_terms,terms',
    'tab_terms,terms,post_terms',
    'tab_terms,pre_terms,terms,post_terms',
    // result
    'tab_result,pre_result,result',
    'tab_result,result,post_result',
    'tab_result,pre_result,result,post_result',
    // appeal (Bezwaar en Beroep)
    'tab_appeal,pre_appeal,appeal',
    'tab_appeal,appeal,post_appeal',
    'tab_appeal,pre_appeal,appeal,post_appeal',
    // onderwaterantwoord
    'tab_desk_memo,desk_memo',
    // all, text
    'tab_description,short_description,pre_description,description,post_description'
    . ',tab_extra_info,pre_extra_info,extra_info,post_extra_info'
    . ',tab_apply_info,pre_apply_info,apply_info,post_apply_info'
    . ',tab_required_for_application,pre_required_for_application,required_for_application,post_required_for_application'
    . ',tab_contact_info,pre_contact_info,contact_info,post_contact_info'
    . ',tab_legal_basis,pre_legal_basis,legal_basis,post_legal_basis'
    . ',tab_terms,pre_terms,terms,post_terms'
    . ',tab_result,pre_result,result,post_result'
    . ',tab_appeal,pre_appeal,appeal,post_appeal'
    . ',tab_desk_memo,desk_memo',
    // all, rte
    'tab_description,short_description,pre_description,description,post_description'
    . ',tab_extra_info,pre_extra_info,extra_info,post_extra_info'
    . ',tab_apply_info,pre_apply_info,apply_info,post_apply_info'
    . ',tab_required_for_application,pre_required_for_application,required_for_application,post_required_for_application'
    . ',tab_contact_info,pre_contact_info,contact_info,post_contact_info'
    . ',tab_legal_basis,pre_legal_basis,legal_basis,post_legal_basis'
    . ',tab_terms,pre_terms,terms,post_terms'
    . ',tab_result,pre_result,result,post_result'
    . ',tab_appeal,pre_appeal,appeal,post_appeal'
    . ',tab_desk_memo,desk_memo',
);

$genericTextFieldsAreProtected = !(isset($globalExtensionConfiguration['genericTextFieldsAreEditable'])
    && (boolean)$globalExtensionConfiguration['genericTextFieldsAreEditable'] === true);

if ($genericTextFieldsAreProtected) {
    // disable generic RTE fields
    $disabledRteFields = array(
        'description',
        'apply_info',
        'extra_info',
        'contact_info',
        'required_for_application',
        'legal_basis',
        'terms',
        'result',
        'appeal'
    );

    foreach ($disabledRteFields as $field) {
        $TCA[$_TABLENAME]['columns'][$field]['config'] = array(
            'type' => 'user',
            'userFunc' => 'Netcreators\\NcgovPdc\\Service\\Backend\\Form\\FormFieldProvider->getDisabledRteField',
        );
    }
}

$rteColumns = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', end($groupedRteColumns));
$columnsForRtes = $rteColumns;
foreach ($rteColumns as $column) {
    $columnsForRtes[] = 'use_' . $column;
}
$nonRteColumns = array_diff(array_keys($TCA[$_TABLENAME]['columns']), $columnsForRtes);
$nonRteColumns = implode(',', $nonRteColumns);
$rteDefinition = 'richtext:rte_transform[mode=ts_css]';

// Index in array $groupedRteColumns containing `all, text`.
// In the tab group defined by this array, all listed fields are shown in their respective tabs as text-only (non-RTE) fields.
$textIndex = 30;

if (isset($_GET['edit'][$_TABLENAME]) && is_array($groupedRteColumns)) {
    foreach ($groupedRteColumns as $index => $rteGroup) {
        $items = array();
        if ($index > 0) {
            $rteGroup = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $rteGroup);
            if (is_array($rteGroup)) {
                foreach ($rteGroup as $column) {
                    // add use_ before each column, except short_description & desk_memo
                    if ($column != 'short_description' && $column != 'desk_memo' && substr($column, 0, 4) != 'tab_') {
                        $items[] = 'use_' . $column;
                        // don't rte generic fields
                        // don't rte the fields for the 'text only' setting
                        if ((!$genericTextFieldsAreProtected || substr($column, 0, 4) == 'pre_' || substr(
                                    $column,
                                    0,
                                    5
                                ) == 'post_') && $index != $textIndex
                        ) {
                            $TCA[$_TABLENAME]['types'][$index]['columnsOverrides'][$column]['defaultExtras'] .= $rteDefinition;
                        }
                        $items[] = $column;
                    } else {
                        if (($column == 'short_description' || $column == 'desk_memo') && $index != $textIndex) {
                            $TCA[$_TABLENAME]['types'][$index]['columnsOverrides'][$column]['defaultExtras'] .= $rteDefinition;
                        }
                        $items[] = $column;
                    }
                }
            }
            $items = $nonRteColumns . ',' . implode(',', $items);
        } else {
            $items = $nonRteColumns;
        }

        if (is_array($tabLabels)) {
            foreach ($tabLabels as $replaceMeWithTabLabel => $tabLabel) {
                $tabConfig = '--div--;' . $tabLabel . ',';
                $items = str_replace($replaceMeWithTabLabel, $tabConfig, $items);
            }
        }
        // meh
        $items = str_replace(',,', ',', $items);
        $TCA[$_TABLENAME]['types'][$index]['showitem'] = $items;
    }
}

