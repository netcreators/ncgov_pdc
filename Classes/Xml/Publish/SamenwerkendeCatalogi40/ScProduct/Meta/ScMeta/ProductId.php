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

namespace Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40\ScProduct\Meta\ScMeta;

use Netcreators\NcExtbaseLib\Service\Xml\Generator\Node;
use Netcreators\NcgovPdc\Domain\Model\Product;

/**
 * Xml
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Xml
 */
class ProductId extends Node
{

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $productId = $product->getScmetaProductId();

        // NOTE: Assuming that either all or none of the scmeta_product_id fields are set depending on if the Gemeente
        // is using VIND (or one of its competitors) or no third-party PDC management software.
        // Gemeentes creating and managing their products manually in the TYPO3 back end would rather leave
        // scmeta_product_id empty in all of their products. `uid` is a valid replacement in these cases.
        // To ensure this expected user behavior, the scmeta_product_id field is set to 'read-only' in the back end.
        if (!$productId) {
            $productId = $product->getUid();
        }

        $this->setTagName('overheidproduct:productID')
            ->setContent(
                htmlspecialchars(
                    $productId,
                    ENT_QUOTES
                )
            );

    }
}


