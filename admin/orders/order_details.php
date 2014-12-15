<?php
$pageID = '';
//load DB and App Config
require_once("../../include/db.connect.inc.php");
require_once("../../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

require_once(__ROOT__."library/orders.class.php");

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php");

//check if user is admin
require_once(__ROOT__."include/admin.check.inc.php");

$order = new Orders();

//ship item(s)
if(isset($_REQUEST['btnShip']) && isset($_REQUEST['hdnShip']))
{
	#update orderStatus to shipped
	$orderID = $_REQUEST['hdnShip'];
	$result = $order->setStatus($orderID,'s');
	if($result)
	{
		#update successful and redirect to index page
		$msg = "Order update was Successful!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/orders/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#update was NOT successful and redirect to index page
		$msg = "Order update was NOT Successful!";
		$msg_type = "warning";
		header("location:".__BASE_URL__."admin/orders/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
}

//deliver item(s)
if(isset($_REQUEST['btnDeliver']) && isset($_REQUEST['hdnShip']))
{
	#update orderStatus to delivered
	$orderID = $_REQUEST['hdnShip'];
	$result = $order->setStatus($orderID,'d');
	if($result)
	{
		#update successful and redirect to index page
		$msg = "Order update was Successful!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/orders/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#update was NOT successful and redirect to index page
		$msg = "Order update was NOT Successful!";
		$msg_type = "warning";
		header("location:".__BASE_URL__."admin/orders/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
}

//deliver item(s)
if(isset($_REQUEST['btnReturn']) && isset($_REQUEST['hdnShip']))
{
	#update orderStatus to delivered
	$orderID = $_REQUEST['hdnShip'];
	$result = $order->setStatus($orderID,'r');
	if($result)
	{
		#update successful and redirect to index page
		$msg = "Order update was Successful!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/orders/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#update was NOT successful and redirect to index page
		$msg = "Order update was NOT Successful!";
		$msg_type = "warning";
		header("location:".__BASE_URL__."admin/orders/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
}

#get orderID
if(isset($_REQUEST['orderID']) && $_REQUEST['orderID'] !='')
{
	$orderID = $_REQUEST['orderID'];
}

$userID = $userHandle->getProperty("userID");
$order->setProperty('userID',$userID);

$allOrders = array();
$allOrders = $order->load($orderID);

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
                Order Details
            </h1>
            <p>&nbsp;</p>
            
            <div style="float:right">
            	<table style="border:solid 1px">
                	<tr>
                		<td>Date: </td>
                        <td><?php echo date('D d M Y g:i:s A',$paymentDate) ;?></td>
                    </tr>
                	<tr>
                		<td>Order No.: </td>
                        <td><?php echo $orderNo ;?></td>
                    </tr>
                	<tr>
                		<td>Order Status: </td>
                        <td>
							<?php 
							foreach($order->getProperty('statusTitle') as $key=>$value)
							{
								echo ($orderStatus == $key) ?$value : "";
							}
							?>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="clear:both">&nbsp;</div>
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
                        <td width="119">Image</td>
                        <td width="281">Product Name</td>
                        <td width="104">Unit Price</td>
                        <td width="116">Quantity</td>
                        <td width="124">Total</td>
                    </tr>
                 </thead>
              <tbody align="center">
            <?php
				$subTotal = '';
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
                 <td>&pound;<?php echo $allOrder['price'] ;?></td>
                 <td><?php echo $allOrder['quantity'] ;?></td>
                 <?php $totalPrice = number_format($allOrder['quantity'] * $allOrder['price'],2) ;?>
                 <td>&pound;<?php echo $totalPrice ;?></td>
                </tr>
             <?php 
			 		$subTotal = $subTotal + $totalPrice;
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
                	<td>SubTotal: </td>
                	<td>&pound;<?php echo number_format($subTotal,2) ;?></td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>VAT: </td>
                	<td>&pound;<?php echo $vat = number_format($subTotal * 0.2,2) ;?></td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>Total: </td>
                	<td style="background-color:#969; color:#FFF; text-align:center">&pound;<?php echo $subTotal + $vat ;?></td>
                </tr>
             </tbody>
             </table> 
             </div>
            <div style="float:right; margin-right:20px">
                 <form name="frmUpdateOrder" action="" method="post">
                    <input type="hidden" name="hdnShip" value="<?php echo $orderNo ;?>" />
                    <?php 
						if($orderStatus == 'p')
						{
					?>
                    <input type="submit" name="btnShip" value="Ship" />
                    <?php
						} 
						elseif($orderStatus == 's')
						{
					?>
                    <input type="submit" name="btnDeliver" value="Confirm Delivery" />
                    <?php
						}
						elseif($orderStatus == 'd')
						{
					?>
                    <input type="submit" name="btnReturn" value="Return Item(s)" />
                    <?php
						}
					?>
                 </form>
	     	</div>            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
            </div>
          </div>
                
    </body>
    
</html>
