<?php
	@session_start();
	require_once(__ROOT__."library/users.class.php");

	if(isset($_SESSION['isherwood_athletics']['userHandle']))
	{
		$userHandle = unserialize($_SESSION['isherwood_athletics']['userHandle']);
	}
	
	#count cart item
	$cart_count = (isset($_SESSION["cart_array"])) ? count($_SESSION["cart_array"]) : '0';
?>
<div id="header">
	<a name="top_page" id="top_page"></a>
    <a href="">
    	<div id="logo">
        	<img src="<?php echo __BASE_URL__ ;?>images/logo.png" alt="logo" />
        </div>
    </a> 
    <div id="myacct">
    <?php 
		if(!isset($userHandle))
		{
	?>
      	<a href="<?php echo __BASE_URL__ ;?>users/login.php">Login</a>
    <?php
		}
		else
		{
	?>
      	<a href="<?php echo __BASE_URL__ ;?>users/logout.php">Logout</a>
    <?php
		}
	?>
    </div>
    <?php
		if(isset($userHandle))
		{
	?>
    <div id="myacct">
      	Welcome <?php echo $userHandle->getProperty("firstName"); ?>!
        <a href="<?php echo __BASE_URL__ ;?>users/profile.php?uID=<?php echo $userHandle->getProperty("userID"); ?>">(My Profile)</a>
    </div>
    <?php
		}
	?>
    <div id="basket"> <a href="<?php echo __BASE_URL__ ;?>users/cart.php">Shopping Cart (<?php echo $cart_count ;?>)</a></div>
    <div id="navigation">
        <ul>
            <li><a id="<?php echo ($pageID == '1') ? 'current' : '' ;?>" href="<?php echo __BASE_URL__ ;?>index.php"> Home </a></li>
            <li><a id="<?php echo ($pageID == '2') ? 'current' : '' ;?>" href="<?php echo __BASE_URL__ ;?>categories.php"> Product Categories </a></li>
            <?php 
				if(isset($userHandle))
				{
			?>
            <li><a id="<?php echo ($pageID == '3' || $pageID == '3.1') ? 'current' : '' ;?>" href="<?php echo __BASE_URL__ ;?>users/order_history.php"> Order History </a></li>
            <li><a id="<?php echo ($pageID == '4' || $pageID == '4.3' || $pageID == '4.4') ? 'current' : '' ;?>" href="<?php echo __BASE_URL__ ;?>users/profile.php?uID=<?php echo $userHandle->getProperty("userID"); ?>"> My Profile </a></li>
            <?php 
				} 
			?>
        </ul>
        <a href="#"><div id=""></div></a>
    </div>
</div>