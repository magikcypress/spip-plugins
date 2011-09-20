<?php
include_spip('base/abstract_sql');

function formulaires_esmlm_charger_dist() {
	$default = array('editable' => ' ', 'email_sub' => '', 'esmlm_ids' => array());

	if ($GLOBALS['visiteur_session']['email']) {
		$default['email_sub'] = $GLOBALS['visiteur_session']['email'];
	}

        spip_log("Chargement du formulaire esmlm");

        $nbLists = sql_countsel("spip_esmlm_lists");
        if ($nbLists == 0) {
          return array('editable' => '');
        } elseif ($nbLists == 1) {
          $esmlm_id = sql_getfetsel("esmlm_id", "spip_esmlm_lists");
          $valeurs = $default;
         $valeurs['esmlm_ids'] = array($esmlm_id);
          return $valeurs;
          } else {
          // editable, mais le squelette trouvera tout seul la liste de valeurs
          return $default;
          }
   return $default;
}

function formulaires_esmlm_verifier_dist($esmlm_id = 0) {
  $erreurs = array();
  if (!_request('esmlm_id') && !_request('esmlm_ids')) {
    $erreurs['esmlm_ids'] = _T('esmlm:ce_champ_est_obligatoire');
  }
  if (!_request('email_sub')) {
    $erreurs['email_sub'] = _T('esmlm:ce_champ_est_obligatoire');
  }
  include_spip('inc/filtres');
  if (_request('email_sub') && !email_valide(_request('email_sub'))) {
    $erreurs['email_sub'] = _T('esmlm:cette_adresse_email_n_est_pas_valide');
  }
  if (count($erreurs)) {
    $erreurs['message_erreur'] = _T('esmlm:veuillez_corriger_votre_saisie');
  }
  spip_log("Verification du formulaire esmlm");
  return $erreurs;
}

function formulaires_esmlm_traiter_dist($esmlm_id = 0) {
	$ok = true;
	$message = '';

          if (_request('esmlm_id')) {
                $lists[] = intval(_request('esmlm_id'));
          } elseif (_request('esmlm_ids')) {
                $lists = array_map("intval", _request('esmlm_ids'));
          }

         
          foreach($lists as $list) {
                        $esmlm_id = intval($list);
                        $listData = sql_fetsel("*", "spip_esmlm_lists", "esmlm_id=".$esmlm_id);

                        if(!_request('email_sub')) $message = _T('esmlm:inscription_emailfrom_erreur'); else $from = _request('email_sub');
                        //$to = trim($listData['esmlm_inscription']);
                        if(!$listData['esmlm_inscription'])
                                $message = _T('esmlm:inscription_emailto_erreur');
                        else $to = $listData['esmlm_inscription'];

                        if(!$listData['esmlm_sujet']) $message = _T('esmlm:inscription_sujet_erreur');
                        else $sujet = $listData['esmlm_sujet'];
               
                        // TODO return_path ne semble pas exister dans la fonction envoyer_mail
                        if(!$listData['nz_chemin_retour']) $message = _T('esmlm:inscription_emailreturnpath_erreur'); else $return_path = $listData['nz_chemin_retour'];
                        $texte .= "\n\n-- "._T('envoi_via_le_site')." ".supprimer_tags(extraire_multi($GLOBALS['meta']['nom_site']))." (".$GLOBALS['meta']['adresse_site']."/) --\n";
                        $envoyer_mail = charger_fonction('envoyer_mail','inc/');

                        if ($envoyer_mail($to, $sujet, $texte, $from, "Return-path : ".$return_path."\n X-Originating-IP: ".$GLOBALS['ip']))
                                $message = _T('esmlm:inscription_ok');
                        else
                                $message = _T('esmlm:inscription_erreur');

                        return array('message_ok' => $message, 'editable' => '');
            }

        spip_log("Envoi du mail esmlm");
}

?>
