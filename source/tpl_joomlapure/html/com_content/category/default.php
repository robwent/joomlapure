<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
include (JPATH_BASE.DS.'templates'.DS.$template.DS.'pure'.DS.'config.php');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
if (!$removeCaption) {
JHtml::_('behavior.caption');
}
?>
<div class="category-list<?php echo $this->pageclass_sfx;?>">

<?php
$this->subtemplatename = 'articles';
echo JLayoutHelper::render('joomla.content.category_default', $this);
?>

</div>
