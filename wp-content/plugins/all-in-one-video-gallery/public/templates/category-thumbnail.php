<?php

/**
 * Category Thumbnail.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */

$images_settings = get_option( 'aiovg_images_settings' );

$permalink = aiovg_get_category_page_url( $term );   

$image_size = ! empty( $images_settings['size'] ) ? $images_settings['size'] : 'large';
$image_data = aiovg_get_image( $term->term_id, $image_size, 'term', true );
$image = $image_data['src'];
$image_alt = ! empty( $image_data['alt'] ) ? $image_data['alt'] : $term->name;
?>

<div class="aiovg-thumbnail">
    <a href="<?php echo esc_url( $permalink ); ?>" class="aiovg-responsive-container" style="padding-bottom: <?php echo esc_attr( $attributes['ratio'] ); ?>;">
        <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" class="aiovg-responsive-element" />
    </a>
    
    <div class="aiovg-caption">
        <div class="aiovg-title">
            <a href="<?php echo esc_url( $permalink ); ?>" class="aiovg-link-title">
                <?php echo esc_html( $term->name ); ?>
            </a>
        </div>
            
        <?php if ( ! empty( $attributes['show_description'] ) && $term->description ) : ?>
            <div class="aiovg-description">
                <?php echo wp_kses_post( nl2br( $term->description ) ); ?>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $attributes['show_count'] ) ) : ?>
            <div class="aiovg-count aiovg-text-small aiovg-text-muted">
                <svg class="aiovg-svg-icon aiovg-svg-icon-videos" width="16" height="16" viewBox="0 0 32 32">
                    <path d="M0 4v24h32v-24h-32zM6 26h-4v-4h4v4zM6 18h-4v-4h4v4zM6 10h-4v-4h4v4zM24 26h-16v-20h16v20zM30 26h-4v-4h4v4zM30 18h-4v-4h4v4zM30 10h-4v-4h4v4zM12 10v12l8-6z"></path>
                </svg>
                <?php printf( _n( '%s video', '%s videos', $term->count, 'all-in-one-video-gallery' ), $term->count ); ?>
            </div>
        <?php endif; ?>
    </div>            			
</div>