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
 * Ajout des scripts de d3js dans le head des pages publiques
 *
 * Uniquement si l'on est autorisé à l'afficher le porte plume dans
 * l'espace public !
 *
 * @pipeline insert_head
 * @param  string $flux Contenu du head
 * @return string Contenu du head
 */
function d3js_insert_head_public($flux){
	include_spip('inc/autoriser');
	if (autoriser('afficher_public', 'd3js')) {
		$flux = d3js_inserer_head($flux);
	}
	return $flux;
}

/**
 * Ajout des scripts de d3js dans le head des pages privées
 *
 * @pipeline header_prive
 * @param  string $flux Contenu du head
 * @return string Contenu du head
 */
function d3js_insert_head_prive($flux){
	$js = find_in_path('javascript/d3.v3.min.js');
	$flux = porte_plume_inserer_head($flux, $GLOBALS['spip_lang'], $prive=true)
		. "<script type='text/javascript' src='$js'></script>\n";
	
	return $flux;
}

/**
 * Ajout des scripts de d3js au texte (un head) transmis
 *
 * @param  string $flux  Contenu du head
 * @param  string $lang  Langue en cours d'utilisation
 * @param  bool   $prive Est-ce pour l'espace privé ?
 * @return string Contenu du head complété
 */
function d3js_inserer_head($flux, $prive = false){
	$js_start = find_in_path('javascript/d3.v3.min.js');
	if (defined('_VAR_MODE') AND _VAR_MODE=="recalcul")
		$js_start = parametre_url($js_start, 'var_mode', 'recalcul');

	$flux .= 
		  "<script type='text/javascript' src='$js_start'></script>\n";

	return $flux;
}

?>