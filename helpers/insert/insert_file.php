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
class InsertFileHelper
{
	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */


	public function file($task, $sql)
	{
		//initialise variables.
		$db                         = JFactory::getDBO();
		$user                       = JFactory::getUser();
		$dateTime                   = date_create('now')->format('Y-m-d H:i:s');
		$sql                        = json_decode(json_encode($sql),    true);
		$params                     = JComponentHelper::getParams('com_k2toflexi');
		$recoveredFiles             = $params['insertFiles'];
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
		 if($task == 'insertImg2'     && $recoveredImgItem     == 2)
		 {
			$task =      'insertFiles';
		 }


		if($task == 'insertFiles')
		{
			if($sql == '')
			{
				$attribs = '{"use_ingroup":"0","allow_multiple":"1","fields_box_placing":"1","add_position":"3","max_values":"0","required":"0","show_values_expand_btn":"1","formlayout":"InlineBoxes","form_file_preview":"0","inputmode":"1","iform_title":"1","iform_desc":"1","iform_lang":"0","iform_access":"0","iform_dir":"0","iform_stamp":"0","iform_title_default":"","iform_desc_default":"","iform_lang_default":"*","iform_access_default":"1","iform_dir_default":"1","iform_stamp_default":"1","use_myfiles":"1","autoassign":"1","target_dir":"1","filelist_cols":["upload_time","hits","__SAVED__"],"resize_on_upload":"","upload_max_w":"","upload_max_h":"","upload_method":"","display_label":"1","show_acc_msg":"0","no_acc_msg":"","include_in_csv_export":"0","noaccess_display":"1","noaccess_addvars":"0","noaccess_url_unlogged":"","noaccess_msg_unlogged":"","noaccess_url_logged":"","noaccess_msg_logged":"","usebutton":"1","buttonsposition":"1","use_action_separator":"0","action_separator":"","allowdownloads":"1","downloadstext":"FLEXI_DOWNLOAD","allowview":"0","viewtext":"","viewinside":"1","stamp_pdfs":"0","pdf_header_text":"","pdf_footer_text":"","pdf_header_ffamily":"Helvetica","pdf_header_fstyle":"","pdf_header_fsize":"11","pdf_header_align":"C","pdf_header_border_type":"0","pdf_footer_ffamily":"Helvetica","pdf_footer_fstyle":"","pdf_footer_fsize":"11","pdf_footer_align":"C","pdf_footer_border_type":"0","stamp_date_format":"DATE_FORMAT_LC2","stamp_custom_date":"","stamp_lang_filter_format":"0","stamp_display_tz_suffix":"1","stamp_display_tz_logged":"0","stamp_display_tz_guests":"0","use_downloads_manager":"0","addtocarttext":"","allowshare":"0","sharetext":"","enable_coupons":"0","coupon_hits_limit":"3","coupon_expiration_days":"15","viewlayout":"InlineBoxes","display_total_count":"0","total_count_label":"FLEXI_FIELD_FILE_TOTAL_FILES","display_total_hits":"0","total_hits_label":"FLEXI_FIELD_FILE_TOTAL_DOWNLOADS","useicon":"0","display_filename":"1","lowercase_filename":"1","link_filename":"1","display_lang":"1","display_size":"0","display_hits":"0","display_descr":"1","use_info_separator":"0","info_separator":"","prx_sfx_open_close_configs":"","remove_space":"0","pretext":"","posttext":"","separatorf":"1","opentag":"","closetag":"","trigger_onprepare_content":"0","trigger_plgs_incatview":"0","send_notifications":"0","notifications_step":"20","notification_tmpl":"%%FLEXI_HITS%% __FILE_HITS__ \r\n %%FLEXI_FDN_FILE_NO%% __FILE_ID__:  [__FILE_TITLE__] \r\n %%FLEXI_FDN_FILE_IN_ITEM%% __ITEM_TITLE__: \r\n __ITEM_URL__","send_all_to_email":"","send_to_current_item_owner":"0","send_to_email_field":"0","display_filter_as_s":"1"}';
				$attribs = json_decode(json_encode($attribs), true);
				// $file    = self::addField(0, 'file', 'k2attachment', 'k2attachment', '', '', 1, $attribs, $user->id, $dateTime);
			}
			$valuesjsons = $this->recoveredFiles($sql);
			return $valuesjsons;
			die();
		}
		else
		{
			return (json_encode(array('task' => false, 'sql' => '', 'message' => '', 'type' => '', 'name' => '')));
			die;
		}
	}


    public function recoveredFiles($sql)
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id', 'itemID', 'filename', 'title', 'titleAttribute', 'hits')))
			->from($db->quoteName('#__k2_attachments'));

			$db->setQuery($query);
			$sql =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertFiles($row);
			$title        = $row['title'];
			$filename     = $row['filename'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteFile($filename, $title);
				$message = self::insertFiles($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteFile($filename, $title);
				$message = self::insertFiles($row);
			}
			unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertTags', 'sql' => '', 'message' => $message, 'type' => 'File', 'name' => $title), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertFiles', 'sql' => $sql, 'message' => $message, 'type' => 'File', 'name' => $title), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertTags', 'sql' => '', 'message' => "noexist", 'type' => 'File', 'name' => "???"), JSON_NUMERIC_CHECK);
		return $valuesjsons;
	}


	public function insertFiles($row)
	{
		$db             = JFactory::getDBO();
		$user           = JFactory::getUser();
		$dateTime       = date_create('now')->format('Y-m-d H:i:s');
		$id             = $row['id'];
		$itemID         = $row['itemID'];
		$filename       = $row['filename'];
		$title          = $row['title'];
		$titleAttribute = $row['titleAttribute'];
		$hits           = $row['hits'];
		$message        = self::addFile($id, $itemID, $filename, $title, $titleAttribute, $hits, $user->id, $dateTime);
		return $message ;
    }
    

    
	public function addFile($id, $itemID, $filename, $title, $titleAttribute, $hits, $user, $datetime)
	{
		// record_ID is the id of the item that you want to load, or set it to zero for new item

		// **************************************
		// Include the needed classes and helpers
		// **************************************

		if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);//TODO a vérifier

		// ***
		// *** Create the item model object
		// ***

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('filename')))
		->from($db->quoteName('#__flexicontent_files'))
		->where($db->quoteName('filename') . ' = '. $db->Quote($filename). ' AND ' . $db->quoteName('altname') . ' = '.  $db->quote($title));
		$db->setQuery($query);
		$namelike =  $db->loadResult();

		if(!$namelike)
		{
			$ext = substr($filename, strrpos($filename, '.')+1);
			$sourceImage = JPATH_ROOT.'/media/k2/attachments/'.$filename;

			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);

				// Insert columns.
				$columns = array('filename', 'filename_original', 'altname', 'description', 'url', 'secure',
						'ext', 'published', 'language', 'hits', 'size', 'assignments', 'stamp', 'uploaded',
						'uploaded_by', 'checked_out', 'checked_out_time', 'access', 'attribs');

				// Insert values.
				$values = array($db->Quote($filename), $db->Quote($filename), $db->Quote($title), $db->Quote(''), 0, 1, $db->Quote($ext), 1, $db->Quote('*'),
						$db->Quote($hits), $db->Quote(0), $db->Quote(0), $db->Quote(1), $db->Quote($datetime),
						$db->Quote($user), $db->Quote(0), $db->Quote($datetime), $db->Quote(1), $db->Quote(''));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_files'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch(Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteTag($name);
				return "failed" ;
			}

			if(JFile::exists($sourceImage))
			{
				JFile::copy($sourceImage, JPATH_ROOT.'/components/com_flexicontent/uploads/'.$filename);
				return 'success';
			}
			else
			{
				return "failed";
			}
		}
		else
		{
			return "exist" ;
		}
    }
    

    public function deleteFile($filename, $title)
	{
		$db    = JFactory::getDBO();
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__flexicontent_files'))
		->where($db->quoteName('filename') . ' = '. $db->Quote($filename). ' AND ' . $db->quoteName('altname') . ' = '.  $db->quote($title));
		$db->setQuery($query);
		$id =  $db->loadResult();
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_files'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);

	}


     
}