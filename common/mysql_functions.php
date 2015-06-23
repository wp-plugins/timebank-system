<?php

// USER FUNCTIONS
// userId always refers to Wordpress user Id.

function newExchange ($sellerUserId, $buyerUserId, $amount, $description, $createdBy) {	
	global $wpdb;
	
	//Define show button acceptance
	$showaccept = '<script> jQuery("#ACCEPT").show(); </script>';
		
	// Seller is active user?
	if (!isActiveUser($sellerUserId)){
		echo "<center>Seller doesn't exist or is not an active user</center>";
		echo $showaccept;	
		return 0;
	};
	// Buyer is active user?
	if (!isActiveUser($buyerUserId)){
		echo "<center>Buyer doesn't exist or is not an active user</center>";
		echo $showaccept;
		return 0;
	};
	// Seller is buyer?
	if ($sellerUserId == $buyerUserId){
		echo "<center>You cannot exchange with yourself</center>";
		echo $showaccept;
		return 0;
	};
	// Possible transfer? (Max Limits is correct?)
	if (!tryMaxExchange($sellerUserId, $amount)){	
		echo "<center>Seller limit overflow </center>";
		echo $showaccept;
		return 0;
	};
	// Possible transfer? (Min Limits is correct?)
	if (!tryMinExchange($buyerUserId, $amount)){	
		echo "<center>Buyer limit overflow </center>";
		echo $showaccept;
		return 0;
	};	
	
	// Exchange action	
	if ($wpdb->query("INSERT INTO " . TBANK_EXCHANGE . " (id_seller, id_buyer, datetime_created, datetime_accepted, 
            datetime_finalized, datetime_denied, datetime_cancelled, concept, amount, status, rating_value, 
            rating_comment, created_by) VALUES ($sellerUserId, $buyerUserId, now(), '0000-00-00 00:00:00', 
                '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 
                '$description', '$amount', '" . PENDING . "', 0, '', $createdBy)")){	
		echo '<center><strong>Your transaction has been send</strong></center>
			<!-- ON AJAX TRANSACTION END GO TO TIMEBANK PAGE -->
			<script type="text/javascript">
			jQuery(document).ready(function() {
			setTimeout(window.location.href = "' . pathToTimebank() . '", 2000);
			});
			</script>';	
		
		//Mail to buyer and seller and admin
		timebankMail($wpdb->insert_id);
		return 1;
	}else{
		echo "<center>Error: Your transaction has NOT been send</center>";	
		echo $showaccept;
		return 0;
	}	
}

function newAdminExchange ($sellerUserId, $buyerUserId, $amount, $description) {	
	global $wpdb;
	
	// Seller is active user?
	if (!isActiveUser($sellerUserId)){
		echo "<center>Seller doesn't exist or is not an active user</center>";
	};
	// Buyer is active user?
	if (!isActiveUser($buyerUserId)){
		echo "<center>Buyer doesn't exist or is not an active user</center>";
	};
	// Seller is buyer?
	if ($sellerUserId == $buyerUserId){
		echo "<center>You cannot exchange with yourself</center>";
	};
	// Possible transfer? (Max Limits is correct?)
	if (!tryMaxExchange($sellerUserId, $amount)){	
		echo "<center>Seller limit overflow </center>";
	};
	// Possible transfer? (Min Limits is correct?)
	if (!tryMinExchange($buyerUserId, $amount)){	
		echo "<center>Buyer limit overflow </center>";
		echo $showaccept;
		return 0;
	};	
	
	// Exchange action	
	if ($wpdb->query("INSERT INTO " . TBANK_EXCHANGE . " (id_seller, id_buyer, datetime_created, datetime_accepted, 
            datetime_finalized, datetime_denied, datetime_cancelled, concept, amount, status, rating_value, 
            rating_comment) VALUES ($sellerUserId, $buyerUserId, now(), '0000-00-00 00:00:00', 
                '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 
                '$description', '$amount', '" . PENDING . "', 0, '')")){	
		echo '<center><strong>Your transaction has been send</strong></center>';	
		
		//Mail to buyer and seller and admin
		timebankMail($wpdb->insert_id);
		return 1;
	}else{
		echo "<center>Error: Your transaction has NOT been send</center>";	
		echo $showaccept;
		return 0;
	}	
}

// Init function for full timebank table user creation
function timebankUserCreateLoop(){
	global $wpdb;
	$result = $wpdb->get_results("SELECT ID FROM " . $wpdb->prefix . "users WHERE 1");
	foreach ($result as $res){
		createUser($res->ID);
	}
}

function updateExchangeStatus($exchangeId, $status){
	global $wpdb;
        
        // UPDATE BALANCE IF TRANSFER ACCEPTED
        if ($status == ACCEPTED){
            $result = $wpdb->get_results("SELECT id_seller, id_buyer, amount FROM " . TBANK_EXCHANGE . " WHERE id = '$exchangeId' LIMIT 1");
            foreach ($result as $res);
            updateBalance ($res->id_seller, $res->id_buyer, $res->amount);
            //Set Accept Time
            $wpdb->query("UPDATE " . TBANK_EXCHANGE . " SET datetime_accepted = now() WHERE id = $exchangeId");
            
        }
        
	// UPDATE TO NEW STATUS IF REJECTED OR COMPLETED
        if ( $wpdb->query("UPDATE " . TBANK_EXCHANGE . " SET status = '$status' 
        WHERE id = '$exchangeId'")){
            
            $statusName = getExchangeStatusName ($status);
            //Set Denied Time if Rejected
            if ($status == REJECTED){
            $wpdb->query("UPDATE " . TBANK_EXCHANGE . " SET datetime_denied = now() WHERE id = $exchangeId");
            }
                    
                echo "<center>The transaction has been " . $statusName . "</center>";	
        }else{
                echo "<center>Error: Impossible Update. Try again later / Contact administrator</center>";	
        }
        
        //Mail to buyer and seller and admin
        timebankMail($exchangeId);
}

function rateExchange($exchangeId, $rate, $comment, $concept){
	global $wpdb;
        
        $query = "UPDATE " . TBANK_EXCHANGE . " SET rating_value = '$rate', rating_comment = '$comment',
	concept = '$concept', status = '" . COMPLETED . "' WHERE id = '$exchangeId'";
        //echo $query;
        if ( $wpdb->query($query)){		
            //Set Finalized Time
            $query = "UPDATE " . TBANK_EXCHANGE . " SET datetime_finalized = now() WHERE id = $exchangeId";
            //echo $query;
            $wpdb->query($query);
            echo "<center>The transaction has been Completed</center>";	
            
            //Mail to buyer and seller and admin
            timebankMail($exchangeId);
            
            return 1;
        }else{
            echo "<center>Error: Impossible Update. Try again later</center>";
            return 0;	
        }
}
            

function salesView ($userId){
	global $wpdb;
	$result = $wpdb->get_results("SELECT *, exchange.id FROM " . TBANK_EXCHANGE . " AS exchange LEFT JOIN 
	" . TBANK_EXCHANGE_STATUSTYPE . " ON exchange.status = " . TBANK_EXCHANGE_STATUSTYPE . ".id
	LEFT JOIN " . $wpdb->prefix . "users ON exchange.id_buyer = " . $wpdb->prefix . "users.ID 
	WHERE id_seller = '$userId' ORDER BY exchange.id DESC LIMIT 50");
	return $result;
}

function purchaseView ($userId){
        global $wpdb;
	$result = $wpdb->get_results("SELECT *, exchange.id FROM " . TBANK_EXCHANGE . " AS exchange LEFT JOIN 
	" . TBANK_EXCHANGE_STATUSTYPE . " ON exchange.status = " . TBANK_EXCHANGE_STATUSTYPE . ".id
	LEFT JOIN " . $wpdb->prefix . "users ON exchange.id_seller = " . $wpdb->prefix . "users.ID 
	WHERE id_buyer = '$userId' ORDER BY exchange.id DESC LIMIT 50");
	return $result;
}

function userNameToUserId ($username){
	global $wpdb;
        $sql = "SELECT ID FROM " . $wpdb->prefix . "users WHERE user_login = '$username' LIMIT 1";
	$result = $wpdb->get_results($sql);
	foreach ($result as $res);
	return $res->ID;
}

function userIdToUserName ($userId){
	global $wpdb;
	$result = $wpdb->get_results("SELECT user_login FROM " . $wpdb->prefix . "users WHERE ID = '$userId' LIMIT 1");
	foreach ($result as $res);
	return $res->user_login;
}

function isActiveUser ($userId){
	global $wpdb;
        
        //If user doesn't exist return 0
        if ($userId == 0) return 0;
        
        $result = $wpdb->get_results("SELECT status FROM " . TBANK_USERS . " WHERE fk_wpuser = '$userId'");
	foreach ($result as $res);
	if ($res->status < ACTIVATED ){ 
            if (!$res->status ){
                // If user is not created in TBANK database create it!
                if (!$userData = getUserData ($userId)){
                    if (createUser($userId)){
                    //echo 'Timebank user Created! (Refreshing...)';
                    return 1;
                    }
                }
            } 
	return 0; //Not active user
	}
	return 1;
}

function tryMaxExchange ($userId, $amount){
	global $wpdb;
	$result = $wpdb->get_results("SELECT balance, max_limit, status FROM " . TBANK_USERS . " WHERE fk_wpuser = '$userId'");
	foreach ($result as $res);
	if ($res->status < ACTIVATED ) return 0; //Not active user
	$limit = $res->max_limit - $res->balance;
	if ($amount <= $limit){
	return 1;
	}
	return 0;
}	

function tryMinExchange ($userId, $amount){
	global $wpdb;
	$result = $wpdb->get_results("SELECT balance, min_limit, status FROM " . TBANK_USERS . " WHERE fk_wpuser = '$userId'");
	foreach ($result as $res);
	if ($res->status < ACTIVATED ) return 0; //Not active user
	$limit = $res->min_limit + $res->balance;
	if ($amount <= $limit){
	return 1;
	}
	return 0;
}

function updateBalance ($sellerId, $buyerId, $amount){
	global $wpdb;
	if (($wpdb->query("UPDATE " . TBANK_USERS . " SET balance = balance + $amount, 
            total_sell_transfers = total_sell_transfers + 1 WHERE fk_wpuser = $sellerId")) 
	&& ($wpdb->query("UPDATE " . TBANK_USERS . " SET balance = balance - $amount, 
            total_buy_transfers = total_buy_transfers + 1 WHERE fk_wpuser = $buyerId"))){
	return 1;
	}else{
	echo "Fatal error: Balance Account SET Failed";
	}
}

function getUserData ($userId){
        global $wpdb;
		$query = "SELECT " . TBANK_USERS . ".id, fk_wpuser, max_limit, min_limit, balance, status, total_sell_transfers,
            total_buy_transfers, total_sell_positive_rating, total_sell_negative_rating, total_buy_positive_rating,
            total_buy_negative_rating, alert, " . TBANK_USERS_STATUSTYPE . ".type 
            FROM " . TBANK_USERS . " INNER JOIN " . TBANK_USERS_STATUSTYPE . " ON " . TBANK_USERS . ".status = " . TBANK_USERS_STATUSTYPE . ".id
            WHERE " . TBANK_USERS . ".fk_wpuser = '$userId' LIMIT 1";
        $result = $wpdb->get_results( $query );
        if ($wpdb->num_rows != 0){
		foreach ($result as $res);
            return $res;
        }else{
            return 0;
        }
}

function printButton($title, $file, $position = left){
	echo '<div class="button" style="float:' . $position . '">
	<a style="font-size:14px; font-weight:bold;" class="fancybox fancybox.ajax" href="' . $file . '">' . $title . '</a></div>';
}

function showIfSet($var){
    if (($var != "") && ($var != "0000-00-00 00:00:00") && ($var != "0")){ 
        return $var;
    }
}

// ADMIN FUNCTIONS
function timebankMail($exchangeId){
        global $wpdb;
        
        $data = getExchangeData($exchangeId);
        if ($data->status == PENDING){ 
            $status_name = "Created"; 
        }else{
            $status_name = $data->status_name;
        }

        $siteUrl = get_site_url();
        $subject = "New Exchange Alert";
        $adminMail = get_bloginfo('admin_email');
        $headers[] = "From: Timebank Team <$adminMail>";  
		$config = getConfiguration();
        $message = $config->email_text; 
		eval("\$message = \"$message\";");
        
        // SEND MAILS to buyer and seller
        wp_mail($data->buyer_email, $subject, $message, $headers);
        wp_mail($data->seller_email, $subject, $message, $headers);
        // SEND MAIL to admin if configurated
        $conf = getConfiguration(); 
        if ($conf->admin_mail == "1" ) wp_mail($adminMail, $subject, $message, $headers);   
        
}

function exchangesView ($username = 0){
	global $wpdb;
	
	$userId = userNameToUserId ($username);
	if ($userId){ 
		$query ="SELECT *, " . TBANK_EXCHANGE . ".id AS exchangeid FROM " . TBANK_EXCHANGE . "  
		LEFT JOIN " . $wpdb->prefix . "users ON " . TBANK_EXCHANGE . ".id_seller = " . $wpdb->prefix . "users.ID
		LEFT JOIN " . TBANK_EXCHANGE_STATUSTYPE . " ON " . TBANK_EXCHANGE . ".status = " . TBANK_EXCHANGE_STATUSTYPE . ".id
		WHERE " . TBANK_EXCHANGE . ".id_seller = '$userId' OR " . TBANK_EXCHANGE . ".id_buyer = '$userId'  ORDER BY " . TBANK_EXCHANGE . ".id DESC LIMIT 100";
		$result = $wpdb->get_results($query);
		
	}else{
		$query = "SELECT *, " . TBANK_EXCHANGE . ".id AS exchangeid FROM " . TBANK_EXCHANGE . "  
		LEFT JOIN " . $wpdb->prefix . "users ON " . TBANK_EXCHANGE . ".id_seller = " . $wpdb->prefix . "users.ID
		LEFT JOIN " . TBANK_EXCHANGE_STATUSTYPE . " ON " . TBANK_EXCHANGE . ".status = " . TBANK_EXCHANGE_STATUSTYPE . ".id
		WHERE 1 ORDER BY " . TBANK_EXCHANGE . ".id DESC LIMIT 100";
		$result = $wpdb->get_results($query);	
	}
        //echo var_dump($result);
	return $result;
}

function createUser($userId){
    global $wpdb;
    $conf = getConfiguration();	
        if ($wpdb->query("INSERT INTO " . TBANK_USERS . " (fk_wpuser, datetime_created, max_limit, min_limit, status, balance) VALUES ('$userId', now(), $conf->default_max_limit, $conf->default_min_limit, 1, $conf->starting_amount)")){
        return 1;
        }else{
        echo $mysqli->error;
        return 0;
        }
}

//This function is used by WP Admin 
function updateUser ( $userId, $maxLimit, $minLimit, $status ){
	global $wpdb;
        $query = "UPDATE " . TBANK_USERS . " SET max_limit = '$maxLimit', min_limit = '$minLimit', status = '$status' WHERE fk_wpuser = $userId";
	//echo $query;
        if ($wpdb->query($query)){	
	return 1;
        }
};

function getExchangeData ($exchangeId){
	global $wpdb;
	$row = $wpdb->get_row("SELECT * FROM ". TBANK_EXCHANGE ." WHERE ". TBANK_EXCHANGE .".id=$exchangeId");
	
        // GET seller user name + mail: user_login
        $getSeller = $wpdb->get_results("SELECT user_login, user_email FROM " . $wpdb->prefix . "users WHERE ID = " . $row->id_seller . " LIMIT 1");
	foreach ($getSeller as $result);
        $row->seller_name = $result->user_login;
        $row->seller_email = $result->user_email;

        // GET buyer user name + mail: user_login
        $getBuyer = $wpdb->get_results("SELECT user_login, user_email FROM " . $wpdb->prefix . "users WHERE ID = " . $row->id_buyer . " LIMIT 1");
	foreach ($getBuyer as $result);
        $row->buyer_name = $result->user_login;
        $row->buyer_email = $result->user_email;
        
        // GET Status nice name
        $getStatus = $wpdb->get_results("SELECT type FROM " . TBANK_EXCHANGE_STATUSTYPE . " WHERE id = " . $row->status . " LIMIT 1");
	foreach ($getStatus as $result);
        $row->status_name = $result->type;
        
        //echo var_dump($row);
        return $row;      
}

function updateExchange( $exchangeId, $idSeller, $idBuyer, $concept, $amount, $status, $ratingValue, $ratingComment ){
	global $wpdb;
	$wpdb->query("UPDATE ". TBANK_EXCHANGE ." SET id_seller = '$idSeller', id_buyer = '$idBuyer', 
		concept = '$concept', amount = '$amount', status = '$status', 
		rating_value = '$ratingValue', rating_comment = '$ratingComment' 
		WHERE id = $exchangeId");
}

// Users view for admins
function getUsers($username = 0){
	global $wpdb;
	$userId = userNameToUserId ($username);
	
	if ($userId){ 
	$query = "SELECT *, " . TBANK_USERS . ".id FROM " . TBANK_USERS . " LEFT JOIN " . $wpdb->prefix . "users ON " . TBANK_USERS . ".fk_wpuser = " . $wpdb->prefix . "users.ID LEFT JOIN " . TBANK_USERS_STATUSTYPE . " 			ON " . TBANK_USERS . ".status = " . TBANK_USERS_STATUSTYPE . ".id  WHERE " . TBANK_USERS . ".fk_wpuser = $userId";
	$result = $wpdb->get_results($query);
	}else{
	$result = $wpdb->get_results("SELECT *, " . TBANK_USERS . ".id FROM " . TBANK_USERS . " LEFT JOIN " . $wpdb->prefix . "users ON " . TBANK_USERS . ".fk_wpuser = " . $wpdb->prefix . "users.ID LEFT JOIN " . TBANK_USERS_STATUSTYPE . " ON " . TBANK_USERS . ".status = " . TBANK_USERS_STATUSTYPE . ".id  WHERE 1");	
	}    
	return $result;		
}

// User view for users view
function showUsers($username){
	global $wpdb;
	$userId = userNameToUserId ($username);
	
	if ($userId){ 
	$query = "SELECT *, " . TBANK_USERS . ".id FROM " . TBANK_USERS . " LEFT JOIN " . $wpdb->prefix . "users ON " . TBANK_USERS . ".fk_wpuser = " . $wpdb->prefix . "users.ID LEFT JOIN " . TBANK_USERS_STATUSTYPE . " 			ON " . TBANK_USERS . ".status = " . TBANK_USERS_STATUSTYPE . ".id  WHERE " . TBANK_USERS . ".fk_wpuser = $userId";
	$result = $wpdb->get_results($query);
	}else{
	return 0;	
	}    
	return $result;		
}


function getConfiguration(){
	global $wpdb;
	$row = $wpdb->get_row("SELECT * FROM ". TBANK_CONF ." WHERE 1");
	return $row;
}

function updateConfiguration(){
	global $wpdb;

	// INSERT PREPARATION
	$pathToTimebank    = $_POST['pathtotimebank'];
	$defaultAnonymous   = $_POST['defaultanonymous'];
	$defaultMaxLimit    = $_POST['defaultmaxlimit'];
	$defaultMinLimit    = $_POST['defaultminlimit'];
	$startingAmount    = $_POST['startingamount'];
	$exchangeTimeout    = $_POST['exchangetimeout'];
    $adminMail          = $_POST['adminmail'];
    $emailText			= $_POST['emailtext'];		
	
	// EXCEPTIONS
	if ($_POST['currency'] != ""){
	$currency 	        = $_POST['currency'];
	}else{
	$currency 	        = "minutes";
	}
	 
	$result = $wpdb->query("UPDATE " . TBANK_CONF . " SET 
	path_to_timebank = '$pathToTimebank', 
	default_anonymous = '$defaultAnonymous', 
    default_max_limit = '$defaultMaxLimit', 
	default_min_limit = '$defaultMinLimit', 
	exchange_timeout='$exchangeTimeout', 
	currency = '$currency',
	starting_amount = '$startingAmount',
	admin_mail = '$adminMail',
	email_text = '$emailText' WHERE 1");
	
}

function getExchangeStatusType(){
	global $wpdb;
	$result = $wpdb->get_results("SELECT id, type FROM " . TBANK_EXCHANGE_STATUSTYPE);
	return $result;
}

function getExchangeStatusName($statusId){
	global $wpdb;
	$var = $wpdb->get_var( "SELECT type FROM " . TBANK_EXCHANGE_STATUSTYPE . " WHERE id=$statusId");
	return $var;

}

function getUserStatusType(){
	global $wpdb;
	$result = $wpdb->get_results("SELECT id, type FROM " . TBANK_USERS_STATUSTYPE );
	return $result;
}

function getUserStatusName($statusId){
	global $wpdb;
	$result = $wpdb->get_results("SELECT type FROM " . TBANK_USERS_STATUSTYPE . " WHERE id=$statusId");
    foreach ($result as $res);	
	return $res->type;
}

function isWpUser(){
	global $wpdb;
	$current_user = wp_get_current_user();
	return $current_user->ID;
}

function addExchangeManager($exchangeId, $managerId){
	global $wpdb;
	if ($wpdb->query("INSERT INTO " . TBANK_EXCHANGE_MANAGER . " (fk_exchange, fk_user) VALUES ('exchangeId' , '$managerId')")){
		return 1;
	}else{
		echo $mysqli->error;
		return 0;
	}	
}

function pathToTimebank(){
	global $wpdb;
	$result = $wpdb->get_results("SELECT path_to_timebank FROM " . TBANK_CONF . " WHERE 1");
    foreach($result as $res);	
	return $res->path_to_timebank;
}

?>