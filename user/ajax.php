<?php

// TIMEBANK AJAX FUNCTIONS

function ajax_new_transfer() {
    check_ajax_referer( "new_transfer" );   
	if ($_POST['buyerUserId']){ $buyerUserId = $_POST['buyerUserId']; }else{ $buyerUserId = userNameToUserId($_POST['buyerUserName']); } 
	if ($_POST['sellerUserId']){ $sellerUserId = $_POST['sellerUserId']; }else{ $sellerUserId = userNameToUserId($_POST['sellerUserName']); } 	
    newExchange ($sellerUserId, $buyerUserId, $_POST["amount"], $_POST["description"], $_POST["createdBy"]);    
    die(); //hack for ajax not echo 0 
}

function ajax_validate_transfer() {
    check_ajax_referer( "validate_transfer" );
    updateExchangeStatus($_POST["exchangeId"], ACCEPTED );
    die(); //hack for ajax not echo 0 
}

function ajax_reject_transfer() {
    check_ajax_referer( "reject_transfer" );
    updateExchangeStatus($_POST["exchangeId"], REJECTED );
    die(); //hack for ajax not echo 0 
}

function ajax_comment_transfer() {
    check_ajax_referer( "comment_transfer" );
    rateExchange($_POST["exchangeId"], $_POST["rate"], $_POST["comment"], $_POST["concept"]);
    die(); //hack for ajax not echo 0 
}
?>
