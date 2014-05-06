<?php

/**
 * Pipeline pour Xiti
 *
 * @plugin     Xiti
 * @copyright  2014
 * @author     Vincent
 * @licence    GNU/GPL
 * @package    SPIP\Xiti\pipelines
 */


if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Configuration des contenus
 * @param array $flux
 * @return array
 */
function xiti_affiche_milieu($flux){
	if ($flux["args"]["exec"] == "configurer_contenu") {
		$flux["data"] .=  recuperer_fond('prive/squelettes/inclure/configurer', array('configurer'=>'configurer_xiti'));
	}
	return $flux;
}

?>