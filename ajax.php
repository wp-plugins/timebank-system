<?php

// TIMEBANK AJAX FUNCTIONS

function ajax_new_transfer() {
    check_ajax_referer( "new_transfer" );    
    $sellerUserId = $_POST["sellerUserId"];
    $buyerUserName = $_POST["buyerUserName"];
    $buyerUserId = userNameToUserId($buyerUserName);
    newExchange ($sellerUserId, $buyerUserId, $_POST["amount"], $_POST["description"]);    
    die(); //hack for ajax not echo 0 
}

function ajax_validate_transfer() {
    check_ajax_referer( "validate_transfer" );
    updateExchangeStatus(get_current_user_id(), $_POST["exchangeId"], ACCEPTED );
    die(); //hack for ajax not echo 0 
}

function ajax_reject_transfer() {
    check_ajax_referer( "reject_transfer" );
    updateExchangeStatus(get_current_user_id(), $_POST["exchangeId"], REJECTED );
    die(); //hack for ajax not echo 0 
}

function ajax_comment_transfer() {
    check_ajax_referer( "comment_transfer" );
    rateExchange($_POST["exchangeId"], $_POST["rate"], $_POST["comment"], $_POST["concept"]);
    die(); //hack for ajax not echo 0 
}
?>
