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

if (!defined("_ECRIRE_INC_VERSION")) return;

/**
 * Definir les meta de configuration liee aux breves
 *
 * @param array $metas
 * @return array
 */
function monitor_configurer_liste_metas($metas){
	$metas['monitor'] =  array('activer_monitor' => 'non');
	return $metas;
}

/**
 * Afficher monitor milieu page site
 * @param array $flux
 * @return array
 */
function monitor_affiche_milieu($flux){

	// si on est sur un site ou il faut activer le monitor...
	if (lire_config('monitor/activer_monitor') == "oui" and trouver_objet_exec($flux['args']['exec'] == "site")){

		$texte = recuperer_fond(
				'prive/objets/editer/monitor',
				array(
					'table_source'=>'monitor',
					'objet'=>$type,
					'id_objet'=>$id,
				)
		);
		if ($p=strpos($flux['data'],"<!--affiche_milieu-->"))
			$flux['data'] = substr_replace($flux['data'],$texte,$p,0);
		else
			$flux['data'] .= $texte;
	}

	if (lire_config('monitor/activer_monitor') == "oui" and trouver_objet_exec($flux['args']['exec'] == "site")){

		$texte = recuperer_fond(
				'prive/objets/contenu/monitor_graph',
				array(
					'table_source'=>'monitor_log',
					'objet'=>$type,
					'id_objet'=>$id,
				)
		);
		if ($p=strpos($flux['data'],"<!--affiche_milieu-->"))
			$flux['data'] = substr_replace($flux['data'],$texte,$p,0);
		else
			$flux['data'] .= $texte;
	}

	// si on est sur la liste des site référencés
	if (lire_config('monitor/activer_monitor') == "oui" and trouver_objet_exec($flux['args']['exec'] == "sites")){

		$texte = recuperer_fond(
				'prive/objets/editer/monitors',
				array(
					'table_source'=>'monitor',
					'objet'=>$type,
					'id_objet'=>$id,
				)
		);
		if ($p=strpos($flux['data'],"<!--affiche_milieu-->"))
			$flux['data'] = substr_replace($flux['data'],$texte,$p,0);
		else
			$flux['data'] .= $texte;
	}

	return $flux;
}

include_once(_DIR_PLUGIN_MONITOR."lib/Monitor/MonitorSites.php");

/**
 * Taches periodiques de monitor 
 *
 * @param array $taches_generales
 * @return array
 */
function monitor_taches_generales_cron($taches_generales){


	if (lire_config('monitor/activer_monitor') == "oui") {

		$sites = sql_allfetsel('id_syndic', 'spip_monitor', 'type like "ping" and statut="oui"');

		foreach ($sites as $site) {
			$href = sql_getfetsel('url_site', 'spip_syndic', 'id_syndic=' . $site['id_syndic']);
			$result = updateWebsite($href, 1);
			spip_log($result, 'test.' . _LOG_ERREUR);
			sql_insertq('spip_monitor_log', array('id_syndic' => $site['id_syndic'], 'log' => $result['result'], 'latency' => $result['latency']));
		}

		$taches_generales['monitor'] = 90; 
	}
		
	return $taches_generales;
}

?>