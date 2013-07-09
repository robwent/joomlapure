<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.pureseo
 *
 * @copyright   Copyright (C) Robert Went. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

// Check if we should remove the content
$removeComponent = '';
$menu = $app->getMenu();
$lang = JFactory::getLanguage();
if ($hideHome && $menu->getActive() == $menu->getDefault( $lang->getTag() )) {
	$removeComponent = 1;
}


if ($removeMootools == 1) {
	unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools-core.js']);
}
if ($removeMootoolsMore == 1) {
	unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools-more.js']);
}
if ($removeCoreJs == 1) {
	unset($doc->_scripts[JURI::root(true) . '/media/system/js/core.js']);
}
if ($removeJquery == 1) {
	unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery.min.js']);
}
if ($removeNoconflict == 1) {
	unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery-noconflict.js']);
}
if ($removeBootstrapjs == 1) {
	unset($doc->_scripts[JURI::root(true) . '/media/jui/js/bootstrap.min.js']);
}
if ($removeCaption == 1) {
	unset($doc->_scripts[JURI::root(true) . '/media/system/js/caption.js']);
	if (isset($this->_script['text/javascript']))
	{
		$this->_script['text/javascript'] = preg_replace('%window\.addEvent\(\'load\',\s*function\(\)\s*{\s*new\s*JCaption\(\'img.caption\'\);\s*}\);\s*%', '', $this->_script['text/javascript']);
		if ($view == 'tag'){
			$this->_script['text/javascript'] = '';
		}
		if (empty($this->_script['text/javascript']))
			unset($this->_script['text/javascript']);
	}
}
//Unset the canonical url
if ($removeCanonical) {
	foreach ( $doc->_links as $k => $array ) {
		if ( $array['relation'] == 'canonical' ) {
			unset($doc->_links[$k]);
		}
	}
}
// Add Google font if set to css (or not if told not to load on mobile) - TO DO error if mobile detect not installed
//if (!(MobileDetector::isBot() && $mobileRemoveBotCss) || (MobileDetector::isMobile() && $mobileRemoveWebfonts) ) {
	if ($this->params->get('googleFont') && $this->params->get('googleFontName')) {
		$fontLink = 'http://fonts.googleapis.com/css?family='.$this->params->get('googleFontName');
		$doc->addStylesheet($fontLink,'text/css','screen');
	}
//}
// Add a print stylesheet
if (class_exists('MobileDetector')) {
	if (!(MobileDetector::isMobile() && $mobileRemovePrint)) {
		if ($printCSS) {
			$printLink = '/templates/'.$template.'/css/print.css';
			$doc->addStylesheet($printLink,'text/css','print');
		}
	}
}
// Set generator tag or remove completely
$this->setGenerator($generator);
// add rel publisher if set
if ($publisher != '') {
	$doc->addHeadLink( $publisher, 'publisher', 'rel' );
}
