<?php
if
(
	isset($_GET['notifMsg']) && 
	isset($_GET['notifType']) &&
	$_GET['notifType'] != "" &&
	$_GET['notifMsg'] != ""
)
{
	$notification['message'] = base64_decode($_GET['notifMsg']);
	$notification['type'] = strtolower(base64_decode($_GET['notifType']));
?>
<div id="alert" class="alert_<?php echo $notification['type']; ?>" style="margin-top:0">
	<p>
		<img id="alert_img_warning" src="<?php echo __BASE_URL__ ; ?>images/icons/icon_warning.png" alt="warning" class="mid_align" style="display:<?php echo ($notification['type'] == "warning") ? "" :"none"; ?>"/>
		<img id="alert_img_info" src="<?php echo __BASE_URL__ ; ?>images/icons/icon_info.png" alt="info" class="mid_align" style="display:<?php echo ($notification['type'] == "info") ? "" :"none"; ?>"/>
		<img id="alert_img_success" src="<?php echo __BASE_URL__ ; ?>images/icons/icon_accept.png" alt="success" class="mid_align" style="display:<?php echo ($notification['type'] == "success") ? "" :"none"; ?>"/>
		<img id="alert_img_error" src="<?php echo __BASE_URL__ ; ?>images/icons/icon_error.png" alt="error" class="mid_align" style="display:<?php echo ($notification['type'] == "error") ? "" :"none"; ?>"/>
		<span id="alert_message">
		<?php
			echo $notification['message'];
		?>
		</span>
	</p>
</div>

<br class="clear"/>

<?php
}
else
{
	#DEFAULT / EMPTY NOTIFICATION 
?>
<div id="alert" class="alert_info" style="margin-top:0; display:none">
	<p>
		<img id="alert_img_warning" src="<?php echo __BASE_URL__ ; ?>images/icons/icon_warning.png" alt="warning" class="mid_align" style="display:none"/>
		<img id="alert_img_info" src="<?php echo __BASE_URL__ ; ?>images/icons/icon_info.png" alt="info" class="mid_align" style="display:none"/>
		<img id="alert_img_success" src="<?php echo __BASE_URL__ ; ?>images/icons/icon_accept.png" alt="success" class="mid_align" style="display:none"/>
		<img id="alert_img_error" src="<?php echo __BASE_URL__ ; ?>images/icons/icon_error.png" alt="error" class="mid_align" style="display:none"/>
		<span id="alert_message">&nbsp;</span>
	</p>
</div>
<?php	
}
?>