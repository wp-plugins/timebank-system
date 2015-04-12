
<!-- START NEW_EXCHANGE.PHP: HTML with ajax --> 
<!-- classes tbrequest and tbsend are shown or hidden on click selection -->
<div class="timebank">
    <div id="showExchangeWindow" style="display: none;">
		<div style="width:100%; position:relative; float:left;">
			<div class="bigbutton"><a id="requestbutton">Request <?php echo $config->currency ?> ></a></div> 
			<div class="bigbutton"><a id="sendbutton">Send <?php echo $config->currency ?> ></a></div>
			
			<div id="result"></div>   
			<input id="sellerUserId" class="tbrequest" name="sellerUserId" type="hidden" value="<?php echo $user = isWpUser(); ?>" />
			<input id="buyerUserId" class="tbsend" name="buyerUserId" type="hidden" value="<?php echo $user = isWpUser(); ?>" />
			
			<div id="popupexchange">
				<div class="tbrequest clearboth"><?php echo __('Request' , 'timebank') . ' ' . $config->currency . ' ' . __('from', 'timebank') ?>:</div>
				<div class="tbrequest"><input id="buyerUserName" name="buyerusername" placeholder="username" type="text" size="19" maxlength="20" /></div>
				<div class="tbsend clearboth"><?php echo __('Send' , 'timebank') . ' ' . $config->currency . ' ' . __('to', 'timebank') ?>:</div>
				<div class="tbsend"><input id="sellerUserName" name="sellerusername" placeholder="username" type="text" size="19" maxlength="20" /></div>
				
				<div class="clearboth"><?php _e('Amount', 'timebank'); ?>: </div>
				<div><input id="amount" name="amount" type="text" size="4" maxlength="5" style="text-align:right;" /> <?php echo $config->currency ?></div>

				<div class="clearboth"><?php _e('Description', 'timebank'); ?>: </div>
				<div><input id="description" name="description" type="text" size="33" maxlength="37" /></div>

				<div class="tbrequest clearboth" colspan="2" style="background-color:#FFFFFF;"><input id="TBREQUEST" class="tbcheck" type="submit" name="ACCEPT" value="<?php _e('Request', 'timebank'); ?>" /></div>
				<div class="tbsend clearboth" colspan="2" style="background-color:#FFFFFF;"><input id="TBSEND" class="tbcheck" type="submit" name="ACCEPT" value="<?php _e('Send', 'timebank'); ?>" /></div>
			</div>
		</div>
	</div>

<?php $nonce = wp_create_nonce( 'new_transfer' ); ?>

        <script type="text/javascript">
        jQuery(document).ready(function(){

            /* Clear inputs on load */
            jQuery("#buyerUserName").val(''); jQuery("#amount").val(''); jQuery("#description").val(''); jQuery("#TBREQUEST, TBHIDE").prop('disabled', false);
			
			// Routine check -> checkdata
			jQuery( ".tbcheck" ).click(checkdata);
			
			// TB Request insert 
            jQuery( "#TBREQUEST" ).click(function() {
				if (checkdata()){
				
					if ( jQuery( "#buyerUserName" ).val() == "" ){
						alert ("Please enter user name");
						jQuery("#TBREQUEST, #TBSEND").show();
						return 0;
					}				
					if (jQuery.ajax({
						type: "post",url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
						data: { action: 'new_transfer', _ajax_nonce: '<?php echo $nonce; ?>', sellerUserId: jQuery( "#sellerUserId" ).val(), buyerUserName: jQuery( "#buyerUserName" ).val(), amount: jQuery( "#amount" ).val(), description: jQuery( "#description" ).val(), createdBy: jQuery( "#sellerUserId" ).val()  },
						success: function(html){ 
								jQuery("#result").html(html + "<br />").fadeOut().fadeIn();
								//thickbox auto fade: setTimeout( function() {parent.tb_remove(); },4000);  	
								}
					})){ 
						jQuery("#result").html("<center>Please wait... </center><br />").fadeOut().fadeIn(); 
						setTimeout( function() { jQuery("#TBREQUEST, #TBSEND").show(); },5000);
					}
				}
            });
			
			// TB Send insert 
            jQuery( "#TBSEND" ).click(function() {
				if (checkdata()){
					
					if ( jQuery( "#sellerUserName" ).val() == "" ){
						alert ("Please enter user name");
						jQuery("#TBREQUEST, #TBSEND").show();
						return 0;
					}
					if (jQuery.ajax({
						type: "post",url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
						data: { action: 'new_transfer', _ajax_nonce: '<?php echo $nonce; ?>', sellerUserName: jQuery( "#sellerUserName" ).val(), buyerUserId: jQuery( "#buyerUserId" ).val(), amount: jQuery( "#amount" ).val(), description: jQuery( "#description" ).val(), createdBy: jQuery( "#buyerUserId" ).val()  },
						success: function(html){ 
								jQuery("#result").html(html + "<br />").fadeOut().fadeIn();
								//thickbox auto fade: setTimeout( function() {parent.tb_remove(); },4000);  	
								}
					})){ 
						jQuery("#result").html("<center>Please wait... </center><br />").fadeOut().fadeIn(); 
						setTimeout( function() { jQuery("#TBREQUEST, #TBSEND").show(); },5000);
					}
				}
            });

			// Routine check on both forms
			function checkdata() {
				
				//First prevent multiples clicks during 5seconds
				jQuery("#TBREQUEST, #TBSEND").hide();
				
                if ( isNaN(jQuery( "#amount" ).val()) || jQuery( "#amount" ).val() < 1 ){
                    alert ("Amount has to be set and positive integer");
					jQuery("#TBREQUEST, #TBSEND").show();
                    return 0;
                }
                if ( jQuery( "#description" ).val() == "" ){
                    alert ("Please enter description");
					jQuery("#TBREQUEST, #TBSEND").show();
                    return 0;
                }
				return 1;
			};
			
			//button click actions
			jQuery(".tbsend").addClass('hide');
			
			jQuery('#requestbutton').click(function() {
				jQuery(".tbrequest").addClass('show');
				jQuery(".tbrequest").removeClass('hide');
				jQuery(".tbsend").addClass('hide');
				jQuery(".tbsend").removeClass('show');
			});
			jQuery('#sendbutton').click(function() {
				jQuery(".tbrequest").addClass('hide');
				jQuery(".tbrequest").removeClass('show');
				jQuery(".tbsend").addClass('show');
				jQuery(".tbsend").removeClass('hide');
			});
			
        });
        </script>
</div>      
<!-- END NEW_REQUEST.PHP --> 
