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
class InsertFieldHelper
{
    /**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */
    
	public function recoveredFields($sql) 
	{
		$db          = JFactory::getDBO();
		$user        = JFactory::getUser();
		if($sql == '')
		{
			$query   = $db->getQuery(true);
			$query
			->select($db->quoteName(array('name', 'value', 'published', 'type')))
			->from($db->quoteName('#__k2toflexi_extra_fields'));
			$db->setQuery($query);
			$sql     =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message = self::insertFields($row);
			$name    = $row['name'];
			$value   = $row['value'];
			$values  = json_decode($value);
			$label   = $values[0]->{'alias'};
			if ($label == ''){
				$label = str_replace('  ','-',$name);
				$label = strtr($label,  "�����������������������������������������������������","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn" );
				$label = strtolower($label);
			}
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteField($name, $label);
				$message = self::insertFields($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteField($name, $label);
				$message = self::insertFields($row);
			}
			unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => 'insertType', 'sql' => '', 'message' => $message, 'type' => 'Field', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertFields', 'sql' => $sql, 'message' => $message, 'type' => 'Field', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertType', 'sql' => '', 'message' => 'noexist', 'type' => 'Field', 'name' => "???"), JSON_NUMERIC_CHECK);
		return $valuesjsons;
    }
    

    public function insertFields($row)
	{
		$db          = JFactory::getDBO();
		$user        = JFactory::getUser();
		$dateTime    = date_create('now')->format('Y-m-d H:i:s');
		$name        = $row['name'];
		$value       = $row['value'];
		$published   = $row['published'];
		$type        = $row['type'];
		$values      = json_decode($value);
		$description = $values[0]->{'value'};
		$alias       = $values[0]->{'alias'};
		$change      = self::fieldChange($type, $value);
		$type        = $change[0];
		$attribs     = $change[1];
		if($alias == '')
		{
			$alias   = str_replace(' ','-',$name);
			$alias   = strtr($alias,  "�����������������������������������������������������","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn" );//TODO a vérifier
			$alias   = strtolower($alias);
		}
		if ($type == 'error')
		{
			return "failed";
		}
		else
		{
	
			$message = self::addField(0, $type, $alias, $name, $description, '', $published, $attribs, $user->id, $dateTime);
			return $message;
		}
    }


    public function fieldChange($type, $value)
	{
		$values = json_decode($value);
		switch($type)
		{
			case "textfield":
				$type    = 'text';
				$val     = $values[0]->{'value'};
				$attribs = json_encode(array('add_position' => 3, 'validation' => 'HTML', 'display_label_form' => 1, 'size' => 30, 'default_value' => $val), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "textarea":
				$type    = 'textarea';
				$val     = $values[0]->{'value'};
				$cols    = $values[0]->{'cols'};
				$rows    = $values[0]->{'rows'};
 				$attribs = json_encode(array('add_position' => 3, 'validation' => 2, 'display_label_form' => 1,
 						'display_label' => 1, 'default_value' => $val, 'rows' => $rows , 'cols' => $cols ,'editor' => '', 'use_html' =>1,
 						'trigger_onprepare_content' => 1, 'trigger_plgs_incatview' => 0, 'microdata_itemprop' => '',
 						'useogp' => 0, 'ogpinview' => ['__SAVED__'], 'ogpusage' => 1, 'ogpmaxlen' => 300,
 						'display_filter_as_s' => 1), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "select":
				$type      = 'select';
				$vals      = $values[0]->{'value'};
				$nbElem    = 0;
				$fieldElem = '';
				foreach($values as $val)
				{
					if($nbElem == 0)
					{
						$fieldElem = $fieldElem        . 'n' . $val->{'value'} . '::'.($val->{'name'}) . '%%' ;
					}
					else
					{
						$fieldElem = '\r\ '.$fieldElem . 'n' . $val->{'value'} . '::'.($val->{'name'}) . '%%' ;
					}
					$nbElem = $nbElem . 1;
				}
				$attribs = json_encode(array('use_ingroup' => 0, 'allow_multiple' => 0, 'add_position' => 3, 'required' => 0,
						'max_values' => 0, 'min_values' => 0, 'exact_values' => 0, 'default_value' => $vals,
						'default_value_use' => 0, 'sql_mode' => 0, 'field_elements' => $fieldElem), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "multipleSelect":
				$type      = 'selectmultiple';
				$vals      = $values[0]->{'value'};
				$nbElem    = 0;
				$fieldElem = '';
				foreach($values as $val)
				{
					if($nbElem == 0)
					{
						$fieldElem = $fieldElem        . 'n' . $val->{'value'} . '::'.($val->{'name'}) . '%%' ;
					}
					else
					{
						$fieldElem = '\r\ '.$fieldElem . 'n' . $val->{'value'} . '::'.($val->{'name'}) . '%%' ;
					}
					$nbElem = $nbElem . 1;
				}
				$attribs = json_encode(array('use_ingroup' => 0, 'allow_multiple' => 0, 'add_position' => 3, 'required' => 0,
						'max_values' => 0, 'min_values' => 0, 'exact_values' => 0, 'default_value' => $vals,
						'default_value_use' => 0, 'sql_mode' => 0, 'field_elements' => $fieldElem), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "radio":
				$type      = 'radio';
				$vals      = $values[0]->{'value'};
				$nbElem    = 0;
				$fieldElem = '';
				foreach($values as $val)
				{
					if($nbElem == 0)
					{
						$fieldElem = $fieldElem . 'n' . $val->{'value'} . '::'.($val->{'name'}) . '%%' ;
					}
					else
					{
						$fieldElem = '\r\ '.$fieldElem . 'n' . $val->{'value'} . '::'.($val->{'name'}) . '%%' ;
					}
					$nbElem = $nbElem . 1;
				}
				$attribs = json_encode(array('use_ingroup' => 0, 'allow_multiple' => 0, 'add_position' => 3, 'required' => 0,
						'max_values' => 0, 'min_values' => 0, 'exact_values' => 0, 'default_value' => $vals,
						'default_value_use' => 0, 'sql_mode' => 0, 'field_elements' => $fieldElem), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "link":
				$type    = 'weblink';
				$val     = $values[0]->{'value'};
				$val     = str_replace('http:\/\/','',$val);
				$text    = $values[0]->{'name'};
				$target  = $values[0]->{'target'};
				$attribs = json_encode(array('use_ingroup' => 0, 'allow_multiple' => 0, 'add_position' => 3, 'max_values' => 0,
						'default_link' => $val, 'use_text' => 1, 'default_text'=> '', 'title' => $text, 'target' => $target,'required' => 1, 'inputmask' => '', 'maxlength' => '4000', 'size' => 30,
						'extra_attributes' => '', 'display_label' => 1, 'show_acc_msg' => 0, 'no_acc_msg' => '',
						'include_in_csv_export' => 0, 'viewlayout' => 'default', 'prx_sfx_open_close_configs' => '',
						'remove_space' => 0, 'pretext' => '', 'posttext' => '', 'separatorf' => 1, 'opentag' => '',
						'closetag' => '', 'trigger_onprepare_content' => 0, 'trigger_plgs_incatview' => 0), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "csv":
				$type = 'file';
				$attribs = json_encode(array('use_ingroup' => 0, 'allow_multiple' => 1, 'add_position' => 3,
						'max_values' => 0, 'required' => 0, 'display_label' => 1, 'show_acc_msg' => 0, 'no_acc_msg' => '',
						'include_in_csv_export' => 0, 'viewlayout' => 'InlineBoxes', 'prx_sfx_open_close_configs' => '',
						'remove_space' => 0, 'pretext' => '', 'posttext' => '', 'separatorf' => 1, 'opentag' => '',
						'closetag' => '', 'trigger_onprepare_content' => 0, 'trigger_plgs_incatview' => 0,
						'display_filter_as_s' => 1), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "date":
				$type = 'date';
				$attribs = json_encode(array('use_ingroup' => 0, 'allow_multiple' => 0, 'add_position' => 3,
						'max_values' => 0, 'required' => 0, 'display_label_form' => 1, 'no_acc_msg_form' => '',
						'size' => 30, 'display_label' => 1, 'show_acc_msg' => 0, 'no_acc_msg' => '',
						'include_in_csv_export' => 0, 'viewlayout' => 'default', 'prx_sfx_open_close_configs' => '',
						'remove_space' => 0, 'pretext' => '', 'posttext' => '', 'separatorf' => 1), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "image":
				$type      = 'image';
				$params    = JComponentHelper::getParams('com_k2toflexi');
				$imageType = $params['imageType'];
				if(empty($imageType) || $imageType == 1)
				{
					$attribs = '{"display_label_form":"1","no_acc_msg_form":"","use_ingroup":"0","allow_multiple":"1","fields_box_placing":"1","max_values":"0","required":"0","add_position":"3","file_btns_position":"2","upload_extensions":"bmp,gif,jpg,jpeg,png","upload_maxsize":"10000000","thumb_size_resizer":"2","thumb_size_default":"120","image_source":"1","autoassign":"1","of_usage":"0","protect_original":"1","target_dir":"1","auto_delete_unused":"1","list_all_media_files":"0","limit_by_uploader":"0","resize_on_upload":"","upload_max_w":"","upload_max_h":"","upload_method":"","linkto_url":"0","url_target":"_self","use_alt":"0","default_alt":"","alt_usage":"0","use_title":"0","default_title":"","title_usage":"0","use_desc":"1","default_desc":"","text_desc":"0","use_cust1":"0","default_cust1":"","cust1_usage":"0","use_cust2":"0","default_cust2":"","cust2_usage":"0","display_label":"1","show_acc_msg":"0","no_acc_msg":"","include_in_csv_export":"0","default_method_item":"display","default_method_cat":"display_single","cat_link_single_to":"1","usepopup":"1","popupinview":["item","category","module","backend","__SAVED__"],"popuptype":"4","popuptype_mobile":"","grouptype":"1","thumbincatview":"1","thumbinitemview":"2","showtitle":"0","showdesc":"0","uselegend":"1","legendinview":["item","category","module","backend","__SAVED__"],"default_image":"","dir":"images\\\/stories\\\/flexicontent","unique_thumb_method":"0","quality":"90","wm_opacity":"100","wm_position":"BR","w_l":"800","h_l":"600","method_l":"0","use_watermark_l":"0","wm_l":"plugins\\\/flexicontent_fields\\\/image\\\/image\\\/watermarks\\\/wm_l.png","copy_original_l":"1","w_m":"400","h_m":"300","method_m":"0","use_watermark_m":"0","wm_m":"plugins\\\/flexicontent_fields\\\/image\\\/image\\\/watermarks\\\/wm_m.png","copy_original_m":"1","w_s":"120","h_s":"90","method_s":"1","use_watermark_s":"0","wm_s":"plugins\\\/flexicontent_fields\\\/image\\\/image\\\/watermarks\\\/wm_s.png","copy_original_s":"1","w_b":"40","h_b":"30","method_b":"1","use_watermark_b":"0","wm_b":"plugins\\\/flexicontent_fields\\\/image\\\/image\\\/watermarks\\\/wm_s.png","copy_original_b":"1","prx_sfx_open_close_configs":"","remove_space":"0","pretext":"","posttext":"","separatorf":"0","opentag":"","closetag":"","useogp":"0","ogpinview":["item","__SAVED__"],"ogpthumbsize":"2","display_filter_as_s":"1"}';
					$attribs = json_decode(json_encode($attribs), true);
				}
				else
				{
					$attribs = json_encode(array('display_label_form' => 1, 'no_acc_msg_form' => '', 'use_ingroup' => 0,
						'allow_multiple' => 1, 'fields_box_placing' => 1, 'max_values' => 0, 'required' => 0,
						'add_position' => 3, 'file_btns_position' => 2, 'upload_extensions' => 'bmp,gif,jpg,jpeg,png',
						'upload_maxsize' => 10000000, 'thumb_size_resizer' => 2, 'thumb_size_default' => 120, 'image_source' => (-2),
						'autoassign' => 1, 'of_usage' => 0, 'protect_original' => 1, 'target_dir' => 1,
						'auto_delete_unused' => 1, 'list_all_media_files' => 0, 'limit_by_uploader' => 0,
						'resize_on_upload' => '', 'upload_max_w' => '', 'upload_max_h' => '', 'upload_method' => '',
						'linkto_url' => 0, 'url_target' => '_self', 'use_alt' => 0, 'default_alt' => '',
						'alt_usage' => 0, 'use_title' => 0, 'default_title' => '', 'title_usage' => 0, 'use_desc' => 1,
						'default_desc' => '', 'text_desc' => 0, 'use_cust1' => 0, 'default_cust1' => '', 'cust1_usage' => 0,
						'use_cust2' => 0, 'default_cust2' => '', 'cust2_usage' => 0, 'display_label' => 1,
						'show_acc_msg' => 0, 'no_acc_msg' => '', 'include_in_csv_export' => 0,
						'default_method_item' => 'display', 'display' => 'display_single',
						'cat_link_single_to' => 1, 'usepopup' => 1, 'popupinview' => ['item','category',
		 				'module','backend','__SAVED__'], 'popuptype' => 4, 'popuptype_mobile' => '', 'grouptype' => 1,
		 				'thumbincatview' => 1, 'thumbinitemview' => 2, 'showtitle' => 0, 'showdesc' => 0,
		 				'uselegend' => 1, 'legendinview' => ['item','category','module','backend','__SAVED__'],
		 				'default_image' => '', 'dir' => 'images\/stories\/flexicontent', 'unique_thumb_method' => 0,
		 				'quality' => 90, 'wm_opacity' => 100, 'wm_position' => 'BR', 'w_l' => 800, 'h_l' => 600,
		 				'method_l' => 0, 'use_watermark_l' => 0, 'wm_l' => 'plugins\/flexicontent_fields\/image\/image\/watermarks\/wm_l.png',
		 				'copy_original_l' => 1, 'w_m' => 400, 'h_m' => 300, 'method_m' => 0, 'use_watermark_m' => 0,
		 				'wm_m' => 'plugins\/flexicontent_fields\/image\/image\/watermarks\/wm_m.png',
		 				'copy_original_m' => 1, 'w_s' => 120, 'h_s' => 90, 'method_s' => 1, 'use_watermark_s' => 0,
		 				'wm_s' => 'plugins\/flexicontent_fields\/image\/image\/watermarks\/wm_s.png',
		 				'copy_original_s' => 1, 'w_b' => 40, 'h_b' => 30, 'method_b' => 1, 'use_watermark_b' => 0,
		 				'wm_b' => 'plugins\/flexicontent_fields\/image\/image\/watermarks\/wm_s.png',
		 				'copy_original_b' => 1, 'prx_sfx_open_close_configs' => '', 'remove_space' => 0,
		 				'pretext' => '', 'posttext' => '', 'separatorf' => 0, 'opentag' => '', 'closetag' => '', 'useogp' => 0,
		 				'ogpinview' => ['item','__SAVED__'], 'ogpthumbsize' => 2, 'display_filter_as_s' => 1), JSON_NUMERIC_CHECK);
				}
				return [$type, $attribs];
			case "header":
				$type    = 'error';
				$attribs = json_encode(array('display_label' => 1, 'pretext' => '', 'posttext' => ''), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			case "labels":
				$type    = 'error';
				$attribs = json_encode(array('display_label' => 1, 'pretext' => '', 'posttext' => ''), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
			default:
				$type    = 'error';
				$attribs = json_encode(array('display_label' => 1, 'pretext' => '', 'posttext' => ''), JSON_NUMERIC_CHECK);
				return [$type, $attribs];
		}
	}


    public function addField($record_ID, $field_type, $name, $label, $description, $positions, $published, $attribs, $checked_out, $checked_out_time)
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
		->select($db->quoteName(array('name')))
		->from($db->quoteName('#__flexicontent_fields'))
		->where($db->quoteName('name') . ' = '. $db->Quote($name) . ' AND ' . $db->quoteName('label') . ' = '.  $db->quote($label));
		$db->setQuery($query);
		$namelike =  $db->loadResult();

		if(!$namelike)
		{

			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);

				// Insert columns.
				$columns = array('asset_id', 'field_type', 'name', 'label', 'description', 'positions', 'published', 'attribs', 'checked_out', 'checked_out_time');

				// Insert values.
				$values = array(999, $db->Quote($field_type), $db->Quote($name), $db->Quote($label), $db->Quote($description), $db->Quote($positions), $published, $db->Quote($attribs), $checked_out, $db->Quote($checked_out_time));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_fields'))
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
				self::deleteField($name, $label);
				return "failed" ;
			}
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id')))
			->from($db->quoteName('#__flexicontent_fields'))
			->where($db->quoteName('name') . ' = '. $db->Quote($name));
			$db->setQuery($query);
			$id =  $db->loadResult();
			$parentid  = self::getFlexiId();
			$assetname = self::addAsset($id, $parentid, 'field', $name, $label, true);

			if($assetname == false)
			{
				$query = $db->getQuery(true);
				$conditions = array(
					$db->quoteName('name') . ' = '. $db->Quote($name)
				);
				$query->delete($db->quoteName('#__flexicontent_fields'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->query($query);
				return "failed" ;
			}
			else
			{
				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id')))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('name') . ' = '. $db->Quote($assetname));
				$db->setQuery($query);
				$assetid =  $db->loadResult();

				try
				{
					$db->transactionStart();
					$query = $db->getQuery(true);
					$fields = array(
							$db->quoteName('asset_id') . ' = ' . $db->quote($assetid)
					);

					// Conditions for which records should be updated.
					$conditions = array(
							$db->quoteName('name') . ' = ' . $db->quote($name),
					);
					$query->update($db->quoteName('#__flexicontent_fields'))->set($fields)->where($conditions);
					$db->setQuery($query);
					$result = $db->execute();
					$db->transactionCommit();
				}
				catch(Exception $e)
				{
					// catch any database errors.
					$db->transactionRollback();
					self::deleteField($name, $label);
					return "failed" ;
				}
				return "success" ;
			}
			return "success" ;
		}
		else
		{
			return "exist" ;
		}
    }
    

    public function deleteField($name, $label)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__flexicontent_fields'))
		->where($db->quoteName('name') . ' = '. $db->Quote($name) . ' AND ' . $db->quoteName('label') . ' = '.  $db->quote($label));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_fields'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$name       = 'com_flexicontent.field.'.$id ;
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('name') . ' = '. $db->Quote($name)
		);
		$query->delete($db->quoteName('#__assets'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
	}


	public function getFlexiId()
	{
		$class   = new InsertTypeHelper();
		$methods = $class->getFlexiId();
		return $methods;
	}
 
	public function addAsset($id, $parent_id, $type, $title, $alias, $ressaie)
	{
		$class   = new InsertItemHelper();
		$methods = $class->addAsset($id, $parent_id, $type, $title, $alias, $ressaie);
		return $methods;
	}


}