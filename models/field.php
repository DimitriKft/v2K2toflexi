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
class K2toflexiModelField extends JModelList
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
                ->from($db->quoteName('#__k2toflexi_extra_fields'));
		return $query;
	}

	

   // Field migration
   public function field($task, $sql) 
   {
	   $class   = new InsertFieldHelper();
	   $methods = $class->field($task, $sql);
	   return $methods;
   }

   public function recoveredFields($sql) 
   {
	   $class   = new InsertFieldHelper();
	   $methods = $class->recoveredFields($sql);
	   return $methods;
   }

   public function insertFields($row)
   {
	   $class   = new InsertFieldHelper();
	   $methods = $class->insertFields($row);
	   return $methods;
   }

   public function fieldChange($type, $value)
   {
	   $class   = new InsertFieldHelper();
	   $methods = $class->fieldChange($type, $value);
	   return $methods;
   }

   public function addField($record_ID, $field_type, $name, $label, $description, $positions, $published, $attribs, $checked_out, $checked_out_time)
   {
	   $class   = new InsertFieldHelper();
	   $methods = $class->addField($record_ID, $field_type, $name, $label, $description, $positions, $published, $attribs, $checked_out, $checked_out_time);
	   return $methods;
   }

   public function deleteField($name, $label)
   {
	   $class   = new InsertFieldHelper();
	   $methods = $class->deleteField($name, $label);
	   return $methods;
   }

}