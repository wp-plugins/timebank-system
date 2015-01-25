
<!-- START NEW_REQUEST.PHP: HTML with ajax --> 

    <div style="display: none;">
        <div id="inline1" style="overflow:auto;">

        <div style="width:450px; margin-top:10px;">
        <strong class="fancytitle">REQUEST TIME TO A USER</strong>
        <br /><br /><div id="result"></div>   
                <input id="sellerUserId" name="sellerUserId" type="hidden" value="<?php echo $user = isWpUser(); ?>" />
                <input name="option" type="hidden" value="send" />	
                <table id="status" width="420">
                <td>Username: </td><td><input id="buyerUserName" name="buyerusername" type="text" size="19" maxlength="20" /></td><tr>
                <td>Amount: </td><td><input id="amount" name="amount" type="text" size="4" maxlength="5" style="text-align:right;" /> <?php echo $config->currency ?></td><tr>
                <td>Description: </td><td><input id="description" name="description" type="text" size="33" style="width:260px;" maxlength="37" /></td><tr>
                <td colspan="2" style="background-color:#FFFFFF;"><input id="ACCEPT" type="submit" name="ACCEPT" value="SEND" style="float:right;" /></td>
                </table>
        </div>

<?php $nonce = wp_create_nonce( 'new_transfer' ); ?>

        <script type="text/javascript">
        jQuery(document).ready(function(){

            /* Clear inputs on load */
            jQuery("#buyerUserName").val(''); jQuery("#amount").val(''); jQuery("#description").val(''); jQuery("#ACCEPT").prop('disabled', false);

            jQuery( "#ACCEPT" ).click(function() {
			
				//First prevent multiples clicks during 5seconds
				jQuery("#ACCEPT").hide();
					
                if ( jQuery( "#buyerUserName" ).val() == "" ){
                    alert ("Please enter user name");
					jQuery("#ACCEPT").show();
                    return 0;
                }
				
                if ( isNaN(jQuery( "#amount" ).val()) || jQuery( "#amount" ).val() < 1 ){
                    alert ("Amount has to be set and positive integer");
					jQuery("#ACCEPT").show();
                    return 0;
                }
                if ( jQuery( "#description" ).val() == "" ){
                    alert ("Please enter description");
					jQuery("#ACCEPT").show();
                    return 0;
                }
                if (jQuery.ajax({
                    type: "post",url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                    data: { action: 'new_transfer', _ajax_nonce: '<?php echo $nonce; ?>', sellerUserId: jQuery( "#sellerUserId" ).val(), buyerUserName: jQuery( "#buyerUserName" ).val(), amount: jQuery( "#amount" ).val(), description: jQuery( "#description" ).val()  },
                    success: function(html){ 
                            jQuery("#result").html(html + "<br />").fadeOut().fadeIn();
							setTimeout( function() {parent.tb_remove(); },4000);  
							//problematico el autorefresco setTimeout(window.location.href = "<?php echo pathToTimebank() ?>", 2000);	 	
                    		}
                })){ 
					jQuery("#result").html("<center>Please wait... </center><br />").fadeOut().fadeIn(); 
				}; 
            });
        });
        </script>
        
        </div>
    </div>
<!-- END NEW_REQUEST.PHP --> 
