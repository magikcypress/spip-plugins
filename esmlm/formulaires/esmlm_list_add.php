<?php
function formulaires_esmlm_list_add_charger_dist($esmlm_id = -1) {

            $valeurs = sql_fetsel('*', 'spip_esmlm_lists', 'esmlm_id='.intval($esmlm_id));

		$valeurs = array(
			'esmlm_id' => -1,
			'esmlm_inscription' => '',
			'esmlm_chemin_retour' => '',
			'esmlm_sujet' => ''
		);

	return $valeurs;
}

function formulaires_esmlm_list_add_verifier_dist() {
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

function formulaires_esmlm_list_add_traiter_dist() {
	include_spip('base/abstract_sql');

	// On récupère les champs
	$esmlm_inscription = _request('esmlm_inscription');
	$esmlm_chemin_retour = _request('esmlm_chemin_retour');
	$esmlm_sujet = _request('esmlm_sujet');

	// Si le menu existe on modifie
	if ($esmlm_id = intval(_request('esmlm_id'))){
		sql_updateq(
			'spip_esmlm_lists',
			array(
				'esmlm_inscription' => $esmlm_inscription,
				'esmlm_chemin_retour' => $esmlm_chemin_retour,
				'esmlm_sujet' => $esmlm_sujet
			),
			'esmlm_id = '.$esmlm_id
		);
	}
	// Sinon on le crée
	else{
		$esmlm_id = sql_insertq(
			'spip_esmlm_lists',
			array(
				'esmlm_inscription' => $esmlm_inscription,
				'esmlm_chemin_retour' => $esmlm_chemin_retour,
				'esmlm_sujet' => $esmlm_sujet
			)
		);

        }

        spip_log('Création d\'une liste', 'esmlm');
 	return array('message_ok' => 'ok', 'editable' => 1);
}

?>