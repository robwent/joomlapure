<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.pureseo.layouts
 *
 * @copyright   Copyright (C) Robert Went. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// Include the seo config file
$app = JFactory::getApplication();
$template = $app->getTemplate();
include (JPATH_BASE.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'pure'.DIRECTORY_SEPARATOR.'config.php');
?>
<?php $images = json_decode($displayData->images); ?>
<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
	<?php $imgfloat = (empty($images->float_fulltext)) ? $displayData->params->get('float_fulltext') : $images->float_fulltext; ?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image"> <img
		<?php if ($images->image_fulltext_caption):
		echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';
		endif; ?>
		<?php if ($imageResizeMain) {
			$settings = array();
			if ($imageWidthMain) {$settings['w'] = $imageWidthMain;}
			if ($imageHeightMain) {$settings['h'] = $imageHeightMain;}
			if ($imageCropMain == 1) {$settings['crop'] = 'true';}
			if ($imageScaleMain == 1) {$settings['scale'] = 'true';}
			if ($canvasColor) {$settings['canvas-color'] = $canvasColor;}
			if ($imageResizeSmush) {$settings['smush'] = 1;}
			if ($imageCacheTime) {$settings['cache_http_minutes'] = $imageCacheTime;}
			$original = $images->image_fulltext;
			$images->image_fulltext = JoomlaPure::resize($images->image_fulltext,$settings);
		} ?>
		src="<?php if ($cdnUrl && $cdnFeaturedImages) {echo $cdnUrl.'/'.ltrim(htmlspecialchars($images->image_fulltext), '/');} else echo htmlspecialchars($images->image_fulltext); ?>" <?php if ($imageResizeMain && $original) echo 'data-original="'.$original.'"'; ?> alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/> </div>
		<?php echo 'main:'.$imageResizeMain; ?>
	<?php endif; ?>
