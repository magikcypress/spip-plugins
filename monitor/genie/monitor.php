<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

// http://doc.spip.org/@genie_syndic_dist
function genie_monitor_dist($t) {

    if (lire_config('monitor/activer_monitor') == "oui") {
        
        include_once(_DIR_PLUGIN_MONITOR."lib/Monitor/MonitorSites.php");

        // Aller chercher les 5 dernier ping dans spip_syndic
        $sites = sql_allfetsel('monitor.id_syndic, site.url_site', 'spip_monitor as monitor left join spip_syndic as site on monitor.id_syndic = site.id_syndic', 'monitor.type = "ping" and monitor.statut = "oui"', '', 'site.date_ping ASC', '0,5');

        foreach ($sites as $site) {
            $result = updateWebsite($site['url_site'], 1);
            // Insert les data dans monitor_log
            $insert_ping = sql_insertq('spip_monitor_log', array('id_syndic' => $site['id_syndic'], 'log' => ($result['result'] ? "oui" : "non"), 'latency' => $result['latency']));
            if(is_numeric($insert_ping) && $insert_ping > 0) {
                // Updater champs date_ping dans spip_syndic
                sql_updateq('spip_syndic', array('date_ping' => date('Y-m-d H:i:s'), 'statut_log' => ($result['result'] ? "oui" : "non")), 'id_syndic=' . intval($site['id_syndic']));
            }
        }
        return 0;
    }

}

?>