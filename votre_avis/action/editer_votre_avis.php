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

// http://doc.spip.org/@action_editer_votre_avis_dist
function action_editer_votre_avis_dist($arg=null) {

	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}

	// Envoi depuis le formulaire d'edition d'une votre_avis
	if (!$id_votre_avis = intval($arg)) {
		$id_votre_avis = votre_avis_inserer(_request('id_parent'));
	}

	if (!$id_votre_avis)
		return array(0,''); // erreur

	$err = votre_avis_modifier($id_votre_avis);

	return array($id_votre_avis,$err);
}

/**
 * Inserer une votre_avis en base
 * http://doc.spip.org/@insert_votre_avis
 *
 * @param int $id_rubrique
 * @return int
 */
function votre_avis_inserer($id_rubrique) {

	include_spip('inc/rubriques');

	// Si id_rubrique vaut 0 ou n'est pas definie, creer le votre_avis
	// dans la premiere rubrique racine
	if (!$id_rubrique = intval($id_rubrique)) {
		$id_rubrique = sql_getfetsel("id_rubrique", "spip_rubriques", "id_parent=0",'', '0+titre,titre', "1");
	}

	// La langue a la creation : c'est la langue de la rubrique
	$row = sql_fetsel("lang, id_secteur", "spip_rubriques", "id_rubrique=$id_rubrique");
	$lang = $row['lang'];
	$id_rubrique = $row['id_secteur']; // garantir la racine

	$champs = array(
		'id_rubrique' => $id_rubrique,
		'statut' => 'prop',
		'date_heure' => date('Y-m-d H:i:s'),
		'lang' => $lang,
		'langue_choisie' => 'non');
	
	// Envoyer aux plugins
	$champs = pipeline('pre_insertion',
		array(
			'args' => array(
				'table' => 'spip_votre_avis',
			),
			'data' => $champs
		)
	);
	$id_votre_avis = sql_insertq("spip_votre_avis", $champs);
	pipeline('post_insertion',
		array(
			'args' => array(
				'table' => 'spip_votre_avis',
				'id_objet' => $id_votre_avis
			),
			'data' => $champs
		)
	);
	return $id_votre_avis;
}


/**
 * Modifier une votre_avis en base
 * $c est un contenu (par defaut on prend le contenu via _request())
 *
 * http://doc.spip.org/@revisions_votre_avis
 *
 * @param int $id_votre_avis
 * @param array $set
 * @return string|bool
 */
function votre_avis_modifier($id_votre_avis, $set=null) {

	include_spip('inc/modifier');
	$c = collecter_requests(
		// white list
		array('titre', 'texte'/*, 'lien_titre', 'lien_url'*/),
		// black list
		array('id_parent', 'statut'),
		// donnees eventuellement fournies
		$set
	);

	// Si le votre_avis est publiee, invalider les caches et demander sa reindexation
	$t = sql_getfetsel("statut", "spip_votre_avis", "id_votre_avis=$id_votre_avis");
	if ($t == 'publie') {
		$invalideur = "id='votre_avis/$id_votre_avis'";
		$indexation = true;
	}

	if ($err = objet_modifier_champs('votre_avis', $id_votre_avis,
		array(
			'nonvide' => array('titre' => _T('votre_avis:titre_nouvelle_votre_avis')." "._T('info_numero_abbreviation').$id_votre_avis),
			'invalideur' => $invalideur,
			'indexation' => $indexation
		),
		$c))
		return $err;

	$c = collecter_requests(array('id_parent', 'statut'),array(),$set);
	$err = votre_avis_instituer($id_votre_avis, $c);
	return $err;
}

/**
 * Instituer une votre_avis : modifier son statut ou son parent
 *
 * @param int $id_votre_avis
 * @param array $c
 * @return string
 */
function votre_avis_instituer($id_votre_avis, $c) {
	$champs = array();

	// Changer le statut de la votre_avis ?
	$row = sql_fetsel("statut, id_rubrique,lang, langue_choisie", "spip_votre_avis", "id_votre_avis=".intval($id_votre_avis));
	$id_rubrique = $row['id_rubrique'];

	$statut_ancien = $statut = $row['statut'];
	$langue_old = $row['lang'];
	$langue_choisie_old = $row['langue_choisie'];

	if ($c['statut']
	AND $c['statut'] != $statut
	AND autoriser('publierdans', 'rubrique', $id_rubrique)) {
		$statut = $champs['statut'] = $c['statut'];
	}

	// Changer de rubrique ?
	// Verifier que la rubrique demandee est a la racine et differente
	// de la rubrique actuelle
	if ($id_parent = intval($c['id_parent'])
	AND $id_parent != $id_rubrique
	AND (NULL !== ($lang=sql_getfetsel('lang', 'spip_rubriques', "id_parent=0 AND id_rubrique=".intval($id_parent))))) {
		$champs['id_rubrique'] = $id_parent;
		// - changer sa langue (si heritee)
		if ($langue_choisie_old != "oui") {
			if ($lang != $langue_old)
				$champs['lang'] = $lang;
		}
		// si la votre_avis est publiee
		// et que le demandeur n'est pas admin de la rubrique
		// repasser la votre_avis en statut 'prop'.
		if ($statut == 'publie') {
			if (!autoriser('publierdans','rubrique',$id_parent))
				$champs['statut'] = $statut = 'prop';
		}
	}

	// Envoyer aux plugins
	$champs = pipeline('pre_edition',
		array(
			'args' => array(
				'table' => 'spip_votre_avis',
				'id_objet' => $id_votre_avis,
				'action'=>'instituer',
				'statut_ancien' => $statut_ancien,
			),
			'data' => $champs
		)
	);

	if (!$champs) return;

	sql_updateq('spip_votre_avis', $champs, "id_votre_avis=".intval($id_votre_avis));

	//
	// Post-modifications
	//

	// Invalider les caches
	include_spip('inc/invalideur');
	suivre_invalideur("id='votre_avis/$id_votre_avis'");

	// Au besoin, changer le statut des rubriques concernees 
	include_spip('inc/rubriques');
	calculer_rubriques_if($id_rubrique, $champs, $statut_ancien);

	// Pipeline
	pipeline('post_edition',
		array(
			'args' => array(
				'table' => 'spip_votre_avis',
				'id_objet' => $id_votre_avis,
				'action'=>'instituer',
				'statut_ancien' => $statut_ancien,
			),
			'data' => $champs
		)
	);


	// Notifications
	if ($notifications = charger_fonction('notifications', 'inc')) {
		$notifications('instituervotre_avis', $id_votre_avis,
			array('statut' => $statut, 'statut_ancien' => $statut_ancien)
		);
	}

	return ''; // pas d'erreur
}

function insert_votre_avis($id_rubrique) {
	return votre_avis_inserer($id_rubrique);
}
function revisions_votre_avis ($id_votre_avis, $set=false) {
	return votre_avis_modifier($id_votre_avis,$set);
}
?>