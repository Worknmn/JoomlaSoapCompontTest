<?php
/**
 * @package     Joomla.Site
 * @subpackage  Components.Aleantestw
 *
 */

// No direct access.
defined('_JEXEC') or die('Restricted access!');

JLoader::register('AleantestwHelper', JPATH_ADMINISTRATOR . '/components/com_aleantestw/helpers/helper.php');
JLoader::register('AleantestwFrontHelper', JPATH_COMPONENT . '/helpers/helper.php');

$controller = JControllerLegacy::getInstance('Aleantestw');
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
$controller->redirect();
