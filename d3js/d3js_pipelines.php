<?php
/**
 * Utilisations de pipelines par d3js
 *
 * @plugin     d3js
 * @copyright  2013
 * @author     vincent
 * @licence    GNU/GPL
 * @package    SPIP\D3js\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;
	

/**
 * Ajout l'appel javascript de d3js au head public
 *
 * Appelé aussi depuis le privé avec $prive à true.
 * 
 * @pipeline insert_head_js
 * @param string $flux  Contenu du head
 * @param  bool  $prive Est-ce pour l'espace privé ?
 * @return string Contenu du head complété
 */
function d3js_insert_head($flux='', $prive = false){
	include_spip('inc/autoriser');
	// toujours autoriser pour le prive.
	if ($prive or autoriser('afficher_public', 'd3js')) {
		if ($prive) {
			$d3jsprive = find_in_path('javascript/d3.v3.min.js');
			$flux .= "<script src='" .$d3jsprive . "'></script>\n";
		}
		$d3jspublic = find_in_path('javascript/d3.v3.min.js');
		$flux .= "<script src='" . $d3jspublic . "'></script>\n";
	}
	return $flux;
}

?>