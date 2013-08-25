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

class JFormFieldFrameworkcss extends JFormField
{
	protected function getInput() {
		echo '<textarea rows="3" cols="10" class="uneditable-input"></textarea>';
	}
}