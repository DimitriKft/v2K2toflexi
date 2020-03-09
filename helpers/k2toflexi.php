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
class K2toflexidHelper extends JHelperContent
{
    public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(
			'Analyse',
			'index.php?option=com_k2toflexi&view=analysis',
			$vName == 'analysis'
		);
        JHtmlSidebar::addEntry(
            'Sélection Type',
            'index.php?option=com_k2toflexi&view=type',
            $vName == 'type'
        );
        JHtmlSidebar::addEntry(
            'Sélection Catégorie',
            'index.php?option=com_k2toflexi&view=category',
            $vName == 'category'
        );
        JHtmlSidebar::addEntry(
            'Sélection Field',
            'index.php?option=com_k2toflexi&view=field',
            $vName == 'field'
        );
        JHtmlSidebar::addEntry(
            'Sélection Tag',
            'index.php?option=com_k2toflexi&view=tag',
            $vName == 'tag'
        );
        JHtmlSidebar::addEntry(
            'Sélection Item',
            'index.php?option=com_k2toflexi&view=item',
            $vName == 'item'
        );
        JHtmlSidebar::addEntry(
            'Votre sélection',
            'index.php?option=com_k2toflexi&view=select',
            $vName == 'selection'
        );
        JHtmlSidebar::addEntry(
            'Migration',
            'index.php?option=com_k2toflexi',
            $vName == 'migrate'
        );
        
    }
}