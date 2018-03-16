<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_synonym';

// (frans) FIXME: remove synonym, since it's just a very bad implementation [(klaus) So - is this fair game?]
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_synonym',
        'label' => 'synonym',
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
        'delete' => 'deleted',
        'default_sortby' => 'ORDER BY synonym',
        'searchFields' => 'synonym',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_synonym.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, synonym, relatesto'
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check'
            )
        ),
        'synonym' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.synonym',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
                'max' => 256,
            )
        ),
        'relates_to' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.relates_to',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'loadingStrategy' => 'proxy',
                'foreign_class' => 'Tx_NcgovPdc_Domain_Model_Synonym',
                'foreign_table' => $_TXEXTKEY . '_domain_model_synonym',
                'foreign_table_where' => 'ORDER BY ' . $_TXEXTKEY . '_domain_model_synonym.synonym',
                'MM' => $_TXEXTKEY . '_synonym_relatesto_mm',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.synonym_relatesto_edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.synonym_relatesto_new',
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
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, synonym, relates_to')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);

