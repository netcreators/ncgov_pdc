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

namespace Netcreators\NcgovPdc\Domain\Model;

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class ReferenceLinkFactory
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    /**
     * Creates a reference link to a resource identifier (th
     * @param string $link the link (presumably a http resource)
     * @param string $name the link name (optional)
     * @throws Exception\LinkNotSpecifiedException
     * @return ReferenceLink    the link object
     */
    public function createReferenceToOtherInfo($link, $name = '')
    {
        if (empty($link)) {
            // unable to link to nothing
            throw new Exception\LinkNotSpecifiedException();
        }
        if (empty($name)) {
            $name = $link;
        }
        $referenceLink = $this->createReferenceLink(
            $link,
            ReferenceLink::TYPE_URL,
            $name
        );
        $referenceLink->setLink($link);
        return $referenceLink;
    }

    /**
     * Creates a link to a product.
     * @param \Netcreators\NcgovPdc\Domain\Model\Product $product the product
     * @param string $resourceIdentifier
     * @param string $name the link name (optional)
     * @throws Exception\LinkNotSpecifiedException
     * @return ReferenceLink    the link object
     */
    public function createReferenceToProduct(
        \Netcreators\NcgovPdc\Domain\Model\Product $product,
        $resourceIdentifier,
        $name = ''
    ) {
        $referenceLink = $this->createReferenceLink(
            $resourceIdentifier,
            ReferenceLink::TYPE_PRODUCT,
            $name
        );
        $referenceLink->setLinkProduct($product);
        return $referenceLink;
    }

    /**
     * Creates a link to a product.
     * @param \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @param string $resourceIdentifier
     * @param string $name
     * @throws Exception\LinkNotSpecifiedException
     * @return ReferenceLink
     */
    public function createReferenceToFrequentlyAskedQuestion(
        \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion $frequentlyAskedQuestion,
        $resourceIdentifier,
        $name = ''
    ) {
        if (empty($frequentlyAskedQuestion)) {
            // unable to link to nothing
            throw new Exception\LinkNotSpecifiedException();
        }
        $referenceLink = $this->createReferenceLink(
            $resourceIdentifier,
            ReferenceLink::TYPE_FREQUENTLY_ASKED_QUESTION,
            $name
        );
        $referenceLink->setLinkFrequentlyAskedQuestion($frequentlyAskedQuestion);
        return $referenceLink;
    }

    /**
     * Creates a new reference link object
     * @param string $resourceIdentifier
     * @param integer $type
     * @param string $name the name of the object (optional)
     * @return ReferenceLink
     */
    protected function createReferenceLink($resourceIdentifier, $type, $name = '')
    {
        $referenceLink = $this->objectManager->get(ReferenceLink::class);
        if (!empty($name)) {
            $referenceLink->setName($name);
        }
        $referenceLink->setImported(true);
        $referenceLink->setResourceIdentifier($resourceIdentifier);
        $referenceLink->setType($type);
        return $referenceLink;
    }
}

