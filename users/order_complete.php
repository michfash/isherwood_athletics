<?php 
$pageID = '';
//load DB and App Config
require_once("../include/db.connect.inc.php");
require_once("../include/app.config.inc.php");

require(__ROOT__."library/users.class.php");
include(__ROOT__."library/products.class.php");
include(__ROOT__."library/orders.class.php");

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php"); 


if(isset($_REQUEST['orderID']) && $_REQUEST['orderID'] !='')
{
	$orderID = $_REQUEST['orderID'];
}

$order = new Orders();

$userID = $userHandle->getProperty("userID");
$order->setProperty('userID',$userID);

$allOrders = array();
$allOrders = $order->loadOrderByUser($orderID);

foreach($allOrders as $orders)
{
	$orderNo = $orders['orderID'];
	$paymentDate = $orders['paymentDate'];
	$fullName = $orders['firstName'].' '.$orders['lastName'];
	$address = $orders['address'];
	$phone = $orders['phone'];
	$email = $orders['email'];
	$cc = $orders['cc'];
	$maskedCC = '############'.substr($cc,12,4);
	$total = number_format($orders['total'],2);
	$orderStatus = $orders['orderStatus'];
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Empty cart
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  unset($_SESSION["cart_array"]);
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
                Checkout Complete
            </h1>
            <p>&nbsp;</p>
              <div style="color:green">
                <h3>Payment Received!!!</h3>
                <p>Your order is being processed</p>
              </div>
            <p>&nbsp;</p>
              
              <div style="float:left">
            	<table style="border:solid 1px">
                <thead style="background-color:#969; color:#FFF; text-align:center">
                	<tr>
                    	<td>Bill To:</td>
                    </tr>
                </thead>
                	<tr>
                		<td><strong><?php echo $fullName ;?></strong></td>
                    </tr>
                	<tr>
                		<td><?php echo $address ;?></td>
                    </tr>
                    <?php
						if($phone !='')
						{
					?>
                	<tr>
                		<td><?php echo $phone ;?></td>
                    </tr>
                    <?php
						}
					?>
                	<tr>
                		<td><?php echo $email ;?></td>
                    </tr>
                </table>
            </div>
            
            <div style="float:left; margin-left:20px">
            	<table style="border:solid 1px">
                <thead style="background-color:#969; color:#FFF; text-align:center">
                	<tr>
                    	<td>Payment Information:</td>
                    </tr>
                </thead>
                	<tr>
                		<td><strong><?php echo $fullName ;?></strong></td>
                    </tr>
                	<tr>
                		<td><?php echo $maskedCC ;?></td>
                    </tr>
                	<tr>
                		<td><?php echo '&pound;'.$total ;?></td>
                    </tr>
                </table>
            </div>
            
            <div style="clear:both">&nbsp;</div>
            	
            <table width="100%" border="1" cellspacing="1" cellpadding="6">
            	<thead style="background-color:#969; color:#FFF; text-align:center">
                	<tr>
                        <td width="157">Image</td>
                        <td width="352">Product Name</td>
                        <td width="111">Unit Price</td>
                        <td width="218">Quantity</td>
                        <td width="166">Total</td>
                    </tr>
                 </thead>
              <tbody align="center">
            <?php
				#$subTotal = 0;
				$grandTotal = 0;
				foreach($allOrders as $allOrder)
				{
			?>
            	<tr>
                <?php if(!empty($allOrder['productPicture'])){ ?>
                	<?php echo '<td><img src="'.__BASE_URL__.'images/products/' . $allOrder['productPicture'] . '" alt="' . $allOrder['productName']. '" width="40" height="52" border="1" /></td>'?>
                 <?php 
				 	}
					else
					{
						echo '<td><img src="'.__BASE_URL__.'images/no-image.jpg' . '" alt="' . $allOrder['productName']. '" width="40" height="52" border="1" /></td>';
					}
				 ?>
                 <td><?php echo $allOrder['productName'] ;?></td>
                 <td>&pound;<?php echo $price = number_format($allOrder['price'] +($allOrder['price'] * 0.2),2);?></td>
                 <td><?php echo $allOrder['quantity'] ;?></td>
                 <?php $totalPrice = number_format($allOrder['quantity'] * $price,2) ;?>
                 <td>&pound;<?php echo $totalPrice ;?></td>
                </tr>
             <?php 
			 		#$subTotal = $subTotal + $totalPrice;
					$grandTotal = $grandTotal + $totalPrice;
				}
			 ?>
                <tr>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td><!--SubTotal: --></td>
                	<td><!--&pound;<?php #echo number_format($subTotal,2) ;?>--></td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>Total VAT charged: </td>
                	<td>&pound;<?php echo $vat = number_format($grandTotal * 0.2,2) ;?></td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>Total(VAT inclusive): </td>
                	<td style="background-color:#969; color:#FFF; text-align:center">&pound;<?php echo $grandTotal ;?></td>
                </tr>
             </tbody>
             </table> 
              
             </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
          </div>
       </div>
</body>
</html>