<div class="submitbox" id="submitslider">
<div id="wa-publishing-actions">
<div class="wa-pub-section"> 
</div>
</div>
<div id="major-publishing-actions">	
	<?php if ( $post_status == 'publish'  ): ?>
		<div id="delete-action">
			<a class="submitdelete deletion" href="<?php echo $delete_link ?>">
				<?php echo __( 'Move to Trash', 'wps' ); ?>
			</a>
		</div>
	<?php endif; ?>
	<div id="publishing-action">
		<?php 
			if ( $post_status != 'publish'  ):
				submit_button( __( 'Create slider', 'wps' ), 'primary', 'publish', false );
			else:
				submit_button( __( 'Update slider', 'wps' ), 'primary', 'submit', false );
			endif; ?>
	</div>
</div>
<div class="clear"></div>
</div>
<input type="hidden" name="post_type_is_wa_wps" value="yes" />
<input type="hidden" name="slides_order" id="unique_slides_order" value="" />