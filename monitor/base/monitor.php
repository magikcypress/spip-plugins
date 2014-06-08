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
			"status" => "enum('oui','non') NOT NULL default 'oui'",
			"error" => "varchar(255) NOT NULL",
			"date_modif" => "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
			"maj"	=> "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_monitor",
		)
	);

	$tables['spip_monitor_log'] = array(
		'principale' => 'non',
		'field'=> array(
			"id_monitor_log" => "bigint(21) unsigned NOT NULL AUTO_INCREMENT",
			"id_syndic" => "bigint(21) NOT NULL",
			"status"	=> "enum('ping','poids') NOT NULL default 'ping'",
			"maj"	=> "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_monitor_log",
		)
	);

	return $tables;
}

?>