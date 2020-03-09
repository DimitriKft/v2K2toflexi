<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	k2toflexi
 * @copyright	(C) 2017 Com'3Elles. All right reserved
 * @license GNU/GPL v2
 */

// no direct access
defined( '_JEXEC' ) or die;



/**
 * Migrate display helper
 *
 * @package		Joomla
 * @subpackage	k2toflexi
 * @since		1.0
 */
class migrateHelper{
	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */
	public static function getActions(){
		$user 	= JFactory::getUser();
		$result	= new JObject;
		
		$actions = array(
				'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);
		foreach ($actions as $action){
			$result->set($action, $user->authorise($action, 'com.k2toflexi'));
		}
		
		return $result;
	}


	public function migration($task, $sql)
	{
		//initialise variables.
		$db                         = JFactory::getDBO();
		$user                       = JFactory::getUser();
		$dateTime                   = date_create('now')->format('Y-m-d H:i:s');
		$sql                        = json_decode(json_encode($sql),    true);
		$params                     = JComponentHelper::getParams('com_k2toflexi');
		$recoveredImgCat            = $params['insertImg'];
		$recoveredImgItem           = $params['insertImg2'];
		$recoveredTags              = $params['insertTags'];
		$recoveredFiles             = $params['insertFiles'];
		$recoveredFields            = $params['insertFields'];
		$recoveredType              = $params['insertType'];
		$recoveredCategory          = $params['insertCategory'];
		$recoveredItem              = $params['insertItem'];
		$debug                      = $params['debug'];
		$rotatDate                  = JFactory::getDate()->format('Y-m');

		Jlog::addLogger ( 
			array(
				'logger'   => 'database',
				'db_table' => '#__log_k2toflexi',
				),
				JLog::INFO
			);
		Jlog::addLogger ( 
			array(
				'text_file'         => 'k2toflexi_'.$rotatDate.'.log.php',
				'text_entry_format' => '{DATE} {TIME} {CLIENTIP} {CATEGORY} {MESSAGE}'
				),
				JLog::ERROR
			);

		 if($task == 'insertImg'      && $recoveredImgCat      == 2) 
		 {
			$task =      'insertImg2';
		 }
		 if($task == 'insertImg2'     && $recoveredImgItem     == 2)
		 {
			$task =      'insertFiles';
		 }
		 if($task == 'insertFiles'    && $recoveredFiles    == 2)
		 {
			$task =      'insertTags';
		 }
		 if($task == 'insertTags'     && $recoveredTags     == 2)
		 {
			$task =      'insertFields';
		 }
		 if($task == 'insertFields'   && $recoveredFields   == 2)
		 {
			$task =      'insertType';
		 }
		 if($task == 'insertType'     && $recoveredType     == 2)
		 {
			$task =      'insertCategory';
		 }
		 if($task == 'insertCategory' && $recoveredCategory == 2)
		 {
			$task =      'insertItem';
		 }
		 if($task == 'insertItem'     && $recoveredItem     == 2)
		 {
			$task =      false;
		 }

		if($task == 'insertImg')
		{
			$valuesjsons = $this->recoveredImgCat($sql);
			return $valuesjsons;
            $logEntry    = new JlogEntry("Insert img1", JLog::INFO, $srvdate , 'img1');
            Jlog::add($logEntry);
			$logEntry2   = new JlogEntry("Insert img1", JLog::ERROR, $srvdate , 'img1');		
            Jlog::add($logEntry2);
			die();
		}
		else if($task == 'insertImg2')
		{
			$valuesjsons = $this->recoveredImgItem($sql);
			return $valuesjsons;
			if($debug == 1)
			{
            	$logEntry = new JlogEntry("Insert img2", JLog::INFO, $srvdate , 'img2');
            	Jlog::add($logEntry);
				$logEntry = new JlogEntry("Insert img2", JLog::ERROR, $srvdate , 'img2');
            	Jlog::add($logEntry);
			}
			die();
		}
		else if($task == 'insertFiles')
		{
			if($sql == '')
			{
				$attribs = '{"use_ingroup":"0","allow_multiple":"1","fields_box_placing":"1","add_position":"3","max_values":"0","required":"0","show_values_expand_btn":"1","formlayout":"InlineBoxes","form_file_preview":"0","inputmode":"1","iform_title":"1","iform_desc":"1","iform_lang":"0","iform_access":"0","iform_dir":"0","iform_stamp":"0","iform_title_default":"","iform_desc_default":"","iform_lang_default":"*","iform_access_default":"1","iform_dir_default":"1","iform_stamp_default":"1","use_myfiles":"1","autoassign":"1","target_dir":"1","filelist_cols":["upload_time","hits","__SAVED__"],"resize_on_upload":"","upload_max_w":"","upload_max_h":"","upload_method":"","display_label":"1","show_acc_msg":"0","no_acc_msg":"","include_in_csv_export":"0","noaccess_display":"1","noaccess_addvars":"0","noaccess_url_unlogged":"","noaccess_msg_unlogged":"","noaccess_url_logged":"","noaccess_msg_logged":"","usebutton":"1","buttonsposition":"1","use_action_separator":"0","action_separator":"","allowdownloads":"1","downloadstext":"FLEXI_DOWNLOAD","allowview":"0","viewtext":"","viewinside":"1","stamp_pdfs":"0","pdf_header_text":"","pdf_footer_text":"","pdf_header_ffamily":"Helvetica","pdf_header_fstyle":"","pdf_header_fsize":"11","pdf_header_align":"C","pdf_header_border_type":"0","pdf_footer_ffamily":"Helvetica","pdf_footer_fstyle":"","pdf_footer_fsize":"11","pdf_footer_align":"C","pdf_footer_border_type":"0","stamp_date_format":"DATE_FORMAT_LC2","stamp_custom_date":"","stamp_lang_filter_format":"0","stamp_display_tz_suffix":"1","stamp_display_tz_logged":"0","stamp_display_tz_guests":"0","use_downloads_manager":"0","addtocarttext":"","allowshare":"0","sharetext":"","enable_coupons":"0","coupon_hits_limit":"3","coupon_expiration_days":"15","viewlayout":"InlineBoxes","display_total_count":"0","total_count_label":"FLEXI_FIELD_FILE_TOTAL_FILES","display_total_hits":"0","total_hits_label":"FLEXI_FIELD_FILE_TOTAL_DOWNLOADS","useicon":"0","display_filename":"1","lowercase_filename":"1","link_filename":"1","display_lang":"1","display_size":"0","display_hits":"0","display_descr":"1","use_info_separator":"0","info_separator":"","prx_sfx_open_close_configs":"","remove_space":"0","pretext":"","posttext":"","separatorf":"1","opentag":"","closetag":"","trigger_onprepare_content":"0","trigger_plgs_incatview":"0","send_notifications":"0","notifications_step":"20","notification_tmpl":"%%FLEXI_HITS%% __FILE_HITS__ \r\n %%FLEXI_FDN_FILE_NO%% __FILE_ID__:  [__FILE_TITLE__] \r\n %%FLEXI_FDN_FILE_IN_ITEM%% __ITEM_TITLE__: \r\n __ITEM_URL__","send_all_to_email":"","send_to_current_item_owner":"0","send_to_email_field":"0","display_filter_as_s":"1"}';
				$attribs = json_decode(json_encode($attribs), true);
				$file    = self::addField(0, 'file', 'k2attachment', 'k2attachment', '', '', 1, $attribs, $user->id, $dateTime);
			}
			$valuesjsons = $this->recoveredFiles($sql);
			return $valuesjsons;
			die();
		}
		else if($task == 'insertTags')
		{
			$valuesjsons = $this->recoveredTags($sql);
			return $valuesjsons;
			if($debug == 1)
			{
            	$logEntry = new JlogEntry("Insert tag ", JLog::INFO, $srvdate , 'tag');
            	Jlog::add($logEntry);
				$logEntry2 = new JlogEntry("Insert tag ", JLog::ERROR, $srvdate , 'tag');
				Jlog::add($logEntry2);
			}
			die();
		}
		else if($task == 'insertFields')
		{
			$valuesjsons = $this->recoveredFields($sql);
			return $valuesjsons;
			if($debug == 1)
			{
            	$logEntry = new JlogEntry("Insert field ", Jlog::INFO, $srvdate , 'field');
				$logEntry = new JlogEntry("Insert field ", Jlog::ERROR, $srvdate , 'field');
            	Jlog::add($logEntry);
			}
			die();
		}
		else if($task == 'insertType')
		{
			$valuesjsons = $this->recoveredType($sql);
			return $valuesjsons;
			if($debug == 1)
			{
				$logEntry = new JlogEntry("Insert type ", Jlog::INFO, $srvdate , 'type');
				Jlog::add($logEntry);
				$logEntry = new JlogEntry("Insert type ", Jlog::ERROR, $srvdate , 'type');
				Jlog::add($logEntry);
			}
			die();
		}
		else if($task == 'insertCategory')
		{
			$valuesjsons = $this->recoveredCategory($sql);
			return $valuesjsons;
			if($debug == 1)
			{
            	$logEntry = new JlogEntry("Insert cat ", Jlog::INFO, $srvdate , 'categorie');
            	Jlog::add($logEntry);
				$logEntry = new JlogEntry("Insert cat ", Jlog::ERROR, $srvdate , 'categorie');
				Jlog::add($logEntry);
			}
			die();
		}
		else if($task == 'placeCategory')
		{
			$valuesjsons = $this->placeCategory($sql);
			return $valuesjsons;
			if($debug == 1)
			{
            	$logEntry = new JlogEntry("Place cat ", Jlog::INFO, $srvdate , 'categorie');
            	Jlog::add($logEntry);
				$logEntry = new JlogEntry("Insert cat ", Jlog::ERROR, $srvdate , 'categorie');
				Jlog::add($logEntry);
			}
			die();
		}
		else if($task == 'insertItem')
		{
			$valuesjsons = $this->recoveredItem($sql);
			return $valuesjsons;
			if($debug == 1)
			{
            	$logEntry = new JlogEntry("Insert item ", Jlog::INFO, $srvdate , 'item');
            	Jlog::add($logEntry);
			    $logEntry = new JlogEntry("Insert item ", Jlog::ERROR, $srvdate , 'item');
			    Jlog::add($logEntry);
			}
			die();
		}
		else
		{
			return (json_encode(array('task' => false, 'sql' => '', 'message' => '', 'type' => '', 'name' => '')));
			die;
		}
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