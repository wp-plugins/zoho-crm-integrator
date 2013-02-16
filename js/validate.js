// JavaScript Document

 jQuery(document).ready(function(){
	jQuery('#lead_form').submit(function(){
        var company = jQuery("#company").val();
		var last_name = jQuery("#last_name").val();
		
		if(company=="")
		{
			alert("Please enter your company");
			jQuery("#company").focus();
			return false;
		}
		
		else if(last_name=="")
		{
			alert("Please enter your last name");
			jQuery("#last_name").focus();
			return false;
		}
		else{
			return true;
		}
    });
	});