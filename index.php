<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.pureseo
 *
 * @copyright   Copyright (C) Robert Went. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$params = $app->getTemplate(true)->params;
$this->language = $doc->language;
$this->direction = $doc->direction;
$template = $app->getTemplate();
// Move all the guff somewhere else
include ('pure'.DIRECTORY_SEPARATOR.'config.php');
include ('pure'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'main_index_include.php');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width">
	<?php
	if (!(JPluginHelper::isEnabled('system', 'pure_mobiledetect') && MobileDetector::isBot() && $mobileRemoveBotCss)) {
		if (JPluginHelper::isEnabled('system', 'pure_mobiledetect') && MobileDetector::isMobile() && !MobileDetector::isTablet() && $mobileMobileCss) {
		// Mobiledetect installed/enabled/selected, is a mobile -> load mobile.css ?>
		<link rel="stylesheet" href="/pure-seo/templates/<?php echo $template; ?>/css/mobile.css" type="text/css" />
		<?php } elseif (JPluginHelper::isEnabled('system', 'pure_mobiledetect') && MobileDetector::isTablet() && $mobileTabletCss) {
		// Mobiledetect installed/enabled/selected, is a tablet -> load tablet.css ?>
		<link rel="stylesheet" href="/pure-seo/templates/<?php echo $template; ?>/css/tablet.css" type="text/css" />
		<?php } else {
		echo $loadCss; // Loads selected stylesheets
	}
}
	 // Add jQuery if set to load in the head
if ((JPluginHelper::isEnabled('system', 'pure_mobiledetect') && MobileDetector::isBot() && $mobileRemoveBotJs)) {
if ($addJquery == 'top' && $jqueryVersion) : ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/<?php echo $jqueryVersion; ?>/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo JURI::root(true) ?>/templates/<?php echo $template; ?>/js/jquery-1.8.3.min.js"><\/script>')</script>
<?php endif;
} ?>
<jdoc:include type="head" />
</head>
<body>
	<!--start demo-->
	<h1><?php echo JRequest::getVar('view'); ?></h1>
	<div class="content pure-g-r" id="layout">
		<?php if ($this->countModules('side-nav')) : ?>
		<div class="sidebar pure-u">
			<header class="header pure-u-1">
				<hgroup <?php if ($waiAriaRoles) echo 'role="banner"'; ?>>
					<span class="brand-title"><a href="<?php echo $this->baseurl; ?>" title="<?php echo $sitename; ?>"><?php echo $sitename; ?></a></span>
					<span class="brand-tagline">Extreme Joomla Optimization</span>
				</hgroup>

				<nav class="nav" <?php if ($waiAriaRoles) echo 'role="navigation"'; ?>>
					<jdoc:include type="modules" name="side-nav" style="pureseo" />
				</nav>
			</header>
		</div>
	<?php endif; ?>
	<?php if ($this->countModules('banner')) : ?>
	<div class="splash pure-u-1">
		<div class="pure-g-r">
			<jdoc:include type="modules" name="banner" style="pureseo" />
		</div>
	</div>
<?php endif; ?>

<div class="content pure-u-1" <?php if ($waiAriaRoles) echo 'role="main"'; ?>>
	<div class="pure-g-r content-ribbon">
		<jdoc:include type="message" />
		<?php if ($removeComponent != 1) : ?>
		<jdoc:include type="component" />
	<?php endif; ?>
</div>
</div>
<?php if ($this->countModules('footer')) : ?>
	<footer class="footer pure-u-1">
		<jdoc:include type="modules" name="footer" style="pureseo" />
	</footer>
<?php endif; ?>
</div>
<!--end demo-->


<?php
if (!(JPluginHelper::isEnabled('system', 'pure_mobiledetect') && MobileDetector::isBot() && $mobileRemoveBotJs)) {
if ($addJquery == 'bottom' && $jqueryVersion) : ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/<?php echo $jqueryVersion; ?>/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo JURI::root(true) ?>/templates/<?php echo $template; ?>/js/jquery-1.8.3.min.js"><\/script>')</script>
<?php endif;
if ($addPlugins) {
	if ($cdnUrl && $cdnJavascript) { ?>
	<script src="<?php echo $cdnUrl ?>/templates/<?php echo $template; ?>/js/plugins.js"></script>
	<?php } else { ?>
	<script src="<?php echo JURI::root(true) ?>/templates/<?php echo $template; ?>/js/plugins.js"></script>
	<?php }
};
if ($addScripts) {
	if ($cdnUrl && $cdnJavascript) { ?>
	<script src="<?php echo $cdnUrl ?>/templates/<?php echo $template; ?>/js/scripts.js"></script>
	<?php } else { ?>
	<script src="<?php echo JURI::root(true) ?>/templates/<?php echo $template; ?>/js/scripts.js"></script>
	<?php }
};
if ($this->params->get('analyticsCode') || ($this->params->get('googleFont') && $this->params->get('googleFontLoader'))) : ?>
<script type="text/javascript">
<?php endif;
if ($this->params->get('googleFont') && $this->params->get('googleFontName') && $this->params->get('googleFontLoader')): ?>
	WebFontConfig={google:{families:["<?php echo $this->params->get('googleFontName'); ?>"]}};(function(){var e=document.createElement("script");e.src=("https:"==document.location.protocol?"https":"http")+"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";e.type="text/javascript";e.async="true";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();
<?php endif;
if ($this->params->get('analyticsCode')) {
	$scriptReg = '/<\/?script[^>]*?>/';
$acode = preg_replace($scriptReg, '', $this->params->get('analyticsCode')); //Remove script tags
$acode = preg_replace('/\s+/', '', $acode); //Remove all whitespace
echo $acode;
}
if ($this->params->get('analyticsCode') || ($this->params->get('googleFont') && $this->params->get('googleFontLoader'))) : ?>
	</script>
<?php endif;
}
if (!(JPluginHelper::isEnabled('system', 'pure_mobiledetect') && (MobileDetector::isBot() || MobileDetector::isMobile() || MobileDetector::isTablet()) )) {?>
<jdoc:include type="modules" name="debug" style="none" />
<?php } ?>
</body>
</html>