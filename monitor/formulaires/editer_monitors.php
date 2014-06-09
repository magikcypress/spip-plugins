<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2014                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

/**
 * Gestion du formulaire de monitoring des sites 
 *
 * @package SPIP\Formulaires
**/
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Chargement du formulaire de configuration du monitoring des sites
 *
 * @return array
 *     Environnement du formulaire
**/
function formulaires_editer_monitors_charger_dist(){

	$valeurs = array();
	
	return $valeurs;
	
}

/**
 * Vérifications du formulaire de configuration du monitoring des sites
 *
 * @return array
 *     Tableau des erreurs
**/
function formulaires_editer_monitors_verifier_dist(){
	$erreurs = array();
			
	return $erreurs;
}

/**
 * Traitement du formulaire de configuration du monitoring des sites
 *
 * @return array
 *     Retours du traitement
**/
function formulaires_editer_monitors_traiter_dist(){

	$sites = sql_allfetsel('id_syndic', 'spip_syndic', 'statut="publie"');

	foreach ($sites as $site) {
		sql_insertq('spip_monitor',array('id_syndic'=>$site['id_syndic'], 'statut'=>_request('activer_monitors_ping'), 'type'=>_request('activer_monitors_ping_type')));
		sql_insertq('spip_monitor',array('id_syndic'=>$site['id_syndic'], 'statut'=>_request('activer_monitors_poids'), 'type'=>_request('activer_monitors_poids_type')));
	}
		
	return array('message_ok'=>_T('config_info_enregistree'));
}

?>
