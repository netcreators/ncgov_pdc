<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_registration';

$tableDefinition = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_registration',
        'label' => 'subject',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
        'copyAfterDuplFields' => 'sys_language_uid',
        'useColumnsForDefaultValues' => 'sys_language_uid',
        'default_sortby' => 'ORDER BY tstamp DESC',
        'delete' => 'deleted',
        'searchFields' => 'subject,result,remark,actions',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_registration.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, name, description'
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check'
            )
        ),
        'subject' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.subject',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
                'max' => 256,
            )
        ),
        'start_time' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.start_time',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime',
            )
        ),
        'end_time' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.end_time',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime',
            )
        ),
        'closed' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.closed',
            'config' => array(
                'type' => 'check',
            )
        ),
        'result' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.result',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => $_TXEXTKEY . '_domain_model_registrationresult',
                'maxitems' => 1,
            ),
        ),
        'remark' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.remark',
            'config' => array(
                'type' => 'text',
                'eval' => '',
                'rows' => 30,
                'cols' => 80,
            )
        ),
        'actions' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.actions',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => $_TXEXTKEY . '_domain_model_registrationaction',
                'foreign_field' => 'registration',
                'foreign_table_where' => 'ORDER BY ' . $_TXEXTKEY . '_domain_model_registrationaction.tstamp',
                'appearance' => array(
                    'newRecordLinkPosition' => 'top',
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ),
            ),
        ),
        'next_registration' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.next_registration',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => $_TXEXTKEY . '_domain_model_registration',
                'maxitems' => 1,
            ),
        ),
        'user' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.user',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'maxitems' => 1,
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

$tableDefinition['types']['1']['showitem'] = implode(',', array_keys($tableDefinition['columns']));

return $tableDefinition;

