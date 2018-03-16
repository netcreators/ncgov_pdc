<?php

namespace Netcreators\NcgovPdc\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class FaqContentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @var    \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $contentObject;

    /**
     * If the escaping interceptor should be disabled inside this ViewHelper, then set this value to FALSE.
     * This is internal and NO part of the API. It is very likely to change.
     *
     * @var boolean
     * @internal
     */
    protected $escapingInterceptorEnabled = false;

    /**
     * Constructor. Used to create an instance of tslib_cObj used by the render() method.
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject injector for tslib_cObj (optional)
     * @return void
     */
    public function __construct(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject = null)
    {
        $this->contentObject = $contentObject !== null ? $contentObject : GeneralUtility::makeInstance(
            'TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer'
        );
    }

    /**
     * @param string $parseFuncTSPath path to TypoScript parseFunc setup.
     * @return string the parsed string.
     */
    public function render()
    {
        $value = trim($this->renderChildren());
        $value = str_replace(chr(10), '', $value);
        $value = trim(str_replace(array('<p>', '</p>'), array('', chr(10)), $value));
        return $value;
    }
}

