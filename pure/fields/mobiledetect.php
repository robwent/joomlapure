<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.pureseo
 *
 * @copyright   Copyright (C) Robert Went. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldMobiledetect extends JFormField
{
	protected function getInput() {
		jimport( 'joomla.plugin.helper' );

		if (!JPluginHelper::isEnabled('system', 'pure_mobiledetect')) {
			echo '<div class="alert alert-error" style="float:left">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<p>'.JText::_('TPL_JOOMLAPURE_MOBILEDISABLED').'<br/><a href="http://extensions.robertwent.com/pure-mobiledetect" target="_blank">'.JText::_('TPL_JOOMLAPURE_MOBILEDOWNLOAD').'</a><br/><a href="'.JURI::root(true).'/administrator/index.php?option=com_plugins&view=plugins?&filter_search=pure+mobiledetect" target="_blank">'.JText::_('TPL_JOOMLAPURE_MOBILEPUBLISH').'</a></p></div>';
		} else {
			echo '<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button><p>'.JText::_('TPL_JOOMLAPURE_MOBILEENABLED').'
			</p><p>'.JText::_('TPL_JOOMLAPURE_MOBILESUFIXESEX').'</p></div>';
		}
	}
}