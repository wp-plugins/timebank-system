<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php');

global $wpdb;
	
// UPDATE USER
if ($_POST['option']=="edit"){

	// INSERT PREPARATION
	$exchangeId = $_POST['exchangeid']; 
	$idSeller = $_POST['idseller']; 
	$idBuyer = $_POST['idbuyer']; 
	$concept = $_POST['concept']; 
	$amount = $_POST['amount']; 
	$status = $_POST['status']; 
	$rate = $_POST['ratingvalue']; 
	$comment = $_POST['ratingcomment']; 
	
	if ( $status == 2 ){ // ACCEPTED
	// UPDATE EXCHANGE
	updateExchangeStatus ($idBuyer, $exchangeId, $status);	
	// ADD EXCHANGE MANAGER
	addExchangeManager($exchangeId, 1);	
	}
	
	if ( $status == 3 ){ // COMPLETED
	// RATE EXCHANGE
	rateExchange($exchangeId, $rate, $comment, $concept);
	// ADD EXCHANGE MANAGER
	addExchangeManager($exchangeId, 1);	// OJU NO PONGAS 1 en exchange manager!
	}
	
	if ( $status == 4 ){ // REJECTED
	// UPDATE EXCHANGE
	updateExchangeStatus ($idBuyer, $exchangeId, $status);	
	// ADD EXCHANGE MANAGER
	addExchangeManager($exchangeId, 1);	
	}
	
	if ( $status == 5 ){ // CANCELLED
	// UPDATE EXCHANGE
	updateExchangeStatus ($idBuyer, $exchangeId, $status);	
	// ADD EXCHANGE MANAGER
	addExchangeManager($exchangeId, 1);	
	}
	
};

// USERS TABLE VIEW

	$exchangeId = $_GET['id']; // Get from navigator
	$exchangeData = getExchangeData ($exchangeId);
	$config = getConfiguration();
?>

<!-- TIMEBANK STYLE INIT -->	
<div class="timebank">

	<p style="font-size:20px;"><strong>TIME-BANK EDIT EXCHANGE</strong></p>
	<hr>
	
	<form action="" method="post">
		<input name="option" type="hidden" value="edit" />	
		<input name="idseller" type="hidden" value="<?php echo $exchangeData->id_seller; ?>" />	
		<input name="idbuyer" type="hidden" value="<?php echo $exchangeData->id_buyer; ?>" />
		<input name="exchangeid" type="hidden" value="<?php echo $exchangeData->id; ?>" />
		<table border=1 width=50% >
		
		<?php
		echo "<td>ID:</td><td>" . $exchangeData->id . "</td><tr />";
		echo "<td>Seller:</td><td>" . $exchangeData->seller_name . "</td><tr />";
		echo "<td>Buyer:</td><td>" . $exchangeData->buyer_name . "</td><tr />";
		echo "<td>Date Creation:</td><td>" . $exchangeData->datetime_created . "</td><tr />";
		echo "<td>Date Accetepd:</td><td>" . $exchangeData->datetime_accepted . "</td><tr />";
		echo "<td>Date Finalized:</td><td>" . $exchangeData->datetime_finalized . "</td><tr />";
		echo "<td>Date Denied:</td><td>" . $exchangeData->datetime_denied . "</td><tr />";
		echo "<td>Date Cancellation:</td><td>" . $exchangeData->datetime_cancelled . "</td><tr />";
		echo "<td>Concept:</td><td><input type=text name=concept value='" . $exchangeData->concept . "'></td><tr />";
		echo "<td>Amount (". $config->currency ."):</td><td><input type=text name=amount disabled value='" . $exchangeData->amount . "'></td><tr />";
		echo "<td>Status:</td><td>";
		
		$statusType = getExchangeStatusType();
		
		foreach ($statusType as $type){
			if ($exchangeData->status == $type->id) echo "$type->type &nbsp; ";
		}

			switch ($exchangeData->status){
				case 1:
					echo "<select name=status>
					<option value='2'>Accept</option>
					<option value='4'>Reject</option></select>
					</td><tr />";
					break;	
				
				case 2:
				case 3:
				
					if ($exchangeData->rating_value == 1) $selected1 = "selected";
					if ($exchangeData->rating_value == 2) $selected2 = "selected";
					if ($exchangeData->rating_value == 3) $selected3 = "selected";
					if ($exchangeData->rating_value == 4) $selected4 = "selected";
					if ($exchangeData->rating_value == 5) $selected5 = "selected";	
								
					echo "<select name=status>
					<option value='3'>Completed</option>
					<!--<option value='5'>Cancelled</option>-->
					</select>
					</td><tr />
					<td>Rate:</td><td>

						<select id=rate name=ratingvalue >
						<option value=0>0</option>
						<option value=1 " . $selected1 . ">1</option>
						<option value=2 " . $selected2 . ">2</option>
						<option value=3 " . $selected3 . ">3</option>
						<option value=4 " . $selected4 . ">4</option>
						<option value=5 " . $selected5 . ">5</option>
						</select>
					
					</td><tr />
					<td>Comment:</td><td><input type=text name=ratingcomment value='" . $exchangeData->rating_comment . "'></td>";
					break;		
			}				 			
		?>
		
		<td colspan="2" style="background-color:#fff;">	<input type="submit" value="SAVE DATA" style="float:right;" class="button" /></td>
		</table>
	</form>

<!-- TIMEBANK STYLE CLOSE -->	
</div>