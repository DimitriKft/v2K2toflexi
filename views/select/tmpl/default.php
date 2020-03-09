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


?>


<?php if (!empty( $this->sidebar)) : ?>

<div id="j-sidebar-container" class="span2 col-md-2">
<h2 class="t3-off-canvas-header-title"><?php echo JText::_('COM_K2TOFLEXI_SIDEBAR_SELECT') ?></h2>
<div class="fc-board-set-inner"><?php
?>
	<?php echo str_replace('type="button"', '', $this->sidebar); ?>
</div>
<div id="j-main-container" class="span10 col-md-10">

<?php else : ?>

<div id="j-main-container" class="span12 col-md-12">

<?php endif;?>
</div>

