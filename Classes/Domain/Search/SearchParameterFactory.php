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

namespace Netcreators\NcgovPdc\Domain\Search;

/**
 * Repository
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Model
 */
class SearchParameterFactory
{
    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Request
     */
    protected $request;

    /**
     * @var \Netcreators\NcgovPdc\Configuration\ConfigurationManager
     * @inject
     */
    protected $localConfigurationManager;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    /**
     * Sets the current web request
     * @param \TYPO3\CMS\Extbase\Mvc\Request $request
     * @return self for chaining
     */
    public function setRequest(\TYPO3\CMS\Extbase\Mvc\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Creates search parameter
     * @return SearchParameter the parameter
     */
    public function build()
    {
        /** @var SearchParameter $parameter */
        $parameter = $this->objectManager->get(SearchParameter::class);

        $parameter->setNumberOfResultPages(
            $this->localConfigurationManager->get(
                'controllers.FrequentlyAskedQuestion.actions.find.maxResultPagesCount'
            )
        );
        if ($this->request->hasArgument('search')) {
            $parameter->setSearchQuery($this->sanitizeSearchQuery(urldecode($this->request->getArgument('search'))));
        }
        if ($parameter->searchIsEmpty()) {
            // steal sword from other search form
            $searchInPost = $this->localConfigurationManager->get(
                'controllers.FrequentlyAskedQuestion.actions.find.otherSearchFormMethodIsPost'
            );
            if ($searchInPost) {
                $searchArray = $_POST;
            } else {
                $searchArray = $_GET;
            }
            $search = \TYPO3\CMS\Extbase\Utility\ArrayUtility::getValueByPath(
                $searchArray,
                \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(
                    '.',
                    $this->localConfigurationManager->get(
                        'controllers.FrequentlyAskedQuestion.actions.find.otherSearchFormElement'
                    )
                )
            );
            $parameter->setSearchQuery($this->sanitizeSearchQuery($search));
        }
        if ($this->request->hasArgument('pageNumber')) {
            $parameter->setPageNumber((int)$this->request->getArgument('pageNumber'));
        }

        $this->setSearchOption($parameter, 'matchExactPhrase');
        $this->setSearchOption($parameter, 'includePrivateResults');
        $this->setSearchOption($parameter, 'includeBusinessResults');
        $this->setSearchOption($parameter, 'includeRemoteProducts');

        $parameter->setSearchWords(
            $this->getSearchWords($parameter->getMatchExactPhrase(), $parameter->getSearchQuery())
        );
        return $parameter;
    }

    /**
     * Sets a search option from request (if the option is a request argument) or TypoScript default
     *
     * @param SearchParameter $parameter One of matchExactPhrase, includePrivateResults, includeBusinessResults, includeRemoteProducts
     * @param string $searchOptionName
     * @throws \InvalidArgumentException
     */
    protected function setSearchOption(
        SearchParameter $parameter,
        $searchOptionName
    ) {

        $configurationKey = 'controllers.FrequentlyAskedQuestion.actions.find.searchOptions.defaultValues.' . $searchOptionName;
        $setterMethodName = 'set' . ucfirst($searchOptionName);

        if (!$this->localConfigurationManager->exists($configurationKey) || !method_exists(
                $parameter,
                $setterMethodName
            )
        ) {
            throw new \InvalidArgumentException('Invalid parameter $searchOptionName: \'' . $searchOptionName . '\'');
        }

        if ($this->request->hasArgument($searchOptionName)) {
            $parameter->$setterMethodName((boolean)$this->request->getArgument($searchOptionName));
        } else {
            if ($parameter->searchIsEmpty()) {
                $parameter->$setterMethodName(
                    (boolean)intval($this->localConfigurationManager->get($configurationKey))
                );
            }
        }

    }

    /**
     * Returns the words in the search as an array.
     * @param boolean $matchExactPhrase If TRUE then the first entry of the words array will contain all the words.
     * @param string $search the search query
     * @return array the search words.
     */
    public function getSearchWords($matchExactPhrase, $search)
    {
        if ($matchExactPhrase) {
            $searchWords = array($search);
        } else {
            $search = $this->removeWordsNotRelevantForSearch($search);
            $searchWords = \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(' ', $search);
        }
        return $searchWords;
    }

    /**
     * Removes words which do not contribute to search results (eg the, in, what)
     * @param string $search the search string
     * @return string
     */
    private function removeWordsNotRelevantForSearch($search)
    {
        $words = \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(
            ',',
            $this->localConfigurationManager->get('wordsNotRelevantForSearch')
        );
        if (count($words) > 0) {
            foreach ($words as $word) {
                $search = preg_replace('/^' . $word . '\s/', '', $search);
                $search = preg_replace('/\s' . $word . '$/', '', $search);
                $search = preg_replace('/\s' . $word . '\s/', ' ', $search);
            }
        }
        return $search;
    }

    /**
     * Sanitizes search
     * @param    string $search
     * @return    string the sanitized search parameter
     */
    protected function sanitizeSearchQuery($search)
    {
        // prevent hacks
        $search = str_replace('\'', '"', $search);
        $search = addslashes($search);
        $search = str_replace('<', '', $search);
        $search = str_replace('>', '', $search);
        $search = str_replace('?', '', $search);
        return urldecode($search);
    }
}

