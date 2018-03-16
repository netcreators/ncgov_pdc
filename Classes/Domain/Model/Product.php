<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2014 Frans van der Veen [Netcreators] <extensions@netcreators.com>
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
use Netcreators\NcExtbaseLib\Domain\Repository\TtContentRepository;
use Netcreators\NcgovPdc\Configuration\TableCreationArrayManager;
use Netcreators\NcgovPdc\Utility\LinkUtility;
use Netcreators\NcgovPdc\Utility\TioThemeClassificationReader;
use Netcreators\NcgovPdc\Utility\UniformProductNameListReader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Product Model
 */
class Product extends Base
{

    /**
     * @var string
     */
    public $name = '';
    /**
     * @var string
     */
    protected $owmsCoreIdentifier = '';
    /**
     * @var \DateTime
     */
    protected $owmsCoreModified;
    /**
     * @var string
     */
    protected $owmsMantleAbstract = '';
    /**
     * @var string
     */
    protected $scmetaProductId = '';
    /**
     * @var integer
     */
    protected $scmetaRequestOnline = self::REQUEST_ONLINE_NO;
    /**
     * @var string
     */
    protected $scmetaRequestOnlineUrl = '';
    /**
     * @var integer
     */
    protected $scmetaRequestOnlineSingleSignOn = self::REQUEST_ONLINE_SINGLE_SIGN_ON_UNDEFINED;
    /**
     * @var string
     */
    protected $scmetaContactPoint = '';
    /**
     * @var string
     */
    protected $scmetaUniformProductNames = '';
    /**
     * @var string
     */
    protected $scmetaRelatedUniformProductNames = '';
    /**
     * @var string
     */
    protected $tioThemes = '';
    /**
     * @var integer
     */
    protected $weight = 0;
    /**
     * @var integer
     */
    protected $sessionNumber = 0;
    /**
     * @var boolean
     */
    protected $imported = false;

    /**
     * @var integer
     */
    protected $tstamp = 0;
    /**
     * @var integer
     */
    protected $crdate = 0;
    /**
     * @var integer
     */
    protected $turnaround = 0;
    /**
     * @var integer
     */
    protected $type = 0;

    /**
     * @var string
     */
    protected $changes = '';
    /**
     * @var integer
     */
    protected $costs = '';

    /**
     * @var string
     */
    protected $deskMemo = '';

    /**
     * @var string
     */
    protected $shortDescription = '';

    /**
     * @var string
     */
    protected $preDescription = '';
    /**
     * @var string
     */
    protected $description = '';
    /**
     * @var string
     */
    protected $postDescription = '';
    /**
     * @var boolean
     */
    protected $useDescription = false;
    /**
     * @var boolean
     */
    protected $usePreDescription = false;
    /**
     * @var boolean
     */
    protected $usePostDescription = false;

    /**
     * @var string
     */
    protected $preApplyInfo = '';
    /**
     * @var string
     */
    protected $applyInfo = '';
    /**
     * @var string
     */
    protected $postApplyInfo = '';
    /**
     * @var boolean
     */
    protected $usePreApplyInfo = false;
    /**
     * @var boolean
     */
    protected $useApplyInfo = false;
    /**
     * @var boolean
     */
    protected $usePostApplyInfo = false;

    /**
     * @var string
     */
    protected $preExtraInfo = '';
    /**
     * @var string
     */
    protected $extraInfo = '';
    /**
     * @var string
     */
    protected $postExtraInfo = '';
    /**
     * @var boolean
     */
    protected $usePreExtraInfo = false;
    /**
     * @var boolean
     */
    protected $useExtraInfo = false;
    /**
     * @var boolean
     */
    protected $usePostExtraInfo = false;

    /**
     * @var string
     */
    protected $preContactInfo = '';
    /**
     * @var string
     */
    protected $contactInfo = '';
    /**
     * @var string
     */
    protected $postContactInfo = '';
    /**
     * @var boolean
     */
    protected $usePreContactInfo = false;
    /**
     * @var boolean
     */
    protected $useContactInfo = false;
    /**
     * @var boolean
     */
    protected $usePostContactInfo = false;

    /**
     * @var string
     */
    protected $preRequiredForApplication = '';
    /**
     * @var string
     */
    protected $requiredForApplication = '';
    /**
     * @var string
     */
    protected $postRequiredForApplication = '';
    /**
     * @var boolean
     */
    protected $usePreRequiredForApplication = false;
    /**
     * @var boolean
     */
    protected $useRequiredForApplication = false;
    /**
     * @var boolean
     */
    protected $usePostRequiredForApplication = false;

    /**
     * @var string
     */
    protected $preLegalBasis = '';
    /**
     * @var string
     */
    protected $legalBasis = '';
    /**
     * @var string
     */
    protected $postLegalBasis = '';
    /**
     * @var boolean
     */
    protected $usePreLegalBasis = false;
    /**
     * @var boolean
     */
    protected $useLegalBasis = false;
    /**
     * @var boolean
     */
    protected $usePostLegalBasis = false;

    /**
     * @var string
     */
    protected $preTerms = '';
    /**
     * @var string
     */
    protected $terms = '';
    /**
     * @var string
     */
    protected $postTerms = '';
    /**
     * @var boolean
     */
    protected $usePreTerms = false;
    /**
     * @var boolean
     */
    protected $useTerms = false;
    /**
     * @var boolean
     */
    protected $usePostTerms = false;

    /**
     * @var string
     */
    protected $preResult = '';
    /**
     * @var string
     */
    protected $result = '';
    /**
     * @var string
     */
    protected $postResult = '';
    /**
     * @var boolean
     */
    protected $usePreResult = false;
    /**
     * @var boolean
     */
    protected $useResult = false;
    /**
     * @var boolean
     */
    protected $usePostResult = false;

    /**
     * @var string
     */
    protected $preAppeal = '';
    /**
     * @var string
     */
    protected $appeal = '';
    /**
     * @var string
     */
    protected $postAppeal = '';
    /**
     * @var boolean
     */
    protected $usePreAppeal = false;
    /**
     * @var boolean
     */
    protected $useAppeal = false;
    /**
     * @var boolean
     */
    protected $usePostAppeal = false;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    public $frequentlyAskedQuestionInfo;
    /**
     * @var string
     */
    public $image;

    /**
     * @var string
     */
    protected $showFields;

    /**
     * @var boolean
     */
    protected $hidden;

    // relation fields
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\TtAddress>
     */
    protected $contactAddresses;

    /**
     * @var string
     */
    protected $costsContent;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Synonym>
     * @lazy
     */
    protected $synonyms;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Keyword>
     * @lazy
     */
    protected $keywords;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\LinkGroup>
     * @lazy
     */
    protected $linkGroups;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion>
     * @lazy
     */
    protected $frequentlyAskedQuestions;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Responsible>
     * @lazy
     */
    protected $responsibles;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Product>
     * @lazy
     */
    protected $relatedProducts;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Tip>
     * @lazy
     * @cascade remove
     */
    protected $tips;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Authority>
     * @lazy
     */
    protected $authorities;

    /**
     * @var string
     */
    protected $requestForm;
    /**
     * @var string
     */
    public $attachments;
    /**
     * @var string
     */
    protected $directive;
    /**
     * @var string
     */
    protected $processDescription;
    /**
     * @var string
     */
    protected $relatedRegulatory;
    /**
     * @var string
     */
    protected $audience;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceLaws;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceLocalLaws;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceForms;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceInternal;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceExternal;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcExtbaseLib\Domain\Model\BackendUserGroup>
     * @lazy
     */
    protected $maintainedBy;
    /**
     * @var string
     */
    protected $language;

    /**
     * @var \Netcreators\NcgovPdc\Configuration\TableCreationArrayManager
     * @inject
     */
    protected $tableCreationArrayManager = null;

    /**
     * @var bool
     */
    protected $linkGroupsSorted = false;

    /**
     * @var bool
     */
    protected $sortAsc = false;

    /**
     * @var bool
     */
    protected $memberSortBy = false;

    /**
     * @var string
     */
    protected $customLabel = '';

    /**
     * @var boolean
     */
    protected $showDynamicContent = false;

    /**
     * Enum for $scmetaRequestOnline
     */
    const REQUEST_ONLINE_NO = 0;
    const REQUEST_ONLINE_YES = 1;
    const REQUEST_ONLINE_DIGID = 2;

    /**
     * Enum for $scmetaSingleSignOn
     */
    const REQUEST_ONLINE_SINGLE_SIGN_ON_NO = 0;
    const REQUEST_ONLINE_SINGLE_SIGN_ON_YES = 1;
    const REQUEST_ONLINE_SINGLE_SIGN_ON_UNDEFINED = 2;

    /**
     * Constructs this product
     * @param TableCreationArrayManager $tableCreationArrayManager
     */
    public function injectTableCreationArrayManager(
        TableCreationArrayManager $tableCreationArrayManager
    ) {
        $this->tableCreationArrayManager = $tableCreationArrayManager;
    }

    public function initializeObject()
    {
        $this->contactAddresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->keywords = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->linkGroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->synonyms = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->frequentlyAskedQuestions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->responsibles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->relatedProducts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->tips = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->authorities = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceLaws = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceLocalLaws = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceForms = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceInternal = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceExternal = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->maintainedBy = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Sets the uid of the product
     * @param integer $uid
     * @return Product
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * Sorts the related frequently asked questions. Please note that each faq should have the mmSorting property
     * set in the order you need them, before calling this method.
     * @return void
     */
    public function sortFrequentlyAskedQuestions()
    {

        if (count($this->frequentlyAskedQuestions) > 0) {
            $this->sortAsc = 'ASC' === 'ASC';
            $this->memberSortBy = 'mmSorting';
            $storage = $this->frequentlyAskedQuestions->toArray();
            $sortResult = uasort($storage, array($this, 'sortCompare'));

            if ($sortResult) {
                $this->frequentlyAskedQuestions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

                foreach ($storage as $item) {
                    $this->frequentlyAskedQuestions->attach($item);
                }
            }
        }
    }

    /**
     * Sorts the related link groups. It will be sorted by the value of the link group sorting field
     * @return void
     */
    public function sortLinkGroups()
    {

        if (!$this->linkGroupsSorted && count($this->linkGroups) > 0) {
            $this->linkGroupsSorted = true;
            $oldSort = $this->sortAsc;
            $this->sortAsc = 'ASC' === 'ASC';
            $this->memberSortBy = 'sorting';
            $storage = $this->linkGroups->toArray();
            $sortResult = uasort($storage, array($this, 'sortCompare'));

            if ($sortResult) {
                $this->linkGroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

                foreach ($storage as $item) {
                    $this->linkGroups->attach($item);
                }
            }

            $this->sortAsc = $oldSort;
        }
    }

    /**
     * Comparison method for usort
     * @param mixed $a
     * @param mixed $b
     * @return integer
     * @throws \Exception
     * @author Claus Due, Wildside A/S
     */
    protected function sortCompare($a, $b)
    {
        if (is_string($a)) {
            $comp = strcmp($a, $b);
            if ($this->sortAsc === false) {
                // reverse output
                if ($comp < 0) {
                    return 1;
                } else {
                    if ($comp > 0) {
                        return -1;
                    } else {
                        return $comp;
                    }
                }
            }
        } else {
            if (is_numeric($a)) {
                if ($a === $b) {
                    return 0;
                } else {
                    if ($a > $b) {
                        return $this->sortAsc ? 1 : -1;
                    } else {
                        if ($a > $b) {
                            return $this->sortAsc ? -1 : 1;
                        }
                    }
                }
            } else {
                if (is_object($a)) {
                    // throw if $a uses Iterator
                    if ($a instanceof \Iterator) {
                        throw new \Exception("Invalid sort property, cannot sort by Iterator", 1304870321);
                    }
                    if ($a instanceof \DateTime) {
                        $a = $a->getTimestamp();
                    }
                    if ($b instanceof \DateTime) {
                        $b = $b->getTimestamp();
                    }
                    if ($a instanceof \TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject) {
                        if (!$this->memberSortBy) {
                            throw new \Exception("Asked to sort by DomainObject but memberSortBy parameter was not specified", 1304871195);
                        }
                        $getter = "get" . ucfirst($this->memberSortBy);
                        $a = $a->$getter();
                        $b = $b->$getter();
                        return $this->sortCompare($a, $b);
                    }
                } else {
                    if (is_array($a)) {
                        throw new \Exception("Invalid sort property, cannot sort by array", 1304870358);
                    }
                }
            }
        }

        return 0;
    }

    /**
     * Test functions
     * @return boolean
     */
    public function getHasFullDescription()
    {
        return $this->hasSetValue('description');
    }

    /**
     * @return bool
     */
    public function getHasFullApplyInfo()
    {
        return $this->hasSetValue('applyInfo');
    }

    /**
     * @return bool
     */
    public function getHasFullExtraInfo()
    {
        return $this->hasSetValue('extraInfo');
    }

    /**
     * @return bool
     */
    public function getHasFullContactInfo()
    {
        return $this->hasSetValue('contactInfo') || count($this->getContactAddresses());
    }

    /**
     * @return bool
     */
    public function getHasFullRequiredForApplication()
    {
        return $this->hasSetValue('requiredForApplication');
    }

    /**
     * @return bool
     */
    public function getHasFullLegalBasis()
    {
        return $this->hasSetValue('legalBasis');
    }

    /**
     * @return bool
     */
    public function getHasFullTerms()
    {
        return $this->hasSetValue('terms');
    }

    /**
     * @return bool
     */
    public function getHasFullResult()
    {
        return $this->hasSetValue('result');
    }

    /**
     * @return bool
     */
    public function getHasFullAppeal()
    {
        return $this->hasSetValue('appeal');
    }

    /**
     * Gets maintained by
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getMaintainedBy()
    {
        return $this->maintainedBy;
    }

    /**
     * Sets the maintained by
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $maintainedBy
     * @return self
     */
    public function setMaintainedBy($maintainedBy)
    {
        $this->maintainedBy = $maintainedBy;
        return $this;
    }

    /**
     * Returns property hidden
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets property hidden
     * @param boolean $hidden the property
     * @return self
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * Returns property imported
     * @return boolean
     */
    public function getImported()
    {
        return $this->imported;
    }

    /**
     * Sets property imported
     * @param boolean $imported the property
     * @return self
     */
    public function setImported($imported)
    {
        $this->imported = $imported;
        return $this;
    }

    /**
     * Returns property sessionNumber
     * @return integer the session number
     */
    public function getSessionNumber()
    {
        return $this->sessionNumber;
    }

    /**
     * Sets property sessionNumber
     * @param string $sessionNumber the property
     * @return self
     */
    public function setSessionNumber($sessionNumber)
    {
        $this->sessionNumber = $sessionNumber;
        return $this;
    }

    /**
     * @param $tstamp
     * @return self
     */
    public function setCrdate($tstamp)
    {
        $this->crdate = $tstamp;
        return $this;
    }

    /**
     * @param integer $tstamp
     * @return self
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * @return \DateTime
     */
    public function getTstampAsDateTime()
    {
        return $this->getUnixTimestampAsDateTime($this->tstamp);
    }

    /**
     * Sets owms core modified
     * @param \DateTime $date
     * @return self
     */
    public function setOwmsCoreModified($date)
    {
        $this->owmsCoreModified = $date;
        return $this;
    }

    /**
     * Gets owms core modified
     * @return \DateTime
     */
    public function getOwmsCoreModified()
    {
        return $this->owmsCoreModified;
    }

    /**
     * Returns property showFields
     * @return string
     */
    public function getShowFields()
    {
        return $this->showFields;
    }

    /**
     * Sets property showFields
     * @param string $showFields the property
     * @return self
     */
    public function setShowFields($showFields)
    {
        $this->showFields = $showFields;
        return $this;
    }

    /**
     * Returns property type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets property type
     * @param string $type the property
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns property owmsCoreIdentifier
     * @return string
     */
    public function getOwmsCoreIdentifier()
    {
        return $this->owmsCoreIdentifier;
    }

    /**
     * Sets property owmsCoreIdentifier
     * @param string $owmsCoreIdentifier the property
     * @return self
     */
    public function setOwmsCoreIdentifier($owmsCoreIdentifier)
    {
        $this->owmsCoreIdentifier = $owmsCoreIdentifier;
        return $this;
    }

    /**
     * Returns property name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets property name
     * @param string $name the property
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns property owmsMantleAbstract
     * @return string
     */
    public function getOwmsMantleAbstract()
    {
        return $this->owmsMantleAbstract;
    }

    /**
     * Sets property owmsMantleAbstract
     * @param string $owmsMantleAbstract the property
     * @return self
     */
    public function setOwmsMantleAbstract($owmsMantleAbstract)
    {
        $this->owmsMantleAbstract = $owmsMantleAbstract;
        return $this;
    }

    /**
     * Returns property scmetaProductId
     * @return string
     */
    public function getScmetaProductId()
    {
        return $this->scmetaProductId;
    }

    /**
     * Sets property scmetaProductId
     * @param string $scmetaProductId the property
     * @return self
     */
    public function setScmetaProductId($scmetaProductId)
    {
        $this->scmetaProductId = $scmetaProductId;
        return $this;
    }

    /**
     * Returns property scmetaRequestOnline
     * @return integer
     */
    public function getScmetaRequestOnline()
    {
        return $this->scmetaRequestOnline;
    }

    /**
     * Returns if requesting online is available for this product.
     *
     * @return boolean
     */
    public function getRequestOnlineAvailable()
    {
        return in_array(
            $this->scmetaRequestOnline,
            array(
                Product::REQUEST_ONLINE_YES,
                Product::REQUEST_ONLINE_DIGID
            )
        );
    }

    /**
     * Returns if requesting online with DigiD is available for this product.
     *
     * @return boolean
     */
    public function getRequestOnlineWithDigidAvailable()
    {
        return $this->scmetaRequestOnlineSingleSignOn == Product::REQUEST_ONLINE_DIGID;
    }

    /**
     * Sets property scmetaRequestOnline
     * @param integer $scmetaRequestOnline the property
     * @return self
     */
    public function setScmetaRequestOnline($scmetaRequestOnline)
    {

        if (!in_array(
            $scmetaRequestOnline,
            array(
                self::REQUEST_ONLINE_NO,
                self::REQUEST_ONLINE_YES,
                self::REQUEST_ONLINE_DIGID
            )
        )
        ) {
            $scmetaRequestOnline = self::REQUEST_ONLINE_NO;
        }

        $this->scmetaRequestOnline = $scmetaRequestOnline;
        return $this;
    }

    /**
     * Returns property scmetaRequestOnlineUrl
     * @return string $scmetaRequestOnlineUrl
     */
    public function getScmetaRequestOnlineUrl()
    {
        return $this->scmetaRequestOnlineUrl;
    }

    /**
     * Sets property scmetaRequestOnlineUrl
     * @param string $scmetaRequestOnlineUrl the property
     * @return self
     */
    public function setScmetaRequestOnlineUrl($scmetaRequestOnlineUrl)
    {
        $this->scmetaRequestOnlineUrl = $scmetaRequestOnlineUrl;
        return $this;
    }

    /**
     * Returns property scmetaRequestOnlineSingleSignOn
     * @return integer
     */
    public function getScmetaRequestOnlineSingleSignOn()
    {
        return $this->scmetaRequestOnlineSingleSignOn;
    }

    /**
     * Returns if single sign on for requesting online is available for this product. Other than
     * self::getScmetaRequestOnlineSingleSignOn(), this can be used as boolean in templates, since
     * self::REQUEST_ONLINE_SINGLE_SIGN_ON_UNDEFINED (which is 2 and does not evaluate to FALSE upon type cast)
     * also returns FALSE.
     *
     * @return boolean
     */
    public function getRequestOnlineWithSingleSignOnAvailable()
    {
        return $this->scmetaRequestOnlineSingleSignOn == self::REQUEST_ONLINE_SINGLE_SIGN_ON_YES;
    }

    /**
     * Sets property scmetaRequestOnlineSingleSignOn
     * @param string $scmetaRequestOnlineSingleSignOn the property
     * @return self
     */
    public function setScmetaRequestOnlineSingleSignOn($scmetaRequestOnlineSingleSignOn)
    {

        if (!in_array(
            $scmetaRequestOnlineSingleSignOn,
            array(
                self::REQUEST_ONLINE_SINGLE_SIGN_ON_NO,
                self::REQUEST_ONLINE_SINGLE_SIGN_ON_YES,
                self::REQUEST_ONLINE_SINGLE_SIGN_ON_UNDEFINED
            )
        )
        ) {
            $scmetaRequestOnlineSingleSignOn = self::REQUEST_ONLINE_SINGLE_SIGN_ON_UNDEFINED;
        }

        $this->scmetaRequestOnlineSingleSignOn = $scmetaRequestOnlineSingleSignOn;
        return $this;
    }

    /**
     * Returns property scmetaContactPoint
     * @return string
     */
    public function getScmetaContactPoint()
    {
        return $this->scmetaContactPoint;
    }

    /**
     * Sets property scmetaContactPoint
     * @param string $scmetaContactPoint the property
     * @return self
     */
    public function setScmetaContactPoint($scmetaContactPoint)
    {
        $this->scmetaContactPoint = $scmetaContactPoint;
        return $this;
    }

    /**
     * Returns the selected UPL uniform product names
     * @return array
     */
    public function getScmetaUniformProductNames()
    {
        if (!$this->scmetaUniformProductNames) {
            return array();
        }

        return GeneralUtility::trimExplode(',', $this->scmetaUniformProductNames);
    }

    /**
     * Returns the selected UPL uniform product names represented by their names
     * @return array
     */
    public function getScmetaUniformProductNamesWithName()
    {
        /** @var UniformProductNameListReader $uniformProductNameListReader */
        $uniformProductNameListReader = $this->objectManager->get(
            UniformProductNameListReader::class
        );
        return $uniformProductNameListReader->getUniformProductNamesByResourceIdentifiers(
            $this->getScmetaUniformProductNames()
        );
    }

    /**
     * Sets property scmetaUniformProductNames
     * @param    array|string $scmetaUniformProductNames the property
     * @return self
     */
    public function setScmetaUniformProductNames($scmetaUniformProductNames)
    {
        if (is_array($scmetaUniformProductNames)) {
            $scmetaUniformProductNames = implode(',', $scmetaUniformProductNames);
        }
        $this->scmetaUniformProductNames = $scmetaUniformProductNames;
        return $this;
    }

    /**
     * Adds a UPL name
     * @param    string $scmetaUniformProductName the uniform product name's resourceIdentifier
     * @return    self                                for chaining
     */
    public function addScmetaUniformProductName($scmetaUniformProductName)
    {
        $scmetaUniformProductName = (string)$scmetaUniformProductName;
        $this->scmetaUniformProductNames = implode(
            ',',
            array_unique(
                array_merge($this->getScmetaUniformProductNames(), array($scmetaUniformProductName))
            )
        );
        if ($this->scmetaUniformProductNames[0] == ',') {
            $this->scmetaUniformProductNames = substr($this->scmetaUniformProductNames, 1);
        }
        return $this;
    }

    /**
     * Clears property tioThemes
     * @return    self    for chaining
     */
    public function removeAllScmetaUniformProductNames()
    {
        $this->scmetaUniformProductNames = '';
        return $this;
    }

    /**
     * Returns the selected related UPL uniform product names
     * @return array
     */
    public function getScmetaRelatedUniformProductNames()
    {
        if (!$this->scmetaRelatedUniformProductNames) {
            return array();
        }

        return GeneralUtility::trimExplode(',', $this->scmetaRelatedUniformProductNames);
    }

    /**
     * Returns the selected related UPL uniform product names represented by their name
     * @return array
     */
    public function getScmetaRelatedUniformProductNamesWithName()
    {
        /** @var UniformProductNameListReader $uniformProductNameListReader */
        $uniformProductNameListReader = $this->objectManager->get(
            UniformProductNameListReader::class
        );
        return $uniformProductNameListReader->getUniformProductNamesByResourceIdentifiers(
            $this->getScmetaRelatedUniformProductNames()
        );
    }

    /**
     * Sets property scmetaRelatedUniformProductNames
     * @param    array|string $scmetaRelatedUniformProductNames the property
     * @return self
     */
    public function setScmetaRelatedUniformProductNames($scmetaRelatedUniformProductNames)
    {
        if (is_array($scmetaRelatedUniformProductNames)) {
            $scmetaRelatedUniformProductNames = implode(',', $scmetaRelatedUniformProductNames);
        }
        $this->scmetaRelatedUniformProductNames = $scmetaRelatedUniformProductNames;
        return $this;
    }

    /**
     * Adds a UPL name to the selected related uniform product names
     * @param    string $scmetaRelatedUniformProductName the uniform product name's resourceIdentifier
     * @return self
     */
    public function addScmetaRelatedUniformProductName($scmetaRelatedUniformProductName)
    {
        $scmetaRelatedUniformProductName = (string)$scmetaRelatedUniformProductName;
        $this->scmetaRelatedUniformProductNames = implode(
            ',',
            array_unique(
                array_merge($this->getScmetaRelatedUniformProductNames(), array($scmetaRelatedUniformProductName))
            )
        );
        if ($this->scmetaRelatedUniformProductNames[0] == ',') {
            $this->scmetaRelatedUniformProductNames = substr($this->scmetaRelatedUniformProductNames, 1);
        }
        return $this;
    }

    /**
     * Clears property scmetaRelatedUniformProductNames
     * @return self
     */
    public function removeAllScmetaRelatedUniformProductNames()
    {
        $this->scmetaRelatedUniformProductNames = '';
        return $this;
    }

    /**
     * Returns property tioThemes
     * @return    array    $tioThemes
     */
    public function getTioThemes()
    {
        if (!$this->tioThemes) {
            return array();
        }

        return GeneralUtility::trimExplode(',', $this->tioThemes);
    }

    /**
     * Returns the selected TiO themes as associative array structures ('id', 'name', 'resourceIdentifier', 'urlName', 'children')
     * @return    array    the theme names
     */
    public function getTioThemesWithName()
    {
        /** @var TioThemeClassificationReader $tioThemeClassificationReader */
        $tioThemeClassificationReader = $this->objectManager->get(
            TioThemeClassificationReader::class
        );
        return $tioThemeClassificationReader->getTioThemesByIdentifiers($this->getTioThemes());
    }

    /**
     * Sets property tioThemes
     * @param    array|string $tioThemes the property
     * @return self
     */
    public function setTioThemes($tioThemes)
    {
        if (is_array($tioThemes)) {
            $tioThemes = implode(',', $tioThemes);
        }
        $this->tioThemes = $tioThemes;
        return $this;
    }

    /**
     * Adds TiO theme (Should use Traits to share between Product and FrequentlyAskedQuestion, once we run on PHP 5.4.)
     * @param    string $tioTheme the theme identifier
     * @return    self                for chaining
     */
    public function addTioTheme($tioTheme)
    {
        $tioTheme = (string)$tioTheme;
        $this->tioThemes = implode(
            ',',
            array_unique(
                array_merge($this->getTioThemes(), array($tioTheme))
            )
        );
        if ($this->tioThemes[0] == ',') {
            $this->tioThemes = substr($this->tioThemes, 1);
        }
        return $this;
    }

    /**
     * Clears property tioThemes
     * @return    self    for chaining
     */
    public function removeAllTioThemes()
    {
        $this->tioThemes = '';
        return $this;
    }

    /**
     * Returns property changes
     * @return string
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * Sets property changes
     * @param string $changes the property
     * @return self
     */
    public function setChanges($changes)
    {
        $this->changes = $changes;
        return $this;
    }

    /**
     * Returns property costs
     * @return string
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * Sets property costs
     * @param string $costs the property
     * @return self
     */
    public function setCosts($costs)
    {
        $this->costs = $costs;
        return $this;
    }

    /**
     * Returns property deskMemo
     * @return string
     */
    public function getDeskMemo()
    {
        return $this->deskMemo;
    }

    /**
     * Sets property deskMemo
     * @param string $deskMemo the property
     * @return self
     */
    public function setDeskMemo($deskMemo)
    {
        $this->deskMemo = $deskMemo;
        return $this;
    }

    /**
     * Returns property turnaround
     * @return string
     */
    public function getTurnaround()
    {
        return $this->turnaround;
    }

    /**
     * Sets property turnaround
     * @param string $turnaround the property
     * @return self
     */
    public function setTurnaround($turnaround)
    {
        $this->turnaround = $turnaround;
        return $this;
    }

    /**
     * Returns property usePreDescription
     * @return boolean
     */
    public function getUsePreDescription()
    {
        return $this->usePreDescription;
    }

    /**
     * Sets property usePreDescription
     * @param boolean $usePreDescription the property
     * @return self
     */
    public function setUsePreDescription($usePreDescription)
    {
        $this->usePreDescription = $usePreDescription;
        return $this;
    }

    /**
     * Returns property preDescription
     * @return string
     */
    public function getPreDescription()
    {
        return $this->preDescription;
    }

    /**
     * Sets property preDescription
     * @param string $preDescription the property
     * @return self
     */
    public function setPreDescription($preDescription)
    {
        $this->preDescription = $preDescription;
        return $this;
    }

    /**
     * Returns property useDescription
     * @return boolean
     */
    public function getUseDescription()
    {
        return $this->useDescription;
    }

    /**
     * Sets property useDescription
     * @param boolean $useDescription the property
     * @return self
     */
    public function setUseDescription($useDescription)
    {
        $this->useDescription = $useDescription;
        return $this;
    }

    /**
     * Returns property description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets property description
     * @param string $description the property
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Returns property usePostDescription
     * @return boolean
     */
    public function getUsePostDescription()
    {
        return $this->usePostDescription;
    }

    /**
     * Sets property usePostDescription
     * @param boolean $usePostDescription the property
     * @return self
     */
    public function setUsePostDescription($usePostDescription)
    {
        $this->usePostDescription = $usePostDescription;
        return $this;
    }

    /**
     * Returns property postDescription
     * @return boolean
     */
    public function getPostDescription()
    {
        return $this->postDescription;
    }

    /**
     * Sets property postDescription
     * @param string $postDescription the property
     * @return self
     */
    public function setPostDescription($postDescription)
    {
        $this->postDescription = $postDescription;
        return $this;
    }

    /**
     * Returns property usePreApplyInfo
     * @return boolean
     */
    public function getUsePreApplyInfo()
    {
        return $this->usePreApplyInfo;
    }

    /**
     * Sets property usePreApplyInfo
     * @param boolean $usePreApplyInfo the property
     * @return self
     */
    public function setUsePreApplyInfo($usePreApplyInfo)
    {
        $this->usePreApplyInfo = $usePreApplyInfo;
        return $this;
    }

    /**
     * Returns property preApplyInfo
     * @return string
     */
    public function getPreApplyInfo()
    {
        return $this->preApplyInfo;
    }

    /**
     * Sets property preApplyInfo
     * @param string $preApplyInfo the property
     * @return self
     */
    public function setPreApplyInfo($preApplyInfo)
    {
        $this->preApplyInfo = $preApplyInfo;
        return $this;
    }

    /**
     * Returns property useApplyInfo
     * @return boolean $useApplyInfo
     */
    public function getUseApplyInfo()
    {
        return $this->useApplyInfo;
    }

    /**
     * Sets property useApplyInfo
     * @param boolean $useApplyInfo the property
     * @return self
     */
    public function setUseApplyInfo($useApplyInfo)
    {
        $this->useApplyInfo = $useApplyInfo;
        return $this;
    }

    /**
     * Returns property applyInfo
     * @return string
     */
    public function getApplyInfo()
    {
        return $this->applyInfo;
    }

    /**
     * Sets property applyInfo
     * @param string $applyInfo the property
     * @return self
     */
    public function setApplyInfo($applyInfo)
    {
        $this->applyInfo = $applyInfo;
        return $this;
    }

    /**
     * Returns property usePostApplyInfo
     * @return boolean
     */
    public function getUsePostApplyInfo()
    {
        return $this->usePostApplyInfo;
    }

    /**
     * Sets property usePostApplyInfo
     * @param boolean $usePostApplyInfo the property
     * @return self
     */
    public function setUsePostApplyInfo($usePostApplyInfo)
    {
        $this->usePostApplyInfo = $usePostApplyInfo;
        return $this;
    }

    /**
     * Returns property postApplyInfo
     * @return string
     */
    public function getPostApplyInfo()
    {
        return $this->postApplyInfo;
    }

    /**
     * Sets property postApplyInfo
     * @param string $postApplyInfo the property
     * @return self
     */
    public function setPostApplyInfo($postApplyInfo)
    {
        $this->postApplyInfo = $postApplyInfo;
        return $this;
    }

    /**
     * Returns property usePreExtraInfo
     * @return boolean
     */
    public function getUsePreExtraInfo()
    {
        return $this->usePreExtraInfo;
    }

    /**
     * Sets property usePreExtraInfo
     * @param boolean $usePreExtraInfo the property
     * @return self
     */
    public function setUsePreExtraInfo($usePreExtraInfo)
    {
        $this->usePreExtraInfo = $usePreExtraInfo;
        return $this;
    }

    /**
     * Returns property preExtraInfo
     * @return string
     */
    public function getPreExtraInfo()
    {
        return $this->preExtraInfo;
    }

    /**
     * Sets property preExtraInfo
     * @param boolean $preExtraInfo the property
     * @return self
     */
    public function setPreExtraInfo($preExtraInfo)
    {
        $this->preExtraInfo = $preExtraInfo;
        return $this;
    }

    /**
     * Returns property useExtraInfo
     * @return boolean
     */
    public function getUseExtraInfo()
    {
        return $this->useExtraInfo;
    }

    /**
     * Sets property useExtraInfo
     * @param boolean $useExtraInfo the property
     * @return self
     */
    public function setUseExtraInfo($useExtraInfo)
    {
        $this->useExtraInfo = $useExtraInfo;
        return $this;
    }

    /**
     * Returns property extraInfo
     * @return string
     */
    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * Sets property extraInfo
     * @param string $extraInfo the property
     * @return self
     */
    public function setExtraInfo($extraInfo)
    {
        $this->extraInfo = $extraInfo;
        return $this;
    }

    /**
     * Returns property usePostExtraInfo
     * @return boolean
     */
    public function getUsePostExtraInfo()
    {
        return $this->usePostExtraInfo;
    }

    /**
     * Sets property usePostExtraInfo
     * @param boolean $usePostExtraInfo the property
     * @return self
     */
    public function setUsePostExtraInfo($usePostExtraInfo)
    {
        $this->usePostExtraInfo = $usePostExtraInfo;
        return $this;
    }

    /**
     * Returns property postExtraInfo
     * @return string
     */
    public function getPostExtraInfo()
    {
        return $this->postExtraInfo;
    }

    /**
     * Sets property postExtraInfo
     * @param string $postExtraInfo the property
     * @return self
     */
    public function setPostExtraInfo($postExtraInfo)
    {
        $this->postExtraInfo = $postExtraInfo;
        return $this;
    }

    /**
     * Returns property usePreContactInfo
     * @return boolean
     */
    public function getUsePreContactInfo()
    {
        return $this->usePreContactInfo;
    }

    /**
     * Sets property usePreContactInfo
     * @param boolean $usePreContactInfo the property
     * @return self
     */
    public function setUsePreContactInfo($usePreContactInfo)
    {
        $this->usePreContactInfo = $usePreContactInfo;
        return $this;
    }

    /**
     * Returns property preContactInfo
     * @return string
     */
    public function getPreContactInfo()
    {
        return $this->preContactInfo;
    }

    /**
     * Sets property preContactInfo
     * @param string $preContactInfo the property
     * @return self
     */
    public function setPreContactInfo($preContactInfo)
    {
        $this->preContactInfo = $preContactInfo;
        return $this;
    }

    /**
     * Returns property useContactInfo
     * @return boolean
     */
    public function getUseContactInfo()
    {
        return $this->useContactInfo;
    }

    /**
     * Sets property useContactInfo
     * @param boolean $useContactInfo the property
     * @return self
     */
    public function setUseContactInfo($useContactInfo)
    {
        $this->useContactInfo = $useContactInfo;
        return $this;
    }

    /**
     * Returns property contactInfo
     * @return string
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * Sets property contactInfo
     * @param string $contactInfo the property
     * @return self
     */
    public function setContactInfo($contactInfo)
    {
        $this->contactInfo = $contactInfo;
        return $this;
    }

    /**
     * Returns property usePostContactInfo
     * @return boolean
     */
    public function getUsePostContactInfo()
    {
        return $this->usePostContactInfo;
    }

    /**
     * Sets property usePostContactInfo
     * @param boolean $usePostContactInfo the property
     * @return self
     */
    public function setUsePostContactInfo($usePostContactInfo)
    {
        $this->usePostContactInfo = $usePostContactInfo;
        return $this;
    }

    /**
     * Returns property postContactInfo
     * @return string
     */
    public function getPostContactInfo()
    {
        return $this->postContactInfo;
    }

    /**
     * Sets property postContactInfo
     * @param string $postContactInfo the property
     * @return self
     */
    public function setPostContactInfo($postContactInfo)
    {
        $this->postContactInfo = $postContactInfo;
        return $this;
    }

    /**
     * Returns property usePreRequiredForApplication
     * @return boolean
     */
    public function getUsePreRequiredForApplication()
    {
        return $this->usePreRequiredForApplication;
    }

    /**
     * Sets property usePreRequiredForApplication
     * @param boolean $usePreRequiredForApplication the property
     * @return self
     */
    public function setUsePreRequiredForApplication($usePreRequiredForApplication)
    {
        $this->usePreRequiredForApplication = $usePreRequiredForApplication;
        return $this;
    }

    /**
     * Returns property preRequiredForApplication
     * @return string
     */
    public function getPreRequiredForApplication()
    {
        return $this->preRequiredForApplication;
    }

    /**
     * Sets property preRequiredForApplication
     * @param string $preRequiredForApplication the property
     * @return self
     */
    public function setPreRequiredForApplication($preRequiredForApplication)
    {
        $this->preRequiredForApplication = $preRequiredForApplication;
        return $this;
    }

    /**
     * Returns property useRequiredForApplication
     * @return boolean
     */
    public function getUseRequiredForApplication()
    {
        return $this->useRequiredForApplication;
    }

    /**
     * Sets property useRequiredForApplication
     * @param boolean $useRequiredForApplication the property
     * @return self
     */
    public function setUseRequiredForApplication($useRequiredForApplication)
    {
        $this->useRequiredForApplication = $useRequiredForApplication;
        return $this;
    }

    /**
     * Returns property requiredForApplication
     * @return string
     */
    public function getRequiredForApplication()
    {
        return $this->requiredForApplication;
    }

    /**
     * Sets property requiredForApplication
     * @param string $requiredForApplication the property
     * @return self
     */
    public function setRequiredForApplication($requiredForApplication)
    {
        $this->requiredForApplication = $requiredForApplication;
        return $this;
    }

    /**
     * Returns property usePostRequiredForApplication
     * @return boolean
     */
    public function getUsePostRequiredForApplication()
    {
        return $this->usePostRequiredForApplication;
    }

    /**
     * Sets property usePostRequiredForApplication
     * @param boolean $usePostRequiredForApplication the property
     * @return self
     */
    public function setUsePostRequiredForApplication($usePostRequiredForApplication)
    {
        $this->usePostRequiredForApplication = $usePostRequiredForApplication;
        return $this;
    }

    /**
     * Returns property postRequiredForApplication
     * @return string
     */
    public function getPostRequiredForApplication()
    {
        return $this->postRequiredForApplication;
    }

    /**
     * Sets property postRequiredForApplication
     * @param string $postRequiredForApplication the property
     * @return self
     */
    public function setPostRequiredForApplication($postRequiredForApplication)
    {
        $this->postRequiredForApplication = $postRequiredForApplication;
        return $this;
    }

    /**
     * Returns property usePreLegalBasis
     * @return boolean
     */
    public function getUsePreLegalBasis()
    {
        return $this->usePreLegalBasis;
    }

    /**
     * Sets property usePreLegalBasis
     * @param boolean $usePreLegalBasis the property
     * @return self
     */
    public function setUsePreLegalBasis($usePreLegalBasis)
    {
        $this->usePreLegalBasis = $usePreLegalBasis;
        return $this;
    }

    /**
     * Returns property preLegalBasis
     * @return string
     */
    public function getPreLegalBasis()
    {
        return $this->preLegalBasis;
    }

    /**
     * Sets property preLegalBasis
     * @param string $preLegalBasis the property
     * @return self
     */
    public function setPreLegalBasis($preLegalBasis)
    {
        $this->preLegalBasis = $preLegalBasis;
        return $this;
    }

    /**
     * Returns property useLegalBasis
     * @return boolean
     */
    public function getUseLegalBasis()
    {
        return $this->useLegalBasis;
    }

    /**
     * Sets property useLegalBasis
     * @param boolean $useLegalBasis the property
     * @return self
     */
    public function setUseLegalBasis($useLegalBasis)
    {
        $this->useLegalBasis = $useLegalBasis;
        return $this;
    }

    /**
     * Returns property legalBasis
     * @return string
     */
    public function getLegalBasis()
    {
        return $this->legalBasis;
    }

    /**
     * Sets property legalBasis
     * @param string $legalBasis the property
     * @return self
     */
    public function setLegalBasis($legalBasis)
    {
        $this->legalBasis = $legalBasis;
        return $this;
    }

    /**
     * Returns property usePostLegalBasis
     * @return boolean
     */
    public function getUsePostLegalBasis()
    {
        return $this->usePostLegalBasis;
    }

    /**
     * Sets property usePostLegalBasis
     * @param boolean $usePostLegalBasis the property
     * @return self
     */
    public function setUsePostLegalBasis($usePostLegalBasis)
    {
        $this->usePostLegalBasis = $usePostLegalBasis;
        return $this;
    }

    /**
     * Returns property postLegalBasis
     * @return string
     */
    public function getPostLegalBasis()
    {
        return $this->postLegalBasis;
    }

    /**
     * Sets property postLegalBasis
     * @param string $postLegalBasis the property
     * @return self
     */
    public function setPostLegalBasis($postLegalBasis)
    {
        $this->postLegalBasis = $postLegalBasis;
        return $this;
    }

    /**
     * Returns property usePreTerms
     * @return boolean
     */
    public function getUsePreTerms()
    {
        return $this->usePreTerms;
    }

    /**
     * Sets property usePreTerms
     * @param boolean $usePreTerms the property
     * @return self
     */
    public function setUsePreTerms($usePreTerms)
    {
        $this->usePreTerms = $usePreTerms;
        return $this;
    }

    /**
     * Returns property preTerms
     * @return string
     */
    public function getPreTerms()
    {
        return $this->preTerms;
    }

    /**
     * Sets property preTerms
     * @param string $preTerms the property
     * @return self
     */
    public function setPreTerms($preTerms)
    {
        $this->preTerms = $preTerms;
        return $this;
    }

    /**
     * Returns property useTerms
     * @return boolean
     */
    public function getUseTerms()
    {
        return $this->useTerms;
    }

    /**
     * Sets property useTerms
     * @param boolean $useTerms the property
     * @return self
     */
    public function setUseTerms($useTerms)
    {
        $this->useTerms = $useTerms;
        return $this;
    }

    /**
     * Returns property terms
     * @return string
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Sets property terms
     * @param string $terms the property
     * @return self
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     * Returns property usePostTerms
     * @return boolean
     */
    public function getUsePostTerms()
    {
        return $this->usePostTerms;
    }

    /**
     * Sets property usePostTerms
     * @param boolean $usePostTerms the property
     * @return self
     */
    public function setUsePostTerms($usePostTerms)
    {
        $this->usePostTerms = $usePostTerms;
        return $this;
    }

    /**
     * Returns property postTerms
     * @return string
     */
    public function getPostTerms()
    {
        return $this->postTerms;
    }

    /**
     * Sets property postTerms
     * @param string $postTerms the property
     * @return self
     */
    public function setPostTerms($postTerms)
    {
        $this->postTerms = $postTerms;
        return $this;
    }

    /**
     * Returns property usePreResult
     * @return boolean
     */
    public function getUsePreResult()
    {
        return $this->usePreResult;
    }

    /**
     * Sets property usePreResult
     * @param boolean $usePreResult the property
     * @return self
     */
    public function setUsePreResult($usePreResult)
    {
        $this->usePreResult = $usePreResult;
        return $this;
    }

    /**
     * Returns property preResult
     * @return string
     */
    public function getPreResult()
    {
        return $this->preResult;
    }

    /**
     * Sets property preResult
     * @param string $preResult the property
     * @return self
     */
    public function setPreResult($preResult)
    {
        $this->preResult = $preResult;
        return $this;
    }

    /**
     * Returns property useResult
     * @return boolean
     */
    public function getUseResult()
    {
        return $this->useResult;
    }

    /**
     * Sets property useResult
     * @param boolean $useResult the property
     * @return self
     */
    public function setUseResult($useResult)
    {
        $this->useResult = $useResult;
        return $this;
    }

    /**
     * Returns property result
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets property result
     * @param string $result the property
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Returns property usePostResult
     * @return boolean
     */
    public function getUsePostResult()
    {
        return $this->usePostResult;
    }

    /**
     * Sets property usePostResult
     * @param boolean $usePostResult the property
     * @return self
     */
    public function setUsePostResult($usePostResult)
    {
        $this->usePostResult = $usePostResult;
        return $this;
    }

    /**
     * Returns property postResult
     * @return string
     */
    public function getPostResult()
    {
        return $this->postResult;
    }

    /**
     * Sets property postResult
     * @param string $postResult the property
     * @return self
     */
    public function setPostResult($postResult)
    {
        $this->postResult = $postResult;
        return $this;
    }

    /**
     * Returns property usePreAppeal
     * @return boolean
     */
    public function getUsePreAppeal()
    {
        return $this->usePreAppeal;
    }

    /**
     * Sets property usePreAppeal
     * @param boolean $usePreAppeal the property
     * @return self
     */
    public function setUsePreAppeal($usePreAppeal)
    {
        $this->usePreAppeal = $usePreAppeal;
        return $this;
    }

    /**
     * Returns property preAppeal
     * @return string
     */
    public function getPreAppeal()
    {
        return $this->preAppeal;
    }

    /**
     * Sets property preAppeal
     * @param boolean $preAppeal the property
     * @return self
     */
    public function setPreAppeal($preAppeal)
    {
        $this->preAppeal = $preAppeal;
        return $this;
    }

    /**
     * Returns property useAppeal
     * @return string
     */
    public function getUseAppeal()
    {
        return $this->useAppeal;
    }

    /**
     * Sets property useAppeal
     * @param boolean $useAppeal the property
     * @return self
     */
    public function setUseAppeal($useAppeal)
    {
        $this->useAppeal = $useAppeal;
        return $this;
    }

    /**
     * Returns property appeal
     * @return string
     */
    public function getAppeal()
    {
        return $this->appeal;
    }

    /**
     * Sets property appeal
     * @param string $appeal the property
     * @return self
     */
    public function setAppeal($appeal)
    {
        $this->appeal = $appeal;
        return $this;
    }

    /**
     * Returns property usePostAppeal
     * @return boolean
     */
    public function getUsePostAppeal()
    {
        return $this->usePostAppeal;
    }

    /**
     * Sets property usePostAppeal
     * @param boolean $usePostAppeal the property
     * @return self
     */
    public function setUsePostAppeal($usePostAppeal)
    {
        $this->usePostAppeal = $usePostAppeal;
        return $this;
    }

    /**
     * Returns property postAppeal
     * @return string
     */
    public function getPostAppeal()
    {
        return $this->postAppeal;
    }

    /**
     * Sets property postAppeal
     * @param boolean $postAppeal the property
     * @return self
     */
    public function setPostAppeal($postAppeal)
    {
        $this->postAppeal = $postAppeal;
        return $this;
    }

    /**
     * Sets property synonyms
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $synonyms the property
     * @return self
     */
    public function setSynonyms($synonyms)
    {
        $this->synonyms = $synonyms;
        return $this;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $linkGroups
     * @return self
     */
    public function setLinkGroups($linkGroups)
    {
        $this->linkGroups = $linkGroups;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getLinkGroups()
    {
        $this->sortLinkGroups();
        return $this->linkGroups;
    }

    /**
     * Sets property keywords
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $keywords the property
     * @return self
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Sets property frequentlyAskedQuestions
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $frequentlyAskedQuestions the property
     * @return self
     */
    public function setFrequentlyAskedQuestions($frequentlyAskedQuestions)
    {
        $this->frequentlyAskedQuestions = $frequentlyAskedQuestions;
        return $this;
    }

    /**
     * Returns property frequentlyAskedQuestionInfo
     * @return string
     */
    public function getFrequentlyAskedQuestionInfo()
    {
        return $this->frequentlyAskedQuestionInfo;
    }

    /**
     * Sets property frequentlyAskedQuestionInfo
     * @param string $frequentlyAskedQuestionInfo the property
     * @return self
     */
    public function setFrequentlyAskedQuestionInfo($frequentlyAskedQuestionInfo)
    {
        $this->frequentlyAskedQuestionInfo = $frequentlyAskedQuestionInfo;
        return $this;
    }

    /**
     * Sets property image
     * @param string $image the property
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Sets property attachments
     * @param string $attachments the property
     * @return self
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Has related product
     * @param Product $relatedProduct the property
     * @return self for chaining
     */
    public function hasRelatedProduct($relatedProduct)
    {
        $result = $this->relatedProducts->contains($relatedProduct);
        return $result;
    }

    /**
     * Sets property relatedProducts
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $relatedProducts the property
     * @return self
     */
    public function setRelatedProducts($relatedProducts)
    {
        $this->relatedProducts = $relatedProducts;
        return $this;
    }

    /**
     * Sets property authorities
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $authorities the property
     * @return self
     */
    public function setAuthorities($authorities)
    {
        $this->authorities = $authorities;
        return $this;
    }

    /**
     * Sets property responsibles
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $responsibles the property
     * @return self
     */
    public function setResponsibles($responsibles)
    {
        $this->responsibles = $responsibles;
        return $this;
    }

    /**
     * Returns property requestForm
     * @return string
     */
    public function getRequestForm()
    {
        return $this->requestForm;
    }

    /**
     * Sets property requestForm
     * @param string $requestForm the property
     * @return self
     */
    public function setRequestForm($requestForm)
    {
        $this->requestForm = $requestForm;
        return $this;
    }

    /**
     * Returns property directive
     * @return string
     */
    public function getDirective()
    {
        return $this->directive;
    }

    /**
     * Sets property directive
     * @param string $directive the property
     * @return self
     */
    public function setDirective($directive)
    {
        $this->directive = $directive;
        return $this;
    }

    /**
     * Returns property processDescription
     * @return string
     */
    public function getProcessDescription()
    {
        return $this->processDescription;
    }

    /**
     * Sets property processDescription
     * @param string $processDescription the property
     * @return self
     */
    public function setProcessDescription($processDescription)
    {
        $this->processDescription = $processDescription;
        return $this;
    }

    /**
     * Returns property relatedRegulatory
     * @return string
     */
    public function getRelatedRegulatory()
    {
        return $this->relatedRegulatory;
    }

    /**
     * Sets property relatedRegulatory
     * @param string $relatedRegulatory the property
     * @return self
     */
    public function setRelatedRegulatory($relatedRegulatory)
    {
        $this->relatedRegulatory = $relatedRegulatory;
        return $this;
    }

    /**
     * Returns property source
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Sets property source
     * @param string $source the property
     * @return self
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Returns property shortDescription
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Sets property shortDescription
     * @param string $shortDescription the property
     * @return self
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    /**
     * Sets property audience
     * @param string $audience the property
     * @return self
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;
        return $this;
    }

    /**
     * Returns property referenceLaws
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceLaws()
    {
        return clone $this->referenceLaws;
    }

    /**
     * Returns if the referenceLaw exists in the product
     * @param ReferenceLink $referenceLaw the law reference to be checked for
     * @return boolean
     */
    public function containsReferenceLaw(ReferenceLink $referenceLaw)
    {
        return $this->referenceLaws->contains($referenceLaw);
    }

    /**
     * Sets property referenceLaw
     * @param ReferenceLink $referenceLaw the property
     * @return self
     */
    public function addReferenceLaw(ReferenceLink $referenceLaw)
    {
        $this->referenceLaws->attach($referenceLaw);
        return $this;
    }

    /**
     * Sets property referenceLaw
     * @param ReferenceLink $referenceLaw the property
     * @return self
     */
    public function removeReferenceLaw(ReferenceLink $referenceLaw)
    {
        $this->referenceLaws->detach($referenceLaw);
        return $this;
    }

    /**
     * Sets property referenceLaws
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $referenceLaws the property
     * @return self
     */
    public function setReferenceLaws($referenceLaws)
    {
        $this->referenceLaws = $referenceLaws;
        return $this;
    }


    /**
     * Removes all references to other info/
     * @return self
     */
    public function removeAllReferenceLaws()
    {
        $this->referenceLaws = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property referenceLocalLaws
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceLocalLaws()
    {
        return clone $this->referenceLocalLaws;
    }

    /**
     * Returns if the referenceLaw exists in the product
     * @param ReferenceLink $referenceLaw the law reference to be checked for
     * @return boolean
     */
    public function containsReferenceLocalLaw(ReferenceLink $referenceLaw)
    {
        return $this->referenceLocalLaws->contains($referenceLaw);
    }

    /**
     * Sets property referenceLaw
     * @param ReferenceLink $referenceLaw the property
     * @return self
     */
    public function addReferenceLocalLaw(ReferenceLink $referenceLaw)
    {
        $this->referenceLocalLaws->attach($referenceLaw);
        return $this;
    }

    /**
     * Sets property referenceLaw
     * @param ReferenceLink $referenceLaw the property
     * @return self
     */
    public function removeReferenceLocalLaw(ReferenceLink $referenceLaw)
    {
        $this->referenceLocalLaws->detach($referenceLaw);
        return $this;
    }

    /**
     * Sets property referenceLocalLaws
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $referenceLocalLaws the property
     * @return self
     */
    public function setReferenceLocalLaws($referenceLocalLaws)
    {
        $this->referenceLocalLaws = $referenceLocalLaws;
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return self
     */
    public function removeAllReferenceLocalLaws()
    {
        $this->referenceLocalLaws = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property referenceForms
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceForms()
    {
        return clone $this->referenceForms;
    }

    /**
     * Returns if the referenceLaw exists in the product
     * @param ReferenceLink $referenceForm the law reference to be checked for
     * @return boolean
     */
    public function containsReferenceForm(ReferenceLink $referenceForm)
    {
        return $this->referenceForms->contains($referenceForm);
    }

    /**
     * Sets property referenceForm
     * @param ReferenceLink $referenceForm the property
     * @return self
     */
    public function addReferenceForm(ReferenceLink $referenceForm)
    {
        $this->referenceForms->attach($referenceForm);
        return $this;
    }

    /**
     * Sets property referenceLaw
     * @param ReferenceLink $referenceForm the property
     * @return self
     */
    public function removeReferenceForm(ReferenceLink $referenceForm)
    {
        $this->referenceForms->detach($referenceForm);
        return $this;
    }

    /**
     * Sets property referenceForms
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $referenceForms the property
     * @return self
     */
    public function setReferenceForms($referenceForms)
    {
        $this->referenceForms = $referenceForms;
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return self
     */
    public function removeAllReferenceForms()
    {
        $this->referenceForms = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property referenceInternal
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceInternal()
    {
        return clone $this->referenceInternal;
    }

    /**
     * Returns if the referenceLaw exists in the product
     * @param ReferenceLink $referenceInternal the law reference to be checked for
     * @return boolean
     */
    public function containsReferenceInternal(ReferenceLink $referenceInternal)
    {
        return $this->referenceInternal->contains($referenceInternal);
    }

    /**
     * Sets property referenceForm
     * @param ReferenceLink $referenceInternal the property
     * @return self
     */
    public function addReferenceInternal(ReferenceLink $referenceInternal)
    {
        $this->referenceInternal->attach($referenceInternal);
        return $this;
    }

    /**
     * Sets property referenceInternal
     * @param ReferenceLink $referenceInternal the property
     * @return self
     */
    public function removeReferenceInternal(ReferenceLink $referenceInternal)
    {
        $this->referenceInternal->detach($referenceInternal);
        return $this;
    }

    /**
     * Sets property referenceForms
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $referenceInternal the property
     * @return self
     */
    public function setReferenceInternal($referenceInternal)
    {
        $this->referenceInternal = $referenceInternal;
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return self
     */
    public function removeAllReferenceInternal()
    {
        $this->referenceInternal = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property referenceForms
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceExternal()
    {
        return clone $this->referenceExternal;
    }

    /**
     * Returns if the referenceLaw exists in the product
     * @param ReferenceLink $referenceExternal the law reference to be checked for
     * @return boolean
     */
    public function containsReferenceExternal(ReferenceLink $referenceExternal)
    {
        return $this->referenceExternal->contains($referenceExternal);
    }

    /**
     * Sets property referenceExternal
     * @param ReferenceLink $referenceExternal the property
     * @return self
     */
    public function addReferenceExternal(ReferenceLink $referenceExternal)
    {
        $this->referenceExternal->attach($referenceExternal);
        return $this;
    }

    /**
     * Sets property referenceExternal
     * @param ReferenceLink $referenceExternal the property
     * @return self
     */
    public function removeReferenceExternal(ReferenceLink $referenceExternal)
    {
        $this->referenceExternal->detach($referenceExternal);
        return $this;
    }

    /**
     * Sets property referenceExternal
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $referenceExternal the property
     * @return self
     */
    public function setReferenceExternal($referenceExternal)
    {
        $this->referenceExternal = $referenceExternal;
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return self
     */
    public function removeAllReferenceExternal()
    {
        $this->referenceExternal = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property language
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets property language
     * @param string $language the property
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Action getter functions
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function getFullDescription()
    {
        return $this->getSetValue('description');
    }

    /**
     * @return string
     */
    public function getFullApplyInfo()
    {
        return $this->getSetValue('applyInfo');
    }

    /**
     * @return string
     */
    public function getFullExtraInfo()
    {
        return $this->getSetValue('extraInfo');
    }

    /**
     * @return string
     */
    public function getFullContactInfo()
    {
        return $this->getSetValue('contactInfo');
    }

    /**
     * @return string
     */
    public function getFullRequiredForApplication()
    {
        return $this->getSetValue('requiredForApplication');
    }

    /**
     * @return string
     */
    public function getFullLegalBasis()
    {
        return $this->getSetValue('legalBasis');
    }

    /**
     * @return string
     */
    public function getFullTerms()
    {
        return $this->getSetValue('terms');
    }

    /**
     * @return string
     */
    public function getFullResult()
    {
        return $this->getSetValue('result');
    }

    /**
     * @return string
     */
    public function getFullAppeal()
    {
        return $this->getSetValue('appeal');
    }

    /**
     * Tests if user exists as responsible
     * @param \Netcreators\NcgovPdc\Domain\Model\Responsible $user
     * @return bool
     */
    public function hasUser($user)
    {
        $result = false;
        if ($this->responsibles->count() > 0) {
            foreach ($this->responsibles as $responsible) {
                if ($user->getUid() == $responsible->getUid()) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Checks if the product either is assigned to the current user or is in the same usergroup as the current user.
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user
     * @return boolean true or false
     */
    public function getIsUserAllowedToView($user)
    {
        $result = false;
        if ($this->responsibles !== null && $this->responsibles->count() > 0) {
            if ($this->responsibles->contains($user)) {
                $result = true;
            }
        }
        $usergroup = $user->getUsergroup();
        if (!$result && $this->authorities !== null && $this->authorities->count() > 0
            && $usergroup !== null && $usergroup->count() > 0
        ) {
            foreach ($this->authorities as $authority) {
                foreach ($usergroup as $group) {
                    if ($authority->getUid() == $group->getUid()) {
                        $result = true;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Returns the uri for the link
     * @return string
     */
    public function getSourceLinkUri()
    {
        return $this->getLink($this->source);
    }

    /**
     * @return string
     */
    public function getSourceLinkName()
    {
        return $this->getSourceLinkUri();
    }

    /**
     * @return string
     */
    public function getSourceLinkTitle()
    {
        return $this->getSourceLinkUri();
    }

    /**
     * @return string
     */
    public function getRequestFormLinkUri()
    {
        return $this->getLink($this->requestForm);
    }

    /**
     * @return string
     */
    public function getRequestFormLinkName()
    {
        return $this->getLink($this->requestForm);
    }

    /**
     * @return string
     */
    public function getRequestFormLinkTitle()
    {
        return $this->getLink($this->requestForm);
    }

    /**
     * @return string
     */
    public function getDirectiveLinkUri()
    {
        return $this->getLink($this->directive);
    }

    /**
     * @return string
     */
    public function getDirectiveLinkName()
    {
        return $this->getLink($this->directive);
    }

    /**
     * @return string
     */
    public function getDirectiveLinkTitle()
    {
        return $this->getLink($this->directive);
    }

    /**
     * @return string
     */
    public function getProcessDescriptionLinkUri()
    {
        return $this->getLink($this->processDescription);
    }

    /**
     * @return string
     */
    public function getProcessDescriptionLinkName()
    {
        return $this->getLink($this->processDescription);
    }

    /**
     * @return string
     */
    public function getProcessDescriptionLinkTitle()
    {
        return $this->getLink($this->processDescription);
    }

    /**
     * @return string
     */
    public function getRelatedRegulatoryLinkUri()
    {
        return $this->getLink($this->relatedRegulatory);
    }

    /**
     * @return string
     */
    public function getRelatedRegulatoryLinkName()
    {
        return $this->getLink($this->relatedRegulatory);
    }

    /**
     * @return string
     */
    public function getRelatedRegulatoryLinkTitle()
    {
        return $this->getLink($this->relatedRegulatory);
    }

    /**
     * @return string
     */
    public function getCostsLinkTitle()
    {
        return $this->getLink($this->costs);
    }

    /**
     * @return string
     */
    public function getCostsLinkUri()
    {
        return $this->getLink($this->costs);
    }

    /**
     * @return string
     */
    public function getCostsLinkName()
    {
        return $this->getLink($this->costs);
    }

    /**
     * @return bool
     */
    public function getHasFrequentlyAskedQuestionInfo()
    {
        $tagLess = trim(strip_tags($this->frequentlyAskedQuestionInfo));
        return !empty($tagLess);
    }

    /**
     * Returns the name of the image attached to the product.
     * @return string
     */
    public function getImage()
    {
        $result = false;
        if (!empty($this->image)) {
            $result = $this->tableCreationArrayManager->get(
                    'tx_ncgovpdc_domain_model_product.columns.image.config.uploadfolder'
                )
                . '/' . $this->image;
        }
        return $result;
    }

    /**
     * Returns the attachments if available, false otherwise.
     * @return mixed array or false
     */
    public function getAttachments()
    {
        $result = false;
        if (!empty($this->attachments)) {
            $attachments = GeneralUtility::trimExplode(',', $this->attachments);
            if (is_array($attachments) && count($attachments) > 0) {
                $files = array();
                foreach ($attachments as $attachment) {
                    $files[] = array(
                        'url' => $this->tableCreationArrayManager->get(
                                'tx_ncgovpdc_domain_model_product.columns.attachments.config.uploadfolder'
                            )
                            . '/' . $attachment,
                        'name' => basename($attachment),
                    );
                }
                $result = $files;
            }
        }
        return $result;
    }

    /**
     * Returns the connected audiences
     * @return array the audiences
     */
    public function getAudience()
    {
        return \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(',', $this->audience);
    }

    /**
     * Returns the attached contact addresses
     * @return array
     */
    public function getContactAddresses()
    {
        return clone $this->contactAddresses;
    }

    /**
     * Tests if contact address already is connected to the product
     * @param TtAddress $testAddress the address to test
     * @return boolean true if the address is connected to the product false otherwise
     */
    public function hasContactAddress(TtAddress $testAddress)
    {
        return $this->contactAddresses->contains($testAddress);
    }

    /**
     * Removes given contact address
     * @param TtAddress $address the address to remove
     * @return self
     */
    public function removeContactAddress(TtAddress $address)
    {
        if ($this->contactAddresses->contains($address)) {
            $this->contactAddresses->detach($address);
        }
        return $this;
    }

    /**
     * Adds contact address to the product
     * @param TtAddress $address the address
     * @return self
     */
    public function addContactAddress(TtAddress $address)
    {
        if (!$this->hasContactAddress($address)) {
            $this->contactAddresses->attach($address);
        }
        return $this;
    }

    /*
	 * Adds multiple addresses to the product
	 * @param array $addresses the addresses to be added
	 * @return self
	 */
    public function addContactAddresses($addresses)
    {
        if (count($addresses) > 0) {
            foreach ($addresses as $address) {
                $this->addContactAddress($address);
            }
        }
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return self
     */
    public function removeAllContactAddresses()
    {
        $this->contactAddresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Gets related 'cost' content elements
     * @return array|null
     */
    public function getCostsContent()
    {
        /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $localContentObject */
        $localContentObject = GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer'
        );

        $costsContent = null;
        $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        /** @var TtContentRepository $contentRepository */
        $contentRepository = $objectManager->get(
            TtContentRepository::class
        );

        if (!is_array($this->costsContent) && strlen($this->costsContent) > 0) {
            $costsContentUids = GeneralUtility::trimExplode(',', $this->costsContent);
            if (is_array($costsContentUids)) {
                $costsContent = array();
                foreach ($costsContentUids as $uid) {
                    $pid = '';
                    $record = $contentRepository->findByUidNotDeletedIgnoreHidden($uid);
                    if ($record) {
                        $pid = (string)$record->getPid();
                    }
                    $config = array(
                        'table' => 'tt_content',
                        'select.' => array(
                            'pidInList' => $pid, //=> $extbaseSettings['persistence']['storagePid'],
                            'where' => 'uid=' . $uid,
                        )
                    );
                    $result = $localContentObject->cObjGetSingle('CONTENT', $config);
                    if ($result) {
                        $costsContent[] = $result;
                    }
                }
            }
        }
        return $costsContent;
    }

    /**
     * @param $costsContent
     * @return self
     */
    public function setCostsContent($costsContent)
    {
        $this->costsContent = $costsContent;
        return $this;
    }

    /**
     * @param Keyword $testKeyword
     * @return bool
     */
    public function hasKeyword(Keyword $testKeyword)
    {
        return $this->keywords->contains($testKeyword);
    }

    /**
     * Adds keyword
     * @param Keyword $keyword the keyword to add
     * @return self
     */
    public function addKeyword(Keyword $keyword)
    {
        if (!$this->hasKeyword($keyword)) {
            $this->keywords->attach($keyword);
        }
        return $this;
    }

    /**
     * Adds keywords
     * @param array $keywords the keywords
     * @return self
     */
    public function addKeywords($keywords)
    {
        if (count($keywords) > 0) {
            foreach ($keywords as $keyword) {
                $this->addKeyword($keyword);
            }
        }
        return $this;
    }

    /**
     * Removes keyword
     * @param Keyword $keyword the keyword
     * @return self
     */
    public function removeKeyword(Keyword $keyword)
    {
        if ($this->hasKeyword($keyword)) {
            $this->keywords->detach($keyword);
        }
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getSynonyms()
    {
        return clone $this->synonyms;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getKeywords()
    {
        return clone $this->keywords;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getFrequentlyAskedQuestions()
    {
        return clone $this->frequentlyAskedQuestions;
    }

    /**
     * Returns only questions where the date is available.
     * @return array
     */
    public function getAvailableFrequentlyAskedQuestions()
    {
        $result = array();

        if (count($this->frequentlyAskedQuestions) > 0) {
            /** @var FrequentlyAskedQuestion $faq */
            foreach ($this->frequentlyAskedQuestions as $faq) {
                if ($faq->getOwmsMantleAvailableStart() < date_create() && $faq->getOwmsMantleAvailableEnd(
                    ) > date_create()
                ) {
                    $result[] = $faq;
                }
            }
        }

        return $result;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getResponsibles()
    {
        return clone $this->responsibles;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRelatedProducts()
    {
        return clone $this->relatedProducts;
    }

    /**
     * Adds a related product to this product.
     * @param Product $product
     * @return self
     */
    public function addRelatedProduct(Product $product)
    {
        $this->relatedProducts->attach($product);
        return $this;
    }

    /**
     * Removes a related product to this product.
     * @param Product $product
     * @return self
     */
    public function removeRelatedProduct(Product $product)
    {
        if ($this->relatedProducts->contains($product)) {
            $this->relatedProducts->detach($product);
        }
        return $this;
    }

    /**
     * Adds a frequently asked question
     *
     * @param FrequentlyAskedQuestion $object
     * @return self
     */
    public function addFrequentlyAskedQuestion(FrequentlyAskedQuestion $object)
    {
        $this->frequentlyAskedQuestions->attach($object);
        return $this;
    }

    /**
     * Removes a frequently asked question
     *
     * @param FrequentlyAskedQuestion $object frequently asked question to be removed
     * @return self
     */
    public function removeFrequentlyAskedQuestion(FrequentlyAskedQuestion $object)
    {
        $this->frequentlyAskedQuestions->detach($object);
        return $this;
    }

    /**
     * Checks if the object has a frequently asked question
     *
     * @param FrequentlyAskedQuestion $object frequently asked question to be tested
     * @return boolean
     */
    public function hasFrequentlyAskedQuestion(FrequentlyAskedQuestion $object)
    {
        return $this->frequentlyAskedQuestions->contains($object);
    }

    /**
     * Sets property tips
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tips the property
     * @return self
     */
    public function setTips($tips)
    {
        $this->tips = $tips;
        return $this;
    }

    /**
     * Removes tip.
     * @param Tip $tip
     * @return self
     */
    public function removeTip(Tip $tip)
    {
        $this->tips->detach($tip);
        return $this;
    }

    /**
     * Adds a tip to the current list of tips.
     * @param Tip $tip
     * @return self
     */
    public function addTip(Tip $tip)
    {
        $this->tips->attach($tip);
        return $this;
    }

    /**
     * Gets the tips
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getTips()
    {
        return clone $this->tips;
    }

    /**
     * Gets the number of not yet approved tips
     * @return int
     */
    public function getNotApprovedTipCount()
    {

        $openCount = 0;

        if (count($this->tips) > 0) {

            /** @var Tip $tip */
            foreach ($this->tips as $tip) {
                if ($tip->getState() !== Tip::STATE_APPROVED) {
                    $openCount++;
                }
            }
        }

        return $openCount;
    }

    /**
     * Gets the number of tips
     * @return int
     */
    public function getTipCount()
    {
        return count($this->tips);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getAuthorities()
    {
        return clone $this->authorities;
    }

    /**
     * @param Authority $authority
     * @return self
     */
    public function addAuthority(Authority $authority)
    {
        $this->authorities->attach($authority);
        return $this;
    }

    /**
     * @param Authority $authority
     * @return self
     */
    public function removeAuthority(Authority $authority)
    {
        $this->authorities->detach($authority);
        return $this;
    }

    /**
     * Returns property customLabel
     * @return string
     */
    public function getCustomLabel()
    {
        return $this->customLabel;
    }

    /**
     * Sets property customLabel
     * @param string $customLabel
     * @return self
     */
    public function setCustomLabel($customLabel)
    {
        $this->customLabel = $customLabel;
        return $this;
    }

    /**
     * Returns property showDynamicContent
     * @return boolean
     */
    public function getShowDynamicContent()
    {
        return $this->showDynamicContent;
    }

    /**
     * Sets property showDynamicContent
     * @param boolean $showDynamicContent the property
     * @return self
     */
    public function setShowDynamicContent($showDynamicContent)
    {
        $this->showDynamicContent = $showDynamicContent;
        return $this;
    }


    /**
     * Combined field getters (for in the view)
     * @param string $setName
     * @return boolean
     */
    public function hasSetValue($setName)
    {
        $set = array('pre' . ucfirst($setName), $setName, 'post' . ucfirst($setName));
        $result = false;
        foreach ($set as $key) {
            $useKey = 'use' . ucfirst($key);
            $result = $result || (strlen(trim($this->$key)) && $this->$useKey);
        }
        return $result;
    }

    /**
     * @param $setName
     * @return string
     */
    protected function getSetValue($setName)
    {
        $set = array('pre' . ucfirst($setName), $setName, 'post' . ucfirst($setName));
        $result = '';
        foreach ($set as $key) {
            $useKey = 'use' . ucfirst($key);
            if (!empty($this->$useKey)) {
                $content = trim($this->$key);
                if (empty($content)) {
                    continue;
                }
                $result .= '<div class="pdcproduct_' . strtolower($key) . '">' . $content . '</div>';
            }
        }
        return $result;
    }

    /**
     * Purposely changed var here, since extbase insists in validating the whole domain object
     * @var string
     * \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext
     */
    protected $controllerContext;

    /**
     * Sets the current controller context
     *
     * @param \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext
     * @return self
     */
    public function setControllerContext(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext)
    {
        $this->controllerContext = $controllerContext;
        return $this;
    }

    /**
     * Returns the link
     * @param string $link
     * @return string
     */
    private function getLink($link)
    {
        if (LinkUtility::isLinkToPage($link)) {
            // Note: Cannot @inject uriBuilder:
            // When Product is used as controller action method parameter, the rewritten property manager
            // recursively validates the entire domain model, including the injected UriBuilder. The UriBuilder
            // fails to validate since it has a $contentObject property, which again is validated.
            // \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer fails validation, as its properties are not
            // annotated with @var.
            // Well done, extbase. -.- (And @dontvalidate does not work here.)
            /** @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder */
            $uriBuilder = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
            return $uriBuilder->setTargetPageUid($link)
                ->setCreateAbsoluteUri(true)
                ->setAddQueryString(false)
                ->setArguments(array())
                ->build();
        } else {
            if (LinkUtility::isLinkToFile($link)) {
                return LinkUtility::getInternalLink($link);
            } else {
                return LinkUtility::getExternalLink($link);
            }
        }
    }

    /**
     * Returns this product as a formatted string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getUid() . $this->name;
    }
}

