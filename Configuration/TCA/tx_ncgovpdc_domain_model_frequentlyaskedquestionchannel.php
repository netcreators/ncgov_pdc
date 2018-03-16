<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_frequentlyaskedquestionchannel';

$tableDefinition = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_frequentlyaskedquestionchannel',
        'label' => 'question',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
        'copyAfterDuplFields' => 'sys_language_uid',
        'useColumnsForDefaultValues' => 'sys_language_uid',
        'default_sortby' => 'ORDER BY question',
        'searchFields' => 'channels,question,short_answer,answer,answer_product_field,'
            . 'authorized_answer,authorized_answer_product_field,contact_addresses',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_frequentlyaskedquestionchannel.gif'
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
        'question' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.question',
            'config' => array(
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required',
                'max' => 255,
            )
        ),
        'short_answer' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.short_answer',
            // TODO: Check for maxsize
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'content-special-shortcut',
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte'
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
            'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts]',
        ),
        'answer' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.answer',
            // TODO: Check for maxsize
            // optional, max 2500 chars
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'content-special-shortcut',
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte'
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
            'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts]',
        ),
        'answer_product_field' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.answer_product_field',
            'config' => array(
                // TODO: Check for maxsize
                // optional, max 2500 chars
                'type' => 'text',
                'rows' => 30,
                'cols' => 80,
                'readOnly' => 1
            )
        ),
        'answer_addresses' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.answer_addresses',
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
                'MM' => $_TXEXTKEY . '_frequently_asked_question_channel_address_mm',
                'MM_match_fields' => array(
                    'fieldname' => 'answer_addresses',
                ),
                'MM_insert_fields' => array(
                    'fieldname' => 'answer_addresses',
                ),
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_instance_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_instance_new',
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
        'authorized_answer' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.authorized_answer',
            // TODO: Check for maxsize
            // optional, max 1000 chars
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'content-special-shortcut',
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte'
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
            'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts]',
        ),
        'authorized_answer_product_field' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.authorized_answer_product_field',
            'config' => array(
                // TODO: Check for maxsize
                // optional, max 1000 chars
                'type' => 'text',
                'rows' => 30,
                'cols' => 80,
                'readOnly' => 1
            ),
        ),
        'authorized_answer_addresses' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.authorized_answer_addresses',
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
                'MM' => $_TXEXTKEY . '_frequently_asked_question_channel_address_mm',
                'MM_match_fields' => array(
                    'fieldname' => 'authorized_answer_addresses',
                ),
                'MM_insert_fields' => array(
                    'fieldname' => 'authorized_answer_addresses',
                ),
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_instance_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_instance_new',
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
        'reference_other_info' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_other_info',
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
                'MM' => $_TXEXTKEY . '_frequently_asked_question_channel_link_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 4 ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
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
                            'type' => '4',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'reference_contacts' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_contacts',
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
                'MM' => $_TXEXTKEY . '_frequently_asked_question_channel_link_contact_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 4 ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
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
                            'type' => '4',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'channels' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels.I.0',
                        'generiek'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels.I.1',
                        'balie'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels.I.2',
                        'chat'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels.I.3',
                        'e-mail'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels.I.4',
                        'sms'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels.I.5',
                        'telefoon'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels.I.6',
                        'post'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.channels.I.7',
                        'website'
                    ),
                ),
                'minitems' => 1,
                'maxitems' => 9999,
                'size' => 10,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
            ),
        ),
        'frequently_asked_question_uid' => array(
            'config' => array(
                'type' => 'passthrough',
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
                'MM' => $_TXEXTKEY . '_frequently_asked_question_channel_address_mm',
                'MM_match_fields' => array(
                    'fieldname' => 'contact_addresses',
                ),
                'MM_insert_fields' => array(
                    'fieldname' => 'contact_addresses',
                ),
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_instance_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.contact_instance_new',
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
    ),
    'types' => array(
        '1' => array('showitem' => '')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);

$tableDefinition['types']['1']['showitem'] = implode(',', array_keys($tableDefinition['columns']));

return $tableDefinition;

