<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Leonie Philine Bitto [Netcreators] <leonie@netcreators.nl>
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

namespace Netcreators\NcgovPdc\Domain\Repository\SamenwerkendeCatalogi40;

use Netcreators\NcgovPdc\Domain\Search\SearchParameter;
use Netcreators\NcgovPdc\Service\Search\SamenwerkendeCatalogi40\QueryBuilder;
use Netcreators\NcgovPdc\Service\Search\SamenwerkendeCatalogi40\Request;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Repository
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Repository
 */
class RemoteProductRepository
{

    /**
     * @var \Netcreators\NcgovPdc\Configuration\ConfigurationManager
     * @inject
     */
    protected $localConfigurationManager = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    /**
     * Returns the products found by Samenwerkende Catalogi 4.0 search
     * @see http://www.logius.nl/producten/toegang/samenwerkende-catalogi/
     * @param SearchParameter $parameter
     * @param integer $startRecordIndex
     * @return array
     */
    public function findBySearch(SearchParameter $parameter, $startRecordIndex = 1)
    {

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->objectManager->get(
            QueryBuilder::class
        );
        $queryBuilder->injectConfigurationManager($this->localConfigurationManager);
        $query = $queryBuilder->build($parameter);

        /** @var Request $request */
        $request = $this->objectManager->get(
            Request::class
        );
        $request->setStartRecordIndex($startRecordIndex);
        $request->setMaximumRecords(
            (int)$this->localConfigurationManager->get(
                'controllers.FrequentlyAskedQuestion.actions.find.maxSamenwerkendeCatalogiResultCount'
            )
        );

        /** @var \Netcreators\NcgovPdc\Service\Search\SamenwerkendeCatalogi40\Response $response */
        $response = $request->send($query);

        try {
            $remoteProducts = $response->getRemoteProducts();
        } catch (\Netcreators\NcgovPdc\Service\Exception $e) {
            GeneralUtility::devLog($e->getMessage(), 'ncgov_pdc', GeneralUtility::SYSLOG_SEVERITY_ERROR);

            // Fail silently.
            return array();
        }

        return $remoteProducts;

    }
}

