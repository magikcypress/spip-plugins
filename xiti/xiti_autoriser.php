<?php

/**
 * Pipeline pour Xiti
 *
 * @plugin     Xiti
 * @copyright  2014
 * @author     Vincent
 * @licence    GNU/GPL
 * @package    SPIP\Xiti\autoriser
 */


if (!defined('_ECRIRE_INC_VERSION')) return;

// pour le pipeline d'autorisation
function xiti_autoriser(){}

// bouton du bandeau
function autoriser_xiti_menu_dist($faire, $type='', $id=0, $qui = NULL, $opt = NULL){
	return 	($GLOBALS['meta']['activer_xiti'] != 'non');
}