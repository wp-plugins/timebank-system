<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php');

// UPDATE CONFIGURATION
if ($_POST['option']=="edit"){
	updateConfiguration(); 		
};

// CONFIG TABLE VIEW
$config = getConfiguration();

?>

<!-- TIMEBANK STYLE INIT -->	
<div class="timebank">
	<br /><p style="font-size:20px;"><strong>TIME-BANK GENERAL CONFIGURATION</strong></p><hr>
	
	<form action="" method="post">
	<input name="page" type="hidden" value="timebank_configuration" />
	<input name="option" type="hidden" value="edit" />	
		<table border=1 width=99% >

		<th> Timebank Page PATH:</th> <td><input name="pathtotimebank" type="text" value="<?php echo $config->path_to_timebank; ?>" size="40" maxlength="250" /></td>
		<td class="explain">Absolute URL to your Timebank Page Path. <br />Shortcode you have to insert into your page content: [timebank_exchange]  </td><tr />		
		
		<th> No existant users name:</th> <td><input name="defaultanonymous" type="text" value="<?php echo $config->default_anonymous; ?>" size="20" maxlength="20" /> </td>
		<td class="explain">This name is printed if a Wordpress user is eliminated from the System. (Timebank stores exchange data of unexistant users for security / integrity reasons)</td><tr />
		
		<!--<th> Authcode size (PIN):</th> <td><input name="authcodelenght" type="text" value="<?php echo $config->authcode_lenght; ?>" size="2" maxlength="2" /> </td>
		<td class="explain">digits. WARNING: Change this value only when you begin running your timebank. Otherwise users will experiment AuthCode problems</td><tr />
		
		<th> Authcode tries:</th> <td><input name="authcodetries" type="text" value="<?php echo $config->authcode_tries; ?>" size="1" maxlength="1" /> </td>
		<td class="explain">Max tries before denying the exchange</td><tr />-->
		
		<th> Default Max Limit currency:</th> <td><input name="defaultmaxlimit" type="text" value="<?php echo $config->default_max_limit; ?>" size="4" maxlength="4" /> </td>
		<td class="explain"><?php echo $config->currency; ?>. (Default exchange limit for new users) </td><tr />
		
		<th> Default Min Limit currency:</th> <td><input name="defaultminlimit" type="text" value="<?php echo $config->default_min_limit; ?>" size="4" maxlength="4" /> </td>
		<td class="explain"><?php echo $config->currency; ?>. (Default exchange limit for new users)</td><tr />

		<th> Exchange Timeout:</th> <td><input name="exchangetimeout" type="text" value="<?php echo $config->exchange_timeout; ?>" size="3" maxlength="2" /> </td>
		<td class="explain">hours before an exchange Rejection occurs (will be working in v2)</td><tr />
		
		<th> Currency name:</th> <td><input name="currency" type="text" value="<?php echo $config->currency; ?>" size="20" maxlength="20" /> </td>
		<td class="explain">You can use the currency name or alias you like (Examples: min, hours, €, $, tokens ... )</td><tr />

		<th> Send mail to Admin:</th> <td><input type="radio" name="adminmail" value="1" <?php if ($config->admin_mail == '1') echo 'checked="checked"'; ?> > Yes &nbsp; <input type="radio" name="adminmail" value="0" <?php if ($config->admin_mail == '0') echo 'checked="checked"'; ?> > No </td>
		<td class="explain">Activate / deactivate send mail to admin on all exchange status (by default: allways send mail to admin)</td><tr />

		<th> Text mail:</th> <td><textarea rows="18" cols="38" name="emailtext"><?php echo $config->email_text ?></textarea></td>
		<td class="explain">Configure as you like the mail text that will be send to all users every time an exchange occurs.<br />
		Be carefull with the "variables" / "parameters". If something goes wrong, copy and paste this original text:<br />
		<pre><?php echo $config->email_original_text ?></pre>
		</td><tr />
			
		<td colspan="3" style="background-color:#fff;">	<input type="submit" value="SAVE DATA" style="float:right;" class="button" /></td>
		</table>
	</form>
	
<!-- TIMEBANK STYLE CLOSE -->	
</div>
