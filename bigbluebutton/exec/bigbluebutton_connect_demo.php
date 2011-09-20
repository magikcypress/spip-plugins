<?php
function exec_bigbluebutton_connect_demo_dist() {

	include_spip('base/abstract_sql');

	$id_auteur = intval(_request('id_auteur'));

	if ( $id_auteur <= 0)
		echo minipres(_T('public:aucun_auteur'));
	else{

		// On vérifie si l'auteur exsite
		if ($id_auteur > 0){

			// On récupère l'auteur
			$auteur = sql_fetsel(
				'id_auteur, nom, email',
				'spip_auteurs',
				'id_auteur = '.$id_auteur
			);

			if (!$auteur){
				echo minipres(_T('public:aucun_auteur'));
				return;
			}

                }

                // Si tout s'est bien passé on lance la connexion
                if ($id_auteur > 0)
                echo bigbluebutton_connect_demo($auteur);
         }

         return array('redirect' => generer_url_ecrire('auteur_infos&id_auteur='.$auteur['id_auteur'].''));
}

function bigbluebutton_connect_demo($auteur){

	include_spip('base/abstract_sql');
        include_spip('bbb_api');

            // On récupère les champs
            $bbb_meeting_room_name = $auteur['nom'];
            $bbb_attendee_name = _T('bigbluebutton:demo_meeting');
            $bbb_attendee_pwd = uniqid();
            $bbb_attendee_email = $auteur['email'];
            $bbb_attendee_ip = $_SERVER['REMOTE_ADDR'];
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

        ?>
        <script type="text/javascript"> window.open("<?php echo $bbb_join_url_attendee; ?>", "_blank");</script>
        <?php

}

?>