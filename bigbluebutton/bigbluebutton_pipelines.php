<?php
function bigbluebutton_affiche_droite($flux){

        // Affiche le bouton pour accéder à la webconf par default
	if ($flux['args']['exec'] == 'auteur_infos' && $flux['args']['id_auteur'] == $GLOBALS['visiteur_session']['id_auteur']){

		$boite = debut_boite_info(true)
			. icone_horizontale(
				_T('bigbluebutton:acces_videoconf'),
				generer_url_ecrire('bigbluebutton_connect_demo','id_auteur='.$flux['args']['id_auteur']),
				find_in_path('bigbluebutton-22.png', 'img_pack/', false),
				'',
				false
			)
			. fin_boite_info(true);

		$flux['data'] .= $boite;

	}

	return $flux;
}

function bigbluebutton_header_prive($flux) {
	// On ajoute un CSS pour le back-office
	$flux .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._DIR_PLUGIN_BIGBLUEBUTTON."css/styles.css\" />";
	return $flux;
}

function bigbluebutton_header_public($flux){
        $csspublic = find_in_path('css/styles.css');
        $flux .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$csspublic."\" />";
        return $flux;
}

function bigbluebutton_taches_generales_cron($taches_generales){
        $taches_generales['bigbluebutton_automatisation_deleteroom'] = 60 * 59;
	return $taches_generales;
}

?>