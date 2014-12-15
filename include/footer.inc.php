<div id="footer">
       		<div id="footer_1">
            <ul>
            <li>About Us</li>
			<li>Products</li>
   			<li>News</li>
            <li>Contacts</li>
            </ul>
            </div>
       
       
       		<div id="footer_2">
            <?php
				if(!isset($userHandle) || (isset($userHandle) && $userHandle->getProperty('role') != 0))
				{
			?>
            <h3> <a href="<?php echo __BASE_URL__ ;?>admin/login.php" target="_blank">Admin Login</a></h3>
            <?php
				}
			?>
            <ul>
            <li>My Shoping account</li>
			<li>Login</li>
			<li>FAQs</li>
            <li>Privacy policy</li>
			<li>Site Terms and Conditions</li>
			<li>customer care</li>
			<li>Contacts</li>

            </ul>
            </div>
            
            <div id="footer_4">
            <h3> Latest Tweets</h3>
            
            <ul>
            <li> Facebook</li>
            <li> Twitter</li>
            <li> Youtube</li>
            <li> LinkedIn</li>
            </ul>
            </div>
            
            <div id="footer_3">
            <h3> Social Media</h3>
            
            <ul>
            <li> Facebook</li>
            <li> Twitter</li>
            <li> Youtube</li>
            <li> LinkedIn</li>
            </ul>
            </div>
            
</div>