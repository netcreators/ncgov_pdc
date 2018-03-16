<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Leonie Philine Bitto [Netcreators] <leonie@netcreators.nl>
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

namespace Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40\ScProduct;

use Netcreators\NcExtbaseLib\Service\Xml\Generator\Node;
use Netcreators\NcgovPdc\Domain\Model\Product;
use Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40\Parameter;

/**
 * Xml
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Xml
 */
class Body extends Node
{

    /**
     * @param Product $product
     * @param Parameter $parameter
     */
    public function __construct(
        Product $product,
        Parameter $parameter
    ) {
        $this->setTagName('overheidproduct:body')
            ->addChild(
                $parameter->getObjectManager()->get(
                    Body\ProductHtml::class,
                    $product
                )
            );
    }
}

