<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.pureseo
 *
 * @copyright   Copyright (C) Robert Went. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Core Joomla
$config = JFactory::getConfig();
$app = JFactory::getApplication('site');
$params = $app->getTemplate(true)->params;
$siteTitle = $config->get('sitename'); // Get the site title for use anywhere
$pageUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; // The current page url for use anywhere

$sitename = $params->get('sitename', $siteTitle);
$hideHome = $params->get('hideHome', 0);
$frameworkCSS = $params->get('frameworkCSS', 'local');
$printCSS = $params->get('printCSS', 0);

$modBase = $params->get('modBase', 0);
$modButtons = $params->get('modButtons', 0);
$modFormsR = $params->get('modFormsR', 0);
$modFormsNR = $params->get('modFormsNR', 0);
$modGridsR = $params->get('modGridsR', 0);
$modGridsNR = $params->get('modGridsNR', 0);
$modMenusR = $params->get('modMenusR', 0);
$modMenusNR = $params->get('modMenusNR', 0);
$modTables = $params->get('modTables', 0);
$loadCss = $params->get('loadCss');

$removeCaption = $params->get('removeCaption', 0);
$removeMootools = $params->get('removeMootools', 0);
$removeMootoolsMore = $params->get('removeMootoolsMore', 0);
$removeCoreJs = $params->get('removeCoreJs', 0);
$removeJquery = $params->get('removeJquery', 0);
$removeNoconflict = $params->get('removeNoconflict', 0);
$removeBootstrapTooltip = $params->get('removeBootstrapTooltip', 0);
$removeBootstrapjs = $params->get('removeBootstrapjs', 0);
$removeCanonical = $params->get('removeCanonical', 0);
$removeArticleTags = $params->get('removeArticleTags', 0);
$addJquery = $params->get('addJquery', 0);
$jqueryVersion = $params->get('jqueryVersion', '1.8.3');
$bottomScripts = $params->get('bottomScripts', 0);
$addPlugins = $params->get('addPlugins', 0);
$addScripts = $params->get('addScripts', 0);
$generator = $params->get('generator', '');

$descriptionMax = $params->get('descriptionMax', 160);
$openGraph = $params->get('openGraph', 0);
$ogAdmins = $params->get('ogAdmins', '');
$ogSitename = $params->get('ogSitename', $siteTitle);
$twitterTags = $params->get('twitterTags', 0);
$twitterSite = $params->get('twitterSite');
$defaultTCard = $params->get('defaultTCard');
$twittercreator = $params->get('twittercreator');
$twittercreatorDf = $params->get('twittercreatorDf');

$microdata = $params->get('microdata', 0);
$microPublishing = $params->get('microPublishing', 0);
$microTitle = $params->get('microTitle', 0);
$microBread = $params->get('microBread', 0);
$microFeaturedImage = $params->get('microFeaturedImage', 0);
$microAuthor = $params->get('microAuthor', 0);
$microAuthorArticles = $params->get('microAuthorArticles', 0);
$microContactAddress = $params->get('microContactAddress', 0);
$waiAriaRoles = $params->get('waiAriaRoles', 0);

$relAuthor = $params->get('relAuthor', 0);
$relAuthorDirect = $params->get('relAuthorDirect', 0);
$relPublisher = $params->get('relPublisher', 0);
$publisher = $params->get('publisher', '');

$SocialCount = $params->get('SocialCount', 0);
$SocialCountJs = $params->get('SocialCountJs', 0);
$SocialCountCss = $params->get('SocialCountCss', 0);
$SocialCountIcons = $params->get('SocialCountIcons', 0);
$SocialCountPosition = $params->get('SocialCountPosition', 0);
$SocialCountSize = $params->get('SocialCountSize');
$SocialCountFacebook = $params->get('SocialCountFacebook', 0);
$SocialCountTwitter = $params->get('SocialCountTwitter', 0);
$SocialCountGoogle = $params->get('SocialCountGoogle', 0);
$SocialCountText = $params->get('SocialCountText');
if ($SocialCountSize == 'small') {
	$SocialCountMarkup	= '<ul class="socialcount socialcount-small" data-url="'.$pageUrl.'" data-share-text="'.$SocialCountText.'">
	<li class="facebook"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$pageUrl.'" title="Share on Facebook"><span class="social-icon icon-facebook"></span><span class="count">Like</span></a></li>
	<li class="twitter"><a target="_blank" href="https://twitter.com/intent/tweet?text='.$pageUrl.'" title="Share on Twitter"><span class="social-icon icon-twitter"></span><span class="count">Tweet</span></a></li>
	<li class="googleplus"><a target="_blank" href="https://plus.google.com/share?url='.$pageUrl.'" title="Share on Google Plus"><span class="social-icon icon-googleplus"></span><span class="count">+1</span></a></li>
</ul>';
} elseif ($SocialCountSize == 'large') {
	$SocialCountMarkup	= '<ul class="socialcount socialcount-large" data-url="'.$pageUrl.'" data-share-text="'.$SocialCountText.'">
	<li class="facebook"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$pageUrl.'" title="Share on Facebook"><span class="social-icon icon-facebook"></span><span class="count">Like</span></a></li>
	<li class="twitter"><a target="_blank" href="https://twitter.com/intent/tweet?text='.$pageUrl.'" title="Share on Twitter"><span class="social-icon icon-twitter"></span><span class="count">Tweet</span></a></li>
	<li class="googleplus"><a target="_blank" href="https://plus.google.com/share?url='.$pageUrl.'" title="Share on Google Plus"><span class="social-icon icon-googleplus"></span><span class="count">+1</span></a></li>
</ul>';
} else {
	$SocialCountMarkup	= '<ul class="socialcount" data-url="'.$pageUrl.'" data-share-text="'.$SocialCountText.'">
	<li class="facebook"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$pageUrl.'" title="Share on Facebook"><span class="social-icon icon-facebook"></span><span class="count">Like</span></a></li>
	<li class="twitter"><a target="_blank" href="https://twitter.com/intent/tweet?text='.$pageUrl.'" title="Share on Twitter"><span class="social-icon icon-twitter"></span><span class="count">Tweet</span></a></li>
	<li class="googleplus"><a target="_blank" href="https://plus.google.com/share?url='.$pageUrl.'" title="Share on Google Plus"><span class="social-icon icon-googleplus"></span><span class="count">+1</span></a></li>
	</ul>';
}

$pathConvert = $params->get('pathConvert', '/usr/bin/convert');
$imageQuality = $params->get('imageQuality', 80);
$canvasColor = $params->get('canvasColor', 'transparent');
$imageCacheTime = $params->get('imageCacheTime', '1440');
$imageUseFeatured = $params->get('imageUseFeatured');
$imageLinkTeaser = $params->get('imageLinkTeaser', 0);
$imageResizeContent = $params->get('imageResizeContent', 0);
$imageResizeSmush = $params->get('imageResizeSmush', 0);
$imageResizeTeaser = $params->get('imageResizeTeaser');
$imageCropTeaser = $params->get('imageCropTeaser');
$imageScaleTeaser = $params->get('imageScaleTeaser');
$imageWidthTeaser = $params->get('imageWidthTeaser');
$imageHeightTeaser = $params->get('imageHeightTeaser');
$imageResizeMain = $params->get('imageResizeMain');
$imageCropMain = $params->get('imageCropMain');
$imageScaleMain = $params->get('imageScaleMain');
$imageWidthMain = $params->get('imageWidthMain');
$imageHeightMain = $params->get('imageHeightMain');

$mobileMobileCss = $params->get('mobileMobileCss', 0);
$mobileTabletCss = $params->get('mobileTabletCss', 0);
$mobileRemoveWebfonts = $params->get('mobileRemoveWebfonts', 0);
$mobileRemovePrint = $params->get('mobileRemovePrint', 0);
$mobileRemoveComments = $params->get('mobileRemoveComments', 0);
// Experimental
$mobileRemoveBotJs = $params->get('mobileRemoveBotJs', 0);
$mobileRemoveBotCss = $params->get('mobileRemoveBotCss', 0);

$commentCode = $params->get('commentCode');

$cdnUrl = $params->get('cdnUrl', 0);
// Remove any trailing slashes if added
$cdnUrl = rtrim($cdnUrl, '/');
$cdnJavascript = $params->get('cdnJavascript', 0);
$cdnFeaturedImages = $params->get('cdnFeaturedImages', 0);
$cdnContentImages = $params->get('cdnContentImages', 0);

$content404 = $params->get('content404');
$email404 = $params->get('email404', 0);
$email404recipient = $params->get('email404recipient');
