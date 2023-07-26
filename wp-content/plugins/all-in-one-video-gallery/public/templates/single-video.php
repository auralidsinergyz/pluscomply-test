<?php

/**
 * Single Video Page.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */
?>

<div class="aiovg aiovg-single-video">
    <!-- Player -->
    <?php the_aiovg_player( $attributes['id'] ); ?>

    <br />

    <!-- Description -->
    <div class="aiovg-description"><?php echo $content; ?></div>

    <!-- Meta informations -->
    <div class="aiovg-meta">
        <?php   
        // Author & Date
        $user_meta = array();
        
        if ( $attributes['show_date'] ) {
            $user_meta[] = sprintf( esc_html__( 'on %s', 'all-in-one-video-gallery' ), get_the_date() );
        }
                
        if ( $attributes['show_user'] ) {
			$author_url = aiovg_get_user_videos_page_url( $post->post_author );
            $user_meta[] = sprintf( '%s <a href="%s" class="aiovg-link-author">%s</a>', esc_html__( 'by', 'all-in-one-video-gallery' ), esc_url( $author_url ), esc_html( get_the_author() ) );				
        }
        
        if ( count( $user_meta ) ) {
            printf( '<div class="aiovg-user aiovg-text-small aiovg-text-muted">%s</div>', esc_html__( 'Posted', 'all-in-one-video-gallery' ) . ' ' . implode( ' ', $user_meta ) );
        }
        
        // Category(s)
        if ( $attributes['show_category'] && ! empty( $attributes['categories'] ) ) {
            $term_meta = array();
            foreach ( $attributes['categories'] as $category ) {
				$category_url = aiovg_get_category_page_url( $category );
                $term_meta[] = sprintf( '<a class="aiovg-link-category" href="%s">%s</a>', esc_url( $category_url ), esc_html( $category->name ) );
            }
            printf( '<div class="aiovg-category aiovg-text-small"><svg class="aiovg-svg-icon aiovg-svg-icon-categories" width="16" height="16" viewBox="0 0 32 32"><path d="M26 30l6-16h-26l-6 16zM4 12l-4 18v-26h9l4 4h13v4z"></path></svg> %s</div>', implode( ', ', $term_meta ) );
        }

        // Tag(s)
        if ( $attributes['show_tag'] && ! empty( $attributes['tags'] ) ) {
            $term_meta = array();
            foreach ( $attributes['tags'] as $tag ) {
				$tag_url = aiovg_get_tag_page_url( $tag );
                $term_meta[] = sprintf( '<a class="aiovg-link-tag" href="%s">%s</a>', esc_url( $tag_url ), esc_html( $tag->name ) );
            }
            printf( '<div class="aiovg-tag aiovg-text-small"><svg class="aiovg-svg-icon aiovg-svg-icon-tags" width="16" height="16" viewBox="0 0 40 32"><path d="M38.5 0h-12c-0.825 0-1.977 0.477-2.561 1.061l-14.879 14.879c-0.583 0.583-0.583 1.538 0 2.121l12.879 12.879c0.583 0.583 1.538 0.583 2.121 0l14.879-14.879c0.583-0.583 1.061-1.736 1.061-2.561v-12c0-0.825-0.675-1.5-1.5-1.5zM31 12c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"></path><path d="M4 17l17-17h-2.5c-0.825 0-1.977 0.477-2.561 1.061l-14.879 14.879c-0.583 0.583-0.583 1.538 0 2.121l12.879 12.879c0.583 0.583 1.538 0.583 2.121 0l0.939-0.939-13-13z"></path></svg> %s</div>', implode( ', ', $term_meta ) );
        }
        ?>  
        
        <!-- Views count -->
        <?php if ( $attributes['show_views'] ) : ?>
            <div class="aiovg-views aiovg-text-small aiovg-text-muted">
                <svg class="aiovg-svg-icon aiovg-svg-icon-views" width="16" height="16" viewBox="0 0 32 32">
                    <path d="M16 6c-6.979 0-13.028 4.064-16 10 2.972 5.936 9.021 10 16 10s13.027-4.064 16-10c-2.972-5.936-9.021-10-16-10zM23.889 11.303c1.88 1.199 3.473 2.805 4.67 4.697-1.197 1.891-2.79 3.498-4.67 4.697-2.362 1.507-5.090 2.303-7.889 2.303s-5.527-0.796-7.889-2.303c-1.88-1.199-3.473-2.805-4.67-4.697 1.197-1.891 2.79-3.498 4.67-4.697 0.122-0.078 0.246-0.154 0.371-0.228-0.311 0.854-0.482 1.776-0.482 2.737 0 4.418 3.582 8 8 8s8-3.582 8-8c0-0.962-0.17-1.883-0.482-2.737 0.124 0.074 0.248 0.15 0.371 0.228v0zM16 13c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z"></path>
                </svg>
                <?php
                $views_count = get_post_meta( get_the_ID(), 'views', true );
                printf( esc_html__( '%d views', 'all-in-one-video-gallery' ), $views_count );
                ?>
            </div>
        <?php endif; ?>        
    </div>    
    
    <!-- Socialshare buttons -->
    <?php the_aiovg_socialshare_buttons(); ?>
</div>

<?php
// Related videos
if ( $attributes['related'] ) {
	$atts = array();
	
	$atts[] = 'title="' . esc_html__( 'You may also like', 'all-in-one-video-gallery' ) . '"';
	
	if ( ! empty( $attributes['categories'] ) ) {
		$ids = array();
		foreach ( $attributes['categories'] as $category ) {
			$ids[] = $category->term_id;
		}
		$atts[] = 'category="' . implode( ',', $ids ) . '"';
    }
    
    if ( ! empty( $attributes['tags'] ) ) {
		$ids = array();
		foreach ( $attributes['tags'] as $tag ) {
			$ids[] = $tag->term_id;
		}
		$atts[] = 'tag="' . implode( ',', $ids ) . '"';
	}
    
    $atts[] = 'related="1"';
    $atts[] = 'exclude="' . (int) $attributes['id'] . '"';
    $atts[] = 'show_count="0"';
    $atts[] = 'columns="' . (int) $attributes['columns'] . '"';
    $atts[] = 'limit="' . (int) $attributes['limit'] . '"';
    $atts[] = 'orderby="' . sanitize_text_field( $attributes['orderby'] ) . '"';
    $atts[] = 'order="' . sanitize_text_field( $attributes['order'] ) . '"';
    $atts[] = 'show_pagination="' . (int) $attributes['show_pagination'] . '"';

	$related_videos = do_shortcode( '[aiovg_videos ' . implode( ' ', $atts ) . ']' );
		
	if ( $related_videos != aiovg_get_message( 'videos_empty' ) ) {
		echo $related_videos;
	} 
}