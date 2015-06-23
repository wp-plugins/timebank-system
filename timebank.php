<?php

/*
Plugin Name: TimeBank
Plugin URI: http://www.time-bank.info/
Description: The timebank-sharing system for your wordpress users! Read install documentation on www.time-bank.info. <br /> Support us at <a href="https://www.teaming.net/wordpresstime-bank-bancodeltiempo">www.teaming.net</a>
Author: Guillermo Tamborero
Version: 1.571
Author URI: http://www.time-bank.info
*/

define( 'TBPLUGIN_DIR', dirname(__FILE__).'/' );  
 
// INSTALL HOOK when plugin is activated
register_activation_hook(__FILE__,'timebank_install');
function timebank_install(){
	include_once "admin/install.php"; 
	jal_install();
	jal_install_data();
	timebankUserCreateLoop();
}

// UPDATE HOOK when plugin is updated / reactivated
add_action( 'plugins_loaded', 'timebank_update' );
function timebank_update(){
	include_once "admin/install.php"; 
	jal_install();
}

// UNINSTALL hook
register_deactivation_hook(__FILE__,'timebank_uninstall');
function timebank_uninstall(){
	include_once "admin/install.php"; 
	jal_uninstall();
}

// Save errors on log file
add_action('activated_plugin','save_error');
function save_error(){
file_put_contents(plugin_dir_path( __FILE__ ). 'log_error_activation.txt', ob_get_contents());
}	

// Create user on Timebank database when wordpress user creation
add_action('user_register','timebankUserCreate');
function timebankUserCreate($user_id){
	createUser($user_id);
}

 

// ADMIN SIDEBAR BUTTONS:
add_action( 'admin_menu', 'timebank_menu' );
function timebank_menu() {
	add_menu_page( __('TimeBank' , 'timebank'), 'TimeBank', 'manage_options', 'timebank', 'timebank_exchanges' );
	add_submenu_page( 'timebank', __('New Exchange' , 'timebank'), 'New Exchange', 'manage_options', 'timebank_newexchange', 'timebank_newexchange'); 
	add_submenu_page( 'timebank', 'Users', 'Users', 'manage_options', 'timebank_users', 'timebank_users');      
	add_submenu_page( 'timebank', 'Configuration', 'Configuration', 'manage_options', 'timebank_options', 'timebank_options'); 
	// Timebank edit user must be here with 'null' property to have access to the admin page without a menu button
	add_submenu_page( 'null', 'Edit User', 'Edit User', 'manage_options', 'timebank_edituser', 'timebank_edituser'); 
	add_submenu_page( 'null', 'Edit Exchange', 'Edit Exchange', 'manage_options', 'timebank_editexchange', 'timebank_editexchange'); 
    
	//Register CSS Admin Style
	wp_register_style( 'timebank-style', plugins_url('css/adminstyle.css', __FILE__) );
    wp_enqueue_style( 'timebank-style' );
}

//ADMIN SHOW EXCHANGES
function timebank_exchanges() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once "admin/show_exchanges.php";
}

function timebank_editexchange() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once "admin/edit_exchange.php";
}

function timebank_newexchange() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once "admin/new_exchange.php";
}

//ADMIN SHOW / EDIT USERS
function timebank_users() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once "admin/show_users.php";
}

function timebank_edituser() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once "admin/edit_user.php";
}

//ADMIN GENERAL CONFIGURATION
function timebank_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once "admin/edit_configuration.php";
}

//USER FUNCTIONS (public)
function timebank_user_exchanges_view(){
	include_once "user/exchanges_view.php";
}

//CSS STYLE FOR PUBLIC 
add_action( 'wp_enqueue_scripts', 'timebank_stylesheet' );
function timebank_stylesheet(){
    wp_register_style( 'timebank-style', plugins_url('css/style.css', __FILE__) );
    wp_enqueue_style( 'timebank-style' );
}

// Add RateIt hook for front-end (wp_head) and backend (admin_footer)
add_action('wp_head', 'rateClass');
add_action('admin_footer', 'rateClass');
function rateClass() {
	echo '
	<!-- Add RateIt Plugin Jquery -->
	<script type="text/javascript" src="';
	echo plugins_url( 'js/rateit/src/jquery.rateit.js', __FILE__ );
	echo '"></script>
	<link rel="stylesheet" type="text/css" href="';
	echo plugins_url( 'js/rateit/src/rateit.css', __FILE__ );
	echo '" media="screen" />';
}

// SIDEBAR CREATION
class TimeBankWidget extends WP_Widget
{
  function TimeBankWidget(){
    $widget_ops = array('classname' => 'RandomPostWidget', 'description' => 'Timebank user access / options view' );
    $this->WP_Widget('RandomPostWidget', 'TimeBank -> Options', $widget_ops);
  }
 
  function form($instance){
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance){
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET PRINT
    include_once( plugin_dir_path( __FILE__ ) . 'user/sidebar.php');
 
    echo $after_widget;
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("TimeBankWidget");') );
// SIDEBAR CREATION END

//SHORT CODE CREATION
add_shortcode('timebank_exchange', 'timebank_user_exchanges_view');

//BUDDY PRESS HOOK
add_action( 'bp_setup_nav', 'add_timebank_nav_tab' , 100 );
function add_timebank_nav_tab() {
bp_core_new_nav_item( array(
    'name' => __( 'TimeBank', 'timebank' ),
    'slug' => 'timebank',
    'position' => 80,
    'screen_function' => 'timebank_info',
    'default_subnav_slug' => 'timebank'
) );
}


// show feedback when 'Feedback’ tab is clicked
function timebank_info() {

bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
//tiene que ir detras este ad action. busca el bp_post template
add_action( 'bp_template_content','timebank_exchange_view' );
}

function timebank_exchange_view() {
    timebank_user_exchanges_view();
}

    // AJAX FUNCTIONS
    include( plugin_dir_path( __FILE__ ) . 'common/constants.php');
    include( plugin_dir_path( __FILE__ ) . 'common/mysql_functions.php');
    include( plugin_dir_path( __FILE__ ) . 'user/ajax.php'); 
    
    function new_transfer_ajax() {  
        ajax_new_transfer();
    }
    add_action( 'wp_ajax_new_transfer', 'new_transfer_ajax' );

    function validate_transfer_ajax() {
        ajax_validate_transfer();
    }
    add_action( 'wp_ajax_validate_transfer', 'validate_transfer_ajax' );

    function reject_transfer_ajax() {
        ajax_reject_transfer();
    }
    add_action( 'wp_ajax_reject_transfer', 'reject_transfer_ajax' );

    function comment_transfer_ajax() { 
        ajax_comment_transfer();
    }
    add_action( 'wp_ajax_comment_transfer', 'comment_transfer_ajax' );

// ADD THICKBOX
function add_themescript(){
    wp_enqueue_script('jquery');
    wp_enqueue_script('thickbox',null,array('jquery'));
    wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0');    
}
add_action('init','add_themescript');

// TRANSLATION
add_action( 'plugins_loaded', 'timebank_load_textdomain' );
function timebank_load_textdomain() {
  //load_plugin_textdomain( 'timebank' ); 
  load_plugin_textdomain( 'timebank', false, dirname( plugin_basename( __FILE__ ) ) ); 
}

//function load_plugin_textdomain_timebank(){
 // load_plugin_textdomain( 'timebank' );
//}
  //add_action('init', 'load_plugin_textdomain_timebank');
  
?>