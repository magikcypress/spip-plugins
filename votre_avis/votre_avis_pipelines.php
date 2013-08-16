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


/**
 * Definir les meta de configuration liee aux votre_avis
 *
 * @param array $metas
 * @return array
 */
function votre_avis_configurer_liste_metas($metas){
	$metas['activer_votre_avis'] =  'non';
	$metas['config_rubrique_votre_avis'] =  '1'; // rubrique N°1 par defaut
	$metas['config_nbcaracteres_votre_avis'] =  '100'; // 100 caracteres autorisés par defaut
	return $metas;
}

/**
 * Ajouter les votre_avis a valider sur les rubriques 
 *
 * @param array $flux
 * @return array
**/
function votre_avis_rubrique_encours($flux){
	if ($flux['args']['type'] == 'rubrique') {
		$lister_objets = charger_fonction('lister_objets','inc');

		$id_rubrique = $flux['args']['id_objet'];

		//
		// Les votre_avis a valider
		//
		$flux['data'] .= $lister_objets('votre_avis', array(
			'titre'=>_T('votre_avis:info_votre_avis_valider'),
			'statut'=>array('prepa','prop'),
			'id_rubrique'=>$id_rubrique,
			'par'=>'date_heure'));
	}
	return $flux;
}




/**
 * Ajouter les votre_avis references sur les vues de rubriques
 *
 * @param array $flux
 * @return array
**/
function votre_avis_affiche_enfants($flux) {
	if ($e = trouver_objet_exec($flux['args']['exec'])
	  AND $e['type'] == 'rubrique'
	  AND $e['edition'] == false) {
		$id_rubrique = $flux['args']['id_rubrique'];

		if ($GLOBALS['meta']["activer_votre_avis"] == 'oui') {
			$lister_objets = charger_fonction('lister_objets','inc');
			$bouton_votre_avis = '';
			$id_parent = sql_getfetsel('id_parent', 'spip_rubriques', 'id_rubrique='.$id_rubrique);
			if (autoriser('creervotre_avisdans','rubrique',$id_rubrique,NULL,array('id_parent'=>$id_parent))) {
				$bouton_votre_avis .= icone_verticale(_T('votre_avis:icone_nouveau_votre_avis'), generer_url_ecrire("votre_avis_edit","id_rubrique=$id_rubrique&new=oui"), "votre-avis-24.png","new", 'right')
				. "<br class='nettoyeur'>";
			}

			$flux['data'] .= $lister_objets('votre_avis', array('titre'=>_T('votre_avis:icone_ecrire_nouvel_avis'), 'where'=>"statut != 'prop' AND statut != 'prepa'", 'id_rubrique'=>$id_rubrique, 'par'=>'date_heure'));
			$flux['data'] .= $bouton_votre_avis;
		}
	}
	return $flux;
}




/**
 * Bloc sur les informations generales concernant chaque type d'objet
 *
 * @param string $texte
 * @return string
 */
function votre_avis_accueil_informations($texte){
	include_spip('base/abstract_sql');

	if ($GLOBALS['meta']["activer_votre_avis"] == 'oui') {
		$q = sql_select("COUNT(*) AS cnt, statut", 'spip_votre_avis', '', 'statut', '','', "COUNT(*)<>0");

		$cpt_avis = array();
		$cpt_avis2 = array();
		$where = false;
		if ($GLOBALS['visiteur_session']['statut']=='0minirezo'){
			$where = sql_allfetsel('id_objet','spip_auteurs_liens',"objet='rubrique' AND id_auteur=".intval($GLOBALS['visiteur_session']['id_auteur']));
			if ($where){
				$where = sql_in('id_rubrique',array_map('reset',$where));
			}
		}
		$defaut = $where ? '0/' : '';
		while($row = sql_fetch($q)) {
		  $cpt_avis[$row['statut']] = $row['cnt'];
		  $cpt_avis2[$row['statut']] = $defaut;
		}

		if ($cpt_avis) {
			if ($where) {
				$q = sql_select("COUNT(*) AS cnt, statut", 'spip_votre_avis', $where, "statut");
				while($row = sql_fetch($q)) {
					$r = $row['statut'];
					$cpt2[$r] = intval($row['cnt']) . '/';
				}
			}
			$texte .= "<div class='accueil_informations votre_avis liste'>";
			$texte .= "<h4>" . afficher_plus_info(generer_url_ecrire("votre_avis"), "", _T('votre_avis:info_votre_avis_02')) . "</h4>";
			$texte .= "<ul class='liste-items'>";
			if (isset($cpt_avis['prop'])) $texte .= "<li class='item'>"._T("texte_statut_attente_validation").": ".$cpt_avis2['prop'].$cpt_avis['prop'] . '</li>';
			if (isset($cpt_avis['publie'])) $texte .= "<li class='item on'>"._T("texte_statut_publies").": ".$cpt_avis2['publie'] .$cpt_avis['publie'] . '</li>';
			$texte .= "</ul>";
			$texte .= "</div>";
		}
	}
	return $texte;
}


/**
 * Compter les votre_avis dans une rubrique
 * 
 * @param array $flux
 * @return array
 */
function votre_avis_objet_compte_enfants($flux){
	if ($flux['args']['objet']=='rubrique'
	  AND $id_rubrique=intval($flux['args']['id_objet'])) {
		// juste les publies ?
		if (array_key_exists('statut', $flux['args']) and ($flux['args']['statut'] == 'publie')) {
			$flux['data']['votre_avis'] = sql_countsel('spip_votre_avis', "id_rubrique=".intval($id_rubrique)." AND (statut='publie')");
		} else {
			$flux['data']['votre_avis'] = sql_countsel('spip_votre_avis', "id_rubrique=".intval($id_rubrique)." AND (statut='publie' OR statut='prop')");
		}
	}
	return $flux;
}


/**
 * Changer la langue des votre_avis si la rubrique change
 * 
 * @param array $flux
 * @return array
 */
function votre_avis_trig_calculer_langues_rubriques($flux){

	$s = sql_select("A.id_votre_avis AS id_votre_avis, R.lang AS lang", "spip_votre_avis AS A, spip_rubriques AS R", "A.id_rubrique = R.id_rubrique AND A.langue_choisie != 'oui' AND (A.lang='' OR R.lang<>'') AND R.lang<>A.lang");
	while ($row = sql_fetch($s)) {
		$id_votre_avis = $row['id_votre_avis'];
		sql_updateq('spip_votre_avis', array("lang"=>$row['lang'], 'langue_choisie'=>'non'), "id_votre_avis=$id_votre_avis");
	}
		
	return $flux;
}


/**
 * Publier et dater les rubriques qui ont un votre_avis publie
 * 
 * @param array $flux
 * @return array
 */
function votre_avis_calculer_rubriques($flux){

	$r = sql_select("R.id_rubrique AS id, max(A.date_heure) AS date_h", "spip_rubriques AS R, spip_votre_avis AS A", "R.id_rubrique = A.id_rubrique AND R.date_tmp <= A.date_heure AND A.statut='publie' ", "R.id_rubrique");
	while ($row = sql_fetch($r))
	  sql_updateq('spip_rubriques', array('statut_tmp'=>'publie', 'date_tmp'=>$row['date_h']), "id_rubrique=".$row['id']);	
		
	return $flux;
}




/**
 * Ajouter les votre_avis a valider sur la page d'accueil 
 *
 * @param array $flux
 * @return array
**/
function votre_avis_accueil_encours($flux){
	$lister_objets = charger_fonction('lister_objets','inc');

	if ($GLOBALS['meta']["activer_votre_avis"] == 'oui') {
		$flux .= $lister_objets('votre_avis', array(
			'titre'=>afficher_plus_info(generer_url_ecrire('votre_avis'))._T('votre_avis:info_votre_avis_valider'),
			'statut'=>array('prepa','prop'),
			'par'=>'date_heure'));
	}

	return $flux;
}



/**
 * Optimiser la base de donnee en supprimant les liens orphelins
 *
 * @param array $flux
 * @return array
 */
function votre_avis_optimiser_base_disparus($flux){
	$n = &$flux['data'];
	$mydate = $flux['args']['date'];


	# les votre_avis qui sont dans une id_rubrique inexistante
	$res = sql_select("B.id_votre_avis AS id",
		        "spip_votre_avis AS B
		        LEFT JOIN spip_rubriques AS R
		          ON B.id_rubrique=R.id_rubrique",
			"R.id_rubrique IS NULL
		         AND B.maj < $mydate");

	$n+= optimiser_sansref('spip_votre_avis', 'id_votre_avis', $res);


	//
	// Votre Avis
	//

	sql_delete("spip_votre_avis", "statut='refuse' AND maj < $mydate");

	return $flux;

}

/**
 * Afficher le nombre de votre_avis dans chaque rubrique
 *
 * @param array $flux
 * @return array
 */
function votre_avis_boite_infos($flux){
	if ($flux['args']['type']=='rubrique'
	  AND $id_rubrique = $flux['args']['id']){
		if ($nb = sql_countsel('spip_votre_avis',"statut='publie' AND id_rubrique=".intval($id_rubrique))){
			$nb = "<div>". singulier_ou_pluriel($nb, "votre_avis:info_1_votre_avis", "votre_avis:info_nb_votre_avis") . "</div>";
			if ($p = strpos($flux['data'],"<!--nb_elements-->"))
				$flux['data'] = substr_replace($flux['data'],$nb,$p,0);
		}
	}
	return $flux;
}

/**
 * Configuration des contenus
 * @param array $flux
 * @return array
 */
function votre_avis_affiche_milieu($flux){
	if ($flux["args"]["exec"] == "configurer_contenu") {
		$flux["data"] .=  recuperer_fond('prive/squelettes/inclure/configurer',array('configurer'=>'configurer_votre_avis'));
	}
	return $flux;
}

/**
 * Ajout des scripts du votre_avis
 *
 * @param  string $flux  Contenu du head
 * @param  string $lang  Langue en cours d'utilisation
 * @param  bool   $prive Est-ce pour l'espace privé ?
 * @return string Contenu du head complété
 */
// function votre_avis_insert_head_js($flux, $prive = false){
// 	$timetext = find_in_path('public/javascript/votre_avis_timetext.js');
// 	$js_start = parametre_url(generer_url_public('public/javascript/votre_avis_prive_timetext.js'), 'lang', $lang);
// 	if (defined('_VAR_MODE') AND _VAR_MODE=="recalcul")
// 		$js_start = parametre_url($js_start, 'var_mode', 'recalcul');

// 	$flux .= 
// 		   "<script type='text/javascript' src='$timetext'></script>\n";

// 	return $flux;
// }

/**
 * Ajout des CSS du votre_avis au head public
 *
 * Appelé aussi depuis le privé avec $prive à true.
 * 
 * @pipeline insert_head_css
 * @param string $flux  Contenu du head
 * @param  bool  $prive Est-ce pour l'espace privé ?
 * @return string Contenu du head complété
 */
function votre_avis_insert_head_css($flux='', $prive = false){
	include_spip('inc/autoriser');
	// toujours autoriser pour le prive.
	if ($prive or autoriser('afficher_public', 'votre_avis')) {
		if ($prive) {
			$cssprive = find_in_path('public/css/votre_avis_prive.css');
			$flux .= "<link rel='stylesheet' type='text/css' media='all' href='$cssprive' />\n";
		}
		$css = direction_css(find_in_path('public/css/votre_avis.css'), lang_dir());
		$css_icones = generer_url_public('public/votre_avis.css');
		if (defined('_VAR_MODE') AND _VAR_MODE=="recalcul")
			$css_icones = parametre_url($css_icones, 'var_mode', 'recalcul');
		$flux
			.= "<link rel='stylesheet' type='text/css' media='all' href='$css' />\n";
	}
	return $flux;
}

?>
