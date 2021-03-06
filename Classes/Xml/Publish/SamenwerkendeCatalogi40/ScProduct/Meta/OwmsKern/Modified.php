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

/**
 * Xml
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Xml
 */
class Modified extends Node
{

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $modifiedDate = $product->getOwmsCoreModified();

        // OWMS_core_modified is a field related to external leveranciers. We do not need to require the Gemeente to
        // manually fill this field if we can use the tstamp field instead.

        if (!$modifiedDate) {
            $modifiedDate = $product->getTstampAsDateTime();
        }

        $this->setTagName('dcterms:modified')
            ->setContent(
                htmlspecialchars(
                    $modifiedDate->format('Y-m-d'),
                    ENT_QUOTES
                )
            );
    }
}

