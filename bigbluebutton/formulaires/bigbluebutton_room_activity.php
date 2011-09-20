<?php
function formulaires_bigbluebutton_room_activity_charger_dist() {

    include_spip('inc/bigbluebutton_room_activity');
    $valeurs = bigbluebutton_room_activity();
    return $valeurs;

}
?>