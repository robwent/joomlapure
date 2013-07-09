<?php
/**
 * @package     SEO Overrides
 * @author		Robert Went http://www.robertwent.com
 * @copyright   Copyright (C) 2013 - Robert Went
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
// Get language code
$lang = JFactory::getLanguage();
$languages = JLanguageHelper::getLanguages('lang_code');
$languagetag = $lang->getTag();
$languageCode = $languages[ $lang->getTag() ]->sef;
// Start SEO Extras
$doc = JFactory::getDocument();
$app = JFactory::getApplication();

// Use CDN for content images and resize if needed
if (($cdnUrl && $cdnContentImages) || $imageResizeContent) {
	$dom = new DOMDocument();
	$dom->loadHTML($this->item->introtext);
	if ($imageResizeContent && !class_exists('resize')) {
		# code...
	}
	foreach ($dom->getElementsByTagName('img') as $item) {
		if (substr($item->getAttribute('src'), 0, 4) != 'http') {
			$original = $item->getAttribute('src');
			$newSrc = '';
			// Resize if selected and image has width and height attributes
			if ($imageResizeContent && $item->getAttribute('width') && $item->getAttribute('height')) {
				if (!class_exists('resize')) {
					include (JPATH_BASE.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'pure'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'function.resize.php');
				}
				$width = rtrim($item->getAttribute('width'), 'px');
				$height = rtrim($item->getAttribute('height'), 'px');
				$settings = array('w'=>$width,'h'=>$height);
				$newSrc = resize($item->getAttribute('src'),$settings);
			}
			// Add cdn if selected
			if ($cdnUrl && $cdnContentImages) {
				if ($newSrc) {
					$newSrc = $cdnUrl.'/'.ltrim($newSrc, '/');
				} else {
					$newSrc = $cdnUrl.'/'.ltrim($item->getAttribute('src'), '/');
				}
			}
			// Check if we have a new path and replace original
			if ($newSrc) {
				$item->setAttribute('data-original', $original);
				$item->setAttribute('src', $newSrc);
			}
		}
	}
	$this->item->introtext = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
}