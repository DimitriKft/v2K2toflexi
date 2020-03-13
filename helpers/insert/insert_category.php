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
class InsertCatHelper
{
	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */
	public function category($task, $sql)
	{
		//initialise variables.
		$db                         = JFactory::getDBO();
		$user                       = JFactory::getUser();
		$dateTime                   = date_create('now')->format('Y-m-d H:i:s');
		$sql                        = json_decode(json_encode($sql),    true);
		$params                     = JComponentHelper::getParams('com_k2toflexi');
		$recoveredCategory          = $params['insertCategory'];
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

	 if($task == 'insertCategory')
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
		else
		{
			return (json_encode(array('task' => false, 'sql' => '', 'message' => '', 'type' => '', 'name' => '')));
			die;
		}
	}

    public function recoveredCategory($sql)
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if ($sql == ''){
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id', 'name', 'alias', 'description', 'parent', 'extraFieldsGroup', 'published', 'access', 'ordering', 'image', 'params', 'trash', 'plugins', 'language')))
			->from($db->quoteName('#__k2toflexi_categories'));
			$db->setQuery($query);
			$sql2     =  $db->loadAssocList();
			$sqlplace =  array();
		}
		else
		{
			$sql2     = $sql['sql'];
			$sqlplace = $sql['sqlplace'];
		}
		foreach($sql2 as $elem =>$row)
		{
			$message      = self::insertCategory($row);
			$name         = $row['name'];
			$alias        = $row['alias'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteCategory($name, $alias);
				$message = self::insertCategory($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteCategory($name, $alias);
				$message = self::insertCategory($row);
			}
			if($message == false)
			{
				array_push($sqlplace, $row);
				$message = "success";
				unset($sql2[$elem]);
			}
			else
			{
				unset($sql2[$elem]);
			}
			$sql = array('sql' => $sql2, 'sqlplace' => $sqlplace);
			if($sql2 == array())
			{
				$valuesjsons = json_encode(array('task' => 'placeCategory', 'sql' => $sqlplace, 'message' => $message, 'type' => 'Category', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else
			{
				$valuesjsons = json_encode(array('task' => 'insertCategory', 'sql' => $sql, 'message' => $message, 'type' => 'Category', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertItem', 'sql' => '', 'message' => 'noexist', 'type' => 'Category', 'name' => "???"), JSON_NUMERIC_CHECK);
		return $valuesjsons;
		die;
    }
    

    
	public function insertCategory($row)
	{
		$db          = JFactory::getDBO();
		$user        = JFactory::getUser();
		$dateTime    = date_create('now')->format('Y-m-d H:i:s');
		$id          = $row['id'];
		$name        = $row['name'];
		$alias       = $row['alias'];
		$description = $row['description'];
		$parent      = $row['parent'];
		$published   = $row['published'];
		$access      = $row['access'];
		$params      = $row['params'];
		$language    = $row['language'];
		$image       = $row['image'];
		$param       = json_decode($params);

		if(isset($param->{'catMetaDesc'}))
		{
			$catMetaDesc = $param->{'catMetaDesc'};
		}
		else
		{
			$catMetaDesc = '';
		}
		if(isset($param->{'catMetaKey'}))
		{
			$catMetaKey = $param->{'catMetaKey'};
		}
		else
		{
			$catMetaKey = '';
		}
		if(isset($param->{'catMetaRobots'}))
		{
			$catMetaRobots = $param->{'catMetaRobots'};
		}
		else{
			$catMetaRobots = '';
		}
		if(isset($param->{'catMetaAuthor'}))
		{
			$catMetaAuthor = $param->{'catMetaAuthor'};
		}
		else
		{
			$catMetaAuthor = '';
		}

		$catMetaData = json_encode(array('page_title' => $alias, 'author' => $catMetaAuthor, 'robots' => $catMetaRobots), JSON_NUMERIC_CHECK);
		$continue    = self::addCategory($id, 0, $parent, $name, $alias, $description, $published, $user->id, $dateTime, $access, $catMetaDesc, $catMetaKey, $catMetaData, $language, $image);
		return $continue;
	}


    public function placeCategory($sql)
	{
		$db = JFactory::getDBO();
		while($sql != (array()))
		{
			foreach($sql as $elem =>$row)
			{
				$message = self::placeCategory2($row);
				$name    = $row['name'];
				if($message == 'failed' || $message == false || $message == 'error')
				{
					unset($sql[$elem]);
				}
				else
				{
					unset($sql[$elem]);
					array_push($sql, $row);
				}
				if($sql == array())
				{
					$valuesjsons = json_encode(array('task' => 'insertItem', 'sql' => '', 'message' => $message, 'type' => 'CategoryPlace', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
				}
				else
				{
					$valuesjsons = json_encode(array('task' => 'placeCategory', 'sql' => $sql, 'message' => $message, 'type' => 'CategoryPlace', 'name' => $name), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
				}
			}
		}
		$valuesjsons = json_encode(array('task' => 'insertItem', 'sql' => '', 'message' => '', 'type' => '', 'name' => ''), JSON_NUMERIC_CHECK);
		return $valuesjsons;
		die;
	}



    public function placeCategory2($row)
	{
		$db       = JFactory::getDBO();
		$user     = JFactory::getUser();
		$alias    = $row['alias'];
		$title    = $row['name'];
		$dateTime = date_create('now')->format('Y-m-d H:i:s');
		$query    = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id', 'asset_id', 'level')))
		->from($db->quoteName('#__categories'))
		->where($db->quoteName('alias') . " = ".$db->quote($alias). ' AND ' . $db->quoteName('title') . ' = '.  $db->quote($title));
		$db->setQuery($query);
		$data =  $db->loadAssoc();
		if(!$data)
		{
			return 'failed';
		}
		else
		{
			$id_k2     = $row['id'];
			$parent_k2 = $row['parent'];
			if($parent_k2 == 0 )
			{
				return 'failed';
			}
			else
			{
				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('alias')))
				->from($db->quoteName('#__k2_categories'))
				->where($db->quoteName('id') . " = ".$db->quote($parent_k2));
				$db->setQuery($query);
				$alias_k2 =  $db->loadResult();
				
				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('name')))
				->from($db->quoteName('#__k2_categories'))
				->where($db->quoteName('id') . " = ".$db->quote($parent_k2));
				$db->setQuery($query);
				$title_k2 =  $db->loadResult();

				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id', 'level', 'asset_id', 'path')))
				->from($db->quoteName('#__categories'))
				->where($db->quoteName('title') . " = ".$db->quote($title_k2). ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias_k2));
				$db->setQuery($query);
				$data_parent =  $db->loadAssoc();
				$id          = $data['id'];

				if(!$data_parent || $data_parent['id'] == $id)
				{
					$query = $db->getQuery(true);
					$query
					->select($db->quoteName(array('id')))
					->from($db->quoteName('#__assets'))
					->where($db->quoteName('name') . " = ".$db->quote('com_content'). ' AND ' . $db->quoteName('parent_id') . ' = 1');
					$db->setQuery($query);
					$id_asset =  $db->loadResult();
					try
					{
						$db->transactionStart();
						$query = $db->getQuery(true);
						$fields = array(
								$db->quoteName('parent_id') . ' = ' . $db->quote($id_asset),
								$db->quoteName('level')     . ' = 2'
						);
						// Conditions for which records should be updated.
						$conditions = array(
								$db->quoteName('name')      . ' = ' . $db->quote('com_content.category'.$id),
						);

						$query->update($db->quoteName('#__assets'))->set($fields)->where($conditions);
						$db->setQuery($query);
						$result = $db->execute();
						$db->transactionCommit();
					}
					catch(Exception $e)
					{
						// catch any database errors.
						$db->transactionRollback();
						self::deleteCategory($title, $alias);
						return false ;
					}
					try
					{
						$db->transactionStart();
						$query = $db->getQuery(true);
						$fields = array(
								$db->quoteName('parent_id') . ' = ' . $db->quote(1),
								$db->quoteName('level')     . ' = ' . $db->quote(1),
								$db->quoteName('path')      . ' = ' . $db->quote($title),
								$db->quoteName('extension') . ' = ' . $db->quote('com_content')
						);
						// Conditions for which records should be updated.
						$conditions = array(
						        $db->quoteName('id')        . ' = ' . $db->quote($id),
						);

						$query->update($db->quoteName('#__categories'))->set($fields)->where($conditions);
						$db->setQuery($query);
						$result = $db->execute();
						$db->transactionCommit();
						return 'error';
					}
					catch(Exception $e)
					{
						// catch any database errors.
						$db->transactionRollback();
						self::deleteCategory($title, $alias);
						return false ;
					}
				}
				$parent_id    = $data_parent['id'];
				$parent_level = $data_parent['level'];
				$level        = $data['level'];

				if($level > $parent_level)
				{
					return 'failed';
				}
				else
				{
					$asset_parent_id = $data_parent['asset_id'];
					$asset_id        = $data['asset_id'];
					$asset_name      = 'com_content.category.'.$id;
			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);
				$fields = array(
						$db->quoteName('parent_id') . ' = ' . $db->quote($asset_parent_id),
						$db->quoteName('level')     . ' = ' . $db->quote($parent_level + 2)
				);
				// Conditions for which records should be updated.
				$conditions = array(
						$db->quoteName('name')      . ' = ' . $db->quote($asset_name),
				);

				$query->update($db->quoteName('#__assets'))->set($fields)->where($conditions);
				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch(Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteCategory($title, $alias);
				return false ;
			}
					$parent_path = $data_parent['path'];
					$path        = $parent_path . '/' . $title;
					$extension   = self::getExtension($asset_parent_id, ($parent_level + 2));
			try
			{
				$db->transactionStart();
				$query  = $db->getQuery(true);
				$fields = array(
						$db->quoteName('parent_id') . ' = ' . $db->quote($parent_id),
						$db->quoteName('level')     . ' = ' . $db->quote($parent_level + 1),
						$db->quoteName('path')      . ' = ' . $db->quote($path),
						$db->quoteName('extension') . ' = ' . $db->quote($extension)
				);

				// Conditions for which records should be updated.
				$conditions = array(
						$db->quoteName('id')        . ' = ' . $db->quote($id),
				);

				$query->update($db->quoteName('#__categories'))->set($fields)->where($conditions);
				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch(Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteCategory($title, $alias);
				return false ;
			}
					return 'success';
				}
			}
		}
    }
    

    public function addCategory($id, $record_ID, $parent_id, $title, $alias, $description, $published, $user_id, $dateTime, $access, $catMetaDesc, $catMetaKey, $catMetaData, $language, $image = '', $hits = 0, $version = 1)
	{
		// record_ID is the id of the item that you want to load, or set it to zero for new item

		// **************************************
		// Include the needed classes and helpers
		// **************************************

		if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

		// ***
		// *** Create the item model object
		// ***

		$db          = JFactory::getDBO();
		$sourceImage = JPATH_ROOT.'/images/k2/categories/'.$alias.'.jpg';

		if(JFile::exists($sourceImage))
		{
			$image = 'images\/k2\/categories\/'.$alias.'.jpg';
		}
		else 
		{
			$image = '';
		}

		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('title')))
		->from($db->quoteName('#__categories'))
		->where($db->quoteName('title') . ' = '. $db->quote($title) . ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
		$db->setQuery($query);
		$namelike =  $db->loadResult();

		if (!$namelike){
			$params = json_encode(array('image' => $image,'note' => '','print_behaviour' => '','show_print_icon' => '','show_email_icon' => ''
					,'show_feed_icon' => '','show_feed_link' => '','show_csvbutton' => '','show_addbutton' => '',
			'addbutton_menu_itemid' => '','show_icons' => '','use_font_icons' => '','font_icon_configs' => '',
			'font_icon_classes' => '','btn_grp_dropdown' => '','btn_grp_dropdown_class' => '','show_cat_title' => '',
			'title_cut_text' => '','show_description_image' => '','cat_image_source' => '','cat_link_image' => '',
			'cat_image_method' => '','cat_image_width' => '','cat_image_height' => '','show_description' => '',
			'trigger_onprepare_content_cat' => '','show_subcategories' => '','subcat_orderby' => '','show_itemcount' => '',
			'show_subcatcount' => '','show_empty_cats' => '','show_description_image_subcat' => '','subcat_image_source' => '',
			'subcat_link_image' => '','subcat_image_method' => '','subcat_image_width' => '','subcat_image_height' => '',
			'show_description_subcat' => '','description_cut_text_subcat' => '','show_label_subcats' => '','prx_sfx_open_close_configs_subcat' => '',
			'subcat_pretext' => '','subcat_posttext' => '','subcat_separatorf' => '','subcat_opentag' => '','subcat_closetag' => '',
			'show_peercategories' => '','peercat_orderby' => '','show_itemcount_peercat' => '','show_subcatcount_peercat' => '',
			'show_empty_peercats' => '','show_description_image_peercat' => '','peercat_image_source' => '','peercat_link_image' => '',
			'peercat_image_method' => '','peercat_image_width' => '','peercat_image_height' => '','show_description_peercat' => '',
			'description_cut_text_peercat' => '','show_label_peercats' => '','prx_sfx_open_close_configs_peercat' => '','peercat_pretext' => '',
			'peercat_posttext' => '','peercat_separatorf' => '','peercat_opentag' => '','peercat_closetag' => '','ff_placement' => '',
			'ff_toggle_search_title' => '','filter_autosubmit' => '','filter_instructions' => '','show_search_go' => '',
			'flexi_button_class_go' => '','flexi_button_class_go_custom' => '','show_search_reset' => '','flexi_button_class_reset' => '',
			'flexi_button_class_reset_custom' => '','filter_pretext' => '','filter_posttext' => '','filter_opentag' => '','filter_closetag' => '',
			'filter_placement' => '','filter_separatorf' => '','use_search' => '','show_search_label' => '','show_searchphrase' => '',
			'default_searchphrase' => '','search_autocomplete' => '','use_filters' => '','filters_order' => '','filters' => '',
			'show_filter_labels' => '','initial_filters' => '','use_persistent_filters' => '','persistent_filters' => '','show_alpha' => '',
			'alphacharseparator' => '','alphaaliases' => '','alphaskipempty' => '','show_editbutton_lists' => '','show_state_icon_lists' => '',
			'show_deletebutton_lists' => '','show_comments_count' => '','show_title_lists' => '','link_titles_lists' => '','force_full' => '',
			'show_readmore' => '','orderby' => '','orderbycustomfield' => '','orderbycustomfieldi' => 0,'orderbycustomfielddir' => '',
			'orderbycustomfieldint' => '','orderby_2nd' => '','orderbycustomfield_2nd' => '','orderbycustomfieldid_2nd' => 0,
			'orderbycustomfielddir_2nd' => '','orderbycustomfieldint_2nd' => '','orderby_override' => '','orderby_override_2nd' => '',
			'limit_options' => '','orderby_custom' => '','orderby_custom_2nd' => '','show_noauth' => '','show_owned' => '','show_trashed' => '',
			'display_subcategories_items' => '','filtercat' => '','use_limit_before_search_filt' => '','limit_before_search_filt' => '',
			'display_flag_featured' => '','limit' => '','show_item_total' => '','show_pagination' => '','show_pagination_results' => '',
			'limit_override' => '','limit_override_label' => '','mu_addtext_cats' => '','mu_add_condition_obtainded_acc' => '',
			'mu_addtext_acclvl' => ['no_acc','free_acc','needed_acc','obtained_acc','__SAVED__'],'mu_no_acc_text' => '',
			'mu_free_acc_text' => '','mu_addcss_radded' => '','mu_addtext_radded' => '','mu_ra_timeframe_intervals' => '',
			'mu_ra_timeframe_names' => '','mu_addcss_rupdated' => '','mu_addtext_rupdated' => '','mu_ru_timeframe_intervals' => '',
			'mu_ru_timeframe_names' => '','automatic_pathways' => '','add_canonical' => '','microdata_itemtype_cat' => '',
			'comments' => '','feed_limit' => '','feed_summary' => '','feed_summary_cut' => '','feed_show_readmore' => '','feed_use_image' => '',
			'feed_link_image' => '','feed_image_source' => '','feed_image_size' => '','feed_image_width' => '','feed_image_height' => '',
			'feed_image_method' => '','feed_extra_fields' => '','feed_orderby' => '','feed_orderbycustomfield' => '',
			'feed_orderbycustomfieldid' => 0,'feed_orderbycustomfielddir' => '','feed_orderbycustomfieldint' => '',
			'feed_orderby_2nd' => '','feed_orderbycustomfield_2nd' => '','feed_orderbycustomfieldid_2nd' => 0,
			'feed_orderbycustomfielddir_2nd' => '','feed_orderbycustomfieldint_2nd' => '','enable_notifications' => '',
			'nf_enable_debug' => '','nf_send_as_bcc' => '','nf_extra_properties' => ['creator','modifier','created','modified',
			'viewlink','editlinkfe','editlinkbe','__SAVED__'],'nf_add_introtext' => '','nf_add_fulltext' => '',
			'cats_enable_notifications' => '','cats_userlist_notify_new' => '','cats_userlist_notify_new_pending' => '',
			'cats_userlist_notify_existing' => '','cats_userlist_notify_existing_reviewal' => '','clayout' => '','clayout_switcher' => '',
			'clayout_switcher_display_mode' => '','clayout_switcher_label' => '','inheritcid' => ''), JSON_NUMERIC_CHECK);

			$lft       = 0;
			$rgt       = 0;
			$level     = 1;
			$path      = $title;
			$extension = 'com_content';

			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);

				// Insert columns.
				$columns = array('asset_id', 'parent_id', 'lft', 'rgt', 'level', 'path', 'extension', 'title', 'alias', 'note', 'description', 'published', 'checked_out', 'checked_out_time', 'access', 'params',
						'metadesc', 'metakey', 'metadata', 'created_user_id', 'created_time', 'modified_user_id', 'modified_time', 'hits', 'language', 'version'
					);

				// Insert values.
				$values = array(0, 1, $lft, $rgt, $level, $db->quote($path), $db->quote($extension),$db->quote($title), $db->quote($alias), $db->quote(''), $db->quote($description), $published, $user_id, $db->quote($dateTime), $access, $db->quote($params),
						$db->quote($catMetaDesc), $db->quote($catMetaKey), $db->quote($catMetaData), $user_id, $db->quote($dateTime), $user_id, $db->quote($dateTime), $hits, $db->quote($language), $version
					);

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__categories'))
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
				self::deleteCategory($title, $alias);
				return "failed" ;
			}

			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id')))
			->from($db->quoteName('#__categories'))
			->where($db->quoteName('title') . ' = '. $db->quote($title) . ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
			$db->setQuery($query);
			$id    =  $db->loadResult();
			$type  = 'category';
			$query = $db->getQuery(true);

			if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);//TODO a vérifier

			$query = $db->getQuery(true);
			$content = 'com_content';
			$query
			->select($db->quoteName(array('id')))
			->from($db->quoteName('#__assets'))
			->where($db->quoteName('name') . ' = ' . '"com_content"' . ' AND ' . $db->quoteName('level') . ' = 1');
			$db->setQuery($query);
			$asset_id =  $db->loadResult();

			if($asset_id != 0)
			{
				$asset_name = self::addAsset($id, $asset_id, $type, $title, $alias, true);

				if($asset_name == false)
				{
					$query      = $db->getQuery(true);
					$conditions = array(
							$db->quoteName('id') . ' = '. $db->Quote($id)
					);
					$query->delete($db->quoteName('#__categories'));
					$query->where($conditions);
					$db->setQuery($query);
					$db->query($query);
					return "failed" ;
				}
				else
				{
					$query = $db->getQuery(true);
					$query
					->select($db->quoteName(array('id', 'parent_id', 'lft', 'rgt', 'level')))
					->from($db->quoteName('#__assets'))
					->where($db->quoteName('name') . " = ".$db->quote($asset_name));
					$db->setQuery($query);
					$data            =  $db->loadAssoc();
					$asset_id        =  $data['id'];
					$asset_lft       =  $data['lft'];
					$asset_rgt       =  $data['rgt'];
					$asset_level     =  $data['level'];
					$asset_parent_id =  $data['parent_id'];
					$level           =  $asset_level - 1;
					$path            = $title;
					$extension       = self::getExtension($asset_parent_id, $asset_level);

					try
					{
						$db->transactionStart();
						$query = $db->getQuery(true);
						$fields = array(
								$db->quoteName('asset_id')  . ' = ' . $db->quote($asset_id),
								$db->quoteName('lft')       . ' = ' . $db->quote($asset_lft),
								$db->quoteName('rgt')       . ' = ' . $db->quote($asset_rgt),
								$db->quoteName('level')     . ' = ' . $db->quote($level),
								$db->quoteName('path')      . ' = ' . $db->quote($path),
								$db->quoteName('extension') . ' = ' . $db->quote($extension)
						);

						// Conditions for which records should be updated.
						$conditions = array(
								$db->quoteName('id') . ' = ' . $db->quote($id),
						);
						$query->update($db->quoteName('#__categories'))->set($fields)->where($conditions);
						$db->setQuery($query);
						$result = $db->execute();
						$db->transactionCommit();
					}
					catch(Exception $e)
					{
						// catch any database errors.
						$db->transactionRollback();
						self::deleteCategory($title, $alias);
						return "failed" ;
					}
					if($parent_id == 0)
					{
						return "success";
					}
					else
					{
						return false;
					}
					return "success" ;
				}
			}
			else return "failed" ;
		}

		else
		{
			return "exist" ;
		}
	}


	public function addAsset($id, $parent_id, $type, $title, $alias, $ressaie)
	{
		// record_ID is the id of the item that you want to load, or set it to zero for new item

		// **************************************
		// Include the needed classes and helpers
		// **************************************

		if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);//TODO a vérifier

		// ***
		// *** Create the item model object
		// ***

		if($type == 'category')
		{
			$name   = 'com_content.category.' .$id ;
			$title2 = $title ;
		}
		else if($type == 'field')
		{
			$name   = 'com_flexicontent.field.'.$id ;
			$title2 = 'label' ;
		}
		else if($type == 'item')
		{
			$name   = 'com_content.article.'.$id ;
			$title2 = $name ;
		}
		else if($type == 'type')
		{
			$name   = 'com_flexicontent.type.'.$id ;
			$title2 = $title ;
		}
		else
		{
			return false;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('name')))
		->from($db->quoteName('#__assets'))
		->where($db->quoteName('name') . ' = '. $db->quote($name));
		$db->setQuery($query);
		$namelike =  $db->loadResult();

		if(!$namelike)
		{
			$level = self::getLevel($parent_id);
			$lft   = self::getLft($parent_id);
			$rgt   = self::getRgt($parent_id);
			if($rgt == false || $lft == false)
			{
				if($type == 'category')
				{
					self::deleteCategory($title, $alias);
				}
				else if($type == 'field')
				{
					self::deleteField($title, $alias);
				}
				else if($type == 'item')
				{
					self::deleteItem($title, $alias);
				}
				else if($type == 'type')
				{
					self::deleteType($title, $alias);
				}
				return false;
			}
			$rules = '{}';

			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);

				// Insert columns.
				$columns = array('parent_id', 'lft', 'rgt', 'level', 'name', 'title', 'rules');

				// Insert values.
				$values = array($db->Quote($parent_id), $db->Quote($lft), $db->Quote($rgt), $db->Quote($level), $db->Quote($name), $db->Quote($title2), $db->Quote($rules));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__assets'))
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
				if($type == 'category')
				{
					self::deleteCategory($title, $alias);
				}
				else if($type == 'field')
				{
					self::deleteField($title, $alias);
				}
				else if($type == 'item')
				{
					self::deleteItem($title, $alias);
				}
				else if($type == 'type')
				{
					self::deleteType($title, $alias);
				}
				return false;
			}
			return $name ;
		}
		else
		{
			if($ressaie == true)
			{
				$query = $db->getQuery(true);
				$conditions = array(
						$db->quoteName('name') . ' = '. $db->Quote($name)
				);
				$query->delete($db->quoteName('#__assets'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->query($query);
				self::addAsset($id, $parent_id, $type, $title, $alias, false);
			}
			else
			{
				return false ;
			}
		}
    }

	public function getExtension($asset_parent_id, $asset_level)
	{
		$db              = JFactory::getDBO();
		$timestamp_debut = microtime(true);

		while($asset_level > 1)
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('parent_id', 'level', 'name')))
			->from($db->quoteName('#__assets'))
			->where($db->quoteName('id') . " = ".$db->quote($asset_parent_id));
			$db->setQuery($query);
			$data            =  $db->loadAssoc();
			$asset_name      =  $data['name'];
			$asset_level     =  $data['level'];
			$asset_parent_id =  $data['parent_id'];
			$timestamp_fin   =  microtime(true);
			$difference_ms   =  $timestamp_fin - $timestamp_debut;
			if($difference_ms > 5)
			{
				return 	'com_content';
				die;
			}
		}
		return $asset_name;
	}

	public function deleteCategory($title, $alias)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__categories'))
		->where($db->quoteName('title') . ' = '. $db->Quote($title). ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
		$db->setQuery($query);
		$id =  $db->loadResult();
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__categories'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$name       = 'com_content.category.' .$id ;
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('name') . ' = '. $db->Quote($name)
		);
		$query->delete($db->quoteName('#__assets'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
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
}