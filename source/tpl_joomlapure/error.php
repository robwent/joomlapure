<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.pureseo
 *
 * @copyright   Copyright (C) Robert Went. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');


include ('pure'.DIRECTORY_SEPARATOR.'config.php');

// Check for a referer and if so send mail
if (isset($_SERVER["HTTP_REFERER"]) && $email404 && $email404recipient){
	// Mail Settings
	$mailer = JFactory::getMailer();
// Set sender as site default
	$config = JFactory::getConfig();
	$sender = array( 
		$config->get('config.mailfrom'),
		$config->get('config.fromname') );
	$mailer->setSender($sender);
// Set recipient
	$recipient = trim($email404recipient);
	$mailer->addRecipient($recipient);
// Mail body
	$body   = JText::_('TPL_JOOMLAPURE_404_PAGE_ALERT_FOR')." ".$sitename."\n\n".JText::_('TPL_JOOMLAPURE_THE_USER_WAS_REFERED_FROM')." ".$_SERVER["HTTP_REFERER"]." \n\n".JText::_('TPL_JOOMLAPURE_THEY_WERE_TRYING_TO_REACH')." ".JURI::base().ltrim($_SERVER['REQUEST_URI'], '/');
	$subject = JText::_('TPL_JOOMLAPURE_404_MAIL_SUBJECT');
	$mailer->setSubject($subject);
	$mailer->setBody($body);
	$send = $mailer->Send();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->title; ?> <?php echo $this->error->getMessage();?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php echo $this->language; ?>" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css" type="text/css" />

	<?php
	$debug = JFactory::getConfig()->get('debug_lang');
	if ((defined('JDEBUG') && JDEBUG) || $debug)
	{
		?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/cms/css/debug.css" type="text/css" />
		<?php
	}
	?>
	<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

</head>

<body class="site">
	<div class="content pure-g-r"> <!--content-->
		<header class="header pure-u-1">

		</header>
		<div class="content pure-g-r"> <!--main wrap-->
			<div class="main pure-u-1 content-ribbon" <?php if ($waiAriaRoles) echo 'role="main"'; ?>>
				<?php echo $content404; ?>
				<?php 
				if (isset($_SERVER["HTTP_REFERER"]) && $email404 && $email404recipient && $send !== true) { //Let the visitor know if admin has been notified
					echo JText::_('TPL_JOOMLAPURE_404_ERROR_SENDING_MAIL').' ' . $send->message;
				} elseif (isset($_SERVER["HTTP_REFERER"]) && $email404 && $email404recipient && $send === true) {
					echo JText::_('TPL_JOOMLAPURE_404_ADMIN_IS_INFORMED');
				}
				?>
			</div>
			<div class="main pure-u-1">
			<?php
			$this->searchmodules = JModuleHelper::getModules('404search');
			foreach ($this->searchmodules as $searchmodule)
			{
				$output = JModuleHelper::renderModule($searchmodule, array('style' => 'puredefault'));
				$params = new JRegistry;
				$params->loadString($searchmodule->params);
				echo $output;
			}
			?>
			</div>
		</div> <!--end main wrap-->
	</div> <!--end content-->
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
