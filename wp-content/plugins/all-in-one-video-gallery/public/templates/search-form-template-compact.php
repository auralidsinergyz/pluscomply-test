<?php

/**
 * Search Form: Compact Layout.
 *
 * @link    https://plugins360.com
 * @since   3.0.0
 *
 * @package All_In_One_Video_Gallery
 */
?>

<div class="aiovg aiovg-search-form aiovg-search-form-template-compact">
	<form method="get" action="<?php echo esc_url( aiovg_get_search_page_url() ); ?>">
    	<?php if ( ! get_option('permalink_structure') ) : ?>
       		<input type="hidden" name="page_id" value="<?php echo esc_attr( $attributes['search_page_id'] ); ?>" />
    	<?php endif; ?>

		<div class="aiovg-form-group aiovg-field-keyword">
			<input type="text" name="vi" class="aiovg-form-control" placeholder="<?php esc_attr_e( 'Search Videos', 'all-in-one-video-gallery' ); ?>" value="<?php echo isset( $_GET['vi'] ) ? esc_attr( $_GET['vi'] ) : ''; ?>" />
		</div>
		
		<!-- Hook for developers to add new fields -->
        <?php do_action( 'aiovg_search_form_fields', $attributes ); ?>		

		<div class="aiovg-form-group aiovg-field-submit">
			<button type="submit" class="aiovg-button"> 
				<svg class="aiovg-svg-icon aiovg-svg-icon-search" width="16" height="16" viewBox="0 0 32 32">
					<path d="M31.008 27.231l-7.58-6.447c-0.784-0.705-1.622-1.029-2.299-0.998 1.789-2.096 2.87-4.815 2.87-7.787 0-6.627-5.373-12-12-12s-12 5.373-12 12 5.373 12 12 12c2.972 0 5.691-1.081 7.787-2.87-0.031 0.677 0.293 1.515 0.998 2.299l6.447 7.58c1.104 1.226 2.907 1.33 4.007 0.23s0.997-2.903-0.23-4.007zM12 20c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"></path>
				</svg>
			</button>
		</div>		
	</form> 
</div>
