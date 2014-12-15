<?php
$pageID = '';
//load DB and App Config
require_once("../../include/db.connect.inc.php");
require_once("../../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

require_once(__ROOT__."library/products.class.php");
require_once(__ROOT__."library/orders.class.php");

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php");

//check if user is admin
require_once(__ROOT__."include/admin.check.inc.php");

#set filter and status parameters
$filterParam = array();
$orderStatus = 'a';
$statusParam = array('p','s','d','r');
if(isset($_REQUEST['orderStatus']) && $_REQUEST['orderStatus'] !='a')
{
	$orderStatus = $_REQUEST['orderStatus'];
	$statusParam = array($orderStatus);
}

$order = new Orders();
$product = new Products();

$userID = $userHandle->getProperty("userID");
$order->setProperty('userID',$userID);

// how many pages we have when using paging?
$totalNumberOfRecords = Orders::countRows($filterParam,$statusParam);

require_once(__ROOT__."include/pagination_offset_max.inc.php");
$allOrders = array();
$allOrders = $order->loadAll_filter($filterParam,$statusParam,$offset,__ROWS_PER_PAGE__);

foreach($allOrders as $value)
{
	$countOrders = $value['orderStatus'];
}

$countAll = Orders::countRows($filterParam,array('p','s','d','r'));
$countPro = Orders::countRows($filterParam,array('p'));
$countShi = Orders::countRows($filterParam,array('s'));
$countDel = Orders::countRows($filterParam,array('d'));
$countRet = Orders::countRows($filterParam,array('r'));

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
                Order History
            </h1>
            <p>&nbsp;</p>
            <div align="left">
                Displaying Page <?php echo $curr_page ?> of <?php echo $max_page?>
            </div>
            <div style="margin-bottom:15px; height: 30px; clear:both; margin-top:10px">
                <?php 
                    $criteria = "showing ".__ROWS_PER_PAGE__." most recent orders";
                ?>
                <div style="vertical-align:bottom; line-height:30px; float:left;font-size:14px"> 
                    <em id="txtStatus"><?php echo $criteria; ?></em>
                </div>
                <div style="clear:both">&nbsp;</div>
                <div style="vertical-align:bottom; line-height:30px; float:left;font-size:14px"> 
                    <em id="txtStatus"><a <?php echo ($orderStatus == 'a') ?'style="color:#F63"' :'' ;?> href="?orderStatus=a">All(<?php echo $countAll ;?>)</a> | </em>
                    <em id="txtStatus"><a <?php echo ($orderStatus == 'p') ?'style="color:#F63"' :'' ;?> href="?orderStatus=p">Processing(<?php echo $countPro ;?>) | </a></em>
                    <em id="txtStatus"><a <?php echo ($orderStatus == 's') ?'style="color:#F63"' :'' ;?> href="?orderStatus=s">Shipped(<?php echo $countShi ;?>) | </a></em>
                    <em id="txtStatus"><a <?php echo ($orderStatus == 'd') ?'style="color:#F63"' :'' ;?> href="?orderStatus=d">Delivered(<?php echo $countDel ;?>) | </a></em>
                    <em id="txtStatus"><a <?php echo ($orderStatus == 'r') ?'style="color:#F63"' :'' ;?> href="?orderStatus=r">Returned(<?php echo $countRet ;?>)</a></em>
                </div>
            </div>
            <table width="100%" height="155">
            	<thead style="background-color:#969; color:#FFF; text-align:center">
                	<tr>
                    	<td width="102">Order No.</td>
                    	<td width="256">Item</td>
                    	<td width="133">Date</td>
                    	<td width="181">Amount</td>
                    	<td width="240">Order Status</td>
                    	<td width="142">Action</td>
                    </tr>
                </thead>
                
			<?php
                if(count($allOrders)>0)
                {
					foreach($allOrders as $allOrder)
					{
            ?>
               <tbody style="text-align:center">
               <tr>
               		<td><?php echo $allOrder['orderID'] ;?></td>
                   <td style="text-decoration:underline">
                     <a href="<?php echo __BASE_URL__ ;?>admin/products/view.php?productID=<?php echo $allOrder['productID'] ; ?>"><?php echo $allOrder['productName'] ; ?></a>
                    </td> 
                   <td>
                     <?php echo date('d/m/Y',$allOrder['paymentDate']) ; ?>
                    </td> 
                   <td>
                     <?php echo '&pound;'.number_format($allOrder['total'],2) ; ?>
                    </td> 
                   <td>
				   		<?php 
							foreach($order->getProperty('statusTitle') as $key=>$value)
							{
								echo ($allOrder['orderStatus'] == $key) ?$value : "";
							}
						?>
                   </td>
                   <td>
                   	<a href="<?php echo __BASE_URL__ ;?>admin/orders/order_details.php?orderID=<?php echo $allOrder['orderID'] ; ?>">View Order</a>
                    </td>
                </tr>
                </tbody>
			<?php
            	}//end foreach
            ?>    
            <tfoot>
            <tr> 
            	<td>&nbsp;</td>
            	<td>&nbsp;</td>
                <td>
                    <!-- Begin pagination -->
                    <?php require_once(__ROOT__."include/display_pagination.inc.php"); ?>
                    <!-- End pagination -->
                 </td>
             </tr>
             
                    <?php
                            }
                            else //count = 0
                            {
                     ?>		
               <tr>
               	<td>
                    <div class="emptyRecords">
                        No Order(s) Found!
                    </div>
                    
                    <?php 
                        }//end if count > 0
                    ?>
                    </td>
                </tr>
                </tfoot>
             </table>
             </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
          </div>
       </div>
                
    </body>
    
</html>
