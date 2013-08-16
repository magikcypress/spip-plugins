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

if (!defined("_ECRIRE_INC_VERSION")) return;

function formulaires_configurer_votre_avis_charger_dist(){
	foreach(array(
		"activer_votre_avis",
		"config_rubrique_votre_avis",
		"config_nbcaracteres_votre_avis",
		) as $m)
		$valeurs[$m] = $GLOBALS['meta'][$m];

	return $valeurs;
}


function formulaires_configurer_votre_avis_traiter_dist(){

	$res = array('editable'=>true);
	foreach(array(
		"activer_votre_avis",
		"config_rubrique_votre_avis",
		"config_nbcaracteres_votre_avis",
		) as $m)
		if (!is_null($v=_request($m))) {
			if ($m=="activer_votre_avis")
				ecrire_meta($m, $v=='oui'?'oui':'non');
			else {
				ecrire_meta(''. $m . '', $v);
			}
		}

	$res['message_ok'] = _T('config_info_enregistree');
	return $res;
}

