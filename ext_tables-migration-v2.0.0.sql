
-- Visitor view count statistics have to be reset, since we will have to work with an entirely new set of records.
TRUNCATE TABLE tx_ncgovpdc_domain_model_statistics;

-- Remove legacy themes column
ALTER TABLE
  tx_ncgovpdc_domain_model_product
DROP COLUMN
  themes;

-- Move owms_core_identifier value into scmeta_product_id
UPDATE
  tx_ncgovpdc_domain_model_product
SET
  scmeta_product_id = owms_core_identifier;


-- Change audience 'organisatie/ondernemer' to 'ondernemer'
UPDATE
  tx_ncgovpdc_domain_model_product
SET
  audience = 'ondernemer'
WHERE
	audience = 'organisatie/ondernemer';

UPDATE
  tx_ncgovpdc_domain_model_product
SET
  audience = 'particulier,ondernemer'
WHERE
	audience = 'particulier,organisatie/ondernemer'
  OR
	audience = 'organisatie/ondernemer,particulier';

-- `owms_mantle_abstract` is not migrated. As required OWMS 4.0/SC 4.0 field, its content is derived from `description`
-- during the creation of the SC 4.0 XML feed in case `owms_mantle_abstract` is empty.

-- Set scmeta_request_online_single_sign_on to default value '2' ('undefined') [Note: Defined as "DEFAULT '2'" in ext_tables.sql!]
UPDATE
  tx_ncgovpdc_domain_model_product
SET
  scmeta_request_online_single_sign_on = 2;

-- Would love to migrate `themes` (SC_Onderwerp 2.1) to `tio_themes` (Thema-indeling Overheid 1.6).
-- But sadly the two standards are entirely incompatible.