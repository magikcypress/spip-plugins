<?php

/**
 * Compat ascendante pour d'autre plugins
 * http://zone.spip.org/trac/spip-zone/changeset/36546
 */

function bigbluebutton_verifier_corriger_date_saisie($suffixe,$horaire,&$erreurs){
	include_spip('inc/date_gestion');
	return verifier_corriger_date_saisie($suffixe,$horaire,$erreurs);
}

?>