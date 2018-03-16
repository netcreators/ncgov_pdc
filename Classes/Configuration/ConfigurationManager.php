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

namespace Netcreators\NcgovPdc\Configuration;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A general purpose configuration manager
 *
 * @package ncgov_pdc
 * @internal
 */
class ConfigurationManager implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * Storage for the settings, loaded by loadGlobalSettings()
     *
     * @var array
     */
    protected $settings = array();
    /**
     * @var array
     */
    protected $defaults = array();
    /**
     * @var boolean
     */
    protected $initialized = false;

    /**
     * Constructs the configuration manager
     *
     * @param array $settings An array of object names of the configuration sources
     * @internal
     */
    public function __construct(array $settings = array())
    {
        $this->settings = $settings;
    }

    /**
     * Initializes the configuration manager.
     * @param array $settings the controller's settings array
     * @param array $defaults the default values of the settings when empty
     * @param string $extensionName the name of the extension
     * @param array|boolean $skipPaths paths to ignore
     * @return void
     */
    public function initialize($settings, $defaults, $extensionName, $skipPaths = false)
    {
        $this->initialized = true;
        $this->settings = $settings;
        $this->globalExtensionConfiguration = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extensionName]
        );
        $this->setTSDefaults($defaults, $skipPaths);
        $this->initializeTypoScriptConfig();
    }

    protected $configuration;
    protected $flexForm;
    protected $typoScript;
    protected $dontCheckPaths; // paths which should be skipped
    protected $globalExtensionConfiguration;

    /**
     * Sets the default values for Flexform and TS configuration model.
     *
     * @param array $defaults
     * @param bool|array $dontCheckPaths
     */
    public function setTSDefaults($defaults, $dontCheckPaths = false)
    {
        $this->defaults = $defaults;
        $this->dontCheckPaths = $dontCheckPaths;
    }

    /**
     * Initializes the typoscript configuration.
     * Overrides default configuration settings with TS configuration.
     *
     * @returns void
     */
    public function initializeTypoScriptConfig()
    {
        // init flexform and data associated
        // TODO: make typoscript & flexform configuration parser / checker

        if (is_array($this->defaults) && count($this->defaults) > 0) {
            // Copy all TypoScript values over the default values
            $this->configuration = $this->_overrideSettings($this->settings, $this->defaults, $this->dontCheckPaths);
        } else {
            $this->configuration = $this->settings;
        }
    }

    /**
     * Merges two arrays, overrides the values in the target array with values in the source.
     *
     * @param array $source
     * @param array $target
     * @param array|boolean $dontCheckPaths
     * @param array|boolean $currentPath
     * @return array
     */
    private function _overrideSettings($source, $target, $dontCheckPaths = false, $currentPath = false)
    {
        // Source will be copied over target, if existent
        // Target will be returned, so values already in target will remain
        $override = false;
        if ($dontCheckPaths !== false) {
            if (is_array($dontCheckPaths) && count($dontCheckPaths) > 0) {
                foreach ($dontCheckPaths as $path) {
                    if (GeneralUtility::isFirstPartOfStr($currentPath, $path)) {
                        $override = true;
                        break;
                    }
                }
            }
        }
        if (count($source) > 0) {
            foreach ($source as $key => $value) {
                if (is_array($value)) {
                    if (is_array($target[$key])) {
                        // when both arrays, call recursively iterate the target array
                        if (empty($currentPath)) {
                            $newPath = $key;
                        } else {
                            $newPath = $currentPath . '.' . $key;
                        }
                        $target[$key] = $this->_overrideSettings($value, $target[$key], $dontCheckPaths, $newPath);
                    } else {
                        if (isset($target[$key])) {
                            // not defined yet
                            $target[$key] = $value;
                        } else {
                            if ($override) {
                                // copy anyway
                                $target[$key] = $value;
                            }
                        }
                    }
                } else {
                    if (isset($target[$key])) {
                        // not an array && already defined?
                        $target[$key] = $value;
                    } else {
                        if ($override) {
                            $target[$key] = $value;
                        }
                    }
                }
            }
        }
        return $target;
    }

    /**
     * Does the requested key exist in the TS configuration / flexform?
     *
     * @param string $key path to the requested key
     * @return boolean    true if the key was defined, false otherwise.
     * @throws Exception\InvalidKeyException
     * @throws Exception\NotInitializedException
     */
    public function exists($key)
    {
        if (!$this->initialized) {
            throw new Exception\NotInitializedException();
        }
        $result = false;
        // no subarrays?
        if (strpos($key, '.') === false) {
            if (isset($this->configuration[$key])) {
                $result = true;
            }
        } else {
            $single = true;
            if (substr($key, -1, 1) == '.') {
                $single = false;
                $key = substr($key, 0, -1);
            }
            $keys = GeneralUtility::trimExplode('.', $key);
            if (is_array($keys) && count($keys) > 0) {
                $subKey = array_shift($keys);
                if (empty($subKey)) {
                    throw new Exception\InvalidKeyException($key);
                }
                $temp = $this->configuration;
                if (isset($temp[$subKey])) {
                    $result = $this->traverseExists($keys, $temp[$subKey], $single);
                }
            }
        }
        return $result;
    }

    /**
     * Traverses the given path array into configuration to check if the endpoint exists.
     * @param array $keys the path to be traversed
     * @param array $configuration the configuration to be traversed
     * @param boolean $single are we getting a single variable or an array?
     * @return boolean    true if the given path leads to an existing typoscript variable
     */
    private function traverseExists($keys, $configuration, $single)
    {
        if (!is_array($keys)) {
            return false;
        }
        if (count($keys) == 1) {
            // last part in branche
            if ($single) {
                return isset($configuration[array_shift($keys)]);
            } else {
                return isset($configuration[array_shift($keys)]['_typoScriptNodeValue']);
            }
        } else {
            $key = array_shift($keys);
            return $this->traverseExists($keys, $configuration[$key], $single);
        }
    }

    /**
     * Returns the typoscript variable identified with the given path
     * @param string $key the path to the typoscript variable
     * @return mixed    the ts configuration value
     * @throws Exception\NotInitializedException
     * @throws Exception\NoSuchKeyException
     * @throws Exception\InvalidKeyException
     */
    public function get($key)
    {
        if (!$this->initialized) {
            throw new Exception\NotInitializedException();
        }
        $result = false;
        // no subarrays?
        if (strpos($key, '.') === false) {
            if (isset($this->configuration[$key])) {
                // geen pad opgevraagd, dus moet het een enkele waarde zijn
                if (is_array($this->configuration[$key])) {
                    if (array_search('_typoScriptNodeValue', array_keys($this->configuration[$key])) !== false) {
                        $result = $this->configuration[$key]['_typoScriptNodeValue'];
                    } else {
                        throw new Exception\NoSuchKeyException($key);
                    }
                } else {
                    $result = $this->configuration[$key];
                }
            } else {
                throw new Exception\NoSuchKeyException($key);
            }
        } else {
            $single = true;
            if (substr($key, -1, 1) == '.') {
                $single = false;
                $key = substr($key, 0, -1);
            }
            $keys = GeneralUtility::trimExplode('.', $key);
            $count = count($keys);
            if ($count > 0) {
                $subKey = array_shift($keys);
                if (empty($subKey)) {
                    throw new Exception\InvalidKeyException($key);
                }
                $temp = $this->configuration;
                if (isset($temp[$subKey])) {
                    try {
                        $result = $this->traverseGet($keys, $temp[$subKey], $single);
                    } catch (Exception\InvalidKeyException $exception) {
                        $exception->setKey($key);
                        throw $exception;
                    } catch (Exception\NoSuchKeyException $exception) {
                        $exception->setKey($key);
                        throw $exception;
                    }
                } else {
                    throw new Exception\NoSuchKeyException($key);
                }
            }
        }
        return $result;

    }

    /**
     * Traverses the given path array into the given configuration array. Returns the value if it exists. Throws exception if it does not.
     * @param $keys
     * @param $configuration
     * @param $single
     * @return mixed
     * @throws Exception\NoSuchKeyException
     * @throws Exception\InvalidKeyException
     */
    private function traverseGet($keys, $configuration, $single)
    {
        if (!is_array($keys)) {
            throw new Exception\InvalidKeyException('Undefined');
        }
        $count = count($keys);
        if ($count == 0) {
            if ($single) {
                if (is_array($configuration) && array_search(
                        '_typoScriptNodeValue',
                        array_keys($configuration)
                    ) !== false
                ) {
                    return $configuration['_typoScriptNodeValue'];
                } else {
                    if (isset($configuration) && !is_array($configuration)) {
                        return $configuration;
                    }
                    throw new Exception\NoSuchKeyException('Undefined');
                }
            } else {
                if (isset($configuration)) {
                    return $configuration;
                } else {
                    throw new Exception\NoSuchKeyException('Undefined');
                }
            }
        } else {
            $key = array_shift($keys);
            return $this->traverseGet($keys, $configuration[$key], $single);
        }
    }

    /**
     * Returns global extension configuration identified with key.
     * @param string $key the configuration key
     * @throws Exception\NotInitializedException
     * @throws Exception\NoSuchGlobalKeyException
     * @return mixed    the value of the configuration key
     */
    public function getExtensionConfiguration($key)
    {
        if (!$this->initialized) {
            throw new Exception\NotInitializedException();
        }
        if (!isset($this->globalExtensionConfiguration[$key])) {
            throw new Exception\NoSuchGlobalKeyException($key);
        }
        return $this->globalExtensionConfiguration[$key];
    }
}

