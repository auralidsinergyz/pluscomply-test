<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

/**
 * AJAX handler for 'ld_notifications_get_posts_list' action
 *
 * @return void
 */
function learndash_notifications_ajax_get_posts_list() {
    if ( ! wp_verify_nonce( $_POST['nonce'], 'ld_notifications_nonce' ) ) {
        wp_die();
    }

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die();
    }

    // By default WP_Query search all post title, content, and excerpt. 
	// This filter modify it to only search in post title.
    add_filter( 'posts_search', function( $search, $wp_query ) {
        if ( isset( $wp_query->query['ld_notifications_action'] ) && $wp_query->query['ld_notifications_action'] === 'ld_notifications_get_posts_list' ) {
            $search = preg_replace( '/(OR)\s.*?post_(excerpt|content)\sLIKE\s.*?\)/', '', $search );
        }

        return $search;
    }, 10, 2 );

	$posts      = array();
	$posts_args = array();
	
	foreach( $_POST as $key => $value ) {
		switch ( $key ) {
			case 'group_id':
			case 'course_id':
			case 'lesson_id':
			case 'topic_id':
			case 'quiz_id':
			case 'parent_id':
				if ( is_array( $value ) ) {
					array_walk_recursive( $value, function( &$v ) {
						$v = intval( $v );
					} );
				} else {
					$value = intval( $value );
					$value = array( $value );
				}
				
				// Use key name as variable name.
				$$key = $value;
				break;
			
			default:
				// Use key name as variable name.
				$$key = sanitize_text_field( $value );
				break;
		}
	}

	switch ( $post_type ) {
		case 'groups':
			$label = LearnDash_Custom_Label::get_label( 'group' );
			break;

		case 'sfwd-courses':
			$label = LearnDash_Custom_Label::get_label( 'course' );
			break;
		
		case 'sfwd-lessons':
			$label = LearnDash_Custom_Label::get_label( 'lesson' );
			break;
		
		case 'sfwd-topic':
			$label = LearnDash_Custom_Label::get_label( 'topic' );
			break;
		
		case 'sfwd-quiz':
			$label = LearnDash_Custom_Label::get_label( 'quiz' );
			
			if ( in_array( 'all', $parent_id, true ) && ( ! in_array( 'all', $lesson_id, true ) && ! empty( $lesson_id ) ) ) {
				$parent_id = $lesson_id;
			} elseif ( in_array( 'all', $parent_id, true ) && ( ! in_array( 'all', $course_id, true ) && ! empty( $course_id ) ) ) {
				$parent_id = $course_id;
			}
			break;
	}

	if ( ! empty( $post_type ) ) {
		if ( 
			is_array( $parent_id ) && in_array( 'all', $parent_id, true )
			|| in_array(
				$post_type,
				array(
					learndash_get_post_type_slug( 'course' ),
					learndash_get_post_type_slug( 'group' )
				),
				true 
			) 
		) {
			if (
				in_array(
					$post_type,
					array(
						learndash_get_post_type_slug( 'course' ),
						learndash_get_post_type_slug( 'group' )
					),
					true 
				)
				|| ( 
					$parent_type === 'course'
					&& in_array( 'all', $parent_id ) 
				)
			) {
				$posts_args = array(
					'post_type'               => $post_type,
					's'                       => $keyword ?? null,
					'posts_per_page'          => 10,
					'post_status'             => 'any',
					'orderby'                 => 'relevance',
					'order'                   => 'ASC',
					'suppress_filters'        => false,
					'ld_notifications_action' => 'ld_notifications_get_posts_list',
				);
			} elseif ( is_array( $course_id ) ) {
				$post_ids = array();
				foreach ( $course_id as $c_id ) {
					$post_ids[] = learndash_course_get_steps_by_type( $c_id, $post_type );
 				}
				
				$posts_args = array(
					'post_type'               => $post_type,
					's'                       => $keyword ?? null,
					'post__in'                => $post_ids,
					'posts_per_page'          => 10,
					'post_status'             => 'any',
					'orderby'                 => 'relevance',
					'order'                   => 'ASC',
					'suppress_filters'        => false,
					'ld_notifications_action' => 'ld_notifications_get_posts_list',
				);
			}
		} else {
			$post_ids = array();

			if ( is_array( $parent_id ) && is_array( $course_id ) ) {
				foreach ( $parent_id as $p_id ) {
					foreach ( $course_id as $c_id ) {
						if ( intval( $p_id ) === intval( $c_id ) ) {
							$post_ids = array_merge( learndash_course_get_steps_by_type( $c_id, $post_type ), $post_ids );
						} else {
							$post_ids = array_merge( learndash_course_get_children_of_step( $c_id, $p_id, $post_type ), $post_ids );
						}
					}
				}
			}

			if ( ! empty( $post_ids ) ) {
				$posts_args = array(
					'post_type'               => $post_type,
					's'                       => $keyword ?? null,
					'post__in'                => $post_ids,
					'posts_per_page'          => 10,
					'post_status'             => 'any',
					'orderby'                 => 'relevance',
					'order'                   => 'ASC',
					'suppress_filters'        => false,
					'ld_notifications_action' => 'ld_notifications_get_posts_list',
				);
			}
		}
	}

	if ( ! empty( $posts_args ) ) {
		$posts = get_posts( $posts_args );
	}

    $results = [
		[
			'id'   => 'all',
			'text' => sprintf( _x( 'Any %s', 'Post type label', 'learndash-notifications' ), $label ),
		]
	];

    foreach ( $posts as $post ) {
		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

        $results[] = array(
            'id'   => $post->ID,
            'text' => $post->post_title . '  (ID: ' . $post->ID . ')',
        );
    }

    echo wp_json_encode( $results );
    wp_die();
}

add_action( 'wp_ajax_ld_notifications_get_posts_list', 'learndash_notifications_ajax_get_posts_list' );
