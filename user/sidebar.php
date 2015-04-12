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
		
		// PRINT NEW REQUEST BUTTON	
		echo '<a href="#TB_inline?width=600&height=400&inlineId=showExchangeWindow" class="thickbox">' . __('NEW EXCHANGE', 'timebank') . '</a>';

		// INCLUDE NEW REQUEST html + js code	
		include_once( plugin_dir_path( __FILE__ ) . 'new_exchange.php');
			
		// PRINT BUTTONS
		echo '<br /><a href="' . $pathToTimebank . '">Go to your Timebank</a>';
		echo "<br /><div>Balance: $userData->balance $config->currency</div>";
		echo "<div>Totals Sells: $userData->total_sell_transfers</div>";
		echo "<div>Total Buys: $userData->total_buy_transfers</div>";
		echo "<div>Status: $userData->type</div>"; 
		echo '</div><div style="clear:both";></div><br />';
	}

}else{
	//echo '<center><strong>You need to log to your Wordpress Account before using Timebank!</strong><br /></center>';
}
?>