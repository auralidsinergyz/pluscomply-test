<?php

/**
 * Videos: "Subtitles" meta box.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */
?>

<table id="aiovg-tracks" class="aiovg-table widefat">
	<tr class="aiovg-hidden-xs">
  		<th style="width: 5%;"></th>
    	<th><?php esc_html_e( 'File URL', 'all-in-one-video-gallery' ); ?></th>
    	<th style="width: 15%;"><?php esc_html_e( 'Label', 'all-in-one-video-gallery' ); ?></th>
    	<th style="width: 10%;"><?php esc_html_e( 'Srclang', 'all-in-one-video-gallery' ); ?></th>
    	<th style="width: 20%;"></th>
  	</tr>
  	<?php foreach ( $tracks as $key => $track ) : ?>
        <tr class="aiovg-tracks-row">
            <td class="aiovg-handle aiovg-hidden-xs"><span class="dashicons dashicons-move"></span></td>
            <td>
                <p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg"><strong><?php esc_html_e( 'File URL', 'all-in-one-video-gallery' ); ?></strong></p>
                <div class="aiovg-input-wrap">
                    <input type="text" name="track_src[]" id="aiovg-track-<?php echo esc_attr( $key ); ?>" class="text aiovg-track-src" value="<?php echo esc_attr( $track['src'] ); ?>" />
                </div>
            </td>
            <td>
                <p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg"><strong><?php esc_html_e( 'Label', 'all-in-one-video-gallery' ); ?></strong></p>
                    <div class="aiovg-input-wrap">
                        <input type="text" name="track_label[]" class="text aiovg-track-label" placeholder="<?php esc_attr_e( 'English', 'all-in-one-video-gallery' ); ?>" value="<?php echo esc_attr( $track['label'] ); ?>" />
                    </div>
            </td>
            <td>
                <p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg"><strong><?php esc_html_e( 'Srclang', 'all-in-one-video-gallery' ); ?></strong></p>
                <div class="aiovg-input-wrap">
                    <input type="text" name="track_srclang[]" class="text aiovg-track-srclang" placeholder="<?php esc_attr_e( 'en', 'all-in-one-video-gallery' ); ?>" value="<?php echo esc_attr( $track['srclang'] ); ?>" />
                </div>
            </td>
            <td>
                <div class="hide-if-no-js">
                    <a class="aiovg-upload-track" href="javascript:;"><?php esc_html_e( 'Upload File', 'all-in-one-video-gallery' ); ?></a>
                    <span class="aiovg-pipe-separator">|</span>
                    <a class="aiovg-delete-track" href="javascript:;"><?php esc_html_e( 'Delete', 'all-in-one-video-gallery' ); ?></a>
	  			</div>
            </td>
        </tr>
  	<?php endforeach; ?>
</table>

<p class="hide-if-no-js">
   	<a id="aiovg-add-new-track" class="button" href="javascript:;"><?php esc_html_e( 'Add New File', 'all-in-one-video-gallery' ); ?></a>
</p>

<table id="aiovg-tracks-clone" style="display: none;">
  	<tr class="aiovg-tracks-row">
    	<td class="aiovg-handle aiovg-hidden-xs"><span class="dashicons dashicons-move"></span></td>
  		<td>
      		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg"><strong><?php esc_html_e( 'File URL', 'all-in-one-video-gallery' ); ?></strong></p>
      		<div class="aiovg-input-wrap">
        		<input type="text" name="track_src[]" class="text aiovg-track-src" />
      		</div>
    	</td>
    	<td>
      		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg"><strong><?php esc_html_e( 'Label', 'all-in-one-video-gallery' ); ?></strong></p>
      		<div class="aiovg-input-wrap">
        		<input type="text" name="track_label[]" class="text aiovg-track-label" placeholder="<?php esc_attr_e( 'English', 'all-in-one-video-gallery' ); ?>" />
      		</div>
    	</td>
    	<td>
      		<p class="aiovg-hidden-sm aiovg-hidden-md aiovg-hidden-lg"><strong><?php esc_html_e( 'Srclang', 'all-in-one-video-gallery' ); ?></strong></p>
      		<div class="aiovg-input-wrap">
        		<input type="text" name="track_srclang[]" class="text aiovg-track-srclang" placeholder="<?php esc_attr_e( 'en', 'all-in-one-video-gallery' ); ?>" />
      		</div>
    	</td>
    	<td>
      		<div class="hide-if-no-js">
        		<a class="aiovg-upload-track" href="javascript:;"><?php esc_html_e( 'Upload File', 'all-in-one-video-gallery' ); ?></a>
        		<span class="aiovg-pipe-separator">|</span>
        		<a class="aiovg-delete-track" href="javascript:;"><?php esc_html_e( 'Delete', 'all-in-one-video-gallery' ); ?></a>
	  		</div>
    	</td>
  	</tr>
</table>

<?php
// Add a nonce field
wp_nonce_field( 'aiovg_save_video_tracks', 'aiovg_video_tracks_nonce' );