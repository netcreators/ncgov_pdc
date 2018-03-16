<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_statistics';

return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_statistics',
        'label' => 'type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'type' => 'type',
        'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
        'default_sortby' => 'ORDER BY tstamp DESC',
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_statistics.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, name, description'
    ),
    'columns' => array(
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
                'default' => '0',
                'size' => 1,
                'maxitems' => 1,
            )
        ),
        'product' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.product',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingleBox',
                'foreign_table' => $_TXEXTKEY . '_domain_model_product',
                'maxitems' => 1,
            )
        ),
        'frequently_asked_question' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.frequently_asked_question',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingleBox',
                'foreign_table' => $_TXEXTKEY . '_domain_model_frequentlyaskedquestion',
                'maxitems' => 1,
            )
        ),
        'loggedin_count' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.loggedin_count',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'int',
            )
        ),
        'count' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.count',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'int',
            )
        ),
        'logtime' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.logtime',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'integer',
            )
        ),
        'registration' => array(
            'config' => array(
                'type' => 'passthrough',
            )
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'type,product,loggedin_count,count,logtime'),
        '1' => array('showitem' => 'type,frequently_asked_question,loggedin_count,count,logtime'),
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);

