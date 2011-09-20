<?php

function action_bigbluebutton_moderation_room_demo_dist() {

        include_spip('bbb_api');

            // On récupère les champs
            $bbb_attendee_name = _T('bigbluebutton:demo_meeting');
            $bbb_moderator_name = _T('bigbluebutton:moderateur');

            $bbb_api_key = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_API_KEY"');
            $bbb_url_webconf = sql_getfetsel('set_value', 'spip_bbb_settings', 'set_name="BBB_URL_WEBCONF"');
            $bbb_join_url_moderator = BigBlueButton::joinURL($bbb_attendee_name, $bbb_moderator_name , "mp", $bbb_api_key, $bbb_url_webconf);

        ?>
        <script type="text/javascript"> window.open("<?php echo $bbb_join_url_moderator; ?>", "_blank");</script>
        <?php

        return 1;
}

?>
