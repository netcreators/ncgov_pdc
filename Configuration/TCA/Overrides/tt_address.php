<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = 'tt_address';

// NOTE: The following code will only be usable as soon as EXT:tt_address makes use of TCA Caching, by using
// its own EXT:tt_address/Configuration/TCA/tt_address.php.
// For now, it still uses EXT:tt_address/ext_tables.php with dynamicConfigFile EXT:tt_address/tca.php.

// Therefore, unfortunately, we also still need to override the respective fields in our own ext_tables.php.

//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
//	'tt_address',
//	array (
//		$_TXEXTKEY . '_vac_contact_instance_uid' => array (
//			'exclude' => 1,
//			'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_vac_contact_instance_uid',
//			'config'  =>  array(
//				'type' =>  'passthrough'
//			)
//		),
//		$_TXEXTKEY . '_post_address' => array (
//			'label'  => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_post_address',
//			'config' => array (
//				'type' => 'text',
//				'cols' => '20',
//				'rows' => '3'
//			)
//		),
//		$_TXEXTKEY . '_post_city' => array (
//			'label'  => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_post_city',
//			'config' => array (
//				'type' => 'input',
//				'size' => '20',
//				'eval' => 'trim',
//				'max'  => '255'
//			)
//		),
//		$_TXEXTKEY . '_post_zip' => array (
//			'label'  => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_post_zip',
//			'config' => array (
//				'type' => 'input',
//				'eval' => 'trim',
//				'size' => '10',
//				'max'  => '20'
//			)
//		),
//		$_TXEXTKEY . '_post_p_o_box' => array (
//			'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.' . $_TXEXTKEY . '_post_p_o_box',
//			'config'  => array (
//				'type' => 'input',
//				'size' => '20',
//				'eval' => 'trim',
//				'max'  => '128'
//			)
//		)
//	)
//);
//
//$GLOBALS['TCA']['tt_address']['columns']['phone']['config']['max'] = 255;

