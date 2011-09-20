<?php
function action_bigbluebutton_delete_groups_dist($bbb_id_groups = 0) {
	$securiser_action = charger_fonction('securiser_action', 'inc');
        $arg = $securiser_action();
        $bbb_id_groups = intval($arg);
        
  include_spip('inc/autoriser');

  if (autoriser('supprimer','spip_bbb_groups',intval($bbb_id_groups))) {
      
  	sql_delete('spip_bbb_groups', 'bbb_id_groups='.intval($bbb_id_groups));

        spip_log('Suppression d\'un groupe (id = '.intval($bbb_id_groups).')', 'bigbluebutton');
  }
}
?>