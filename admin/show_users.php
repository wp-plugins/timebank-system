<!-- TIMEBANK STYLE INIT -->	
<div class="timebank">

<?php
error_reporting( E_ERROR | E_WARNING | E_PARSE );

include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php');
	
$username = "";

//SHOW USERS TABLE
if ($_POST['search']){ 
	$username = $_POST['username'];
	$result = getUsers($username); 
	}else{
	$result = getUsers(); 	
	}
if ($_POST['reset']) $result = getUsers();

?>

<div class="wrap">
<form method="post">
	<input type="hidden" name="page" value="timebank" />
	<p style="font-size:20px;"><strong>TIME-BANK USERS</strong></p> <strong>SEARCH USER:</strong> 
	<input type="text" name="username" placeholder="user name" width=20 value="<?php echo $username; ?>"> <input name="search" type="submit" value="SEARCH" />
	<input name="reset" type="submit" value="RESET" />
</form>
<hr></div>

<?php
$config = getConfiguration();

	echo "
	<table>
	<th>User</th>
	<th>Created</th>
	<th>Deactivated</th>
	<th>Max. Limit</th>
	<th>Min. Limit</th>
	<th>Balance (" . $config->currency . ")</th>
	<th>status</th>
	<th>Total Sells</th>
	<th>Total Buys</th>
<!--	<th>Positive Sells</th>
	<th>Negative Sells</th>
	<th>Positive Buys</th>
	<th>Negative Buys</th>-->
	<th></th>
	<tr>";
	
	foreach ($result as $res) {
	
		echo "<td style=\"background-color:#D73; color:#fff;\">"; 
		if ($res->user_login){ 
			echo $res->user_login; 
		}else{ 
			$config = getConfiguration(); 
			echo $config->default_anonymous;
		} 
		echo "</td>";
		echo "<td>" . $res->datetime_created . "</td>";
		echo "<td>" . $res->datetime_deactivated . "</td>";
		echo "<td>" . $res->max_limit . "</td>";
		echo "<td>" . $res->min_limit . "</td>";
		echo "<td>" . $res->balance . "</td>";
		echo "<td>" . $res->type . "</td>";
		echo "<td>" . $res->total_sell_transfers . "</td>";
		echo "<td>" . $res->total_buy_transfers . "</td>";
		/* echo "<td>" . $res->total_sell_positive_rating . "</td>";
		echo "<td>" . $res->total_sell_negative_rating . "</td>";
		echo "<td>" . $res->total_buy_positive_rating . "</td>";
		echo "<td>" . $res->total_buy_negative_rating . "</td>"; */
		echo "<td><a class=button href='admin.php?page=timebank_edituser&userid=" . $res->fk_wpuser . "'>EDIT</a></td>";
		echo "<tr>";		 
	}
	
	echo "</table>";
?>

<!-- TIMEBANK STYLE CLOSE -->	
</div>