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
class InsertTypeHelper
{
    /**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */

	public function type($task, $sql)
	{
		//initialise variables.
		$db                         = JFactory::getDBO();
		$user                       = JFactory::getUser();
		$dateTime                   = date_create('now')->format('Y-m-d H:i:s');
		$sql                        = json_decode(json_encode($sql),    true);
		$params                     = JComponentHelper::getParams('com_k2toflexi');
		$recoveredType              = $params['insertType'];
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

		 if($task == 'insertFields'   && $recoveredFields   == 2)
		 {
			$task =      'insertType';
		 }

	    if($task == 'insertType')
		{
			$valuesjsons = $this->recoveredType($sql);
			return $valuesjsons;
			if($debug == 1)
			{
				$logEntry = new JlogEntry("Insert type ", Jlog::INFO, $srvdate , 'type');
				Jlog::add($logEntry);
				$logEntry = new JlogEntry("Insert type ", Jlog::ERROR, $srvdate , 'type');
				Jlog::add($logEntry);
			}
			die();
		}
		else
		{
			return (json_encode(array('task' => false, 'sql' => '', 'message' => '', 'type' => '', 'name' => '')));
			die;
		}
	}


	public function recoveredType($sql)
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id', 'name')))
			->from($db->quoteName('#__k2toflexi_extra_fields_groups'));
			$db->setQuery($query);
			$sql   =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertType($row);
			$name         = $row['name'];
			$alias        = str_replace('  ','-',$name);
			$alias        = strtr($alias,  "�����������������������������������������������������","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn" );
			$alias        = strtolower($alias);
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteType($name, $alias);
				$message = self::insertType($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteType($name, $alias);
				$message = self::insertType($row);
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
		$db       = JFactory::getDBO();
		$user     = JFactory::getUser();
		$dateTime = date_create('now')->format('Y-m-d H:i:s');
		$name     = $row['name'];
		$id       = $row['id'];
		$alias    = str_replace(' ','-',$name);
		$alias    = strtr($alias,  "�����������������������������������������������������","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn" );
		$alias    = strtolower($alias);
		$attribs  = '{"ilayout":"default","hide_maintext":"0","hide_html":"0","maintext_label":"","maintext_desc":"","comments":"","top_cols":"two","bottom_cols":"two","allow_jview":"1"}';
		$message  = self::addType($id, $name, $alias, 1, 0, $user->id, $dateTime, 1, $attribs);
		return $message ;
    }
    

    public function addType($groupid, $name, $alias, $published, $itemscreatable, $checked_out, $checked_out_time, $access, $attribs)
	{
		// record_ID is the id of the item that you want to load, or set it to zero for new item

		// **************************************
		// Include the needed classes and helpers
		// **************************************

		if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);//TODO a vérifier

		// ***
		// *** Create the item model object
		// ***

		$db      = JFactory::getDBO();
		$query   = $db->getQuery(true);
		$query
		->select($db->quoteName(array('name')))
		->from($db->quoteName('#__flexicontent_types'))
		->where($db->quoteName('name') . ' = '. $db->Quote($name) . ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
		$db->setQuery($query);
		$namelike =  $db->loadResult();

		if(!$namelike)
		{
			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);

				// Insert columns.
				$columns = array('asset_id', 'name', 'alias', 'published', 'itemscreatable', 'checked_out', 'checked_out_time', 'access', 'attribs');

				// Insert values.
				$values = array(999, $db->Quote($name), $db->Quote($alias), $published, $db->Quote($itemscreatable), $db->Quote($checked_out), $db->Quote($checked_out_time), $access, $db->Quote($attribs));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_types'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch(Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteType($name, $alias);
				return "failed" ;
			}

			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id')))
			->from($db->quoteName('#__flexicontent_types'))
			->where($db->quoteName('name') . ' = '. $db->Quote($name). ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
			$db->setQuery($query);
			$id =  $db->loadResult();
			$parentid  = self::getFlexiId();
			$assetname = self::addAsset($id, $parentid, 'type', $name, $alias, true);

			if($assetname == false)
			{
				$query = $db->getQuery(true);
				$conditions = array(
						$db->quoteName('name') . ' = '. $db->Quote($name)
				);
				$query->delete($db->quoteName('#__flexicontent_types'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->query($query);
				return "failed" ;
			}
			else
			{
				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id')))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('name') . ' = '. $db->Quote($assetname));
				$db->setQuery($query);
				$assetid =  $db->loadResult();

				try
				{
					$db->transactionStart();
					$query = $db->getQuery(true);
					$fields = array(
							$db->quoteName('asset_id') . ' = ' . $db->quote($assetid)
					);

					// Conditions for which records should be updated.
					$conditions = array(
							$db->quoteName('name') . ' = ' . $db->quote($name),
					);
					$query->update($db->quoteName('#__flexicontent_types'))->set($fields)->where($conditions);
					$db->setQuery($query);
					$result = $db->execute();
					$db->transactionCommit();
				}
				catch(Exception $e)
				{
					// catch any database errors.
					$db->transactionRollback();
					self::deleteType($name, $alias);
					return "failed" ;
				}

				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id')))
				->from($db->quoteName('#__flexicontent_types'))
				->where($db->quoteName('asset_id') . ' = '. $db->Quote($assetid));
				$db->setQuery($query);
				$type_id =  $db->loadResult();

				for($i = 1; $i <= 14; $i++)
				{
						try
						{
							$db->transactionStart();
							$query = $db->getQuery(true);

							// Insert columns.
							$columns = array('field_id', 'type_id', 'ordering');

							// Insert values.
							$values = array($db->Quote($i), $db->Quote($type_id), $db->Quote($i));

							// Prepare the insert query.
							$query
							->insert($db->quoteName('#__flexicontent_fields_type_relations'))
							->columns($db->quoteName($columns))
							->values(implode(',', $values));

							// Set the query using our newly populated query object and execute it.

							$db->setQuery($query);
							$result = $db->execute();
							$db->transactionCommit();
						}
						catch (Exception $e)
						{
							// catch any database errors.
							$db->transactionRollback();
							self::deleteType($name, $alias);
							return "failed" ;
						}
					}
				}

				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id', 'name', 'type')))
				->from($db->quoteName('#__k2_extra_fields'))
				->where($db->quoteName('group') . ' = '. $db->Quote($groupid));
				$db->setQuery($query);
				$sql =  $db->loadAssocList();
				$ordering = 0;

				foreach($sql as $row)
				{
					$query = $db->getQuery(true);
					$query
						->select($db->quoteName(array('ordering')))
						->from($db->quoteName('#__flexicontent_fields_type_relations'))
						->where($db->quoteName('type_id') . ' = '. $db->Quote($type_id));
					$db->setQuery($query);
					$ordering =  $db->loadColumn();
					$ordering = max($ordering) + 1;
					$namek2 = $row['name'];
					$field_type = $row['type'];

					if($field_type == 'header' || $field_type == 'labels')
					{
					}
					else
					{
						$query = $db->getQuery(true);
						$query
						->select($db->quoteName(array('id')))
						->from($db->quoteName('#__flexicontent_fields'))
						->where($db->quoteName('label') . ' = '. $db->Quote($namek2));
						$db->setQuery($query);
						$field_id =  $db->loadResult();

						try
						{
							$db->transactionStart();
							$query = $db->getQuery(true);

							// Insert columns.
							$columns = array('field_id', 'type_id', 'ordering');

							// Insert values.
							$values = array($db->Quote($field_id), $db->Quote($type_id), $db->Quote($ordering));

							// Prepare the insert query.
							$query
							->insert($db->quoteName('#__flexicontent_fields_type_relations'))
							->columns($db->quoteName($columns))
							->values(implode(',', $values));
							$db->setQuery($query);
							$result = $db->execute();
							$db->transactionCommit();
						}
						catch(Exception $e)
						{
							// catch any database errors.
							$db->transactionRollback();
							self::deleteType($name, $alias);
							return "failed" ;
						}
					}
				}

				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id')))
				->from($db->quoteName('#__flexicontent_fields'))
				->where($db->quoteName('name') . ' = '. $db->Quote('k2attachment') . ' AND ' . $db->quoteName('label') . ' = '.  $db->quote('k2attachment'));
				$db->setQuery($query);
				$k2attachment =  $db->loadResult();

				try
				{
					$db->transactionStart();
					$query = $db->getQuery(true);

					// Insert columns.
					$columns = array('field_id', 'type_id', 'ordering');

					// Insert values.
					$values = array($db->Quote($k2attachment), $db->Quote($type_id), $db->Quote($ordering + 1));

					// Prepare the insert query.
					$query
					->insert($db->quoteName('#__flexicontent_fields_type_relations'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));
					$db->setQuery($query);
					$result = $db->execute();
					$db->transactionCommit();
					}
					catch(Exception $e)
					{
						// catch any database errors.
						$db->transactionRollback();
					}
				return "success";
				}
		else {
			return "exist" ;
		}
    }
	
	
	public function getFlexiId()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__assets'))
		->where($db->quoteName('name') . ' = ' . '"com_flexicontent"' . ' AND ' . $db->quoteName('level') . ' = 1');
		$db->setQuery($query);
		$id =  $db->loadResult();
		return $id;
	}

	public function addAsset($id, $parent_id, $type, $title, $alias, $ressaie)
	{
		$class   = new InsertItemHelper();
		$methods = $class->addAsset($id, $parent_id, $type, $title, $alias, $ressaie);
		return $methods;
	}

    
	public function deleteType($name, $alias)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__flexicontent_types'))
		->where($db->quoteName('name') . ' = '. $db->Quote($name) . ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_types'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$name       = 'com_flexicontent.type.'.$id ;
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('type_id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_fields_type_relations'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('name') . ' = '. $db->Quote($name)
		);
		$query->delete($db->quoteName('#__assets'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
	}


}