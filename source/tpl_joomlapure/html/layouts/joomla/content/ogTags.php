<?php
/**
 * @package     Joomla.CMS
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');

?>
<?php if (!empty($displayData)) : ?><?php foreach ($displayData as $i => $tag) : ?><?php if (in_array($tag->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id')))) : ?><?php $tagParams = new JRegistry($tag->params); ?><?php echo '<meta property="article:tag"  content="'.$this->escape($tag->title).'" />' ."\n"; ?><?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
