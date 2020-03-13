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
class K2toflexiModelType extends JModelList
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
                ->from($db->quoteName('#__k2toflexi_extra_fields_groups'));
		return $query;
	}

	public function type($task,$sql)
	{
		$class   = new InsertTypeHelper();
		$methods = $class->type($task, $sql);
		return $methods;
	}

	public function recoveredType($sql)
	{
		$class   = new InsertTypeHelper();
		$methods = $class->recoveredType($sql);
		return $methods;
	}

	public function insertType($row)
	{
		$class   = new InsertTypeHelper();
		$methods = $class->insertType($row);
		return $methods;
	}

	public function addType($groupid, $name, $alias, $published, $itemscreatable, $checked_out, $checked_out_time, $access, $attribs)
	{
		$class   = new InsertTypeHelper();
		$methods = $class->addType($groupid, $name, $alias, $published, $itemscreatable, $checked_out, $checked_out_time, $access, $attribs);
		return $methods;
	}
	
	public function getFlexiId()
	{
		$class   = new InsertTypeHelper();
		$methods = $class->getFlexiId();
		return $methods;
	}

	public function deleteType($name, $alias)
	{
		$class   = new InsertTypeHelper();
		$methods = $class->deleteType($name, $alias);
		return $methods;
	}
}