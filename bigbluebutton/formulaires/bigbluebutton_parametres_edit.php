<?php
function formulaires_bigbluebutton_parametres_edit_charger_dist() {
	$keys = array('BBB_MAIL_ADMIN', 'BBB_MAIL_RETURN', 'BBB_URL_WEBCONF', 'BBB_API_KEY', 'BBB_DELAY_CLOSE');
	$valeurs = array();
	foreach($keys as $key) {
		$valeurs[$key] = sql_getfetsel("set_value", "spip_bbb_settings", "set_name=".sql_quote($key));
	}
	return $valeurs;
}

function formulaires_bigbluebutton_parametres_edit_verifier_dist() {
  include_spip('inc/filtres');
	$mails = array('BBB_MAIL_ADMIN', 'BBB_MAIL_RETURN');
	$keys = array('BBB_MAIL_ADMIN', 'BBB_MAIL_RETURN', 'BBB_URL_WEBCONF', 'BBB_API_KEY', 'BBB_DELAY_CLOSE');
	$erreurs = array();
	foreach($keys as $obligatoire) {
		if (!_request($obligatoire)) {
			$erreurs[$obligatoire] = _T('bigbluebutton:ce_champ_est_obligatoire');
		}
	}
	foreach($mails as $mail) {
		if (!email_valide(_request($mail))) {
                $erreurs[$mail] = _T('cette_adresse_email_n_est_pas_valide');
                }
	}
	if (count($erreurs)) {
		$erreurs['message_erreur'] = _T('bigbluebutton:veuillez_corriger_votre_saisie');
	}
	return $erreurs;
}

function formulaires_bigbluebutton_parametres_edit_traiter_dist() {
          $keys = array('BBB_MAIL_ADMIN', 'BBB_MAIL_RETURN', 'BBB_URL_WEBCONF', 'BBB_API_KEY', 'BBB_DELAY_CLOSE');
          foreach($keys as $key) {
//           if (sql_countsel("spip_bbb_settings", "set_value="._request($key)." AND set_name=".sql_quote($key)) == 1){
            sql_updateq('spip_bbb_settings', array('set_value' => _request($key)), "set_name=".sql_quote($key));
//           } else {
//            sql_insertq('spip_bbb_settings', array('set_value' => _request($key)), "set_name=".sql_quote($key));
//           }
          }
          spip_log('Modification de la configuration.', 'bigbluebutton');

 	return array('message_ok' => 'ok', 'editable' => 1);
}
?>