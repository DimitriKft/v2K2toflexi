<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	k2toflexi
 * @copyright	(C) 2017 Com'3Elles. All right reserved
 * @license GNU/GPL v2
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class k2toflexiViewK2toflexi extends JViewLegacy
{
	// Surcharge de la méthode display héritée
	function display($tpl = null)
	{
		require_once JPATH_COMPONENT . '/helpers/k2toflexi.php';
		// Test Sidebar
		K2toflexidHelper::addSubmenu('migrate');
		$this->sidebar = JHtmlSidebar::render();
		$this->addToolBar();
		$this->msg = 'Migrate';
		parent::display($tpl);
	}
	
	protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$state = $this->get('State');
		$canDo = migrateHelper::getActions();

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		
		JToolBarHelper::title(JText::_("COM_K2TOFLEXI_MIGRATE_TITLE"));
// 		ToolBarHelper::title(JText::_('K2_CATEGORIES'), 'k2.png');

		if ( !JComponentHelper::isEnabled( 'com_flexicontent', true) ) {
			echo 'This modules requires component FLEXIcontent!';
			return;
		}
		else {
			JToolBarHelper::custom('k2toflexi.migrate', 'copy.png', 'copy_f2.png', 'Migrate', false, true);
		}
		
		JToolbarHelper::cancel('k2toflexi.cancel');
	

		//check if the Options button can be added.
		if ($canDo->get('core.admin')){
			JToolBarHelper::preferences('com_k2toflexi');
		}
	}
}
