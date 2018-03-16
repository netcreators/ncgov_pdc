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
use Netcreators\NcgovPdc\Xml\Search\SamenwerkendeCatalogi40\ResponseParser;

/**
 * Repository
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Service
 */
class Response
{

    /**
     * @var RestClient
     */
    protected $client = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager = null;

    /**
     *
     * @param    RestClient $client
     * @return    $this
     */
    public function setClient(RestClient $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Returns a list of remote products
     * @throws \Netcreators\NcgovPdc\Service\Search\SamenwerkendeCatalogi40\Response\Exception
     * @throws \Netcreators\NcgovPdc\Service\Search\SamenwerkendeCatalogi40\Request\Exception
     * @return    array
     */
    public function getRemoteProducts()
    {
        if (!$this->client) {
            throw new \Netcreators\NcgovPdc\Service\Search\SamenwerkendeCatalogi40\Response\Exception('No REST client provided.');
        }

        $this->client->initializeRequest();
        $this->client->performRequest();

        if ($this->client->hasErrors()) {
            throw new \Netcreators\NcgovPdc\Service\Search\SamenwerkendeCatalogi40\Request\Exception(
                implode(',', $this->client->getErrors())
            );
        }

        /** @var ResponseParser $responseParser */
        $responseParser = $this->objectManager->get(ResponseParser::class);
        $responseParser->setData(
            $this->client->getResponse()
        );
        return $responseParser->getRemoteProducts();
    }
}

