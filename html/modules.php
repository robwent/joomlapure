<?php
defined('_JEXEC') or die('Restricted access');

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the sliders style, you would use the following include:
 * <jdoc:include type="module" name="test" style="slider" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * three arguments.
 */

function modChrome_puredefault($module, &$params, &$attribs)
{
	$mobileRemove = preg_match ('/mobile-remove/', $params->get('moduleclass_sfx'))?1:0;
	$tabletRemove = preg_match ('/tablet-remove/', $params->get('moduleclass_sfx'))?1:0;
	$desktopRemove = preg_match ('/desktop-remove/', $params->get('moduleclass_sfx'))?1:0;
	$botRemove = preg_match ('/bot-remove/', $params->get('moduleclass_sfx'))?1:0;
	$headerTag = $params->get('header_tag');
	$headerTag = isset($headerTag) ? $headerTag : 'h3';
	if (JPluginHelper::isEnabled('system', 'pure_mobiledetect') && MobileDetector::isMobile() && !MobileDetector::isTablet() && $mobileRemove) {
		return null;
	} elseif (JPluginHelper::isEnabled('system', 'pure_mobiledetect') && MobileDetector::isTablet() && $tabletRemove) {
		return null;
	} elseif (JPluginHelper::isEnabled('system', 'pure_mobiledetect') && !MobileDetector::ismobile() && $desktopRemove) {
		return null;
	} elseif (JPluginHelper::isEnabled('system', 'pure_mobiledetect') && MobileDetector::isBot() && $botRemove) {
		return null;
	} elseif ($module->content) { ?>
	<div class="moduletable <?php echo $params->get('moduleclass_sfx'); ?>">
		<?php if ($module->showtitle) : ?>
		<div class="pure-module-title">
			<<?php echo $headerTag; ?>><span><?php echo $module->title; ?></span></<?php echo $headerTag; ?>>
		</div>
	<?php endif; ?>
	<?php echo $module->content; ?>
</div>
<?php
}
}