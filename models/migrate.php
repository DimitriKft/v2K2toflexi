<?php
use Joomla\Session\Storage\None;

/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	k2toflexi
 * @copyright	(C) 2017 Com'3Elles. All right reserved
 * @license GNU/GPL v2
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem');
jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');

/**
 * migration Component model
 *
 * @package		Joomla
 * @subpackage	k2toflexi
 * @since		1.0
 */

class K2toflexiModelMigrate extends JModelLegacy {



	//initialise variables.
	public function migration($task, $sql)
	{
		$class   = new migrateHelper();
		$methods = $class->migration($task, $sql);
		return $methods;
	}

	

	// Image migration
	public function recoveredImgCat($sql)
	{
		$class   = new InsertImghelper();
		$methods = $class->recoveredImgCat($sql);
		return $methods;
	}

	public function recoveredImgItem($sql)
	{
		$class   = new InsertImghelper();
		$methods = $class->recoveredImgItem($sql);
		return $methods;
	}

	public function insertImgCat($row)
	{
		$class   = new InsertImghelper();
		$methods = $class->insertImgCat($row);
		return $methods;	
	}

	public function insertImgItem($row)
	{
		$class   = new InsertImghelper();
		$methods = $class->insertImgItem($row);
		return $methods;
	}



	// Tag migration
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



   // Field migration
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



	// Type migration
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



	// Categories migration
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



	// Item migration
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

	

	// file migration
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
