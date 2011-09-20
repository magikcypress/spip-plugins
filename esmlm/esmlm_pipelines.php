<?php
function esmlm_header_prive($flux) {
	// On ajoute un CSS pour le back-office
	$flux .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._DIR_PLUGIN_ESMLM."css/styles.css\" />";
	return $flux;
}
?>