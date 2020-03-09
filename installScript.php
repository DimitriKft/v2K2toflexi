<?php
defined('_JEXEC') or die('Restricted access');

class com_k2toflexiInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 * @since 1.0
	 */

	function install($parent)
	{
		JFactory::getApplication()->enqueueMessage("Installation of com_k2toflexi version ".$parent->get('manifest')->version." was successful.
				If you just installed FLEXIcontent, then please check on FLEXIcontent's Dashboard that every table was created.", 'notice');
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_k2toflexi');
		//echo "test";
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 * @since 1.0
	 */

	function update($parent)
	{
		// $parent is the class calling this method
		JFactory::getApplication()->enqueueMessage("Update of com_k2toflexi version ".$parent->get('manifest')->version." was successful.
				If you just installed FLEXIcontent, then please check on FLEXIcontent's Dashboard that every table was created.", 'notice');
		$parent->getParent()->setRedirectURL('index.php?option=com_k2toflexi');
		echo '<pre>k2toflexi a été mis à jour en version '.$parent->get('manifest')->version.'</pre>';
	}
}
