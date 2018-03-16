
ALTER TABLE tx_ncgovpdc_domain_model_frequentlyaskedquestion
MODIFY COLUMN owms_core_language varchar(32) DEFAULT '' NOT NULL;


-- Change owms_core_language to the correct values
UPDATE
  tx_ncgovpdc_domain_model_frequentlyaskedquestion
SET
  owms_core_language = 'nl-NL'
WHERE
  owms_core_language = 'nl';

-- Change owms_core_language to the correct values
UPDATE
  tx_ncgovpdc_domain_model_frequentlyaskedquestion
SET
  owms_core_language = 'fy-NL'
WHERE
  owms_core_language = 'fy';

-- Change owms_core_language to the correct values
UPDATE
  tx_ncgovpdc_domain_model_frequentlyaskedquestion
SET
  owms_core_language = 'en-GB'
WHERE
  owms_core_language = 'en';

-- Change owms_core_language to the correct values
UPDATE
  tx_ncgovpdc_domain_model_frequentlyaskedquestion
SET
  owms_core_language = 'fr-FR'
WHERE
  owms_core_language = 'fr';

-- Change owms_core_language to the correct values
UPDATE
  tx_ncgovpdc_domain_model_frequentlyaskedquestion
SET
  owms_core_language = 'de-DE'
WHERE
  owms_core_language = 'de';

