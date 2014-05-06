<?php

/**
 * Pipeline pour Xiti
 *
 * @plugin     Xiti
 * @copyright  2014
 * @author     Vincent
 * @licence    GNU/GPL
 * @package    SPIP\Xiti\base
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Interfaces des tables xiti pour le compilateur
 *
 * @param array $interfaces
 * @return array
 */
function xiti_declarer_tables_interfaces($interfaces) {
	$interfaces['table_des_tables']['xiti'] = 'xiti';
	
	return $interfaces;
}

function xiti_declarer_tables_objets_sql($tables){
	$tables['spip_xiti'] = array(
		'texte_retour' => 'icone_retour',
		'texte_objets' => 'xiti:xiti',
		'texte_objet' => 'xiti:xiti',
		'texte_modifier' => 'xiti:icone_modifier_xiti',
		'texte_creer' => 'xiti:icone_nouveau_xiti',
		'xtnv' => 'xiti:xtnv',
		'xtsd' => 'xiti:xtsd',
		'xtsite' => 'xiti:xtsite',
		'xtn2' => 'xiti:xtn2',
		'xtdi' => 'xiti:xtdi',
		'date' => 'xiti:date_heure',
		'principale' => 'oui',
		'field'=> array(
			"id_xiti"	=> "bigint(21) NOT NULL",
			"date_heure"	=> "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
			"xtnv"	=> "text DEFAULT '' NOT NULL", //parent.document or top.document or document 
			"xtsd"	=> "text DEFAULT '' NOT NULL",
			"xtsite"	=> "text DEFAULT '' NOT NULL",
			"xtn2"	=> "int(11)",  // level 2 site 
			"xtdi" => "text DEFAULT '' NOT NULL" //implication degree
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_xiti",
		)
	);

	return $tables;
}


?>