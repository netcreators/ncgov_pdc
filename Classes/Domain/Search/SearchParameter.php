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
class SearchParameter
{
    /**
     * @var string
     */
    protected $search;

    /**
     * @var array
     */
    protected $searchWords = array();

    /**
     * @var integer
     */
    protected $pageNumber = 0;

    /**
     * @var integer
     */
    protected $numberOfResultPages = 0;

    /**
     * @var boolean
     */
    protected $includeRemoteProducts = false;

    /**
     * @var boolean
     */
    protected $matchExactPhrase = false;

    /*
     * @var boolean
     */
    protected $includePrivateResults = false;

    /**
     * @var boolean
     */
    protected $includeBusinessResults = false;

    /**
     * Sets the search query string
     * @param    $search
     * @return    self        for chaining
     */
    public function setSearchQuery($search)
    {
        $this->search = trim($search);
        return $this;
    }

    /**
     * Returns the search query
     * @return string the search query
     */
    public function getSearchQuery()
    {
        return $this->search;
    }

    /**
     * Checks if the search query is empty.
     * @return    boolean    TRUE if the search is empty
     */
    public function searchIsEmpty()
    {
        return empty($this->search);
    }

    /**
     * Sets the words for the search query
     * @param    array $words Search words
     * @return self                for chaining
     */
    public function setSearchWords(array $words)
    {
        $this->searchWords = $words;
        return $this;
    }

    /**
     * Returns the search words.
     * @return    array
     */
    public function getSearchWords()
    {
        return $this->searchWords;
    }

    /**
     * Sets the last result page number
     * @param    integer $numberOfResultPages The page number of the last result page
     * @return    self                            for chaining
     */
    public function setNumberOfResultPages($numberOfResultPages)
    {
        $this->numberOfResultPages = $numberOfResultPages;
        return $this;
    }

    /**
     * Sets the current page number
     * @param    integer $pageNumber the current page number
     * @return    self                for chaining
     */
    public function setPageNumber($pageNumber)
    {
        if ($pageNumber < 0) {
            $pageNumber = 0;
        }
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * Page number
     * @return    integer    the current page number
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * Returns the following page number
     * @return    integer
     */
    public function getNextPageNumber()
    {
        return $this->pageNumber + 1;
    }

    /**
     * Sets if the search should include remote results
     * @param    boolean $includeRemoteProducts
     * @return    self                                for chaining
     */
    public function setIncludeRemoteProducts($includeRemoteProducts)
    {
        $this->includeRemoteProducts = $includeRemoteProducts;
        return $this;
    }

    /**
     * Returns if the search should include remote results
     * @return    boolean
     */
    public function getIncludeRemoteProducts()
    {
        return $this->includeRemoteProducts;
    }

    /**
     * Sets the matchExactPhrase state
     * @param    boolean $matchExactPhrase find all words as they are?
     * @return    self                    for chaining
     */
    public function setMatchExactPhrase($matchExactPhrase)
    {
        $this->matchExactPhrase = $matchExactPhrase;
        return $this;
    }

    /**
     * Returns the matchExactPhrase state
     * @return    boolean
     */
    public function getMatchExactPhrase()
    {
        return $this->matchExactPhrase;
    }

    /**
     * Sets if the search should include private and / or business results.
     * @param    boolean $private private results?
     * @param    boolean $business business results?
     * @return    self                for chaining
     */
    public function setAudience($private, $business)
    {
        $this->includePrivateResults = $private;
        $this->includeBusinessResults = $business;
        return $this;
    }

    /**
     * Sets if the query should include business results
     * @param    boolean $includeBusinessResults include business results?
     * @return self                            for chaining
     */
    public function setIncludeBusinessResults($includeBusinessResults)
    {
        $this->includeBusinessResults = $includeBusinessResults;
        return $this;
    }

    /**
     * Sets if the query should include business results
     * @param    boolean $includePrivateResults include private results?
     * @return self                                for chaining
     */
    public function setIncludePrivateResults($includePrivateResults)
    {
        $this->includePrivateResults = $includePrivateResults;
        return $this;
    }

    /**
     * Returns if private results should be included
     * @return    boolean    TRUE if search should include private results
     */
    public function includePrivateResults()
    {
        $result = $this->includePrivateResults;
        if ($this->searchIsEmpty()) {
            $result = true;
        }
        return $result;
    }

    /**
     * Returns if business results should be included in search results
     * @return    boolean    TRUE if search should include business search results
     */
    public function includeBusinessResults()
    {
        $result = $this->includeBusinessResults;
        if ($this->searchIsEmpty()) {
            $result = true;
        }
        return $result;
    }

    /**
     * Returns properties of this class
     * @return array
     */
    public function getProperties()
    {
        return get_class_vars(__CLASS__);
    }

    /**
     * Returns the value of the given property
     * @param    string $name the property name
     * @return    mixed
     */
    public function getProperty($name)
    {
        $result = null;
        if (!empty($name) && isset($this->$name)) {
            $result = $this->$name;
        }
        return $result;
    }

    /**
     * Returns the current parameter as xml string
     * @return string
     */
    public function toXml()
    {
        $xml =
            '<searchParameter>
                <matchExactPhrase>' . (int)$this->getMatchExactPhrase() . '</matchExactPhrase>
				<includeRemoteProducts>' . (int)$this->getIncludeRemoteProducts() . '</includeRemoteProducts>
				<search>' . $this->getSearchQuery() . '</search>
				<includeBusinessResults>' . (int)$this->includePrivateResults() . '</includeBusinessResults>
				<includePrivateResults>' . (int)$this->includePrivateResults() . '</includePrivateResults>
				<pageNumber>' . (int)$this->getPageNumber() . '</pageNumber>
			</searchParameter>';

        return $xml;
    }

}


