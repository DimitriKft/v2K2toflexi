<?php


JFactory::getDocument()->addScriptDeclaration(
<<<JS
	function waitForNextData(data){
			jQuery.ajax({
				type: 'POST',
				url: 'index.php?option=com_k2toflexi&task=k2toflexi.migrate&tmpl=component',
				dataType: 'json',
				data: data
			}).done( function(data) {
				document.id('loading').set('text', 'Loading...');
				
				if (data==''){
					document.id('loading').set('text', 'Done...');
				}
				else {
					waitForNextData(data);
				}
			});
		}
		

		function waitForFirstData(){
			jQuery.ajax({
				type: 'POST',
				url: 'index.php?option=com_k2toflexi&task=k2toflexi.migrate&tmpl=component',
				dataType: 'json',
				data: {
				task: '',
				sql: ''
				}
			}).done( function(data) {
				document.id('loading').set('text', 'Loading...');
				
				if (data==''){
					document.id('loading').set('text', 'Done...');
				}
				else {
					waitForNextData(data);
				}
			});
		}
		function NextData(type, msg){
    $("#messages").append(
        "<div class='msg "+ type +"'>"+ msg +"</div>"
    );
}
JS
);


// jQuery(document).ready(function($) {	});






// $document = JFactory::getDocument();
// $document->addScriptDeclaration("
// 	    	/* K2 - Metrics */
// 	        (function(\$){
// 				function K2toflexi(xhr) {
// 					\$.ajax({
// 						type: 'POST',
// 						url: 'index.php',
// 						data: {
// 							'option': 'com_k2toflexi',
// 							'view': 'k2toflexi',
// 							'task': 'migrate',
// 							'".$token."': '1',
// 							'status': xhr.status,
// 							'response': xhr.responseText
// 						}
// 					});
// 				}
// 		        \$(document).ready(function(){
// 					\$.ajax({
// 						crossDomain: true,
// 						type: 'POST',
// 						url: 'https://metrics.getk2.org/gather.php',
// 						data: ".$data."
// 					}).done(function(response, result, xhr) {
// 						K2LogResult(xhr);
// 					}).fail(function(xhr, result, response) {
// 						K2LogResult(xhr);
// 					});
// 				});
// 			})(jQuery);
// 		");

