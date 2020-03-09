//function addmsg(type, msg){
//        /* Simple helper to add a div.
//        type is the name of a CSS class (old/new/error).
//        msg is the contents of the div */
//        $("#messages").append(
//            "<div class='msg "+ type +"'>"+ msg +"</div>"
//        );
//    }
//
//    function waitForMsg(){
//        /* This requests the url "msgsrv.php"
//        When it complete (or errors)*/
//        $.ajax({
//            type: "GET",
//            url: "administrator\components\com_k2toflexi\views\k2toflexi\tmpl\migrateCall.php",
//
//            async: true, /* If set to non-async, browser shows page as "Loading.."*/
//            cache: false,
//            timeout:50000, /* Timeout in ms */
//
//            success: function(data){ /* called when request to barge.php completes */
//                addmsg("new", data); /* Add response to a .msg div (with the "new" class)*/
//                setTimeout(
//                    waitForMsg, /* Request next message */
//                    1000 /* ..after 1 seconds */
//                );
//            },
//            error: function(XMLHttpRequest, textStatus, errorThrown){
//                addmsg("error", textStatus + " (" + errorThrown + ")");
//                setTimeout(
//                    waitForMsg, /* Try again after.. */
//                    15000); /* milliseconds (15seconds) */
//            }
//        });
//    };
//
//    $(document).ready(function(){
//        waitForMsg(); /* Start the inital request */
//    });
//    
//    var renderAction = new Class({
//
//        initialize: function() {
//
//            var myurl = "index.php?option=com_k2toflexi&view=k2toflexi&layout=k2toflexi&tmpl=component";
//
//            var fields = document.getElements('.ajax').getProperties('name','value');
//
//            fields = JSON.encode(fields);
//
//           
//
//                var packageRequest = new Request.HTML({
//
//                    url:myurl,
//
//                    method: 'post',
//
//                    data: { "fields":fields },
//
//                    update: document.id('renderarea'),
//
//                    onRequest: function(){
//
//                        document.id('loading').set('text', 'Loading...');
//
//                    },
//
//                    onSuccess: function(){
//
//                        document.id('loading').set('text', '');
//
//                    },
//
//                    onFailure: function(){
//
//                        document.id('loading').set('text', 'Error when trying to render the menu');
//
//                    }
//
//
//
//                });
//
//                packageRequest.send();
//
//        }
//
//    });
//
//
//
//    window.addEvent('domready', function() {
//
//        document.id('renderbutton').addEvent('click',function(){
//
//            new renderAction();
//
//        });
//
//    });
//    
//    jQuery(document).ready(function(){
//
//    	   jQuery("#savename").click(function(){
//
//    	   var name = jQuery('#name').val();
//
//    	 // you can apply validation here on the name field.
//
//    	   jQuery.post(JPATH_COMPONENT_ADMINISTRATOR.'ajax.php?name='+name , {
//    	        }, function(response){
//
//    	               jQuery('#results').html(jQuery(response).fadeIn('slow'));
//
//    	   });
//
//    	  });
//
//    	});