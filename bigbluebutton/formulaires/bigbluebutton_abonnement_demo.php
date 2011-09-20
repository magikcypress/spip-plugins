<?php
function formulaires_bigbluebutton_abonnement_demo_charger_dist() {

        $valeurs = array('editable' => ' ', 'bbb_attendee_email' => '', 'bbb_date_debut' => '');

	if ($GLOBALS['visiteur_session']['email']) {
		$valeurs['bbb_attendee_email'] = $GLOBALS['visiteur_session']['email'];
	}

		$valeurs = array(
			'bbb_id' => '',
			'bbb_meeting_room_name' => '',
			'bbb_attendee_email' => $valeurs['bbb_attendee_email'],
                        'bbb_attendee_ip' => $_SERVER['REMOTE_ADDR'],
                        'bbb_date_debut' => '',
                        'bbb_date_fin' => ''
		);

	// editable, mais le squelette trouvera tout seul la liste de valeurs
	return $valeurs;

}

function formulaires_bigbluebutton_abonnement_demo_verifier_dist($bbb_meeting_room_name = 0) {
      $erreurs = array();
      if (!_request('bbb_meeting_room_name')) {
        $erreurs['bbb_meeting_room_name'] = _T('bigbluebutton:ce_champ_est_obligatoire');
      }
      include_spip('inc/filtres');
      if (_request('bbb_attendee_email') && !email_valide(_request('bbb_attendee_email'))) {
        $erreurs['bbb_attendee_email'] = _T('bigbluebutton:cette_adresse_email_n_est_pas_valide');
      }
      if (count($erreurs)) {
        $erreurs['message_erreur'] = _T('bigbluebutton:veuillez_corriger_votre_saisie');
      }
      return $erreurs;
}

function formulaires_bigbluebutton_abonnement_demo_traiter_dist($bbb_ids = 0) {
	include_spip('base/abstract_sql');
        include_spip('bbb_api');

	// On récupère les champs
	$bbb_meeting_room_name = _request('bbb_meeting_room_name');
        $bbb_attendee_name = _T('bigbluebutton:demo_meeting');
        $bbb_attendee_pwd = uniqid();
        $bbb_attendee_email = _request('bbb_attendee_email');
        $bbb_attendee_ip = _request('bbb_attendee_ip');
        $bbb_moderator_name = _T('bigbluebutton:moderateur');
        $bbb_moderator_email = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_MAIL_ADMIN"');
        $bbb_moderator_pwd = uniqid();
        $bbb_clef = md5('subscribe#'.$bbb_attendee_ip.'#'.time());

	$t=time();
	$bbb_date_debut = date('Y-m-d H:i:00',$t);
	$bbb_date_fin = date('Y-m-d H:i:00',$t+3600);
        $date_consultation = $bbb_date_debut . " - " . $bbb_date_fin;

        $bbb_api_key = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_API_KEY"');
        $bbb_url_webconf = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_URL_WEBCONF"');
        $bbb_reponse_server = BigBlueButton::createMeetingArray($bbb_meeting_room_name, $bbb_attendee_name, _T('bigbluebutton:bienvenue_webconf'), "mp", "ap", $bbb_api_key, $bbb_url_webconf, $GLOBALS['meta']['adresse_site']);
	$bbb_join_url_attendee = BigBlueButton::joinURL($bbb_attendee_name, $bbb_meeting_room_name , "ap", $bbb_api_key, $bbb_url_webconf);
        $bbb_join_url_moderator = BigBlueButton::joinURL($bbb_attendee_name, $bbb_moderator_name , "mp", $bbb_api_key, $bbb_url_webconf);

        // Traitement erreur serveur bigbluebutton
        //$bbb_reponse_server = print_r($bbb_reponse_server);
        if(!$bbb_reponse_server){
              // Erreur de connexion au serveur
              $bbb_reponse_server = _T('bigbluebutton:erreur_serveurbbb');
        }else if( $bbb_reponse_server['returncode'][0] == 'FAILED' ) {
                 if($bbb_reponse_server['messageKey'][0] == 'checksumError')
                      $bbb_reponse_server = $bbb_reponse_server['message'][0];
                 if($bbb_reponse_server['messageKey'][0] == 'idNotUnique')
                      $bbb_reponse_server = $bbb_reponse_server['message'][0];
       } else {
                $bbb_reponse_server = $bbb_reponse_server['returncode'][0];
                 if(!$bbb_reponse_server['attendeePW'][0])
                     $bbb_attendee_pwd = $bbb_attendee_pwd;
                 else
                     $bbb_attendee_pwd = $bbb_reponse_server['attendeePW'][0];
                 if(!$bbb_reponse_server['moderatorPW'][0])
                     $bbb_moderator_pwd = $bbb_moderator_pwd;
                 else
                     $bbb_moderator_pwd = $bbb_reponse_server['moderatorPW'][0];
        }

	$bbb_id = sql_insertq(
		'spip_bbb_meetingrooms',
		array(
			'bbb_meeting_room_name' => $bbb_meeting_room_name,
                        'bbb_attendee_email' => $bbb_attendee_email,
                        'bbb_attendee_pwd' => $bbb_attendee_pwd,
                        'bbb_attendee_ip' => $bbb_attendee_ip,
			'bbb_moderator_pwd' => $bbb_moderator_pwd,
                        'bbb_moderator_email' => $bbb_moderator_email,
                        'bbb_date_inscription' => date('Y-m-d H:i:00',time()),
			'bbb_date_debut' => $bbb_date_debut,
                        'bbb_date_fin' => $bbb_date_fin,
                        'bbb_join_url_attendee' => $bbb_join_url_attendee,
                        'bbb_join_url_moderator' => $bbb_join_url_moderator,
                        'bbb_clef' => $bbb_clef,
                        'bbb_reponse_server' => $bbb_reponse_server,
                        'bbb_confirmation' => 1,
                        'bbb_tache_confirm_cron' => 1
		)
	);

        // Envoi email confirmation participant
        $sujet = "[".$GLOBALS['meta']['nom_site']."] "._T('bigbluebutton:sujet_consultation_day');

        $corps = _T('bigbluebutton:mail_info_consultation_corps', array('nom_site' => $GLOBALS['meta']['nom_site'], 'url_site' => $GLOBALS['meta']['adresse_site'], 'date_consultation' => $date_consultation, 'url_consultation' => $bbb_join_url_attendee, 'bbb_attendee_email' => addslashes($bbb_attendee_email), 'bbb_meeting_room_name' => $bbb_meeting_room_name));
        // Chargement de la fonction
        $envoyer_mail = charger_fonction('envoyer_mail', 'inc');
        if ($envoyer_mail($bbb_attendee_email, $sujet, $corps, $from)) {
                spip_log('Envoi email consultation [Ok]', 'bigbluebutton');
        } else {
                spip_log('Envoi email consultation [Erreur]', 'bigbluebutton');
        }

                    // Envoi email confirmation Enseignant
                    $to = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_MAIL_ADMIN"');
                    $sujet = "[".$GLOBALS['meta']['nom_site']."] "._T('bigbluebutton:sujet_reservation_moderator');
                    $res = sql_select('bbb_meeting_room_name,bbb_attendee_email,bbb_moderator_email,bbb_join_url_moderator,bbb_date_debut,bbb_date_fin', 'spip_bbb_meetingrooms', 'bbb_id = '.$bbb_id);
                    while ($row = sql_fetch($res)) {
                                        $bbb_descriptif =
                                            $row["bbb_meeting_room_name"] . " " . $row["bbb_attendee_email"] . "\r " .
                                            $row["bbb_join_url_moderator"] . "\r " .
                                            $row["bbb_date_debut"] . "\n " .
                                            $row["bbb_date_fin"] . "\n ";
                    }
                    $corps = _T('bigbluebutton:mail_info_inscription_corps_enseignant', array('nom_site' => $GLOBALS['meta']['nom_site'], 'url_site' => $GLOBALS['meta']['adresse_site'], 'bbb_descriptif'=>$bbb_descriptif));

                    // Chargement de la fonction
                    $envoyer_mail = charger_fonction('envoyer_mail', 'inc');
                    if ($envoyer_mail($to, $sujet, $corps, $from)) {
                        $return = $return.'<p>'._T('bigbluebutton:inscription_ok').'</p>';
                    } else {
                        $return = $return.'<p>'._T('bigbluebutton:inscription_no').'</p>';
                    }

        $message .= (strlen($message) > 0 ? '<br />' : '')._T('bigbluebutton:message_ok');

        spip_log('Création d\'une salle', 'bigbluebutton');

	return array('message_ok' => $message, 'editable' => '');
}
?>