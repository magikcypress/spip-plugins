<?php
function formulaires_esmlm_list_edit_charger_dist($esmlm_id = -1) {

	if ($esmlm_id == -1 || !$valeurs = sql_fetsel('*', 'spip_esmlm_lists', 'esmlm_id='.intval($esmlm_id))) {

		$valeurs = array(
			'esmlm_id' => -1,
			'esmlm_inscription' => '',
			'esmlm_chemin_retour' => '',
			'esmlm_sujet' => ''
		);

	}

	return $valeurs;
}

function formulaires_esmlm_list_edit_verifier_dist() {
        include_spip('inc/filtres');
	$mails = array('esmlm_inscription', 'esmlm_chemin_retour');
	$keys = array('esmlm_inscription', 'esmlm_chemin_retour');
	$erreurs = array();
	foreach($keys as $obligatoire) {
		if (!_request($obligatoire)) {
			$erreurs[$obligatoire] = _T('esmlm:ce_champ_est_obligatoire');
		}
	}
	foreach($mails as $mail) {
		if (!email_valide(_request($mail))) {
                $erreurs[$mail] = _T('esmlm:cette_adresse_email_n_est_pas_valide');
                }
	}

	if (count($erreurs)) {
		$erreurs['message_erreur'] = _T('esmlm:veuillez_corriger_votre_saisie');
	}
	return $erreurs;
}


function formulaires_esmlm_list_edit_traiter_dist($esmlm_id) {
        $champs = array(
            'esmlm_inscription' => _request('esmlm_inscription'),
            'esmlm_chemin_retour' => _request('esmlm_chemin_retour'),
            'esmlm_sujet' => _request('esmlm_sujet')
        );

        sql_updateq('spip_esmlm_lists', $champs, "esmlm_id = ".intval(_request('esmlm_id')));
        spip_log('spip_esmlm_lists', $champs, "esmlm_id = ".intval(_request('esmlm_id')));
        spip_log('Modification du message « '._request('esmlm_inscription').' » (id = '._request('esmlm_id').')', 'esmlm');

 	return array('message_ok' => 'ok', 'redirect' => generer_url_ecrire('esmlm_lists'));
}

?>