<?php

/**
 * Pipeline pour Xiti
 *
 * @plugin     Xiti
 * @copyright  2014
 * @author     Vincent
 * @licence    GNU/GPL
 * @package    SPIP\Xiti\administrations
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Installation/maj des tables xiti
 *
 * @param string $nom_meta_base_version
 * @param string $version_cible
 */
function xiti_upgrade($nom_meta_base_version, $version_cible){
	
	$maj = array();
	$maj['create'] = array(
		array('configurer_liste_metas')
	);
	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

/**
 * Definir la meta de configuration liee à xiti
 *
 * @param array $metas
 * @return array
 */
function configurer_liste_metas($metas){
	// $metas['activer_xiti'] =  'non';
	// return $metas;
	ecrire_meta('' . $metas['activer_xiti'] . '', 'non');
}

/**
 * Desinstallation/suppression des tables xiti
 *
 * @param string $nom_meta_base_version
 */
function xiti_vider_tables($nom_meta_base_version) {
	effacer_meta("activer_xiti");
	effacer_meta('xtnv_xiti');
	effacer_meta('xtsd_xiti');
	effacer_meta('xtsite_xiti');
	effacer_meta('xtpage_xiti');
	effacer_meta('xtn2_xiti');
	effacer_meta('xtdi_xiti');
	effacer_meta($nom_meta_base_version);
}

?>