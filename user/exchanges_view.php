<div class="container">
<?php
// USER VIEW
error_reporting( E_ERROR | E_WARNING | E_PARSE );
global $bp; //BuddyPress global

include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php');

$config = getConfiguration();

	// If user is set = Show the user. If is not set = Show current user
	if ($_GET['user']!=""){ 
		$user = $_GET['user'];
	}else{
            $current_user = wp_get_current_user();
            $user = $current_user->user_login;
            //IF buddy
            if(isset($bp)){
                if (bp_get_displayed_user_username()) $user = bp_get_displayed_user_username();
            }
	}
	//get user ID
	$userId = get_user_by( 'login', $user);  
	$userId = (string) $userId->ID;
	$pathToTimebank = pathToTimebank();
	$username = "";

	// IF RESET = Show current user
	if ($_POST['reset']){ 
		$user = "";
		$current_user = wp_get_current_user();
		$user = $current_user->user_login;
		$userId = get_user_by( 'login', $user);  
		$userId = (string) $userId->ID;
	}

	//IF SEARCH = SHOW USERS TABLE
	if ($_POST['search']){ 
		$username = $_POST['username'];
	}

if(!isset($bp)){
?>

<!-- SEARCH USERS (Shows if buddy not detected) -->
<form method="post">
	<input type="hidden" name="page" value="timebank" />
	<p style="font-size:16px;"><strong>SEARCH USERS:</strong> 
	<input type="text" name="username" placeholder="user name" width=20 value="<?php echo $username; ?>"> <input name="search" type="submit" value="SEARCH" />
	<input name="reset" type="submit" value="RESET" />
</form>

<?php
}

	//SHOW USERS SEARCH
	if ($result = showUsers($username)){
                echo '<font style="font-size:24px;"><strong>Search results:</font><br />';
		echo "
		<table>
		<th>User</th>
		<th>Created</th>
		<th>Balance ($config->currency)</th>
		<th>status</th>
		<th>Total Sells</th>
		<th>Total Buys</th>
		<!--<th>Positive Sells</th>
		<th>Negative Sells</th>
		<th>Positive Buys</th>
		<th>Negative Buys</th>-->
		<tr>";
		
		foreach ($result as $res) {
		
			echo "<td style=\"background-color:#ccc; color:#fff;\">"; 
			if ($res->user_login){ 
				echo "<a href=" . $pathToTimebank . "?user=" . $res->user_login . "><strong>" . $res->user_login . "</strong></a>";
			}else{ 
				$config = getConfiguration(); 
				echo $config->default_anonymous;
			} 
			echo "</td>";
			echo "<td>" . $res->datetime_created . "</td>";
			echo "<td>" . $res->balance . "</td>";
			echo "<td>" . $res->type . "</td>";
			echo "<td>" . $res->total_sell_transfers . "</td>";
			echo "<td>" . $res->total_buy_transfers . "</td>";
			echo "<tr>";		 
		}
		
		echo "</table>";
              
                
	}elseif ($_POST['reset']){
		echo "";
	}elseif ($username!=""){
                echo '<font style="font-size:24px;"><strong>Search results:</font><br />';
		echo "User not found<br /><br />";
                      
	}
	// END SEARCH	

        // If user is not created in TBANK database create it!
	if (!$userData = getUserData ($userId)){
            if (createUser($userId)){
            echo 'Timebank user Created! (Refreshing...)';
            echo '<script>parent.window.location.reload(true);</script>';
            }
        }

	//SHOW USER STATS
	if ($user && $userData){
		echo '<font style="font-size:24px;"><strong>' . $user . '</strong> TimeBank stats</font><br />';
		echo "<div class=userstats>";
			echo "<div>Balance:<br /> $userData->balance $config->currency </div>";
                        echo "<div>User $config->currency limits:<br />Max: $userData->max_limit - Min: $userData->min_limit</div>";
                        echo "<div>Status: <br />$userData->type</div>";  
                        echo "<div>Totals Sells:<br /> $userData->total_sell_transfers</div>";
                        echo "<div>Total Buys:<br /> $userData->total_buy_transfers</div>";
                        //Future echo "<div>Sells Rating: <br />+ $userData->total_sell_positive_rating <br /> $userData->total_sell_negative_rating</div>";
			//Future echo "<div>Purchase Rating: <br />+ $userData->total_buy_positive_rating <br /> $userData->total_buy_negative_rating</div>";
			echo '<div style="clear:both; padding:0px; margin:0px; border:0px;"></div>';				
		echo '</div><div style="clear:both";></div><br />';
	}else{
		echo '<br /><br />You need to log to your Wordpress Account before using Timebank!';
	}

	//print button NEW REQUEST if user is logged in
	if (isWpUser()){ 
            //print NEW REQUEST BUTTON + ajax function
			echo '<a href="#TB_inline?width=460&height=330&inlineId=inline1" class="thickbox" title="" style="padding: 8px; background-color: #ddd; float:right; margin-right:10px;">NEW TIME REQUEST</a>';
            include_once( plugin_dir_path( __FILE__ ) . 'new_request.php');
            //print button ACCEPT / REJECT + ajax function
            include_once( plugin_dir_path( __FILE__ ) . 'validate_transfer.php');
            //print button COMMENT + ajax function
            include_once( plugin_dir_path( __FILE__ ) . 'comment_transfer.php');
	}


//SHOW PURCHASE
	$result = purchaseView ($userId);
	
	echo '<br /><br /><font style="font-size:16px;"><strong><!--' . $user. '--> Buys</strong></font>';
	echo '<table style="background-color:#fff; width:99%;">';
	echo "<th>Date</th>";
	echo "<th>Seller</th>";	
	echo "<th>Concept</th>";
	echo "<th>$config->currency</th>";
	echo "<th>Rating</th>";
	echo "<th>Comment</th>";
	echo "<th>Status</th>";
	echo "<tr>";


	foreach ($result as $res) {
		echo "<td>" . $res->datetime_created . "</td>";
		echo "<td id=user_value" . $res->id . "><!--<a href=" . $pathToTimebank . "?user=" . $res->user_login . ">-->" . $res->user_login . "</td>";	
		echo "<td id=concept_value" . $res->id . ">" . $res->concept . "</td>";
		echo "<td id=amount_value" . $res->id . ">" . $res->amount . "</td>";
		echo "<td id=rating_value" . $res->id . "><div class=rateit data-rateit-value=" . $res->rating_value . " data-rateit-ispreset=true data-rateit-readonly=true></div></td>";
		echo "<td id=rating_comment" . $res->id . ">" . $res->rating_comment . "</td>";

		$validatePath= "validate_transfer.php?id=" . $res->id;
		$commentPath= "comment_transfer.php?exchangeId=" . $res->id;
		
		//View $options if user is user viewer
		if ($res->id_buyer == get_current_user_id()){ 

			//Pending
			if ($res->status == "1") { echo "<td id=status" . $res->id . " class=\"alert\">" . $res->type . "<br /><a id=". $res->id ." href=#TB_inline?width=460&height=250&inlineId=inline2 class=\"thickbox validate\">Accept / Reject</a>"; }
			//Accepted
			if ($res->status == "2") { echo "<td id=status" . $res->id . " class=accepted>" . $res->type . "<br /><a id=". $res->id ." href=#TB_inline?width=460&height=320&inlineId=inline3 class=\"thickbox comment\">Comment</a>"; }	
			//Completed
			if ($res->status == "3") { echo "<td id=status" . $res->id . " class=completed>" . $res->type . "<a>"; }
			//Rejected		
			if ($res->status == "4") { echo "<td id=status" . $res->id . " class=rejected>" . $res->type . "<a>"; }
			//Cancelled
			if ($res->status == "5") { echo "<td id=status" . $res->id . " class=rejected>" . $res->type . "<a>"; }		

			echo "</td>"; 
			
		}else{ 

			//Pending
			if ($res->status == "1") { $options = "<td id=status" . $res->id . ">" . $res->type; }
			//Accepted
			if ($res->status == "2") { $options = "<td id=status" . $res->id . ">" . $res->type; }
			//Completed
			if ($res->status == "3") { $options = "<td id=status" . $res->id . ">" . $res->type; }
			//Rejected		
			if ($res->status == "4") { $options = "<td id=status" . $res->id . ">" . $res->type; }
			//Cancelled
			if ($res->status == "5") { $options = "<td id=status" . $res->id . ">" . $res->type; }		

			echo "$options</td>"; 

		}
		echo "<tr>";
	}
	echo '</table>';
	echo "<br /><br />";	

// SHOW SALES
	$result = salesView ($userId);
	
	echo '<font style="font-size:16px;"><strong><!--' . $user . '--> Sales</strong></font>';
	echo '<table style="background-color:#fff; width:99%;">';
	echo "<th>Date</th>";
	echo "<th>Buyer</th>";	
	echo "<th>Concept</th>";
	echo "<th>$config->currency</th>";
	echo "<th>Rating</th>";
	echo "<th>Comment</th>";
	echo "<th>Status</th>";
	echo "<tr>";

	foreach ($result as $res) {
	
		echo "<td>" . $res->datetime_created . "</td>";	
		echo "<td>" . $res->user_login . "</td>";	
		echo "<td>" . $res->concept . "</td>";
		echo "<td>" . $res->amount . "</td>";
		echo "<td id=rating_value" . $res->id . "><div class=rateit data-rateit-value=" . $res->rating_value . " data-rateit-ispreset=true data-rateit-readonly=true></div></td>";
		echo "<td>" . $res->rating_comment . "</td>";
		echo "<td class=\"status\">" . $res->type . "</td>";
		echo "<tr>";
	}
	echo '</table>';
	echo "<br /><br />";
	
?>
</div>