// JavaScript Document

 jQuery(document).ready(function(){
	jQuery('#lead_form').submit(function(){
        var company = jQuery("#company").val();
		var last_name = jQuery("#last_name").val();
		var email = jQuery("#email").val();
		var atpos=email.indexOf("@");
		var dotpos=email.lastIndexOf(".");
		
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
		
		
		else if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
		  {
		  alert("Please enter a valid e-mail address");
		  return false;
		  }
		else{
			return true;
		}
    });
	});