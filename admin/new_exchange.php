<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php');

global $wpdb;
$config = getConfiguration();

// INSERT DATA
if ($_POST['option']=="edit"){
	$sellerName = $_POST['sellername']; 
	$buyerName = $_POST['buyername']; 
	$description = $_POST['description']; 
	$amount = $_POST['amount']; 
	
	$sellerUserId = userNameToUserId ($sellerName);
	$buyerUserId = userNameToUserId ($buyerName);	

	//transforma nombres a IDES
	if (newAdminExchange ($sellerUserId, $buyerUserId, $amount, $description)) echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=admin.php?page=timebank">';
}

?>

<!-- TIMEBANK STYLE INIT -->	
<div class="timebank">

		<?php
		// PRINT NEW REQUEST BUTTON	
		//echo '<a href="#TB_inline?width=600&height=400&inlineId=showExchangeWindow" class="thickbox" style="padding: 8px; background-color: #ddd; float:right; margin-right:10px;">' . __('NEW EXCHANGE', 'timebank') . '</a>';

		// INCLUDE NEW REQUEST html + js code	
		//include_once( plugin_dir_path( __FILE__ ) . '../user/new_exchange.php');
		?>

	<p style="font-size:20px;"><strong>TIME-BANK NEW EXCHANGE</strong></p>
	<hr>
	
	<form action="" method="post">
		<input name="option" type="hidden" value="edit" />	
		<table border=1 width=50% >
		
		<?php
		echo "<td>Seller:</td><td><input id=sellerUserName type=text name=sellername value=$_POST[sellername]></td><tr />";
		echo "<td>Buyer:</td><td><input id=buyerUserName type=text name=buyername value=$_POST[buyername]></td><tr />";
		echo "<td>Concept:</td><td><input type=text id=description name=description value=$_POST[description]></td><tr />";
		echo "<td>Amount (" . $config->currency . ") :</td><td><input type=text id=amount name=amount value=$_POST[amount]></td><tr />";	 			
		?>
		
		<td colspan="2" style="background-color:#fff;">	<input id="ACCEPT" type="submit" value="INSERT DATA" style="float:right;" class="button" /></td>
		</table>
	</form>

<!-- TIMEBANK STYLE CLOSE -->	
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery( "#ACCEPT" ).click(function(event) {
			
				//First prevent multiples clicks during 5seconds
				jQuery("#ACCEPT").hide();
					
                if ( jQuery( "#buyerUserName" ).val() == "" ){
                    alert ("Please enter user name");
					jQuery("#ACCEPT").show();
                    event.preventDefault();
                }
				
                if ( jQuery( "#sellerUserName" ).val() == "" ){
                    alert ("Please enter user name");
					jQuery("#ACCEPT").show();
                    event.preventDefault();
                }
				
                if ( isNaN(jQuery( "#amount" ).val()) || jQuery( "#amount" ).val() < 1 ){
                    alert ("Amount has to be set and positive integer");
					jQuery("#ACCEPT").show();
                    event.preventDefault();
                }
                if ( jQuery( "#description" ).val() == "" ){
                    alert ("Please enter description");
					jQuery("#ACCEPT").show();
                    event.preventDefault();
                }
	});
});
</script>