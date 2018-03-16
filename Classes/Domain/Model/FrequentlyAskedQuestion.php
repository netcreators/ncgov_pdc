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
use Netcreators\NcgovPdc\Utility\TioThemeClassificationReader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class FrequentlyAskedQuestion extends Base
{

    /**
     * @var boolean
     */
    protected $hidden = false;
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
    protected $weight = '';
    /**
     * @var integer
     */
    protected $priority = '';
    /**
     * @var string
     */
    protected $supplierSystem = '';
    /**
     * @var string
     */
    protected $editorialState = '';
    /**
     * @var integer
     */
    protected $verificationDate = 0;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Revision>
     * @lazy
     * @cascade remove
     */
    protected $revisions;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel>
     * @cascade remove
     */
    protected $frequentlyAskedQuestionChannels;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceProducts;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceFrequentlyAskedQuestions;

    /**
     * @var string
     */
    protected $owmsCoreTitle = '';
    /**
     * @var string
     */
    protected $owmsCoreIdentifier = '';
    /**
     * @var string
     */
    protected $owmsCoreLanguage = '';
    /**
     * @var string
     */
    protected $owmsCoreCreatorScheme = '';
    /**
     * @var string
     */
    protected $owmsCoreCreator = '';
    /**
     * @var \DateTime
     */
    protected $owmsCoreModified;
    /**
     * @var string
     */
    protected $owmsCoreSpatialScheme = '';
    /**
     * @var string
     */
    protected $owmsCoreSpatial = '';
    /**
     * @var integer
     */
    protected $owmsCoreTemporalStart = 0;
    /**
     * @var integer
     */
    protected $owmsCoreTemporalEnd = 0;
    /**
     * @var string
     */
    protected $owmsMantleAuthority = '';
    /**
     * @var string
     */
    protected $owmsMantleContributor = '';
    /**
     * @var \DateTime
     */
    protected $owmsMantleAvailableStart = null;
    /**
     * @var \DateTime
     */
    protected $owmsMantleAvailableEnd = null;
    /**
     * @var string
     */
    protected $owmsMantleAudience = '';

    /**
     * @var string
     */
    protected $owmsMantleSubjects122;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Destination>
     * @lazy
     */
    protected $destinations;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\Authority>
     * @lazy
     */
    protected $authorities;

    /**
     * Is the faq active?
     * @var boolean
     */
    private $isActive = false;

    /**
     * Channels to be shown
     * @var string
     * Note: Purposely changed var here, since extbase insists in validating the whole domain object
     */
    private $activeChannels;

    /**
     * Clean the url for referenceLinks?
     * @var boolean
     */
    private $cleanUrl;

    /**
     * Sorting in relation
     * @var boolean
     */
    private $mmSorting;

    /**
     * Object initialization
     */
    public function initializeObject()
    {


        $this->frequentlyAskedQuestionChannels = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->revisions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceProducts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceFrequentlyAskedQuestions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->authorities = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->destinations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Gets the local (relation) sorting
     * @return integer
     */
    public function getMmSorting()
    {
        return $this->mmSorting;
    }

    /**
     * Sets the local (relation) sorting
     * @param integer $mmSorting
     * @return self
     */
    public function setMmSorting($mmSorting)
    {
        $this->mmSorting = $mmSorting;
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
     * Returns property modified
     * @return \DateTime
     */
    public function getOwmsCoreModified()
    {
        return $this->owmsCoreModified;
    }

    /**
     * Sets property hidden
     * @param \DateTime $owmsCoreModified the property
     * @return self
     */
    public function setOwmsCoreModified(\DateTime $owmsCoreModified)
    {
        $this->owmsCoreModified = $owmsCoreModified;
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
     * Returns property weight
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Sets property weight
     * @param int $weight the property
     * @return self
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * Returns property priority
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Sets property priority
     * @param int $priority the property
     * @return self
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Returns property supplierSystem
     * @return string
     */
    public function getSupplierSystem()
    {
        return $this->supplierSystem;
    }

    /**
     * Sets property supplierSystem
     * @param string $supplierSystem the property
     * @return self
     */
    public function setSupplierSystem($supplierSystem)
    {
        $this->supplierSystem = $supplierSystem;
        return $this;
    }

    /**
     * Returns property redactionState
     * @return string
     */
    public function getEditorialState()
    {
        return $this->editorialState;
    }

    /**
     * Sets property redactionState
     * @param string $state the property
     * @return self
     */
    public function setEditorialState($state)
    {
        $this->editorialState = $state;
        return $this;
    }

    /**
     * Returns property verificationDate
     * @return \DateTime
     */
    public function getVerificationDate()
    {
        return $this->verificationDate;
    }

    /**
     * Sets property verificationDate
     * @param \DateTime $verificationDate the property
     * @return self
     */
    public function setVerificationDate($verificationDate)
    {
        $this->verificationDate = $verificationDate;
        return $this;
    }

    /**
     * Returns property revisions
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRevisions()
    {
        return $this->revisions;
    }

    /**
     * Adds a revision
     * @param Revision $revision the property
     * @return self
     */
    public function addRevision(Revision $revision)
    {
        $this->revisions->attach($revision);
        return $this;
    }

    /**
     * Removes a revision
     * @param Revision $revision the property
     * @return self
     */
    public function removeRevision(Revision $revision)
    {
        $this->revisions->detach($revision);
        return $this;
    }

    /**
     * Removes all revisions
     * @return self
     */
    public function removeAllRevisions()
    {
        if (count($this->revisions) > 0) {
            // FIXME: updated extbase version should make this obsolete
            foreach ($this->revisions as $revision) {
                $this->revisions->detach($revision);
            }
            //$this->revisions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        }
        return $this;
    }

    /**
     * Returns property frequentlyAskedQuestionChannels
     * @return array
     */
    public function getFrequentlyAskedQuestionChannels()
    {
        $newChannels = array();
        if ($this->frequentlyAskedQuestionChannels != null && count($this->frequentlyAskedQuestionChannels) > 0
            && is_array($this->activeChannels) && count($this->activeChannels) > 0
        ) {
            foreach ($this->frequentlyAskedQuestionChannels as $faqChannel) {
                $hasChannel = false;
                foreach ($this->activeChannels as $channel) {
                    if ($faqChannel->hasChannel($channel)) {
                        $hasChannel = true;
                        break;
                    }
                }
                if ($hasChannel) {
                    $newChannels[] = $faqChannel;
                }
            }
        }
        return $newChannels;
    }

    /**
     * Returns the channels contained
     * @return array
     */
    public function getContainedChannelTypes()
    {
        $channels = array();
        if ($this->frequentlyAskedQuestionChannels != null && count($this->frequentlyAskedQuestionChannels) > 0) {
            /** @var FrequentlyAskedQuestionChannel $faqChannel */
            foreach ($this->frequentlyAskedQuestionChannels as $faqChannel) {
                $channels = array_merge($channels, $faqChannel->getChannels());
            }
        }
        return $channels;
    }


    /**
     * Sets the active channels for this faq.
     * @param array $channels
     * @return self instance
     */
    public function setActiveChannels($channels)
    {
        $this->activeChannels = $channels;
        return $this;
    }

    /**
     * Returns the specified channel object
     * @param array $channels
     * @return FrequentlyAskedQuestionChannel or NULL
     */
    public function containsAnyChannel($channels)
    {
        $result = false;
        if ($this->frequentlyAskedQuestionChannels != null && count($this->frequentlyAskedQuestionChannels) > 0
            && is_array($channels) && count($channels) > 0
        ) {
            /** @var FrequentlyAskedQuestionChannel $faqChannel */
            foreach ($this->frequentlyAskedQuestionChannels as $faqChannel) {
                foreach ($channels as $channel) {
                    if ($faqChannel->hasChannel($channel)) {
                        $result = true;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Returns the specified channel object
     * @param string $channel
     * @return FrequentlyAskedQuestionChannel or NULL
     */
    public function getFrequentlyAskedQuestionChannel($channel)
    {
        $result = null;
        if ($this->frequentlyAskedQuestionChannels != null && count($this->frequentlyAskedQuestionChannels) > 0) {
            /** @var FrequentlyAskedQuestionChannel $faqChannel */
            foreach ($this->frequentlyAskedQuestionChannels as $faqChannel) {
                if ($faqChannel->hasChannel($channel)) {
                    $result = $faqChannel;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Checks if a specific faq channel exists
     * @param string $channel the channel to check for
     * @return boolean    true if it exists
     */
    public function hasFrequentlyAskedQuestionChannel($channel)
    {
        $result = false;
        if ($this->frequentlyAskedQuestionChannels != null && $this->frequentlyAskedQuestionChannels->count() > 0) {
            /** @var FrequentlyAskedQuestionChannel $faqChannel */
            foreach ($this->frequentlyAskedQuestionChannels as $faqChannel) {
                if ($faqChannel->hasChannel($channel)) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Sets property frequentlyAskedQuestionChannels
     * @param FrequentlyAskedQuestionChannel $frequentlyAskedQuestionChannel the property
     * @return self
     */
    public function addFrequentlyAskedQuestionChannel(
        FrequentlyAskedQuestionChannel $frequentlyAskedQuestionChannel
    ) {
        $this->frequentlyAskedQuestionChannels->attach($frequentlyAskedQuestionChannel);
        return $this;
    }

    /**
     * Removes frequentlyAskedQuestionChannel
     * @param FrequentlyAskedQuestionChannel $frequentlyAskedQuestionChannel the property
     * @return self
     */
    public function removeFrequentlyAskedQuestionChannel(
        FrequentlyAskedQuestionChannel $frequentlyAskedQuestionChannel
    ) {
        $this->frequentlyAskedQuestionChannels->detach($frequentlyAskedQuestionChannel);
        return $this;
    }

    /**
     * Removes all frequentlyAskedQuestionChannel
     * @return self
     */
    public function removeAllFrequentlyAskedQuestionChannels()
    {
        if (count($this->frequentlyAskedQuestionChannels) > 0) {
            foreach ($this->frequentlyAskedQuestionChannels as $faqChannel) {
                $this->frequentlyAskedQuestionChannels->detach($faqChannel);
            }
        }
        return $this;
    }

    /**
     * Returns property referenceProducts
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceProducts()
    {
        return clone $this->referenceProducts;
    }

    /**
     * Adds reference to product (link)
     * @param ReferenceLink $referenceProduct the property
     * @return self
     */
    public function addReferenceProduct(ReferenceLink $referenceProduct)
    {
        $this->referenceProducts->attach($referenceProduct);
        return $this;
    }

    /**
     * Removes reference to product (link)
     * @param ReferenceLink $referenceProduct the property
     * @return self
     */
    public function removeReferenceProduct(ReferenceLink $referenceProduct)
    {
        $this->referenceProducts->detach($referenceProduct);
        return $this;
    }

    /**
     * Removes all referenceProducts
     * @return self
     */
    public function removeAllReferenceProducts()
    {
        $this->referenceProducts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property referenceFrequentlyAskedQuestions
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceFrequentlyAskedQuestions()
    {
        return $this->referenceFrequentlyAskedQuestions;
    }

    /**
     * Returns property referenceFrequentlyAskedQuestions, filtered to contain only ReferenceLinks which link to an actual
     * FAQ Domain Object.
     *
     * @return array
     */
    public function getReferenceFrequentlyAskedQuestionsFiltered()
    {
        $newReferences = array();
        if (count($this->referenceFrequentlyAskedQuestions) > 0) {
            /** @var ReferenceLink $link */
            foreach ($this->referenceFrequentlyAskedQuestions as $link) {
                if ($link->getLinkFrequentlyAskedQuestion()) {
                    $newReferences[] = $link;
                }
            }
        }
        return $newReferences;
    }

    /**
     * Tests if there is at least one valid reference link to frequently asked questions.
     * @return boolean
     */
    public function getHasValidReferenceFrequentlyAskedQuestions()
    {
        $result = false;
        if ($this->referenceFrequentlyAskedQuestions !== null && count($this->referenceFrequentlyAskedQuestions) > 0) {
            foreach ($this->referenceFrequentlyAskedQuestions as $referenceLink) {
                if ($referenceLink->getIsValid()) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Adds property referenceFrequentlyAskedQuestions
     * @param ReferenceLink $referenceFrequentlyAskedQuestion the property
     * @return self
     */
    public function addReferenceFrequentlyAskedQuestion(
        ReferenceLink $referenceFrequentlyAskedQuestion
    ) {
        $this->referenceFrequentlyAskedQuestions->attach($referenceFrequentlyAskedQuestion);
        return $this;
    }

    /**
     * Adds property referenceFrequentlyAskedQuestions
     * @param ReferenceLink $referenceFrequentlyAskedQuestion the property
     * @return self
     */
    public function removeReferenceFrequentlyAskedQuestion(
        ReferenceLink $referenceFrequentlyAskedQuestion
    ) {
        $this->referenceFrequentlyAskedQuestions->detach($referenceFrequentlyAskedQuestion);
        return $this;
    }

    /**
     * Removes all referenceFrequentlyAskedQuestions
     * @return self
     */
    public function removeAllReferenceFrequentlyAskedQuestions()
    {
        $this->referenceFrequentlyAskedQuestions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property owmsCoreTitle
     * @return string
     */
    public function getOwmsCoreTitle()
    {
        return $this->owmsCoreTitle;
    }

    /**
     * Sets property owmsCoreTitle
     * @param string $owmsCoreTitle the property
     * @return self
     */
    public function setOwmsCoreTitle($owmsCoreTitle)
    {
        $this->owmsCoreTitle = $owmsCoreTitle;
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
     * Returns property owmsCoreLanguage
     * @return string
     */
    public function getOwmsCoreLanguage()
    {
        return $this->owmsCoreLanguage;
    }

    /**
     * Sets property owmsCoreLanguage
     * @param string $owmsCoreLanguage the property
     * @return self
     */
    public function setOwmsCoreLanguage($owmsCoreLanguage)
    {
        $this->owmsCoreLanguage = $owmsCoreLanguage;
        return $this;
    }

    /**
     * Returns property owmsCoreCreatorScheme
     * @return string
     */
    public function getOwmsCoreCreatorScheme()
    {
        return $this->owmsCoreCreatorScheme;
    }

    /**
     * Sets property owmsCoreCreatorScheme
     * @param string $owmsCoreCreatorScheme the property
     * @return self
     */
    public function setOwmsCoreCreatorScheme($owmsCoreCreatorScheme)
    {
        $this->owmsCoreCreatorScheme = $owmsCoreCreatorScheme;
        return $this;
    }

    /**
     * Returns property owmsCoreCreator
     * @return string
     */
    public function getOwmsCoreCreator()
    {
        return $this->owmsCoreCreator;
    }

    /**
     * Sets property owmsCoreCreator
     * @param string $owmsCoreCreator the property
     * @return self
     */
    public function setOwmsCoreCreator($owmsCoreCreator)
    {
        $this->owmsCoreCreator = $owmsCoreCreator;
        return $this;
    }

    /**
     * Returns property owmsCoreSpatialScheme
     * @return string
     */
    public function getOwmsCoreSpatialScheme()
    {
        return $this->owmsCoreSpatialScheme;
    }

    /**
     * Sets property owmsCoreSpatialScheme
     * @param string $owmsCoreSpatialScheme the property
     * @return self
     */
    public function setOwmsCoreSpatialScheme($owmsCoreSpatialScheme)
    {
        $this->owmsCoreSpatialScheme = $owmsCoreSpatialScheme;
        return $this;
    }

    /**
     * Returns property owmsCoreSpatial
     * @return string
     */
    public function getOwmsCoreSpatial()
    {
        return $this->owmsCoreSpatial;
    }

    /**
     * Sets property owmsCoreSpatial
     * @param string $owmsCoreSpatial the property
     * @return self
     */
    public function setOwmsCoreSpatial($owmsCoreSpatial)
    {
        $this->owmsCoreSpatial = $owmsCoreSpatial;
        return $this;
    }

    /**
     * Returns property owmsCoreTemporalStart
     * @return \DateTime
     */
    public function getOwmsCoreTemporalStart()
    {
        return $this->owmsCoreTemporalStart;
    }

    /**
     * Sets property owmsCoreTemporalStart
     * @param \DateTime $owmsCoreTemporalStart the property
     * @return self
     */
    public function setOwmsCoreTemporalStart($owmsCoreTemporalStart)
    {
        $this->owmsCoreTemporalStart = $owmsCoreTemporalStart;
        return $this;
    }

    /**
     * Returns property owmsCoreTemporalEnd
     * @return \DateTime
     */
    public function getOwmsCoreTemporalEnd()
    {
        return $this->owmsCoreTemporalEnd;
    }

    /**
     * Sets property owmsCoreTemporalEnd
     * @param \DateTime $owmsCoreTemporalEnd the property
     * @return self
     */
    public function setOwmsCoreTemporalEnd($owmsCoreTemporalEnd)
    {
        $this->owmsCoreTemporalEnd = $owmsCoreTemporalEnd;
        return $this;
    }

    /**
     * Returns property owmsMantleAuthority
     * @return string
     */
    public function getOwmsMantleAuthority()
    {
        return $this->owmsMantleAuthority;
    }

    /**
     * Sets property owmsMantleAuthority
     * @param string $owmsMantleAuthority the property
     * @return self
     */
    public function setOwmsMantleAuthority($owmsMantleAuthority)
    {
        $this->owmsMantleAuthority = $owmsMantleAuthority;
        return $this;
    }

    /**
     * Returns property owmsMantleContributor
     * @return string
     */
    public function getOwmsMantleContributor()
    {
        return $this->owmsMantleContributor;
    }

    /**
     * Sets property owmsMantleContributor
     * @param string $owmsMantleContributor the property
     * @return self
     */
    public function setOwmsMantleContributor($owmsMantleContributor)
    {
        $this->owmsMantleContributor = $owmsMantleContributor;
        return $this;
    }

    /**
     * Returns property owmsMantleAvailableStart
     * @return \DateTime|NULL
     */
    public function getOwmsMantleAvailableStart()
    {
        return $this->owmsMantleAvailableStart;
    }

    /**
     * Sets property owmsMantleAvailableStart
     * @param \DateTime $owmsMantleAvailableStart the property
     * @return self
     */
    public function setOwmsMantleAvailableStart($owmsMantleAvailableStart)
    {
        $this->owmsMantleAvailableStart = $owmsMantleAvailableStart;
        return $this;
    }

    /**
     * Returns property owmsMantleAvailableEnd
     * @return \DateTime|NULL
     */
    public function getOwmsMantleAvailableEnd()
    {
        return $this->owmsMantleAvailableEnd;
    }

    /**
     * Sets property owmsMantleAvailableEnd
     * @param \DateTime $owmsMantleAvailableEnd the property
     * @return self
     */
    public function setOwmsMantleAvailableEnd($owmsMantleAvailableEnd)
    {
        $this->owmsMantleAvailableEnd = $owmsMantleAvailableEnd;
        return $this;
    }

    /**
     * Returns property owmsMantleAudience
     * @return array
     */
    public function getOwmsMantleAudience()
    {
        return GeneralUtility::trimExplode(',', $this->owmsMantleAudience);
    }

    /**
     * Sets property owmsMantleAudience
     * @param array $owmsMantleAudience the property
     * @return self
     */
    public function setOwmsMantleAudience($owmsMantleAudience)
    {
        $this->owmsMantleAudience = implode(',', $owmsMantleAudience);
        return $this;
    }

    /**
     * Returns property owmsMantleSubjects
     * @return array
     */
    public function getOwmsMantleSubjects()
    {
        if ($this->owmsMantleSubjects122 != '') {
            return GeneralUtility::trimExplode(',', $this->owmsMantleSubjects122);
        } else {
            return array();
        }
    }

    /**
     * Sets owms mantle subjects
     * @param string $subjects subjects (as csv)
     * @return self
     */
    public function setOwmsMantleSubjects($subjects)
    {
        $this->owmsMantleSubjects122 = $subjects;
        return $this;
    }

    /**
     * Returns the selected subjects represented by their name
     * @return array the subjects
     */
    public function getOwmsMantleSubjectsWithName()
    {
        /** @var TioThemeClassificationReader $tioThemeClassificationReader */
        $tioThemeClassificationReader = $this->objectManager->get(TioThemeClassificationReader::class);
        return $tioThemeClassificationReader->getTioThemesByIdentifiers($this->getOwmsMantleSubjects());
    }

    /**
     * Adds owms subject
     * @param string $owmsMantleSubject the identifier
     * @return self
     */
    public function addOwmsMantleSubject($owmsMantleSubject)
    {
        $owmsMantleSubject = (string)$owmsMantleSubject;
        $this->owmsMantleSubjects122 = implode(
            ',',
            array_unique(
                array_merge($this->getOwmsMantleSubjects(), array($owmsMantleSubject))
            )
        );
        if ($this->owmsMantleSubjects122[0] == ',') {
            $this->owmsMantleSubjects122 = substr($this->owmsMantleSubjects122, 1);
        }
        return $this;
    }

    /**
     * Clears property owmsMantleSubjects
     * @return self
     */
    public function removeAllOwmsMantleSubjects()
    {
        $this->owmsMantleSubjects122 = '';
        return $this;
    }

    /**
     * Returns property destinations
     * @return array the destinations
     */
    public function getDestinations()
    {
        return clone $this->destinations;
    }

    /**
     * Adds destination
     * @param Destination $destination the identifier
     * @return self
     */
    public function addDestination(Destination $destination)
    {
        $this->destinations->attach($destination);
        return $this;
    }

    /**
     * Removes destination
     * @param Destination $destination the identifier
     * @return self
     */
    public function removeDestination(Destination $destination)
    {
        $this->destinations->detach($destination);
        return $this;
    }

    /**
     * Removes all Destinations
     * @return self
     */
    public function removeAllDestinations()
    {
        $this->destinations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Checks if this FAQ has a given Destination
     * @param array $destinations the destinations
     * @return self
     */
    public function containsDestinations($destinations)
    {
        $result = false;
        foreach ($destinations as $destination) {
            if (!empty($destination)) {
                foreach ($this->destinations as $myDestination) {
                    if ($myDestination->getUid() == $destination) {
                        $result = true;
                        break;
                    }
                }
            }
        }

        return $result;
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
     * Sets property authorities
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $authorities the property
     * @return self
     */
    public function setAuthorities($authorities)
    {
        $this->authorities = $authorities;
        return $this;
    }

    public function addAuthority(Authority $authority)
    {
        $this->authorities->attach($authority);
        return $this;
    }

    /**
     * @return null|\TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getAuthorities()
    {
        if ($this->authorities == null || !is_object($this->authorities)) {
            return null;
        }
        return clone $this->authorities;
    }

    /**
     * @param $uid
     * @return integer
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * Sets if the faq is active (expanded)
     * @return self
     */
    public function setIsActive()
    {
        $this->isActive = true;
        return $this;
    }

    /**
     * Returns the active state of the faq.
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Checks if a user group is in this faq model.
     * @param Authority $authority the authority
     * @return boolean
     */
    public function containsAuthority(Authority $authority)
    {
        $found = false;
        if (count($this->authorities)) {
            $found = $this->authorities->contains($authority);

            /** @var Authority $localAuthority */
            foreach ($this->authorities as $localAuthority) {
                if ($authority->getUid() == $localAuthority->getUid()) {
                    $found = true;
                }
            }
        }
        return $found;
    }

    /**
     * Sets the page identifier for the target page for the reference links to be generated.
     * @param integer $faqTargetPageIdentifier the target page
     * @param integer $productTargetPageIdentifier the target page
     * @return self
     */
    public function setTargetPageIdentifier($faqTargetPageIdentifier, $productTargetPageIdentifier)
    {
        if ($this->referenceFrequentlyAskedQuestions && !is_array(
                $this->referenceFrequentlyAskedQuestions
            ) && $this->referenceFrequentlyAskedQuestions->count()
        ) {
            /** @var ReferenceLink $referenceLink */
            foreach ($this->referenceFrequentlyAskedQuestions as $referenceLink) {
                $referenceLink->setPageIdentifier($faqTargetPageIdentifier);
            }
        }
        if ($this->referenceProducts && !is_array($this->referenceProducts) && $this->referenceProducts->count()) {
            /** @var ReferenceLink $referenceLink */
            foreach ($this->referenceProducts as $referenceLink) {
                $referenceLink->setPageIdentifier($productTargetPageIdentifier);
            }
        }
        return $this;
    }

    /**
     * @param boolean $state
     */
    public function setCleanUrl($state)
    {
        $this->cleanUrl = $state;
    }

    /**
     * @return boolean
     */
    public function getCleanUrl()
    {
        return $this->cleanUrl;
    }

    /**
     * Sets the uri builder object.
     * @return self
     */
    public function setReferencesCleanUrl()
    {
        if ($this->referenceFrequentlyAskedQuestions != null && $this->referenceFrequentlyAskedQuestions->count() > 0) {
            /** @var ReferenceLink $referenceLink */
            foreach ($this->referenceFrequentlyAskedQuestions as $index => $referenceLink) {
                $referenceLink->setCleanUrl($this->cleanUrl);
                $this->referenceFrequentlyAskedQuestions->offsetSet($referenceLink, $referenceLink);
            }
        }
        if ($this->referenceProducts != null && $this->referenceProducts->count() > 0) {
            /** @var ReferenceLink $referenceLink */
            foreach ($this->referenceProducts as $index => $referenceLink) {
                $referenceLink->setCleanUrl($this->cleanUrl);
                $this->referenceProducts->offsetSet($referenceLink, $referenceLink);
            }
        }
        return $this;
    }

    /**
     * String representation
     * @return string
     */
    public function __toString()
    {
        return $this->owmsCoreIdentifier . $this->sessionNumber . $this->imported . $this->owmsCoreTitle;
    }
}

