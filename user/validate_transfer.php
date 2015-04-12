
<!-- START VALIDATETRANSFER.PHP -->    
        <div style="display: none;">
            <div id="inline2">
                <div style="width:100%; margin-top:10px;">
                <strong class="fancytitle">VALIDATE TIME REQUEST</strong>
                <center><br /><div id="validateresult"></div>      
                <br />
                <table id="status">
                From: <user_value></user_value> <br /> Concept: <concept_value></concept_value> <br /> Amount: <amount_value></amount_value> <?php echo $config->currency ?> <br /><br />
                <input id="ACCEPT_TRANSFER" name="ACCEPT" type="submit" value="ACCEPT" /> - <input id="REJECT_TRANSFER" name="REJECT" type="submit" value="REJECT" />
                </table>	
                </div>
                </center>
            </div>
        </div>    

    <script type="text/javascript">          
    jQuery(document).ready(function(){

        <?php /* AJAX SECURE NONCE */ $nonce = wp_create_nonce( 'validate_transfer' ); ?>
        jQuery( "#ACCEPT_TRANSFER" ).click(function() {
                jQuery.ajax({
                        type: "post",url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                        data: { action: 'validate_transfer', _ajax_nonce: '<?php echo $nonce; ?>', exchangeId: exchangeId  },
                        success: function(html){      
                                jQuery("#validateresult").html(html + "<br />").fadeOut().fadeIn();
                                //Update external data + Prepare
                                var status = '#status' + exchangeId;
								var statselect = jQuery(status).parents('.buys').length; //parent has class buys
								if (statselect){ 
									jQuery( status ).html("Accepted <a href=#TB_inline?width=460&height=320&inlineId=inline3 class=\"thickbox comment\">Comment</a>").addClass("accepted");
                                }else{
									jQuery( status ).html("Accepted").addClass("accepted");
								}
								setTimeout( function() {parent.tb_remove(); },2000); 
                                
                        }
                }); 
        });

        <?php /* AJAX SECURE NONCE */ $nonce = wp_create_nonce( 'reject_transfer' ); ?>
        jQuery( "#REJECT_TRANSFER" ).click(function() {
                jQuery.ajax({
                        type: "post",url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                        data: { action: 'reject_transfer', _ajax_nonce: '<?php echo $nonce; ?>', exchangeId: exchangeId  },
                        success: function(html){      
                                jQuery("#validateresult").html(html + "<br />").fadeOut().fadeIn();  
                                //Update external data + Prepare
                                status = '#status' + exchangeId;
                                jQuery( status ).html("Rejected").addClass("rejected");
                                setTimeout( function() {parent.tb_remove(); },2000);
                        }
                }); 
        });

        // Set fancybox visible data
        jQuery('.validate').click(function(){ 
            jQuery("#validateresult").html(''); 
            jQuery("#commentresult").html(''); 
            exchangeId = jQuery(this).attr('id');
            jQuery('user_value').html(jQuery ('#user_value' + exchangeId).html());
            jQuery('concept_value').html(jQuery ('#concept_value' + exchangeId).html());
            jQuery('amount_value').html(jQuery ('#amount_value' + exchangeId).html());
        });

    });
    </script>
<!-- END VALIDATETRANSFER.PHP --> 