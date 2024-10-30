var url = "https://www.mbct.me/manager/wp-getcamps";
jQuery.ajax({
   type: "Post",
   dataType: "json",
   contentType: "application/javascript",			 
   url: url,
   crossDomain: true,
	xhrFields: {
       'withCredentials': true
    },
   success: function (jsonData) {					
	 console.log(this.jsonData);
		  var arg = (jsonData); 
		if(arg.code == 200 ){				
				var myObjects = arg.camps ; 
				var dataChamp = jQuery('#mc_champ').data('champ');			
				jQuery.each(myObjects, function(){							
					if(this.name == dataChamp || myObjects.length == 1){
						dcSelected = 'selected="selected"';
					} else {
						dcSelected = '';
					}
					jQuery('#mc_champ').append(
						jQuery ('<option value = "' +this.url +'||' + this.name +'||' + this.lct +'"' + dcSelected +'>'+ this.name+' </option>')
					);
									
				});	
			}		
			
		if(arg.code == 401){			
			window.location = jQuery(".mc-plugin-logout").data('link');
		}
	
   },			    
});

function beforeSubmit(sn){
	jQuery(sn).data('bval',jQuery(sn).val()).val("Please wait..").attr('disabled', 'disabled');				
}
function afterSubmit(sn){
	jQuery(sn).val(jQuery(sn).data('bval')).removeAttr('disabled');	
	
}

jQuery(document).ready(function (){		
	jQuery('.mc-plugin-logout').click(function(e){				
		e.preventDefault();		
		jQuery('.mc-plugin-logout').addClass('click');
		var url = "https://www.mbct.me/manager/wp-logout";
		jQuery.ajax({
		   type: "POST",
		   dataType: "json",
		   contentType: "application/javascript",			 
		   url: url,
		   crossDomain: true,
		   xhrFields: {
				 'withCredentials': true
			},
		   success: function (jsonData) {						
			 console.log(this.jsonData);
			  var arg = (jsonData); 
				if(arg.code == 200 ){
					if(jQuery(".mc-plugin-logout").hasClass("click"))
					{
						console.log(arg.msg);
						window.location = jQuery(".mc-plugin-logout").data('link');				
					}									
				}	
				if(arg.code == 401){			
					window.location = jQuery(".mc-plugin-logout").data('link');
				}
	
		   },				
		}); 

	});

	
	var url = "https://www.mbct.me/manager/wp-gettrial-days";
		jQuery.ajax({
		   type: "Post",
		   dataType: "json",
		   contentType: "application/javascript",			 
		   url: url,
		   crossDomain: true,
			xhrFields: {
			   'withCredentials': true
			},
		   success: function (jsonData) {					
			  var arg = (jsonData); 
				if(arg.acc_type == "paid" ){
					jQuery(".mc-special-price").hide();
				}
				else {
					if(arg.days != 0)
					{
						jQuery(".mc-special-price").show();
						jQuery("span.mc-price-message-user").show();
						jQuery("span.mc-price-message-user").html("You have <b>"+ arg.days + "</b> days left on your <b> Free Trial.</b>") ;
						return false;	
					}
					if(arg.days == 0)
					{
						jQuery(".mc-special-price").show();
						jQuery("span.mc-price-message-click").show();						
						return false;													
					}
					
					
				}
									
					
		   },			    
		});
									
									
	jQuery('.mc-plugin-tabs-nav li a').on('click',function(e){
		e.preventDefault();
		jQuery('.mc-plugin-tabs-nav li').removeClass('mc-plugin-tab-active');
		jQuery(this).parent().addClass('mc-plugin-tab-active');
		jQuery('.mc-plugin-tabs-content').removeClass('mc-plugin-tab-active');
		jQuery(jQuery(this).attr('href')).addClass('mc-plugin-tab-active');
	});	
	
	//-------------- popup ----------// 		
	
	jQuery('#mcchatscript').submit(function(e) {
		e.preventDefault();
		var dataString = jQuery(this).serialize(); 		
		if(jQuery('#add_code_dir').val() == ''){
			jQuery('#add_code_dir').parent().find('span').show();
			jQuery('#add_code_dir').focus();
			return false;
		} else {
			jQuery('#add_code_dir').parent().find('span').hide();
		}
					
			beforeSubmit('#addsitecode2');
			
		if(jQuery('#mc_champ option:selected').val() != '' && jQuery("input[type='radio'][name='mc_color']:checked").length >= 1){
			wc = window.confirm("Do you want to replace current automatic code?");
			if(wc == false){				
				afterSubmit('#addsitecode2');
				return false;
			} else {				
				jQuery('#mc_champ option').attr('selected', false);
				jQuery("input[type='radio'][name='mc_color']").attr('checked',false);
				jQuery('.mc-plugin-radio').removeClass('checked');
			}	
	
		}		
		
		jQuery.ajax({
			url: ajaxurl,			
			data: dataString,
			dataType: 'JSON',
			type: 'post',
			success: function(data) {
				if(data.success){
					jQuery(".mc-popup").fadeIn(function(){						
						jQuery(".mc_copy_link").each(function(){
							if(jQuery(this).parent().find('embed').length == 0){
								jQuery(this).zclip({
									path: swfLink,
									copy: function(){	
										console.log(jQuery(this).parent().find('a:first').attr('href'));
										return jQuery(this).parent().find('a:first').attr('href');
									},
									
								});
							}							
						});							
					});	
					e.preventDefault();
					afterSubmit('#addsitecode2');
				}
			},
		});				
		return false;
	});	
	jQuery('#mcaddcode').submit(function(e) {
		e.preventDefault();
		var dataString = jQuery(this).serialize(); 	
		
			
		if(jQuery('#mc_champ option:selected').val() == ''){
			jQuery('#mc_champ').parent().find('span').show();
			jQuery('#mc_champ').focus();
			return false;
		} else {
			jQuery('#mc_champ').parent().find('span').hide();
		}
		if(jQuery("input[type='radio'][name='mc_color']:checked").length == 0){
			jQuery('.mc-plugin-color-palate').parent().find('span').show();			
			return false;
		} else {
			jQuery('.mc-plugin-color-palate').parent().find('span').hide();			
		}	
		
			// new filed----------	
			var opt_val = jQuery('#mc_champ option:selected').val();
			var lct_val = opt_val.split('||')[2];
			console.log('lct: '+ lct_val);
			if((lct_val == 'null') || (lct_val == '') || (lct_val == 'undefined')  )
			{				
				jQuery("span.ms-lct-error-msg").show();					    
				jQuery("span.ms-lct-error-msg").html( 'Campaign <b>' + opt_val.split('||')[1] + '</b> is in Draft mode. Please make sure you have a lead capture template assigned.') ; 	
				return false;				
			}	
			else
			{				
				jQuery("span.ms-lct-error-msg").hide();					
			}
			
		beforeSubmit('#addsitecode1');		
			
		if(jQuery('#add_code_dir').val() != ''){
			wc = window.confirm("Do you want to replace current manual code?");
			if(wc == false){				
				afterSubmit('#addsitecode1');
				return false;
			} else {
				jQuery('#add_code_dir').val('');
			}
		}
			
		jQuery.ajax({
			url: ajaxurl,			
			data: dataString,
			dataType: 'JSON',
			type: 'post',
			success: function(data) {
				if(data.success){
					jQuery(".mc-popup").fadeIn(function(){						
						jQuery(".mc_copy_link").each(function(){
							if(jQuery(this).parent().find('embed').length == 0){
								jQuery(this).zclip({
									path: swfLink,
									copy: function(){	
										console.log(jQuery(this).parent().find('a:first').attr('href'));
										return jQuery(this).parent().find('a:first').attr('href');
									}, 		
								});
							}							
						});							
					});
					 e.preventDefault();					
					afterSubmit('#addsitecode1');

				}
			},
		});		
		
		return false;						
	});
		
	jQuery(".mc-close").on("click", function(e) {  
		 jQuery(".mc-popup").fadeOut();						
		 return false;
	});		 	
	
	jQuery('input[type="radio"]').each(function(){
		if (jQuery(this).prop("checked")) {
			jQuery(this).parent().addClass('checked');
		}else{
			jQuery(this).parent().removeClass('checked');
		}
	});
	
	jQuery('input[type="radio"]').click(function(){
		jQuery('input[type="radio"]').parent().removeClass('checked');
		if (jQuery(this).prop("checked")) {
			jQuery(this).parent().addClass('checked');
		}else{
			jQuery(this).parent().removeClass('checked');
		}
	});
	
});

