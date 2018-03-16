<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_registrationaction';

return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_registrationaction',
        'label' => 'type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'type' => 'type',
        'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
        'default_sortby' => 'ORDER BY tstamp DESC',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_registrationaction.gif'
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
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.2',
                        '2'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.3',
                        '3'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.4',
                        '4'
                    ),
                    //array('LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.5', '5'),
                ),
                'default' => '2',
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
                'foreign_class' => '\Netcreators\NcgovPdc\Domain\Model\Product',
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
        'search_parameter' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.search_parameter',
            'config' => array(
                'type' => 'text',
                'eval' => 'required',
                'rows' => 30,
                'cols' => 80,
            )
        ),
        'registration' => array(
            'config' => array(
                'type' => 'passthrough',
            )
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'type,product'),
        '1' => array('showitem' => 'type,frequently_asked_question'),
        '2' => array('showitem' => 'type,search_parameter'),
        '3' => array('showitem' => 'type,search_parameter,frequently_asked_question'),
        '4' => array('showitem' => 'type,product,frequently_asked_question'),
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);

