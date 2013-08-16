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

include_spip('inc/actions');
include_spip('inc/editer');

function formulaires_editer_votre_avis_charger_dist($id_votre_avis='new', $id_rubrique=0, $retour='', $lier_trad=0, $config_fonc='votre_avis_edit_config', $row=array(), $hidden=''){
	$valeurs = formulaires_editer_objet_charger('votre_avis',$id_votre_avis,$id_rubrique,$lier_trad,$retour,$config_fonc,$row,$hidden);
	// un bug a permis a un moment que des votre_avis soient dans des sous rubriques
	// lorsque ce cas se presente, il faut relocaliser la votre_avis dans son secteur, plutot que n'importe ou
	if ($valeurs['id_parent'])
		$valeurs['id_parent'] = sql_getfetsel('id_secteur','spip_rubriques','id_rubrique='.intval($valeurs['id_parent']));
	return $valeurs;
}

/**
 * Identifier le formulaire en faisant abstraction des parametres qui
 * ne representent pas l'objet edite
 */
function formulaires_editer_votre_avis_identifier_dist($id_votre_avis='new', $id_rubrique=0, $retour='', $lier_trad=0, $config_fonc='votre_avis_edit_config', $row=array(), $hidden=''){
	return serialize(array(intval($id_votre_avis),$lier_trad));
}


// Choix par defaut des options de presentation
function votre_avis_edit_config($row)
{
	global $spip_lang;

	$config = $GLOBALS['meta'];
	$config['lignes'] = 8;
	$config['langue'] = $spip_lang;

	$config['restreint'] = ($row['statut'] == 'publie');
	return $config;
}

function formulaires_editer_votre_avis_verifier_dist($id_votre_avis='new', $id_rubrique=0, $retour='', $lier_trad=0, $config_fonc='votre_avis_edit_config', $row=array(), $hidden=''){
	// auto-renseigner le titre si il n'existe pas
	titre_automatique('titre',array('texte'));
	// on ne demande pas le titre obligatoire : il sera rempli a la volee dans editer_article si vide
	$erreurs = formulaires_editer_objet_verifier('votre_avis',$id_votre_avis,array('id_parent'));
	return $erreurs;
}

// http://doc.spip.org/@inc_editer_article_dist
function formulaires_editer_votre_avis_traiter_dist($id_votre_avis='new', $id_rubrique=0, $retour='', $lier_trad=0, $config_fonc='votre_avis_edit_config', $row=array(), $hidden=''){
	return formulaires_editer_objet_traiter('votre_avis',$id_votre_avis,$id_rubrique,$lier_trad,$retour,$config_fonc,$row,$hidden);
}

?>
