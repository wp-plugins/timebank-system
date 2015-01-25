<?php

	global $wpdb;

	//EXCHANGE STATUS TYPE (wp_tbank_exchange_statustype)
    define ("PENDING", 1);
    define ("ACCEPTED", 2);
    define ("COMPLETED", 3);
    define ("REJECTED", 4);
    define ("CANCELLED", 5);
    define ("DENIED", 6);

	//USER STATUS TYPE (wp_tbank_users_statustype)
	define ("DELETED", -2);
	define ("DEACTIVATED", -1);
	define ("BLOCKED", 0);
	define ("ACTIVATED", 1);
	
	//TABLE NAMES for WPDB querys
	define ("TBANK_CONF", $wpdb->prefix . 'tbank_conf');
	define ("TBANK_EXCHANGE", $wpdb->prefix . 'tbank_exchange');
	define ("TBANK_EXCHANGE_DENEGATIONTYPE", $wpdb->prefix . 'tbank_exchange_denegationtype');
	define ("TBANK_EXCHANGE_MANAGER", $wpdb->prefix . 'tbank_exchange_manager');
	define ("TBANK_EXCHANGE_STATUSTYPE", $wpdb->prefix . 'tbank_exchange_statustype');
	define ("TBANK_USERS", $wpdb->prefix . 'tbank_users');	
	define ("TBANK_USERS_STATUSTYPE", $wpdb->prefix . 'tbank_users_statustype');	
	define ("TBANK_USERS_ALERTTYPE", $wpdb->prefix . 'tbank_users_alerttype');	
?>