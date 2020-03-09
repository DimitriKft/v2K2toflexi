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

jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');

/**
 * K2toflexi Component Controller
 * 
 * @package		Joomla
 * @subpackage	k2toflexi
 */

class K2toflexiControllerK2toflexi extends JControllerLegacy {
	/**Overide the display methode for the controller
	 * 
	 * @return	void
	 * @since 1.0
	 */
	
	function display($cachable = false, $urlparams = false) 
	{
		require_once JPATH_COMPONENT.'/helpers/migrate.php';

		// affectation de la vue récupérée en paramètre
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->getCmd('view', 'K2toflexi'));

		parent::display($cachable = false, $urlparams = false);
		
		return $this;
	}
	
	/**
	 * method to call the migation method from the ajax code
	 *
	 * @return void
	 * @since 1.0
	 */
	
	public function migrate($data = false)
	{
		if ($data == false ){
			$data = json_encode(array("success" => true, "message" => "Loading...", "task" => 'insertImg', "sql" => ''));
		}
  		$data = $_POST['json'];
		$data = json_decode($data);
  		$task = $data->{'task'};
  		$sql = $data->{'sql'};
		$valuesjsons = $this->getModel('migrate')->migration($task, $sql);
		$message = $this->getModel('message')->message($valuesjsons);	
		$valuesjson = json_decode($valuesjsons);
		$task = $valuesjson->{'task'};
		$sql = $valuesjson->{'sql'};
		
		$response = json_encode(array("success" => true, "message" => $message, "task" => $task, "sql" => $sql));
		echo $response;

		die;
	}


	public function analysis($data = false)
	{
		if ($data == false ){
			$data = json_encode(array("success" => true, "message" => "Loading...", "task" => 'insertImg', "sql" => ''));
		}
  		$data = $_POST['json'];
		$data = json_decode($data);
  		$task = $data->{'task'};
  		$sql = $data->{'sql'};
		$valuesjsons = $this->getModel('analysis')->analyze($task, $sql);
		$message = $this->getModel('message')->message($valuesjsons);	
		$valuesjson = json_decode($valuesjsons);
		$task = $valuesjson->{'task'};
		$sql = $valuesjson->{'sql'};
		
		$response = json_encode(array("success" => true, "message" => $message, "task" => $task, "sql" => $sql));
		echo $response;

		die;
	}
	
	/**
	 * method to send the data for the ajax call
	 *
	 * @return void
	 * @since 1.0
	 */
	
	public function firstmigrate()
	{
		$response = json_encode(array("success" => true, "message" => "Loading...", "task" => 'insertImg', "sql" => ''));
		
		echo $response;
		die;
	}
	
	/**
	 * method to continue the migration in case an error happens so that it doesn't crash
	 *
	 * @return void
	 * @since 1.0
	 */
	
	public function errorcorect()
	{

		$data = $_POST;
		$response = substr(strval($data), 0, strpos(strval($data), "<font size='1'><table class='xdebug-error xe-fatal-error' dir='ltr' border='1' cellspacing='0' cellpadding='1'>"));
		echo $response;
		die;
	}
}