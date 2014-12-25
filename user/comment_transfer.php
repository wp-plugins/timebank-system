        <div style="display: none;">
            <div id="inline3">
                <div style="width:450px;">
                    
        <strong>EXCHANGE RATING</strong>
        <br />
	<table id="status" width="420">
        <br /><div id="commentresult"></div>    
	<td>Comment: </td><td><textarea id="comment2" name="comment2" maxlength="150" rows=3 style="resize: none; width:95%;" /></textarea> </td><tr>
	<td>Rating: </td><td>
		<select id="rate" name="rate" >
		<option value="0">0</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		</select>
		
		<!-- RateIt plugin activation on select -->
		<div class="rateit" data-rateit-backingfld="#rate"></div>
        </td><tr>
	<td colspan="2" style="background-color:#FFFFFF;"><input id="COMMENT_TRANSFER" type="submit" name="ACCEPT" value="SEND" style="float:right;" /></td>
	</table>
         
                </div>
                </center>
            </div>
        </div>    

    <script type="text/javascript">          
    jQuery(document).ready(function(){

        <?php /* AJAX SECURE NONCE */ $nonce = wp_create_nonce( 'comment_transfer' ); ?>
        jQuery( "#COMMENT_TRANSFER" ).click(function() {
                jQuery.ajax({
                        type: "post",url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                        data: { action: 'comment_transfer', _ajax_nonce: '<?php echo $nonce; ?>', exchangeId: exchangeId, concept: jQuery( "#concept_value" + exchangeId ).text(), comment: jQuery( "#comment2" ).val(), rate: jQuery( "#rate" ).val()  },
                        success: function(html){      
                                jQuery("#commentresult").html(html + "<br />").fadeOut().fadeIn();
                                //Update external data + Prepare
                                status = '#status' + exchangeId;
                                rating = '#rating_comment' + exchangeId;
                                rvalue = '#rating_value' + exchangeId + ' div';
                                jQuery( status ).html("Completed").addClass("completed");
                                jQuery( rating ).html(jQuery( "#comment2" ).val());
                                jQuery( rvalue ).html( jQuery( "#rate" ).val() + " stars");
                                setTimeout( function() {parent.tb_remove(); },2000);    
                        }
                }); 
        });

        // Set fancybox visible data
        jQuery('.comment').click(function(){ 
            jQuery("#commentresult").html(''); 
            jQuery("#comment2").val('');
            exchangeId = jQuery(this).attr('id');
        });

    });
    </script>
<!-- END COMMENTTRANSFER.PHP --> 
