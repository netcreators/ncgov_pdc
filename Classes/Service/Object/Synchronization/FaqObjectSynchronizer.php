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

namespace Netcreators\NcgovPdc\Service\Object\Synchronization;

use Netcreators\NcExtbaseLib\Domain\Model\IntermediateObject;
use Netcreators\NcExtbaseLib\Service\Object\Synchronization\AbstractObjectSynchronizer;
use Netcreators\NcgovPdc\Domain\Model\Destination;
use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;
use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestionChannel;
use Netcreators\NcgovPdc\Domain\Model\Product;
use Netcreators\NcgovPdc\Domain\Model\ReferenceLink;
use Netcreators\NcgovPdc\Domain\Model\Revision;
use Netcreators\NcgovPdc\Domain\Model\TtAddress;

/**
 * Synchronization interface. Used by the synchronization controller.
 *
 * the controller will be called as $controller->synchronize($intermediate);
 * the controller will have an object of the interface
 * The controller will first get the identifier by getObjectIdentifier($intermediate)
 * Then it will use findObject($intermediate, $identifier) to find the local object which must be updated
 * Then it will use canUpdateObject to test if the local object is ready to be updated by the intermediate
 * Then it will update the object through updateObject($localObject, $intermediate)
 * If the object exists and does not have to be updated it will be touched through touchObject($localObject, $intermediate)
 * If the object was not found through findObject it can be created in createObject($intermediate)
 * The log methods are there to report what is going on. (practical for debugging)
 *
 * You will have to take care of the objects being written in the repository yourself.
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Model
 */
class FaqObjectSynchronizer extends AbstractObjectSynchronizer
{

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\DestinationRepository
     * @inject
     */
    protected $destinationRepository;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\FrequentlyAskedQuestionRepository
     * @inject
     */
    protected $faqRepository;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\ProductRepository
     * @inject
     */
    protected $productRepository;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\TtAddressRepository
     * @inject
     */
    protected $addressRepository;

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\ReferenceLinkRepository
     * @inject
     */
    protected $referenceLinkRepository;

    /**
     * Finds the given object
     * @param IntermediateObject $intermediateObject the intermediate object (depends on local implementation what type it is)
     * @param mixed $identifier the identifier of the object to be found
     * @return mixed the object found or false if not found
     */
    public function findObject(IntermediateObject $intermediateObject, $identifier)
    {
        if (isset($this->createdObjects['faq'][$identifier])) {
            $found = $this->createdObjects['faq'][$identifier];
        } else {
            $found = $this->faqRepository->findImportedByOwmsCoreIdentifier($identifier)->getFirst();
        }
        return $found;
    }

    /**
     * Returns true if the local object can be updated against the intermediate object (or other conditions, local implementation
     * has the freedom to decide)
     * @param mixed $localObject the local object
     * @param IntermediateObject $intermediateObject the intermediate object
     * @return boolean
     */
    public function canUpdateObject($localObject, IntermediateObject $intermediateObject)
    {
        $result = $this->forceFullUpdate;
        if (!$this->forceFullUpdate) {
            $date = $localObject->getOwmsCoreModified();
            $intermediateDate = $intermediateObject->getData('owms_core_modified');
            $result = $date < $intermediateDate;
        }
        return $result;
    }

    /**
     * Updates the local object with the intermediate object
     * @param mixed $localObject the local object
     * @param IntermediateObject $intermediateObject the intermediate object
     * @return void
     */
    public function updateObject($localObject, IntermediateObject $intermediateObject)
    {
        $this->updateCount++;
        $localObject->setSessionNumber($this->logRepository->getSessionId());
        $this->setAttributesFromIntermediate($localObject, $intermediateObject);
        $this->faqRepository->update($localObject);
        $this->log(
            'Updating',
            $localObject->getOwmsCoreIdentifier(),
            array(
                'info' => $intermediateObject->getData('owms_core_title')
            )
        );
    }

    /**
     * Touches the local object if it exists but does not have to be updated (useful for synchronizing when you have to mark
     *    objects which have been inspected)
     * @param mixed $localObject the local object
     * @param IntermediateObject $intermediateObject the intermediate object
     * @return void
     */
    public function touchObject($localObject, IntermediateObject $intermediateObject)
    {
        $this->touchCount++;
        $localObject->setSessionNumber($this->logRepository->getSessionId());
        $this->faqRepository->update($localObject);
    }

    /**
     * Creates the object from the intermediate given.
     * @param IntermediateObject $intermediateObject
     * @return void
     */
    public function createObject(IntermediateObject $intermediateObject)
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );

        $this->createCount++;
        /** @var FrequentlyAskedQuestion $faq */
        $faq = $this->objectManager->get(FrequentlyAskedQuestion::class);
        // make sure it is registered to this session, so we can delete all records without this session number
        $faq->setSessionNumber($this->logRepository->getSessionId());

        $identifier = $this->getObjectIdentifierFromIntermediate($intermediateObject);
        $this->log(
            'Creating',
            $identifier,
            array(
                'info' => $intermediateObject->getData('owms_core_title')
            )
        );

        $faq->setOwmsCoreIdentifier($identifier);
        $faq->setPid((int)$extbaseFrameworkConfiguration['persistence']['storagePid']);
        $faq->setImported(true);
        $this->setAttributesFromIntermediate($faq, $intermediateObject);

        $this->createdObjects['faq'][$identifier] = $faq;
        $this->faqRepository->add($faq);

    }

    /**
     * Returns the value with which the object can be identified from the intermediate object. Will be used and passed
     * to findobject and log
     * @param IntermediateObject $intermediateObject
     * @return mixed the identifier of the object
     */
    public function getObjectIdentifierFromIntermediate(IntermediateObject $intermediateObject)
    {
        return $intermediateObject->getData('owms_core_identifier');
    }

    /**
     * Sets the attributes from the intermediate object
     * @param FrequentlyAskedQuestion $faq the faq for which the properties need to be set
     * @param IntermediateObject $intermediate the intermediate object
     * @return void
     */
    protected function setAttributesFromIntermediate(FrequentlyAskedQuestion &$faq, IntermediateObject $intermediate)
    {
        foreach ($intermediate->getKeys() as $key) {
            switch ($key) {
                case 'revision':
                    $faq->removeAllRevisions();
                    $revisions = $intermediate->getData($key);
                    if (count($revisions)) {
                        foreach ($revisions as $revision) {
                            /** @var Revision $faqRevision */
                            $faqRevision = $this->objectManager->get(Revision::class);
                            $faqRevision->setVersion($revision['version']);
                            $faqRevision->setDateTime($revision['date_time']);
                            $faqRevision->setAuthor($revision['author']);
                            $faqRevision->setComment($revision['comment']);
                            $faqRevision->setRevisionTypes($revision['revisionType']);
                            $faqRevision->createTitle($faq->getOwmsCoreTitle());

                            $faq->addRevision($faqRevision);
                        }
                    }
                    break;
                case 'subject':
                    $faq->removeAllOwmsMantleSubjects();
                    $items = $intermediate->getData($key);
                    if (count($items)) {
                        foreach ($items as $item) {
                            $faq->addOwmsMantleSubject($item);
                        }
                    }
                    break;
                case 'destination':
                    $faq->removeAllDestinations();
                    $items = $intermediate->getData($key);
                    if (count($items)) {
                        foreach ($items as $item) {
                            $destination = $this->findOrCreateDestination($item);
                            if ($destination !== false) {
                                $faq->addDestination($destination);
                            }
                        }
                    }
                    break;
                case 'channel':
                    $faq->removeAllFrequentlyAskedQuestionChannels();
                    $items = $intermediate->getData($key);
                    if (count($items)) {
                        foreach ($items as $item) {
                            /** @var FrequentlyAskedQuestionChannel $faqChannel */
                            $faqChannel = $this->objectManager->get(
                                FrequentlyAskedQuestionChannel::class
                            );
                            $faqChannel->addChannel($item['kanaal']);
                            $faqChannel->setQuestion($item['vraag']);
                            $faqChannel->setAnswer(
                                $item['antwoord'] . $item['antwoordTekst']
                            ); // Concatenate APlus and SDU fields
                            $faqChannel->setAnswerProductField($item['antwoordProductVeld']);
                            if (count($item['antwoordAdres'])) {
                                $answerAddresses = $this->findOrCreateContactInstances($item['antwoordAdres']);
                                $faqChannel->removeAllAnswerAddresses();
                                $faqChannel->addAnswerAddresses($answerAddresses);
                            }
                            $faqChannel->setShortAnswer($item['kort_antwoord']);
                            $faqChannel->setAuthorizedAnswer(
                                $item['onderwater_antwoord'] . $item['onderwater_antwoordTekst']
                            ); // Concatenate APlus and SDU fields
                            $faqChannel->setAuthorizedAnswerProductField($item['onderwater_antwoordProductVeld']);
                            if (count($item['onderwater_antwoordAdres'])) {
                                $authorizedAnswerAddresses = $this->findOrCreateContactInstances(
                                    $item['onderwater_antwoordAdres']
                                );
                                $faqChannel->removeAllAuthorizedAnswerAddresses();
                                $faqChannel->addAuthorizedAnswerAddresses($authorizedAnswerAddresses);
                            }
                            $faqChannel->removeAllReferenceOtherInfo();
                            if (count($item['referenceOtherInfo'])) {
                                $references = $this->findOrCreateReferences('OtherInfo', $item['referenceOtherInfo']);
                                foreach ($references as $reference) {
                                    $faqChannel->addReferenceOtherInfo($reference);
                                }
                            }
                            if (count($item['referenceContact'])) {
                                $references = $this->findOrCreateReferences('Contact', $item['referenceContact']);
                                foreach ($references as $reference) {
                                    $faqChannel->addReferenceContact($reference);
                                }
                            }
                            if (count($item['contactAddresses'])) {
                                $contactAddresses = $this->findOrCreateContactInstances($item['contactAddresses']);
                                $faqChannel->removeAllContactAddresses();
                                $faqChannel->addContactAddresses($contactAddresses);
                            }
                            $faq->addFrequentlyAskedQuestionChannel($faqChannel);
                        }
                    }
                    break;
                case 'referenceProduct':
                    $items = $intermediate->getData($key);
                    $faq->removeAllReferenceProducts();
                    if (count($items)) {
                        $references = $this->findOrCreateReferences('Product', $items);
                        foreach ($references as $reference) {
                            $faq->addReferenceProduct($reference);
                        }
                    }
                    break;
                case 'referenceFaq':
                    $items = $intermediate->getData($key);
                    $faq->removeAllReferenceFrequentlyAskedQuestions();
                    if (count($items)) {
                        $references = $this->findOrCreateReferences('Faq', $items);
                        foreach ($references as $reference) {
                            $faq->addReferenceFrequentlyAskedQuestion($reference);
                        }
                    }
                    break;
                default:
                    $this->setObjectProperty($faq, $key, $intermediate->getData($key));
                    break;
            }
        }
    }

    /**
     * Finds or creates a destination
     * @param string $destination the addresses
     * @return Destination
     */
    protected function findOrCreateDestination($destination)
    {
        $type = 'destination';
        $found = null;
        if (isset($this->createdObjects[$type][md5($destination)])) {
            $found = $this->createdObjects[$type][md5($destination)];
        } else {
            // not created let's find it in the database
            /** @var Destination $existingDestination */
            $existingDestination = $this->destinationRepository->findOneByName($destination);
            if (!$existingDestination) {
                /** @var Destination $newDestination */
                $newDestination = $this->objectManager->get(Destination::class);
                $newDestination->setName($destination);

                $found = $newDestination;
                $this->createdObjects[$type][md5($destination)] = $newDestination;
                $this->destinationRepository->add($newDestination);
            } else {
                // update it if it changes
                $existingDestination->setName($destination);

                $found = $existingDestination;
                $this->destinationRepository->update($existingDestination);
            }
        }
        return $found;
    }

    /**
     * Returns the links (either creates them or looks them up)
     * @param string $type the type of references
     * @param array $references the references
     * @return array of \Netcreators\NcgovPdc\Domain\Model\ReferenceLink records
     */
    protected function findOrCreateReferences($type, $references)
    {
        $found = array();
        foreach ($references as $reference) {
            $identifier = $reference['id'];
            if (isset($this->createdObjects['reference' . $type][$identifier])) {
                $found[] = $this->createdObjects['reference' . $type][$identifier];
            } else {
                // not created let's find it in the database
                /** @var ReferenceLink $existingLink */
                $existingLink = $this->referenceLinkRepository->findOneByResourceIdentifier($identifier);
                if ($existingLink === null) {

                    /** @var ReferenceLink $newLink */
                    $newLink = $this->objectManager->get(ReferenceLink::class);
                    $newLink->setResourceIdentifier($identifier);

                    $this->setLinkData($newLink, $reference);

                    $found[] = $newLink;
                    $this->createdObjects['reference' . $type][$identifier] = $newLink;
                    $this->referenceLinkRepository->add($newLink);
                } else {
                    // update it for if it changes
                    $this->setLinkData($existingLink, $reference);

                    $found[] = $existingLink;
                    $this->referenceLinkRepository->update($existingLink);
                }
            }
        }
        return $found;
    }

    /**
     * Note: This method was introduced in r457:52f24b829d3a, existed through several changes and merges until
     * r470:60810445241d, and was removed by a faulty merge (wrong merge direction?) in r475:c6086822d693!
     * If more things went missing, then check r470:c6086822d693!
     *
     * @param ReferenceLink &$referenceLink
     * @param array $referenceData
     */
    protected function setLinkData(ReferenceLink &$referenceLink, array $referenceData)
    {

        $referenceLink->setName($referenceData['title']);
        $referenceLink->setLink($referenceData['url']);
        $referenceLink->setType($referenceData['type']);
        $referenceLink->setImported(true);

        switch ($referenceData['type']) {
            case ReferenceLink::TYPE_PRODUCT:


                /**
                 * 2015-04-15 nc_extbase_lib v1.1.8 Leonie Philine Bitto <leonie@netcreators.nl>
                 *    - Added a parameter $identifierField to \Netcreators\NcExtbaseLib\Service\Object\Synchronization\AbstractObjectSynchronizer::getOrLoadCachedById().
                 *        Among other instances, the method is used to find existing FAQ and Product records in the various PDC synchronizers.
                 *
                 *        SDU provides a separate, numeric ScmetaProductId, and an URI-type OwmsCoreIdentifier. SDU relates FAQs to Products via the ScmetaProductId.
                 *        Opus+ on the other hand uses the OwmsCoreIdentifier for such relations.
                 *        Aplus also provides a separate ScmetaProductId and OwmsCoreIdentifier.
                 *
                 *        For SDU Product->FAQ relations, getOrLoadCachedById() must be called with $identifierField='ScmetaProductId'.
                 */
                $identifierField = 'OwmsCoreIdentifier';
                if (is_numeric($referenceData['url'])) {
                    $identifierField = 'ScmetaProductId';
                }

                /** @var Product $referencedProduct */
                $referencedProduct = $this->getOrLoadCachedById(
                    $this->productRepository,
                    $referenceData['url'],
                    $identifierField
                );

                // Note: Would it make sense to fall back to OwmsCoreIdentifier if no Product is found my ScmetaProductId?
                //       This way we could have one single approach, instead of differenciating via a numeric URL referenceData field.
                if ($referencedProduct) {
                    $referenceLink->setLinkProduct($referencedProduct);
                }
                break;

            case ReferenceLink::TYPE_FREQUENTLY_ASKED_QUESTION:
                $referencedFrequentlyAskedQuestion = $this->getOrLoadCachedById(
                    $this->faqRepository,
                    $referenceData['url']
                );
                if ($referencedFrequentlyAskedQuestion) {
                    $referenceLink->setLinkFrequentlyAskedQuestion($referencedFrequentlyAskedQuestion);
                }
                break;
        }
    }

    /**
     * Returns the contact addresses (either creates them or looks them up)
     * @param array $contactInstances the contact addresses
     * @return array of \Netcreators\NcgovPdc\Domain\Model\TtAddress records
     */
    protected function findOrCreateContactInstances($contactInstances)
    {
        $found = array();
        foreach ($contactInstances as $contactInstanceInfo) {
            $identifier = $contactInstanceInfo['id'];
            if (isset($this->createdObjects['contactInstances'][$identifier])) {
                $found[] = $this->createdObjects['contactInstances'][$identifier];
            } else {
                // not created - let's find it in the database
                $existingContactInstance = $this->addressRepository->findOneByTxNcgovpdcVacContactInstanceUid(
                    $identifier
                );
                if (!$existingContactInstance) {
                    /** @var TtAddress $newContactInstance */
                    $newContactInstance = $this->objectManager->get(TtAddress::class);
                    $newContactInstance->setTxNcgovpdcVacContactInstanceUid($identifier);

                    $this->setContactInstanceProperties($newContactInstance, $contactInstanceInfo);

                    $found[] = $newContactInstance;
                    $this->createdObjects['contactInstances'][$identifier] = $newContactInstance;
                    $this->addressRepository->add($newContactInstance);
                } else {
                    // update it for if it changes
                    $this->setContactInstanceProperties($existingContactInstance, $contactInstanceInfo);

                    $found[] = $existingContactInstance;
                    $this->addressRepository->update($existingContactInstance);
                }
            }
        }
        return $found;
    }

    /**
     * @param TtAddress &$contactInstance
     * @param array $contactInstanceInfo
     *
     * @return void
     */
    protected function setContactInstanceProperties(TtAddress &$contactInstance, array $contactInstanceInfo)
    {
        $contactInstance->setCountry($contactInstanceInfo['country']);
        $contactInstance->setName($contactInstanceInfo['name']);
        $contactInstance->setPhone($contactInstanceInfo['phone']);
        $contactInstance->setFax($contactInstanceInfo['fax']);
        $contactInstance->setEmail($contactInstanceInfo['email']);
        $contactInstance->setWww($contactInstanceInfo['www']);
        $contactInstance->setDescription($contactInstanceInfo['description']);
        $contactInstance->setAddress(
            $this->composeContactInstanceAddress(
                $contactInstanceInfo['visitorAddress_street'],
                $contactInstanceInfo['visitorAddress_houseNumber']
            )
        );
        $contactInstance->setCity($contactInstanceInfo['visitorAddress_city']);
        $contactInstance->setZip($contactInstanceInfo['visitorAddress_postCode']);
        $contactInstance->setTxNcgovpdcPostAddress(
            $this->composeContactInstanceAddress(
                $contactInstanceInfo['postAddress_street'],
                $contactInstanceInfo['postAddress_houseNumber']
            )
        );
        $contactInstance->setTxNcgovpdcPostCity($contactInstanceInfo['postAddress_city']);
        $contactInstance->setTxNcgovpdcPostZip($contactInstanceInfo['postAddress_postCode']);
        $contactInstance->setTxNcgovpdcPostPOBox($contactInstanceInfo['postAddress_POBox']);
    }

    /**
     * Compose Street and House Number field
     *
     * @param string $streetName
     * @param string $houseNumber
     * @return string
     */
    protected function composeContactInstanceAddress($streetName, $houseNumber)
    {
        $address = '';

        if ($streetName) {
            $address .= $streetName;
            $address .= $houseNumber ? ' ' . $houseNumber : '';
        }
        return $address;
    }

    /**
     * Tests whether the object should be deleted (from the intermediate object)
     * @param IntermediateObject $intermediateObject
     * @param FrequentlyAskedQuestion $localObject
     * @return boolean
     */
    public function shouldObjectBeDeleted(IntermediateObject $intermediateObject, $localObject = null)
    {
        if ($intermediateObject->getData('deleted') == '1') {
            return true;
        }
        return false;
    }

    /**
     * Actually deletes object, if shouldObjectBeDeleted is true
     * @param FrequentlyAskedQuestion $localObject
     * @param IntermediateObject $intermediateObject
     */
    public function deleteObject($localObject, IntermediateObject $intermediateObject)
    {
        $this->log('Deleting', $localObject->getOwmsCoreIdentifier());
        $this->deleteCount++;
        $this->faqRepository->remove($localObject);
    }
}

