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
JText::script('COM_K2TOFLEXI_MIGRATIONFINISHED');
JText::script('COM_K2TOFLEXI_FLEXIREQUIRE');
JText::script('COM_K2TOFLEXI_K2REQUIRE');
JText::script('COM_K2TOFLEXI_LOADING');

?>

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2 col-md-2">
	<h2 class="t3-off-canvas-header-title"><?php echo JText::_('Sélection des files') ?></h2>
<?php echo str_replace('type="button"', '', $this->sidebar); ?>
	</div>
	<div id="j-main-container" class="span10 col-md-10">
<?php else : ?>
	<div id="j-main-container" class="span12 col-md-12">
<?php endif;?>

<div id="contenu">		
	<h1>Sélection des Files</h1>
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


<form action="index.php" method="post" id="adminForm" name="adminForm">
	<input type = "hidden" name = "task" value = "com_k2toflexi" />
	<input type = "hidden" name = "option" value = "com_k2toflexi" />
</form>
<div id="contenu">
	<div id="titre" class="page-header"><h1><?php echo 'Migrer vos Files'; ?></h1></div>
	<div id="k2toflexi"><button id="renderbutton" type="button" class="btn btn-primary" value="renderbutton" onclick="waitForFirstData();"><?php echo 'Migrer vos files'; ?></button></div>
	<span id="loading2"></span>
	<span id="loading"></span>
	<div class="btn-group" role="group" id="boutons">
		<button class="btn btn-secondary btn-info"    onclick = "showall()">     <?php   echo JText::_("COM_K2TOFLEXI_ALL");?>     </button>
		<button class="btn btn-secondary btn-danger"  onclick = "showerrors()">  <?php   echo JText::_("COM_K2TOFLEXI_ERROR");?>   </button>
		<button class="btn btn-secondary btn-warning" onclick = "showalert()">   <?php   echo JText::_("COM_K2TOFLEXI_ALERT");?>   </button>
		<button class="btn btn-secondary btn-success" onclick = "showsuccess()"> <?php   echo JText::_("COM_K2TOFLEXI_SUCCESS");?> </button>
	</div>
	<div id="messages" class="hero-unit"></div>
</div>
<div id="read"></div>

<?php

if ( !JComponentHelper::isEnabled( 'com_flexicontent', true) ) {
	JFactory::getDocument()->addScriptDeclaration(
	<<<JS
	jQuery(document).ready(function () {
	jQuery("#renderbutton").hide();
		jQuery("#messages").append(
			Joomla.JText._('COM_K2TOFLEXI_FLEXIREQUIRE')
	);
	}
);
JS
);
}
if ( !JComponentHelper::isEnabled( 'com_k2', true) ) {
	JFactory::getDocument()->addScriptDeclaration(
	<<<JS
	jQuery(document).ready(function () {
	jQuery("#renderbutton").hide();
		jQuery("#messages").append(
			Joomla.JText._('COM_K2TOFLEXI_K2REQUIRE')
	);
	}
);
JS
);
}

JFactory::getDocument()->addScriptDeclaration(
<<<JS

function NextData(data){
	jQuery("#messages").append(
			"<div class='msg new'>"+ data +"</div>"
	);
}


	function waitForNextData(data){
	jQuery.ajax({
		method: "post",
		url:"index.php?option=com_k2toflexi&task=file.file&tmpl=component",
		data : {json: data},
	    dataType : "json",

		success: function(data){
			console.log("success n�2");
			console.log(data);
			if (data.task == false){
				NextData(data.message);
 				scrollmove();
				jQuery("#loading2").hide()
				jQuery("#loading").append(
					Joomla.JText._('COM_K2TOFLEXI_MIGRATIONFINISHED')
				);
	        }else{
				NextData(data.message);
 				scrollmove();
				waitForNextData(JSON.stringify(data));
	        }
		},
        error: function(r) {
			console.log("error n�2");
			console.log(r);
			waitForNextData(data);
        }
	});
};

		function waitForFirstData(){
	jQuery.ajax({
		method: "post",
		url:"index.php?option=com_k2toflexi&task=file.firstmigrateFile&tmpl=component",
	    dataType : "json",
	    contentType: "application/json; charset=utf-8",

		success: function(data){
			jQuery("#renderbutton").hide();

			jQuery("#loading2").append(
				Joomla.JText._('COM_K2TOFLEXI_LOADING')
			);
			console.log("success");
			console.log(JSON.stringify(data));
			NextData(data.message);
			waitForNextData(JSON.stringify(data));
		},
		error: function(data){
			alert(data);
		}
	});
};

 		function scrollmove(){

			var elem = document.getElementById('messages');
			elem.scrollTop = elem.scrollHeight;
 		};

		function showall()
{
	jQuery(".alert-success").show();
	jQuery(".exist").show();
	jQuery(".alert-error").show();
};

		function showerrors()
{
	jQuery(".alert-error").show();
	jQuery(".alert-success").hide();
	jQuery(".exist").hide();
};

		function showalert()
{
	jQuery(".exist").show();
	jQuery(".alert-success").hide();
	jQuery(".alert-error").hide();
};

		function showsuccess()
{
	jQuery(".alert-success").show();
	jQuery(".exist").hide();
	jQuery(".alert-error").hide();
};

JS
);

?>

