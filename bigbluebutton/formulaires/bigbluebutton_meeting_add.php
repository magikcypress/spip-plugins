<?php
function formulaires_bigbluebutton_meeting_add_charger_dist($bbb_id = -1) {

	$t=time();
	$valeurs["bbb_date_debut"] = date('Y-m-d H:i:00',$t);
	$valeurs["bbb_date_fin"] = date('Y-m-d H:i:00',$t+3600);

        // dispatcher date et heure
	list($valeurs["bbb_date_debut"],$valeurs["bbb_heure_debut"]) = explode(' ',date('d/m/Y H:i',strtotime($valeurs["bbb_date_debut"])));
	list($valeurs["bbb_date_fin"],$valeurs["bbb_heure_fin"]) = explode(' ',date('d/m/Y H:i',strtotime($valeurs["bbb_date_fin"])));


		$valeurs = array(
			'bbb_meeting_room_name' => '',
			'bbb_attendee_email' => '',
                        'bbb_attendee_ip' => $_SERVER['REMOTE_ADDR'],
                        'bbb_moderator_email' => '',
                        'bbb_date_debut' => $valeurs["bbb_date_debut"],
                        'bbb_date_fin' => $valeurs["bbb_date_fin"],
                        'bbb_heure_debut' => $valeurs["bbb_heure_debut"],
                        'bbb_heure_fin' => $valeurs["bbb_heure_fin"],
			'bbb_join_url_attendee' => '',
			'bbb_join_url_moderator' => '',
                        'bbb_envoi_email_confirm' => 0
		);

	return $valeurs;
}

function formulaires_bigbluebutton_meeting_add_verifier_dist() {
        include_spip('inc/filtres');
        include_spip('inc/date_gestion');

        //$erreurs = formulaires_editer_objet_verifier('bigbluebutton',$bbb_id,array('bbb_date_debut','bbb_date_fin'));

        $bbb_date_debut = _request('bbb_date_debut');
        $bbb_date_fin = _request('bbb_date_fin');

	$bbb_date_debut = verifier_corriger_date_saisie('debut',$bbb_date_debut,$erreurs);
	$bbb_date_fin = verifier_corriger_date_saisie('fin',$bbb_date_fin,$erreurs);

	if ($bbb_date_debut AND $bbb_date_fin AND $bbb_date_fin<$bbb_date_debut)
		$erreurs['bbb_date_fin'] = _T('bigbluebutton:erreur_date_avant_apres');

        $mails = array('bbb_attendee_email', 'bbb_moderator_email');
	$keys = array('bbb_meeting_room_name', 'bbb_attendee_email', 'bbb_moderator_email');
	$erreurs = array();
	foreach($keys as $obligatoire) {
		if (!_request($obligatoire)) {
			$erreurs[$obligatoire] = _T('bigbluebutton:ce_champ_est_obligatoire');
		}
	}
	foreach($mails as $mail) {
		if (!email_valide(_request($mail))) {
                $erreurs[$mail] = _T('bigbluebutton:cette_adresse_email_n_est_pas_valide');
                }
	}

	if (count($erreurs)) {
		$erreurs['message_erreur'] = _T('bigbluebutton:veuillez_corriger_votre_saisie');
	}
	return $erreurs;
}

function formulaires_bigbluebutton_meeting_add_traiter_dist() {
	include_spip('base/abstract_sql');
        include_spip('bbb_api');

	$bbb_meeting_room_name = _request('bbb_meeting_room_name');
        $bbb_attendee_email = _request('bbb_attendee_email');
        $bbb_attendee_pwd = uniqid();
        $bbb_attendee_ip = _request('bbb_attendee_ip');
        $bbb_moderator_name = _T('bigbluebutton:moderateur');
        $bbb_moderator_email = _request('bbb_moderator_email');
        $bbb_moderator_pwd = uniqid();
        $bbb_date_debut = _request('bbb_date_debut');
	$bbb_date_fin = _request('bbb_date_fin');
        $bbb_heure_debut = _request('bbb_heure_debut');
	$bbb_heure_fin = _request('bbb_heure_fin');
        $bbb_envoi_email_confirm = _request('bbb_envoi_email_confirm');

        // la adte en Anglais pour l'insertion dans la DB
        $bbb_date_debut = $bbb_date_debut." ".$bbb_heure_debut;
        $bbb_date_fin = $bbb_date_fin." ".$bbb_heure_fin;
        $bbb_date_debut = strtotime(str_replace('/', '-', $bbb_date_debut));
        $bbb_date_fin = strtotime(str_replace('/', '-', $bbb_date_fin));
	$bbb_date_debut = date('Y-m-d H:i',$bbb_date_debut);
	$bbb_date_fin = date('Y-m-d H:i',$bbb_date_fin);
        $bbb_clef = md5('subscribe#'.$bbb_attendee_ip.'#'.time());

        if (!isset($bbb_envoi_email_confirm)) {

        $bbb_api_key = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_API_KEY"');
        $bbb_url_webconf = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_URL_WEBCONF"');
        $bbb_reponse_server = BigBlueButton::createMeetingArray($bbb_meeting_room_name, $bbb_meeting_room_name, _T('bigbluebutton:bienvenue_webconf'), $bbb_moderator_pwd, $bbb_attendee_pwd, $bbb_api_key, $bbb_url_webconf, $GLOBALS['meta']['adresse_site']);
	$bbb_join_url_attendee = BigBlueButton::joinURL($bbb_meeting_room_name, $bbb_meeting_room_name ,$bbb_attendee_pwd, $bbb_api_key, $bbb_url_webconf);
        $bbb_join_url_moderator = BigBlueButton::joinURL($bbb_meeting_room_name, $bbb_moderator_name ,$bbb_moderator_pwd, $bbb_api_key, $bbb_url_webconf);

        }

        // Traitement erreur serveur bigbluebutton
        //$bbb_reponse_server = print_r($bbb_reponse_server);
        if(!$bbb_reponse_server){
            
              if (isset($bbb_envoi_email_confirm)) {
              $bbb_reponse_server = _T('bigbluebutton:confirmation_attente');
              } else {
              // Erreur de connexion au serveur
              $bbb_reponse_server = _T('bigbluebutton:erreur_serveurbbb');
              }
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
                                'bbb_confirmation' => $bbb_envoi_email_confirm == 1 ? 0 : 1,
                                'bbb_tache_confirm_cron' => $bbb_envoi_email_confirm == 1 ? 0 : 1
			)
		);

        // Envoi du mail de confirmation
        if (isset($bbb_envoi_email_confirm) && ($bbb_envoi_email_confirm == 'on' || $bbb_envoi_email_confirm == 1)) {
            $bbb_clef = sql_getfetsel('bbb_clef', 'spip_bbb_meetingrooms', 'bbb_id='.$bbb_id);
            // Envoi email confirmation participant
            $sujet = "[".$GLOBALS['meta']['nom_site']."] "._T('bigbluebutton:sujet_reservation');
            $url_validation = generer_url_public(_BIGBLUEBUTTON_VALIDATION,'id='.$bbb_clef);
            $corps = _T('bigbluebutton:mail_info_inscription_corps', array('nom_site' => $GLOBALS['meta']['nom_site'], 'url_site' => $GLOBALS['meta']['adresse_site'], 'url_validation' => $url_validation, 'bbb_attendee_email' => addslashes($bbb_attendee_email), 'bbb_meeting_room_name' => $bbb_meeting_room_name));
            // Chargement de la fonction
            $envoyer_mail = charger_fonction('envoyer_mail', 'inc');
            if ($envoyer_mail($bbb_attendee_email, $sujet, $corps, $from)) {
                spip_log('Envoi email participant [Ok]', 'bigbluebutton');
            } else {
                spip_log('Envoi email participant [Erreur]', 'bigbluebutton');
            }
        }	

        spip_log('Création d\'une salle', 'bigbluebutton');
 	return array('message_ok' => 'ok', 'editable' => 1);
}

?>


