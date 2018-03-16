<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Leonie Philine Bitto [Netcreators] <extensions@netcreators.com>
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

namespace Netcreators\NcgovPdc\Controller;

use Netcreators\NcExtbaseLib\Controller\BaseSynchronizerModuleController;
use Netcreators\NcgovPdc\Service\MultiPageProcess\FaqSynchronizer;
use Netcreators\NcgovPdc\Service\Task\SynchronizationOnDemandOrIntervalTask;

/**
 * Module Controller, allowing status info, log display, reset and force start of FAQ Synchronizer.
 *
 * @author Leonie Philine Bitto <extensions@netcreators.nl>
 * @package NcgovPdc
 * @subpackage Controller
 */
class FrequentlyAskedQuestionSynchronizerModuleController extends BaseSynchronizerModuleController
{

    protected $synchronizerName = 'Antwoord';

    /**
     * @return string
     */
    public function getSequencerTaskName()
    {
        return SynchronizationOnDemandOrIntervalTask::class;
    }

    /**
     * @return array
     */
    public function getDefaultSynchronizerConfiguration()
    {
        return array(
            '10' => array(
                FaqSynchronizer::class => array(

                    /**
                     * Default parameter properties of
                     * @see \Netcreators\NcExtbaseLib\Service\MultiPageProcess\AbstractSynchronizer
                     */
                    'minimumNumberOfRecordsSafetyLimit' => 10,
                    'maximumNumberOfRecordsToProcessLimit' => 150,
                    'excludedItems' => '',
                    'skipFeedDownload' => false,
                    'sourceFeedUrl' => false,
                    'tempFeedFileName' => 'typo3temp/FaqImport.xml',
                    'pagesToClearCacheFor' => '',
                    'forceFullUpdate' => false,
                    'storagePid' => null, // Set this to override module.tx_[extKeyShort].persistence.storagePid

                    /**
                     * FrequentlyAskedQuestion-Synchronizer-specific parameter properties of
                     * @see \Netcreators\NcgovPdc\Service\MultiPageProcess\FaqSynchronizer
                     */
                    'useCurl' => true, // Note: If using Authentication, cUrl is used regardless of this option.
                    'useAuthentication' => false,
                    'username' => '',
                    'password' => '',
                    'isPartialImport' => false,
                    'faqDetailViewPageId' => 0,
                ),
            ),
        );
    }

}

