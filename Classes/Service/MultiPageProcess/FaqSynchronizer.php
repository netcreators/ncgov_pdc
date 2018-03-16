<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2010 Frans van der Veen
 *  (c) 2014-2015 Leonie Philine Bitto [Netcreators] <extensions@netcreators.com>
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

namespace Netcreators\NcgovPdc\Service\MultiPageProcess;

use Netcreators\NcExtbaseLib\Domain\Model\IntermediateObject;
use Netcreators\NcExtbaseLib\Domain\Model\LastTime;
use Netcreators\NcExtbaseLib\Service\MultiPageProcess\AbstractSynchronizer;
use Netcreators\NcExtbaseLib\Service\MultiPageProcess\Exception\SafetyException;
use Netcreators\NcExtbaseLib\Service\Object\Synchronization\ObjectSynchronizationController;
use Netcreators\NcExtbaseLib\Service\Web\File\RestRequestDownloader;
use Netcreators\NcExtbaseLib\Service\Web\Rest\RestClient;
use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;
use Netcreators\NcgovPdc\Service\File\Record\Converter\XmlFaqConverter;
use Netcreators\NcgovPdc\Service\Object\Synchronization\FaqObjectSynchronizer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * FaqSynchronizer
 *
 * @author Frans van der Veen
 * @author Leonie Philine Bitto <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcExtbaseLib
 * @subpackage Service
 */
class FaqSynchronizer extends AbstractSynchronizer
{

    /**
     * @var boolean
     */
    protected $useAuthentication = false;
    /**
     * @var string
     */
    protected $username = '';
    /**
     * @var string
     */
    protected $password = '';
    /**
     * @var boolean
     */
    protected $isPartialImport;
    /**
     * @var boolean
     */
    protected $useCurl = false;
    /**
     * @var integer
     */
    protected $faqDetailViewPageId = 0;

    /**
     * @var \Netcreators\NcExtbaseLib\Domain\Repository\LastTimeRepository
     * @inject
     */
    protected $lastTimeRepository;
    /**
     * @var boolean
     */
    protected $lastTimeIsNew = false;
    /**
     * @var LastTime
     */
    protected $lastTime = null;
    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\ReferenceLinkRepository
     * @inject
     */
    protected $referenceLinkRepository = null;
    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\FrequentlyAskedQuestionRepository
     * @inject
     */
    protected $faqRepository = null;
    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\ProductRepository
     * @inject
     */
    protected $productRepository = null;
    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\TtAddressRepository
     * @inject
     */
    protected $addressRepository = null;

    const LAST_TIME_KEY = 'pdcfaq';

    const FAQ_INTERMEDIATE_KEY = 'PdcFaq';

    /**
     * Initializes the process
     * @see \Netcreators\NcExtbaseLib\Service\MultiPageProcess\MultiPageProcessInterface::initialize()
     */
    public function initialize()
    {
    }

    /**
     * Sets if to use HTTP authentication to download the XML feed
     * @param boolean $value
     * @return self
     */
    public function setUseAuthentication($value)
    {
        $this->useAuthentication = $value;
        return $this;
    }

    /**
     * Sets the username
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Sets the password
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Sets isPartialImport
     * @param array $value state
     */
    public function setIsPartialImport($value)
    {
        $this->isPartialImport = (boolean)$value;
    }

    /**
     * Sets the faq page id
     * @param integer $pageId
     * @return self
     */
    public function setFaqDetailViewPageId($pageId)
    {
        $this->faqDetailViewPageId = $pageId;
        return $this;
    }

    /**
     * Sets if to use CURL to download the file
     * @param string $value
     * @return self
     */
    public function setUseCurl($value)
    {
        $this->useCurl = $value;
        return $this;
    }

    /**
     * Sets the current time that the task is started
     */
    public function setLastUpdatedTimeStartToNow()
    {
        if (!$this->lastTime) {
            $this->getLastUpdateTime();
        }
        if ($this->lastTime != null) {
            $this->lastTime->setStartTime(time());
            if (!$this->lastTimeIsNew) {
                $this->lastTimeRepository->update($this->lastTime);
            }
        }
    }

    /**
     * Gets the last update time
     * @return integer the last updated time (timestamp)
     */
    public function getLastUpdateTime()
    {
        if (!$this->lastTime) {
            $this->lastTime = $this->lastTimeRepository->findOneByName(self::LAST_TIME_KEY);
        }
        if ($this->lastTime != null) {
            $result = $this->lastTime->getLastUpdated();
        } else {
            /** @var LastTime lastTime */
            $this->lastTime = $this->objectManager->get(LastTime::class);
            $this->lastTime->setName(self::LAST_TIME_KEY);
            $this->lastTime->setLastUpdated(time());
            $this->lastTime->setStartTime(time());
            $this->lastTimeIsNew = true;
            $this->lastTimeRepository->add($this->lastTime);
            $result = 0;
        }
        return $result;
    }

    /**
     * Sets the last updated time to the start time of this sync
     */
    public function setLastUpdatedTimeToStartTime()
    {
        if (!$this->lastTime) {
            $this->getLastUpdateTime();
        }
        if ($this->lastTime != null) {
            $this->lastTime->setLastUpdated($this->lastTime->getStartTime());
            if (!$this->lastTimeIsNew) {
                $this->lastTimeRepository->update($this->lastTime);
            }
        }
    }

    /**
     * Use corresponding download method to retrieve feed data
     * @return void
     */
    public function downloadFeed()
    {

        $this->setLastUpdatedTimeStartToNow();

        // Download file using authentication
        if ($this->useAuthentication) {

            /** @var RestClient $restClient */
            $restClient = $this->objectManager->get(RestClient::class);

            $data = array(
                'username' => $this->username,
                'password' => $this->password,
            );
            if ($this->isPartialImport) {
                $data['date'] = date('Y-m-d H:i:s', $this->getLastUpdateTime());
            }

            $restClient->setApiUrl($this->sourceFeedUrl);
            $restClient->setContentType('');

            // HTTP Basic Authentication for SDU
            $restClient->setUsername($this->username);
            $restClient->setPassword($this->password);

            // POST data authentication for APlus
            $restClient->setRequestData($data);

            // Diverting from use of default file downloader (\Netcreators\NcExtbaseLib\Service\Web\File\Downloader) for use of REST client.
            /** @var RestRequestDownloader fileDownloader */
            $this->fileDownloader = $this->objectManager->get(
                RestRequestDownloader::class
            );
            $this->fileDownloader->setRestRequestClient($restClient);

            $this->logRepository->log(
                'Authenticating FAQ feed file from \'' . $this->sourceFeedUrl
                . '\' to \'' . $this->tempFeedFileName . (
                $this->isPartialImport ? '\', requesting FAQs modified after \'' . $data['date'] : ''
                ) . '\'...'
            );

            $this->fileDownloader->downloadFile($this->sourceFeedUrl, $this->tempFeedFileName);

        } else {

            // Plain file download without authentication using \Netcreators\NcExtbaseLib\Service\Web\File\Downloader (@see AbstractSynchronizer).
            $this->logRepository->log(
                'Downloading FAQ feed file from \'' . $this->sourceFeedUrl . '\' to \'' . $this->tempFeedFileName . '\'...'
            );
            $this->fileDownloader->downloadFile($this->sourceFeedUrl, $this->tempFeedFileName, null, $this->useCurl);
        }
    }

    /**
     * This page downloads the file
     * @throws Exception\NoFilePresentException
     * @throws Exception\DownloadFailedException
     * @pageOrder 1
     * @return void
     */
    public function pageDownloadFaqFile()
    {

        if ($this->skipFeedDownload) {
            $this->logRepository->log('Configured to skip faq feed downloading.');

            if (!$this->tempFeedFileName || !file_exists($this->tempFeedFileName)) {
                $this->isFinished = true;
                $this->pageIsFinished = true;
                // nothing to process
                throw new Exception\NoFilePresentException();
            }

            $this->pageIsFinished = true;
            $this->logRepository->log('Local file ' . $this->tempFeedFileName . ' present.');

        } else {
            $this->downloadFeed();

            if (!$this->tempFeedFileName || !file_exists($this->tempFeedFileName)) {
                $this->isFinished = true;
                throw new Exception\DownloadFailedException();
            }

            $this->pageIsFinished = true;
            $this->logRepository->log('Downloaded ok.');
        }
    }

    /**
     * Creates intermediate records
     * @pageOrder 2
     * @throws SafetyException
     * @return void
     */
    public function pageParseAndCreateFaqIntermediates()
    {
        $index = $this->getResumeIndex();
        $this->logRepository->log('Creating FAQ intermediates, starting at index ' . $index . '.');

        /** @var XmlFaqConverter $converter */
        $converter = $this->objectManager->get(XmlFaqConverter::class);

        $this->logRepository->log('Parsing FAQ file...');
        $xmlFeedInfo = stat($this->tempFeedFileName);
        if ($xmlFeedInfo['size'] <= 39) {
            // only contains xml header, we can quit the whole process
            $this->logRepository->log('Stopping process since there is no new partial data since last sync.');
            if (!$this->isPartialImport) {
                $this->logRepository->log('ERROR: This is supposed to be a full sync, this is wrong!');
            }
            $this->pageIsFinished = true;
            $this->isFinished = true;
        } else {
            $converter->processFile($this->tempFeedFileName);
            $totalIntermediateObjectCount = $converter->getObjectCount();
            $this->logRepository->log('Reading & converting FAQ objects...');

            $converter->setExcludedItems($this->excludedItems);
            $converter->setIsPartialImport($this->isPartialImport);

            $objects = $converter->getConvertedObjects($index, $this->maximumNumberOfRecordsToProcessLimit);
            $this->logRepository->log('Storing ' . count($objects) . ' FAQ objects...');

            if (!$this->isPartialImport && ($totalIntermediateObjectCount < $this->minimumNumberOfRecordsSafetyLimit)) {
                throw new SafetyException(
                    "For non-partial imports, at least " . $this->minimumNumberOfRecordsSafetyLimit . " intermediate objects need to be present. "
                    . "In the current import, only " . $totalIntermediateObjectCount . " intermediate objects were counted. (@see TS:settings.import.minimumNumberOfRecordsSafetyLimit.)"
                );
            }
            foreach ($objects as $object) {
                $this->intermediateObjectRepository->add($object);
            }
            if ($index + $this->maximumNumberOfRecordsToProcessLimit < $totalIntermediateObjectCount) {
                $this->setResumeIndex($index + $this->maximumNumberOfRecordsToProcessLimit);
            } else {
                $this->pageIsFinished = true;
            }
        }
    }

    /**
     * Processes the intermediate data
     * @pageOrder 3
     * @throws SafetyException
     * @return void
     */
    public function pageProcessFaqIntermediates()
    {

        $index = $this->getResumeIndex();
        $this->logRepository->log('Processing FAQ intermediates, starting at index ' . $index . '.');

        $totalIntermediateObjectCount = $this->intermediateObjectRepository->countAllForType(
            self::FAQ_INTERMEDIATE_KEY
        );
        $intermediateObjects = $this->intermediateObjectRepository->findAllForTypeWithOffsetAndLimit(
            self::FAQ_INTERMEDIATE_KEY,
            $index,
            $this->maximumNumberOfRecordsToProcessLimit
        );
        $this->logRepository->log($totalIntermediateObjectCount . ' FAQ intermediates found.');

        /** @var ObjectSynchronizationController $controller */
        $controller = $this->objectManager->get(ObjectSynchronizationController::class);

        /** @var FaqObjectSynchronizer $faqSynchronizer */
        $faqSynchronizer = $this->objectManager->get(FaqObjectSynchronizer::class);

        $faqSynchronizer->setForceFullUpdate($this->forceFullUpdate);
        $controller->setSynchronizer($faqSynchronizer);
        if (!$this->isPartialImport && ($totalIntermediateObjectCount < $this->minimumNumberOfRecordsSafetyLimit)) {
            throw new SafetyException(
                "For non-partial imports, at least " . $this->minimumNumberOfRecordsSafetyLimit . " intermediate objects need to be present. "
                . "In the current import, only " . $totalIntermediateObjectCount . " intermediate objects were counted. (@see TS:settings.import.minimumNumberOfRecordsSafetyLimit.)"
            );
        }
        /** @var IntermediateObject $intermediate */
        foreach ($intermediateObjects as $intermediate) {
            try {
                $controller->synchronize($intermediate);
            } catch (Exception $e) {
                $this->logRepository->log(
                    'Problem synchronizing FAQ:'
                    . chr(10) . get_class($e)
                    . chr(10) . $e->getMessage()
                    . chr(10) . $intermediate // calling __toString().
                );
            }
        }
        $this->logRepository->log(
            'Created ' . $faqSynchronizer->getCreateCount() . ' FAQs, updated '
            . $faqSynchronizer->getUpdateCount() . ' FAQs, touched '
            . $faqSynchronizer->getTouchCount() . ' FAQs, deleted '
            . $faqSynchronizer->getDeleteCount() . ' FAQs...'
        );
        if ($index + $this->maximumNumberOfRecordsToProcessLimit < $totalIntermediateObjectCount) {
            $this->setResumeIndex($index + $this->maximumNumberOfRecordsToProcessLimit);
        } else {
            $this->pageIsFinished = true;
        }
    }

    /**
     * Cleaning up old stuff
     * @pageOrder 4
     * @return void
     */
    public function pageFaqCleanUp()
    {

        $this->logRepository->log('Cleaning FAQ intermediates...');
        $this->intermediateObjectRepository->removeAllForType(self::FAQ_INTERMEDIATE_KEY);

        if ($this->isPartialImport) {
            $this->logRepository->log('FAQs imported in previous sessions are kept, since this is a partial import.');
            $this->referenceLinkRepository->removeZombieImportedReferenceLinks();
            $this->pageIsFinished = true;
            return;
        }

        $this->logRepository->log('Cleaning FAQs which were not imported in the current session...');
        $faqsToRemove = $this->faqRepository->findByImportedAndNotInSessionWithLimit(
            $this->logRepository->getSessionId(),
            0,
            $this->maximumNumberOfRecordsToProcessLimit
        );

        $removeCount = 0;
        /** @var FrequentlyAskedQuestion $faq */
        foreach ($faqsToRemove as $faq) {
            $removeCount++;
            $this->logRepository->log(
                'Removing FAQ [TYPO3 UID: ' . $faq->getUid() . ', OWMS core ID: ' . $faq->getOwmsCoreIdentifier(
                ) . ']: ' . $faq->getOwmsCoreTitle() . '.'
            );
            $this->faqRepository->remove($faq);
        }
        $this->logRepository->log('Removed ' . $removeCount . ' FAQs.');

        if (count($faqsToRemove) == $this->maximumNumberOfRecordsToProcessLimit) {
            $index = $this->getResumeIndex();
            $this->setResumeIndex($index + 1);
        } else {
            $this->referenceLinkRepository->removeZombieImportedReferenceLinks();
            $this->pageIsFinished = true;
        }
    }

    /**
     * Cleaning cache
     * @pageOrder 5
     * @return void
     */
    public function pageClearCache()
    {
        $this->clearCacheForConfiguredPages();
        $this->pageIsFinished = true;
    }

    /**
     * Generate RealURL cHashes for all records, so manually generated links will work :)
     * @pageOrder 6
     * @return void
     */
    public function pageGenerateUrls()
    {

        /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject */
        $contentObject = $this->objectManager->get('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

        $index = $this->getResumeIndex();
        $this->logRepository->log('Generating FAQ links, starting with index ' . $index . '.');
        $faqRecords = $this->faqRepository->findAllWithOffsetAndLimit(
            $index,
            $this->maximumNumberOfRecordsToProcessLimit
        );
        $faqCount = count($faqRecords);
        if ($faqCount > 0) {
            /** @var FrequentlyAskedQuestion $faq */
            foreach ($faqRecords as $faq) {
                $configuration = array(
                    'parameter' => $this->faqDetailViewPageId,
                    'additionalParams' => '&tx_ncgovpdc_pi[frequentlyAskedQuestion]=' . $faq->getUid(),
                    'useCacheHash' => true,
                    'forceAbsoluteUrl' => true,
                );
                $contentObject->typoLink_URL($configuration);
            }
        }
        if ($faqCount == $this->maximumNumberOfRecordsToProcessLimit) {
            $this->setResumeIndex($index + $this->maximumNumberOfRecordsToProcessLimit);
        } else {
            $this->setLastUpdatedTimeToStartTime();
            $this->pageIsFinished = true;
            $this->isFinished = true;
        }
    }
}

