<?php

/**
 * Videos: "Video Sources" meta box.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */
?>

<table class="aiovg-table widefat">
  	<tbody>
    	<tr>
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Source Type', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>        
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Source Type', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<select name="type" id="aiovg-video-type" class="select">
                	<?php 
					$types = aiovg_get_video_source_types( true );
					foreach ( $types as $key => $label ) {
						printf( '<option value="%s"%s>%s</option>', $key, selected( $key, $type, false ), $label );
					}
					?>
        		</select>
      		</td>
    	</tr>
    	<tr id="aiovg-field-mp4" class="aiovg-toggle-fields aiovg-type-default">
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Video File', 'all-in-one-video-gallery' ); ?></label>
				<div class="aiovg-text-muted">(mp4, webm, ogv, m4v, mov)</div>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Video File', 'all-in-one-video-gallery' ); ?></strong> <span class="aiovg-text-muted">(mp4, webm, ogv, m4v, mov)</span>
				</p>
				
				<div class="aiovg-input-wrap aiovg-media-uploader">
					<?php
					if ( ! empty( $quality_levels ) ) {
						printf(
							'<div class="aiovg-quality-selector"%s>',
							( empty( $sources ) ? ' style="display: none;"' : '' )
						);

						printf( 
							'<p><span class="dashicons dashicons-arrow-down-alt2"></span> %s (%s)</p>',
							esc_html__( 'Select a Quality Level', 'all-in-one-video-gallery' ),
							esc_html__( 'This will be the default quality level for this video', 'all-in-one-video-gallery' )
						);

						echo '<ul class="aiovg-radio horizontal">';

						printf( 
							'<li><label><input type="radio" name="quality_level" value=""%s/>%s</label></li>',
							checked( $quality_level, '', false ),
							esc_html__( 'None', 'all-in-one-video-gallery' )
						);

						foreach ( $quality_levels as $quality ) {
							printf( 
								'<li><label><input type="radio" name="quality_level" value="%s"%s/>%s</label></li>',
								esc_attr( $quality ),
								checked( $quality_level, $quality, false ),
								esc_html( $quality )
							);
						}

						echo '</ul>';
						echo '</div>';
					}
					?>                                                
					<input type="text" name="mp4" id="aiovg-mp4" class="text" placeholder="<?php esc_attr_e( 'Enter your direct file URL (OR) upload your file using the button here', 'all-in-one-video-gallery' ); ?> &rarr;" value="<?php echo esc_attr( $mp4 ); ?>" />
					<div class="aiovg-upload-media hide-if-no-js">
						<a href="javascript:;" id="aiovg-upload-mp4" class="button button-secondary" data-format="mp4">
							<?php esc_html_e( 'Upload File', 'all-in-one-video-gallery' ); ?>
						</a>
					</div>
				</div>

				<?php if ( ! empty( $sources ) ) : 
					foreach ( $sources as $index => $source ) :	?>
						<div class="aiovg-input-wrap aiovg-media-uploader aiovg-source">
							<?php
							echo '<div class="aiovg-quality-selector">';

							printf( 
								'<p><span class="dashicons dashicons-arrow-down-alt2"></span> %s</p>',
								esc_html__( 'Select a Quality Level', 'all-in-one-video-gallery' )
							);

							echo '<ul class="aiovg-radio horizontal">';

							printf( 
								'<li><label><input type="radio" name="quality_levels[%d]" value=""%s/>%s</label></li>',
								$index,
								checked( $source['quality'], '', false ),
								esc_html__( 'None', 'all-in-one-video-gallery' )
							);

							foreach ( $quality_levels as $quality ) {
								printf( 
									'<li><label><input type="radio" name="quality_levels[%d]" value="%s"%s/>%s</label></li>',
									$index,
									esc_attr( $quality ),
									checked( $source['quality'], $quality, false ),
									esc_html( $quality )
								);
							}
							
							echo '</ul>';
							echo '</div>';
							?>
							<input type="text" name="sources[<?php echo $index; ?>]" class="text" placeholder="<?php esc_attr_e( 'Enter your direct file URL (OR) upload your file using the button here', 'all-in-one-video-gallery' ); ?> &rarr;" value="<?php echo esc_attr( $source['src'] ); ?>" />
							<div class="aiovg-upload-media hide-if-no-js">
								<a href="javascript:;" class="button button-secondary aiovg-button-upload" data-format="mp4">
									<?php esc_html_e( 'Upload File', 'all-in-one-video-gallery' ); ?>
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( ! empty( $quality_levels ) && count( $sources ) < ( count( $quality_levels ) - 1 ) ) : ?>
					<a href="javascript:;" id="aiovg-add-new-source" data-limit="<?php echo count( $quality_levels ); ?>"><?php esc_html_e( '[+] Add More Quality Levels', 'all-in-one-video-gallery' ); ?></a>
				<?php endif; ?> 
      		</td>
    	</tr>
		<?php if ( ! empty( $webm ) ) : ?>
			<tr id="aiovg-field-webm" class="aiovg-toggle-fields aiovg-type-default">
				<td class="label aiovg-hidden-xs">
					<label><?php esc_html_e( 'WebM', 'all-in-one-video-gallery' ); ?></label>
					<div class="aiovg-text-error">(<?php esc_html_e( 'deprecated', 'all-in-one-video-gallery' ); ?>)</div>
				</td>
				<td>
					<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
						<strong><?php esc_html_e( 'WebM', 'all-in-one-video-gallery' ); ?></strong> <span class="aiovg-text-error">(<?php esc_html_e( 'deprecated', 'all-in-one-video-gallery' ); ?>)</span>
					</p>

					<div class="aiovg-input-wrap aiovg-media-uploader">                                                
						<input type="text" name="webm" id="aiovg-webm" class="text" placeholder="<?php esc_attr_e( 'Enter your direct file URL (OR) upload your file using the button here', 'all-in-one-video-gallery' ); ?> &rarr;" value="<?php echo esc_attr( $webm ); ?>" />
						<div class="aiovg-upload-media hide-if-no-js">
							<a href="javascript:;" id="aiovg-upload-webm" class="button button-secondary" data-format="webm">
								<?php esc_html_e( 'Upload File', 'all-in-one-video-gallery' ); ?>
							</a>
						</div>
					</div>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ( ! empty( $ogv ) ) : ?>
			<tr id="aiovg-field-ogv" class="aiovg-toggle-fields aiovg-type-default">
				<td class="label aiovg-hidden-xs">
					<label><?php esc_html_e( 'OGV', 'all-in-one-video-gallery' ); ?></label>
					<div class="aiovg-text-error">(<?php esc_html_e( 'deprecated', 'all-in-one-video-gallery' ); ?>)</div>
				</td>
				<td>
					<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
						<strong><?php esc_html_e( 'OGV', 'all-in-one-video-gallery' ); ?></strong> <span class="aiovg-text-error">(<?php esc_html_e( 'deprecated', 'all-in-one-video-gallery' ); ?>)</span>
					</p>
					
					<div class="aiovg-input-wrap aiovg-media-uploader">                                                
						<input type="text" name="ogv" id="aiovg-ogv" class="text" placeholder="<?php esc_attr_e( 'Enter your direct file URL (OR) upload your file using the button here', 'all-in-one-video-gallery' ); ?> &rarr;" value="<?php echo esc_attr( $ogv ); ?>" />
						<div class="aiovg-upload-media hide-if-no-js">
							<a href="javascript:;" id="aiovg-upload-ogv" class="button button-secondary" data-format="ogv">
								<?php esc_html_e( 'Upload File', 'all-in-one-video-gallery' ); ?>
							</a>
						</div>
					</div> 
				</td>
			</tr> 
		<?php endif; ?> 
		<tr class="aiovg-toggle-fields aiovg-type-adaptive">
			<td class="label aiovg-hidden-xs">
				<label><?php esc_html_e( 'HLS', 'all-in-one-video-gallery' ); ?></label>
			</td>
			<td>
				<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'HLS', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				
				<div class="aiovg-input-wrap">
					<input type="text" name="hls" id="aiovg-hls" class="text" placeholder="<?php printf( '%s: https://www.mysite.com/stream.m3u8', esc_attr__( 'Example', 'all-in-one-video-gallery' ) ); ?>" value="<?php echo esc_url( $hls ); ?>" />
				</div>
			</td>
		</tr>
		<tr class="aiovg-toggle-fields aiovg-type-adaptive">
			<td class="label aiovg-hidden-xs">
				<label><?php esc_html_e( 'MPEG-DASH', 'all-in-one-video-gallery' ); ?></label>
			</td>
			<td>
				<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'MPEG-DASH', 'all-in-one-video-gallery' ); ?></strong>
				</p>

				<div class="aiovg-input-wrap">
					<input type="text" name="dash" id="aiovg-dash" class="text" placeholder="<?php printf( '%s: https://www.mysite.com/stream.mpd', esc_attr__( 'Example', 'all-in-one-video-gallery' ) ); ?>" value="<?php echo esc_url( $dash ); ?>" />
				</div>
			</td>
		</tr>
    	<tr class="aiovg-toggle-fields aiovg-type-youtube">
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'YouTube URL', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'YouTube URL', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<div class="aiovg-input-wrap">
          			<input type="text" name="youtube" id="aiovg-youtube" class="text" placeholder="<?php printf( '%s: https://www.youtube.com/watch?v=twYp6W6vt2U', esc_attr__( 'Example', 'all-in-one-video-gallery' ) ); ?>" value="<?php echo esc_url( $youtube ); ?>" />
				</div>
      		</td>
    	</tr>
    	<tr class="aiovg-toggle-fields aiovg-type-vimeo">
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Vimeo URL', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Vimeo URL', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<div class="aiovg-input-wrap">
          			<input type="text" name="vimeo" id="aiovg-vimeo" class="text" placeholder="<?php printf( '%s: https://vimeo.com/108018156', esc_attr__( 'Example', 'all-in-one-video-gallery' ) ); ?>" value="<?php echo esc_url( $vimeo ); ?>" />
				</div>
      		</td>
    	</tr>
        <tr class="aiovg-toggle-fields aiovg-type-dailymotion">
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Dailymotion URL', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Dailymotion URL', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<div class="aiovg-input-wrap">
          			<input type="text" name="dailymotion" id="aiovg-dailymotion" class="text" placeholder="<?php printf( '%s: https://www.dailymotion.com/video/x11prnt', esc_attr__( 'Example', 'all-in-one-video-gallery' ) ); ?>" value="<?php echo esc_url( $dailymotion ); ?>" />
				</div>
      		</td>
    	</tr>
		<tr class="aiovg-toggle-fields aiovg-type-rumble">
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Rumble URL', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Rumble URL', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<div class="aiovg-input-wrap">
          			<input type="text" name="rumble" id="aiovg-rumble" class="text" placeholder="<?php printf( '%s: https://rumble.com/val8vm-how-to-use-rumble.html', esc_attr__( 'Example', 'all-in-one-video-gallery' ) ); ?>" value="<?php echo esc_url( $rumble ); ?>" />
				</div>
      		</td>
    	</tr>
        <tr class="aiovg-toggle-fields aiovg-type-facebook">
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Facebook URL', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Facebook URL', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<div class="aiovg-input-wrap">
          			<input type="text" name="facebook" id="aiovg-facebook" class="text" placeholder="<?php printf( '%s: https://www.facebook.com/facebook/videos/10155278547321729', esc_attr__( 'Example', 'all-in-one-video-gallery' ) ); ?>" value="<?php echo esc_url( $facebook ); ?>" />
				</div>
      		</td>
    	</tr>
        <tr class="aiovg-toggle-fields aiovg-type-embedcode">
            <td class="label aiovg-hidden-xs">
                <label><?php esc_html_e( 'Embed Code', 'all-in-one-video-gallery' ); ?></label>
            </td>
            <td>
                <p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Embed Code', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				
				<textarea name="embedcode" id="aiovg-embedcode" class="textarea" rows="6" placeholder="<?php esc_attr_e( 'Enter your Iframe Embed Code', 'all-in-one-video-gallery' ); ?>"><?php echo esc_textarea( $embedcode ); ?></textarea>

				<p>
					<?php
					printf(
						'<span class="aiovg-text-error"><strong>%s</strong></span>: %s',
						esc_html__( 'Warning', 'all-in-one-video-gallery' ),
						esc_html__( 'This field allows "iframe" and "script" tags. So, make sure the code you\'re adding with this field is harmless to your website.', 'all-in-one-video-gallery' )
					);
					?>
				</p>
            </td>
        </tr>
        <?php do_action( 'aiovg_admin_add_video_source_fields', $post->ID ); ?>
   	 	<tr>
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Thumbnail Image', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Thumbnail Image', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				
				<div id="aiovg-image-uploader" class="aiovg-input-wrap aiovg-media-uploader">                                                
					<input type="text" name="image" id="aiovg-image" class="text" placeholder="<?php esc_attr_e( 'Enter your direct file URL (OR) upload your file using the button here', 'all-in-one-video-gallery' ); ?> &rarr;" value="<?php echo esc_attr( $image ); ?>" />
					<div class="aiovg-upload-media hide-if-no-js">
						<a href="javascript:;" id="aiovg-upload-image" class="button button-secondary" data-format="image">
							<?php esc_html_e( 'Upload File', 'all-in-one-video-gallery' ); ?>
						</a>
					</div>
				</div>

				<?php if ( ! empty( $featured_images_settings['enabled'] ) ) : ?>
					<p>
						<label>
							<input type="checkbox" name="set_featured_image" value="1" <?php checked( $set_featured_image, 1 ); ?>/>
							<?php esc_html_e( 'Store this image as a featured image.', 'all-in-one-video-gallery' ); ?>
						</label>
					</p>
				<?php endif; ?>

				<?php do_action( 'aiovg_admin_after_image_field' ); ?> 
      		</td>
    	</tr> 
    	<tr>
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Video Duration', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Video Duration', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<div class="aiovg-input-wrap">
          			<input type="text" name="duration" id="aiovg-duration" class="text" placeholder="6:30" value="<?php echo esc_attr( $duration ); ?>" />
       			</div>
      		</td>
    	</tr>
    	<tr>
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Views Count', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Views Count', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<div class="aiovg-input-wrap">
          			<input type="text" name="views" id="aiovg-views" class="text" value="<?php echo esc_attr( $views ); ?>" />
       			</div>
      		</td>
    	</tr>
		<tr id="aiovg-field-download" class="aiovg-toggle-fields aiovg-type-default">
      		<td class="label aiovg-hidden-xs">
        		<label><?php esc_html_e( 'Download', 'all-in-one-video-gallery' ); ?></label>
      		</td>
      		<td>
        		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg">
					<strong><?php esc_html_e( 'Download', 'all-in-one-video-gallery' ); ?></strong>
				</p>
				  
				<label>
					<input type="checkbox" name="download" value="1" <?php checked( $download, 1 ); ?> />
					<?php esc_html_e( 'Check this option to allow users to download this video.', 'all-in-one-video-gallery' ); ?>
				</label>
      		</td>
    	</tr>     
  	</tbody>
</table>

<?php if ( ! empty( $quality_levels ) ) : ?>
	<div id="aiovg-source-clone" style="display: none;">
		<div class="aiovg-input-wrap aiovg-media-uploader aiovg-source">
			<?php
			echo '<div class="aiovg-quality-selector">';
			printf( 
				'<p><span class="dashicons dashicons-arrow-down-alt2"></span> %s</p>',
				esc_html__( 'Select a Quality Level', 'all-in-one-video-gallery' )
			);
			echo '<ul class="aiovg-radio horizontal">';
			printf( 
				'<li><label><input type="radio" value=""/>%s</label></li>',
				esc_html__( 'None', 'all-in-one-video-gallery' )
			);
			foreach ( $quality_levels as $quality ) {
				printf( 
					'<li><label><input type="radio" value="%s"/>%s</label></li>',
					esc_attr( $quality ),
					esc_html( $quality )
				);
			}
			echo '</ul>';
			echo '</div>';
			?>
			<input type="text" class="text" placeholder="<?php esc_attr_e( 'Enter your direct file URL (OR) upload your file using the button here', 'all-in-one-video-gallery' ); ?> &rarr;" value="" />
			<div class="aiovg-upload-media hide-if-no-js">
				<a href="javascript:;" class="button button-secondary aiovg-button-upload" data-format="mp4">
					<?php esc_html_e( 'Upload File', 'all-in-one-video-gallery' ); ?>
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
// Add a nonce field
wp_nonce_field( 'aiovg_save_video_sources', 'aiovg_video_sources_nonce' );