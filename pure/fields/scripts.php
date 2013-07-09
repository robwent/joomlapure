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

class JFormFieldScripts extends JFormField
{
	protected $type = 'Scripts';

	protected function getInput()
	{
		$app = JFactory::getApplication();
		$template = $app->getTemplate();
		$doc = JFactory::getDocument();
		$root = JURI::root();
		$template = 'pureseo'; //until I find a way to get the current theme in admin
		$style = '#jform_params_loadCss {width:100%;min-height:40px;} #jform_params_test_field {min-width:250px;min-height:100px}'; //make the css box a bit bigger
		$doc->addStyleDeclaration($style);
		ob_start();
		?>

		<script type="text/javascript">
		jQuery(function() {

			function hideShow() {
				var i = jQuery('input[name="jform[params][frameworkCSS]"]:checked').val();

				if(i=='custom'){
					jQuery('#collapse3').parent('div.accordion-group').show();
				} else {
					jQuery('#collapse3').parent('div.accordion-group').hide();
				}
			}
			//check on page load
			hideShow();
            //check on click
            jQuery('#jform_params_frameworkCSS0,#jform_params_frameworkCSS1,#jform_params_frameworkCSS2').click(hideShow);

			//default links
			var style = '<link rel="stylesheet" href="<?php echo JURI::root(true) ; ?>/templates/<?php echo $template; ?>/css/style.css" type="text/css" />';
			var local = '<link rel="stylesheet" href="/templates/<?php echo $template; ?>/css/pure-min.css" type="text/css" />';
			var cdn = '<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.2.0/pure-min.css" type="text/css" />';

			//check for button changes
			jQuery('#jform_params_frameworkCSS0,#jform_params_modBase,#jform_params_modButtons,#jform_params_modFormsR,#jform_params_modFormsNR,#jform_params_modGridsR,#jform_params_modGridsNR,#collapse2 #jform_params_modMenusR,#jform_params_modMenusNR,#jform_params_modTables').click(calculateCdn);
			jQuery('#jform_params_frameworkCSS1').on("click", function(){
				jQuery('#jform_params_loadCss').text(local);
			});
			jQuery('#jform_params_frameworkCSS2').on("click", function(){
				jQuery('#jform_params_loadCss').text(style);
			});
			//calculate which files should be loaded by the template
			function calculateCdn() {
				var i = 0;
				var url = '';
				if (jQuery('input[name="jform[params][modBase]"]:checked').val() == 1) {
					url += 'pure/0.2.0/base-min.css&amp;'; i ++;
				}
				if (jQuery('input[name="jform[params][modButtons]"]:checked').val() == 1) {
					url += 'pure/0.2.0/buttons-min.css&amp;'; i ++;
				}
				if (jQuery('input[name="jform[params][modFormsR]"]:checked').val() == 1) {
					url += 'pure/0.2.0/forms-min.css&amp;'; i ++;
				}
				if (jQuery('input[name="jform[params][modFormsNR]"]:checked').val() == 1) {
					url += 'pure/0.2.0/forms-nr-min.css&amp;'; i ++;
				}
				if (jQuery('input[name="jform[params][modGridsR]"]:checked').val() == 1) {
					url += 'pure/0.2.0/grids-min.css&amp;'; i ++;
				}
				if (jQuery('input[name="jform[params][modGridsNR]"]:checked').val() == 1) {
					url += 'pure/0.2.0/grids-nr-min.css&amp;'; i ++;
				}
				if (jQuery('input[name="jform[params][modMenusR]"]:checked').val() == 1) {
					url += 'pure/0.2.0/menus-min.css&amp;'; i ++;
				}
				if (jQuery('input[name="jform[params][modMenusNR]"]:checked').val() == 1) {
					url += 'pure/0.2.0/menus-nr-min.css&amp;'; i ++;
				}
				if (jQuery('input[name="jform[params][modTables]"]:checked').val() == 1) {
					url += 'pure/0.2.0/tables-min.css&amp;'; i ++;
				}

			if (i === 9) { //all selected so just load the full file
				jQuery('#jform_params_loadCss').text(cdn + style);
			} else if (i === 0) { //none selected so just load style.css
				jQuery('#jform_params_loadCss').text(style);
			} else { //some selected so trim last amp and load with style
				var urlstart = '<link rel="stylesheet" href="http://yui.yahooapis.com/combo?';
				url = url.slice(0, - 5);
				var urlend = '">';
				jQuery('#jform_params_loadCss').text(urlstart + url + urlend + style);
			}
		};
	});
</script>
<?php
return ob_get_clean();
}
}
//node = jQuery(this);
//console.log(value);
//console.log(node);
// remove the last 5 chars from a string var.slice(0, - 5);