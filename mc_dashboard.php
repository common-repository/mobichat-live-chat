<div class="mc-plugin-loggedin">
	<?php 
	$getInfo = get_option('mc_user_'.$mobi_user_email);
	if(!empty($getInfo)){
		extract(unserialize($getInfo));		
	}
	echo 'Logged in as : '. $mobi_user_email;?>
	<?php if(isset($_REQUEST['success'])){ ?>
		<div class="mc-plugin-sucess">Success you have mobichat on your blog!</div>
	<?php }	?>
</div>
<a href="javascript:void(0);" class="mc-plugin-logout" data-link="<?php echo admin_url('admin.php?page=mobichat&logout=true'); ?>">logout</a>

<div class="mc-valid mobile">
		<a style="display:none" class="mc-special-price" href="http://www.mbct.me/manager/trail-to-paid?plan_id=23&user_email=<?php echo $mobi_user_email?>" target="_blank"> <span> Special Wordpress Pricing! $19/month </span> </a>		 
		<span class="mc-price-message-user" style="display:none"> </span>
		<span class="mc-price-message-click" style="display:none"> 
		Your trial is over, please <b><a href="https://www.mbct.me/manager/trail-to-paid?plan_id=23&user_email=<?php echo $mobi_user_email?>" target="_blank"> click here </a></b> to get a paid account </span>
</div>

<div class="mc-plugin-tabs">
	<ul class="mc-plugin-tabs-nav">
		<li class="mc-plugin-tab-active"><a href="#mc-plugin-code-step">Add code in 1 step</a></li>		
		<li><a href="#mc-plugin-code-manually">Add chat code manually</a></li>
		<div class="mc-valid desktop">
		<a style="display:none" class="mc-special-price" href="https://www.mbct.me/manager/trail-to-paid?plan_id=23&user_email=<?php echo $mobi_user_email?>" target="_blank"> <span> Special Wordpress Pricing! $19/month </span> </a>		 
		<span class="mc-price-message-user" style="display:none"> </span>
		<span class="mc-price-message-click" style="display:none"> 
		Your trial is over, please <b><a href="https://www.mbct.me/manager/trail-to-paid?plan_id=23&user_email=<?php echo $mobi_user_email?>" target="_blank"> click here </a></b> to get a paid account </span>
		</div>
		<!-- https://www.mbct.me/manager/trail-to-paid?plan_id=39&user_email=user_emai 
			http://www.mbct.me/manager/trail-to-paid?plan_id=23&user_email=
		-->
	</ul>
	<div class="mc-plugin-tabs-content mc-plugin-tab-active" id="mc-plugin-code-step">
		<form id="mcaddcode" method="post" >							
				 
			<ul>		<?php  $mobi_user_mc_champ_name = explode('||',$mobi_user_mc_champ); ?>	
			<span class="ms-lct-error-msg" style="display:none">  </span>	
				<li><label for="mc_champ">Select your campaign:</label>
					<div class="mc-plugin-campaign">				
						<input type="hidden" name="action" value="mc_ajax_dash_json" />
						<select id="mc_champ" class="mc_champ" name="mc_champ" data-champ="<?php echo $mobi_user_mc_champ_name[1]; ?>">
							<option value=""> Select your campaign </option>
						</select><span class="ms-plugin-error-msg">Please select your campaign</span>							
					</div>
				</li>
				<li><label for="darkpink">Select your color:</label>
						<div class="mc-plugin-color-palate">
							<ul>							
							<?php 
							$mc_color_arr = array('darkpink'=>'chat3_1' ,'red'=>'chat3_2' ,'green'=>'chat3_3' ,'lightgreen'=>'chat3_4' ,'blue'=>'chat3_5' ,'sky'=>'chat3_6' ,'pink'=>'chat3_7' ,'purple'=>'chat3_8' ,'brown'=>'chat3_9' ,'yellow'=>'chat3_10');
							foreach($mc_color_arr as $key=>$val){
								$getVal = $key.'||'.$val;
								$getSel = '';								
								$getImg = plugins_url( sprintf('/mobichat-live-chat/images/%s.png',$val), dirname(__FILE__));
								if ($mobi_user_mc_color == $getVal){
									$getSel = 'checked';
								}									
								echo sprintf('<li><div class="mc-plugin-radio"><input type="radio" name="mc_color" id="%s" %s value="%s" class="mc_color"> </div><label for="%s"> <img src="%s" ></label></li>',$key,$getSel,$getVal,$key,$getImg);
							}
							?>							
							</ul>
							<span class="ms-plugin-error-msg">Please select color</span>
						</div>
						<div id="log"></div>
				</li>
				<li><input type="submit" id="addsitecode1" name="Add to my wordpress site" value="Add to my wordpress site" /></li>
			</ul>
		</form>
	</div>

<div class="mc-popup">
<div class="mc-popup-container">
	<a href="" class="mc-close"></a>
	<h3> Success You Have Mobichat on your blog ! </h3>	
	<p>1 more step to start chatting with customers!<br />Download an agent app and login with your signup details </p>
	<ul>
	<li>
		<img src="<?php echo plugins_url( '/mobichat-live-chat/images/window.png', dirname(__FILE__) ); ?>" /><br />
		for windows desktop<br />
	<a href="http://www.mobichat.com/mobichat-setup.exe" target="_blnk"> Download </a><a href="javascript:void(0);" class="mc_copy_link"> Copy Link </a>
	</li>
	<li>
		<img src="<?php echo plugins_url( '/mobichat-live-chat/images/iphone.png', dirname(__FILE__) ); ?>" /><br />
		for iphone<br />
		<a href="https://itunes.apple.com/us/app/mobichat-agent-app/id889709859?mt=8" target="_blnk"> Download </a><a href="#" class="mc_copy_link"> Copy Link </a>
	</li>
	<li>
		<img src="<?php echo plugins_url( '/mobichat-live-chat/images/android.png', dirname(__FILE__) ); ?>" /><br />
		for android<br />
		<a href="https://play.google.com/store/apps/details?id=com.mobichat.mobichatapp" target="_blnk"> Download </a><a href="#" class="mc_copy_link"> Copy Link </a>
	</li>
	</ul>
	</div>	
</div>

	
	
	<div class="mc-plugin-tabs-content" id="mc-plugin-code-manually">
		<form id="mcchatscript" method="post">
			<ul>
				<li>
				 <?php $mc_user_site_script_val = $mobi_user_site_script; ?>
				 <?php if(!empty($mc_user_site_script_val)){
						$mc_code = stripslashes($mc_user_site_script_val);
					}?>
				 <textarea name="add_code_dir" id="add_code_dir" col="15" rows="5" placeholder="Add your code(<tags />) here"><?php echo $mc_code; ?></textarea>
				 <input type="hidden" name="action" value="mc_ajax_dash_json" />
				<span class="ms-plugin-error-msg">Please enter your code</span>
				</li>
				<li><input type="submit" id="addsitecode2" name="Add to my wordpress site" value="Add to my wordpress site" /></li>
			</ul>
		</form>
	</div>
</div>

<script>
var swfLink = "<?php echo plugins_url( '/mobichat-live-chat/js/ZeroClipboard.swf', dirname(__FILE__) ); ?>";					
</script>		