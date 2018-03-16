<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2012 Frans van der Veen [Netcreators] <extensions@netcreators.com>
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

namespace Netcreators\NcgovPdc\ViewHelpers;

/**
 *
 */
class ProductContainsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
{
    /**
     * Renders the then child if the product contains the given value in the given property
     * @param object $product the object
     * @param string $contains the value the property contains for the then statement
     * @param string $propertyName the property to get from the object
     * @return string value of the property.
     */
    public function render($product, $contains, $propertyName)
    {
        $value = $this->getProductProperty($product, $propertyName);
        if (strpos($value, $contains) !== false) {
            $result = $this->renderThenChild();
        } else {
            $result = $this->renderElseChild();
        }
        return $result;
    }

    /**
     * Gets the property
     * @param object $subject
     * @param string $propertyName
     * @return string
     */
    protected function getProductProperty($subject, $propertyName)
    {
        $value = '';

        if (is_callable(array($subject, 'get' . ucfirst($propertyName)))) {
            $value = call_user_func(array($subject, 'get' . ucfirst($propertyName)));
        } elseif ($subject instanceof ArrayAccess && isset($subject[$propertyName])) {
            $value = $subject[$propertyName];
        } elseif (array_key_exists($propertyName, get_object_vars($subject))) {
            $value = $subject->$propertyName;
        }
        return $value;
    }
}

