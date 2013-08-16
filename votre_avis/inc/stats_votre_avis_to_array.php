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

include_spip('inc/votre_avis');

function inc_stats_votre_avis_to_array_dist($unite, $duree, $id_rubrique, $choix, $options = array()) {
	$now = time();

	if (!in_array($unite,array('jour','mois')))
		$unite = 'jour';
	$serveur = '';

	$table = "spip_votre_avis";
	$order = "date_heure";
	$where = array();
	$where[] = "statut='publie'";
	if ($duree)
		$where[] = sql_date_proche($order,-$duree,'day',$serveur);

	if ($id_rubrique) {
			$table = "spip_votre_avis";
			$where[] = "id_rubrique=".intval($id_rubrique);
	}

	if ($choix) {
			$table = "spip_votre_avis";
			$where[] = "titre LIKE '".$choix."'";
	}
	$where = implode(" AND ",$where);
	$format = ($unite=='jour'?'%Y-%m-%d':'%Y-%m-01');

	$res = sql_select("COUNT(titre) AS v, titre as c, DATE_FORMAT($order,'$format') AS d", $table, $where, "c", "d", "",'',$serveur);
	$format = str_replace('%','',$format);
	$periode = ($unite=='jour'?24*3600:365*24*3600/12);
	$step = intval(round($periode*1.1,0));

	$data = array();
	$r = sql_fetch($res,$serveur);
	if (!$r){
		$r = array('d'=>date($format,$now),'v'=>0,'c'=>'0');
	}
	do {
		// print $r['c'];
		// print $r['v'];
		$data[$r['d']] = array('votre_avis'=>$r['v'], $r['c']=>$r['c']);
		array_push($data[$r['d']], $r['c']);
		$last = $r['d'];

		// donnee suivante
		$r = sql_fetch($res,$serveur);
		// si la derniere n'est pas la date courante, l'ajouter
		if (!$r AND $last!=date($format,$now))
			$r = array('d'=>date($format,$now),'v'=>0);

		// completer les trous manquants si besoin
		if ($r){
			$next = strtotime($last);
			$current = strtotime($r['d']);
			while (($next+=$step)<$current AND $d=date($format,$next)){
				if (!isset($data[$d]))
					$data[$d] = array('votre_avis'=>0);
				$last = $d;
				$next = strtotime($last);
			}
		}
	}
	while ($r);	

	$data[$last]['votre_avis'] = $data[$last]['votre_avis'];
	print_r($data);


  return $data;

}


?>
