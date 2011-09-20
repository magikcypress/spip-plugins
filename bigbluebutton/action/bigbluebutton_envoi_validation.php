<?php

function action_bigbluebutton_envoi_validation_dist($bbb_id = 0) {
	$securiser_action = charger_fonction('securiser_action', 'inc');
        $arg = $securiser_action();
        $bbb_id = intval($arg);
        $bbb_attendee_email = sql_getfetsel("bbb_attendee_email", "spip_bbb_meetingrooms", "bbb_id=".intval($bbb_id));

  include_spip('inc/autoriser');

  if (autoriser('confirmation_valid_mail','spip_bbb_meetingrooms',intval($bbb_id))) {

        $bbb_clef = sql_getfetsel('bbb_clef', 'spip_bbb_meetingrooms', 'bbb_id='.$bbb_id);
        $bbb_attendee_email = sql_getfetsel('bbb_attendee_email', 'spip_bbb_meetingrooms', 'bbb_id='.$bbb_id);
        $bbb_meeting_room_name = sql_getfetsel('bbb_meeting_room_name', 'spip_bbb_meetingrooms', 'bbb_id='.$bbb_id);
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
}

?>