<?php

function balise_BIGBLUEBUTTON_VALIDATION($p) {
	return calculer_balise_dynamique($p, 'BIGBLUEBUTTON_VALIDATION', array());
}

function balise_BIGBLUEBUTTON_VALIDATION_dyn() {
    include_spip('base/abstract_sql');
    include_spip('bbb_api');
    $return = "";
   
    if (isset($_GET['id']) && $_GET['id'] != '') {

        
        if (sql_countsel("spip_bbb_meetingrooms", "bbb_clef=".intval($_GET['id'])." AND bbb_confirmation=1") == 1) {

	   $return = $return.'<p>'._T('bigbluebutton:deja_inscrit').'</p>';
                    
	} else {
 
        $res = sql_select("bbb_meeting_room_name, bbb_moderator_pwd, bbb_attendee_pwd", "spip_bbb_meetingrooms","bbb_tache_confirm_cron=0 AND bbb_clef=".intval($_GET['id']));

        while ($row = sql_fetch($res)) {

                $bbb_meeting_room_name = $row["bbb_meeting_room_name"];
                $bbb_moderator_pwd = $row["bbb_moderator_pwd"];
                $bbb_attendee_pwd = $row["bbb_attendee_pwd"];
                $bbb_moderator_name = _T('bigbluebutton:moderateur');

                

                // Création d'une salle sur le serveur bigbluebutton
                $bbb_api_key = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_API_KEY"');
                $bbb_url_webconf = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_URL_WEBCONF"');
                $bbb_reponse_server = BigBlueButton::createMeetingArray($bbb_meeting_room_name, $bbb_meeting_room_name, _T('bigbluebutton:bienvenue_webconf'), $bbb_moderator_pwd, $bbb_attendee_pwd, $bbb_api_key, $bbb_url_webconf, $GLOBALS['meta']['adresse_site']);
                $bbb_join_url_attendee = BigBlueButton::joinURL($bbb_meeting_room_name, $bbb_meeting_room_name ,$bbb_attendee_pwd, $bbb_api_key, $bbb_url_webconf);
                $bbb_join_url_moderator = BigBlueButton::joinURL($bbb_meeting_room_name, $bbb_moderator_name ,$bbb_moderator_pwd, $bbb_api_key, $bbb_url_webconf);

                // Traitement erreur serveur bigbluebutton
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
                }

	            sql_updateq("spip_bbb_meetingrooms", array('bbb_confirmation' => 1, 'bbb_join_url_attendee' => $bbb_join_url_attendee, 'bbb_join_url_moderator' => $bbb_join_url_moderator, 'bbb_reponse_server' => $bbb_reponse_server ), "bbb_clef = ".intval($_GET['id']));

                    // Envoi email confirmation Enseignant
                    $to = sql_getfetsel('bbb_moderator_email', 'spip_bbb_meetingrooms', 'bbb_clef = '.intval($_GET['id']));
                    $sujet = "[".$GLOBALS['meta']['nom_site']."] "._T('bigbluebutton:sujet_reservation_moderator');
                    $res = sql_select('bbb_meeting_room_name,bbb_attendee_email,bbb_moderator_email,bbb_join_url_moderator,bbb_date_debut,bbb_date_fin', 'spip_bbb_meetingrooms', 'bbb_clef = '.intval($_GET['id']));
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

                  }


    }
  
  return $return;
}
?>