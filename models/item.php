<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HelloWorld Model
 *
 * @since  0.0.1
 */
class K2toflexiModelItem extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
                ->from($db->quoteName('#__k2toflexi_items'));
		return $query;
	}

	
	// Item migration

	public function item($task, $sql)
	{
		$class   = new InsertItemHelper();
		$methods = $class->item($task, $sql);
		return $methods;
	}

	public function recoveredItem($sql)
	{
		$class   = new InsertItemHelper();
		$methods = $class->recoveredItem($sql);
		return $methods;
	}

	public function insertItem($row)
	{
		$class   = new InsertItemHelper();
		$methods = $class->insertItem($row);
		return $methods;
	}

	public function addItem($id, $catid, $catidk2, $dateTime, $title, $alias, $introtext, $language, $featured, $published, $fulltext,
					$video, $gallery, $extra_fields_search, $created, $created_by, $created_by_alias, $checked_out, $checked_out_time, $modified,
					$modified_by, $publish_up, $publish_down, $trash, $access, $empty, $featured_ordering, $image_caption,
					$image_credits, $video_caption, $video_credits, $hits, $params, $metadesc, $metadata, $metakey, $extra_fields)
	{
		$class   = new InsertItemHelper();
		$methods = $class->addItem($id, $catid, $catidk2, $dateTime, $title, $alias, $introtext, $language, $featured, $published, $fulltext,
		$video, $gallery, $extra_fields_search, $created, $created_by, $created_by_alias, $checked_out, $checked_out_time, $modified,
		$modified_by, $publish_up, $publish_down, $trash, $access, $empty, $featured_ordering, $image_caption,
		$image_credits, $video_caption, $video_credits, $hits, $params, $metadesc, $metadata, $metakey, $extra_fields);
		return $methods;
	}

	public function addItem2($item_id, $introtext, $fulltext, $created, $created_by, $modified, $modifiedby, $title, $hits, $type,
						     $version, $favourites, $categories, $item_id_k2, $extra_fields_search, $extra_fields,
							 $alias, $catid, $metadesc, $metakey, $metadata, $attribs, $urls, $images)
	{
	
		$class   = new InsertItemHelper();
		$methods = $class->addItem2($item_id, $introtext, $fulltext, $created, $created_by, $modified, $modifiedby, $title, $hits, $type,
		$version, $favourites, $categories, $item_id_k2, $extra_fields_search, $extra_fields,
		$alias, $catid, $metadesc, $metakey, $metadata, $attribs, $urls, $images);
		return $methods;
	}

	public function addAsset($id, $parent_id, $type, $title, $alias, $ressaie)
	{
		$class   = new InsertItemHelper();
		$methods = $class->addAsset($id, $parent_id, $type, $title, $alias, $ressaie);
		return $methods;
	}

	public function getLft($parent_id)
	{
		$class   = new InsertItemHelper();
		$methods = $class->getLft($parent_id);
		return $methods;
	}

	public function getRgt($parent_id)
	{
		$class   = new InsertItemHelper();
		$methods = $class->getRgt($parent_id);
		return $methods;
	}

	public function getLevel($parent_id)
	{
		$class   = new InsertItemHelper();
		$methods = $class->getLevel($parent_id);
		return $methods;
	}

	public function deleteItem($title, $alias)
	{
		$class   = new InsertItemHelper();
		$methods = $class->deleteItem($title, $alias);
		return $methods;
	}

}