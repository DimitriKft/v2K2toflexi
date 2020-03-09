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
class InsertImgHelper
{
	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */



	public function recoveredImgCat($sql)
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'k2')) 
		{
			if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'k2'.DS.'categories'))
			{
			}
			if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'k2'.DS.'items')) 
			{
			}
		}
		if ($sql == '')
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id', 'name', 'alias', 'description', 'parent', 'extraFieldsGroup', 'published', 'access', 'ordering', 'image', 'params', 'trash', 'plugins', 'language')))
			->from($db->quoteName('#__k2_categories'));
			$db->setQuery($query);
			$sql   =  $db->loadAssocList();
			
			
		}
		foreach($sql as $elem =>$row)
		{
			$message  = self::insertImgCat($row);
			$catalias = $row['alias']; 
			unset($sql[$elem]); 
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertImg2', 'sql' => '', 'message' => $message, 'type' => 'ImageCategorie', 'name' => $catalias.'.jpg'), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertImg', 'sql' => $sql, 'message' => $message, 'type' => 'ImageCategorie', 'name' => $catalias.'.jpg'), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		return $valuesjsons;
	}


	public function recoveredImgItem($sql)
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'k2'))
		{
			if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'k2'.DS.'categories'))
			{
			}
			if(JFolder::create(JPATH_ROOT.DS.'images'.DS.'k2'.DS.'items'))
			{
			}
		}
		if($sql == '')
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array( 'id', 'title', 'alias', 'catid', 'published', 'introtext', 'fulltext', 'video',
					'gallery', 'extra_fields', 'extra_fields_search', 'created', 'created_by', 'created_by_alias', 'checked_out',
					'checked_out_time', 'modified', 'modified_by', 'publish_up', 'publish_down', 'trash', 'access', 'ordering',
					'featured', 'featured_ordering', 'image_caption', 'image_credits', 'video_caption', 'video_credits', 'hits',
					'params', 'metadesc', 'metadata', 'metakey', 'plugins', 'language')))
			->from($db->quoteName('#__k2_items'));
			$db->setQuery($query);
			$sql   =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message   = self::insertImgItem($row);
			$itemalias = $row['alias'];
			unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertFiles', 'sql' => '', 'message' => $message, 'type' => 'ImageItem', 'name' => $itemalias.'.jpg'), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertImg2', 'sql' => $sql, 'message' => $message, 'type' => 'ImageItem', 'name' => $itemalias.'.jpg'), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		return $valuesjsons;
	}


    public function insertImgCat($row)
	{
		$db          = JFactory::getDBO();
		$user        = JFactory::getUser();
		$catid       = $row['id'];
		$catalias    = $row['alias'];
		$sourceImage = JPATH_ROOT.'/media/k2/categories/'.$catid.'.jpg';
		if(JFile::exists($sourceImage))
		{
			JFile::copy($sourceImage, JPATH_ROOT.'/images/k2/categories/'.$catalias.'.jpg');
			return 'success';
		}
		else
		{
			return "exist";
		}
	}


	public function insertImgItem($row)
	{
		$db          = JFactory::getDBO();
		$user        = JFactory::getUser();
		$itemid      = $row['id'];
		$itemalias   = $row['alias'];
		$md5         = md5("Image".$itemid);
		$sourceImage = JPATH_ROOT.'/media/k2/items/src/'.$md5.'.jpg';
		if(JFile::exists($sourceImage))
		{
			JFile::copy($sourceImage, JPATH_ROOT.'/images/k2/items/'.$itemalias.'.jpg');
			return 'success';
		}
		else
		{
			return "exist";
		}
	}

	
}