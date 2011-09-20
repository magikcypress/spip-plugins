<?php
function action_bigbluebutton_delete_dist($bbb_id = 0) {
	$securiser_action = charger_fonction('securiser_action', 'inc');
        $arg = $securiser_action();
        $bbb_id = intval($arg);
        $bbb_meeting_room_name = sql_getfetsel("bbb_meeting_room_name", "spip_bbb_meetingrooms", "bbb_id=".intval($bbb_id));
        $bbb_moderator_pwd = sql_getfetsel("bbb_moderator_pwd", "spip_bbb_meetingrooms", "bbb_id=".intval($bbb_id));

  include_spip('inc/autoriser');

  if (autoriser('supprimer','spip_bbb_meetingrooms',intval($bbb_id))) {
      
        include_spip('bbb_api');
        $bbb_api_key = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_API_KEY"');
        $bbb_url_webconf = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_URL_WEBCONF"');
        // Modére le participant, mais ne supprime pas la salle sur le serveur BBB
        $bbb_reponse_server = BigBlueButton::endMeeting( $bbb_meeting_room_name, $bbb_moderator_pwd, $bbb_url_webconf, $bbb_api_key );

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
        }

  	//sql_delete('spip_bbb_meetingrooms', 'bbb_id='.intval($bbb_id));

        spip_log('Suppression de la liste « '.$bbb_meeting_room_name.' » (id = '.intval($bbb_id).') '.$bbb_reponse_server.'', 'bigbluebutton');
  }
}
?>