<?php

namespace Netcreators\NcgovPdc\ViewHelpers;

class RealUrlAliasViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseHandle;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->databaseHandle = $GLOBALS['TYPO3_DB'];
    }

    /**
     * Gets the realurl alias, if there is any
     * @param integer $uid
     * @return string
     */
    public function render($uid)
    {

        $result = '';
        $uid = (int)$uid;

        if ($uid > 0) {
            $row = $this->databaseHandle->exec_SELECTgetSingleRow(
                'value_alias',
                'tx_realurl_uniqalias',
                'value_id = ' . $uid
            );
            $result = $row['value_alias'];
        }

        return $result;
    }
}

