<?php

/**
 * Video Thumbnail - Image positioned to the left side of the caption.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */

$images_settings = get_option( 'aiovg_images_settings' );

$post_meta = get_post_meta( $post->ID );

$image_size = ! empty( $images_settings['size'] ) ? $images_settings['size'] : 'large';
$image_data = aiovg_get_image( $post->ID, $image_size, 'post', true );
$image = $image_data['src'];
$image_alt = ! empty( $image_data['alt'] ) ? $image_data['alt'] : $post->post_title;
?>

<div class="aiovg-thumbnail aiovg-thumbnail-style-image-left" data-id="<?php echo esc_attr( $post->ID ); ?>">
    <div class="aiovg-row">
        <div class="aiovg-col aiovg-col-p-40">
            <a href="<?php the_permalink(); ?>" class="aiovg-responsive-container" style="padding-bottom: <?php echo esc_attr( $attributes['ratio'] ); ?>;">
                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" class="aiovg-responsive-element" />                        
                
                <?php if ( $attributes['show_duration'] && ! empty( $post_meta['duration'][0] ) ) : ?>
                    <div class="aiovg-duration">
                        <?php echo esc_html( $post_meta['duration'][0] ); ?>
                    </div>
                <?php endif; ?>
                
                <svg class="aiovg-svg-icon aiovg-svg-icon-play" width="32" height="32" viewBox="0 0 32 32">
                    <path d="M16 0c-8.837 0-16 7.163-16 16s7.163 16 16 16 16-7.163 16-16-7.163-16-16-16zM16 29c-7.18 0-13-5.82-13-13s5.82-13 13-13 13 5.82 13 13-5.82 13-13 13zM12 9l12 7-12 7z"></path>
                </svg>
            </a> 
        </div>   	
        
        <div class="aiovg-col aiovg-col-p-60">
            <div class="aiovg-caption">
                <?php if ( $attributes['show_title'] ) : ?>
                    <div class="aiovg-title">
                        <a href="<?php the_permalink(); ?>" class="aiovg-link-title"><?php echo esc_html( aiovg_truncate( get_the_title(), $attributes['title_length'] ) ); ?></a>
                    </div>
                <?php endif; ?>

                <?php
                $meta = array();					

                if ( $attributes['show_date'] ) {
                    $meta[] = sprintf( esc_html__( 'on %s', 'all-in-one-video-gallery' ), get_the_date() );
                }
                        
                if ( $attributes['show_user'] ) {
                    $author_url = aiovg_get_user_videos_page_url( $post->post_author );
                    $meta[] = sprintf( '%s <a href="%s" class="aiovg-link-author">%s</a>', esc_html__( 'by', 'all-in-one-video-gallery' ), esc_url( $author_url ), esc_html( get_the_author() ) );			
                }

                if ( count( $meta ) ) {
                    printf( '<div class="aiovg-user aiovg-text-small aiovg-text-muted">%s</div>', esc_html__( 'Posted', 'all-in-one-video-gallery' ) . ' ' . implode( ' ', $meta ) );
                }
                ?>
                    
                <?php if ( $attributes['show_excerpt'] ) : ?>
                    <div class="aiovg-excerpt">
                        <?php the_aiovg_excerpt( $attributes['excerpt_length'] ); ?>
                    </div>
                <?php endif; ?>
                
                <?php
                if ( $attributes['show_category'] ) {
                    $categories = wp_get_object_terms( 
                        get_the_ID(), 
                        'aiovg_categories',
                        array(
                            'orderby' => sanitize_text_field( $attributes['categories_orderby'] ),
                            'order'   => sanitize_text_field( $attributes['categories_order'] )
                        ) 
                    );
                    
                    if ( ! empty( $categories ) ) {
                        $meta = array();
                        foreach ( $categories as $category ) {
                            $category_url = aiovg_get_category_page_url( $category );
                            $meta[] = sprintf( '<a href="%s" class="aiovg-link-category">%s</a>', esc_url( $category_url ), esc_html( $category->name ) );
                        }
                        printf( '<div class="aiovg-category aiovg-text-small"><svg class="aiovg-svg-icon aiovg-svg-icon-categories" width="16" height="16" viewBox="0 0 32 32"><path d="M26 30l6-16h-26l-6 16zM4 12l-4 18v-26h9l4 4h13v4z"></path></svg> %s</div>', implode( ', ', $meta ) );
                    }
                }
                ?>

                <?php
                if ( $attributes['show_tag'] ) {
                    $tags = wp_get_object_terms( 
                        get_the_ID(), 
                        'aiovg_tags',
                        array(
                            'orderby' => sanitize_text_field( $attributes['categories_orderby'] ),
                            'order'   => sanitize_text_field( $attributes['categories_order'] )
                        ) 
                    );

                    if ( ! empty( $tags ) ) {
                        $meta = array();
                        foreach ( $tags as $tag ) {
                            $tag_url = aiovg_get_tag_page_url( $tag );
                            $meta[] = sprintf( '<a href="%s" class="aiovg-link-tag">%s</a>', esc_url( $tag_url ), esc_html( $tag->name ) );
                        }
                        printf( '<div class="aiovg-tag aiovg-text-small"><svg class="aiovg-svg-icon aiovg-svg-icon-tags" width="16" height="16" viewBox="0 0 40 32"><path d="M38.5 0h-12c-0.825 0-1.977 0.477-2.561 1.061l-14.879 14.879c-0.583 0.583-0.583 1.538 0 2.121l12.879 12.879c0.583 0.583 1.538 0.583 2.121 0l14.879-14.879c0.583-0.583 1.061-1.736 1.061-2.561v-12c0-0.825-0.675-1.5-1.5-1.5zM31 12c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"></path><path d="M4 17l17-17h-2.5c-0.825 0-1.977 0.477-2.561 1.061l-14.879 14.879c-0.583 0.583-0.583 1.538 0 2.121l12.879 12.879c0.583 0.583 1.538 0.583 2.121 0l0.939-0.939-13-13z"></path></svg> %s</div>', implode( ', ', $meta ) );
                    }
                }
                ?>
                
                <?php if ( $attributes['show_views'] ) : ?>
                    <div class="aiovg-views aiovg-text-small aiovg-text-muted">
                        <svg class="aiovg-svg-icon aiovg-svg-icon-views" width="16" height="16" viewBox="0 0 32 32">
                            <path d="M16 6c-6.979 0-13.028 4.064-16 10 2.972 5.936 9.021 10 16 10s13.027-4.064 16-10c-2.972-5.936-9.021-10-16-10zM23.889 11.303c1.88 1.199 3.473 2.805 4.67 4.697-1.197 1.891-2.79 3.498-4.67 4.697-2.362 1.507-5.090 2.303-7.889 2.303s-5.527-0.796-7.889-2.303c-1.88-1.199-3.473-2.805-4.67-4.697 1.197-1.891 2.79-3.498 4.67-4.697 0.122-0.078 0.246-0.154 0.371-0.228-0.311 0.854-0.482 1.776-0.482 2.737 0 4.418 3.582 8 8 8s8-3.582 8-8c0-0.962-0.17-1.883-0.482-2.737 0.124 0.074 0.248 0.15 0.371 0.228v0zM16 13c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z"></path>
                        </svg> 
                        <?php printf( esc_html__( '%d views', 'all-in-one-video-gallery' ), $post_meta['views'][0] ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>    
    </div>
</div>