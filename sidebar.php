<?php
if (is_user_logged_in()){

	if ( !include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php')) echo "timebank include error";

// PREPARATION
	$config = getConfiguration();
    $userId = get_current_user_id();
    $current_user = wp_get_current_user();
    $userName = $current_user->user_login;
    $pathToTimebank = pathToTimebank();

	if ($userData = getUserData ($userId)){
		echo '<strong>' . $userName . ' Timebank:</strong>';
		echo "<div class=userstats>";
		// Timebank direct buttons 	
			echo '<a href="#TB_inline?width=450&height=300&inlineId=inline1" class="thickbox">New Time Request</a>';
			include_once( plugin_dir_path( __FILE__ ) . 'new_request.php');	
			echo '<br /><a href="' . $pathToTimebank . '">Go to your Timebank</a>';
			echo "<br /><div>Balance:<br /> $userData->balance $config->currency</div>";
                        //echo "<div>User $config->currency limits:<br />Max: $userData->max_limit - Min: $userData->min_limit</div>";
                        echo "<div>Totals Sells:<br /> $userData->total_sell_transfers</div>";
                        echo "<div>Total Buys:<br /> $userData->total_buy_transfers</div>";
						echo "<div>Status: <br />$userData->type</div>"; 
                        //echo "<div>Sells Rating: <br />+ $userData->total_sell_positive_rating <br /> $userData->total_sell_negative_rating</div>";
			//echo "<div>Purchase Rating: <br />+ $userData->total_buy_positive_rating <br /> $userData->total_buy_negative_rating</div>";
			
					
		echo '</div><div style="clear:both";></div><br />';
	}

}else{
	//echo '<center><strong>You need to log to your Wordpress Account before using Timebank!</strong><br /></center>';
}
?>