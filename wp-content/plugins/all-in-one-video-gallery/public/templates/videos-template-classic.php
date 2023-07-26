<?php

/**
 * Videos: Classic Template.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */
?>

<div id="aiovg-<?php echo esc_attr( $attributes['uid'] ); ?>" class="aiovg aiovg-videos aiovg-videos-template-classic">
	<?php                    
    // Display the title (if applicable)
    if ( ! empty( $attributes['title'] ) ) : ?>
        <h3 class="aiovg-header">
            <?php echo esc_html( $attributes['title'] ); ?>
        </h3>
    <?php 
    endif;

    // Display the videos count
    if ( ! empty( $attributes['show_count'] ) ) : ?>
    	<div class="aiovg-count">
			<?php printf( esc_html__( "%d video(s) found", 'all-in-one-video-gallery' ), $attributes['count'] ); ?>
        </div>
    <?php endif;
    
    // The loop
    echo '<div class="aiovg-grid aiovg-row">';
        
    while ( $aiovg_query->have_posts() ) :        
        $aiovg_query->the_post();           
        ?>            
        <div class="aiovg-col aiovg-col-<?php echo (int) $attributes['columns']; ?>">
            <?php the_aiovg_video_thumbnail( $post, $attributes ); ?>            
        </div>                
        <?php 
    endwhile;

    echo '</div>';
        
    // Use reset postdata to restore orginal query
    wp_reset_postdata();        
    
    if ( ! empty( $attributes['show_pagination'] ) ) { // Pagination
        the_aiovg_pagination( $aiovg_query->max_num_pages, "", $attributes['paged'], $attributes );
    } elseif ( ! empty( $attributes['show_more'] ) ) { // More button        
        the_aiovg_more_button( $aiovg_query->max_num_pages, $attributes );
    }
    ?>
</div>