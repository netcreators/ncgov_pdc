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
class FrequentlyAskedQuestionFactory implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager = null;

    /**
     * Creates a frequently asked question, from owms standards. Assuming there is only one channel being used.
     * @param $imported
     * @param $owmsCoreIdentifier
     * @param $owmsCoreCreator
     * @param $owmsCoreCreatorScheme
     * @param $owmsCoreModified
     * @param $channel
     * @param $question
     * @param $shortAnswer
     * @param $answer
     * @param $authorizedAnswer
     * @return FrequentlyAskedQuestion    $question
     */
    public function createFrequentlyAskedQuestion(
        $imported,
        // meta
        $owmsCoreIdentifier,
        $owmsCoreCreator,
        $owmsCoreCreatorScheme,
        $owmsCoreModified,
        // question channel
        $channel,
        $question,
        $shortAnswer,
        $answer,
        $authorizedAnswer
    ) {
        /** @var FrequentlyAskedQuestion $faq */
        $faq = $this->objectManager->get(FrequentlyAskedQuestion::class);
        $faq->setImported($imported);
        $faq->setOwmsCoreIdentifier($owmsCoreIdentifier);
        $faq->setOwmsCoreTitle($question);
        $faq->setOwmsCoreModified($owmsCoreModified);

        /** @var FrequentlyAskedQuestionChannel $faqChannel */
        $faqChannel = $this->objectManager->get(FrequentlyAskedQuestionChannel::class);
        $faqChannel->addChannel($channel);
        $faqChannel->setQuestion($question);
        $faqChannel->setShortAnswer($shortAnswer);
        $faqChannel->setAnswer($answer);
        $faqChannel->setAuthorizedAnswer($authorizedAnswer);

        $faq->addFrequentlyAskedQuestionChannel($faqChannel);

        return $faq;
    }

}

