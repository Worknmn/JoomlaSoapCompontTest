<?php
/**
 * @package     Joomla.Site
 * @subpackage  Components.Aleantestw
 *
 * @since       3.0.0
 */

// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_aleantestwcomponent'))
{
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

$controller = JControllerLegacy::getInstance('AleantestwComponent');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();

