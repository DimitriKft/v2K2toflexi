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
class K2toflexiModelCategory extends JModelList
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
                ->from($db->quoteName('#__k2toflexi_categories'));
		return $query;
	}


	
	// Categories migration


	public function category($task, $sql)
	{
		$class   = new InsertCatHelper();
		$methods = $class->category($task, $sql);
		return $methods;
	}

	public function recoveredCategory($sql)
	{
		$class   = new InsertCatHelper();
		$methods = $class->recoveredCategory($sql);
		return $methods;
	}

	public function insertCategory($row)
	{
		$class   = new InsertCatHelper();
		$methods = $class->insertCategory($row);
		return $methods;
	}

	public function placeCategory($sql)
	{
		$class   = new InsertCatHelper();
		$methods = $class->placeCategory($sql);
		return $methods;
	}

	public function placeCategory2($row)
	{
		$class   = new InsertCatHelper();
		$methods = $class->placeCategory2($row);
		return $methods;
	}

	public function addCategory($id, $record_ID, $parent_id, $title, $alias, $description, $published, $user_id, $dateTime, $access, $catMetaDesc, $catMetaKey, $catMetaData, $language, $image = '', $hits = 0, $version = 1)
	{
		$class   = new InsertCatHelper();
		$methods = $class->addCategory($id, $record_ID, $parent_id, $title, $alias, $description, $published, $user_id, $dateTime, $access, $catMetaDesc, $catMetaKey, $catMetaData, $language, $image = '', $hits = 0, $version = 1);
		return $methods;
	}

	public function getExtension($asset_parent_id, $asset_level)
	{
		$class   = new InsertCatHelper();
		$methods = $class->getExtension($asset_parent_id, $asset_level);
		return $methods;
	}

	public function deleteCategory($title, $alias)
	{
		$class   = new InsertCatHelper();
		$methods = $class->deleteCategory($title, $alias);
		return $methods;
	}
}