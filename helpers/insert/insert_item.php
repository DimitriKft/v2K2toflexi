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
class InsertItemHelper
{
	/**
	 * gets a list of the actions that can be performed.
	 * 
	 * @return 	JObject
	 * @since	1.0
	 */



	public function item($task, $sql)
	{
		//initialise variables.
		$db                         = JFactory::getDBO();
		$user                       = JFactory::getUser();
		$dateTime                   = date_create('now')->format('Y-m-d H:i:s');
		$sql                        = json_decode(json_encode($sql),    true);
		$params                     = JComponentHelper::getParams('com_k2toflexi');
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
		
		   if($task == 'insertItem')
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


    public function recoveredItem($sql)
	{
		$db    = JFactory::getDBO();
		$user  = JFactory::getUser();
		if($sql == '')
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array( 'id', 'title', 'alias', 'catid', 'published', 'introtext', 'fulltext', 'video',
					'gallery', 'extra_fields', 'extra_fields_search', 'created', 'created_by', 'created_by_alias', 'checked_out',
					'checked_out_time', 'modified', 'modified_by', 'publish_up', 'publish_down', 'trash', 'access', 'ordering',
					'featured', 'featured_ordering', 'image_caption', 'image_credits', 'video_caption', 'video_credits', 'hits',
					'params', 'metadesc', 'metadata', 'metakey', 'plugins', 'language')))
			->from($db->quoteName('#__k2toflexi_items'));
			$db->setQuery($query);
			$sql  =  $db->loadAssocList();
		}
		foreach($sql as $elem =>$row)
		{
			$message      = self::insertItem($row);
			$title        = $row['title'];
			$alias        = $row['alias'];
			$params       = JComponentHelper::getParams('com_k2toflexi');
			$replaceflexi = $params['replaceflexi'];
			if($message == "failed")
			{
				self::deleteItem($title, $alias);
				$message = self::insertItem($row);
			}
			else if($message == "exist" && $replaceflexi == 1)
			{
				self::deleteItem($title, $alias);
				$message = self::insertItem($row);
			}
			else if($message == "again")
			{
				unset($sql[$elem]);
				$valuesjsons = self::insertItem($sql);
				return $valuesjsons;
			}
			unset($sql[$elem]);
			if($sql == array())
			{
				$valuesjsons = json_encode(array('task' => false, 'sql' => '', 'message' => $message, 'type' => 'Item', 'name' => $title), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
			else{
				$valuesjsons = json_encode(array('task' => 'insertItem', 'sql' => $sql, 'message' => $message, 'type' => 'Item', 'name' => $title), JSON_NUMERIC_CHECK);
				return $valuesjsons;
				die;
			}
		}
		$valuesjsons = json_encode(array('task' => false, 'sql' => '', 'message' => 'noexist', 'type' => 'Item', 'name' => "???"), JSON_NUMERIC_CHECK);
		return $valuesjsons;
		die;
    }
    


    public function insertItem($row)
	{
		$db                  = JFactory::getDBO();
		$user                = JFactory::getUser();
		$dateTime            = date_create('now')->format('Y-m-d H:i:s');
		$title               = $row['title'];
		$alias               = $row['alias'];
		$catidk2             = $row['catid'];
		$introtext           = $row['introtext'];
		$language            = $row['language'];
		$extra_fields        = $row['extra_fields'];
		$published           = $row['published'];
		$fulltext            = $row['fulltext'];
		$video               = $row['video'];
		$gallery             = $row['gallery'];
		$extra_fields_search = $row['extra_fields_search'];
		$created             = $row['created'];
		$created_by          = $row['created_by'];
		$created_by_alias    = $row['created_by_alias'];
		$checked_out         = $row['checked_out'];
		$checked_out_time    = $row['checked_out_time'];
		$modified            = $row['modified'];
		$modified_by         = $row['modified_by'];
		$publish_up          = $row['publish_up'];
		$publish_down        = $row['publish_down'];
		$trash               = $row['trash'];
		$access              = $row['access'];
		$featured            = $row['featured'];
		$featured_ordering   = $row['featured_ordering'];
		$image_caption       = $row['image_caption'];
		$image_credits       = $row['image_credits'];
		$video_caption       = $row['video_caption'];
		$video_credits       = $row['video_credits'];
		$hits                = $row['hits'];
		$params              = $row['params'];
		$metadesc            = $row['metadesc'];
		$metadata            = $row['metadata'];
		$metakey             = $row['metakey'];
		$plugins             = $row['plugins'];
		$id                  = $row['id'];
		$state               = $row['state'];

		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('name')))
			->from($db->quoteName('#__k2_categories'))
			->where($db->quoteName('id') . " = ".$db->quote($catidk2));
		$db->setQuery($query);
		$catname =  $db->loadResult();

		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__categories'))
		->where($db->quoteName('title') . " = ".$db->quote($catname));
		$db->setQuery($query);
		$catid =  $db->loadResult();

		if ($title == "" && $alias == null)
		{
			return "again";
		}
		$message = self::addItem($id, $catid, $catidk2, $dateTime, $title, $alias, $introtext, $language,
				$featured, $published, $fulltext, $video, $gallery, $extra_fields_search,
				$created, $created_by, $created_by_alias, $checked_out, $checked_out_time, $modified, $modified_by,
				$publish_up, $publish_down, $trash, $access, $featured, $featured_ordering,
				$image_caption, $image_credits, $video_caption, $video_credits, $hits, $params,
				$metadesc, $metadata, $metakey, $extra_fields);
		return $message;
    }
    

    public function addItem($id, $catid, $catidk2, $dateTime, $title, $alias, $introtext, $language, $featured, $published, $fulltext,
					$video, $gallery, $extra_fields_search, $created, $created_by, $created_by_alias, $checked_out, $checked_out_time, $modified,
					$modified_by, $publish_up, $publish_down, $trash, $access, $empty, $featured_ordering, $image_caption,
					$image_credits, $video_caption, $video_credits, $hits, $params, $metadesc, $metadata, $metakey, $extra_fields)
	{
		// record_ID is the id of the item that you want to load, or set it to zero for new item

		// **************************************
		// Include the needed classes and helpers
		// **************************************
		if (!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

		// ***
		// *** Create the item model object
		// ***

		$db = JFactory::getDBO();
		if($trash == 1)
		{
			$state = (-2);
		}
		else
		{
			$state = 1;
		}
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('title')))
		->from($db->quoteName('#__content'))
		->where($db->quoteName('title') . ' = '. $db->quote($title). ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
		$db->setQuery($query);
		$namelike =  $db->loadResult();

		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('title')))
		->from($db->quoteName('#__flexicontent_items_tmp'))
		->where($db->quoteName('title') . ' = '. $db->quote($title). ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
		$db->setQuery($query);
		$extlike =  $db->loadResult();

		if (!$namelike || !$extlike)
		{
			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('extraFieldsGroup')))
			->from($db->quoteName('#__k2_categories'))
			->where($db->quoteName('id') . ' = '. $db->quote($catidk2));
			$db->setQuery($query);
			$type_id_k2 =  $db->loadResult();

			if ($type_id_k2 == 0)
			{
				$type_id = 1;
				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('alias')))
				->from($db->quoteName('#__flexicontent_types'))
				->where($db->quoteName('id') . ' = '. $db->quote($type_id));
				$db->setQuery($query);
				$type_name =  $db->loadResult();
			}
			else
			{
				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('name')))
				->from($db->quoteName('#__k2_extra_fields_groups'))
				->where($db->quoteName('id') . ' = '. $db->quote($type_id_k2));
				$db->setQuery($query);
				$type_name =  $db->loadResult();

				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id')))
				->from($db->quoteName('#__flexicontent_types'))
				->where($db->quoteName('alias') . ' = '. $db->quote(str_replace(' ','-',$type_name)));
				$db->setQuery($query);
				$type_id =  $db->loadResult();
			}
			$lang_parent_id = 0;
			$query = $db->getQuery(true);
			$q2    = $db->getQuery(true);
			$query
				->select($db->quoteName(array('id')))
				->from($db->quoteName('#__content'))
			    ->union($q2
				->select($db->quoteName(array('item_id')))
				->from($db->quoteName('#__flexicontent_items_ext'))
			);

			$db->setQuery($query);
			$item_id =  $db->loadColumn();
			$item_id =  max($item_id) + 1;
	

			try
			{
				$db->transactionStart();
				$query   = $db->getQuery(true);
				$columns = array('item_id', 'type_id', 'language', 'lang_parent_id', 'sub_items', 'sub_categories', 'related_items', 'search_index');

				// Insert values.
				$values = array($db->quote($item_id), $db->quote($type_id), $db->quote($language), $db->quote($lang_parent_id),
						$db->quote(''), $db->quote(''), $db->quote(''), $db->quote($title.' | '.$alias.' | '.$introtext.' | '.$fulltext.' | '.$created.' | '.$created_by.' | '.
						$modified.' | '.$modified_by.' | '.$type_name));
						
				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_ext'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}
			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);
				// Insert columns.
				$columns = array('id', 'title', 'alias', 'state', 'catid', 'created', 'created_by', 'modified', 'modified_by', 'publish_up', 'publish_down', 'version', 'ordering', 'access', 'hits', 'featured', 'language', 'type_id', 'lang_parent_id');

				// Insert values.
				$values = array($db->quote($item_id), $db->quote($title), $db->quote($alias), $db->quote($published), $db->quote($catid),
					      	    $db->quote($created), $db->quote($created_by), $db->quote($modified), $db->quote($modified_by),
						        $db->quote($publish_up), $db->quote($publish_down), $db->quote(1), $db->quote(0), $db->quote($access),
						        $db->quote($hits), $db->quote($featured), $db->quote($language), $db->quote($type_id), $db->quote($lang_parent_id));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_tmp'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));

				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch (Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteItem($title, $alias);
				return "failed" ;
			}
			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);
				$columns = array('item_id', 'version_id', 'comment', 'created', 'created_by', 'state');

				// Insert values.
				$values = array($item_id, 1, $db->quote(''), $db->quote($created), $db->quote($created_by), $db->quote(0));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			$query = $db->getQuery(true);
			$query
			->select($db->quoteName(array('asset_id')))
			->from($db->quoteName('#__categories'))
			->where($db->quoteName('id') . ' = '. $db->quote($catid));
			$db->setQuery($query);
			$parent_id =  $db->loadResult();
			$asset_name = self::addAsset($item_id, $parent_id, 'item', $title, $alias, true);

			if($asset_name == false)
			{
				$query      = $db->getQuery(true);
				$conditions = array(
						$db->quoteName('id') . ' = '. $db->Quote($item_id)
				);
				$query->delete($db->quoteName('#__flexicontent_items_tmp'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->query($query);
				$query      = $db->getQuery(true);
				$conditions = array($db->quoteName('item_id') . ' = '. $db->Quote($item_id));
				$query->delete($db->quoteName('#__flexicontent_versions'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->query($query);
				$query      = $db->getQuery(true);
				$conditions = array($db->quoteName('item_id') . ' = '. $db->Quote($item_id));
				$query->delete($db->quoteName('#__flexicontent_items_ext'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->query($query);
				$query = $db->getQuery(true);
				$conditions = array($db->quoteName('item_id') . ' = '. $db->Quote($item_id));
				$query->delete($db->quoteName('#__flexicontent_items_versions'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->query($query);
				return "failed" ;
			}
			else
			{
				$query    = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id')))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('name') . " = ".$db->quote($asset_name));
				$db->setQuery($query);
				$asset_id =  $db->loadResult();
				$urls = json_encode(array('urla' => false, 'urlatext' => '', 'urlb' => false, 'urlbtext' => '', 'targetb' => '', 'urlc' => false, 'urlctext' => '', 'targetc' => ''), JSON_NUMERIC_CHECK);
				$attribs = json_encode(array('show_print_icon' => '', 'show_editbutton' => '', 'show_deletebutton' => '',
						'show_state_icon' => '', 'show_title' => '', 'show_intro' => '', 'readmore' => '', 'comments' => '',
						'automatic_pathways' => '', 'view_extra_css_fe' => '', 'view_extra_js_fe' => '', 'microdata_itemtype' => '',
						'microdata_itemtype' => '', 'override_title' => '', 'custom_ititle' => '', 'addcat_title' => '',
						'add_canonical' => '', 'ilayout' => ''), JSON_NUMERIC_CHECK);
				$robots     = '';
				$author     = '';
				$rights     = ''; //ROBOT
				$xreference = '';
				$sourceImage = JPATH_ROOT.'/images/k2/items/'.$alias.'.jpg';

				if(JFile::exists($sourceImage))
				{
					$images = json_encode(array('image_intro' => 'images\/k2\/items\/'.$alias.'.jpg', 'float_intro' => '', 'image_intro_alt' => $image_credits,
							 'image_intro_caption' => $image_caption, 'image_fulltext' => 'images\/k2\/items\/'.$alias.'.jpg', 'float_fulltext' => '',
							 'image_fulltext_alt' => $image_credits, 'image_fulltext_caption' => $image_caption), JSON_NUMERIC_CHECK);
				}
				else
				{
					$images = json_encode(array('image_intro' => '', 'image_intro_alt' => '', 'image_intro_caption' => '', 'image_fulltext' => '', 'float_fulltext' => '', 'image_fulltext_alt' => '', 'image_fulltext_caption' => ''), JSON_NUMERIC_CHECK);;
				}

				$metadata = json_encode(array('robots' => $robots, 'xreference' => $xreference, 'author' => $author, 'rights' => $rights), JSON_NUMERIC_CHECK);
				try
				{
					$db->transactionStart();
					$query = $db->getQuery(true);
					$columns = array('id', 'asset_id', 'title', 'alias', 'introtext', 'fulltext', 'state', 'catid',
							         'created', 'created_by', 'created_by_alias', 'modified', 'modified_by', 'checked_out',
							         'checked_out_time', 'publish_up', 'publish_down', 'images', 'urls', 'attribs', 'version',
							         'ordering', 'metakey', 'metadesc', 'access', 'hits', 'metadata', 'featured', 'language', 'xreference');

					// Insert values.
					$values = array($db->quote($item_id), $db->quote($asset_id), $db->quote($title), $db->quote($alias), $db->quote($introtext), $db->quote($fulltext),
							$db->quote($published), $catid, $db->quote($created), $db->quote($created_by), $db->quote($created_by_alias), $db->quote($modified),
							$db->quote($modified_by), $db->quote($checked_out), $db->quote($checked_out_time), $db->quote($publish_up),
							$db->quote($publish_down), $db->quote($images), $db->quote($urls), $db->quote($attribs), 1, 0,
							$db->quote($metakey), $db->quote($metadesc), $db->quote($access), $db->quote($hits), $db->quote($metadata),
							$db->quote($featured), $db->quote($language), $db->quote($xreference));

					// Prepare the insert query.
					$query
					->insert($db->quoteName('#__content'))
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
					self::deleteItem($title, $alias);
					return "failed" ;
				}
				try
				{
					$db->transactionStart();
					$query   = $db->getQuery(true);
					$columns = array('catid', 'itemid', 'ordering');

					// Insert values.
					$values  = array($catid, $item_id, 0);

					// Prepare the insert query.
					$query
					->insert($db->quoteName('#__flexicontent_cats_item_relations'))
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
					self::deleteItem($title, $alias);
					return "failed" ;
				}
				$message = self::addItem2($item_id, $introtext, $fulltext, $created, $created_by, $modified, $modified_by, $title,
						                  $hits, $type_id, 1, 'button', $catid, $id, $extra_fields_search, $extra_fields,
						                  $alias, $catid, $metadesc, $metakey, $metadata, $attribs, $urls, $images);
				return $message ;
			}
		}
		else
		{
			$query = $db->getQuery(true);
			$query
				->select($db->quoteName(array('state')))
				->from($db->quoteName('#__flexicontent_items_tmp'))
				->where($db->quoteName('title') . ' = '. $db->quote($title). ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
			$db->setQuery($query);
			$state =  $db->loadResult();

			if($state == -2)
			{
				try
				{
					$db->transactionStart();
					$query  = $db->getQuery(true);
					// Fields to update.
					$fields = array($db->quoteName('state') . ' = ' . $db->quote(1),);
					// Conditions for which records should be updated.
					$conditions = array($db->quoteName('title') . ' = '. $db->quote($title));
					$query->update($db->quoteName('#__flexicontent_items_tmp'))->set($fields)->where($conditions);
					$db->setQuery($query);
					$result = $db->execute();
					$db->transactionCommit();
					return "exist";
				}
				catch(Exception $e)
				{
					// catch any database errors.
					$db->transactionRollback();
					return "exist" ;
				}
			}
			else
			{
				return "exist";
			}
			return "exist";
		}
		return "exist";
    }
    
    public function addItem2($item_id, $introtext, $fulltext, $created, $created_by, $modified, $modifiedby, $title, $hits, $type,
						     $version, $favourites, $categories, $item_id_k2, $extra_fields_search, $extra_fields,
							 $alias, $catid, $metadesc, $metakey, $metadata, $attribs, $urls, $images)
	{
		if (!defined('DS')) define('DS',DIRECTORY_SEPARATOR);// TODO a vérifier

		// ***
		// *** Create the item model object
		// ***

		$db = JFactory::getDBO();
			try
			{
				$db->transactionStart();
				$query  = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 1, $item_id, 1, 1, $db->quote('<p>'.$introtext.'</p><hr id="system-readmore" /><p>'.$fulltext.'</p>'));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));

				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch (Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 2, $item_id, 1, 1, $db->quote($created));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query   = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values  = array(1, 3, $item_id, 1, 1, $db->quote($created_by));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 4, $item_id, 1, 1, $db->quote($modified));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query   = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 5, $item_id, 1, 1, $db->quote($modifiedby));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 6, $item_id, 1, 1, $db->quote($title));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query   = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 7, $item_id, 1, 1, $db->quote($hits));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query   = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values  = array(1, 8, $item_id, 1, 1, $db->quote($type));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));

				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch (Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query   = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 9, $item_id, 1, 1, $db->quote($version));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));

				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch (Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query   = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 12, $item_id, 1, 1, $db->quote($favourites));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));

				$db->setQuery($query);
				$result = $db->execute();
				$db->transactionCommit();
			}
			catch (Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
				self::deleteItem($title, $alias);
				return "failed" ;
			}

			try
			{
				$db->transactionStart();
				$query   = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, 13, $item_id, 1, 1, $db->quote($categories));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}
 
			try
			{
				$db->transactionStart();
				$fieldData = serialize(array('alias'  => $alias, 'catid'  =>  $catid, 'metadesc' => $metadesc, 'metakey' => $metakey, 'metadata' => $metadata, 'attribs' => $attribs, 'urls' => $urls, 'images' => $images));

				$query = $db->getQuery(true);
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Insert values.
				$values = array(1, (-2), $item_id, 1, 1, $db->quote($fieldData));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
				self::deleteItem($title, $alias);
				return "failed" ;
			}
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('tagID')))
		->from($db->quoteName('#__k2_tags_xref'))
		->where($db->quoteName('itemID') . ' = '. $db->quote($item_id_k2));

		$db->setQuery($query);
		$tags =  $db->loadColumn();
		$value_order = 0;

		if($tags)
		{
			foreach($tags as $tag)
			{
				$value_order = $value_order + 1;
				$query = $db->getQuery(true);
				$query
					->select($db->quoteName(array('name')))
					->from($db->quoteName('#__k2_tags'))
					->where($db->quoteName('id') . " = ".$db->quote($tag));

				$db->setQuery($query);
				$tag_name =  $db->loadResult();

				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id')))
				->from($db->quoteName('#__flexicontent_tags'))
				->where($db->quoteName('name') . " = ".$db->quote($tag_name));
				$db->setQuery($query);
				$tag_id =  $db->loadResult();

				try
				{
					$db->transactionStart();
					$query = $db->getQuery(true);
					$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

					// Insert values.
					$values = array(1, 14, $item_id, $value_order, 1, $db->quote($tag_id));

					// Prepare the insert query.
					$query
					->insert($db->quoteName('#__flexicontent_items_versions'))
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
					self::deleteItem($title, $alias);
					return "failed" ;
				}

				try
				{
					$db->transactionStart();
					$query = $db->getQuery(true);
					$columns = array('tid', 'itemid');

					// Insert values.
					$values = array($db->quote($tag_id), $db->quote($item_id));

					// Prepare the insert query.
					$query
					->insert($db->quoteName('#__flexicontent_tags_item_relations'))
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
					self::deleteItem($title, $alias);
					return "failed" ;
				}
			}
		}
		if($extra_fields){
			$fields_json = json_decode($extra_fields);
			if(isset($fields_json))
			{
			foreach($fields_json as $fields_value)
			{

				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('name', 'value')))
				->from($db->quoteName('#__k2_extra_fields'))
				->where($db->quoteName('id') . " = ".$db->quote($fields_value->id));

				$db->setQuery($query);
				$field_k2_assoc =  $db->loadAssoc();
				$field_name     = $field_k2_assoc['name'];
				$value          = $field_k2_assoc['value'];

				$query = $db->getQuery(true);
				$query
				->select($db->quoteName(array('id', 'field_type')))
				->from($db->quoteName('#__flexicontent_fields'))
				->where($db->quoteName('label') . " = ".$db->quote($field_name));

				$db->setQuery($query);
				$field_assoc =  $db->loadAssoc();
				$field_id    = $field_assoc['id'];
				$field_type  = $field_assoc['field_type'];

				if(!$field_assoc)
				{
				}
				else
				{
					if($field_type == 'radio'|| $field_type == 'select')
					{
						$values = json_decode($value);
						$val    = $values[0]->{'value'};
						if($val == $fields_value->value)
						{
							$field_val = '\r\ n'.$fields_value->value;
						}
						else
						{
							$field_val = 'n'.$fields_value->value;
						}
						try
						{
							$db->transactionStart();
							$query   = $db->getQuery(true);
							$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');
							// Insert values.
							$values  = array(1, $db->quote($field_id), $item_id, 1, 1, $db->quote($field_val));

							// Prepare the insert query.
							$query
							->insert($db->quoteName('#__flexicontent_items_versions'))
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
							self::deleteItem($title, $alias);
							return "failed";
						}
						try
						{
							$db->transactionStart();
							$query   = $db->getQuery(true);
							$columns = array('field_id', 'item_id', 'valueorder', 'suborder', 'value', 'value_integer', 'value_decimal', 'value_datetime');

							// Insert values.
							$values = array($field_id, $item_id, 1, 1, $db->quote($field_val), $db->quote(0), $db->quote(0.000000000000000), $db->quote(NULL));

							// Prepare the insert query.
							$query
							->insert($db->quoteName('#__flexicontent_fields_item_relations'))
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
							self::deleteItem($title, $alias);
							return "failed" ;
						}
					}
					else if($field_type == 'image')
					{
						$values     = json_decode($value);
						$val        = $fields_value->value;
						$name_image = substr($val, strrpos($val, '/')+1);
						$params     = JComponentHelper::getParams('com_k2toflexi');
						$imageType  = $params['imageType'];

						if(empty($imageType) || $imageType == 1)
						{
							JFolder::create( JPATH_ROOT.'/images/stories/flexicontent/item_'.$item_id.'_field_'.$field_id.'/original');
							$field_val   = serialize(array('originalname'  => $name_image, 'existingname'  =>  '', 'desc' => ''));
							$valimage    = str_replace('\\','/',$val);
							$sourceImage = JPATH_ROOT.'/'.$valimage;

							if(JFile::exists($sourceImage))
							{
								JFile::copy($sourceImage, JPATH_ROOT.'/images/stories/flexicontent/item_'.$item_id.'_field_'.$field_id.'/original/'.$name_image);

								try
								{
									$db->transactionStart();
									$query = $db->getQuery(true);
									$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

									// Insert values.
									$values = array(1, $db->quote($field_id), $item_id, 1, 1, $db->quote($field_val));

									// Prepare the insert query.
									$query
									->insert($db->quoteName('#__flexicontent_items_versions'))
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
									self::deleteItem($title, $alias);
									return "failed" ;
								}
								try
								{
									$db->transactionStart();
									$query   = $db->getQuery(true);
									$columns = array('field_id', 'item_id', 'valueorder', 'suborder', 'value', 'value_integer', 'value_decimal', 'value_datetime');

									// Insert values.
									$values = array($field_id, $item_id, 1, 1, $db->quote($field_val), $db->quote(0), $db->quote(0.000000000000000), $db->quote(NULL));

									// Prepare the insert query.
									$query
									->insert($db->quoteName('#__flexicontent_fields_item_relations'))
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
									self::deleteItem($title, $alias);
									return "failed" ;
								}
							}
						}
						else
						{
							$valimage  = str_replace('\\','/',$val);
							$field_val = serialize(array('originalname'  => $valimage, 'existingname'  =>  '', 'desc' => ''));
							try
							{
								$db->transactionStart();
								$query = $db->getQuery(true);
								$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

								// Insert values.
								$values = array(1, $db->quote($field_id), $item_id, 1, 1, $db->quote($field_val));

								// Prepare the insert query.
								$query
								->insert($db->quoteName('#__flexicontent_items_versions'))
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
								self::deleteItem($title, $alias);
								return "failed" ;
							}
							try
							{
								$db->transactionStart();
								$query = $db->getQuery(true);
								$columns = array('field_id', 'item_id', 'valueorder', 'suborder', 'value', 'value_integer', 'value_decimal', 'value_datetime');

								// Insert values.
								$values = array($field_id, $item_id, 1, 1, $db->quote($field_val), $db->quote(0), $db->quote(0.000000000000000), $db->quote(NULL));

								// Prepare the insert query.
								$query
								->insert($db->quoteName('#__flexicontent_fields_item_relations'))
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
								self::deleteItem($title, $alias);
								return "failed" ;
							}
						}
					}
					else if($field_type == 'selectmultiple')
					 {
						$values       = json_decode($value);
						$val          = $values[0]->{'value'};
						$field_values = $fields_value->value;
						$valueorder   = 0;
						foreach($field_values as $fields_v)
						{
							$valueorder = $valueorder + 1;

							if($val == $fields_v)
							{
								$field_val = '\r\ n'.$fields_v;
							}
							else
							{
								$field_val = 'n'.$fields_v;
							}
							try
							{
								$db->transactionStart();
								$query = $db->getQuery(true);

								// Insert values.
								$values = array(1, $db->quote($field_id), $item_id, $valueorder, 1, $db->quote($field_val));
								$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

								// Prepare the insert query.
								$query
								->insert($db->quoteName('#__flexicontent_items_versions'))
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
								self::deleteItem($title, $alias);
								return "failed" ;
							}
							try
							{
								$db->transactionStart();
								$query   = $db->getQuery(true);
								$columns = array('field_id', 'item_id', 'valueorder', 'suborder', 'value', 'value_integer', 'value_decimal', 'value_datetime');

								// Insert values.
								$values = array($field_id, $item_id, $valueorder, 1, $db->quote($field_val), $db->quote(0), $db->quote(0.000000000000000), $db->quote(NULL));

								// Prepare the insert query.
								$query
								->insert($db->quoteName('#__flexicontent_fields_item_relations'))
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
								self::deleteItem($title, $alias);
								return "failed" ;
							}
						}
					}
					else if($field_type == 'weblink')
					{
						$values       = json_decode($value);
						$val          = $values[0]->{'value'};
						$field_values = $fields_value->value;
						$field_val    = serialize(array('link'  => $field_values[1], 'title'  =>  '', 'linktext' => $field_values[0], 'class' => '', 'id' => '', 'target' => '', 'hits' => 0));
						try
						{
							$db->transactionStart();
							$query = $db->getQuery(true);

							// Insert values.
							$values = array(1, $db->quote($field_id), $item_id, 1, 1, $db->quote($field_val));
							$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

							// Prepare the insert query.
							$query
							->insert($db->quoteName('#__flexicontent_items_versions'))
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
							self::deleteItem($title, $alias);
							return "failed" ;
						}
						try
						{
							$db->transactionStart();
							$query   = $db->getQuery(true);
							$columns = array('field_id', 'item_id', 'valueorder', 'suborder', 'value', 'value_integer', 'value_decimal', 'value_datetime');

							// Insert values.
							$values = array($field_id, $item_id, 1, 1, $db->quote($field_val), $db->quote(0), $db->quote(0.000000000000000), $db->quote(NULL));

							// Prepare the insert query.
							$query
							->insert($db->quoteName('#__flexicontent_fields_item_relations'))
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
							self::deleteItem($title, $alias);
							return "failed" ;
						}
					}
					else
					{
							try
							{
								$db->transactionStart();
								$query = $db->getQuery(true);
								$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

								// Insert values.
								$values = array(1, $db->quote($field_id), $item_id, 1, 1, $db->quote($fields_value->value));

								// Prepare the insert query.
								$query
								->insert($db->quoteName('#__flexicontent_items_versions'))
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
								self::deleteItem($title, $alias);
								return "failed" ;
							}
							try
							{
								$db->transactionStart();
								$query   = $db->getQuery(true);
								$columns = array('field_id', 'item_id', 'valueorder', 'suborder', 'value', 'value_integer', 'value_decimal', 'value_datetime');

								// Insert values.
								$values = array($field_id, $item_id, 1, 1, $db->quote($fields_value->value), $db->quote(0), $db->quote(0.000000000000000), $db->quote(NULL));

								// Prepare the insert query.
								$query
								->insert($db->quoteName('#__flexicontent_fields_item_relations'))
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
								self::deleteItem($title, $alias);
								return "failed" ;
							}
						}
					}
				}
			}
		}

		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__flexicontent_fields'))
		->where($db->quoteName('name') . ' = '. $db->Quote('k2attachment') . ' AND ' . $db->quoteName('label') . ' = '.  $db->quote('k2attachment'));
		$db->setQuery($query);
		$k2attachment =  $db->loadResult();

		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('filename', 'title')))
		->from($db->quoteName('#__k2_attachments'))
		->where($db->quoteName('itemID') . ' = '. $db->Quote($item_id_k2));

		$db->setQuery($query);
		$sql        =  $db->loadAssocList();
		$valueorder = 0;

		foreach($sql as $row)
		{
			$valueorder = $valueorder + 1;
			$title      = $row['title'];
			$filename   = $row['filename'];
			$db         = JFactory::getDBO();
			$query      = $db->getQuery(true);
			$query
			->select($db->quoteName(array('id')))
			->from($db->quoteName('#__flexicontent_files'))
			->where($db->quoteName('filename') . ' = '. $db->Quote($filename). ' AND ' . $db->quoteName('altname') . ' = '.  $db->quote($title));
			$db->setQuery($query);
			$file_id    =  $db->loadResult();
			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);

				// Insert values.
				$values = array(1, $db->quote($k2attachment), $item_id, $valueorder, 1, $db->quote($file_id));
				$columns = array('version', 'field_id', 'item_id', 'valueorder', 'suborder', 'value');

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_items_versions'))
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
			}
			try
			{
				$db->transactionStart();
				$query = $db->getQuery(true);
				$columns = array('field_id', 'item_id', 'valueorder', 'suborder', 'value', 'value_integer', 'value_decimal', 'value_datetime');

				// Insert values.
				$values = array($k2attachment, $item_id, $valueorder, 1, $db->quote($file_id), $db->quote($file_id), $db->quote((0.000000000000000)+$file_id), $db->quote(NULL));

				// Prepare the insert query.
				$query
				->insert($db->quoteName('#__flexicontent_fields_item_relations'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));

				$db->setQuery($query);
				$result = $db->execute();

				$db->transactionCommit();
			}
			catch (Exception $e)
			{
				// catch any database errors.
				$db->transactionRollback();
			}
		}


		$query      = $db->getQuery(true);
		$conditions = array($db->quoteName('field_id') . ' = 0');
		$query->delete($db->quoteName('#__flexicontent_fields_type_relations'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		return "success";
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
    

    public function getLevel($parent_id)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('level')))
		->from($db->quoteName('#__assets'))
		->where($db->quoteName('id') . ' = '. $db->quote($parent_id));
		$db->setQuery($query);
		$level =  $db->loadResult() + 1 ;
		return $level;
    }
    


    public function getLft($parent_id)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);;
		$q2    = $db->getQuery(true);
		$query
		->select($db->quoteName(array('lft')))
		->from($db->quoteName('#__assets'))
		->where($db->quoteName('id') . ' = '. $db->quote($parent_id));
		$db->setQuery($query);
		$lft =  $db->loadResult();

		while($lft)
		{
			$query = $db->getQuery(true);
			$q2    = $db->getQuery(true);
			$lft   = $lft + 1;
			$lft2  = $lft;
			$query
				->select($db->quoteName(array('lft')))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('lft') . ' = '. $db->quote($lft))
				->union($q2
				->select($db->quoteName(array('rgt')))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('rgt') . ' = '. $db->quote($lft))
				);
			$db->setQuery($query);
			$lft =  $db->loadResult();
		}

		if(isset($lft2))
		{
			return $lft2;
		}
		else if(isset ($lft))
		{
			return $lft - 1;
		}
		else
		{
			$query = $db->getQuery(true);
			$q2    = $db->getQuery(true);
			$query
					->select($db->quoteName(array('lft')))
					->from($db->quoteName('#__assets'))
					->where($db->quoteName('lft') . ' = '. $db->quote($lft))
				->union($q2
					->select($db->quoteName(array('rgt')))
					->from($db->quoteName('#__assets'))
					->where($db->quoteName('rgt') . ' = '. $db->quote($lft))
			);
			$db->setQuery($query);
			$lft =  $db->loadAssoc();
			$lft = max($lft)+1;
			return $lft ;

			if(isset($lft))
			{
				return $lft;
			}
			else
			{
				return false;
			}
		}
		return $lft2 ;
	}


    public function getRgt($parent_id)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$q2    = $db->getQuery(true);
		$query
		->select($db->quoteName(array('lft')))
		->from($db->quoteName('#__assets'))
		->where($db->quoteName('id') . ' = '. $db->quote($parent_id));
		$db->setQuery($query);
		$lft =  $db->loadResult();

		while($lft)
		{
			$query = $db->getQuery(true);
			$q2    = $db->getQuery(true);
			$lft   = $lft + 1;
			$rgt   = $lft;
			$query
				->select($db->quoteName(array('lft')))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('lft') . ' = '. $db->quote($lft))
				->union($q2
				->select($db->quoteName(array('rgt')))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('rgt') . ' = '. $db->quote($lft))
				);
			$db->setQuery($query);
			$lft =  $db->loadResult();
		}

		if(isset($rgt))
		{
			$lft = $rgt;

			while($lft)
			{
				$query = $db->getQuery(true);
				$q2    = $db->getQuery(true);
				$lft   = $lft + 2;
				$rgt   = $lft;
				$query
					->select($db->quoteName(array('lft')))
					->from($db->quoteName('#__assets'))
					->where($db->quoteName('lft') . ' = '. $db->quote($lft))
					->union($q2
					->select($db->quoteName(array('rgt')))
					->from($db->quoteName('#__assets'))
					->where($db->quoteName('rgt') . ' = '. $db->quote($lft))
					);
				$db->setQuery($query);
				$lft =  $db->loadResult();
			}

			if(isset($rgt))
			{
				return $rgt;
			}
			else
			{
				$query = $db->getQuery(true);
				$q2    = $db->getQuery(true);
				$query
				->select($db->quoteName(array('lft')))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('lft') . ' = '. $db->quote($lft))
				->union($q2
					->select($db->quoteName(array('rgt')))
					->from($db->quoteName('#__assets'))
					->where($db->quoteName('rgt') . ' = '. $db->quote($lft))
				);
				$db->setQuery($query);
				$lft =  $db->loadAssoc();
				$lft = max($lft)+2;
				return $lft ;

				if(isset($lft))
				{
					return $lft;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			$query = $db->getQuery(true);
			$q2    = $db->getQuery(true);
			$query
			->select($db->quoteName(array('lft')))
			->from($db->quoteName('#__assets'))
			->where($db->quoteName('lft') . ' = '. $db->quote($lft))
			->union($q2
					->select($db->quoteName(array('rgt')))
					->from($db->quoteName('#__assets'))
					->where($db->quoteName('rgt') . ' = '. $db->quote($lft))
			);
			$db->setQuery($query);
			$lft =  $db->loadAssoc();
			$lft = max($lft)+2;
			return $lft ;
			if (isset($lft)) {return $lft;}
			else {
				return false;
			}
		}
		return false ;

	}


    public function deleteItem($title, $alias)
	{
		$db     = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('id')))
		->from($db->quoteName('#__content'))
		->where($db->quoteName('title') . ' = '. $db->Quote($title). ' AND ' . $db->quoteName('alias') . ' = '.  $db->quote($alias));
		$db->setQuery($query);
		$id         =  $db->loadResult();
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__content'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('item_id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_versions'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_items_tmp'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('item_id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_items_ext'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$query      = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('itemid') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_cats_item_relations'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('item_id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_items_versions'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('itemid') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_tags_item_relations'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('item_id') . ' = '. $db->Quote($id)
		);
		$query->delete($db->quoteName('#__flexicontent_fields_item_relations'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
		$name = 'com_content.article.'.$id ;
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('name') . ' = '. $db->Quote($name)
		);
		$query->delete($db->quoteName('#__assets'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->query($query);
	}	
}