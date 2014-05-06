<?php

/**
 * Pipeline pour Xiti
 *
 * @plugin     Xiti
 * @copyright  2014
 * @author     Vincent
 * @licence    GNU/GPL
 * @package    SPIP\Xiti\
 */

if (!defined("_ECRIRE_INC_VERSION")) return;

		
function formulaires_configurer_xiti_charger_dist(){
	foreach(array(
		"activer_xiti",
		'xtnv_xiti',
		'xtsd_xiti',
		'xtsite_xiti',
		'xtpage_xiti',
		'xtn2_xiti',
		'xtdi_xiti'
		) as $m)
		$valeurs[$m] = $GLOBALS['meta'][$m];

	return $valeurs;
}


function formulaires_configurer_xiti_traiter_dist(){

	$res = array('editable'=>true);
	foreach(array(
		"activer_xiti",
		'xtnv_xiti',
		'xtsd_xiti',
		'xtsite_xiti',
		'xtpage_xiti',
		'xtn2_xiti',
		'xtdi_xiti'
		) as $m)
		if (!is_null($v=_request($m))) {
			if ($m=="activer_xiti")
				ecrire_meta($m, $v=='oui'?'oui':'non');
			else {
				ecrire_meta(''. $m . '', $v);
			}
		}

	$res['message_ok'] = _T('config_info_enregistree');
	return $res;
}
