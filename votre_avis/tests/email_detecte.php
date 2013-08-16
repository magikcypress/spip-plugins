<?php
/**
 * Test unitaire de la fonction email_detecte
 * du fichier formulaires/ecrire_votre_avis.php
 *
 * genere automatiquement par TestBuilder
 *
 */


	$test = 'email_detecte';
	$remonte = "../";
	while (!is_dir($remonte."ecrire"))
		$remonte = "../$remonte";
	require $remonte.'tests/test.inc';
	find_in_path("formulaires/ecrire_votre_avis.php",'',true);

	//
	// hop ! on y va
	//
	$err = tester_fun('email_detecte', essais_email_detecte());
	
	// si le tableau $err est pas vide ca va pas
	if ($err) {
		die ('<dl>' . join('', $err) . '</dl>');
	}

	echo "OK";

function essais_email_detecte() {

		$essais = array (
		  0 => 
		  array (
		  	0 => 'cyp@rouquin.me',
		    1 => ' cyp@rouquin.me '
		  ),			
		  1 => 
		  array (
		  	0 => 'mailto:cyp@rouquin.me',
		    1 => ' cyp@rouquin.me '
		  ),
		  2 => 
		  array (
		    0 => 'cyp@node.rouquin.me',
		    1 => ' cyp@rouquin.me '
		  ),
		  3 => 
		  array (
		    0 => 'c@r',
		    1 => 'cyp@rouquin.me'
		  ),		
		  4 => 
		  array (
		    0 => 'cyp@rouquin.memememe',
		    1 => 'cyp@rouquin.me'
		  ),	
		  5 => 
		  array (
		    0 => 'cyp@rouquin.m',
		    1 => 'cyp@rouquin.me'
		  ),		  	
		  6 =>
		  array (
		  	0 => '#+^-`&%_=|/|_?=!§{}$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@rouquin.me',
		  	1 => 'cyp@rouquin.me'
		  ),	
		  7 => 
		  array (
		    0 => 'cyp rouquin me',
		    1 => 'cyp@rouquin.me'
		  ),		      
		);
		return $essais;

}