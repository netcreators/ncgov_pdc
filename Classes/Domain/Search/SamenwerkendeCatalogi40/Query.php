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

namespace Netcreators\NcgovPdc\Domain\Search\SamenwerkendeCatalogi40;

/**
 * SC 4.0 Query
 *
 * @author Leonie Philine Bitto <leonie@netcreators.nl>
 * @copyright Netcreators
 * @package NcgovPdc
 * @subpackage Domain
 */
class Query
{
    /**
     * @var string
     */
    private $location = '';

    /**
     * @var integer
     */
    private $locationType = 0;

    /**
     * @var integer
     */
    private $locationOrganisationTypes = 0;

    /**
     * @var integer
     */
    private $audiences = 0;

    /**
     * @var string
     */
    private $keyword = '';

    /**
     * @var boolean
     */
    private $exactMatch = false;

    const LOCATION_TYPE_ORGANISATION = 0;
    const LOCATION_TYPE_POSTCODE = 1;

    const LOCATION_ORGANISATION_TYPE_MUNICIPALITY = 1;
    const LOCATION_ORGANISATION_TYPE_PROVINCE = 2;
    const LOCATION_ORGANISATION_TYPE_WATERBOARD = 4;
    const LOCATION_ORGANISATION_TYPE_HEALTHSERVICE = 8;
    const LOCATION_ORGANISATION_TYPE_MINISTRY = 16;
    const LOCATION_ORGANISATION_TYPE_OTHER = 32;
    const LOCATION_ORGANISATION_TYPE_ALL = 63;

    const AUDIENCE_PRIVATE = 1;
    const AUDIENCE_BUSINESS = 2;

    /**
     * Sets the search location
     * @param    string $location
     * @return self
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Sets the search location type
     * @param    integer $locationType
     * @return self
     */
    public function setLocationType($locationType)
    {
        $this->locationType = $locationType;
        return $this;
    }

    /**
     * Sets the search location organisation types
     * @param    integer $locationOrganisationTypes
     * @return self
     */
    public function setLocationOrganisationTypes($locationOrganisationTypes)
    {
        $this->locationOrganisationTypes = $locationOrganisationTypes;
        return $this;
    }

    /**
     * Sets the search audiences
     * @param    integer $audiences
     * @return self
     */
    public function setAudiences($audiences)
    {
        $this->audiences = $audiences;
        return $this;
    }

    /**
     * Sets the search keyword(s)
     * @param    string $keyword
     * @return self
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Sets if to search for the exact keyword string (TRUE) or allow the keywords to appear in random order (FALSE).
     * @param    boolean $exactMatch
     * @return self
     */
    public function setExactMatch($exactMatch)
    {
        $this->exactMatch = $exactMatch;
        return $this;
    }

    /**
     * Builds a SRU query string
     * @return string
     */
    protected function build()
    {
        $queryString =
            '('
            . '(' . $this->getLocationIndication() . ')'
            . ' and '
            . '(' . $this->getSearchCriteria() . ')'
            . ')';

        return $queryString;
    }

    /**
     * Returns the 'locatieaanduiding' part of the SRU query (postcode/organisatie, organisatietype)
     * @return string
     */
    protected function getLocationIndication()
    {
        $locationIndication = '';

        // For which location are we searching?
        switch ($this->locationType) {
            case self::LOCATION_TYPE_POSTCODE:
                $locationIndication .= 'postcode';
                break;

            case self::LOCATION_TYPE_ORGANISATION:
            default:
                $locationIndication .= 'organisatie';
                break;
        }
        $locationIndication .= '="' . urlencode($this->location) . '"';

        // For which range surrounding the location are we searching?
        $availableOrganisationTypes = array(
            self::LOCATION_ORGANISATION_TYPE_MUNICIPALITY => 'Gemeente',
            self::LOCATION_ORGANISATION_TYPE_PROVINCE => 'Provincie',
            self::LOCATION_ORGANISATION_TYPE_WATERBOARD => 'Waterschap',
            self::LOCATION_ORGANISATION_TYPE_HEALTHSERVICE => 'GGD',
            self::LOCATION_ORGANISATION_TYPE_MINISTRY => 'Ministerie',
            self::LOCATION_ORGANISATION_TYPE_OTHER => 'AndereOrganisatie'
        );
        $selectedOrganisationTypes = array();
        foreach ($availableOrganisationTypes as $bitFlag => $typeLabel) {
            if ($bitFlag & $this->locationOrganisationTypes) {
                $selectedOrganisationTypes[] = 'organisatieType="' . $typeLabel . '"';
            }
        }
        $locationIndication .= ' and (' . implode(' or ', $selectedOrganisationTypes) . ')';

        return $locationIndication;
    }

    /**
     * Returns the 'zoekcriteria' part of the SRU query (audience, keyword, etc.)
     * @return string
     */
    protected function getSearchCriteria()
    {

        $availableAudiences = array(
            self::AUDIENCE_PRIVATE => 'particulier',
            self::AUDIENCE_BUSINESS => 'ondernemer'
        );
        $selectedAudiences = array();
        foreach ($availableAudiences as $bitFlag => $audienceLabel) {
            if ($bitFlag & $this->audiences) {
                $selectedAudiences[] = 'audience="' . $audienceLabel . '"';
            }
        }
        $searchCriteria = '(' . implode(' or ', $selectedAudiences) . ')';

        $keywordSearchOperator = $this->exactMatch ? 'adj' : 'all';
        $searchCriteria .= ' and (keyword ' . $keywordSearchOperator . ' "' . urlencode($this->keyword) . '")';

        return $searchCriteria;
    }

    public function __toString()
    {
        return $this->build();
    }
}

