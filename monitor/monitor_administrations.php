<?php

/**
 * Pipeline pour Monitor
 *
 * @plugin     Monitor
 * @copyright  2014
 * @author     Vincent
 * @licence    GNU/GPL
 * @package    SPIP\Monitor\administrations
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Installation/maj des tables monitor
 *
 * @param string $nom_meta_base_version
 * @param string $version_cible
 */
function monitor_upgrade($nom_meta_base_version,$version_cible){
	
	$maj = array();

	$maj['create'] = array(
		array('maj_tables', array('spip_monitor', 'spip_monitor_log', 'spip_syndic'))
	);

	$maj['1.1'] = array(	
		// Ajout de champs dans spip_syndic
	 	array('maj_tables', array('spip_syndic'))
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

/**
 * Desinstallation/suppression des tables monitor
 *
 * @param string $nom_meta_base_version
 */
function monitor_vider_tables($nom_meta_base_version) {

	sql_drop_table("spip_monitor");
	sql_drop_table("spip_monitor_log");
	sql_alter('TABLE spip_syndic DROP COLUMN date_ping');
	effacer_meta("monitor");
	effacer_meta($nom_meta_base_version);
}

?>