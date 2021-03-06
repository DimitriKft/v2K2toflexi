

<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the HelloWorld Component
 *
 * @since  0.0.1
 */
class K2toflexiViewSelect extends JViewLegacy
{
/**
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		// Toolbar
		$this->addToolBar();

		// Recovored data
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Sidebar
		require_once JPATH_COMPONENT . '/helpers/k2toflexi.php';
		K2toflexidHelper::addSubmenu('select');
		$this->sidebar = JHtmlSidebar::render();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Display the view
		parent::display($tpl);
	}
	
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_("Votre sélection"));
	}
}