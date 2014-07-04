<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
// Fichier source, a modifier dans svn://zone.spip.org/spip-zone/_core_/plugins/monitor/lang/
if (!defined('_ECRIRE_INC_VERSION')) return;

$GLOBALS[$GLOBALS['idx_lang']] = array(

	// A
	'activer_monitor' => 'Activer',
	'activer_monitor_ping' => 'Activer le ping',
	'activer_monitor_poids' => 'Activer le poids des pages',

	// B
	'bouton_activer_ping' => 'Activer le "ping" sur l\'ensemble des sites',
	'bouton_activer_poids' => 'Activer le "poids" sur l\'ensemble des sites',
	'bouton_desactiver_ping' => 'Désactiver le "ping" sur l\'ensemble des sites',
	'bouton_desactiver_poids' => 'Désactiver le "poids" sur l\'ensemble des sites',

	// L
	'legend_obligatoire_monitor' => 'Variables fixes et obligatoires',
	'legend_explication_obligatoire_monitor' => ' ',
	'legend_recommande_monitor' => 'Variables optionnelles dépendant de chaque page auditée (utilisation fortement recommandée)',
	'legend_explication_recommande_monitor' => ' ',
	'legend_activer_monitor' => 'Choix d\'activer monitor',
	'legend_explication_activer_monitor' => ' ',

	// F
	'form_url_site' => 'Nom du site',
	'form_date_ping' => 'Date ping',
	'form_latence' => 'Latence (en ms)',
	'form_poids' => 'Poids (en Kb)',

	// I
	'icone_monitor_configuration' => 'Configurer Monitor',
	'icone_monitor_editer' => 'Lister sites Monitorés',
	'info_site_ping' => 'Le site ping bien.',
	'info_site_noping' => 'Le site ne ping plus.',
	'item_utiliser_monitor' => 'Activer Monitor',
	'item_utiliser_monitor_ping' => 'Activer ping',
	'item_utiliser_monitor_poids' => 'Activer poids page',
	'item_non_utiliser_monitor' => 'Désactiver Monitor',
	'item_non_utiliser_monitor_ping' => 'Désactiver ping',
	'item_non_utiliser_monitor_poids' => 'Désactiver poids page',

	// T
	'texte_monitor' => '<p>Activer Monitor, puis renseigner le formulaire de configuration du plugin</p>',
	'texte_monitor_site' => '<p>Activer le monitoring pour ce site</p>',
	'texte_monitor_sites' => '<p>Activer le monitoring pour tout les sites</p>',
	'texte_monitor_poids' => '<p>Activer le monitoring (poids page) pour ce site</p>',
	'texte_monitor_compteur_aucun_ping' => 'Il n\'y a aucun site qui a "ping" d\'activé sur @site_publie@ sites',
	'texte_monitor_compteur_aucun_poids' => 'Il n\'y a aucun site qui a "poids" d\'activé sur @site_publie@ sites',
	'texte_monitor_compteur_ping' => 'Il y a @nb@ site qui a "ping" d\'activé sur @site_publie@ sites',
	'texte_monitor_compteur_poids' => 'Il y a @nb@ site qui a "poids" d\'activé sur @site_publie@ sites',
	'texte_monitor_compteurs_ping' => 'Il y a @nb@ sites qui ont "ping" d\'activé sur @site_publie@ sites',
	'texte_monitor_compteurs_poids' => 'Il y a @nb@ sites qui ont "poids" d\'activé sur @site_publie@ sites',
	'titre_configurer' => 'Configurer Monitor',
	'titre_monitor' => 'Configuration du plugin Monitor',
	'titre_monitor_site' => 'Activer Monitor pour ce site',
	'titre_monitor_sites' => 'Activer Monitor pour les sites',
	'titre_monitor_ping' => 'Activer le monitoring (ping) pour ce site',
	'titre_monitor_poids' => 'Activer le monitoring (poids page) pour ce site',
	'titre_page_monitor_ping' => 'Liste des sites sous monitor (ping)',
	'titre_page_monitor_poids' => 'Liste des sites sous monitor (poids)',
);
