<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.pureseo
 *
 * @copyright   Copyright (C) Robert Went. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app   = JFactory::getApplication();
$doc   = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
$template = $this->getTemplate();

include (JPATH_BASE.DS.'templates'.DS.$template.DS.'includes'.DS.'vars.php');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<?php echo $loadCss; // Loads selected stylesheets ?>
<jdoc:include type="head" />
</head>
<body class="contentpane modal">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>
