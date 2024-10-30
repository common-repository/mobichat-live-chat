<div class="mc-plugin-tabs">
	<div id="mcFormMsg" ></div>	
	<ul class="mc-plugin-tabs-nav">
		<li class="mc-plugin-tab-active"><a href="#mc-plugin-register-form">Create Account</a></li>
		<li><a href="#mc-plugin-login-form">Sign In</a></li>		
		
	</ul>
	<div class="mc-plugin-tabs-content" id="mc-plugin-login-form">
		<form id="mcLoginForm" method="post">							
			<ul>
				<li><label for="email">Email</label><input type="text" id="lemail" name="email" value="" placeholder="Email" /><span class="ms-plugin-error-msg">Enter your email</span></li>
				<li><label for="password">Password</label><input id="password" type="password" name="password" value="" placeholder="Password" /><span class="ms-plugin-error-msg">Enter your password</span></li>
				<li><input type="hidden" name="remember" id="remember" value="false" /><input name="mclogin" id="mclogin" type="button" value="Login" /></li>
			</ul>
		</form>
	</div>
	<div class="mc-plugin-tabs-content mc-plugin-tab-active" id="mc-plugin-register-form">
		<form id="mcSignupForm" method="post">		
			<ul>
				<li><label for="fname">Name</label><input type="text" id="fname" name="fname" value="" placeholder="Name" /><span class="ms-plugin-error-msg">Enter your full name</span></li>
				<li><label for="email">Email</label><input type="text" id="email" name="email" value="" placeholder="Email" /><span class="ms-plugin-error-msg">Enter your email</span></li>
				<li><label for="password">Password</label><input id="password" type="password" name="pass" value="" placeholder="Password" /><span class="ms-plugin-error-msg">Enter your password</span></li>
				<li><label for="cname">Company Name</label><input id="cname" type="text" name="cname" value="" placeholder="Company Name" /><span class="ms-plugin-error-msg">Enter your company name</span></li>
				<li><input type="hidden" name="remember" value="false" /><input name="mcsignup" id="mcsignup" type="button" value="Sign Up" /></li>
				
			</ul>
		</form>
	</div>
</div>