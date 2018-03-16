<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_referencelink';

$tableDefinition = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_referencelink',
        'label' => 'name',
        'label_alt' => 'link,resource_identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'type' => 'type',
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
        'default_sortby' => 'ORDER BY name, link, resource_identifier',
        'searchFields' => 'name,title,link,link_frequently_asked_question,link_product,link_page,'
            . 'resource_identifier,description,service_url',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_referencelink.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => ''
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check'
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
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.5',
                        '5'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.4',
                        '4'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.1',
                        '1'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.2',
                        '2'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.3',
                        '3'
                    ),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
        'subtype' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subtype',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subtype.I.0',
                        '0'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subtype.I.1',
                        '1'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subtype.I.2',
                        '2'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subtype.I.3',
                        '3'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subtype.I.4',
                        '4'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subtype.I.5',
                        '5'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subtype.I.6',
                        '6'
                    ),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
        'imported' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.imported',
            'config' => array(
                'type' => 'check',
            )
        ),
        'name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.name',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'max' => '1000',
                'eval' => 'trim',
            )
        ),
        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.title',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'max' => '1000',
                'eval' => 'trim',
            )
        ),
        // normal link
        'link' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.link',
            'config' => array(
                'type' => 'input',
                'size' => '80',
                'max' => '1024',
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
        // link to product
        'link_product' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.link_product',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => $_TXEXTKEY . '_domain_model_product',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                /*
                                'type'	 => 'input',
                                'size'	 => '15',
                                'max'	  => '255',
                                'checkbox' => '',
                                'eval'	 => 'trim',
                                'wizards'  => array(
                                    '_PADDING' => 2,
                                    'link'	 => array(
                                        'type'		 => 'popup',
                                        'title'		=> 'Link',
                                        'icon'		 => 'actions-insert-reference',
                                        'module' => array(
                                            'name' => 'wizard_element_browser',
                                                'urlParameters' => array(
                                                    'mode' => 'wizard'
                                                )
                                        ),
                                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1'
                                    )
                                )*/
            )
        ),
        // link to faq
        'link_frequently_asked_question' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.link_frequently_asked_question',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => $_TXEXTKEY . '_domain_model_frequentlyaskedquestion',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                /*
                                'type'	 => 'input',
                                'size'	 => '15',
                                'max'	  => '255',
                                'checkbox' => '',
                                'eval'	 => 'trim',
                                'wizards'  => array(
                                    '_PADDING' => 2,
                                    'link'	 => array(
                                        'type'		 => 'popup',
                                        'title'		=> 'Link',
                                        'icon'		 => 'actions-insert-reference',
                                        'module' => array(
                                            'name' => 'wizard_element_browser',
                                                'urlParameters' => array(
                                                    'mode' => 'wizard'
                                                )
                                        ),
                                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1'
                                    )
                                )
                */
            )
        ),
        // link to product
        'link_page' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.link_page',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ),
        ),
        // store if supplied
        'resource_identifier' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.resource_identifier',
            'config' => array(
                'type' => 'input',
                'size' => '15',
                'max' => '255',
                'eval' => 'trim',
            )
        ),
        'description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.description',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            ),
        ),
        'is_digid_service' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.is_digid_service',
            'config' => array(
                'type' => 'check',
            )
        ),
        'is_electronic_service' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.is_electronic_service',
            'config' => array(
                'type' => 'check',
            )
        ),
        // normal link
        'service_url' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.service_url',
            'config' => array(
                'type' => 'input',
                'size' => '80',
                'max' => '1024',
                'checkbox' => '',
                'eval' => 'trim',
            ),
        ),
    ),
    'types' => array(
        '1' => array('showitem' => '')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);

// no faq, no link, no link page
$tableDefinition['types']['1']['showitem'] = implode(
    ',',
    array_diff(
        array_keys($tableDefinition['columns']),
        array('link_frequently_asked_question', 'link', 'link_page', 'title')
    )
);

// no product, no link, no link page
$tableDefinition['types']['2']['showitem'] = implode(
    ',',
    array_diff(
        array_keys($tableDefinition['columns']),
        array('link_product', 'link', 'link_page', 'title')
    )
);

// no faq, no product, no link
$tableDefinition['types']['3']['showitem'] = implode(
    ',',
    array_diff(
        array_keys($tableDefinition['columns']),
        array('link_frequently_asked_question', 'link_product', 'link', 'title')
    )
);

// no faq, no product, no link page
$tableDefinition['types']['4']['showitem'] = implode(
    ',',
    array_diff(
        array_keys($tableDefinition['columns']),
        array('link_frequently_asked_question', 'link_product', 'link_page', 'title')
    )
);

// no faq, no product, no link page
$tableDefinition['types']['5']['showitem'] = implode(
    ',',
    array_diff(
        array_keys($tableDefinition['columns']),
        array('link_frequently_asked_question', 'link_product', 'link_page', 'title')
    )
);

return $tableDefinition;

