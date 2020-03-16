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
class InsertTagHelper
{
   	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */

	public function tag($task, $sql)
	{
		//initialise variables.
		$db                         = JFactory::getDBO();
		$user                       = JFactory::getUser();
		$dateTime                   = date_create('now')->format('Y-m-d H:i:s');
		$sql                        = json_decode(json_encode($sql),    true);
		$params                     = JComponentHelper::getParams('com_k2toflexi');
		$recoveredTags              = $params['insertTags'];
		$debug                      = $params['debug'];
		$rotatDate                  = JFactory::getDate()->format('Y-m');

		Jlog::addLogger ( 
			array(
				'logger'   => 'database',
				'db_table' => '#__log_k2toflexi',
				),
				JLog::INFO
			);
		Jlog::addLogger ( 
			array(
				'text_file'         => 'k2toflexi_'.$rotatDate.'.log.php',
				'text_entry_format' => '{DATE} {TIME} {CLIENTIP} {CATEGORY} {MESSAGE}'
				),
				JLog::ERROR
			);

	
	
	 if($task == 'insertTags')
		{
			$valuesjsons = $this->recoveredTags($sql);
			return $valuesjsons;
			if($debug == 1)
			{
            	$logEntry = new JlogEntry("Insert tag ", JLog::INFO, $srvdate , 'tag');
            	Jlog::add($logEntry);
				$logEntry2 = new JlogEntry("Insert tag ", JLog::ERROR, $srvdate , 'tag');
				Jlog::add($logEntry2);
			}
			die();
		}
		else
		{
			return (json_encode(array('task' => false, 'sql' => '', 'message' => '', 'type' => '', 'name' => '')));
			die;
		}
	}

     
	public function recoveredTags($sql) 
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query        = $db->getQuery(true);
			$query
			->select($db->quoteName(array('name', 'published')))
			->from($db->quoteName('#__k2toflexi_tags'));
			$db->setQuery($query);
			$sql          =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertTags($row);
			$name         = $row['name'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteTag($name);
				$message  = self::insertTags($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteTag($name);
				$message  = self::insertTags($row);
			}
			unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertFields', 'sql' => '', 'message' => $message, 'type' => 'Tag', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertTags', 'sql' => $sql, 'message' => $message, 'type' => 'Tag', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertFields', 'sql' => '', 'message' => 'noexist', 'type' => 'Tag', 'name' => $name), JSON_NUMERIC_CHECK);
		return $valuesjsons;
    }
	
		
	public function insertTags($row)
	{
		$db        = JFactory::getDBO();
		$user      = JFactory::getUser();
		$dateTime  = date_create('now')->format('Y-m-d H:i:s');
		$name      = $row['name'];
		$published = $row['published'];
		$message   = self::addTag(0, $db->Quote($name), $db->Quote($name), $published, $user->id, $db->Quote($dateTime));
		return $message ;
	}


	public function addTag($record_ID, $name, $alias, $published, $checked_out, $checked_out_time)
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
		->from($db->quoteName('#__flexicontent_tags'))
		->where($db->quoteName('name') . ' = '. $name);
		$db->setQuery($query);
		$namelike =  $db->loadResult();

		if(!$namelike)
		{
			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);

				// Insert columns.
				$columns = array('name', 'alias', 'published', 'checked_out', 'checked_out_time');

				// Insert values.
				$values = array($name, $alias, $published, $checked_out, $checked_out_time);

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_tags'))
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
				self::deleteTag($name);
				return "failed" ;
			}
			return "success" ;
		}
		else{
			return "exist" ;
		}
	}



	public function deleteTag($name)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__flexicontent_tags'))
		->where($db->quoteName('name') . ' = '. $db->Quote($name));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_tags'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
	}

}