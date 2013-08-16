<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2012                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/


if (!defined('_ECRIRE_INC_VERSION')) return;

function formulaires_ecrire_votre_avis_charger_dist(){
	include_spip('inc/texte');
	$puce = definir_puce();
	$valeurs = array(
		'choix_message_votre_avis'=>'',
		'texte_message_votre_avis'=>'',
		'config_nbcaracteres_votre_avis' => $GLOBALS['meta']['config_nbcaracteres_votre_avis']
	);

	return $valeurs;
}

function formulaires_ecrire_votre_avis_verifier_dist(){
	$erreurs = array();
	include_spip('inc/filtres');
	include_spip('inc/votre_avis');

	if(!$choix=_request('choix_message_votre_avis'))
		$erreurs['choix_message_votre_avis'] = _T('info_obligatoire');

	// NoSpam attack
    include_spip('inc/texte');
    // si nospam est present on traite les spams
    if (include_spip('inc/nospam')) {

        // on analyse le sujet
        $infos_texte = analyser_spams($texte);
        // si un lien dans le sujet = spam !
        if ($infos_texte['nombre_liens'] > 0)
                $erreurs['texte_message_votre_avis'] = _T('nospam:erreur_spam');	

        // on analyse le texte
        $infos_texte = analyser_spams($texte);
        if ($infos_texte['nombre_liens'] > 0) {
                // si un lien a un titre de moins de 3 caracteres = spam !
                if ($infos_texte['caracteres_texte_lien_min'] < 3) {
                        $erreurs['texte_message_votre_avis'] = _T('nospam:erreur_spam');
                }
                // si le texte contient plus de trois lien = spam !
                if ($infos_texte['nombre_liens'] >= 3)
                        $erreurs['texte_message_votre_avis'] = _T('nospam:erreur_spam');
        }
    }	

	if (!$texte=_request('texte_message_votre_avis'))
		$erreurs['texte_message_votre_avis'] = _T("info_obligatoire");
	elseif((strlen($texte)<10))
		$erreurs['texte_message_votre_avis'] = _T('votre_avis:votre_avis_attention_dix_caracteres');
	elseif(!(strlen($texte)<$GLOBALS['meta']['activer_votre_avis']))
		$erreurs['texte_message_votre_avis'] = _T('votre_avis:votre_avis_attention_cent_caracteres');
	elseif(!bloque_indesirable($texte))
		$erreurs['texte_message_votre_avis'] = _T('votre_avis:votre_avis_anonyme_erreur');		
	elseif(!email_detecte($texte))
		$erreurs['texte_message_votre_avis'] = _T('votre_avis:votre_avis_anonyme_erreur');		
	elseif(url_detect_fragment($texte) || url_detecte($texte))
		$erreurs['texte_message_votre_avis'] = _T('votre_avis:votre_avis_anonyme_erreur');

	if (!_request('confirmer') AND !count($erreurs)) {
		$erreurs['previsu']=' ';
	}
	return $erreurs;
}

function formulaires_ecrire_votre_avis_traiter_dist($id_votre_avis='new', $id_rubrique, $retour='', $lier_trad=0, $config_fonc='votre_avis_edit_config', $row=array(), $hidden=''){

	global $table_des_traitements;
	include_spip('public/composer');

	$choix = _request('choix_message_votre_avis');
	$texte = _request('texte_message_votre_avis');

	include_spip('inc/rubriques');

	$id_rubrique = $GLOBALS['meta']['config_rubrique_votre_avis'];

	// Si id_rubrique vaut 0 ou n'est pas definie, creer le votre_avis
	// dans la premiere rubrique racine
	if (!$id_rubrique = intval($id_rubrique)) {
		$id_rubrique = sql_getfetsel("id_rubrique", "spip_rubriques", "id_parent=0",'', '0+titre,titre', $id_rubrique);
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
		'langue_choisie' => 'non',
		'titre' => safehtml($choix),
		'texte' => safehtml($texte));
	
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
	$desc = $id_votre_avis;

	$message = _T('votre_avis:votre_avis_envoyer');
	return array('message_ok'=>$message);
}

?>