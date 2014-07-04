<?php

/**
 * Pipeline pour Monitor
 *
 * @plugin     Monitor
 * @copyright  2014
 * @author     Vincent
 * @licence    GNU/GPL
 * @package    SPIP\Monitor\base
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Interfaces des tables Monitor pour le compilateur
 *
 * @param array $interfaces
 * @return array
 */
function monitor_declarer_tables_interfaces($interfaces) {
	$interfaces['table_des_tables']['monitor'] = 'monitor';
	$interfaces['table_des_tables']['monitor_log'] = 'monitor_log';
	
	return $interfaces;
}

function monitor_declarer_tables_objets_sql($tables){
	// On ajoute un champ date_ping et statut_log dans spip_syndic
	$tables['spip_syndic']['field']['date_ping'] = "datetime NOT NULL";
	$tables['spip_syndic']['field']['statut_log'] = "varchar(3) NOT NULL";

	$tables['spip_monitor'] = array(
		'texte_retour' => 'icone_retour',
		'texte_objets' => 'monitor:monitor',
		'texte_objet' => 'monitor:monitor',
		'texte_modifier' => 'monitor:icone_modifier_monitor',
		'texte_creer' => 'monitor:icone_nouveau_monitor',
		'principale' => 'oui',
		'field'=> array(
			"id_monitor" => "bigint(21) unsigned NOT NULL AUTO_INCREMENT",
			"id_syndic" => "bigint(21) NOT NULL",
			"type"	=> "varchar(255) NOT NULL",
			"statut" => "varchar(255) NOT NULL default 'oui'",
			"date_modif" => "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
			"maj"	=> "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_monitor",
			"UNIQUE KEY"	=> "id_syndic,type",
		)
	);

	$tables['spip_monitor_log'] = array(
		'principale' => 'non',
		'field'=> array(
			"id_monitor_log" => "bigint(21) unsigned NOT NULL AUTO_INCREMENT",
			"id_syndic" => "bigint(21) NOT NULL",
			"statut"	=> "varchar(255) NOT NULL default 'ping'",
			"log" => "varchar(3) NOT NULL",
			"valeur" => "varchar(255) NOT NULL",
			"maj"	=> "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_monitor_log",
		)
	);

	return $tables;
}

?>