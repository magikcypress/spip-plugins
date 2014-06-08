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
	if (trouver_objet_exec($flux['args']['exec'] == "site")){

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

	// si on est sur la liste des site référencés
	if (trouver_objet_exec($flux['args']['exec'] == "sites")){

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

?>