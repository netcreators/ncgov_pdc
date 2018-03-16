<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_advancedtheme';

$tableDefinition = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_advancedtheme',
        'label' => 'title',
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
        'default_sortby' => 'ORDER BY title',
        'searchFields' => 'identifier,title,session_number,keywords,without_context',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_advancedtheme.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, title'
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
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.imported',
            'config' => array(
                'type' => 'check'
            )
        ),
        'session_number' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.session_number',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'modified' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.modified',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime',
            ),
        ),
        'identifier' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.identifier',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
            )
        ),
        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.title',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
            )
        ),
        'keywords' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.keywords',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'without_context' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.without_context',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'level' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.level',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'number',
                'max' => 256,
            )
        ),
        'type' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.type.I.1',
                        1
                    ),
                ),
                'default' => 1,
            )
        ),
        'parent' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.parent',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('', 0),
                ),
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => $_TABLENAME,
                'foreign_table_where' => 'AND ###CURRENT_PID### = ' . $_TABLENAME . '.pid AND ###THIS_UID### != ' . $_TABLENAME . '.uid ORDER BY ' . $_TABLENAME . '.title',
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
                'foreign_table' => 'tx_ncgovpdc_domain_model_product',
                'foreign_table_where' => 'AND ###CURRENT_PID### = tx_ncgovpdc_domain_model_product.pid ORDER BY tx_ncgovpdc_domain_model_product.name',
                'MM' => $_TXEXTKEY . '_advancedtheme_product_mm'
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

// range to allow for later extension
foreach (range(0, 20) as $type) {
    $tableDefinition['types'][$type]['showitem'] = implode(',', array_keys($tableDefinition['columns']));
}

return $tableDefinition;

