<?php

$GLOBALS['esmlm_lists_base_version'] = 0.2;

function esmlm_declarer_tables_interfaces($interface){
	// 'spip_' dans l'index de $tables_principales
	$interface['table_des_tables']['esmlm_lists']='esmlm_lists';
	return $interface;
}

function esmlm_declarer_tables_principales($tables_principales) {
	// spip_esmlm_lists
	$spip_esmlm_lists = array(
	    "esmlm_id" => "INT(11) NOT NULL auto_increment",
	    "esmlm_inscription" => "VARCHAR(255) NOT NULL",
            "esmlm_chemin_retour" => "VARCHAR(255) NOT NULL",
            "esmlm_sujet" => "VARCHAR(255) NOT NULL"
	);

	$spip_esmlm_lists_key = array(
	    "PRIMARY KEY" => "esmlm_id"
	);

	$tables_principales['spip_esmlm_lists'] = array(
        'field' => &$spip_esmlm_lists,
        'key' => &$spip_esmlm_lists_key
        );
        
        return $tables_principales;
}

function esmlm_upgrade($nom_meta_base_version, $version_cible) {

	$current_version='0.2';
	if ((!isset($GLOBALS['meta'][$nom_meta_version_base]))
		|| (($current_version = $GLOBALS['meta'][$nom_meta_version_base]) != $version_cible)){

		if ($current_version=='0.2'){
			// Création des tables
			include_spip('base/create');
			include_spip('base/abstract_sql');
			creer_base();

			ecrire_meta($nom_meta_version_base, $current_version=$version_cible);
                        spip_log('Création des tables du plugin esmlm', 'esmlm');
		}
		
                spip_log('Ajout des tables du plugin esmlm', 'esmlm');
	}

}

function esmlm_vider_tables($nom_meta_base_version) {
        include_spip('inc/meta');
        include_spip('base/abstract_sql');
        sql_drop_table('spip_esmlm_lists');
        effacer_meta($nom_meta_base_version);
        spip_log('Suppression des tables du plugin esmlm', 'esmlm');
}

?>
