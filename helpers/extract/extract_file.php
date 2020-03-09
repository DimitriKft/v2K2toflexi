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
			->select($db->quoteName(array('id', 'itemID', 'filename', 'title', 'titleAttribute', 'hits')))
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
		$id             = $row['id'];
		$itemID         = $row['itemID'];
		$filename       = $row['filename'];
		$title          = $row['title'];
		$titleAttribute = $row['titleAttribute'];
		$hits           = $row['hits'];
		$message        = self::addFile($id, $itemID, $filename, $title, $titleAttribute, $hits, $user->id, $dateTime);
		return $message ;
    }
    

    
	public function addFile($id, $itemID, $filename, $title, $titleAttribute, $hits, $user, $datetime)
	{
		// record_ID is the id of the item that you want to load, or set it to zero for new item

		// **************************************
		// Include the needed classes and helpers
		// **************************************

		if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);//TODO a vérifier

		// ***
		// *** Create the item model object
		// ***

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('filename')))
		->from($db->quoteName('#__flexicontent_files'))
		->where($db->quoteName('filename') . ' = '. $db->Quote($filename). ' AND ' . $db->quoteName('altname') . ' = '.  $db->quote($title));
		$db->setQuery($query);
		$namelike =  $db->loadResult();

		if(!$namelike)
		{
			$ext = substr($filename, strrpos($filename, '.')+1);
			$sourceImage = JPATH_ROOT.'/media/k2/attachments/'.$filename;

			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);

				// Insert columns.
				$columns = array('filename', 'filename_original', 'altname', 'description', 'url', 'secure',
						'ext', 'published', 'language', 'hits', 'size', 'assignments', 'stamp', 'uploaded',
						'uploaded_by', 'checked_out', 'checked_out_time', 'access', 'attribs');

				// Insert values.
				$values = array($db->Quote($filename), $db->Quote($filename), $db->Quote($title), $db->Quote(''), 0, 1, $db->Quote($ext), 1, $db->Quote('*'),
						$db->Quote($hits), $db->Quote(0), $db->Quote(0), $db->Quote(1), $db->Quote($datetime),
						$db->Quote($user), $db->Quote(0), $db->Quote($datetime), $db->Quote(1), $db->Quote(''));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_files'))
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
				self::deleteTag($name);
				return "failed" ;
			}

			if(JFile::exists($sourceImage))
			{
				JFile::copy($sourceImage, JPATH_ROOT.'/components/com_flexicontent/uploads/'.$filename);
				return 'success';
			}
			else
			{
				return "failed";
			}
		}
		else
		{
			return "exist" ;
		}
    }
    

    public function deleteFile($filename, $title)
	{
		$db    = JFactory::getDBO();
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__flexicontent_files'))
		->where($db->quoteName('filename') . ' = '. $db->Quote($filename). ' AND ' . $db->quoteName('altname') . ' = '.  $db->quote($title));
		$db->setQuery($query);
		$id =  $db->loadResult();
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_files'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);

	}


     
}