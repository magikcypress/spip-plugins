<?php
function action_esmlm_delete_dist($esmlm_id = 0) {
	$securiser_action = charger_fonction('securiser_action', 'inc');
        $arg = $securiser_action();
        $esmlm_id = intval($arg);
        $esmlm_inscription = sql_getfetsel("esmlm_inscription", "spip_esmlm_lists", "esmlm_id=".intval($esmlm_id));

  include_spip('inc/autoriser');

  if (autoriser('supprimer','spip_esmlm_lists',intval($esmlm_id))) {
  	sql_delete('spip_esmlm_lists', 'esmlm_id='.intval($esmlm_id));
        spip_log('Suppression de la liste « '.$esmlm_inscription.' » (id = '.intval($esmlm_id).')', 'esmlm');
  }
}
?>