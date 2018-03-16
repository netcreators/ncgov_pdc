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

use Netcreators\NcExtbaseLib\Domain\Model\Base;
use Netcreators\NcgovPdc\Utility\LinkUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class ReferenceLink extends Base
{
    /**
     * @var string
     */
    protected $name = '';
    /**
     * @var boolean
     */
    protected $imported = false;
    /**
     * @var string
     */
    protected $link = '';
    /**
     * @var integer
     */
    protected $linkPage = '';

    /**
     * @var \Netcreators\NcgovPdc\Domain\Model\Product
     */
    protected $linkProduct = null;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion
     */
    protected $linkFrequentlyAskedQuestion = null;
    /**
     * @var string
     */
    protected $resourceIdentifier = '';
    /**
     * @var integer
     */
    protected $type;
    /**
     * @var integer
     */
    protected $subtype;
    /**
     * @var string
     */
    protected $description = '';
    /**
     * @var boolean
     */
    protected $isDigidService = false;
    /**
     * @var boolean
     */
    protected $isElectronicService = false;
    /**
     * @var string
     */
    protected $serviceUrl = '';

    /**
     * @var bool
     */
    protected $cleanUrl = true;

    /**
     * @var bool|integer
     */
    protected $pageUid = false;

    const TYPE_URL = 4, TYPE_PRODUCT = 1, TYPE_FREQUENTLY_ASKED_QUESTION = 2, TYPE_PAGE = 3, TYPE_URL_PRODUCT = 5;

    const SUBTYPE_LAW = 1, SUBTYPE_LOCAL_LAW = 2, SUBTYPE_FORM = 3, SUBTYPE_INTERNAL = 4, SUBTYPE_EXTERNAL = 5, SUBTYPE_CTA_BUTTON = 6;

    /**
     * Sets the url rendering state, clean is short.
     * @param boolean $state set the state of how the url should be rendered
     */
    public function setCleanUrl($state)
    {
        $this->cleanUrl = $state;
    }

    /**
     * Returns whether the clean url is set
     * @return boolean the url state
     */
    public function getCleanUrl()
    {
        return $this->cleanUrl;
    }

    /**
     * @return bool
     */
    public function getIsValid()
    {
        switch ($this->type) {
            case self::TYPE_URL:
            case self::TYPE_URL_PRODUCT:
                return true;
            case self::TYPE_PRODUCT:
                if ($this->linkProduct && $this->linkProduct instanceof Product) {
                    return true;
                }
                return false;
            case self::TYPE_FREQUENTLY_ASKED_QUESTION:
                if ($this->linkFrequentlyAskedQuestion && $this->linkFrequentlyAskedQuestion instanceof FrequentlyAskedQuestion) {
                    return true;
                }
                return false;
            case self::TYPE_PAGE:
                return true;
        }

        return false; // invalid type
    }

    /**
     * @return bool|string
     * @throws Exception\TargetPageNotSpecifiedException
     */
    public function getLinkUri()
    {
        // Cannot @inject uriBuilder. @see \Netcreators\NcgovPdc\Domain\Model\Product::getLink() for explanation.
        /** @var UriBuilder $uriBuilder */
        $uriBuilder = $this->objectManager->get(UriBuilder::class);

        $result = false;
        switch ($this->type) {
            case self::TYPE_URL_PRODUCT:
                // intentional fallthough
            case self::TYPE_URL:
                // the url itself will contain the link
                $result = $this->link;
                if (strpos($result, 'http://') === false && strpos($result, 'https://') === false) {
                    $result = 'http://' . $result;
                }
                break;
            case self::TYPE_PRODUCT:
                // the link product will contain the id of the product
                if ($this->pageUid === false) {
                    throw new Exception\TargetPageNotSpecifiedException();
                }
                // the link frequently asked question will contain the id of the faq
                $uriBuilder->reset();
                $uriBuilder->setCreateAbsoluteUri(true);
                $uriBuilder->setTargetPageUid($this->pageUid);
                $controller = 'Product';
                $action = 'detail';
                if ($this->cleanUrl) {
                    $controller = '';
                    $action = '';
                }
                $result = $uriBuilder->uriFor(
                    $action,
                    array(
                        'product' => $this->linkProduct
                    ),
                    $controller,
                    'NcgovPdc',
                    'Pi'
                );
                break;
            case self::TYPE_FREQUENTLY_ASKED_QUESTION:
                if ($this->pageUid === false) {
                    throw new Exception\TargetPageNotSpecifiedException();
                }
                // the link frequently asked question will contain the id of the faq
                $uriBuilder->reset();
                $uriBuilder->setCreateAbsoluteUri(true);
                $uriBuilder->setTargetPageUid($this->pageUid);
                $controller = 'FrequentlyAskedQuestion';
                $action = 'detail';
                if ($this->cleanUrl) {
                    $controller = '';
                    $action = '';
                }
                $result = $uriBuilder->uriFor(
                    $action,
                    array(
                        'frequentlyAskedQuestion' => $this->linkFrequentlyAskedQuestion
                    ),
                    $controller,
                    'NcgovPdc',
                    'Pi'
                );
                break;
            case self::TYPE_PAGE:
                // the link will contain the pid
                $uriBuilder->reset();
                $uriBuilder->setCreateAbsoluteUri(true);
                $uriBuilder->setTargetPageUid($this->linkPage);
                break;
        }
        return $result;
    }

    /**
     * Returns the name of the link for use in 'title' fields
     * @return string
     */
    public function getTitle()
    {
        $result = $this->title;
        if (!isset($this->title)) {
            $result = $this->createTitle();
        }
        return $result;
    }

    /**
     * @return bool|string
     * @throws Exception\TargetPageNotSpecifiedException
     */
    protected function createTitle()
    {
        if (isset($this->name)) {
            $result = $this->name;
        } elseif (isset($this->link)) {
            $result = $this->link;
        } else {
            $result = $this->getLinkUri();
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return bool
     */
    public function getIsLinkToPage()
    {
        return LinkUtility::isLinkToPage($this->link);
    }

    /**
     * @return Product
     */
    public function getLinkProduct()
    {
        return $this->linkProduct;
    }

    /**
     * @return FrequentlyAskedQuestion
     */
    public function getLinkFrequentlyAskedQuestion()
    {
        return $this->linkFrequentlyAskedQuestion;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param Product $linkProduct
     */
    public function setLinkProduct(Product $linkProduct)
    {
        $this->linkProduct = $linkProduct;
    }

    /**
     * @param FrequentlyAskedQuestion $linkFrequentlyAskedQuestion
     */
    public function setLinkFrequentlyAskedQuestion(FrequentlyAskedQuestion $linkFrequentlyAskedQuestion)
    {
        $this->linkFrequentlyAskedQuestion = $linkFrequentlyAskedQuestion;
    }

    /**
     * @return string
     */
    public function getResourceIdentifier()
    {
        return $this->resourceIdentifier;
    }

    /**
     * @param string $value
     */
    public function setResourceIdentifier($value)
    {
        $this->resourceIdentifier = $value;
    }

    /**
     * @return boolean
     */
    public function getImported()
    {
        return $this->imported;
    }

    /**
     * @param boolean $imported
     */
    public function setImported($imported)
    {
        $this->imported = $imported;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * @param int $subtype
     * @return self
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
        return $this;
    }

    /**
     * @return int
     */
    public function getLinkPage()
    {
        return $this->linkPage;
    }

    /**
     * @param int $linkPage
     * @return self
     */
    public function setLinkPage($linkPage)
    {
        $this->linkPage = $linkPage;
        return $this;
    }

    /**
     * Sets the description of the link
     * @param string $value the description
     * @return \Netcreators\NcgovPdc\Domain\Model\ReferenceLink instance for chaining
     */
    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    /**
     * Returns the description of the link
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets whether the link refers to a digid service
     * @param boolean $value true if the link refers to a digid service, false otherwise
     * @return \Netcreators\NcgovPdc\Domain\Model\ReferenceLink instance for chaining
     */
    public function setIsDigidService($value)
    {
        $this->isDigidService = $value;
        return $this;
    }

    /**
     * Returns whether the link refers to a digid service.
     * @return boolean
     */
    public function getIsDigidService()
    {
        return $this->isDigidService;
    }

    /**
     * Sets whether the link refers to an electronic service
     * @param boolean $value true if the link refers to an electronic service, false otherwise
     * @return \Netcreators\NcgovPdc\Domain\Model\ReferenceLink
     */
    public function setIsElectronicService($value)
    {
        $this->isElectronicService = $value;
        return $this;
    }

    /**
     * Returns whether the link refers to an electronic service
     * @return boolean
     */
    public function getIsElectronicService()
    {
        return $this->isElectronicService;
    }

    /**
     * Sets the electronic service url
     * @param string $value the service url
     * @return \Netcreators\NcgovPdc\Domain\Model\ReferenceLink instance for chaining
     */
    public function setServiceUrl($value)
    {
        $this->serviceUrl = $value;
        return $this;
    }

    /**
     * Returns the electronic service url
     * @return string
     */
    public function getServiceUrl()
    {
        return $this->serviceUrl;
    }

    /**
     * Tests if the link is an ictu resource identifier.
     * @param string $link teh link
     * @return boolean    true if it is an ictu resource reference
     */
    public function linkIsResourceIdentifier($link)
    {
        return strtoupper(substr($link, 0, 7)) == 'ICTU://';
    }

    /**
     * Sets the page identifier for the page where the frequently asked question / product will be displayed
     * @param integer $uid the identifier
     * @return self for chaining
     */
    public function setPageIdentifier($uid)
    {
        $this->pageUid = $uid;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->link . $this->name . $this->resourceIdentifier;
    }
}

