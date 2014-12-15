<?php 
$pageID = '';
//load DB and App Config
require_once("../include/db.connect.inc.php");
require_once("../include/app.config.inc.php");

require(__ROOT__."library/users.class.php");
include(__ROOT__."library/products.class.php");
include(__ROOT__."library/orders.class.php");

$products = new Products();
$orders = new Orders();

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php");


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//      (check out)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

#Luhn check
#Luhn algorithm
function validLuhn($number) {
    for ($sum = 0, $i = strlen($number) - 1; $i >= 0; $i--) {
        $digit = (int) $number[$i];
        $sum += (($i % 2) === 0) ? array_sum(str_split($digit * 2)) : $digit;
    }
    return (($sum % 10) === 0);
}



if(isset($_REQUEST['total'])) $total=$_REQUEST['total'];
if (isset($_REQUEST['complete_checkout'])) {
	$cc = $_REQUEST['cc'];

	if(validLuhn($cc) == false)
	{
		echo '<script>alert("Please enter a valid credit card details!")</script>';
	}
	else
	{
		$userID = $userHandle->getProperty("userID");
		$orders->setProperty('userID',$userID);
		$orders->setProperty('cc',$cc);
		$orders->setProperty('total',$total);
		$orders->saveOrder();
		$orderID = $orders->getProperty('orderID');
		
		foreach ($_SESSION["cart_array"] as $each_item) { 
			$item_id = $each_item['item_id'];
			$quantity = $each_item['quantity'];
			$product = $products->loadByVariation($item_id);
			foreach($product as $key=>$value)
			{
				$price = $value['price'];
			}
			$amount = $quantity * $price;
			$orders->setProperty('variationID',$item_id);
			$orders->setProperty('quantity',$quantity);
			$orders->setProperty('amount',$amount);
			$orders->saveOrderDetails($orderID);
		}
		header("location: order_complete.php?orderID=$orderID");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Welcome to Isherwood Athletics</title>
        <link href="<?php echo __BASE_URL__ ;?>css/style.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript">
		
			function validateUpdate()
			{
				
				var obj = null;
				var errorMsg = "";
				var error = false;
								
				//credit card needs to be entered
				obj = document.getElementById("cc");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please enter your credit card details!</span>";
					error = true; 
				}		
							
				//get form status			
				if(error == true)
				{
					var obj = document.getElementById("formstatus");
					obj.innerHTML = errorMsg;
					obj.style.display = "";
					
					var obj = document.getElementById("complete_checkout");
					obj.disabled = false;
					
					return false;
				}
				return true;
			}
					
        </script>
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
                Complete Checkout
            </h1>
            <p>&nbsp;</p>
			<h3>Please complete to check out, amount to be collected from your card is &pound;<?php echo $total ;?></h3>
              <form class="new" action="" method="post" onSubmit="return validateUpdate();">
              <table>
                <tr>
                    <td>Enter your Credit Card Number: </td>
                    <td><input type="text" name="cc" id="cc"  /></td>
                    <td><input name="complete_checkout" type="submit" id="complete_checkout" value="Complete" /></td>
                 </tr>
                </table>
              </form>
             </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
          </div>
       </div>
</body>
</html>