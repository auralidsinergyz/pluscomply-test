<?php

/**
 * Videos
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * AIOVG_Public_Videos class.
 *
 * @since 1.0.0
 */
class AIOVG_Public_Videos {

	/**
	 * The detault shortcode attribute values.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array     $defaults An associative array of defaults.
	 */
	protected $defaults = array();
	
	/**
	 * Get things started.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Register shortcode(s)
		add_shortcode( "aiovg_videos", array( $this, "run_shortcode_videos" ) );
		add_shortcode( "aiovg_category", array( $this, "run_shortcode_category" ) );
		add_shortcode( "aiovg_tag", array( $this, "run_shortcode_tag" ) );
		add_shortcode( "aiovg_search", array( $this, "run_shortcode_search" ) );
		add_shortcode( "aiovg_user_videos", array( $this, "run_shortcode_user_videos" ) );
	}

	/**
	 * Run the shortcode [aiovg_videos].
	 *
	 * @since 1.0.0
	 * @param array $atts An associative array of attributes.
	 */
	public function run_shortcode_videos( $atts ) {		
		$attributes = shortcode_atts( $this->get_defaults(), $atts );
		$attributes['shortcode'] = 'aiovg_videos';
				
		if ( ! empty( $attributes['related'] ) ) {
			// Category page
			if ( $term_slug = get_query_var( 'aiovg_category' ) ) {         
				$term = get_term_by( 'slug', sanitize_text_field( $term_slug ), 'aiovg_categories' );
				$attributes['category'] = $term->term_id;
			}

			// Tag page
			if ( $term_slug = get_query_var( 'aiovg_tag' ) ) {         
				$term = get_term_by( 'slug', sanitize_text_field( $term_slug ), 'aiovg_tags' );
				$attributes['tag'] = $term->term_id;
			}
		}
		
		$content = $this->get_content( $attributes );
		
		if ( empty( $content ) ) {
			$content = aiovg_get_message( 'videos_empty' );
		}
		
		return $content;		
	}
	
	/**
	 * Run the shortcode [aiovg_category].
	 *
	 * @since 1.0.0
	 * @param array $atts An associative array of attributes.
	 */
	public function run_shortcode_category( $atts ) {	
		$term_slug = get_query_var( 'aiovg_category' );
		
		if ( ! empty( $term_slug ) ) {
			$term = get_term_by( 'slug', sanitize_title( $term_slug ), 'aiovg_categories' );
		} elseif ( ! empty( $atts['id'] ) ) {			
			$term = get_term_by( 'id', (int) $atts['id'], 'aiovg_categories' );
		}
		
		if ( isset( $term ) && ! empty( $term ) ) {
			$categories_settings = get_option( 'aiovg_categories_settings' );

			$content = '';
			
			if ( ! empty( $categories_settings['back_button'] ) ) {
				$page_settings = get_option( 'aiovg_page_settings' );

				$back_button_url  = get_permalink( $page_settings['category'] );
				$back_button_text = __( 'All Categories', 'all-in-one-video-gallery' );

				if ( $term->parent > 0 ) {
					$parent_term = get_term_by( 'id', $term->parent, 'aiovg_categories' );

					$back_button_url  = aiovg_get_category_page_url( $parent_term );
					$back_button_text = $parent_term->name;
				}

				$back_button_url = apply_filters( 'aiovg_back_to_categories_link', $back_button_url );

				if ( ! empty( $back_button_url ) ) {
					$content .= sprintf( 
						'<p class="aiovg aiovg-categories-nav"><a href="%s">&larr; %s</a></p>',
						esc_url( $back_button_url ),
						$back_button_text
					);						
				}
			}

			if ( ! empty( $term->description ) ) {
				$content .= sprintf( '<div class="aiovg-category-description">%s</div>', wp_kses_post( nl2br( $term->description ) ) );
			}

			// Videos
			$attributes = shortcode_atts( $this->get_defaults(), $atts );
			$attributes['shortcode'] = 'aiovg_category';
			$attributes['category'] = $term->term_id;

			$videos = $this->get_content( $attributes );
			
			// Sub Categories
			$_attributes = array();
			$_attributes[] = 'id="' . $term->term_id . '"';
			$_attributes[] = 'limit="0"';

			if ( ! empty( $content ) ) {
				$_attributes[] = 'title="' . __( 'Sub Categories', 'all-in-one-video-gallery' ) . '"';
			}
			
			$sub_categories = do_shortcode( '[aiovg_categories ' . implode( ' ', $_attributes ) . ']' );				
			if ( $sub_categories == aiovg_get_message( 'categories_empty' ) ) {
				$sub_categories = '';
			}
			
			// ...
			if ( empty( $videos ) && empty( $sub_categories ) ) {
				$content .= aiovg_get_message( 'videos_empty' );
			} else {
				$content .= $videos;
				$content .= $sub_categories;
			}

			return $content;
		}
		
		return do_shortcode( '[aiovg_categories]' );	
	}

	/**
	 * Run the shortcode [aiovg_tag].
	 *
	 * @since 2.4.3
	 * @param array $atts An associative array of attributes.
	 */
	public function run_shortcode_tag( $atts ) {	
		$term_slug = get_query_var( 'aiovg_tag' );
		
		if ( ! empty( $term_slug ) ) {
			$term = get_term_by( 'slug', sanitize_title( $term_slug ), 'aiovg_tags' );
		} elseif ( ! empty( $atts['id'] ) ) {			
			$term = get_term_by( 'id', (int) $atts['id'], 'aiovg_tags' );
		}
		
		if ( isset( $term ) && ! empty( $term ) ) {
			$page_settings = get_option( 'aiovg_page_settings' );
			$content = '';
	
			if ( ! empty( $term->description ) ) {
				$content .= sprintf( '<p class="aiovg-tag-description">%s</p>', wp_kses_post( nl2br( $term->description ) ) );
			}

			// Videos
			$attributes = shortcode_atts( $this->get_defaults(), $atts );
			$attributes['shortcode'] = 'aiovg_tag';
			$attributes['tag'] = $term->term_id;

			$content .= $this->get_content( $attributes );

			if ( empty( $content ) ) {
				$content .= aiovg_get_message( 'videos_empty' );
			}
	
			return $content;
		} else {
			$args = array( 
				'taxonomy' => 'aiovg_tags', 
				'format'   => 'array',
				'echo'     => false 
			);

			$args = apply_filters( 'aiovg_tags_args', $args, $atts );
			$tags = wp_tag_cloud( $args );

			if ( ! empty( $tags ) ) {
				return '<div class="aiovg aiovg-tags-list">' . implode( "\n", $tags ) . '</div>';
			}
		}
		
		return aiovg_get_message( 'tags_empty' );
	}
	
	/**
	 * Run the shortcode [aiovg_search].
	 *
	 * @since 1.0.0
	 * @param array $atts An associative array of attributes.
	 */
	public function run_shortcode_search( $atts ) {	
		$attributes = shortcode_atts( $this->get_defaults(), $atts );
		$attributes['shortcode'] = 'aiovg_search';
		
		if ( isset( $_GET['vi'] ) ) {
			$attributes['search_query'] = $_GET['vi'];
		}
		
		if ( isset( $_GET['ca'] ) ) {
			$attributes['category'] = $_GET['ca'];
		}

		if ( ! isset( $_GET['ca'] ) || ( isset( $_GET['ca'] ) && empty( $_GET['ca'] ) ) ) {
			$categories_excluded = get_terms( array(
				'taxonomy'   => 'aiovg_categories',
				'hide_empty' => false,
				'fields'     => 'ids',
				'meta_key'   => 'exclude_search_form',
				'meta_value' => 1
			) );

			if ( ! empty( $categories_excluded ) && ! is_wp_error( $categories_excluded ) ) {
				$attributes['category_exclude'] = $categories_excluded;
			}
		}

		if ( isset( $_GET['ta'] ) ) {
			$attributes['tag'] = $_GET['ta'];
		}
		
		$content = $this->get_content( $attributes );
		
		if ( empty( $content ) ) {
			$content = aiovg_get_message( 'videos_empty' );
		}
		
		return $content;		
	}
	
	/**
	 * Run the shortcode [aiovg_user_videos].
	 *
	 * @since 1.0.0
	 * @param array $atts An associative array of attributes.
	 */
	public function run_shortcode_user_videos( $atts ) {	
		$user_slug = get_query_var( 'aiovg_user' );
		$content   = '';		
		
		if ( empty( $user_slug ) ) {
			if ( ! empty( $atts['id'] ) ) {
				$user_slug = get_the_author_meta( 'user_nicename', (int) $atts['id'] );	
			} elseif ( is_user_logged_in() ) {
				$user_slug = get_the_author_meta( 'user_nicename', get_current_user_id() );				
			}
		}
		
		if ( ! empty( $user_slug ) ) {		
			$attributes = shortcode_atts( $this->get_defaults(), $atts );
			$attributes['shortcode'] = 'aiovg_user_videos';
			$attributes['user_slug'] = $user_slug;

			$content = $this->get_content( $attributes );		
		} else {
			$content = do_shortcode( '[aiovg_videos]' );	
		}
		
		if ( empty( $content ) ) {
			$content = aiovg_get_message( 'videos_empty' );
		}
		
		return $content;	
	}

	/**
	 * Load more videos.
	 *
	 * @since 2.5.1
	 */
	public function ajax_callback_load_more_videos() {
		// Security check
		check_ajax_referer( 'aiovg_ajax_nonce', 'security' );

		// Proceed safe
		$attributes = array();

		foreach ( $_POST as $key => $value ) {
			if ( $value == 'false' ) {
				$attributes[ $key ] = 0;
			} elseif ( $value == 'true' ) {
				$attributes[ $key ] = 1;
			} else {
				$attributes[ $key ] = is_array( $value ) ? array_map( 'intval', $value ) : sanitize_text_field( $value );
			}
		}

		$json = array();
		$json['html'] = $this->get_content( $attributes );

		wp_send_json_success( $json );
	}
	
	/**
	 * Get the html output.
	 *
	 * @since  1.0.0
	 * @param  array  $atts    An associative array of attributes.
	 * @return string $content HTML output.
	 */
	public function get_content( $attributes ) {		
		$attributes['ratio'] = ! empty( $attributes['ratio'] ) ? (float) $attributes['ratio'] . '%' : '56.25%';
		
		// Enqueue style dependencies
		wp_enqueue_style( AIOVG_PLUGIN_SLUG . '-public' );
		
		// Define the query
		$args = array(				
			'post_type'      => 'aiovg_videos',
			'posts_per_page' => ! empty( $attributes['limit'] ) ? (int) $attributes['limit'] : -1,
			'post_status'    => 'publish'
		);

		if ( ! empty( $attributes['show_pagination'] ) || ! empty( $attributes['show_more'] ) ) { // Pagination
			$args['paged'] = (int) $attributes['paged'];
		}
		
		if ( ! empty( $attributes['search_query'] ) ) { // Search
			$args['s'] = sanitize_text_field( $attributes['search_query'] );
		}		

		$tax_queries = array();

		if ( ! empty( $attributes['category'] ) ) { // Category
			$tax_queries[] = array(
				'taxonomy'         => 'aiovg_categories',
				'field'            => 'term_id',
				'terms'            => is_array( $attributes['category'] ) ? array_map( 'intval', $attributes['category'] ) : array_map( 'intval', explode( ',', $attributes['category'] ) ),
				'include_children' => false
			);
		}

		if ( ! empty( $attributes['category_exclude'] ) ) { // Exclude categories
			$tax_queries[] = array(
				'taxonomy'         => 'aiovg_categories',
				'field'            => 'term_id',
				'terms'            => is_array( $attributes['category_exclude'] ) ? array_map( 'intval', $attributes['category_exclude'] ) : array_map( 'intval', explode( ',', $attributes['category_exclude'] ) ),
				'include_children' => false,
				'operator'         => 'NOT IN'
			);
		}

		if ( ! empty( $attributes['tag'] ) ) { // Tag
			$tax_queries[] = array(
				'taxonomy'         => 'aiovg_tags',
				'field'            => 'term_id',
				'terms'            => is_array( $attributes['tag'] ) ? array_map( 'intval', $attributes['tag'] ) : array_map( 'intval', explode( ',', $attributes['tag'] ) ),
				'include_children' => false
			);
		}		

		$count_tax_queries = count( $tax_queries );
		if ( $count_tax_queries ) {
			$tax_relation = 'AND';
			if ( ! empty( $attributes['related'] ) ) {		
				$tax_relation = 'OR';
			}

			$args['tax_query'] = ( $count_tax_queries > 1 ) ? array_merge( array( 'relation' => $tax_relation ), $tax_queries ) : $tax_queries;
		}
		
		if( ! empty( $attributes['featured'] ) ) { // Featured			
			$args['meta_query'] = array(
				array(
					'key'     => 'featured',
					'value'   => 1,
					'compare' => '='
				)
			);				
		}
		
		if ( ! empty( $attributes['user_slug'] ) ) { // User
			$args['author_name'] = sanitize_text_field( $attributes['user_slug'] );
		}		
		
		if ( ! empty( $attributes['include'] ) ) { // Include video IDs
			$args['post__in'] = array_map( 'intval', explode( ',', $attributes['include'] ) );
		}

		if ( ! empty( $attributes['exclude'] ) ) { // Exclude video IDs
			$args['post__not_in'] = array_map( 'intval', explode( ',', $attributes['exclude'] ) );
		}
		
		$orderby = sanitize_text_field( $attributes['orderby'] );
		$order   = sanitize_text_field( $attributes['order'] );
		
		switch ( $orderby ) {
			case 'views':
				$args['meta_key'] = $orderby;
				$args['orderby']  = 'meta_value_num';
			
				$args['order']    = $order;
				break;
			case 'rand':
				$seed = aiovg_get_orderby_rand_seed( (int) $attributes['paged'] );
				$args['orderby']  = 'RAND(' . $seed . ')';
				break;
			default:
				$args['orderby']  = $orderby;
				$args['order']    = $order;
		}
	
		$args = apply_filters( 'aiovg_query_args', $args, $attributes );
		$aiovg_query = new WP_Query( $args );
		
		// Start the loop
		global $post;
		
		// Process output
		$content = '';
		
		if ( $aiovg_query->have_posts() ) {		
			if ( ( is_front_page() && is_home() ) || empty( $attributes['show_pagination'] ) ) {
				$attributes['count'] = $aiovg_query->post_count;
			} else {
				$attributes['count'] = $aiovg_query->found_posts;
			}
			
			ob_start();
			include apply_filters( 'aiovg_load_template', AIOVG_PLUGIN_DIR . 'public/templates/videos-template-classic.php', $attributes );
			$content = ob_get_clean();			
		}
		
		return $content;	
	}
	
	/**
	 * Get the default shortcode attribute values.
	 *
	 * @since  1.0.0
	 * @return array $atts An associative array of attributes.
	 */
	public function get_defaults() {	
		if ( empty( $this->defaults ) ) {	
			$pagination_settings = get_option( 'aiovg_pagination_settings', array() ); 

			$fields = aiovg_get_shortcode_fields();

			foreach ( $fields['videos']['sections'] as $section ) {
				foreach ( $section['fields'] as $field ) {
					$this->defaults[ $field['name'] ] = $field['value'];
				}
			}

			foreach ( $fields['categories']['sections']['general']['fields'] as $field ) {
				if ( 'orderby' == $field['name'] || 'order' == $field['name'] ) {
					$this->defaults[ 'categories_' . $field['name'] ] = $field['value'];
				}
			}			
			
			$this->defaults['source'] = 'videos';
			$this->defaults['count'] = 0;
			$this->defaults['paged'] = aiovg_get_page_number();	
			$this->defaults['pagination_ajax'] = isset( $pagination_settings['ajax'] ) && ! empty( $pagination_settings['ajax'] ) ? 1 : 0;		
		}

		$this->defaults['uid'] = aiovg_get_uniqid();
		
		return $this->defaults;
	}
	
}
