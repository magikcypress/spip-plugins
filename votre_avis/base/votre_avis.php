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

/**
 * Interfaces des tables votre_avis pour le compilateur
 *
 * @param array $interfaces
 * @return array
 */
function votre_avis_declarer_tables_interfaces($interfaces) {
	$interfaces['table_des_tables']['votre_avis'] = 'votre_avis';

	$interfaces['exceptions_des_tables']['votre_avis']['id_secteur'] = 'id_rubrique';
	$interfaces['exceptions_des_tables']['votre_avis']['date'] = 'date_heure';
	// $interfaces['exceptions_des_tables']['votre_avis']['nom_site'] = 'lien_titre';
	// $interfaces['exceptions_des_tables']['votre_avis']['url_site'] = 'lien_url';

	// $interfaces['table_des_traitements']['LIEN_TITRE'][]= _TRAITEMENT_TYPO;
	// $interfaces['table_des_traitements']['LIEN_URL'][]= 'vider_url(%s)';
	$interfaces['table_des_traitements']['TITLE']['votre_avis'] = "safehtml(".str_replace("%s","interdit_html(%s)",_TRAITEMENT_RACCOURCIS).")";
	$interfaces['table_des_traitements']['TEXTE']['votre_avis'] = "safehtml(".str_replace("%s","interdit_html(%s)",_TRAITEMENT_RACCOURCIS).")";
	
	return $interfaces;
}


function votre_avis_declarer_tables_objets_sql($tables){
	$tables['spip_votre_avis'] = array(
		'texte_retour' => 'icone_retour',
		'texte_objets' => 'votre_avis:votre_avis',
		'texte_objet' => 'votre_avis:votre_avis',
		'texte_modifier' => 'votre_avis:icone_modifier_votre_avis',
		'texte_creer' => 'votre_avis:icone_nouveau_votre_avis',
		'info_aucun_objet'=> 'votre_avis:info_aucun_votre_avis',
		'info_1_objet' => 'votre_avis:info_1_votre_avis',
		'info_nb_objets' => 'votre_avis:info_nb_votre_avis',
		'texte_logo_objet' => 'votre_avis:logo_votre_avis',
		'texte_langue_objet' => 'votre_avis:titre_langue_votre_avis',
		'titre' => 'titre, lang',
		'date' => 'date_heure',
		'principale' => 'oui',
		'field'=> array(
			"id_votre_avis"	=> "bigint(21) NOT NULL",
			"date_heure"	=> "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
			"titre"	=> "text DEFAULT '' NOT NULL",
			"texte"	=> "longtext DEFAULT '' NOT NULL",
			// "lien_titre"	=> "text DEFAULT '' NOT NULL",
			// "lien_url"	=> "text DEFAULT '' NOT NULL",
			"statut"	=> "varchar(6)  DEFAULT '0' NOT NULL",
			"id_rubrique"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"lang"	=> "VARCHAR(10) DEFAULT '' NOT NULL",
			"langue_choisie"	=> "VARCHAR(3) DEFAULT 'non'",
			"maj"	=> "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_votre_avis",
			"KEY id_rubrique"	=> "id_rubrique",
		),
		'join' => array(
			"id_votre_avis"=>"id_votre_avis",
			"id_rubrique"=>"id_rubrique"
		),
		'statut' =>  array(
			array(
				'champ'=>'statut',
				'publie'=>'publie',
				'previsu'=>'publie,prop',
				'exception'=>'statut'
			)
		),
		'texte_changer_statut' => 'votre_avis:entree_votre_avis_publiee',
		'aide_changer_statut' => 'votre_avistatut',
		'statut_titres' => array(
			'prop' => 'votre_avis:titre_votre_avis_proposee',
			'publie' => 'votre_avis:titre_votre_avis_publiee',
			'refuse' => 'votre_avis:titre_votre_avis_refusee',
		),
		'statut_textes_instituer' => 	array(
			'prop' => 'votre_avis:item_votre_avis_proposee', //_T('texte_statut_propose_evaluation')
			'publie' => 'votre_avis:item_votre_avis_validee', //_T('texte_statut_publie')
			'refuse' => 'votre_avis:item_votre_avis_refusee', //_T('texte_statut_refuse')
		),

		'rechercher_champs' => array(
		  'titre' => 8, 'texte' => 2 /*, 'lien_titre' => 1, 'lien_url' => 1 */
		),
		'rechercher_jointures' => array(
			'document' => array('titre' => 2, 'descriptif' => 1)
		),
		'champs_versionnes' => array('id_rubrique', 'titre'/*, 'lien_titre', 'lien_url'*/, 'texte'),
	);

	return $tables;
}


?>
