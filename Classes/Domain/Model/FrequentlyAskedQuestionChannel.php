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

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class FrequentlyAskedQuestionChannel extends Base
{
    /**
     * @var boolean
     */
    protected $hidden;
    /**
     * @var string
     */
    protected $channels;
    /**
     * @var string
     */
    protected $question;
    /**
     * @var string
     */
    protected $shortAnswer;
    /**
     * @var string
     */
    protected $answer;
    /**
     * @var string
     */
    protected $answerProductField;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\TtAddress>
     */
    protected $answerAddresses;
    /**
     * @var string
     */
    protected $authorizedAnswer;
    /**
     * @var string
     */
    protected $authorizedAnswerProductField;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\TtAddress>
     */
    protected $authorizedAnswerAddresses;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceOtherInfo;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\ReferenceLink>
     * @lazy
     */
    protected $referenceContacts;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\TtAddress>
     */
    protected $contactAddresses;

    /**
     * Initialization
     */
    public function initializeObject()
    {


        $this->answerAddresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->authorizedAnswerAddresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceOtherInfo = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->referenceContacts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->contactAddresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns property hidden
     * @return boolean
     */
    public function getHidden()
    {
        return (boolean)$this->hidden;
    }

    /**
     * Sets property hidden
     * @param boolean $hidden the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function setHidden($hidden)
    {
        $this->hidden = (boolean)$hidden;
        return $this;
    }

    /**
     * Returns property channels
     * @return array $channels
     */
    public function getChannels()
    {
        return \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(',', $this->channels);
    }

    /**
     * Sets property channels
     * @param string $channel the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function addChannel($channel)
    {
        if (empty($this->channels)) {
            $this->channels = $channel;
        } else {
            if (array_search($channel, $this->getChannels()) === false) {
                $this->channels .= ',' . $channel;
            }
        }
        return $this;
    }

    /**
     * Removes channel from channels
     * @param $channel
     * @return $this object for chaining
     */
    public function removeChannel($channel)
    {
        if (!empty($this->channels)) {
            $this->channels = implode(',', array_diff(array($channel), $this->getChannels()));
        }
        return $this;
    }

    /**
     * Removes channel from channels
     * @param $channel
     * @return $this object for chaining
     */
    public function hasChannel($channel)
    {
        if (empty($this->channels)) {
            $result = false;
        } else {
            if (array_search($channel, $this->getChannels()) === false) {
                $result = false;
            } else {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Returns property question
     * @return string $question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets property question
     * @param string $question the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    /**
     * Returns property shortAnswer
     * @return string
     */
    public function getShortAnswer()
    {
        return $this->shortAnswer;
    }

    /**
     * Sets property shortAnswer
     * @param string $shortAnswer the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function setShortAnswer($shortAnswer)
    {
        $this->shortAnswer = $shortAnswer;
        return $this;
    }

    /**
     * Returns property answer
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Sets property answer
     * @param string $answer the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
        return $this;
    }

    /**
     * Returns property answerProductField
     * @return string
     */
    public function getAnswerProductField()
    {
        return $this->answerProductField;
    }

    /**
     * Sets property answer
     * @param string $answerProductField the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function setAnswerProductField($answerProductField)
    {
        $this->answerProductField = $answerProductField;
        return $this;
    }

    /**
     * Returns the attached contact addresses
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\TtAddress>
     */
    public function getAnswerAddresses()
    {
        return clone $this->answerAddresses;
    }

    /**
     * Tests if contact address already is connected to the FAQ channel
     * @param TtAddress $testAddress the address to test
     * @return boolean true if the address is connected to the FAQ channel false otherwise
     */
    public function hasAnswerAddress(TtAddress $testAddress)
    {
        return $this->answerAddresses->contains($testAddress);
    }

    /**
     * Removes given contact address
     * @param TtAddress $address the address to remove
     * @return self for chaining
     */
    public function removeAnswerAddress(TtAddress $address)
    {
        if ($this->answerAddresses->contains($address)) {
            $this->answerAddresses->detach($address);
        }
        return $this;
    }

    /**
     * Adds contact address to the product
     * @param TtAddress $address the address
     * @return self instance for chaining
     */
    public function addAnswerAddress(TtAddress $address)
    {
        if (!$this->hasAnswerAddress($address)) {
            $this->answerAddresses->attach($address);
        }
        return $this;
    }

    /*
     * Adds multiple addresses to the product
     * @param array $addresses the addresses to be added
     * @return self instance for chaining
     */
    public function addAnswerAddresses($addresses)
    {
        if (count($addresses) > 0) {
            foreach ($addresses as $address) {
                $this->addAnswerAddress($address);
            }
        }
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return self for chaining
     */
    public function removeAllAnswerAddresses()
    {
        $this->answerAddresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property authorizedAnswer
     * @return string
     */
    public function getAuthorizedAnswer()
    {
        return $this->authorizedAnswer;
    }

    /**
     * Sets property authorizedAnswer
     * @param string $authorizedAnswer the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function setAuthorizedAnswer($authorizedAnswer)
    {
        $this->authorizedAnswer = $authorizedAnswer;
        return $this;
    }

    /**
     * Returns property authorizedAnswerProductField
     * @return string
     */
    public function getAuthorizedAnswerProductField()
    {
        return $this->authorizedAnswerProductField;
    }

    /**
     * Sets property authorizedAnswerProductField
     * @param string $authorizedAnswerProductField the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function setAuthorizedAnswerProductField($authorizedAnswerProductField)
    {
        $this->authorizedAnswerProductField = $authorizedAnswerProductField;
        return $this;
    }

    /**
     * Returns the attached contact addresses
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\TtAddress>
     */
    public function getAuthorizedAnswerAddresses()
    {
        return clone $this->authorizedAnswerAddresses;
    }

    /**
     * Tests if contact address already is connected to the FAQ channel
     * @param TtAddress $testAddress the address to test
     * @return boolean true if the address is connected to the FAQ channel false otherwise
     */
    public function hasAuthorizedAnswerAddress(TtAddress $testAddress)
    {
        return $this->authorizedAnswerAddresses->contains($testAddress);
    }

    /**
     * Removes given contact address
     * @param TtAddress $address the address to remove
     * @return self for chaining
     */
    public function removeAuthorizedAnswerAddress(TtAddress $address)
    {
        if ($this->authorizedAnswerAddresses->contains($address)) {
            $this->authorizedAnswerAddresses->detach($address);
        }
        return $this;
    }

    /**
     * Adds contact address to the product
     * @param TtAddress $address the address
     * @return self instance for chaining
     */
    public function addAuthorizedAnswerAddress(TtAddress $address)
    {
        if (!$this->hasAuthorizedAnswerAddress($address)) {
            $this->authorizedAnswerAddresses->attach($address);
        }
        return $this;
    }

    /*
     * Adds multiple addresses to the product
     * @param array $addresses the addresses to be added
     * @return self instance for chaining
     */
    public function addAuthorizedAnswerAddresses($addresses)
    {
        if (count($addresses) > 0) {
            foreach ($addresses as $address) {
                $this->addAuthorizedAnswerAddress($address);
            }
        }
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return self for chaining
     */
    public function removeAllAuthorizedAnswerAddresses()
    {
        $this->authorizedAnswerAddresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    public function getHasAuthorizedAnswerContent()
    {
        return $this->getAuthorizedAnswer() || $this->getAuthorizedAnswerProductField(
        ) || $this->getAuthorizedAnswerAddresses();
    }

    /**
     * Returns property referenceOtherInfo
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceOtherInfo()
    {
        return clone $this->referenceOtherInfo;
    }

    /**
     * Sets property referenceOtherInfo
     * @param ReferenceLink $referenceOtherInfo the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function addReferenceOtherInfo(ReferenceLink $referenceOtherInfo)
    {
        $this->referenceOtherInfo->attach($referenceOtherInfo);
        return $this;
    }

    /**
     * Sets property referenceOtherInfo
     * @param ReferenceLink $referenceOtherInfo the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function removeReferenceOtherInfo(ReferenceLink $referenceOtherInfo)
    {
        $this->referenceOtherInfo->detach($referenceOtherInfo);
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function removeAllReferenceOtherInfo()
    {
        $this->referenceOtherInfo = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns property referenceContacts
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getReferenceContacts()
    {
        return clone $this->referenceContacts;
    }

    /**
     * Add a referenceContacts
     * @param ReferenceLink $referenceContacts the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function addReferenceContact(ReferenceLink $referenceContacts)
    {
        $this->referenceContacts->attach($referenceContacts);
        return $this;
    }

    /**
     * Remove a referenceContact
     * @param ReferenceLink $referenceContact the property
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function removeReferenceContact(ReferenceLink $referenceContact)
    {
        $this->referenceContacts->detach($referenceContact);
        return $this;
    }

    /**
     * Removes all references to other info/
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function removeAllReferenceContacts()
    {
        $this->referenceContacts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    /**
     * Returns the attached contact addresses
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getContactAddresses()
    {
        return clone $this->contactAddresses;
    }

    /**
     * Tests if contact address already is connected to the FAQ channel
     * @param TtAddress $testAddress the address to test
     * @return boolean true if the address is connected to the FAQ channel false otherwise
     */
    public function hasContactAddress(TtAddress $testAddress)
    {
        return $this->contactAddresses->contains($testAddress);
    }

    /**
     * Removes given contact address
     * @param TtAddress $address the address to remove
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
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
     * @return self instance for chaining
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
     * @return self instance for chaining
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
     * @return \Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel instance
     */
    public function removeAllContactAddresses()
    {
        $this->contactAddresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        return $this;
    }

    public function __toString()
    {
        return $this->question . $this->answer . $this->shortAnswer . $this->authorizedAnswer;
    }
}

