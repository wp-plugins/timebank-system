<!-- TIMEBANK STYLE INIT -->	
<div class="timebank">

<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE );

include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php');

$username = "";

//SHOW USERS TABLE
if ($_POST['search']){ 
	$username = $_POST['username'];
	$result = exchangesView($username); 
	}else{
	$result = exchangesView(); 	
	}
if ($_POST['reset']) $result = exchangesView();

$config = getConfiguration();

echo '<div class="wrap">';
// SEARCH MODULE
?>

<form method="post">
	<input type="hidden" name="page" value="timebank" />
	<p style="font-size:20px;"><strong>TIME-BANK EXCHANGES</strong></p> <strong>SEARCH EXCHANGE BY USER:</strong> 
	<input type="text" name="username" placeholder="user name" width=20 value="<?php echo $username; ?>"> <input name="search" type="submit" value="SEARCH" />
	<input name="reset" type="submit" value="RESET" />
	<div style="float:right;" class="button"><a href="admin.php?page=timebank_newexchange">NEW EXCHANGE</a></div>
</form>

<?php
	echo '<hr></div>';
	
	echo "
	<table>
	<th>id</th>
	<th>Seller</th>
	<th>Buyer</th>
	<th>Date Creation</th>
	<th>Date Accept</th>
	<th>Date Finalized</th>
	<th>Date Denied</th>
	<th>Date Cancelled</th>
	<th>Concept</th>
	<th>" . $config->currency . "</th>
	<th>Status</th>
	<th>Rating</th>
	<th>Comment</th>
	<th></th>
	<tr>";
	
	foreach ($result as $res) {
	
		echo "<td  style=\"background-color:#559; color:#fff;\">" . $res->exchangeid . "</td>";
		echo "<td>" . $res->user_login . "</td>";
		
			//Get Buyer name by Id
			$buyerName = userIdToUserName ($res->id_buyer);
			
		echo "<td>" . $buyerName . "</td>";
		echo "<td>" . $res->datetime_created . "</td>";
		echo "<td>" . $res->datetime_accepted . "</td>";
		echo "<td>" . $res->datetime_finalized . "</td>";
		echo "<td>" . $res->datetime_denied . "</td>";
		echo "<td>" . $res->datetime_cancelled . "</td>";
		echo "<td>" . $res->concept . "</td>";
		echo "<td>" . $res->amount . "</td>";
		echo "<td>" . $res->type . "</td>";
		echo "<td><div class=rateit data-rateit-value=" . $res->rating_value . " data-rateit-ispreset=true data-rateit-readonly=true></div></td>";
		echo "<td>" . $res->rating_comment . "</td>";
		echo "<td><a class=button href='admin.php?page=timebank_editexchange&id=" . $res->exchangeid . "'>EDIT</a></td>";
		echo "<tr>";		 
	}
	
	echo "</table>";
?>

<!-- TIMEBANK STYLE CLOSE -->	
</div>