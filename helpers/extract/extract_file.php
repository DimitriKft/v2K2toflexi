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
class ExtractFileHelper
{
	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */


    public function recoveredFiles($sql)
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('itemID', 'filename', 'title', 'titleAttribute', 'hits')))
			->from($db->quoteName('#__k2_attachments'));

			$db->setQuery($query);
			$sql =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertFiles($row);
			$title        = $row['title'];
			$filename     = $row['filename'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteFile($filename, $title);
				$message = self::insertFiles($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteFile($filename, $title);
				$message = self::insertFiles($row);
			}
			unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertTags', 'sql' => '', 'message' => $message, 'type' => 'File', 'name' => $title), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertFiles', 'sql' => $sql, 'message' => $message, 'type' => 'File', 'name' => $title), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertTags', 'sql' => '', 'message' => "noexist", 'type' => 'File', 'name' => "???"), JSON_NUMERIC_CHECK);
		return $valuesjsons;
	}


	

	public function insertFiles($row)
	{
		$db             = JFactory::getDBO();
		$user           = JFactory::getUser();
		$dateTime       = date_create('now')->format('Y-m-d H:i:s');
		$itemID         = $row['itemID'];
		$filename       = $row['filename'];
		$title          = $row['title'];
		$titleAttribute = $row['titleAttribute'];
		$hits           = $row['hits'];
		$message   = self::addFile(0, $db->Quote($itemID),$db->Quote($filename),$db->Quote($title),$db->Quote($titleAttribute), $db->Quote($hits), $user->id, $db->Quote($dateTime));
		return $message ;
	}
    


	

	public function addFile($record_ID, $itemID, $filename, $title, $titleAttribute, $hits, $user, $datetime)
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
		->select($db->quoteName(array('itemID')))
		->from($db->quoteName('#__k2toflexi_attachments'))
				->where($db->quoteName('itemID') . ' = '. $itemID);
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
				$columns = array('itemID', 'filename', 'title', 'titleAttribute', 'hits');
				
				// Insert values.
				$values = array($itemID, $filename, $title, $titleAttribute, $hits);
				$query
				->insert($db->quoteName('#__k2toflexi_attachments'))
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
				self::deleteField($itemID);
				return "failed" ;// catch any database errors.
				$db->transactionRollback();
				self::deleteField($itemID);
				return "failed" ;
			}
			return "success" ;
		}
		else{
			return "exist" ;
		}
    }
    


	public function deleteFile($filename, $title)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__k2toflexi_attachments'))
		->where($db->quoteName('itemID') . ' = '. $db->Quote($itemID));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__k2toflexi_attachments'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);

	}


     
}