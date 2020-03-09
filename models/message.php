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

/**
 * migration Component model
 *
 * @package		Joomla
 * @subpackage	k2toflexi
 * @since		1.0
 */

class K2toflexiModelMessage extends JModelLegacy {
	public function message($valuesjsons)
	{
		$valuesjson = json_decode($valuesjsons);
		$type = $valuesjson->{'type'};
		$name = $valuesjson->{'name'};
		$message = $valuesjson->{'message'};

		if ($type == 'Tag' || $type == 'Field' || $type == 'Type' || $type == 'Category' || $type == 'Item'|| $type == 'File'){
			if ($message == 'failed'){
				return JText::sprintf( 'COM_K2TOFLEXI_FAILTOSTOREMESS', $type, $name );
			}
			else if ($message == 'exist'){
				return JText::sprintf( 'COM_K2TOFLEXI_ALREDAYEXISTMESS', $type, $name );
			}
			else if ($message == 'success'){
				return JText::sprintf( 'COM_K2TOFLEXI_SUCCESSMESS', $type, $name );
			}
			else if ($message == 'noexist'){
				return JText::sprintf( 'COM_K2TOFLEXI_NOEXISTMESS', $type );
			}
		}
		else if ($type == ''){
			return JText::sprintf('COM_K2TOFLEXI_LOADING');
		}
		else if ($type == 'ImageCategorie'){
		if ($message == 'failed'){
				return JText::sprintf( 'COM_K2TOFLEXI_IMAGECATFAILEDMESS', $type, $name );
			}
			else if ($message == 'exist'){
				return JText::sprintf( 'COM_K2TOFLEXI_IMAGECATEXISTMESS', $name );
			}
			else if ($message == 'success'){
				return  JText::sprintf( 'COM_K2TOFLEXI_IMAGECATSUCCESSMESS', $name );
			}
		}
		else if ($type == 'ImageItem'){
			if ($message == 'failed'){
				return JText::sprintf( 'COM_K2TOFLEXI_IMAGEITEMFAILEDMESS', $type ,$name );
			}
			else if ($message == 'exist'){
				return JText::sprintf( 'COM_K2TOFLEXI_IMAGEITEMEXISTDMESS', $name );
			}
			else if ($message == 'success'){
				return JText::sprintf( 'COM_K2TOFLEXI_CATPLACESUCCESSMESS', $type ,$name );
			}
		}
		else if ($type == 'CategoryPlace'){
			if ($message == 'success'){
				return JText::sprintf( 'COM_K2TOFLEXI_CATPLACESUCCESSMESS', $name );
			}
			else if ($message == false){
				return JText::sprintf( 'COM_K2TOFLEXI_CATPLACEFALSEDMESS', $name );
			}
			else if ($message == 'failed'){
				return JText::sprintf( 'COM_K2TOFLEXI_CATPLACEFAILEDMESS', $name );
			}
			else if ($message == 'error'){
				return JText::sprintf( 'COM_K2TOFLEXI_CATPLACEERRORMESS', $name );
			}
		}
		else {
			return "";
		}
	}
}
