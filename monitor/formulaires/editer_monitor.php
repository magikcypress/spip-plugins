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
function formulaires_editer_monitor_charger_dist(){

	$valeurs = array();
	$id_syndic = _request('id_syndic');
	$valeurs['status'] = sql_allfetsel('id_syndic,type,statut', 'spip_monitor', 'id_syndic=' . $id_syndic);
	
	return $valeurs;
	
}

/**
 * Vérifications du formulaire de configuration du monitoring des sites
 *
 * @return array
 *     Tableau des erreurs
**/
function formulaires_editer_monitor_verifier_dist(){
	$erreurs = array();
	
	// les checkbox
	foreach(array('activer_monitor_ping','activer_monitor_poids') as $champ)
		if (_request($champ)!='oui')
			set_request($champ,'non');
			
	return $erreurs;
}

/**
 * Traitement du formulaire de configuration du monitoring des sites
 *
 * @return array
 *     Retours du traitement
**/
function formulaires_editer_monitor_traiter_dist(){

	$id_syndic = _request('id_syndic');
	$syndic = sql_allfetsel('id_syndic', 'spip_monitor', 'id_syndic=' . $id_syndic);

	if (!$syndic) {
		sql_insertq('spip_monitor',array('id_syndic'=>$id_syndic, 'statut'=>_request('activer_monitor_ping') ,'type'=>_request('activer_monitor_ping_type')));
		sql_insertq('spip_monitor',array('id_syndic'=>$id_syndic, 'statut'=>_request('activer_monitor_poids') ,'type'=>_request('activer_monitor_poids_type')));
	} else {
		spip_log(_request('activer_monitor_ping'), 'test.' . _LOG_ERREUR);
		spip_log(_request('activer_monitor_ping_type'), 'test.' . _LOG_ERREUR);
		spip_log(_request('id_syndic'), 'test.' . _LOG_ERREUR);
		sql_updateq('spip_monitor', array('statut'=>_request('activer_monitor_ping')), 'id_syndic=' . $id_syndic . 'and type like "' . _request('activer_monitor_ping_type') . '"');
		sql_updateq('spip_monitor', array('statut'=>_request('activer_monitor_poids')), 'id_syndic=' . $id_syndic . 'and type like "' . _request('activer_monitor_poids_type') . '"');
	}
		
	return array('message_ok'=>_T('config_info_enregistree'));
}

?>
