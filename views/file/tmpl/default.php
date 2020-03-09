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
$document = JFactory::getDocument();
$document->addStyleSheet("./components/com_k2toflexi/assets/css/style.css",'text/css',"screen");

?>

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2 col-md-2">
	<h2 class="t3-off-canvas-header-title"><?php echo JText::_('COM_K2TOFLEXI_SIDEBAR_ANALYSIS') ?></h2>
<?php echo str_replace('type="button"', '', $this->sidebar); ?>
	</div>
	<div id="j-main-container" class="span10 col-md-10">
<?php else : ?>
	<div id="j-main-container" class="span12 col-md-12">
<?php endif;?>

<div id="contenu">		
	<h1>Sélection des Fields</h1>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th width="1%">ID</th>
				<th width="2%">ItemID</th>
				<th width="2%">FILENAME</th>
				<th width="2%">TITLE</th>
				<th width="2%">TITLEATTRIBUTE</th>
				<th width="2%">HITS</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) : ?>
					<tr>
						<td align="center"><?php echo $row->id; ?></td>
						<td align="center"><?php echo $row->itemID; ?></td>
						<td align="center"><?php echo $row->filename; ?></td>
						<td align="center"><?php echo $row->title; ?></td>
						<td align="center"><?php echo $row->titleAttribute; ?></td>
						<td align="center"><?php echo $row->hits; ?></td>	
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>