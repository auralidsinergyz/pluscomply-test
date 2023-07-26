<?php

/**
 * Categories: Grid Layout.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */
?>

<div id="aiovg-<?php echo esc_attr( $attributes['uid'] ); ?>" class="aiovg aiovg-categories aiovg-categories-template-grid">
	<?php
    // Display the title (if applicable)
    if ( ! empty( $attributes['title'] ) ) : ?>
        <h3 class="aiovg-header">
            <?php echo esc_html( $attributes['title'] ); ?>
        </h3>
    <?php endif;
    
    // Start the loop   
    echo '<div class="aiovg-grid aiovg-row">';

    foreach ( $terms as $key => $term ) {       
        ?>            
        <div class="aiovg-col aiovg-col-<?php echo esc_attr( $attributes['columns'] ); ?>">		
            <?php the_aiovg_category_thumbnail( $term, $attributes ); ?>		
        </div> 
        <?php                       
    }

    echo '</div>'; 

    if ( ! empty( $attributes['show_pagination'] ) ) { // Pagination
        the_aiovg_pagination( $attributes['max_num_pages'], "", $attributes['paged'], $attributes );
    } elseif ( ! empty( $attributes['show_more'] ) ) { // More button
        the_aiovg_more_button( $attributes['max_num_pages'], $attributes );
    }
    ?>
</div>