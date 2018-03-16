<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2010 Frans van der Veen [Netcreators] <extensions@netcreators.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller Helper
 *
 * FIXME: Not used - remove?
 *
 * @package NcgovPdc
 * @subpackage Controller
 */
class Tx_NcgovPdc_Controller_Helper_TranslateHelper
{

    /**
     * @var string
     */
    protected $locallangPath = 'Resources/Private/Language/';

    /**
     * @var string
     */
    protected $locallangPathAndFilename = null;

    /**
     * Local Language content
     *
     * @var string
     **/
    protected static $LOCAL_LANG_DB = array();

    /**
     * Local Language content charset for individual labels (overriding)
     *
     * @var string
     **/
    protected static $LOCAL_LANG_charset = array();

    /**
     * Key of the language to use
     *
     * @var string
     **/
    protected static $languageKey = 'default';

    /**
     * Pointer to alternative fall-back language to use
     *
     * @var string
     **/
    protected static $alternativeLanguageKey = '';

    /**
     * The extension name for which this instance of the view helper was called.
     *
     * @var string
     */
    protected $extensionName = '';

    /**
     * Is called before render() to initialize localization.
     *
     * @return void
     * @author Bastian Waidelich <bastian@typo3.org>
     */
    public function initialize()
    {
        if (!isset(self::$LOCAL_LANG_DB[$this->extensionName])) {
            $this->initializeLocalization();
        }
    }

    public function setExtensionName($name)
    {
        $this->extensionName = $name;
        return $this;
    }

    /**
     * Loads local-language values by looking for a "locallang.php" (or "locallang.xml") file in the plugin resources directory and if found includes it.
     * Also locallang values set in the TypoScript property "_LOCAL_LANG" are merged onto the values found in the "locallang.php" file.
     *
     * @return void
     * @author Christopher Hlubek <hlubek@networkteam.com>
     * @author Bastian Waidelich <bastian@typo3.org>
     */
    protected function initializeLocalization()
    {
        $this->locallangPathAndFilename = ExtensionManagementUtility::extPath(
            GeneralUtility::camelCaseToLowerCaseUnderscored($this->extensionName),
            $this->locallangPath . 'locallang_db.xml'
        );

        $this->setLanguageKeys();
        self::$LOCAL_LANG_DB[$this->extensionName] = GeneralUtility::readLLfile(
            $this->locallangPathAndFilename,
            self::$languageKey,
            $GLOBALS['TSFE']->renderCharset
        );
        if (self::$alternativeLanguageKey === '') {
            $alternativeLocalLang = GeneralUtility::readLLfile(
                $this->locallangPathAndFilename,
                self::$alternativeLanguageKey
            );
            self::$LOCAL_LANG_DB[$this->extensionName] = array_merge(
                self::$LOCAL_LANG_DB[$this->extensionName],
                $alternativeLocalLang
            );
        }
    }

    /**
     * Sets the currently active language/language_alt keys.
     * Default values are "default" for language key and "" for language_alt key.
     *
     * @return void
     * @author Christopher Hlubek <hlubek@networkteam.com>
     * @author Bastian Waidelich <bastian@typo3.org>
     */
    protected function setLanguageKeys()
    {
        self::$languageKey = 'default';
        self::$alternativeLanguageKey = '';
        if (isset($GLOBALS['TSFE']->config['config']['language'])) {
            self::$languageKey = $GLOBALS['TSFE']->config['config']['language'];
            if (isset($GLOBALS['TSFE']->config['config']['language_alt'])) {
                self::$alternativeLanguageKey = $GLOBALS['TSFE']->config['config']['language_alt'];
            }
        }
    }

    /**
     * Returns the localized label of the LOCAL_LANG key, $key
     * Notice that for debugging purposes prefixes for the output values can be set with the internal vars ->LLtestPrefixAlt and ->LLtestPrefix
     *
     * @param string $key The key from the LOCAL_LANG array for which to return the value.
     * @return string The value from LOCAL_LANG or NULL if no translation was found.
     * @author Christopher Hlubek <hlubek@networkteam.com>
     * @author Bastian Waidelich <bastian@typo3.org>
     */
    public function translate($key)
    {
        // The "from" charset of csConv() is only set for strings from TypoScript via _LOCAL_LANG
        if (isset(self::$LOCAL_LANG_DB[$this->extensionName][self::$languageKey][$key])) {
            $value = self::$LOCAL_LANG_DB[$this->extensionName][self::$languageKey][$key];
            if (isset(self::$LOCAL_LANG_charset[$this->extensionName][self::$languageKey][$key])) {
                $value = $GLOBALS['TSFE']->csConv(
                    $value,
                    self::$LOCAL_LANG_charset[$this->extensionName][self::$languageKey][$key]
                );
            }
            return $value;
        }

        if (self::$alternativeLanguageKey !== '' && isset(self::$LOCAL_LANG_DB[$this->extensionName][self::$alternativeLanguageKey][$key])) {
            $value = self::$LOCAL_LANG_DB[$this->extensionName][self::$alternativeLanguageKey][$key];
            if (isset(self::$LOCAL_LANG_charset[$this->extensionName][self::$alternativeLanguageKey][$key])) {
                $value = $GLOBALS['TSFE']->csConv(
                    $value,
                    self::$LOCAL_LANG_charset[$this->extensionName][self::$alternativeLanguageKey][$key]
                );
            }
        }

        if (isset(self::$LOCAL_LANG_DB[$this->extensionName]['default'][$key])) {
            return self::$LOCAL_LANG_DB[$this->extensionName]['default'][$key]; // No charset conversion because default is english and thereby ASCII
        }

        return null;
    }
}


