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

class ExtractFieldHelper
{
//         /**
// 	 * gets a list of the actions that can be performed.
// 	 * 
// 	 * @return 	JObject
// 	 * @since	1.0
// 	 */
	public function recoveredFields($sql) 
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query        = $db->getQuery(true);
			$query
			->select($db->quoteName(array('name','value', 'type', 'group', 'published', 'ordering')))
			->from($db->quoteName('#__k2_extra_fields'));
			$db->setQuery($query);
			$sql          =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertFields($row);
			$name         = $row['name'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteField($name);
				$message  = self::insertFields($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteField($name);
				$message  = self::insertFields($row);
			}
			unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertType', 'sql' => '', 'message' => $message, 'type' => 'Field', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertFields', 'sql' => $sql, 'message' => $message, 'type' => 'Field', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertType', 'sql' => '', 'message' => 'noexist', 'type' => 'Field', 'name' => $name), JSON_NUMERIC_CHECK);
		return $valuesjsons;
	}
	
	
	public function insertFields($row)
	{
		$db           = JFactory::getDBO();
		$user         = JFactory::getUser();
		$dateTime     = date_create('now')->format('Y-m-d H:i:s');
		$name         = $row['name'];
		$value        = $row['value'];
		$type         = $row['type'];
		$group        = $row['group'];
		$published    = $row['published'];
		$ordering     = $row['ordering'];
		$message   = self::addField(0, $db->Quote($name),$db->Quote($value),$db->Quote($type),$db->Quote($group), $published ,$db->Quote($ordering), $user->id, $db->Quote($dateTime));
		return $message ;
	}


	public function addField($record_ID, $name, $value, $published, $group, $type, $ordering)
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
		->from($db->quoteName('#__k2toflexi_extra_fields'))
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
				$columns = array('name','value','published','group', 'type', 'ordering');
				
				// Insert values.
				$values = array($name, $value, $type, $group, $published, $ordering);
				$query
				->insert($db->quoteName('#__k2toflexi_extra_fields'))
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


	public function deleteField($name)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__k2toflexi_extra_fields'))
		->where($db->quoteName('name') . ' = '. $db->Quote($name));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__k2toflexi_extra_fields'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
	}
}
