<?php
//load DB and App Config
require_once("../include/db.connect.inc.php");
require_once("../include/app.config.inc.php");
session_start();

#update last login date
require_once(__ROOT__."library/users.class.php");
if(isset($_SESSION['isherwood_athletics']['userHandle']))
{
	$userHandle = unserialize($_SESSION['isherwood_athletics']['userHandle']);
}
$userID = $userHandle->getProperty("userID");
$user = new Users();
$loginDate = $userHandle->getProperty("loginDate");
$result = $user->updateLastLogin($userID,$loginDate);
if(!$result) echo "Error trying to update last login date";

//create temporary cart if items in cart
if (isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) > 1) $tempCart = $_SESSION["cart_array"];

session_destroy();//destroy session

//update stock level if items are left in the cart after logout
require_once(__ROOT__."library/products.class.php");
$products = new Products();
if(isset($tempCart) && !empty($tempCart))
{
	foreach ($tempCart as $each_item)
	{
		$items_to_adjust = $each_item['item_id'];
		$itemQty = $each_item['quantity'];
	    //get stock level and update item stock
	    $items = $products->loadItem($items_to_adjust);
	    $currStock = $items[0]['stock'];
		$newStock = $currStock + $itemQty;
	    $products->setProperty('stock',$newStock);
	    $products->updateStockLevel($items_to_adjust);
	}
}

#prepare notification message
$msg = "Logout Successful!";
$msg_type = "success";

header("location:".__BASE_URL__."index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));

?>