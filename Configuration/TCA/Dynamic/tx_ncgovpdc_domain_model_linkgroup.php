<?php

$_EXTKEY = 'ncgov_pdc';
$_TXEXTKEY = 'tx_ncgovpdc';
$_TABLENAME = $_TXEXTKEY . '_domain_model_linkgroup';

$TCA[$_TABLENAME] = array(
    'ctrl' => $TCA[$_TABLENAME]['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden, keyword'
    ),
    'columns' => array(
        // required for extbase
        'pid' => array(
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
        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.title',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required',
                'max' => 256,
            )
        ),
        'reference_links' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.reference_links',
            'config' => array(
                // relation with link table
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 9999,
                'autoSizeMax' => 30,
                'selectedListStyle' => 'width: 400px;',
                'itemListStyle' => 'width: 400px;',
                'foreign_table' => 'tx_ncgovpdc_domain_model_referencelink',
                'MM' => $_TXEXTKEY . '_linkgroup_reference_links_mm',
                'foreign_table_where' => 'AND ' . $_TXEXTKEY . '_domain_model_referencelink.pid = ###CURRENT_PID### AND ' . $_TXEXTKEY . '_domain_model_referencelink.type = 5 AND ' . $_TXEXTKEY . '_domain_model_referencelink.subtype = 6 ORDER BY ' . $_TXEXTKEY . '_domain_model_referencelink.name',
                'wizards' => array(
                    '_PADDING' => 2,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.edit',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'icon' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:' . $_TABLENAME . '.new',
                        'icon' => 'actions-add',
                        'params' => array(
                            'table' => $_TXEXTKEY . '_domain_model_referencelink',
                            'pid' => '###CURRENT_PID###',
                            'type' => '5',
                            'subtype' => '6',
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1',
                    ),
                ),
            ),
        ),
        'product' => array(
            'config' => array(
                'type' => 'passthrough',
            )
        ),
        'sorting' => array(
            'config' => array(
                'type' => 'passthrough',
            )
        ),
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden, title, reference_links')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);

// find current id
$id = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('edit');
$ajax = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('ajax');
$pid = false;

if ($ajax !== null && count($ajax) > 0 && strpos($ajax[1], 'linkgroup') !== false) {
    // handle ajax call
    $pid = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('-', $ajax[1]);

    if ($pid[0] == 'data') {
        $pid = (int)$pid[1];
    } else {
        $pid = false;
    }

} else {
    if (isset($id[$_TABLENAME]) && is_array($id)) {
        // handle normal call, as if table being edited directly
        $id = array_keys($id[$_TABLENAME]);
        $id = (int)$id[0];
    } else {
        $id = 0;
    }
}

if ($id > 0 || $pid !== false) {

    if ($pid === false) {
        $linkGroup = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($_TABLENAME, $id);
        $pid = $linkGroup['pid'];
    }

    // get current pageTsConfig
    $pageTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($pid);

    // fix the limit for foreign tables
    // with the tx_ncgovpdc.columns.<columnname>.foreign_table_where_pid = (either empty, pid, pidlist or 'current')
    // with the tx_ncgovpdc.columns.<columnname>.wizards_add_pid = (either empty, pid or current) you can direct where new records of this kind will be stored
    if (is_array($pageTsConfig) && isset($pageTsConfig['tx_ncgovpdc.']['link_group.']['columns.'])) {

        if (count($pageTsConfig['tx_ncgovpdc.']['link_group.']['columns.']) > 0) {

            foreach ($pageTsConfig['tx_ncgovpdc.']['link_group.']['columns.'] as $column => $pidSetting) {

                if (isset($pidSetting['foreign_table_where_pid'])) {
                    $column = substr($column, 0, -1);
                    $pidLimit = $pidSetting['foreign_table_where_pid'];
                    $foreignTable = $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table'];
                    $foreignTableWhere = $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table_where'];
                    $originalPidLimit = sprintf('AND %s.pid = ###CURRENT_PID###', $foreignTable);

                    if ($foreignTable) {

                        if (empty($pidLimit)) {
                            $newPidLimit = '';
                        } else {

                            if (strpos($pidLimit, ',') === false) {

                                if ($pidLimit === 'current') {
                                    $newPidLimit = sprintf(
                                        'AND %s.pid = %s',
                                        $foreignTable,
                                        '###CURRENT_PID###'
                                    );
                                } else {
                                    $newPidLimit = sprintf(
                                        'AND %s.pid = \'%s\'',
                                        $foreignTable,
                                        $pidLimit
                                    );
                                }
                            } else {
                                $intList = \TYPO3\CMS\Extbase\Utility\ArrayUtility::integerExplode(',', $pidLimit);
                                $newPidLimit = sprintf(
                                    'AND %s.pid IN (%s)',
                                    $foreignTable,
                                    $pidLimit
                                );
                            }
                        }

                        if ($foreignTableWhere == '') {
                            $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table_where'] = $newPidLimit;

                        } elseif (strpos($foreignTableWhere, 'AND') === false) {
                            $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table_where'] = $newPidLimit . ' ' . $foreignTableWhere;

                        } else {
                            $TCA[$_TABLENAME]['columns'][$column]['config']['foreign_table_where'] = str_replace(
                                $originalPidLimit,
                                $newPidLimit,
                                $foreignTableWhere
                            );
                        }
                    }
                }

                if (isset($pidSetting['wizards_add_pid'])) {

                    if ($pidSetting['wizards_add_pid'] == 'current') {
                        $wizardAddPid = '###CURRENT_PID';
                    } else {
                        $wizardAddPid = $pidSetting['wizards_add_pid'];
                    }

                    $TCA[$_TABLENAME]['columns'][$column]['config']['wizards']['add']['params']['pid'] = $wizardAddPid;
                }
            }
        }
    }
}

