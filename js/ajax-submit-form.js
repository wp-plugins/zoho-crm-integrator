// JavaScript Document

        // wait for the DOM to be loaded 
        jQuery(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            jQuery('#lead_form').ajaxForm(function() { 
                alert("Thank you! Your contact was sucessfully added"); 
            }); 
        }); 
