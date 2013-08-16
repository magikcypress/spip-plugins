<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/acces');
include_spip('inc/votre_avis');

function stats_total($serveur=''){
	$row = sql_fetsel("SUM(titre) AS total_absolu", "spip_votre_avis",'','','','','',$serveur);
	return $row ? $row['total_absolu'] : 0;
}