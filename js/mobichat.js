function validation(arrayId, formID){
	var counter = arrayId.length;			
	for(var i=0;i<counter;i++)
	{		
		if(arrayId[i] == "email"){
			if(jQuery(formID + ' #'+ arrayId[i]).val() =='' || jQuery(formID + ' #'+ arrayId[i]).val() == '0'){
				jQuery(formID + ' #'+ arrayId[i]).parent().find('span').html('Enter your email').show();
				jQuery(formID + ' #'+ arrayId[i]).focus();
				return false;
			}
			
			var emailLetter =  /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if(!emailLetter.test(jQuery(formID + ' #'+ arrayId[i]).val())){
				jQuery(formID + ' #'+ arrayId[i]).parent().find('span').html('Enter valid email').show();
				jQuery(formID + ' #'+ arrayId[i]).focus();
				return false;
			}		
			
			var emailblockReg =	/^([\w-\.]+@(?!gmail.c)(?!yahoo.c)(?!hotmail.c)(?!test.c)(?!outlook.c)([\w-]+\.)+[\w-]{2,4})?$/;
			if(!emailblockReg.test(jQuery(formID + ' #'+ arrayId[i]).val())){
				jQuery(formID + ' #'+ arrayId[i]).parent().find('span').html('Sorry you cannot sign up with a free email address. Please email support@mobichat.com for an exception.').show();
				jQuery(formID + ' #'+ arrayId[i]).focus();
				return false;
			}			
			jQuery(formID + ' #'+ arrayId[i]).parent().find('span').hide();		
		} else if(jQuery(formID + ' #'+ arrayId[i]).val() =='' || jQuery(formID + ' #'+ arrayId[i]).val() == '0'){			
			jQuery(formID + ' #'+ arrayId[i]).parent().find('span').show();
			jQuery(formID + ' #'+ arrayId[i]).focus();
			return false;
		} else if(arrayId[i] == "password" && jQuery(formID + ' #'+ arrayId[i]).val().length < 6){	
			jQuery(formID + ' #'+ arrayId[i]).parent().find('span').html('The password must be at least 6 characters').show();
			jQuery(formID + ' #'+ arrayId[i]).focus();
			return false;
		} else {
			jQuery(formID + ' #'+ arrayId[i]).parent().find('span').hide();			
		}				
	}		
	return true;
}
function beforeSubmit(obj){
	var bobj = jQuery(obj).find('input[type="button"]');
	jQuery('#mcFormMsg').slideUp().html('');		
	jQuery(bobj).data('bval',jQuery(bobj).val()).val("Please wait..").attr('disabled', 'disabled');
}
function afterSubmit(obj){	
	jQuery(obj).find('input[type="button"]').val(jQuery(obj).find('input[type="button"]').data('bval')).removeAttr('disabled');	
}
jQuery(document).ready(function(e) {
	
	jQuery('.mc-plugin-tabs-nav li a').on('click',function(e){
		e.preventDefault();
		jQuery('.mc-plugin-tabs-nav li').removeClass('mc-plugin-tab-active');
		jQuery(this).parent().addClass('mc-plugin-tab-active');
		jQuery('.mc-plugin-tabs-content').removeClass('mc-plugin-tab-active');
		jQuery(jQuery(this).attr('href')).addClass('mc-plugin-tab-active');
	});
	 	
	// login
	
	jQuery("#mclogin").click(function(e) { 
		var fn = "#mcLoginForm";
		var fd = jQuery(fn);		
		beforeSubmit(fd);
		if(validation(Array('lemail','password'), fn)){
			
			var uemail = jQuery("input#lemail").val();
			var upass = jQuery("input#password").val();
						
			var dataString = {
				 email:jQuery("input#lemail").val(),
				 password:jQuery("input#password").val(),
				 remember: false
			}
			
			var url = "https://www.mbct.me/manager/wp-login";
			   jQuery.ajax({
				   type: "POST",
				   dataType: "json",
				   contentType: "application/javascript",				 
				   data: JSON.stringify(dataString),
				   crossDomain: true,
				   xhrFields: {
                         'withCredentials': true
					 },
				   url: url,
				   success: function (jsonData) {
						
					    var arg = (jsonData); 
						console.log(arg);
							if(arg.code == 200){	
								jQuery( "#mcFormMsg" ).addClass( "mc-plugin-sucess" );
								jQuery('#mcFormMsg').html(arg.msg).slideDown().delay(5000).slideUp();
								afterSubmit('#mcLoginForm');									
								jQuery('#mcLoginForm').submit();
							}
							else {		
								jQuery("#mcFormMsg").addClass( "mc-plugin-error");		
								jQuery('#mcFormMsg').html(arg.msg).slideDown().delay(5000).slideUp();			
								afterSubmit('#mcLoginForm');
							}
				   },					
				  error: function (request, textStatus, errorThrown) {
					   console.log(request);
					   console.log(textStatus);
					   console.log(errorThrown);
				   }			   
				   				   
				});
		} else {
			afterSubmit(fd);		
		}
		
		return false;
	});
	// sign up
	jQuery("#mcsignup").click(function(e){
		var fn = "#mcSignupForm";
		var fd = jQuery(fn);		
		beforeSubmit(fd);
		if(validation(Array('fname','email','password','cname'), fn)){
			var dataSignup = {
				  cname:jQuery("input#cname").val(),
				  email:jQuery("input#email").val(),
				  fname:jQuery("input#fname").val(),
				  password:jQuery(mcSignupForm.password).val()				  
			}
					
			var url = "https://www.mbct.me/manager/wp-signup";
			   jQuery.ajax({
				   type: "POST",
				   dataType: "json",
				   contentType: "application/javascript",
				  data: JSON.stringify(dataSignup),
				   crossDomain: true,
				   xhrFields: {
                         'withCredentials': true
					 },
				   url: url,
				   success: function (jsonData) {
						 console.log(dataSignup);
					   console.log(this.jsonData);	
					 var arg = (jsonData); 						 			
						 if (arg.code == 200 )
						{
							jQuery( "#mcFormMsg" ).addClass( "mc-plugin-sucess" );
							jQuery('#mcFormMsg').html(arg.msg).slideDown().delay(5000).slideUp();			
							afterSubmit('#mcSignupForm');		
							jQuery('#mcSignupForm').submit();
								/* jQuery('#to_date').val() = date('d-m-Y') ;
								   jQuery('#from_date').val() = date('d-m-Y', strtotime($today. ' + 30 days')) ;	*/							
								
						} else {		
							jQuery("#mcFormMsg").addClass( "mc-plugin-error");		
							jQuery('#mcFormMsg').html(arg.msg).slideDown().delay(5000).slideUp();			
							afterSubmit('#mcSignupForm');
						}	
	
				   },				  
				   error: function (request, textStatus, errorThrown) {					
					   console.log(request.responseText);
					   console.log(textStatus);
					   console.log(errorThrown);
				   }
				})
		} else {
			afterSubmit(fd);
		}
		return false;
		
	});	
});
