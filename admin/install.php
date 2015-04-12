<?php

//FIRST INSTALL FILE 
//Here we set up the TIME BANK DATABASE
	
$jal_db_version = "1.33";
$installed_ver = get_option( "jal_db_version" );
//echo "VERS:" . $installed_ver ;

function jal_install() {
   global $wpdb;
   global $jal_db_version;

   $table_name = $wpdb->prefix . "tbank_conf";
   $table1 = "CREATE TABLE $table_name (
  id tinyint(4) NOT NULL AUTO_INCREMENT,
  default_anonymous varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  default_min_limit int(11) NOT NULL,
  default_max_limit int(11) NOT NULL,
  exchange_timeout int(11) NOT NULL,
  currency varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  path_to_timebank varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  admin_mail tinyint(1) NOT NULL,
  email_original_text text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  email_text text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
);
";

   $table_name = $wpdb->prefix . "tbank_exchange";
   $table2 = "CREATE TABLE $table_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_seller int(11) NOT NULL,
  id_buyer int(11) NOT NULL,
  datetime_created datetime NOT NULL,
  datetime_accepted datetime NOT NULL,
  datetime_finalized datetime NOT NULL,
  datetime_denied datetime NOT NULL,
  datetime_cancelled datetime NOT NULL,
  concept varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  amount int(11) NOT NULL,
  status tinyint(4) NOT NULL,
  rating_value tinyint(4) NOT NULL,
  rating_comment varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  created_by INT NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
);
";

   $table_name = $wpdb->prefix . "tbank_exchange_denegationtype";
   $table3 = "CREATE TABLE $table_name (
  id int(11) NOT NULL,
  description varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
);
";

   $table_name = $wpdb->prefix . "tbank_exchange_manager";
   $table4 = "CREATE TABLE $table_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  fk_exchange int(11) NOT NULL,
  fk_user int(11) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
);
";

   $table_name = $wpdb->prefix . "tbank_exchange_statustype";
   $table5 = "CREATE TABLE $table_name (
  id tinyint(4) NOT NULL,
  type varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
);
";

   $table_name = $wpdb->prefix . "tbank_users";
   $table6 = "CREATE TABLE $table_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  fk_wpuser int(11) NOT NULL,
  datetime_created datetime NOT NULL,
  datetime_modified datetime NOT NULL,
  datetime_deactivated datetime NOT NULL,
  max_limit int(4) NOT NULL,
  min_limit int(4) NOT NULL,
  balance int(11) NOT NULL,
  status tinyint(4) NOT NULL,
  total_sell_transfers int(11) NOT NULL,
  total_buy_transfers int(11) NOT NULL,
  total_sell_positive_rating int(11) NOT NULL,
  total_sell_negative_rating int(11) NOT NULL,
  total_buy_positive_rating int(11) NOT NULL,
  total_buy_negative_rating int(11) NOT NULL,
  alert tinyint(4) NOT NULL,
  UNIQUE KEY fk_wpuser (fk_wpuser),
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
);
";

   $table_name = $wpdb->prefix . "tbank_users_alerttype";
   $table7 = "CREATE TABLE $table_name (
  id int(11) NOT NULL,
  description varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
);
";

   $table_name = $wpdb->prefix . "tbank_users_statustype";
   $table8 = "CREATE TABLE $table_name (
  id tinyint(4) NOT NULL,
  type varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
);
";

	
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $table1 );
   dbDelta( $table2 );
   dbDelta( $table3 );
   dbDelta( $table4 );
   dbDelta( $table5 );
   dbDelta( $table6 );
   dbDelta( $table7 );
   dbDelta( $table8 ); 
 
update_option( "jal_db_version", $jal_db_version );
}


function jal_install_data() {
   global $wpdb;
   
   // INSERT CONFIGURATION
   $id = "1";
   $default_anonymous = "(deleted user)";
   $default_min_limit = "120";
   $default_max_limit = "180";
   $currency = "minutes";
   $exchange_timeout = "48";
   $admin_mail = "1";
   $path_to_timebank = "http://your-site.com/your-timebank-page";
   $email_original_text = 'Hello!
A new timebank transfer has been $status_name on your timebank $siteUrl

Concept: $data->concept
Exchange: $data->amount minutes
Exchange status: $status_name

Buyer: $data->buyer_name , $data->buyer_email 
Seller: $data->seller_name , $data->seller_email 

Date Creation: $data->datetime_created 
Date Accept: " .  showIfSet($data->datetime_accepted) . " 
Date Rejected: " . showIfSet($data->datetime_denied) . "

Please Accept or Reject the transfer as soon as possible on $siteUrl
If you don\'t Accept within 48 hours the transfer will be automaticaly rejected.

The $siteUrl Team.';
   
   $table_name = $wpdb->prefix . "tbank_conf";
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => $id, 'default_anonymous' => $default_anonymous, 'default_min_limit' => $default_min_limit, 'default_max_limit' => $default_max_limit, 'exchange_timeout' => $exchange_timeout, 'currency' => $currency, 'admin_mail' => $admin_mail, 'email_original_text' => $email_original_text,  'email_text' => $email_original_text, 'path_to_timebank' => $path_to_timebank ) );

   // INSERT DENEGATION TYPE
   $table_name = $wpdb->prefix . "tbank_exchange_denegationtype";
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '1', 'description' => 'Disagree with amount' ) );
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '0', 'description' => 'SPAM' ) );
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '2', 'description' => 'Disagree with service / product' ) );

	// INSERT STATUS TYPE
   $table_name = $wpdb->prefix . "tbank_exchange_statustype";
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '1', 'type' => 'Pending' ) );
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '2', 'type' => 'Accepted' ) );
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '3', 'type' => 'Completed' ) );
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '4', 'type' => 'Rejected' ) );
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '5', 'type' => 'Cancelled' ) );
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '6', 'type' => 'Denied' ) );

	// INSERT ALERT TYPE
   $table_name = $wpdb->prefix . "tbank_users_alerttype";
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '1', 'description' => 'You have new transactions' ) );   
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '2', 'description' => 'Please review your transactions' ) ); 

	// INSERT USER STATUS TYPE
   $table_name = $wpdb->prefix . "tbank_users_statustype";
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '-1', 'type' => 'Deactivated' ) );   
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '1', 'type' => 'Activated' ) ); 
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '0', 'type' => 'Blocked' ) ); 
   $rows_affected = $wpdb->insert( $table_name, array( 'id' => '-2', 'type' => 'Deleted' ) ); 
       
}

function jal_uninstall() {
   global $wpdb;
   
   // DROP SUPPORT TABLES (not data)
   
   $table_name = $wpdb->prefix . "tbank_exchange_denegationtype";
   $wpdb->query("DROP TABLE IF EXISTS $table_name");
   
   $table_name = $wpdb->prefix . "tbank_exchange_statustype";
   $wpdb->query("DROP TABLE IF EXISTS $table_name");
   
   $table_name = $wpdb->prefix . "tbank_users_alerttype";
   $wpdb->query("DROP TABLE IF EXISTS $table_name");
   
   $table_name = $wpdb->prefix . "tbank_users_statustype";
   $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

?>