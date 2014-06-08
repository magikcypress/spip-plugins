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

	if (!isset($GLOBALS['meta'][$nom_meta_base_version])){
		$trouver_table = charger_fonction('trouver_table','base');
		if ($desc = $trouver_table('spip_monitor')
		  AND isset($desc['exist'])){
			ecrire_meta($nom_meta_base_version,'1.0.0');
		}
		// si pas de table en base, on fera une simple creation de base
	}
	
	$maj = array();
	$maj['create'] = array(
		array('maj_tables',array('spip_monitor')),
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
	effacer_meta("monitor");
	effacer_meta($nom_meta_base_version);
}

?>