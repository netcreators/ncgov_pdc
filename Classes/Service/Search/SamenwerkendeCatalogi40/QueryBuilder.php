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

use Netcreators\NcgovPdc\Domain\Search\SamenwerkendeCatalogi40\Query;
use Netcreators\NcgovPdc\Domain\Search\SearchParameter;

/**
 * Repository
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Service
 */
class QueryBuilder
{

    /**
     * @var \Netcreators\NcgovPdc\Configuration\ConfigurationManager
     */
    protected $localConfigurationManager = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Sets the configuration manager for this class
     * @param    \Netcreators\NcgovPdc\Configuration\ConfigurationManager $localConfigurationManager
     * @return    self for chaining
     */
    public function injectConfigurationManager(
        \Netcreators\NcgovPdc\Configuration\ConfigurationManager $localConfigurationManager
    ) {
        $this->localConfigurationManager = $localConfigurationManager;
        return $this;
    }

    /**
     * Builds a CQL search query for use on the Samenwerkende Catalogi 4.0 SRU remote product search
     * Choosing sensible defaults is preferred over throwing exceptions.
     * @param    SearchParameter $parameter
     * @throws    \Netcreators\NcgovPdc\Configuration\Exception
     * @return    Query
     */
    public function build(SearchParameter $parameter)
    {
        /** @var Query $query */
        $query = $this->objectManager->get(Query::class);

        // Location
        $location = $this->localConfigurationManager->get('controllers.FrequentlyAskedQuestion.actions.find.location');
        if (!$location) {
            throw new \Netcreators\NcgovPdc\Configuration\Exception('TypoScript value controllers.FrequentlyAskedQuestion.actions.find.location is not set!');
        }
        $query->setLocation($location);

        // Location type
        $locationType = Query::LOCATION_TYPE_ORGANISATION;
        if (strlen($location) == 4 && is_numeric($location)) {
            $locationType = Query::LOCATION_TYPE_POSTCODE;
        }
        $query->setLocationType($locationType);

        // Reach
        if ($parameter->getIncludeRemoteProducts()) {

            $defaultExcludes = Query::LOCATION_ORGANISATION_TYPE_MUNICIPALITY;

            // Include "GGD"?
            //	 | Query::LOCATION_ORGANISATION_TYPE_HEALTHSERVICE;

            $query->setLocationOrganisationTypes(
                Query::LOCATION_ORGANISATION_TYPE_ALL
                ^ $defaultExcludes
            );
        } else {
            // This would not be used currently, as we do local product search locally, without involving the SRU service.
            $query->setLocationOrganisationTypes(
                Query::LOCATION_ORGANISATION_TYPE_MUNICIPALITY
            );
        }

        // Audiences
        $audiences = 0;
        if ($parameter->includePrivateResults()) {
            $audiences |= Query::AUDIENCE_PRIVATE;
        }
        if ($parameter->includeBusinessResults()) {
            $audiences |= Query::AUDIENCE_BUSINESS;
        }
        if (!$audiences) {
            $audiences = Query::AUDIENCE_PRIVATE
                | Query::AUDIENCE_BUSINESS;
        }
        $query->setAudiences($audiences);

        // Keywords / search phrase
        $query->setKeyword(implode(' ', $parameter->getSearchWords()));
        $query->setExactMatch($parameter->getMatchExactPhrase());

        return $query;
    }
}

