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
 * Afficher monitor milieu page site
 * @param array $flux
 * @return array
 */
function monitor_affiche_milieu($flux){

	// si on est sur un site ou il faut activer le monitor...
	if (lire_config('monitor/activer_monitor') == "oui" and trouver_objet_exec($flux['args']['exec'] == "site")){
		$id_syndic = _request('id_syndic');
		$texte = recuperer_fond(
				'prive/objets/editer/monitor',
				array(
					'id_syndic'=>$id_syndic
				)
		);
		$texte .= recuperer_fond(
				'prive/objets/contenu/monitor_graph',
				array(
					'id_syndic'=>$id_syndic,
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
				'prive/objets/editer/monitors'
		);
		if ($p=strpos($flux['data'],"<!--affiche_milieu-->"))
			$flux['data'] = substr_replace($flux['data'],$texte,$p,0);
		else
			$flux['data'] .= $texte;
	}

	return $flux;
}

/**
 * Taches periodiques de monitor 
 *
 * @param array $taches_generales
 * @return array
 */
function monitor_taches_generales_cron($taches_generales){


	if (lire_config('monitor/activer_monitor') == "oui") {
		$taches_generales['monitor'] = 90; 
	}
		
	return $taches_generales;
}

?>