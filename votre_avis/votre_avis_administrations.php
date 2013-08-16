<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2011                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Installation/maj des tables votre_avis
 *
 * @param string $nom_meta_base_version
 * @param string $version_cible
 */
function votre_avis_upgrade($nom_meta_base_version,$version_cible){
	// cas particulier :
	// si plugin pas installe mais que la table existe
	// considerer que c'est un upgrade depuis v 1.0.0
	// pour gerer l'historique des installations SPIP <=2.1
	if (!isset($GLOBALS['meta'][$nom_meta_base_version])){
		$trouver_table = charger_fonction('trouver_table','base');
		if ($desc = $trouver_table('spip_votre_avis')
		  AND isset($desc['exist'])){
			ecrire_meta($nom_meta_base_version,'1.0.0');
		}
		// si pas de table en base, on fera une simple creation de base
	}
	
	$maj = array();
	$maj['create'] = array(
		array('maj_tables',array('spip_votre_avis')),
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

/**
 * Desinstallation/suppression des tables votre_avis
 *
 * @param string $nom_meta_base_version
 */
function votre_avis_vider_tables($nom_meta_base_version) {
	sql_drop_table("spip_votre_avis");
	
	effacer_meta("activer_votre_avis");
	effacer_meta('config_rubrique_votre_avis');
	effacer_meta('config_nbcaracteres_votre_avis');
	
	effacer_meta($nom_meta_base_version);
}

?>
