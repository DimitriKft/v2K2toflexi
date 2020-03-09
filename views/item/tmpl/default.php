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
		
<h1>Sélection des Item</h1>

<form action="index.php?option=com_helloworld&view=helloworlds" method="post" id="adminForm" name="adminForm">
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th width="1%">ID</th>
			<th width="2%">TITLE</th>
			<th width="2%">ALIAS</th>
			<th width="2%">CATID</th>
			<th width="2%">PUBLISHED</th>
			<th width="2%">INTROTEXT</th>
			<th width="2%">FULLTEXT</th>
			<th width="2%">VIDEO</th>
			<th width="2%">GALLERY</th>
			<th width="2%">EXTRA_FIELDS</th>
			<th width="2%">EXTRA_FIELDS_SEARCH</th>
			<th width="2%">CREATED</th>
			<th width="2%">CREATED_BY</th>
			<th width="2%">CREATED_BY_ALIAS</th>
			<th width="2%">CHECKED_OUT</th>
			<th width="2%">CHECKED_OUT_TIME</th>
			<th width="2%">MODIFIED</th>
			<th width="2%">MODIFIED_BY</th>
			<th width="2%">PUBLISH_UO</th>
			<th width="2%">PUBLISH_DOWN</th>
			<th width="2%">TRACH</th>
			<th width="2%">ACCESS</th>
			<th width="2%">ORDERING</th>
			<th width="2%">FEATURED</th>
			<th width="2%">FEATURED_ORDERING</th>
			<th width="2%">IMAGE_CAPTIO</th>
			<th width="2%">IMAGE_CREDITS</th>
			<th width="2%">VIDEO_CAPTION</th>
			<th width="2%">VIDEO_CREDITS</th>
			<th width="2%">HITS</th>
			<th width="2%">PARAMS</th>
			<th width="2%">METADESC</th>
			<th width="2%">METADATA</th>
			<th width="2%">METAKEY</th>
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
						<td align="center"><?php echo $row->title; ?></td>
						<td align="center"><?php echo $row->alias; ?></td>
						<td align="center"><?php echo $row->catid; ?></td>
						<td align="center"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'k2toflexi', true, 'cb'); ?></td>
						<td align="center"><?php echo $row->introtext; ?></td>
						<td align="center"><?php echo $row->fulltext; ?></td>
						<td align="center"><?php echo $row->video; ?></td>
						<td align="center"><?php echo $row->gallery; ?></td>
						<td align="center"><?php echo $row->extra_fields; ?></td>
						<td align="center"><?php echo $row->extra_fields_search; ?></td>
						<td align="center"><?php echo $row->created; ?></td>
						<td align="center"><?php echo $row->created_by; ?></td>
						<td align="center"><?php echo $row->created_by_alias; ?></td>
						<td align="center"><?php echo $row->checked_out; ?></td>
						<td align="center"><?php echo $row->checked_out_time; ?></td>
						<td align="center"><?php echo $row->modified; ?></td>
						<td align="center"><?php echo $row->modified_by; ?></td>
						<td align="center"><?php echo $row->publish_up; ?></td>
						<td align="center"><?php echo $row->publish_down; ?></td>
						<td align="center"><?php echo $row->trash; ?></td>
						<td align="center"><?php echo $row->access; ?></td>
						<td align="center"><?php echo $row->ordering; ?></td>
						<td align="center"><?php echo $row->featured; ?></td>
						<td align="center"><?php echo $row->featured_ordering; ?></td>
						<td align="center"><?php echo $row->image_caption; ?></td>
						<td align="center"><?php echo $row->image_credits; ?></td>
						<td align="center"><?php echo $row->video_caption; ?></td>
						<td align="center"><?php echo $row->video_credits; ?></td>
						<td align="center"><?php echo $row->hits; ?></td>
						<td align="center"><?php echo $row->params; ?></td>
						<td align="center"><?php echo $row->metadesc; ?></td>
						<td align="center"><?php echo $row->metadata; ?></td>
						<td align="center"><?php echo $row->metakey; ?></td>
						<td align="center"><?php echo $row->plugins; ?></td>
						<td align="center"><?php echo $row->language; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
</table>
</div>