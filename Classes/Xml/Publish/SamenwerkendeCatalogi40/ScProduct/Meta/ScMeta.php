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

namespace Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40\ScProduct\Meta;

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
class ScMeta extends Node
{

    /**
     * @param Product $product
     */
    public function __construct(
        Product $product,
        Parameter $parameter
    ) {
        $this->setTagName('overheidproduct:scmeta')
            ->addChild(
                $parameter->getObjectManager()->get(
                    ScMeta\ProductId::class,
                    $product
                )
            )
            ->addChild(
                $parameter->getObjectManager()->get(
                    ScMeta\OnlineAanvragen::class,
                    $product
                )
            );

        if ($product->getScmetaRequestOnline() != Product::REQUEST_ONLINE_NO
            && $product->getScmetaRequestOnlineUrl()
        ) {
            $this->addChild(
                $parameter->getObjectManager()->get(
                    ScMeta\AanvraagUrl::class,
                    $product
                )
            );
        }

        if ($product->getScmetaRequestOnlineSingleSignOn(
            ) != Product::REQUEST_ONLINE_SINGLE_SIGN_ON_UNDEFINED
        ) {
            $this->addChild(
                $parameter->getObjectManager()->get(
                    ScMeta\EenmaligAnmelden::class,
                    $product
                )
            );
        }

        if ($product->getScmetaContactPoint()) {
            $this->addChild(
                $parameter->getObjectManager()->get(
                    ScMeta\ContactPunt::class,
                    $product
                )
            );
        }

        foreach ($product->getScmetaUniformProductNamesWithName() as $resourceIdentifier => $uniformProductName) {
            $this->addChild(
                $parameter->getObjectManager()->get(
                    ScMeta\UniformeProductNaam::class,
                    $resourceIdentifier,
                    $uniformProductName
                )
            );
        }

        foreach ($product->getScmetaRelatedUniformProductNamesWithName(
                 ) as $resourceIdentifier => $uniformProductName) {
            $this->addChild(
                $parameter->getObjectManager()->get(
                    ScMeta\GerelateerdProduct::class,
                    $resourceIdentifier,
                    $uniformProductName
                )
            );
        }
    }
}

