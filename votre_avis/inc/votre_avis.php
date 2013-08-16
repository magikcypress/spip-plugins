<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2011                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

if (!defined("_ECRIRE_INC_VERSION")) return;

// Bloquer les données indésirable
function bloque_indesirable($textes) {
	// eviter d'injecter n'importe quoi dans preg_match
	if (!is_string($textes))
		return false;

	foreach (explode(',', $textes) as $texte) {
		// interdire les tags script
		if (preg_match('#<script(.*?)</script>#i', $texte))
			return false;
		// interdire les frames
		if (preg_match('#<iframe ([^>]*?)src=[\'"]?(http:)?//([^>]*?)>#i', $texte))
			return false;
		// interdire les liens
		if (preg_match('#<a[^>]*?href=[^>]+>#i', $texte))
			return false;		
	}
	return $texte;
}


// Verifier la conformite d'une ou plusieurs adresses email
// quelque soit son emplacement dans le champs de texte
function email_detecte($textes) {
	// eviter d'injecter n'importe quoi dans preg_match
	if (!is_string($textes))
		return false;

	// Si c'est un spammeur autant arreter tout de suite
	if (preg_match(",[\n\r].*(MIME|multipart|Content-),i", $textes)) {
		spip_log("Tentative d'injection de mail : $textes");
		return false;
	}

	foreach (explode(',', $textes) as $v) {
		// nettoyer certains formats
		// "Marie Toto <Marie@toto.com>"
		$texte = trim(preg_replace(",[^<>\"]*<([^<>\"]+)>$,i", "\\1", $v));
		// RFC 822
		if (preg_match('#[^()<>@,;:\\"/[:space:]]+(@([-_0-9a-z]+\.)*[-_0-9a-z]+)#i', $texte))
			return false;
			// $adresse = '';
	}
	return $texte;
}

// detecte 'http://' ou 'mailto:'
/* http://www.exorithm.com/algorithm/view/validate_url */
function url_detecte($textes)
{
	// eviter d'injecter n'importe quoi dans preg_match
	if (!is_string($textes))
		return false;

  	$regex = '/(https?|ftp|mailto):\/\/'; //protocol
  	$regex .= '(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+'; //username
  	$regex .= '(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?'; //password
  	$regex .= '@)?'; //auth requires @
  	$regex .= '((([a-z0-9][a-z0-9-]*[a-z0-9]\.)*'; //domain segments AND
  	$regex .= '[a-z][a-z0-9-]*[a-z0-9]'; //top level domain  OR
  	$regex .= '|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}';
  	$regex .= '(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])'; //IP address
  	$regex .= ')(:\d+)?'; //port
  	$regex .= ')(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*'; //path
  	$regex .= '(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)'; //query string
  	$regex .= '?)?)?'; //path and query string optional
  	$regex .= '(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?'; //fragment
  	$regex .= '/i';
  
  	return (preg_match($regex, $textes) ? true : false);
} 

// detecte url fragment
function url_detect_fragment($textes)
{
	// eviter d'injecter n'importe quoi dans preg_match
	if (!is_string($textes))
		return false;

  	$regex = '/^(((\/?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*'; //path
  	$regex .= '(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)'; //query string
  	$regex .= '?)?)?'; //path and query string optional
  	$regex .= '(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?'; //fragment
  	$regex .= '$/i';

  	return (preg_match($regex, $textes) ? true : false);
}

/**
 * Construire un tableau par popularite
 *   classement => id_truc
 * @param string $type
 * @param string $serveur
 * @return array
 */
function classement_populaires($type, $serveur=''){
	static $classement = array();
	if (isset($classement[$type]))
		return $classement[$type];
	$classement[$type] = sql_allfetsel(id_table_objet($type, $serveur), table_objet_sql($type, $serveur), "statut='publie'", "", "",'','',$serveur);
	$classement[$type] = array_map('reset',$classement[$type]);
	return $classement[$type];
}