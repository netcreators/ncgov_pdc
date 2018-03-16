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

namespace Netcreators\NcgovPdc\Service\Search\SamenwerkendeCatalogi40;

use Netcreators\NcExtbaseLib\Service\Web\Rest\RestClient;
use Netcreators\NcgovPdc\Domain\Search\SamenwerkendeCatalogi40\Query;

/**
 * Repository
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Service
 */
class Request
{

    const SRU_SERVICE_URL = 'http://zoekdienst.overheid.nl/SRUServices/SRUServices.asmx/Search?version=1.2&operation=searchRetrieve&x-connection=sc&recordSchema=sc4.0';

    /**
     * @var \Netcreators\NcExtbaseLib\Domain\Repository\LogRepository
     * @inject
     */
    protected $logRepository = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    /**
     * @var int
     */
    protected $startRecordIndex = 1;

    /**
     * @var int
     */
    protected $maximumRecords = 10;

    /**
     * @param    integer $startRecordIndex
     * @return    self
     */
    public function setStartRecordIndex($startRecordIndex)
    {
        $this->startRecordIndex = $startRecordIndex;
        return $this;
    }

    /**
     * @param    integer $maximumRecords
     * @return    self
     */
    public function setMaximumRecords($maximumRecords)
    {
        $this->maximumRecords = $maximumRecords;
        return $this;
    }

    /**
     * Sends the CQL search query to the Samenwerkende Catalogi 4.0 SRU remote product search and receives the response
     * @param \Netcreators\NcgovPdc\Domain\Search\SamenwerkendeCatalogi40\Query $query
     * @return Response
     */
    public function send(Query $query)
    {

        $requestURL = self::SRU_SERVICE_URL
            . '&startRecord=' . $this->startRecordIndex
            . '&maximumRecords=' . $this->maximumRecords
            . '&query=' . urlencode((string)$query);

        /** @var RestClient $restClient */
        $restClient = $this->objectManager->get(RestClient::class);

        $restClient->setApiUrl($requestURL)
            // \Netcreators\NcExtbaseLib\Service\Web\Rest\RestClient should really support GET requests...
            ->setRequestData(array('foo' => 'bar'));

        /** @var Response $response */
        $response = $this->objectManager->get(Response::class);
        $response->setClient($restClient);

        return $response;
    }
}

