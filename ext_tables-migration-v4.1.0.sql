
-- Set legacy value for Contact Address field:
--	 Since antwoordAdres and onderwaterAntwoordAdres were added, tx_ncgovpdc_frequently_asked_question_channel_address_mm
--   	 no longer used only for contactAddresses, but also for AnswerAddresses and authorizedAnswerAddresses.
--     T	ield used for reference is tx_ncgovpdc_frequently_asked_question_channel_address_mm.field
--     = one of 'answer_addresses', 'authorized_answer_addresses', 'contact_addresses'.
UPDATE
  tx_ncgovpdc_frequently_asked_question_channel_address_mm
SET
  fieldname = 'contact_addresses'
WHERE
  fieldname = '';