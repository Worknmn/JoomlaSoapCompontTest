<?php
/**
 * @package     Joomla.Site
 * @subpackage  Components.Aleantestw
 *
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Aleantestw Component Controller
 *
 * @package     Joomla.Site
 * @subpackage  Components.Aleantestw
 * @since       3.0.0
 */
class AleantestwController extends JControllerLegacy
{
  function display($cachable = false, $urlparams = false) 
  {
      // set default view if not set
      $input = JFactory::getApplication()->input;
      JRequest::setVar('view', JRequest::getCmd('view', 'default'));
  
      // Собственно решаем проблему именования и по умолчанию. Big or small first letter?
      $model = $this->getModel ( 'aleantestw' ); // get first model
      //die('111');
      $view  = $this->getView  ( 'default', 'html'  ); // get view we want to use
      //die('111');
      $view->setModel( $model, true );  // true is for the default model  
      //die('111');
      /*
      // for future. And need to check. 
      //$billsModel = $this->getModel ( 'mycomponents' ); // get second model   
      //$view->setModel( $billsModel );
      // After thar need call model in view like
      //$items2 = $this->get('Items', 'MyComponents');
      //
      /**/             
  
      // call parent
      parent::display($cachable, $urlparams);
  }
}
