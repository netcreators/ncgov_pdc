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

/**
 * Repository
 *
 * @author Frans van der Veen <extensions@netcreators.com>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Model
 */
class KeywordRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Finds an object matching the given keyword.
     *
     * @param string $keyword The keyword to search for
     * @throws \InvalidArgumentException
     * @return \Netcreators\NcgovPdc\Domain\Model\Keyword The matching object if found, otherwise NULL
     */
    public function findOneByKeyword($keyword)
    {
        if (empty($keyword)) {
            throw new \InvalidArgumentException('The keyword must be a non-empty string', 1245071889);
        }
        $query = $this->createQuery();
        //$query->getQuerySettings()->setRespectStoragePage(false);
        return $query->matching($query->equals('keyword', $keyword))->execute()->getFirst();
    }

}

