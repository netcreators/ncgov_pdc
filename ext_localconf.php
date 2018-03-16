<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Netcreators\\NcgovPdc\\Controller\\FrequentlyAskedQuestionSynchronizerCommandController';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
// The name of the extension in UpperCamelCase
    'Netcreators.' . $_EXTKEY, //'NcgovPdc',
    // A unique name of the plugin in UpperCamelCase
    'pi',
    // An array holding the controller-action-combinations that are accessible
    array(
        // The first controller and its first action will be the default
        // default view
        'Product' => 'azIndex,detail,topViewedProducts,newTip,createTip,editTip,updateTip,removeTip,publishXml,tabMenu,productNotAvailable,productMaintainerOverview,showRelatedAdvancedThemesForProduct,showByScmeta',
        'FrequentlyAskedQuestion' => 'find,topViewedFrequentlyAskedQuestions,myQuestionWasNotAnswered,detail',
        'Registration' => 'registrationStatus,startRegistration,closeRegistration,updateRegistrationDone',
    ),
    // An array of non-cachable controller-action-combinations (they must already be enabled)
    array(
        'Product' => 'detail,newTip,createTip,editTip,updateTip,removeTip,publishXml,showByScmeta',
        'FrequentlyAskedQuestion' => 'find',
        'Registration' => 'registrationStatus,startRegistration,closeRegistration,updateRegistrationDone',
    )
);

// hook into tce main to enable overriding of show_fields so rte's will generate links correctly
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]
    = 'Netcreators\\NcgovPdc\\Service\\Backend\\Form\\DataMapPreProcessor';

// hook into wizard_add to enable default settings when creating new records for select entities
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\Controller\\Wizard\\AddController']['className']
    = 'Netcreators\\NcgovPdc\\Service\\Backend\\Controller\\Wizard\\AddController';


$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][
    \Netcreators\NcgovPdc\Service\Backend\Form\FormDataProvider\DatabaseRowModifier::class
] = [
    'before' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowDefaultValues::class,
    ],
];