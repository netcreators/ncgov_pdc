<?php

########################################################################
# Extension Manager/Repository config file for ext "ncgov_pdc".
#
# Auto generated 24-04-2012 09:27
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Producten en Diensten Catalogus',
    'description' => 'De Netcreators Producten en Diensten Catalogus voor TYPO3|gem',
    'category' => 'plugin',
    'shy' => 0,
    'version' => '4.2.10',
    'dependencies' => 'extbase,fluid,nc_extbase_lib',
    'conflicts' => '',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => 'uploads/tx_ncgovpdc/image,uploads/tx_ncgovpdc/attachment',
    'modify_tables' => '',
    'clearcacheonload' => 1,
    'lockType' => '',
    'author' => 'Leonie Philine Bitto [netcreators], Frans van der Veen',
    'author_email' => 'leonie@netcreators.nl',
    'author_company' => 'Netcreators',
    'CGLcompliance' => '',
    'CGLcompliance_note' => '',
    'constraints' => array(
        'depends' => array(
            'php' => '5.5.0-0.0.0',
            'typo3' => '7.6.16-7.6.99',
            'extbase' => '',
            'fluid' => '',
            'nc_extbase_lib' => '1.2.3-1.2.99',
            'tt_address' => '',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
    '_md5_values_when_last_written' => '',
    'suggests' => array(),
);

