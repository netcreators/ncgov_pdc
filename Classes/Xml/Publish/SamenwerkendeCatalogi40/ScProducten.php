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

namespace Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40;

use Netcreators\NcExtbaseLib\Service\Xml\Generator\Node;

/**
 * Xml
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Xml
 */
class ScProducten extends Node
{

    /**
     * @param Parameter $parameter
     */
    public function __construct(Parameter $parameter)
    {
        $this->setTagName('overheidproduct:scproducten')
            ->setNamespace('xmlns:dcterms', 'http://purl.org/dc/terms/')
            ->setNamespace('xmlns:overheid', 'http://standaarden.overheid.nl/owms/terms/')
            ->setNamespace('xmlns:overheidproduct', 'http://standaarden.overheid.nl/product/terms/')
            ->setNamespace('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance')
            ->addAttribute(
                'xsi:schemaLocation',
                'http://standaarden.overheid.nl/product/terms/ http://standaarden.overheid.nl/sc/4.0/xsd/sc.xsd'
            );

        /** @var \Netcreators\NcgovPdc\Domain\Model\Product $product */
        foreach ($parameter->getProducts() as $product) {
            $this->addChild(
                $parameter->getObjectManager()->get(
                    ScProduct::class,
                    $product,
                    $parameter
                )
            );
        }
    }
}

