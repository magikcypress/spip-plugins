<?php
include_spip('base/abstract_sql');
function formulaires_bigbluebutton_groups_add_charger_dist() {

    	$t=time();
	$valeurs["bbb_date_groups"] = date('Y-m-d H:i:00',$t);

		$valeurs = array(
			'bbb_id' => '',
			'bbb_groups_name' => '',
                        'bbb_date_groups' => $valeurs["bbb_date_groups"]
		);

	// editable, mais le squelette trouvera tout seul la liste de valeurs
	return $valeurs;

}

function formulaires_bigbluebutton_groups_add_verifier_dist($bbb_groups_name = 0) {
      $erreurs = array();
      if (!_request('bbb_groups_name')) {
        $erreurs['bbb_groups_name'] = _T('bigbluebutton:ce_champ_est_obligatoire');
      }
      if (count($erreurs)) {
        $erreurs['message_erreur'] = _T('bigbluebutton:veuillez_corriger_votre_saisie');
      }
      return $erreurs;
}

function formulaires_bigbluebutton_groups_add_traiter_dist($bbb_groups_ids = 0) {

	// On récupère les champs
	$bbb_groups_name = _request('bbb_groups_name');
        $bbb_date_groups = _request('bbb_date_groups');

	$bbb_id_groups = sql_insertq(
		'spip_bbb_groups',
		array(
			'bbb_groups_name' => $bbb_groups_name,
                        'bbb_date_groups' => $bbb_date_groups
		)
	);

        spip_log('Création d\'un groupe', 'bigbluebutton');

	return array('message_ok' => 'ok', 'editable' => 1);
}
?>