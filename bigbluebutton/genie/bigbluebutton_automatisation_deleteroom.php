<?php
function genie_bigbluebutton_automatisation_deleteroom_dist($time) {

        $bbb_delay_close = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_DELAY_CLOSE"');
        $res = sql_select("bbb_id,bbb_date_debut,bbb_date_fin,bbb_meeting_room_name,bbb_moderator_pwd","spip_bbb_meetingrooms");
        
        while ($row = sql_fetch($res)) {

            // Si delai dépassé, on supprime le participant
            if(strtotime($row['bbb_date_fin']) >= strtotime("$bbb_delay_close minute", strtotime($row['bbb_date_debut']))) {

            include_spip('bbb_api');
            $bbb_api_key = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_API_KEY"');
            $bbb_url_webconf = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_URL_WEBCONF"');
            // Modére le participant, mais ne supprime pas la salle sur le serveur BBB
            $bbb_reponse_server = BigBlueButton::endMeeting( $row['bbb_meeting_room_name'], $row['bbb_moderator_pwd'], $bbb_url_webconf, $bbb_api_key );

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

            sql_delete('spip_bbb_meetingrooms', 'bbb_id='.intval($row['bbb_id']));

            }

        }
        spip_log("Le meeting de " . $row['bbb_id'] . " vient d'être effacé (id = ".intval($row['bbb_id'])."). Le serveur bbb a retourner les informations suivantes : " .$bbb_reponse_server);
	return 1;
}

?>