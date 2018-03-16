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

namespace Netcreators\NcgovPdc\Domain\Factory;

use Netcreators\NcgovPdc\Domain\Model\FrequentlyAskedQuestion;
use Netcreators\NcgovPdc\Domain\Model\Product;
use Netcreators\NcgovPdc\Domain\Model\RegistrationAction;
use Netcreators\NcgovPdc\Domain\Search\SearchParameter;

/**
 * Registration ActionFactory
 *
 * @package NcgovPdc
 * @subpackage Factory
 */
class RegistrationActionFactory implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager = null;

    /**
     * Creates a perform search registration step
     * @param SearchParameter $search
     * @return RegistrationAction
     */
    public function createSearchAction(SearchParameter $search)
    {
        /** @var RegistrationAction $action */
        $action = $this->objectManager->get(RegistrationAction::class);
        $action->setType(RegistrationAction::TYPE_SEARCH)
            ->setSearchParameter($search);
        return $action;
    }

    /**
     * Creates a view product registration step
     * @param Product $product
     * @return RegistrationAction
     */
    public function createViewProductAction(Product $product)
    {
        /** @var RegistrationAction $action */
        $action = $this->objectManager->get(RegistrationAction::class);
        $action->setType(RegistrationAction::TYPE_PRODUCT)
            ->setProduct($product);
        return $action;
    }

    /**
     * Creates a view faq registration step
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return RegistrationAction
     */
    public function createViewFrequentlyAskedQuestionAction(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        /** @var RegistrationAction $action */
        $action = $this->objectManager->get(RegistrationAction::class);
        $action->setType(RegistrationAction::TYPE_FREQUENTLYASKEDQUESTION)
            ->setFrequentlyAskedQuestion($frequentlyAskedQuestion);
        return $action;
    }

    /**
     * Creates a view faq for product registration step
     * @param Product $product
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return RegistrationAction
     */
    public function createViewFrequentlyAskedQuestionForProductAction(
        Product $product,
        FrequentlyAskedQuestion $frequentlyAskedQuestion
    ) {
        /** @var RegistrationAction $action */
        $action = $this->objectManager->get(RegistrationAction::class);
        $action->setType(RegistrationAction::TYPE_PRODUCT_FREQUENTLYASKEDQUESTION)
            ->setProduct($product)
            ->setFrequentlyAskedQuestion($frequentlyAskedQuestion);
        return $action;
    }

    /**
     * Creates a view faq for search registration step
     * @param SearchParameter $search
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return RegistrationAction
     */
    public function createViewFrequentlyAskedQuestionForSearchAction(
        SearchParameter $search,
        FrequentlyAskedQuestion $frequentlyAskedQuestion
    ) {
        /** @var RegistrationAction $action */
        $action = $this->objectManager->get(RegistrationAction::class);
        $action->setType(RegistrationAction::TYPE_SEARCH_FREQUENTLYASKEDQUESTION)
            ->setSearchParameter($search)
            ->setFrequentlyAskedQuestion($frequentlyAskedQuestion);
        return $action;
    }
}

