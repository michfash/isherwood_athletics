<?php
$pageID = '';
//load DB and App Config
require_once("../include/db.connect.inc.php");
require_once("../include/app.config.inc.php");
?>
<?php
session_start();
require_once(__ROOT__."library/users.class.php");
include(__ROOT__."library/products.class.php");
include(__ROOT__."library/sizes.class.php");


#load all sizes
$allSizes = array();
$allSizes = Sizes::loadAll_filter($filterParam="",array('1'),$start="",$end="");

$products = new Products();
 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 1 (if user attempts to add something to the cart from the product page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_REQUEST['variationID'])) {
    $variationID = $_REQUEST['variationID'];
	$qty = $_REQUEST['qty'];
	$wasFound = false;
	$i = 0;
	// If the cart session variable is not set or cart array is empty
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
	    // RUN IF THE CART IS EMPTY OR NOT SET
		$_SESSION["cart_array"] = array(0 => array("item_id" => $variationID, "quantity" => $qty));
	} else {
		// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
		foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $variationID) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $variationID, "quantity" => $each_item['quantity'] + $qty)));
					  $wasFound = true;
				  } // close if condition
		      } // close while loop
	       } // close foreach loop
		   if ($wasFound == false) {
			   array_push($_SESSION["cart_array"], array("item_id" => $variationID, "quantity" => $qty));
		   }
	}
	header("location: cart.php"); 
    exit();
} 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 2 (if user chooses to empty their shopping cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == "emptycart" && !empty($_SESSION["cart_array"])) {
	foreach ($_SESSION["cart_array"] as $each_item)
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
    	unset($_SESSION["cart_array"]);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 3 (if user chooses to adjust item quantity)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_REQUEST['item_to_adjust']) && $_REQUEST['item_to_adjust'] != "") {
    // execute some code
	$item_to_adjust = $_REQUEST['item_to_adjust'];
	@$addItem = $_REQUEST['addBtn'];
	@$subtractItem = $_REQUEST['subtractBtn'];
	$i = 0;
	foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $item_to_adjust) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  $currQty = $each_item['quantity'];
					  //check stock level and update item stock
					  $items = $products->loadItem($item_to_adjust);
					  $currStock = $items[0]['stock'];
					  if(isset($addItem))
					  {
						  $newQty = $currQty + 1;
						  if($currStock !=0)
						  {
							  $newStock = $currStock - 1;
							  $products->setProperty('stock',$newStock);
							  $products->updateStockLevel($item_to_adjust);
							  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $newQty)));
						  }
						  else
						  {
							#stock level less than quantity selected
							$msg = "We have reached our stock limit, please reduce quantity!";
							$msg_type = "warning";
							header("location:".__BASE_URL__."users/cart.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
							exit;
						  }
					  }
					  elseif(isset($subtractItem))
					  {
						  $newQty = $currQty - 1;
						  if($newQty !=0)
						  {
							  $newStock = $currStock + 1;
							  $products->setProperty('stock',$newStock);
							  $products->updateStockLevel($item_to_adjust);
							  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $newQty)));
						  }
						  else
						  {
							#you cannot reduce to zero except you want to remove item completely
							$msg = "Use the remove button if you wish to remove item!";
							$msg_type = "warning";
							header("location:".__BASE_URL__."users/cart.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
							exit;
						  }
					  }
				  } // close if condition
		      } // close while loop
	} // close foreach loop
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 4 (if user wants to remove an item from cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_REQUEST['index_to_remove']) && $_REQUEST['index_to_remove'] != "") {
    // Access the array and run code to remove that array index
 	$key_to_remove = $_REQUEST['index_to_remove'];
	//update item stock
	foreach($_SESSION["cart_array"] as $key=>$value)
	{
		if($key == $key_to_remove)
		{
			$itemID = $value['item_id'];
			$addQty = $value['quantity'];
			$items = $products->loadItem($itemID);
			$currQty = $items[0]['stock'];
			$newQty = $addQty + $currQty;
			$products->setProperty('stock',$newQty);
			$products->updateStockLevel($itemID);
		}//end if
	}//end foreach
	if (count($_SESSION["cart_array"]) <= 1) {
		unset($_SESSION["cart_array"]);
	} else {
		unset($_SESSION["cart_array"]["$key_to_remove"]);
		sort($_SESSION["cart_array"]);
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 5  (render the cart for the user to view on the page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cartOutput = "";
$vatTotal = 0;
$cartTotal = 0;
$cTotal = 0;
$pp_checkout_btn = '';
$product_id_array = '';
if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
    $cartOutput = '<span style="color:#f00">Your shopping cart is empty</span>';
} else {
	$pp_checkout_btn .= '<form action="" method="post">';
	// Start the For Each loop
	$i = 0; 
    foreach ($_SESSION["cart_array"] as $each_item) { 
		$item_id = $each_item['item_id'];
		$allProducts = $products->loadByVariation($item_id);
		foreach($allProducts as $allProduct) {
			$productID = $allProduct["productID"];
			$product_name = $allProduct["productName"];
			$vat = $allProduct['price'] * 0.2;
			$price = $allProduct['price'];
			$imageURL = (empty($allProduct["productPicture"])) ?'no-image.jpg':$allProduct["productPicture"];
			foreach($allSizes as $key=>$value){
				if($allProduct["sizeID"] == $value['sizeID']){
						$size = $value['sizeDescription'];
				}
			}
		}
		$priceToDisplay = number_format($price + $vat,2);
		
		$pricetotal = number_format($priceToDisplay * $each_item['quantity'],2);
		$cartTotal = number_format($pricetotal + $cartTotal,2);
		
		$pTotal = number_format($price * $each_item['quantity'],2);
		$cTotal = number_format($pTotal + $cTotal,2);
		
		$vatTotal = number_format($cartTotal - $cTotal,2);
		
		setlocale(LC_MONETARY, "en_EN");
        #$pricetotal = money_format("%10.2n", $pricetotal);
		// Dynamic Checkout Btn Assembly
		$x = $i + 1;
		$pp_checkout_btn .= '<input type="hidden" name="item_name_' . $x . '" value="' . $product_name . '">
        <input type="hidden" name="amount_' . $x . '" value="' . $priceToDisplay . '">
        <input type="hidden" name="quantity_' . $x . '" value="' . $each_item['quantity'] . '">  ';
		// Create the product array variable
		$product_id_array .= "$item_id-".$each_item['quantity'].","; 
		// Dynamic table row assembly
		$cartOutput .= "<tr>";
		$cartOutput .= '<td><img src="'.__BASE_URL__.'images/products/' . $imageURL . '" alt="' . $product_name. '" width="40" height="52" border="1" /></td>';
		$cartOutput .= '<td><a href="'.__BASE_URL__.'product.php?pid=' . $productID . '">' . $product_name . '</a></td>';
		$cartOutput .= '<td>' . $size . '</td>';
		$cartOutput .= '<td>&pound;' . $priceToDisplay . '</td>';
		$cartOutput .= '<td><form action="cart.php" method="post">'.$each_item['quantity'] .'<br>
		<input name="subtractBtn" type="submit" value="-" />
		<input name="addBtn" type="submit" value="+" />
		<input name="item_to_adjust" type="hidden" value="' . $item_id . '" />
		</form></td>';
		//$cartOutput .= '<td>' . $each_item['quantity'] . '</td>';
		$cartOutput .= '<td>&pound;' . $pricetotal . '</td>';
		$cartOutput .= '<td><form action="cart.php" method="post"><input name="deleteBtn' . $item_id . '" type="submit" value="X" /><input name="index_to_remove" type="hidden" value="' . $i . '" /></form></td>';
		$cartOutput .= '</tr>';
		$i++; 
    } 
	setlocale(LC_MONETARY, "en_EN");
    #$cartTotal = money_format("%10.2n", $cartTotal);
	$cartTotals = "<div style='font-size:18px; margin-top:12px;' align='right'>Total VAT charged: &pound;".$vatTotal." GBP</div>";
	$cartTotals .= "<div style='font-size:18px; margin-top:12px;' align='right'>Total(VAT inclusive) : &pound;".$cartTotal." GBP</div>";
	#$total=$cartTotal+$vatValue;
	#$cartTotals .= "<div style='font-size:18px; margin-top:12px;' align='right'>Total : &pound;".$total." GBP</div>";'</form>';
}

#close connection to database because they say is best practice
include_once(__ROOT__."include/db.close.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Welcome to Isherwood Athletics</title>
        <link href="<?php echo __BASE_URL__ ;?>css/style.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
            
        <div id="container">
        
            <!-- header starts here -->
                <?php include_once(__ROOT__."include/header.inc.php") ;?>
            <!-- header ends here -->
            
            <div id="mainbody">
            <!-- sidebar starts here -->
            	<?php include_once(__ROOT__.'include/sidebar.inc.php') ;?>
            <!-- sidebar ends here -->
                        
            <div id="maincontent">
            
            <!-- notification starts here -->
            	<?php include_once(__ROOT__.'include/notification.inc.php') ;?>
            <!-- notification ends here -->	
			
            <p id="formstatus" style="font-size:12px;margin-bottom:15px">
                <?php
                
                    if(@$feedback != "")
                    {
                    	echo $feedback;
                    }
                
                ?>				  
            </p>
            <h1>
                Shopping Cart
            </h1>
            <p>&nbsp;</p>
			<?php
                if($cart_count >= 1){
            ?>
            <table width="100%" border="1" cellspacing="1" cellpadding="6">
            	<thead style="background-color:#969; color:#FFF; text-align:center">
                	<tr>
                        <td width="119">Image</td>
                        <td width="281">Product Name</td>
                    	<td width="149">Size</td>
                        <td width="104">Unit Price</td>
                        <td width="116">Quantity</td>
                        <td width="124">Total</td>
                        <td width="98">Action</td>
                    </tr>
                 </thead>
			  <?php };?>
              <tbody align="center">
             <?php echo $cartOutput; ?>
             </tbody>
             </table> 
   			 <?php echo @$cartTotals; ?>
             <?php
					if($cart_count >= 1){
			  ?>
				 <div><a href="complete_checkout.php?total=<?php echo $cartTotal;?>">Click here to check out</a></div>
				<br />
				 <div><a href="<?php echo __BASE_URL__ ;?>">Click here to continue shopping</a></div>
				<br />
				<a href="cart.php?cmd=emptycart">Click Here to Empty Your Shopping Cart</a>
				  <?php };?>
             </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
          </div>
       </div>
                
    </body>
    
</html>
