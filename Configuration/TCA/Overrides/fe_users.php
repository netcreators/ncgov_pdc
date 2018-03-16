<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = 'fe_users';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'fe_users',
    array(
        $_TXEXTKEY . '_availability_status' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_availability_status',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_availability_status.I.0',
                        ''
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_availability_status.I.1',
                        'IN'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_availability_status.I.2',
                        'OUT'
                    ),
                    array(
                        'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_availability_status.I.3',
                        'BUSY'
                    ),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    $_TXEXTKEY . '_availability_status;;;;1-1-1'
);

