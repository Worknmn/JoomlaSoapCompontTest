<?php
/**
 * @package     Joomla.Site
 * @subpackage  Components.Aleantestw
 *
 */

// No direct access
defined('_JEXEC') or die;

//JViewLegacy loadHelper ?
JLoader::register('AleantestwHelper', JPATH_ADMINISTRATOR . '/components/com_aleantestw/helpers/helper.php');

/**
 * View class Default
 *
 * @package     Joomla.Administrator
 * @subpackage  Components.AleantestwComponent
 * @since       3.0.2
 */
class AleantestwComponentViewDefault extends JViewLegacy
{
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed         A string if successful, otherwise a JError object.
	 *
	 * @since   3.0.2
	 */
	public function display($tpl = null)
	{
  $errors = $this->get('Errors');
  //AleantestwHelper::vdw($errors);
		if (!is_null($errors) AND count($errors))
		{
			throw new Exception(implode("", $errors), 500);
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   3.0.2
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_ALEANTESTW_DEFAULT_TITLE'), 'home-2 default');
	}
}
