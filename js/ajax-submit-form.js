// JavaScript Document

        // wait for the DOM to be loaded 
        jQuery(document).ready(function() {
        	
        	var options = { 
        			success:       showResponse // post-submit callback 
        	      }; 
            // bind 'myForm' and provide a simple callback function 
            jQuery('#lead_form').ajaxForm(options); 
        }); 

     // post-submit callback 
        function showResponse(responseText, statusText, xhr, $form)  { 
            // for normal html responses, the first argument to the success callback 
            // is the XMLHttpRequest object's responseText property 
         
            // if the ajaxForm method was passed an Options Object with the dataType 
            // property set to 'xml' then the first argument to the success callback 
            // is the XMLHttpRequest object's responseXML property 
         
            // if the ajaxForm method was passed an Options Object with the dataType 
            // property set to 'json' then the first argument to the success callback 
            // is the json data object returned by the server 
         
            if(responseText=="fail")
            	{
            	alert("The reCAPTCHA wasn't entered correctly. Please try it again.");
            	}
            else{
            	alert("Thank you! Your contact was sucessfully added.");
            }
        } 