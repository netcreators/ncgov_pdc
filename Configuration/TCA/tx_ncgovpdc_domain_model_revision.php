<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_revision';

$tableDefinition = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:' . $_EXTKEY
            . '/Resources/Private/Language/locallang_db.xml:' . $_TXEXTKEY . '_domain_model_revision',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
        'copyAfterDuplFields' => 'sys_language_uid',
        'useColumnsForDefaultValues' => 'sys_language_uid',
        'default_sortby' => 'crdate',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:ncgov_pdc/Resources/Public/Icons/icon_tx_ncgovpdc_domain_model_revision.gif'
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
        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.title',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            )
        ),
        'version' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.version',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 8,
            )
        ),
        'date_time' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.date_time',
            'config' => array(
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'date',
            ),
        ),
        'author' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.author',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 40,
            ),
        ),
        'revision_types' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.revision_types',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.revision_types.I.0',
                        'tekst'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.revision_types.I.1',
                        'inhoud'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.revision_types.I.2',
                        'opmaak'
                    ),
                ),
                'size' => 10,
                'minitems' => 1,
                'maxitems' => 999,
            ),
        ),
        'comment' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.comment',
            'config' => array(
                // max 200 chars
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            ),
        ),
        'frequently_asked_question_uid' => array(
            'config' => array(
                'type' => 'passthrough',
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

