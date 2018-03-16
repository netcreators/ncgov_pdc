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

namespace Netcreators\NcgovPdc\Domain\Repository;

use Netcreators\NcgovPdc\Domain\Model\Exception\NoSearchSpecifiedException;
use Netcreators\NcgovPdc\Domain\Model\Exception\SearchableColumnsNotSetException;

/**
 * Repository
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Model
 */
class FrequentlyAskedQuestionChannelRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array    the columns which will be searched
     */
    protected $searchableColumns = array();

    /**
     * Actually performs the frequently asked question search.
     *
     * @param array $searchWords
     * @throws NoSearchSpecifiedException
     * @throws SearchableColumnsNotSetException
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findBySearch(array $searchWords = array())
    {
        if (!(count($this->searchableColumns) > 0)) {
            throw new SearchableColumnsNotSetException();
        }
        if (!$searchWords) {
            throw new NoSearchSpecifiedException();
        }
        $searchWords[] = implode('%', $searchWords);

        // creating query
        $query = $this->createQuery();
        $matches = false;
        $wordMatches = false;

        foreach ($searchWords as $search) {
            foreach ($this->searchableColumns as $column) {
                $match = $query->like($column, '%' . $search . '%');
                if ($matches === false) {
                    $matches = $match;
                } else {
                    $matches = $query->logicalOr(
                        $match,
                        $matches
                    );
                }
            }
            if ($wordMatches === false) {
                $wordMatches = $matches;
                $matches = false;
            } else {
                $wordMatches = $query->logicalAnd(
                    $wordMatches,
                    $matches
                );
            }
        }

        return $query->matching($wordMatches)->execute();
    }

    /**
     * Sets the columns which can be searched.
     *
     * @param array $columns
     * @return void
     */
    public function setSearchableColumns(array $columns)
    {
        $this->searchableColumns = $columns;
    }
}

