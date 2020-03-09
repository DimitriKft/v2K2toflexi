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
class ExtractItemHelper
{
	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */


    public function recoveredItem($sql)
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('title', 'alias', 'catid', 'published', 'introtext', 'fulltext', 'video',
					'gallery', 'extra_fields', 'extra_fields_search', 'created', 'created_by', 'created_by_alias', 'checked_out',
					'checked_out_time', 'modified', 'modified_by', 'publish_up', 'publish_down', 'trash', 'access', 'ordering',
					'featured', 'featured_ordering', 'image_caption', 'image_credits', 'video_caption', 'video_credits', 'hits',
					'params', 'metadesc', 'metadata', 'metakey', 'plugins', 'language')))
			->from($db->quoteName('#__k2_items'));
			$db->setQuery($query);
			$sql  =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertItem($row);
			$title        = $row['title'];
			$alias        = $row['alias'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteItem($title, $alias);
				$message = self::insertItem($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteItem($title, $alias);
				$message = self::insertItem($row);
			}
			unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => false, 'sql' => '', 'message' => $message, 'type' => 'Item', 'name' => $title), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else{
				$valuesjsons = json_encode(array('task' => 'insertItem', 'sql' => $sql, 'message' => $message, 'type' => 'Item', 'name' => $title), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => false, 'sql' => '', 'message' => 'noexist', 'type' => 'Item', 'name' => "???"), JSON_NUMERIC_CHECK);
		return $valuesjsons;
		die;
    }
    


    public function insertItem($row)
	{
		$db                  = JFactory::getDBO();
		$user                = JFactory::getUser();
		$dateTime            = date_create('now')->format('Y-m-d H:i:s');
		$title               = $row['title'];
		$alias               = $row['alias'];
		$catid               = $row['catid'];
		$published           = $row['published'];
		$introtext           = $row['introtext'];
		$fulltext            = $row['fulltext'];
		$video               = $row['video'];
		$gallery             = $row['gallery'];
		$extra_fields        = $row['extra_fields'];
		$extra_fields_search = $row['extra_fields_search'];
		$created             = $row['created'];
		$created_by          = $row['created_by'];
		$created_by_alias    = $row['created_by_alias'];
		$checked_out         = $row['checked_out'];
		$checked_out_time    = $row['checked_out_time'];
		$modified            = $row['modified'];
		$modified_by         = $row['modified_by'];
		$publish_up          = $row['publish_up'];
		$publish_down        = $row['publish_down'];
		$trash               = $row['trash'];
        $access              = $row['access'];
        $ordering            = $row['ordering'];
		$featured            = $row['featured'];
		$featured_ordering   = $row['featured_ordering'];
		$image_caption       = $row['image_caption'];
		$image_credits       = $row['image_credits'];
		$video_caption       = $row['video_caption'];
		$video_credits       = $row['video_credits'];
		$hits                = $row['hits'];
		$params              = $row['params'];
		$metadesc            = $row['metadesc'];
		$metadata            = $row['metadata'];
		$metakey             = $row['metakey'];
		$plugins             = $row['plugins'];
		$language            = $row['language'];

	

        $message = self::addItem(0, $db->Quote($title),$db->Quote($alias),$db->Quote($catid), $published, $db->Quote($introtext) ,$db->Quote($fulltext), $db->Quote($video), 
        $db->Quote($gallery), $db->Quote($extra_fields), $db->Quote($extra_fields_search), $db->Quote($created), $db->Quote($created_by), $db->Quote($created_by_alias), $db->Quote($checked_out),
        $db->Quote($checked_out_time), $db->Quote($modified),$db->Quote($modified_by), $db->Quote($publish_up),$db->Quote($publish_down), $db->Quote($trash), $db->Quote($access),
        $db->Quote($ordering), $db->Quote($featured), $db->Quote($featured_ordering), $db->Quote($image_caption),  $db->Quote($image_credits), $db->Quote($video_caption), $db->Quote($video_credits),
        $db->Quote($hits), $db->Quote($params), $db->Quote($metadesc), $db->Quote($metadata), $db->Quote($metakey), $db->Quote($plugins), $db->Quote($language), $user->id, $db->Quote($dateTime));
		return $message;
    }
    

    public function addItem($record_ID, $title, $alias, $catid, $published, $introtext, $fulltext, $video, $gallery, $extra_fields, $extra_fields_search, $created, $created_by, $created_by_alias,
    $checked_out, $checked_out_time, $modified, $modified_by, $publish_up, $publish_down, $trash, $access, $ordering, $featured, $featured_ordering, $image_caption, $image_credits, $video_caption,
    $video_credits, $hits, $params, $metadesc, $metadata, $metakey, $plugins, $language)
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
		->select($db->quoteName(array('title')))
		->from($db->quoteName('#__k2toflexi_items'))
		->where($db->quoteName('title') . ' = '. $title);
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
				$columns = array('title', 'alias', 'catid', 'published', 'introtext', 'fulltext', 'video',
                'gallery', 'extra_fields', 'extra_fields_search', 'created', 'created_by', 'created_by_alias', 'checked_out',
                'checked_out_time', 'modified', 'modified_by', 'publish_up', 'publish_down', 'trash', 'access', 'ordering',
                'featured', 'featured_ordering', 'image_caption', 'image_credits', 'video_caption', 'video_credits', 'hits',
                'params', 'metadesc', 'metadata', 'metakey', 'plugins', 'language');
				
				// Insert values.
				$values = array($title, $alias, $catid, $published, $introtext, $fulltext, $video, $gallery, $extra_fields, $extra_fields_search, $created, $created_by, $created_by_alias,
                $checked_out, $checked_out_time, $modified, $modified_by, $publish_up, $publish_down, $trash, $access, $ordering, $featured, $featured_ordering, $image_caption, $image_credits, $video_caption,
                $video_credits, $hits, $params, $metadesc, $metadata, $metakey, $plugins, $language);
				$query
				->insert($db->quoteName('#__k2toflexi_items'))
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
				self::deleteItem($title);
				return "failed" ;// catch any database errors.
				$db->transactionRollback();
				self::deleteItem($title);
				return "failed" ;
			}
			return "success" ;
		}
		else{
			return "exist" ;
		}
    }
    

    public function deleteItem($title)
	{
        $db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__k2toflexi_items'))
		->where($db->quoteName('title') . ' = '. $db->Quote($title));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__k2toflexi_items'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
	}
	}	
