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
	$headerTag = $params->get('header_tag', 'h3');
	$headerClass = $params->get('header_class', '');
	if (JoomlaPure::isMobile() && !JoomlaPure::isTablet() && $mobileRemove) {
		return null;
	} elseif (JoomlaPure::isTablet() && $tabletRemove) {
		return null;
	} elseif (!JoomlaPure::ismobile() && $desktopRemove) {
		return null;
	} elseif (JoomlaPure::isBot() && $botRemove) {
		return null;
	} elseif ($module->content) { ?>
		<?php if ($module->showtitle) : ?>
		<div class="pure-module-title">
			<<?php echo $headerTag; ?>><span<?php if ($headerClass) echo ' class="'.$headerClass.'"'; ?>><?php echo $module->title; ?></span></<?php echo $headerTag; ?>>
		</div>
	<?php endif; ?>
	<?php echo $module->content; ?>
<?php
}
}