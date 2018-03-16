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

/**
 * Utility
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Utility
 */
class GenerateClassPropertiesAndMethodsFromTCA
{
//require_once('/var/lib/typo3latest/typo3conf/ext/ncgov_pdc/Classes/Utility/GenerateClassPropertiesAndMethodsFromTCA.php');
//print(GenerateClassPropertiesAndMethodsFromTCA::generate(array_keys($GLOBALS['TCA']['tt_content']['columns'])));
//die();
    public static function generateProperty($name)
    {
        return "\t" . 'protected $' . $name . ' = \'\';' . chr(10);
    }

    public static function generateMethods($name)
    {
        $methods = array();
        $methods[] = "\t" . '/**' . chr(10);
        $methods[] = "\t" . ' * Returns property ' . $name . chr(10);
        $methods[] = "\t" . ' * @return $' . $name . chr(10);
        $methods[] = "\t" . ' */' . chr(10);
        $methods[] = "\t" . 'public function get' . ucfirst($name) . '() {' . chr(10);
        $methods[] = "\t\t" . 'return $this->' . $name . ';' . chr(10);
        $methods[] = "\t" . '}' . chr(10) . chr(10);

        $methods[] = "\t" . '/**' . chr(10);
        $methods[] = "\t" . ' * Sets property ' . $name . chr(10);
        $methods[] = "\t" . ' * @param $' . $name . ' the property' . chr(10);
        $methods[] = "\t" . ' * @return self for chaining' . chr(10);
        $methods[] = "\t" . ' */' . chr(10);
        $methods[] = "\t" . 'public function set' . ucfirst($name) . '($' . $name . ') {' . chr(10);
        $methods[] = "\t\t" . '$this->' . $name . ' = $' . $name . ';' . chr(10);
        $methods[] = "\t\t" . 'return $this;' . chr(10);
        $methods[] = "\t" . '}' . chr(10) . chr(10);
        return implode('', $methods);
    }

    public static function getCamelCasedName($name)
    {
        $newName = str_replace('_', ' ', $name);
        $newName = ucwords($newName);
        $newName[0] = strtolower($newName[0]);
        $newName = str_replace(' ', '', $newName);
        return $newName;
    }

    public static function generate($columns)
    {
        foreach ($columns as $name) {
            $name = self::getCamelCasedName($name);
            $properties[] = self::generateProperty($name);
            $methods[] = self::generateMethods($name);
        }
        return implode('', $properties) . chr(10) . implode('', $methods);
    }
}

