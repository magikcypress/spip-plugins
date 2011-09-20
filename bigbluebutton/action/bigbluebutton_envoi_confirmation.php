<?php
function action_bigbluebutton_envoi_confirmation_dist($bbb_id = 0) {
	$securiser_action = charger_fonction('securiser_action', 'inc');
        $arg = $securiser_action();
        $bbb_id = intval($arg);
        $bbb_meeting_room_name = sql_getfetsel("bbb_meeting_room_name", "spip_bbb_meetingrooms", "bbb_id=".intval($bbb_id));

  include_spip('inc/autoriser');

  if (autoriser('confirmation_confirm_mail','spip_bbb_meetingrooms',intval($bbb_id))) {

        $res = sql_select("bbb_id,bbb_date_debut,bbb_date_fin,bbb_meeting_room_name,bbb_attendee_email,bbb_join_url_attendee", "spip_bbb_meetingrooms","bbb_tache_confirm_cron=0");
        while ($row = sql_fetch($res)) {

                $bbb_date_debut = $row["bbb_date_debut"];
                $bbb_date_fin = $row["bbb_date_fin"];
                $date_consultation = $bbb_date_debut . " - " . $bbb_date_fin;
                $bbb_id = $row["bbb_id"];
                $url_consultation = $row["bbb_join_url_attendee"];
                $bbb_attendee_email = $row["bbb_attendee_email"];
                $bbb_meeting_room_name = $row["bbb_meeting_room_name"];

                // Confirmer envoi email access
		sql_updateq(
			'spip_bbb_meetingrooms',
			array(
                                'bbb_confirmation' => 1,
				'bbb_tache_confirm_cron' => 1
			),
			'bbb_id = '.$bbb_id
		);

                // Envoi email confirmation participant
                $sujet = "[".$GLOBALS['meta']['nom_site']."] "._T('bigbluebutton:sujet_consultation_day');

                $corps = _T('bigbluebutton:mail_info_consultation_corps', array('nom_site' => $GLOBALS['meta']['nom_site'], 'url_site' => $GLOBALS['meta']['adresse_site'], 'date_consultation' => $date_consultation, 'url_consultation' => $url_consultation, 'bbb_attendee_email' => addslashes($bbb_attendee_email), 'bbb_meeting_room_name' => $bbb_meeting_room_name));
                // Chargement de la fonction
                $envoyer_mail = charger_fonction('envoyer_mail', 'inc');
                if ($envoyer_mail($bbb_attendee_email, $sujet, $corps, $from)) {
                    spip_log('Envoi email consultation [Ok]', 'bigbluebutton');
                } else {
                    spip_log('Envoi email consultation [Erreur]', 'bigbluebutton');
                }

            }
  }
}

?>