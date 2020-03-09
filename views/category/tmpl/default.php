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
	<h1>Sélection des Category</h1>
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th width="1%">ID</th>
			<th width="2%">NAME</th>
			<th width="2%">ALIAS</th>
			<th width="2%">DESCRIPTION</th>
			<th width="2%">PARENT</th>
			<th width="2%">EXTRAFIELDSGROUP</th>
			<th width="2%">PUBLISHED</th>
			<th width="2%">ACCESS</th>
			<th width="2%">ORDERING</th>
			<th width="2%">IMAGE</th>
			<th width="2%">PARAMS</th>
			<th width="2%">TRASH</th>
			<th width="2%">PLUGINS</th>
			<th width="2%">LANGUAGE</th>
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
						<td align="center"><?php echo $row->name; ?></td>
						<td align="center"><?php echo $row->alias; ?></td>
						<td align="center"><?php echo $row->description; ?></td>
						<td align="center"><?php echo $row->parent; ?></td>
						<td align="center"><?php echo $row->extraFieldsGroup; ?></td>
						<td align="center"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'k2toflexi', true, 'cb'); ?></td>
						<td align="center"><?php echo $row->access; ?></td>
						<td align="center"><?php echo $row->ordering; ?></td>
						<td align="center"><?php echo $row->image; ?></td>
						<td align="center"><?php echo $row->params; ?></td>
						<td align="center"><?php echo $row->trash; ?></td>
						<td align="center"><?php echo $row->plugins; ?></td>
						<td align="center"><?php echo $row->language; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>