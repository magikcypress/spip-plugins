<?php

function bigbluebutton_declarer_tables_interfaces($interface){
	// 'spip_' dans l'index de $tables_principales
	$interface['table_des_tables']['bbb_meetingrooms']='bbb_meetingrooms';
        $interface['table_des_tables']['bbb_settings']='bbb_settings';
        $interface['table_des_tables']['bbb_groups']='bbb_groups';
	return $interface;
}

function bigbluebutton_declarer_tables_principales($tables_principales) {
	// spip_bbb_meetingrooms
	$spip_bbb_meetingrooms = array(
	    "bbb_id" => "INT(11) NOT NULL auto_increment",
	    "bbb_meeting_room_name" => "VARCHAR(255) NOT NULL",
            "bbb_attendee_email" => "VARCHAR(255) NOT NULL",
            "bbb_attendee_ip" => "VARCHAR(255) NOT NULL",
            "bbb_attendee_pwd" => "VARCHAR(255) NOT NULL",
            "bbb_moderator_email" => "VARCHAR(255) NOT NULL",
            "bbb_moderator_pwd" => "VARCHAR(255) NOT NULL",
            "bbb_date_inscription" => "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
            "bbb_date_debut"	=> "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
            "bbb_date_fin"	=> "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
            "bbb_join_url_attendee" => "VARCHAR(255) NOT NULL",
            "bbb_join_url_moderator" => "VARCHAR(255) NOT NULL",
            "bbb_reponse_server" => "VARCHAR(255) NOT NULL",
            "bbb_clef" => "VARCHAR(255) NOT NULL",
            "bbb_confirmation" => "int(11) NOT NULL default '0'",
            "bbb_tache_confirm_cron" => "int(11) NOT NULL default '0'"
	);

	$spip_bbb_meetingrooms_key = array(
            "PRIMARY KEY" => "bbb_id"
	);

	$tables_principales['spip_bbb_meetingrooms'] = array(
            'field' => &$spip_bbb_meetingrooms,
            'key' => &$spip_bbb_meetingrooms_key
        );

	// spip_bbb_settings
	$spip_bbb_settings = array(
	  "set_name" => "varchar(15) NOT NULL",
	  "set_value" => "varchar(255) NOT NULL"
	);

	$spip_bbb_settings_key = array(
	  //"PRIMARY KEY" => "set_name"
	);

        $tables_principales['spip_bbb_settings'] = array(
          'field' => &$spip_bbb_settings,
          'key' => &$spip_bbb_settings_key
        );

	// spip_bbb_groups
	$spip_bbb_groups = array(
          "bbb_id_groups" => "INT(11) NOT NULL auto_increment",
          "bbb_groups_name" => "varchar(255) NOT NULL",
	  "bbb_date_groups" => "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL"
	);

	$spip_bbb_groups_key = array(
	  "PRIMARY KEY" => "bbb_id_groups"
	);

        $tables_principales['spip_bbb_groups'] = array(
          'field' => &$spip_bbb_groups,
          'key' => &$spip_bbb_groups_key
        );
        
        return $tables_principales;
}

function bigbluebutton_upgrade($nom_meta_base_version, $version_cible) {
	include_spip('inc/meta');
          $current_version = 0.0;
          if ((!isset($GLOBALS['meta'][$nom_meta_base_version]))
              || (($current_version = $GLOBALS['meta'][$nom_meta_base_version])!=$version_cible)){
            if (version_compare($current_version,'0.3','<')) {
              include_spip('base/abstract_sql');
              include_spip('base/create');
              creer_base();

              sql_insertq('spip_bbb_settings',  array('set_name' => 'BBB_MAIL_ADMIN', 'set_value' => $GLOBALS['meta']['email_webmaster']));
              sql_insertq('spip_bbb_settings',  array('set_name' => 'BBB_MAIL_RETURN', 'set_value' => $GLOBALS['meta']['email_webmaster']));
              sql_insertq('spip_bbb_settings',  array('set_name' => 'BBB_URL_WEBCONF', 'set_value' => $GLOBALS['meta']['adresse_site']));
              sql_insertq('spip_bbb_settings',  array('set_name' => 'BBB_API_KEY', 'set_value' => "Insert api key"));
              sql_insertq('spip_bbb_settings',  array('set_name' => 'BBB_DELAY_CLOSE_ROOM', 'set_value' => "60"));
              ecrire_meta($nom_meta_version_base, $current_version=$version_cible);
              spip_log('Création des tables du plugin bigbluebutton', 'bigbluebutton');
	    }
		
          spip_log('Ajout des tables du plugin bigbluebutton', 'bigbluebutton');
	 }

}

function bigbluebutton_vider_tables($nom_meta_base_version) {
        include_spip('inc/meta');
        include_spip('base/abstract_sql');
        sql_drop_table('spip_bbb_meetingrooms');
        sql_drop_table('spip_bbb_settings');
        sql_drop_table('spip_bbb_groups');
        effacer_meta($nom_meta_base_version);
        spip_log('Suppression des tables du plugin bigbluebutton', 'bigbluebutton');
}

?>
