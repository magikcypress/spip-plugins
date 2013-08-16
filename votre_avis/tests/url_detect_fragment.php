<?php
/**
 * Test unitaire de la fonction url_detect_fragment
 * du fichier formulaires/ecrire_votre_avis.php
 *
 * genere automatiquement par TestBuilder
 *
 */


	$test = 'url_detect_fragment';
	$remonte = "../";
	while (!is_dir($remonte."ecrire"))
		$remonte = "../$remonte";
	require $remonte.'tests/test.inc';
	find_in_path("formulaires/ecrire_votre_avis.php",'',true);

	//
	// hop ! on y va
	//
	$err = tester_fun('url_detect_fragment', essais_url_detect_fragment());
	
	// si le tableau $err est pas vide ca va pas
	if ($err) {
		die ('<dl>' . join('', $err) . '</dl>');
	}

	echo "OK";

function essais_url_detect_fragment() {

		$essais = array (
		  0 => 
		  array (
		    0 => 'http://www.rouquin.me',
		    1 => 'http://www.rouquin.me'
		  ),			
		  1 => 
		  array (
		    0 => 'https://www.rouquin.me',
		    1 => 'https://www.rouquin.me'
		  ),
		  2 => 
		  array (
		    0 => 'https://www.rouquin.me?inc=test&def=1',
		    1 => 'https://www.rouquin.me?inc=test&def=1'
		  ),
		  3 => 
		  array (
		    0 => 'http://www.rouquin.me?db=spip&table=spip_meta&target=tbl_sql.php&token=ee916a6e6f977594fa9708af37dc9f8b',
		    1 => 'http://www.rouquin.me?db=spip&table=spip_meta&target=tbl_sql.php&token=ee916a6e6f977594fa9708af37dc9f8b'
		  ),		
		  4 => 
		  array (
		    0 => 'rouquin.me',
		    1 => 'rouquin.me'
		  ),			    
		);
		return $essais;

}