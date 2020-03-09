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


/**
 * K2toflexi Component Controller
 * 
 * @package		Joomla
 * @subpackage	k2toflexi
 */

class K2toflexiControllerAnalysis extends JControllerLegacy {
	/**Overide the display methode for the controller
	 * 
	 * @return	void
	 * @since 1.0
	 */
	
	function display($cachable = false, $urlparams = false) 
	{
		require_once JPATH_COMPONENT.'/helpers/analysis.php';

		// affectation de la vue récupérée en paramètre
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->getCmd('view', 'Analysis'));

		parent::display($cachable = false, $urlparams = false);

		return $this;
	}
	
	public function analyze()
	{
		$model = $this->getModel('analysis');
		$model->analyze();
	}
}