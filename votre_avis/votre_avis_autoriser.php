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

// pour le pipeline d'autorisation
function votre_avis_autoriser(){}

// bouton du bandeau
function autoriser_votre_avis_menu_dist($faire, $type='', $id=0, $qui = NULL, $opt = NULL){
	return 	($GLOBALS['meta']['activer_votre_avis'] != 'non');
}
function autoriser_votre_aviscreer_menu_dist($faire, $type, $id, $qui, $opt){
	return 	($GLOBALS['meta']['activer_votre_avis'] != 'non');
}



// Autoriser a creer une votre_avis dans la rubrique $id
// http://doc.spip.org/@autoriser_rubrique_creervotre_avisdans_dist
function autoriser_rubrique_creervotre_avisdans_dist($faire, $type, $id, $qui, $opt) {
	$r = sql_fetsel("id_parent", "spip_rubriques", "id_rubrique=".intval($id));
	return
		$id
		AND ($r['id_parent']==0)
		AND ($GLOBALS['meta']['activer_votre_avis']!='non')
		AND autoriser('voir','rubrique',$id);
}


// Autoriser a modifier la votre_avis $id
// = admins & redac si la votre_avis n'est pas publiee
// = admins de rubrique parente si publiee
// http://doc.spip.org/@autoriser_votre_avis_modifier_dist
function autoriser_votre_avis_modifier_dist($faire, $type, $id, $qui, $opt) {
	$r = sql_fetsel("id_rubrique,statut", "spip_votre_avis", "id_votre_avis=".intval($id));
	return
		$r AND (
		($r['statut'] == 'publie' OR (isset($opt['statut']) AND $opt['statut']=='publie'))
			? autoriser('publierdans', 'rubrique', $r['id_rubrique'], $qui, $opt)
			: in_array($qui['statut'], array('0minirezo', '1comite'))
		);
}


?>
