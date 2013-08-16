<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

// On déclare ici la config du core
function votre_avis_ieconfig_metas($table){
	$table['votre_avis']['titre'] = _T('votre_avis:titre_votre_avis');
	$table['votre_avis']['icone'] = 'votre-avis-16.png';
	$table['votre_avis']['metas_brutes'] = 'activer_votre_avis,config_rubrique_votre_avis,config_nbcaracteres_votre_avis';
	
	return $table;
}

?>