<?php
/**
 * Created by PhpStorm.
 * User: djofrans
 * Date: 8/4/14
 * Time: 4:54 PM
 */

// FIXME: Is this used at all? (See also Configuration/TCA/Dynamic/tx_ncgovpdc_domain_model_product.php)
class user_setReferenceLinkSubtypes {

	/**
	 * @var t3lib_db
	 */
	protected $databaseHandle;

	/**
	 * @var array
	 */
	protected $productTable;

	public function initialize() {
		$this->databaseHandle = $GLOBALS['TYPO3_DB'];
		$this->productTable = $GLOBALS['TCA']['tx_ncgovpdc_domain_model_product'];
	}

	/**
	 * Sets subtypes from the field by which it is related to the product
	 * @param string $content
	 * @param array $conf
	 * @return string
	 */
	public function setSubtypesFromFieldFromWhichLinkedFromPdc($content, $conf) {
		$this->initialize();
		$count = 0;

		$tablesSubtypeMapping = array(
			'reference_laws' => 1,
			'reference_local_laws' => 2,
			'reference_forms' => 3,
			'reference_internal' => 4,
			'reference_external' => 5,
		);

		foreach($tablesSubtypeMapping as $column => $subtype) {
			$content .= '<h2>' . $column . '</h2><br />';
			$foreignTable = $this->productTable['columns'][$column]['config']['foreign_table'];
			$foreignTableMm = $this->productTable['columns'][$column]['config']['MM'];
			$sql = vsprintf(
				'SELECT %1$s.*
				FROM %1$s, tx_ncgovpdc_domain_model_product p, %2$s
				WHERE %2$s.uid_local = p.uid
				AND %1$s.uid = %2$s.uid_foreign
				AND %1$s.subtype != %3$d
				AND %1$s.deleted = 0',
				array($foreignTable, $foreignTableMm, $subtype)
			);
			$res = $this->databaseHandle->sql_query($sql);

			if($this->databaseHandle->sql_num_rows($res) > 0) {

				$rows = array();
				while($rows[] = $this->databaseHandle->sql_fetch_assoc($res)) {
				}

				if(count($rows) > 0) {

					foreach($rows as $row) {
						$count++;
						$updatedRow = array('subtype' => $subtype);
						$where = 'uid = ' . $row['uid'];
						$this->databaseHandle->exec_UPDATEquery($foreignTable, $where, $updatedRow);
						$content .= vsprintf('setting subtype "%1$s" to "%2$d"<br />', array($row['name'], $subtype));
				    }
				}
			}

			$this->databaseHandle->sql_free_result($res);
		}

		$content .= $count . ' records bijgewerkt!<br />';
		return $content;
	}
}