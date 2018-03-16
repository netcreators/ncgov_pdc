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
class ReferenceLinkRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var \Netcreators\NcExtbaseLib\Domain\Repository\LogRepository
     * @inject
     */
    protected $logRepository = null;

    /**
     * Returns link identified by identifier and name.
     *
     * @param string $resourceIdentifier the identifier for the link
     * @param string $name the name of the link
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findImportedByResourceIdentifierAndName($resourceIdentifier, $name)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('imported', true),
                $query->logicalAnd(
                    $query->equals('resourceIdentifier', $resourceIdentifier),
                    $query->equals('name', $name)
                )
            )
        )->execute();
    }

    /**
     * Returns product records which are imported and match the owmscore identifier.
     *
     * @param boolean $imported
     * @param integer $offset
     * @param integer $limit
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByImportedWithOffsetAndLimit($imported, $offset, $limit)
    {
        $query = $this->createQuery();
        return $query->matching($query->equals('imported', $imported))
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();
    }

    /**
     * Returns link by link and empty name.
     *
     * @param string $link the name of the link
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByIdenticalLinkAndName($link)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('link', $link),
                $query->equals('name', $link)
            )
        )->execute();
    }

    /**
     * Finds all zombie referencelinks, which were imported.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByImportedNotUsed()
    {

        // This will find all referenced by subtable
        //SELECT l . *
        //FROM `\Netcreators\NcgovPdc\Domain\Model\ReferenceLink` l, tx_ncgovpdc_frequently_asked_question_channel_link_mm m, tx_ncgovpdc_domain_model_frequentlyaskedquestion q
        //WHERE m.uid_foreign = l.uid
        //AND m.uid_local = q.uid
        //GROUP BY l.uid

        $referenceLinkMMTables = array(
            array(
                'tx_ncgovpdc_domain_model_frequentlyaskedquestionchannel',
                'tx_ncgovpdc_frequently_asked_question_channel_link_mm'
            ),
            array('tx_ncgovpdc_domain_model_frequentlyaskedquestion', 'tx_ncgovpdc_frequently_asked_question_link_mm'),
            array(
                'tx_ncgovpdc_domain_model_frequentlyaskedquestion',
                'tx_ncgovpdc_frequently_asked_question_link_product_mm'
            ),
            array('tx_ncgovpdc_domain_model_product', 'tx_ncgovpdc_product_reference_laws_mm'),
            array('tx_ncgovpdc_domain_model_product', 'tx_ncgovpdc_product_reference_local_laws_mm'),
            array('tx_ncgovpdc_domain_model_product', 'tx_ncgovpdc_product_reference_forms_mm'),
            array('tx_ncgovpdc_domain_model_product', 'tx_ncgovpdc_product_reference_external_mm'),
            array('tx_ncgovpdc_domain_model_product', 'tx_ncgovpdc_product_reference_internal_mm'),
        );

        $referenceLinkUidList = array();
        foreach ($referenceLinkMMTables as $table) {
            $parent = $table[0];
            $table = $table[1];

            $statement = sprintf( //  l.imported = 1 AND
                'SELECT l.uid FROM tx_ncgovpdc_domain_model_referencelink l, %s m, %s q WHERE l.deleted = 0 AND m.uid_foreign = l.uid AND m.uid_local = q.uid AND q.deleted = 0 GROUP BY l.uid',
                $table,
                $parent
            );

            /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
            $query = $this->createQuery();

            /** @var array $rawQueryResult */
            $rawQueryResult = $query->statement($statement)->execute(true);

            $referenceLinkUidListForCurrentRelationTable = array();
            foreach ($rawQueryResult as $record) {
                $referenceLinkUidListForCurrentRelationTable[] = $record['uid'];
            }
            $referenceLinkUidList = array_merge($referenceLinkUidList, $referenceLinkUidListForCurrentRelationTable);
        }

        $referenceLinkUidList = array_unique($referenceLinkUidList);

        $query = $this->createQuery();
        $result = $query->matching(
            $query->logicalAnd(
                $query->equals('imported', true),
                $query->logicalNot($query->in('uid', $referenceLinkUidList))
            )
        )->execute();

        return $result;
    }

    /**
     * Removes reference links which are not used anymore.
     */
    public function removeZombieImportedReferenceLinks()
    {
        $removableReferenceLinks = $this->findByImportedNotUsed();
        $message = 'Removing zombie referencelinks: ';

        /** @var \Netcreators\NcgovPdc\Domain\Model\ReferenceLink $referenceLink */
        foreach ($removableReferenceLinks as $referenceLink) {
            $message .= 'uid=' . $referenceLink->getUid() . '; ';
            $this->remove($referenceLink);
        }

        $this->logRepository->log($message);
    }
}

