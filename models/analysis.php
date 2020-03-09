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

jimport('joomla.filesystem');
jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');

/**
 * HelloWorld Model
 *
 * @since  0.0.1
 */
class K2toflexiModelAnalysis extends JModelList
{

  
	//initialise variables.
	public function analyze($task, $sql)
	{
		$class   = new AnalysisHelper();
		$methods = $class->analyze($task, $sql);
		return $methods;
	}
	
		

   	// Image analysis
	public function recoveredImgCat($sql)
	{
		$class   = new ExtractImgHelper();
		$methods = $class->recoveredImgCat($sql);
		return $methods;
    }
    
	public function recoveredImgItem($sql)
	{
		$class   = new ExtractImgHelper();
		$methods = $class->recoveredImgItem($sql);
		return $methods;
	}

	public function insertImgCat($row)
	{
		$class   = new ExtractImgHelper();
		$methods = $class->insertImgCat($row);
		return $methods;	
	}

	public function insertImgItem($row)
	{
		$class   = new ExtractImgHelper();
		$methods = $class->insertImgItem($row);
		return $methods;
    }



    // file analysis
	public function recoveredFiles($sql)
	{
		$class   = new ExtractFileHelper();
		$methods = $class->recoveredFiles($sql);
		return $methods;
	}

	public function insertFiles($row)
	{
		$class   = new ExtractFileHelper();
		$methods = $class->insertFiles($row);
		return $methods;
	}

	public function addFile($id, $itemID, $filename, $title, $titleAttribute, $hits, $user, $datetime)
	{
		$class   = new ExtractFileHelper();
		$methods = $class->addFile($id, $itemID, $filename, $title, $titleAttribute, $hits, $user, $datetime);
		return $methods;
	}

	public function deleteFile($filename, $title)
	{
		$class   = new ExtractFileHelper();
		$methods = $class->deleteFile($filename, $title);
		return $methods;
	}


    
    // Tag analysis
	public function recoveredTags($sql) 
	{
		$class   = new ExtractTagHelper();
		$methods = $class->recoveredTags($sql);
		return $methods;
    }

	public function insertTags($row)
	{
		$class   = new ExtractTagHelper();
		$methods = $class->insertTags($row);
		return $methods;
	}

	public function addTag($record_ID, $name, $alias, $published, $checked_out, $checked_out_time)
	{
		$class   = new ExtractTagHelper();
		$methods = $class->addTag($record_ID, $name, $alias, $published, $checked_out, $checked_out_time);
		return $methods;
	}

	public function deleteTag($name)
	{
		$class   = new ExtractTagHelper();
		$methods = $class->deleteTag($name);
		return $methods;
	}


   // Field analysis
	public function recoveredFields($sql) 
	{
		$class   = new ExtractFieldHelper();
		$methods = $class->recoveredFields($sql);
		return $methods;
    }
    
    
	public function insertFields($row)
	{
		$class   = new ExtractFieldHelper();
		$methods = $class->insertFields($row);
		return $methods;
	}

	public function addField($record_ID, $name, $value, $type, $group, $published, $ordering, $dateTime)
	{
		$class   = new ExtractFieldHelper();
		$methods = $class->addField($record_ID, $name, $value, $type, $group, $published, $ordering, $dateTime);
		return $methods;
	}

	public function deleteField($name, $label)
	{
		$class   = new ExtractFieldHelper();
		$methods = $class->deleteField($name, $label);
		return $methods;
	}



	// Type analysis
	public function recoveredType($sql)
	{
		$class   = new ExtractTypeHelper();
		$methods = $class->recoveredType($sql);
		return $methods;
	}

	public function insertType($row)
	{
		$class   = new ExtractTypeHelper();
		$methods = $class->insertType($row);
		return $methods;
	}

	public function addType($groupid, $name, $alias, $published, $itemscreatable, $checked_out, $checked_out_time, $access, $attribs)
	{
		$class   = new ExtractTypeHelper();
		$methods = $class->addType($groupid, $name, $alias, $published, $itemscreatable, $checked_out, $checked_out_time, $access, $attribs);
		return $methods;
	}


		// Categories analysis
		public function recoveredCategory($sql)
		{
			$class   = new ExtractCatHelper();
			$methods = $class->recoveredCategory($sql);
			return $methods;
		}
	
		public function insertCategory($row)
		{
			$class   = new ExtractCatHelper();
			$methods = $class->insertCategory($row);
			return $methods;
		}
	
		public function addCategory($id, $record_ID, $parent_id, $title, $alias, $description, $published, $user_id, $dateTime, $access, $catMetaDesc, $catMetaKey, $catMetaData, $language, $image = '', $hits = 0, $version = 1)
		{
			$class   = new ExtractCatHelper();
			$methods = $class->addCategory($id, $record_ID, $parent_id, $title, $alias, $description, $published, $user_id, $dateTime, $access, $catMetaDesc, $catMetaKey, $catMetaData, $language, $image = '', $hits = 0, $version = 1);
			return $methods;
		}	
	
		public function deleteCategory($title, $alias)
		{
			$class   = new ExtractCatHelper();
			$methods = $class->deleteCategory($title, $alias);
			return $methods;
		}



			// Item analysis
	public function recoveredItem($sql)
	{
		$class   = new ExtractItemHelper();
		$methods = $class->recoveredItem($sql);
		return $methods;
	}

	public function insertItem($row)
	{
		$class   = new ExtractItemHelper();
		$methods = $class->insertItem($row);
		return $methods;
	}

	public function addItem($id, $catid, $catidk2, $dateTime, $title, $alias, $introtext, $language, $featured, $published, $fulltext,
					$video, $gallery, $extra_fields_search, $created, $created_by, $created_by_alias, $checked_out, $checked_out_time, $modified,
					$modified_by, $publish_up, $publish_down, $trash, $access, $empty, $featured_ordering, $image_caption,
					$image_credits, $video_caption, $video_credits, $hits, $params, $metadesc, $metadata, $metakey, $extra_fields)
	{
		$class   = new ExtractItemHelper();
		$methods = $class->addItem($id, $catid, $catidk2, $dateTime, $title, $alias, $introtext, $language, $featured, $published, $fulltext,
		$video, $gallery, $extra_fields_search, $created, $created_by, $created_by_alias, $checked_out, $checked_out_time, $modified,
		$modified_by, $publish_up, $publish_down, $trash, $access, $empty, $featured_ordering, $image_caption,
		$image_credits, $video_caption, $video_credits, $hits, $params, $metadesc, $metadata, $metakey, $extra_fields);
		return $methods;
	}

	public function deleteItem($title, $alias)
	{
		$class   = new ExtractItemHelper();
		$methods = $class->deleteItem($title, $alias);
		return $methods;
	}

}