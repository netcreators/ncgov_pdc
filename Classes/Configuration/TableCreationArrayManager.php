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

/**
 * A general purpose tca manager
 *
 * @package NcgovPdc
 * @internal
 */
class TableCreationArrayManager implements \TYPO3\CMS\Core\SingletonInterface
{
    protected $configuration;

    /**
     * Constructs the configuration manager
     *
     * @internal
     */
    public function __construct()
    {
        $this->configuration = & $GLOBALS['TCA'];
    }

    /**
     * Does the requested key exist in the TCA?
     *
     * @param string $key path to the requested key
     * @return boolean    true if the key was defined, false otherwise.
     * @throws \Netcreators\NcgovPdc\Configuration\Exception\InvalidKeyException
     */
    public function exists($key)
    {
        $result = false;
        // no sub arrays?
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
            $keys = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('.', $key);
            if (is_array($keys) && count($keys) > 0) {
                $subKey = array_shift($keys);
                if (empty($subKey)) {
                    throw new \Netcreators\NcgovPdc\Configuration\Exception\InvalidKeyException($key);
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
                return isset($configuration[array_shift($kesy)]['_typoScriptNodeValue']);
            }
        } else {
            $key = array_shift($keys);
            return $this->traverseExists($key, $configuration[$key], $single);
        }
    }

    /**
     * Returns the TCA variable identified with the given path
     * @param string $key the path to the typoscript variable
     * @return mixed    the ts configuration value
     * @throws Exception\NoSuchKeyException
     * @throws \Netcreators\NcgovPdc\Configuration\Exception\InvalidKeyException
     */
    public function get($key)
    {
        $result = false;
        // no subarrays?
        if (strpos($key, '.') === false) {
            if (isset($this->configuration[$key])) {
                // geen pad opgevraagd, dus moet het een enkele waarde zijn

                if (is_array($this->configuration[$key])) {
                    // klopt niet
                    throw new Exception\NoSuchKeyException($key);
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
            $keys = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('.', $key);
            $count = count($keys);
            if ($count > 0) {
                $subKey = array_shift($keys);
                if (empty($subKey)) {
                    throw new \Netcreators\NcgovPdc\Configuration\Exception\InvalidKeyException($key);
                }
                $temp = $this->configuration;
                if (isset($temp[$subKey])) {
                    try {
                        $result = $this->traverseGet($keys, $temp[$subKey], $single);
                    } catch (\Netcreators\NcgovPdc\Configuration\Exception\InvalidKeyException $exception) {
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
     * @throws \Netcreators\NcgovPdc\Configuration\Exception\InvalidKeyException
     * @throws Exception\NoSuchKeyException
     */
    private function traverseGet($keys, $configuration, $single)
    {
        if (!is_array($keys)) {
            throw new \Netcreators\NcgovPdc\Configuration\Exception\InvalidKeyException('Undefined');
        }
        $count = count($keys);
        if ($count == 0) {
            if ($single) {
                if (is_array($configuration)) {
                    // klopt niet
                    throw new Exception\NoSuchKeyException('Undefined');
                } else {
                    if (isset($configuration)) {
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
}

