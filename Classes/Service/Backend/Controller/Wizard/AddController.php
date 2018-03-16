<?php

namespace Netcreators\NcgovPdc\Service\Backend\Controller\Wizard;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

class AddController extends \TYPO3\CMS\Backend\Controller\Wizard\AddController
{

    /**
     * Main function
     * Will issue a location-header, redirecting either BACK or to a new alt_doc.php instance...
     *
     * @return    void
     */
    function main()
    {
        if ($this->returnEditConf) {
            parent::main();
        } else {

            $urlParameters = [
                'returnEditConf' => 1,
                'edit[' . $this->P['params']['table'] . '][' . $this->pid . ']' => 'new',
                'returnUrl' => GeneralUtility::removeXSS(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ];

            // pass subtype along
            if ($this->P['params']['table'] === 'tx_ncgovpdc_domain_model_referencelink') {
                if(isset($this->P['params']['type'])) {

                    $urlParameters['type'] = $this->P['params']['type'];

                    if (isset($this->P['params']['subtype'])) {

                        $urlParameters['subtype'] = $this->P['params']['subtype'];
                        $urlParameters['type'] = $this->P['params']['type'];
                    }
                }
            }

            $redirectUrl = BackendUtility::getModuleUrl('record_edit', $urlParameters);

            HttpUtility::redirect($redirectUrl);
        }
    }
}