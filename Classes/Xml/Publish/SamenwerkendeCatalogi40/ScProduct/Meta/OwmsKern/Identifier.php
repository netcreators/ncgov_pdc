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

namespace Netcreators\NcgovPdc\Xml\Publish\SamenwerkendeCatalogi40\ScProduct\Meta\OwmsKern;

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
class Identifier extends Node
{

    /**
     * @param Product $product
     * @param Parameter $parameter
     */
    public function __construct(
        Product $product,
        Parameter $parameter
    ) {
        $this->setTagName('dcterms:identifier')
            ->setContent(
                $this->wrapStringAsCDATA(

                // Generate a valid SC 4.0 OWMS 4.0 Core Identifier, replacing the VIND loket URI (if supplied),
                // as e.g. 'http://webloket.vind.sdu.nl/295/product/producten/19':
                /**
                 * Unieke verwijzing naar de productpagina op de website
                 * van de publicerende overheid. Deze verwijzing moet uniek
                 * zijn binnen de gehele SC-collectie. Zo wordt voor elk
                 * product bij elke organisatie een aparte productpagina
                 * verwacht, en is het niet toegestaan voor twee organisaties
                 * om naar dezelfde URI te verwijzen.
                 */
                    $parameter->getUriBuilder()
                        ->reset()
                        ->setCreateAbsoluteUri(true)
                        ->setTargetPageUid($parameter->getProductPageId())
                        ->uriFor('detail', array('product' => $product))
                )
            );
    }
}

