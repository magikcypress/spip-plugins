<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2014                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

if (!defined("_ECRIRE_INC_VERSION")) return;

function action_instituer_monitor_dist() {

    $securiser_action = charger_fonction('securiser_action', 'inc');
    $arg = $securiser_action();

    list($type, $statut) = preg_split('/\W/', $arg);

    if(in_array($type, array('ping','poids')))  {
        $monitor = sql_allfetsel('id_syndic', 'spip_monitor', 'type=' . sql_quote($type));
        $monitores = array();
        foreach ($monitor as $key => $value) {
            $monitores[] = $value['id_syndic'];

        }
        $syndics = sql_allfetsel('id_syndic', 'spip_syndic');
        foreach ($syndics as $key => $value) {
            $id_syndic = $value['id_syndic'];
            if(in_array($id_syndic, $monitores)) {
                sql_updateq('spip_monitor', array('statut'=>$statut), 'id_syndic=' . intval($id_syndic) . ' and type=' . sql_quote($type));
            } else {
                sql_insertq('spip_monitor', array('id_syndic'=>$id_syndic, 'statut'=>$statut, 'type'=>$type, 'date_modif' => date('Y-m-d H:i:s')));
            }

        }        
    }
}

?>
