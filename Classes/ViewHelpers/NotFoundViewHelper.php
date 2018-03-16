<?php

namespace Netcreators\NcgovPdc\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

class NotFoundViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Add 404 header to product detail page when no product found
     * If configuration for pageNotFound_handling  available then
     * redirect to a page i.e "http://thedomain.com/404" or just "/404/" or "404.html/"
     * or show pageNotFound_handling content then exit
     * @param int $redirect
     * @return void
     */
    public function render($redirect = 0)
    {
        global $TYPO3_CONF_VARS;
        $url = $TYPO3_CONF_VARS['FE']['pageNotFound_handling'];
        $urlParts = parse_url($url);

        if ($urlParts['host'] == '') {
            $urlParts['host'] = GeneralUtility::getIndpEnv('HTTP_HOST');
            if ($url{0} === '/') {
                $url = GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . $url;
            } else {
                $url = GeneralUtility::getIndpEnv('TYPO3_REQUEST_DIR') . $url;
            }
        }

        $res = GeneralUtility::getURL($url);
        if ($res && $redirect) {
            HttpUtility::redirect($url, HttpUtility::HTTP_STATUS_301);
        } elseif ($res) {
            header(HttpUtility::HTTP_STATUS_404);
            echo $res;
            exit;
        } else {
            header(HttpUtility::HTTP_STATUS_404);
        }
    }
}

