#
# Table structure for table 'tx_ncgovpdc_domain_model_product'
#
CREATE TABLE tx_ncgovpdc_domain_model_product (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	copy_uid int(11) unsigned DEFAULT '0' NOT NULL,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	# *********************
	# * general fields
	show_fields tinyint(4) DEFAULT '0' NOT NULL,
	imported tinyint(4) unsigned DEFAULT '0' NOT NULL,
	session_number int(11) DEFAULT '0' NOT NULL,
	weight int(11) DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	owms_core_identifier varchar(250) DEFAULT '' NOT NULL,
	owms_core_modified int(11) unsigned DEFAULT '0' NOT NULL,
	owms_mantle_abstract text NOT NULL,
	scmeta_product_id varchar(255) DEFAULT '' NOT NULL,
	scmeta_request_online tinyint(4) unsigned DEFAULT '0' NOT NULL,
	scmeta_request_online_url text NOT NULL,
	scmeta_request_online_single_sign_on tinyint(4) unsigned DEFAULT '2' NOT NULL,
	scmeta_contact_point varchar(255) DEFAULT '' NOT NULL,
	scmeta_uniform_product_names text NOT NULL,
	scmeta_related_uniform_product_names text NOT NULL,
	tio_themes text NOT NULL,
	type tinyint(4) DEFAULT '0' NOT NULL,
	changes text NOT NULL,
	costs text NOT NULL,
	costs_content blob NOT NULL,
	turnaround int(11) DEFAULT '0' NOT NULL,
	short_description text NOT NULL,
	language varchar(32) DEFAULT '' NOT NULL,
	custom_label varchar(255) DEFAULT '' NOT NULL,
	show_dynamic_content tinyint(4) unsigned DEFAULT '0' NOT NULL,

  # Onderwaterantwoord
	desk_memo text NOT NULL,

	# *********************
	# * combined fields
	#detailed description
	pre_description text NOT NULL,
	description text NOT NULL,
	post_description text NOT NULL,
	use_description tinyint(4) DEFAULT '0'  NOT NULL,
	use_pre_description tinyint(4) DEFAULT '0'  NOT NULL,
	use_post_description tinyint(4) DEFAULT '0'  NOT NULL,

	#apply_info
	pre_apply_info text NOT NULL,
	apply_info text NOT NULL,
	post_apply_info text NOT NULL,
	use_pre_apply_info tinyint(4) DEFAULT '0'  NOT NULL,
	use_apply_info tinyint(4) DEFAULT '0'  NOT NULL,
	use_post_apply_info tinyint(4) DEFAULT '0'  NOT NULL,

	#extra info
	pre_extra_info text NOT NULL,
	extra_info text NOT NULL,
	post_extra_info text NOT NULL,
	use_pre_extra_info tinyint(4) DEFAULT '0'  NOT NULL,
	use_extra_info tinyint(4) DEFAULT '0'  NOT NULL,
	use_post_extra_info tinyint(4) DEFAULT '0'  NOT NULL,

	#contact_info
	pre_contact_info text NOT NULL,
	contact_info text NOT NULL,
	post_contact_info text NOT NULL,
	use_pre_contact_info tinyint(4) DEFAULT '0'  NOT NULL,
	use_contact_info tinyint(4) DEFAULT '0'  NOT NULL,
	use_post_contact_info tinyint(4) DEFAULT '0'  NOT NULL,

	#required for application
	pre_required_for_application text NOT NULL,
	required_for_application text NOT NULL,
	post_required_for_application text NOT NULL,
	use_pre_required_for_application tinyint(4) DEFAULT '0' NOT NULL,
	use_required_for_application tinyint(4) DEFAULT '0' NOT NULL,
	use_post_required_for_application tinyint(4) DEFAULT '0' NOT NULL,

	#legal basis
	pre_legal_basis text NOT NULL,
	legal_basis text NOT NULL,
	post_legal_basis text NOT NULL,
	use_pre_legal_basis tinyint(4) DEFAULT '0' NOT NULL,
	use_legal_basis tinyint(4) DEFAULT '0' NOT NULL,
	use_post_legal_basis tinyint(4) DEFAULT '0' NOT NULL,

	#terms
	pre_terms text NOT NULL,
	terms text NOT NULL,
	post_terms text NOT NULL,
	use_pre_terms tinyint(4) DEFAULT '0' NOT NULL,
	use_terms tinyint(4) DEFAULT '0' NOT NULL,
	use_post_terms tinyint(4) DEFAULT '0' NOT NULL,

	#result
	pre_result text NOT NULL,
	result text NOT NULL,
	post_result text NOT NULL,
	use_pre_result tinyint(4) DEFAULT '0' NOT NULL,
	use_result tinyint(4) DEFAULT '0' NOT NULL,
	use_post_result tinyint(4) DEFAULT '0' NOT NULL,

	#appeal (Bezwaar en Beroep)
	pre_appeal text NOT NULL,
	appeal text NOT NULL,
	post_appeal text NOT NULL,
	use_pre_appeal tinyint(4) DEFAULT '0' NOT NULL,
	use_appeal tinyint(4) DEFAULT '0' NOT NULL,
	use_post_appeal tinyint(4) DEFAULT '0' NOT NULL,

	# *********************
	# * link fields
	contact_addresses text,
	synonyms text,
	keywords text,
	source text,
	frequently_asked_questions text NOT NULL,
	frequently_asked_question_categories text,
	frequently_asked_question_info text,
	image blob,
	responsibles text,
	request_form text,
	attachments text,
	directive text,
	process_description text,
	related_products text,
	advanced_themes text,
	users_available text,
	costs_content text,
	life_phases text,
	related_regulatory text,
	tips text,
	authorities text,
	audience text,
	reference_laws text,
	reference_local_laws text,
	reference_forms text,
	reference_internal text,
	reference_external text,
	link_groups text,
	maintained_by text,
	flexible text NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_product_relatedproducts_mm'
#
CREATE TABLE tx_ncgovpdc_product_relatedproducts_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_responsible_mm'
#
CREATE TABLE tx_ncgovpdc_product_responsible_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_users_available_mm'
#
CREATE TABLE tx_ncgovpdc_product_users_available_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_costs_content_mm'
#
#CREATE TABLE tx_ncgovpdc_product_costs_content_mm (
#	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
#	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
#	tablenames varchar(64) DEFAULT '' NOT NULL,
#	sorting int(11) unsigned DEFAULT '0' NOT NULL,

#	KEY uid_local (uid_local),
#	KEY uid_foreign (uid_foreign)
#);

#
# Table structure for table 'tx_ncgovpdc_product_authority_mm'
#
CREATE TABLE tx_ncgovpdc_product_authority_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_synonym_mm'
#
CREATE TABLE tx_ncgovpdc_product_synonym_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_address_mm'
#
CREATE TABLE tx_ncgovpdc_product_address_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_keyword_mm'
#
CREATE TABLE tx_ncgovpdc_product_keyword_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_frequently_asked_question_mm'
#
CREATE TABLE tx_ncgovpdc_product_frequently_asked_question_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_ncgovpdc_product_frequently_asked_question_category_mm'
#
CREATE TABLE tx_ncgovpdc_product_frequently_asked_question_category_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_reference_laws_mm'
#
CREATE TABLE tx_ncgovpdc_product_reference_laws_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_local_laws_mm'
#
CREATE TABLE tx_ncgovpdc_product_reference_local_laws_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_forms_mm'
#
CREATE TABLE tx_ncgovpdc_product_reference_forms_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_reference_internal_mm'
#
CREATE TABLE tx_ncgovpdc_product_reference_internal_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_reference_external_mm'
#
CREATE TABLE tx_ncgovpdc_product_reference_external_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_product_maintainedby_mm'
#
CREATE TABLE tx_ncgovpdc_product_maintainedby_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_synonym'
#
CREATE TABLE tx_ncgovpdc_domain_model_synonym (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	synonym varchar(255) DEFAULT '' NOT NULL,
	relates_to text NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_synonym_relatesto_mm'
#
CREATE TABLE tx_ncgovpdc_synonym_relatesto_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_keyword'
#
CREATE TABLE tx_ncgovpdc_domain_model_keyword (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	keyword varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_linkgroup'
#
CREATE TABLE tx_ncgovpdc_domain_model_linkgroup (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	reference_links text NOT NULL,

	product int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_linkgroup_reference_links_mm'
#
CREATE TABLE tx_ncgovpdc_linkgroup_reference_links_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_frequentlyaskedquestion'
#
CREATE TABLE tx_ncgovpdc_domain_model_frequentlyaskedquestion (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	imported tinyint(4) unsigned DEFAULT '0' NOT NULL,
	weight int(11) DEFAULT '0' NOT NULL,
	session_number int(11) DEFAULT '0' NOT NULL,
	owms_core_title varchar(255) DEFAULT '' NOT NULL,
	owms_core_identifier varchar(255) DEFAULT '' NOT NULL,
	owms_core_language varchar(32) DEFAULT '' NOT NULL,
	owms_core_creator_scheme varchar(255) DEFAULT '' NOT NULL,
	owms_core_creator varchar(255) DEFAULT '' NOT NULL,
	owms_core_modified int(11) unsigned DEFAULT '0' NOT NULL,
	owms_core_spatial_scheme varchar(28) DEFAULT '' NOT NULL,
	owms_core_spatial varchar(255) DEFAULT '' NOT NULL,
	owms_core_temporal_start int(11) DEFAULT '0' NOT NULL,
	owms_core_temporal_end int(11) DEFAULT '0' NOT NULL,
	owms_mantle_authority varchar(255) DEFAULT '' NOT NULL,
	owms_mantle_contributor text NOT NULL,
	owms_mantle_available_start int(11) DEFAULT '0' NOT NULL,
	owms_mantle_available_end int(11) DEFAULT '0' NOT NULL,
	owms_mantle_audience text,
	owms_mantle_subjects_122 text NOT NULL,
	priority varchar(6) DEFAULT '0' NOT NULL,
	supplier_system text NOT NULL,
	editorial_state varchar(10) DEFAULT '' NOT NULL,
	verification_date int(11) DEFAULT '0' NOT NULL,
	frequently_asked_question_channels int(11) DEFAULT '0' NOT NULL,
	reference_products int(11) DEFAULT '0' NOT NULL,
	reference_frequently_asked_questions text NOT NULL,
	revisions int(11) DEFAULT '0' NOT NULL,
	destinations int(11) DEFAULT '0' NOT NULL,

	authorities text,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_frequently_asked_question_authority_mm'
#
CREATE TABLE tx_ncgovpdc_frequently_asked_question_authority_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_revision'
#
CREATE TABLE tx_ncgovpdc_domain_model_revision (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	title varchar(200) DEFAULT '' NOT NULL,
	version varchar(8) DEFAULT '' NOT NULL,
	date_time int(11) unsigned DEFAULT '0' NOT NULL,
	author varchar(40) DEFAULT '' NOT NULL,
	revision_types text NOT NULL,
	comment varchar(200) DEFAULT '' NOT NULL,

	frequently_asked_question_uid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_destination'
#
CREATE TABLE tx_ncgovpdc_domain_model_destination (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_frequently_asked_question_destination_mm'
#
CREATE TABLE tx_ncgovpdc_frequently_asked_question_destination_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_frequently_asked_question_link_product_mm'
#
CREATE TABLE tx_ncgovpdc_frequently_asked_question_link_product_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_frequently_asked_question_link_mm'
#
CREATE TABLE tx_ncgovpdc_frequently_asked_question_link_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_frequently_asked_question_channel_mm'
#
#CREATE TABLE tx_ncgovpdc_frequently_asked_question_channel_mm (
#	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
#	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
#	tablenames varchar(64) DEFAULT '' NOT NULL,
#	sorting int(11) unsigned DEFAULT '0' NOT NULL,
#
#	KEY uid_local (uid_local),
#	KEY uid_foreign (uid_foreign)
#);

#
# Table structure for table 'tx_ncgovpdc_domain_model_frequentlyaskedquestionchannel'
#
CREATE TABLE tx_ncgovpdc_domain_model_frequentlyaskedquestionchannel (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	channels text NOT NULL,
	question varchar(255) DEFAULT '' NOT NULL,
	short_answer text NOT NULL,
	answer text NOT NULL,
	answer_product_field text NOT NULL,
	answer_addresses text,
	authorized_answer text NOT NULL,
	authorized_answer_product_field text NOT NULL,
	authorized_answer_addresses text,
	reference_other_info text NOT NULL,
	reference_contacts text NOT NULL,
	contact_addresses text,

	frequently_asked_question_uid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_frequently_asked_question_channel_link_mm'
#
CREATE TABLE tx_ncgovpdc_frequently_asked_question_channel_link_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_frequently_asked_question_channel_link_contact_mm'
#
CREATE TABLE tx_ncgovpdc_frequently_asked_question_channel_link_contact_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_frequently_asked_question_channel_address_mm'
#
CREATE TABLE tx_ncgovpdc_frequently_asked_question_channel_address_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(64) DEFAULT '' NOT NULL,
	fieldname varchar(64) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_referencelink'
#
CREATE TABLE tx_ncgovpdc_domain_model_referencelink (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	type tinyint(4) DEFAULT '0' NOT NULL,
	subtype tinyint(4) DEFAULT '0' NOT NULL,
	imported tinyint(4) DEFAULT '0' NOT NULL,
	name text NOT NULL,
	title text NOT NULL,
	link text NOT NULL,
	link_frequently_asked_question text NOT NULL,
	link_product text NOT NULL,
	link_page text NOT NULL,
	resource_identifier varchar(255) DEFAULT '' NOT NULL,

	description text NOT NULL,
	is_digid_service tinyint(4) DEFAULT '0' NOT NULL,
	is_electronic_service tinyint(4) DEFAULT '0' NOT NULL,
	service_url text NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_tip'
#
CREATE TABLE tx_ncgovpdc_domain_model_tip (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL,
	state int(11) DEFAULT '0' NOT NULL,
	datetime int(11) unsigned DEFAULT '0' NOT NULL,

	product int(11) DEFAULT '0' NOT NULL,

	creator int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);


#
# Table structure for table 'tx_ncgovpdc_domain_model_registration'
#
CREATE TABLE tx_ncgovpdc_domain_model_registration (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	subject varchar(255) DEFAULT '' NOT NULL,
	start_time int(11) DEFAULT '0' NOT NULL,
	end_time int(11) DEFAULT '0' NOT NULL,
	closed tinyint(4) DEFAULT '0' NOT NULL,
	result text NOT NULL,
	remark text,
	actions text NOT NULL,
	next_registration int(11) DEFAULT '0' NOT NULL,

	user int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_registrationaction'
#
CREATE TABLE tx_ncgovpdc_domain_model_registrationaction (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	type tinyint(4) DEFAULT '0' NOT NULL,
	product text NOT NULL,
	frequently_asked_question text NOT NULL,
	search_parameter text,
	registration int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_registrationresult'
#
CREATE TABLE tx_ncgovpdc_domain_model_registrationresult (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,

	sorting int(10) DEFAULT '4096' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
	tx_ncgovpdc_availability_status varchar(4) DEFAULT ''
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_statistics'
#
CREATE TABLE tx_ncgovpdc_domain_model_statistics (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,

	type tinyint(4) DEFAULT '0' NOT NULL,
	product text NOT NULL,
	frequently_asked_question text NOT NULL,
	loggedin_count int(11) DEFAULT '0' NOT NULL,
	count int(11) DEFAULT '0' NOT NULL,
	logtime int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_ncgovpdc_domain_model_advancedtheme'
#
CREATE TABLE tx_ncgovpdc_domain_model_advancedtheme (
  uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  crdate int(11) unsigned DEFAULT '0' NOT NULL,
  deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
  hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

  t3ver_oid int(11) DEFAULT '0' NOT NULL,
  t3ver_id int(11) DEFAULT '0' NOT NULL,
  t3ver_wsid int(11) DEFAULT '0' NOT NULL,
  t3ver_label varchar(30) DEFAULT '' NOT NULL,
  t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
  t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
  t3ver_count int(11) DEFAULT '0' NOT NULL,
  t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
  t3_origuid int(11) DEFAULT '0' NOT NULL,

  sys_language_uid int(11) DEFAULT '0' NOT NULL,
  l18n_parent int(11) DEFAULT '0' NOT NULL,
  l18n_diffsource mediumblob NOT NULL,

  identifier varchar(255) DEFAULT '' NOT NULL,
  imported tinyint(4) unsigned DEFAULT '0' NOT NULL,
  title varchar(255) DEFAULT '' NOT NULL,
  session_number int(11) DEFAULT '0' NOT NULL,
  modified int(11) unsigned DEFAULT '0' NOT NULL,
  keywords text NOT NULL,
  without_context text NOT NULL,
  level int(11) DEFAULT '0' NOT NULL,
  type int(11) DEFAULT '0' NOT NULL,
  parent int(11) DEFAULT '0' NOT NULL,
  related_products text,

  PRIMARY KEY (uid),
  KEY parent (pid),
);

#
# Table structure for table 'tx_ncgovpdc_advancedtheme_product_mm'
#
CREATE TABLE tx_ncgovpdc_advancedtheme_product_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(64) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,

  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tt_address'
#
CREATE TABLE tt_address (
  phone varchar(255) DEFAULT '' NOT NULL,
  tx_ncgovpdc_vac_contact_instance_uid int(11) DEFAULT '0' NOT NULL,
  tx_ncgovpdc_post_address tinytext NOT NULL,
  tx_ncgovpdc_post_city varchar(255) DEFAULT '' NOT NULL,
  tx_ncgovpdc_post_zip varchar(20) DEFAULT '' NOT NULL,
  tx_ncgovpdc_post_p_o_box varchar(128) DEFAULT '' NOT NULL,
);
