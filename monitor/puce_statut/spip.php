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

function puce_statut_monitor_dist($id, $statut, $ajax='', $menu_rapide=_ACTIVER_PUCE_RAPIDE){

    $t = sql_getfetsel("statut_log", "spip_syndic", "id_syndic=" . intval($id));
    spip_log($t, 'test.' . _LOG_ERREUR);

    // cas particulier des sites en panne de ping :
    // on envoi une puce speciale, et pas de menu de changement rapide
    if ($t == 0) {
        $puce = 'puce-verte-anim.gif';
        $title = _T('monitor:info_site_reference');
        return http_img_pack($puce, $title);
    }
    else
        return puce_statut_changement_rapide($id,$statut,$ajax,$menu_rapide);
}


?>