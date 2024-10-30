<?php
/**
Plugin Name: MobiChat Live Chat Mobile and Desktop
Plugin URI: http://www.mobichat.com/wordpress-live-chat-plugin
Description: Need more customers? The MobiChat live chat plugin allows you to chat with customers on your desktop or Mobile website. Use our lead capture
to get the information you need from customers right in the chat.
Author: MobiChat LLC
Version: 2.0
Author URI: http://www.mobichat.com/
*/

global $wpdb, $wp_version, $mcea, $mobi_user_email;
$mcea = array();
define ('mobichat',basename(__FILE__));
add_action('init', 'mcSession', 1);
function mcSession() {
    if(!session_id()) {
        session_start();
    }
}
add_action('wp_ajax_nopriv_mc_ajax_dash_json','mc_ajax_dash_json');
add_action('wp_ajax_mc_ajax_dash_json','mc_ajax_dash_json');
function mc_ajax_dash_json(){   
	global $mcea;			
	$mcea = getEmailList();
	$json = array();	
	$storeData = array();
	try{			
		if(isset($_SESSION['mc_email'])){
			if(isset($_POST['add_code_dir']) && !empty($_POST['add_code_dir'])){
				$mc_email = $_SESSION['mc_email'];
				$storeData['mobi_user_site_script'] =  $_POST['add_code_dir'];
				$storeData['mobi_user_mc_color'] = '';
				$storeData['mobi_user_mc_champ'] = '';
			}
			if(!empty($_REQUEST['mc_color']) && !empty($_REQUEST['mc_champ'])){
				$mc_email = $_SESSION['mc_email'];
				$storeData['mobi_user_site_script'] = '';
				$storeData['mobi_user_mc_color'] = $_REQUEST['mc_color'];
				$storeData['mobi_user_mc_champ'] = $_REQUEST['mc_champ'];
			}
			update_option('mc_user_'.$_SESSION['mc_email'], serialize($storeData));
			update_option('mc_chat_activate_id','mc_user_'.$_SESSION['mc_email']);
			$json = array('success'=>1);
		} else {
			$json = array('error'=>'You are not logged in');
		}
	} catch(Exception $e){		
		$json = array('error'=>$e);
	}	
	echo json_encode($json);        
    exit;
}

add_action('admin_enqueue_scripts', 'mobichat_style');
function mobichat_style() {		
	wp_register_style('mobi_style_new', plugins_url('css/mobichat.css', __FILE__));
	wp_enqueue_style('mobi_style_new');
}

add_action('in_admin_footer', 'mobichat_js');
function mobichat_js() {		
	global $mobi_user_email;
	$mobi_user_email = $_SESSION['mc_email'];
	if($_REQUEST['page'] == 'mobichat'){
		if(isset($mobi_user_email) && !empty($mobi_user_email)){	
			wp_register_script('mobi_js_dashboard', plugins_url('js/dashboard.js', __FILE__));				
			wp_enqueue_script('mobi_js_dashboard');		
			wp_register_script('mobi_js_zclip', plugins_url('js/jquery.clipboard.js', __FILE__));				
			wp_enqueue_script('mobi_js_zclip');		
		} else {
			wp_register_script('mobi_js_new', plugins_url('js/mobichat.js', __FILE__));				
			wp_enqueue_script('mobi_js_new');
		}
		add_action('in_admin_footer', 'mc_footer_js_in_admin', 108);
	}
	
}
function getEmailList(){	
	$mc_emails = get_option('mc_user_email_list');
	if(!empty($mc_emails)){
		$mc_e_arr = unserialize($mc_emails);
	}		
	return $mc_e_arr;	
}

function saveEmailList($email){	
	global $mcea;	
	$mcea[] = trim($email);		
	update_option('mc_user_email_list', serialize($mcea));	
}

add_action('admin_menu', 'mobichat_page');
function mobichat_page(){
	global $mcea;			
	$mcea = getEmailList();				
	if(isset($_POST['email']) && !empty($_POST['email'])){	
		$mc_email = trim($_POST['email']);
		if(!in_array($mc_email, $mcea)){
			saveEmailList($mc_email);			
		}
		$_SESSION['mc_email'] = $mc_email;			
		wp_redirect('admin.php?page=mobichat');	
	}	
	if(isset($_REQUEST['logout'])){
		unset($_SESSION['mc_email']);
		wp_redirect('admin.php?page=mobichat');		
	}		
	$mcIcon = plugin_dir_url(__FILE__).'images/favicon.ico';
	add_menu_page( '', 'MobiChat', 1 , 'mobichat', 'mcLoginForm_function', $mcIcon);
}

add_action('init','mobichat_footer_js_init');

function mobichat_footer_js_init() {		
	global $mobi_user_email, $mobi_user_mc_champ, $mobi_user_mc_color, $mobi_user_site_script;		
	$mc_chat_activate_id = get_option('mc_chat_activate_id');	
	if(!empty($mc_chat_activate_id)){				
		extract(unserialize(get_option($mc_chat_activate_id)));						
		if(!empty($mobi_user_mc_champ) && !empty($mobi_user_mc_color)){		
			$mobi_user_mc_champ = explode('||',$mobi_user_mc_champ);
			$mobi_user_mc_color = explode('||',$mobi_user_mc_color);		
			add_action('wp_footer', 'mobichat_footer_js', 108);   		
		}			
		if(!empty($mobi_user_site_script)){				
			add_action('wp_footer', 'mobichat_site_script', 108);   	
		}
	}	
}
function mobichat_footer_js() {	
	global $mobi_user_email, $mobi_user_mc_champ, $mobi_user_mc_color, $mc_user_site_script;					
	echo sprintf('<script>
	function animation_left() {
	  function e() {
		move += 10;
		x.style.right = move + "px";
		if (move == 0) {
		  clearInterval(t)
		}
	  }
	  move = parseInt(x.style.right);
	  var t = setInterval(e, 1 / 100)
	}

	function animation_right() {
	  function e() {
		move -= 10;
		x.style.right = move + "px";
		if (move == -chat_w) {
		  clearInterval(t)
		}
	  }
	  move = parseInt(x.style.right);
	  var t = setInterval(e, 1 / 100)
	}

	function slide_mobi_f34() {
	  x = document.getElementsByClassName("rnd_slide-out")[0];
	  y = x.style.right;
	  if (y == "-" + chat_w + "px") {
		setTimeout("animation_left()", 10);
		x_ex = document.getElementById("mobichat");
		if (!x_ex) {
		  x.insertAdjacentHTML(\'beforeend\', \'<iframe id="mobichat" style="width: \' + chat_w + "px; height:" + chat_h + \'px; border:none;" src="%s"></iframe>\');
		}
	  } else {
		setTimeout("animation_right()", 10)
	  }
	}

	function func1() {
	  var e = document.getElementsByTagName("body")[0];
	  e.insertAdjacentHTML(\'beforeend\', \'<div id="8768339" class="rnd_slide-out" style="width: \' + chat_w + "px;border: 1px solid rgb(117, 117, 117); border-top-left-radius: 0px; border-top-right-radius:0px; border-bottom-right-radius: 0px;border-bottom-left-radius: 0px; margin: 0px; padding: 0px; z-index: 2001; font-weight: 700; font-size: 9px; line-height: 1; position: fixed; top: 130px; right: -" + chat_w + \'px; background-color: rgb(255, 255, 255);"><a class="rnd_handle" id="rnd_slider_handle" href="#" style=" display:none; width: 40px; height: 224px; display: block; text-indent: -99999px; outline: none; position: absolute; top: 0px; left: -40px; background:url(https://www.mbct.me/static/newui/images/livechat_%s.png) no-repeat;" onclick="slide_mobi_f34();">Coupon</a></div>\');
	}
	var x_w = window.innerWidth;
	var y_h = window.innerHeight;
	var chat_w = parseInt(x_w / 40) * 10;
	var chat_h = parseInt(y_h / 15) * 10;
	if (typeof window.orientation == "undefined") {
	  window.addEventListener("load", function load(event) {
		func1();
	  });
	}
	if (typeof window.orientation !== "undefined") {
	  window.addEventListener("load", function load(event) {
		var div = document.createElement("div"),
		  a = document.createElement("a"),
		  img = document.createElement("img");
		div.setAttribute("style", "text-align:center; position:fixed; bottom:0; right:0;z-index:10000;");
		a.href = "%s";
		a.setAttribute("style", "color:#fff; margin:0px 10px 20px 0px; text-decoration:none; float:right; display:inline-block;");
		img.src = "https://tspdy.mbct.me/newui/images/%s.png";
		img.width = "120";
		a.appendChild(img);
		div.appendChild(a);
		document.getElementsByTagName("body")[0].appendChild(div);
	  });
	}
	</script>',$mobi_user_mc_champ[0],$mobi_user_mc_color[0],$mobi_user_mc_champ[0],$mobi_user_mc_color[1]);	
}


function mobichat_site_script(){			
	global $mobi_user_site_script;
	echo stripslashes($mobi_user_site_script);
}

function mcLoginForm_function() {		
	global $mobi_user_email;		
	$mobi_user_email = $_SESSION['mc_email'];		
	if(isset($mobi_user_email)){		
		require_once('mc_dashboard.php');					
	} else {			
		require_once('mc_account.php');					
	}
}
function mc_footer_js_in_admin(){	
	?> 
	<script>
	function animation_left(){function e(){move+=10;x.style.right=move+"px";if(move==0){clearInterval(t)}}move=parseInt(x.style.right);var t=setInterval(e,1/100)}function animation_right(){function e(){move-=10;x.style.right=move+"px";if(move==-chat_w){clearInterval(t)}}move=parseInt(x.style.right);var t=setInterval(e,1/100)}function slide_mobi_f34(){x=document.getElementsByClassName("rnd_slide-out")[0];y=x.style.right;if(y=="-"+chat_w+"px"){setTimeout("animation_left()",10);x_ex=document.getElementById("mobichat");if(!x_ex){x.insertAdjacentHTML('beforeend', '<iframe id="mobichat" style="width: '+chat_w+"px; height:"+chat_h+'px; border:none;" src="https://www.mbct.me/backend/chat/Bqg3fFBi5Lo2Wx7Ls09nMtCnGQ?"></iframe>');}}else{setTimeout("animation_right()",10)}}
	function func1(){var e=document.getElementsByTagName("body")[0];e.insertAdjacentHTML('beforeend', '<div id="8768339" class="rnd_slide-out" style="width: '+chat_w+"px;border: 1px solid rgb(117, 117, 117); border-top-left-radius: 0px; border-top-right-radius:0px; border-bottom-right-radius: 0px;border-bottom-left-radius: 0px; margin: 0px; padding: 0px; z-index: 2001; font-weight: 700; font-size: 9px; line-height: 1; position: fixed; top: 130px; right: -"+chat_w+'px; background-color: rgb(255, 255, 255);"><a class="rnd_handle" id="rnd_slider_handle" href="#" style=" display:none; width: 40px; height: 224px; display: block; text-indent: -99999px; outline: none; position: absolute; top: 0px; left: -40px; background:url(<?php echo plugin_dir_url(__FILE__); ?>images/livechat_pink.png) no-repeat;" onclick="slide_mobi_f34();">Coupon</a></div>');}
	var x_w=window.innerWidth;var y_h=window.innerHeight;var chat_w=parseInt(x_w/40)*10;var chat_h=parseInt(y_h/15)*10;
	if(typeof window.orientation=="undefined"){window.addEventListener("load", function load(event) {func1();});}
	if (typeof window.orientation !== "undefined") {window.addEventListener("load", function load(event) {var div = document.createElement("div"),a = document.createElement("a"),img = document.createElement("img");div.setAttribute("style", "text-align:center; position:fixed; bottom:0; right:0;z-index:10000;");a.href = "https://www.mbct.me/backend/chat/Bqg3fFBi5Lo2Wx7Ls09nMtCnGQ?";a.setAttribute("style", "color:#fff; margin:0px 10px 20px 0px; text-decoration:none; float:right; display:inline-block;");img.src = "undefined";img.width = "120";a.appendChild(img);div.appendChild(a);document.getElementsByTagName("body")[0].appendChild(div);});}
	</script>
	<?php		
}
?>