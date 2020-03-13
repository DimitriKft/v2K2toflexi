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
class K2toflexiModelTag extends JModelList
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
                ->from($db->quoteName('#__k2toflexi_tags'));
		return $query;
	}

	public function tag($task, $sql)
	{
		$class   = new InsertTagHelper();
		$methods = $class->tag($task, $sql);
		return $methods;
	}
	
	public function recoveredTags($sql) 
	{
		$class   = new InsertTagHelper();
		$methods = $class->recoveredTags($sql);
		return $methods;
    }
	
	public function insertTags($row)
	{
		$class   = new InsertTagHelper();
		$methods = $class->insertTags($row);
		return $methods;
	}

	public function addTag($record_ID, $name, $alias, $published, $checked_out, $checked_out_time)
	{
		$class   = new InsertTagHelper();
		$methods = $class->addTag($record_ID, $name, $alias, $published, $checked_out, $checked_out_time);
		return $methods;
	}

	public function deleteTag($name)
	{
		$class   = new InsertTagHelper();
		$methods = $class->deleteTag($name);
		return $methods;
	}	
}