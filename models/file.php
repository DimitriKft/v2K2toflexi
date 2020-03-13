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
class K2toflexiModelFile extends JModelList
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
                ->from($db->quoteName('#__k2_attachments'));
		return $query;
	}

	public function file($task, $sql)
	{
		$class   = new InsertFileHelper();
		$methods = $class->file($task, $sql);
		return $methods;
	}

	public function recoveredFiles($sql)
	{
		$class   = new InsertFileHelper();
		$methods = $class->recoveredFiles($sql);
		return $methods;
	}

	public function insertFiles($row)
	{
		$class   = new InsertFileHelper();
		$methods = $class->insertFiles($row);
		return $methods;
	}

	public function addFile($id, $itemID, $filename, $title, $titleAttribute, $hits, $user, $datetime)
	{
		$class   = new InsertFileHelper();
		$methods = $class->addFile($id, $itemID, $filename, $title, $titleAttribute, $hits, $user, $datetime);
		return $methods;
	}

	public function deleteFile($filename, $title)
	{
		$class   = new InsertFileHelper();
		$methods = $class->deleteFile($filename, $title);
		return $methods;
	}
}