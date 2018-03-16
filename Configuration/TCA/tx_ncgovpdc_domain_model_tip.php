<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_tip';

$tableDefinition = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_tip',
        'label' => 'name',
        'label_userFunc' => 'Netcreators\\NcgovPdc\\Service\\Backend\\Form\\FormFieldProvider->getTipLabel',
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
        'searchFields' => 'name,description',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_tip.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, name, description'
    ),
    'columns' => array(
        'tstamp' => array(
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
        'name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.name',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
                'max' => 256,
            )
        ),
        'description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.description',
            'config' => array(
                'type' => 'text',
                'eval' => 'required',
                'rows' => 30,
                'cols' => 80,
            )
        ),
        'datetime' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.datetime',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'date',
                'default' => time(),
            ),
        ),
        'state' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.state',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.state.I.0',
                        '0'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.state.I.1',
                        '1'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.state.I.2',
                        '2'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.state.I.3',
                        '3'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.state.I.4',
                        '4'
                    ),
                ),
                'size' => 1,
                'maxitems' => 1,
            )
        ),
        'product' => array(
            'config' => array(
                'type' => 'passthrough',
            )
        ),
        'creator' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.creator',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_class' => 'TYPO3\\CMS\\Extbase\\Domain\\Model\\FrontendUser',
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

