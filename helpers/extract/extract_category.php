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
class ExtractCatHelper
{
	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */
	public function recoveredCategory($sql)
	{
        $db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query        = $db->getQuery(true);
			$query
			->select($db->quoteName(array('name', 'alias', 'description', 'parent', 'extraFieldsGroup', 'published', 'access', 'ordering', 'image', 'params', 'trash', 'plugins', 'language')))
			->from($db->quoteName('#__k2_categories'));
			$db->setQuery($query);
			$sql          =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertCategory($row);
			$name         = $row['name'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteCategory($name);
				$message  = self::insertCategory($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteCategory($name);
				$message  = self::insertCategory($row);
			}
            unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertItem', 'sql' => '', 'message' => $message, 'type' => 'Category', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertCategory', 'sql' => $sql, 'message' => $message, 'type' => 'Category', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertItem', 'sql' => '', 'message' => 'noexist', 'type' => 'Category', 'name' => "name"), JSON_NUMERIC_CHECK);
		return $valuesjsons;
    }
    

    
	public function insertCategory($row)
	{
		$db              = JFactory::getDBO();
		$user            = JFactory::getUser();
		$dateTime        = date_create('now')->format('Y-m-d H:i:s');
		$name            = $row['name'];
		$alias           = $row['alias'];
		$description     = $row['description'];
		$parent          = $row['parent'];
		$extrafieldsgroup = $row['extraFieldsGroup'];
		$published       = $row['published'];
		$access          = $row['access'];
		$ordering        = $row['ordering'];
		$image           = $row['image'];
		$params          = $row['params'];
		$trash           = $row['trash'];
		$plugins         = $row['plugins'];
		$language        = $row['language'];
		$message         = self::addCategory(0, $db->Quote($name), $db->Quote($alias), $db->Quote($description), $db->Quote($parent), $db->Quote($extrafieldsgroup), $published, $db->Quote($access),
		$db->Quote($ordering), $db->Quote($image), $db->Quote($params), $db->Quote($trash), $db->Quote($plugins), $db->Quote($language), $user->id, $dateTime);
		return $message;
	}

    public function addCategory($record_ID,$name, $alias, $description, $parent, $extrafieldsgroup, $published, $access, $ordering, $image, $params, $trash, $plugins, $language)
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
		->from($db->quoteName('#__k2toflexi_categories'))
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
				$columns = array('name', 'alias', 'description', 'parent', 'extraFieldsGroup', 'published', 'access', 'ordering', 'image', 'params', 'trash', 'plugins', 'language');
				
				// Insert values.
				$values = array($name, $alias, $description, $parent, $extrafieldsgroup, $published, $access, $ordering, $image, $params, $trash, $plugins, $language);
				$query
				->insert($db->quoteName('#__k2toflexi_categories'))
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
				self::deleteCategory($name);
				return "failed" ;// catch any database errors.
				$db->transactionRollback();
				self::deleteCategory($name);
				return "failed" ;
			}
			return "success" ;
		}
		else{
			return "exist" ;
		}
	}

	public function deleteCategory($name)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__k2toflexi_categories'))
		->where($db->quoteName('name') . ' = '. $db->Quote($name));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__k2toflexi_categories'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
	}

}