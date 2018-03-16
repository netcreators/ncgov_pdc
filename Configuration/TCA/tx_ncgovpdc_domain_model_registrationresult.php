<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_registrationresult';

$tableDefinition = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_registrationresult',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
        'copyAfterDuplFields' => 'sys_language_uid',
        'useColumnsForDefaultValues' => 'sys_language_uid',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'searchFields' => 'name',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_registrationresult.gif'
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
        'name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.name',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
                'max' => 255,
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

