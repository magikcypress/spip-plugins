<?php
function balise_ESMLM_VALIDATION($p) {
	return calculer_balise_dynamique($p, 'ESMLM_VALIDATION', array());
}

function balise_ESMLM_VALIDATION_dyn() {
     
     $email_sub = _request('email_sub');

     if (_request('ok')) {
	  $mailko = !email_valide($adresse_email);
     }
     
     $validable = (!$mailko);

     spip_log("Validation du formulaire");
     return $return;
}



?>
