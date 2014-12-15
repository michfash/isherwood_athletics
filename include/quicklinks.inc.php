<div id="myloginoverlay" class="overlay mediumoverlay"> 

	<h2>Login to your Account</h2>	
	
	<p>Do not have an account yet? <a href="register.php">Register Now</a></p> 
	
	<form id="quickLoginForm" method="post" action=""> 
		<fieldset> 
			<label for="quicksignInUsername">Email Address</label> 
			<input type="text" name="txtEmail" id="txtEmail" size="35"  value="" /> 
			<label for="quicksignInPassword">Password</label> 
			<input id="txtPassword" type="password" size="35" value="" name="txtPassword"/> <br />
			
			<span class="passhelp">I lost my password. Please 
            <a href="#">lets recover it</a></span> 
			
			<input type="submit" alt="Log in" value="Log in" name="btnLogin" title="Log in" /> 
			
		</fieldset> 
	</form> 
 
 <div>You are already Logged in!</div>
</div>

<div id="quicklink">
                      
           <a href="products.php">
           	<div id="quicklink_products"></div>
           </a>
           
           <a class="quickLogin" href="#" rel="div.overlay:eq(0)" >
           	<div id="designer_logon"></div>
           </a>
            
</div>