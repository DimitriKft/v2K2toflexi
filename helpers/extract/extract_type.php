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

use Joomla\CMS\Factory as JFactory;

/**
 * Migrate display helper
 *
 * @package		Joomla
 * @subpackage	k2toflexi
 * @since		1.0
 */
class ExtractTypeHelper
{
    /**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */

	public function recoveredType($sql)
	{
        $db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query        = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id', 'name')))
			->from($db->quoteName('#__k2_extra_fields_groups'));
			$db->setQuery($query);
			$sql          =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertType($row);
			$name         = $row['name'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteType($name);
				$message  = self::insertType($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteType($name);
				$message  = self::insertType($row);
			}
            unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertCategory', 'sql' => '', 'message' => $message, 'type' => 'Type', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertType', 'sql' => $sql, 'message' => $message, 'type' => 'Type', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertCategory', 'sql' => '', 'message' => 'noexist', 'type' => 'Type', 'name' => "???"), JSON_NUMERIC_CHECK);
		return $valuesjsons;
    }
    

    public function insertType($row)
	{
		$db           = JFactory::getDBO();
		$user         = JFactory::getUser();
		$dateTime     = date_create('now')->format('Y-m-d H:i:s');
		$name         = $row['name'];
		$message   = self::addType(0, $db->Quote($name), $user->id, $db->Quote($dateTime));
		return $message ;
    }
    

    public function addType($record_ID, $name)
	{
	// record_ID is the id of the item that you want to load, or set it to zero for new item

		// **************************************
		// Include the needed classes and helpers
		// **************************************

		if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

		// ***
		// *** Create the item model object
		// ***

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('name')))
		->from($db->quoteName('#__k2toflexi_extra_fields_groups'))
		->where($db->quoteName('name') . ' = '. $name);
		$db->setQuery($query);
		$namelike =  $db->loadResult();
		if(!$namelike)
		{
			try
			// Prepare the insert query.
			{
				$db->transactionStart();
				$query = $db->getQuery(true);
				
				// Insert columns.
				$columns = array('name');
				
				// Insert values.
				$values = array($name);
				$query
				->insert($db->quoteName('#__k2toflexi_extra_fields_groups'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
				return "success" ;
			}
			catch(Exception $e)
			{
					// catch any database errors.
				$db->transactionRollback();
				self::deleteField($name);
				return "failed" ;// catch any database errors.
				$db->transactionRollback();
				self::deleteField($name);
				return "failed" ;
			}
			return "success" ;
		}
		else{
			return "exist" ;
		}
    }
	
	

    
	public function deleteType($name)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__k2toflexi_extra_fields_groups'))
		->where($db->quoteName('name') . ' = '. $db->Quote($name));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__k2toflexi_extra_fields_groups'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
	}
}