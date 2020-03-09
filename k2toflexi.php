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


JLoader::register('migrateHelper',     JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'migrate.php');



JLoader::register('InsertImgHelper',   JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'insert' . DIRECTORY_SEPARATOR . 'insert_img.php');
JLoader::register('InsertTagHelper',   JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'insert' . DIRECTORY_SEPARATOR . 'insert_tag.php');
JLoader::register('InsertFieldHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'insert' . DIRECTORY_SEPARATOR . 'insert_field.php');
JLoader::register('InsertTypeHelper',  JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'insert' . DIRECTORY_SEPARATOR . 'insert_type.php');
JLoader::register('InsertCatHelper',   JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'insert' . DIRECTORY_SEPARATOR . 'insert_category.php');
JLoader::register('InsertItemHelper',  JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'insert' . DIRECTORY_SEPARATOR . 'insert_item.php');
JLoader::register('InsertFileHelper',  JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'insert' . DIRECTORY_SEPARATOR . 'insert_file.php');


JLoader::register('AnalysisHelper',      JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'extract' . DIRECTORY_SEPARATOR . 'analysis.php');
JLoader::register('ExtractImgHelper',    JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'extract' . DIRECTORY_SEPARATOR . 'extract_img.php');
JLoader::register('ExtractTagHelper',    JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'extract' . DIRECTORY_SEPARATOR . 'extract_tag.php');
JLoader::register('ExtractFieldHelper',  JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'extract' . DIRECTORY_SEPARATOR . 'extract_field.php');
JLoader::register('ExtractTypeHelper',   JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'extract' . DIRECTORY_SEPARATOR . 'extract_type.php');
JLoader::register('ExtractCatHelper',    JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'extract' . DIRECTORY_SEPARATOR . 'extract_category.php');
JLoader::register('ExtractItemHelper',   JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'extract' . DIRECTORY_SEPARATOR . 'extract_item.php');
JLoader::register('ExtractFileHelper',   JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_k2toflexi' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'extract' . DIRECTORY_SEPARATOR . 'extract_file.php');

if (!JFactory::getUser()->authorise('core.manage', 'com_k2toflexi'))
{
	return JLog::add(JText::_('JERROR_ALERTNOAUTHOR'), JLog::ALERT);
}
 
// récupère une instance du controller prefixé par le nom du composant
$controller = JControllerLegacy::getInstance('k2toflexi');
 
// exécute la tâche demandée
$jinput = JFactory::getApplication()->input;
$task = $jinput->get('task', "", 'STR' );
$controller->execute(JFactory::getApplication()->input->get('task'));
// $controller->execute($task);
 
// exécute la redirection prévue par le controleur
$controller->redirect();
