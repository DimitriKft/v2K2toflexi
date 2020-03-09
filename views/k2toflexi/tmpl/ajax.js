function NextData(type, msg){
    $("#messages").append(
        "<div class='msg "+ type +"'>"+ msg +"</div>"
    );
}

function waitForNextData(){
    /* This requests the url "msgsrv.php"
    When it complete (or errors)*/
    $.ajax({
    	url:"index.php?option=com_k2toflexi&task=k2toflexi.migrate&tmpl=component", 
        method:'POST',
        async: true, /* If set to non-async, browser shows page as "Loading.."*/
        cache: false,
        timeout:50000, /* Timeout in ms */
        
	    update: document.id('renderarea'),
	
	    onRequest: function(){
	        document.id('loading').set('text', 'Loading...');
	
	    },
	
	    onSuccess: function(){
	
	        document.id('loading').set('text', '');
	        setTimeout(
		        	waitForNextData(), /* Request next message */
		        	30000 /* ..after 30 seconds */
		        	);
	
	    },
	
	    onFailure: function(){
	
	        document.id('loading').set('text', 'Error when trying to render the menu');

	    }
    });
}
