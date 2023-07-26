<?php

/**
 * Video
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
 * AIOVG_Public_Video class.
 *
 * @since 1.0.0
 */
class AIOVG_Public_Video {
	
	/**
	 * Get things started.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Register shortcode(s)
		add_shortcode( "aiovg_video", array( $this, "run_shortcode_video" ) );
	}
	
	/**
	 * Always keep using our custom template for AIOVG player page.
	 *
	 * @since  1.0.0
	 * @param  string $template The path of the template to include.
	 * @return string $template Filtered template path.
	 */
	public function template_include( $template ) {	
		$page_settings = get_option( 'aiovg_page_settings' );

		$player_page_id = apply_filters( 'wpml_object_id', (int) $page_settings['player'], 'page' );		
		if ( ! empty( $player_page_id ) && is_page( $player_page_id ) ) {
			$template = apply_filters( 'aiovg_load_template', AIOVG_PLUGIN_DIR . 'public/templates/player.php' );
		}
		
		return $template;		
	}	
	
	/**
	 * Add support for HLS & MPEG-DASH.
	 *
	 * @since  3.0.0
	 * @param  array $mimes Array of allowed mime types.
	 * @return array        Filtered mime types array.
	 */
	public function add_mime_types( $mimes ) {			
		$mimes['m3u8'] = 'application/x-mpegurl';
		$mimes['mpd']  = 'application/dash+xml';

		return $mimes;		
	}
	
	/**
	 * Run the shortcode [aiovg_video].
	 *
	 * @since  1.0.0
	 * @param  array  $atts    An associative array of attributes.
	 * @param  string $content Enclosing content.
	 * @return string          Shortcode output.
	 */
	public function run_shortcode_video( $atts = array(), $content = null ) {		
		// Vars
		if ( ! $atts ) {
			$atts = array();
		}
		
		$post_id = 0;
		
		if ( ! empty( $atts['id'] ) ) {
			$post_id = (int) $atts['id'];
		} else {			
			$supported_formats = array( 'mp4', 'webm', 'ogv', 'hls', 'dash', 'youtube', 'vimeo', 'dailymotion', 'rumble', 'facebook' );
			$is_video_available = 0;
			
			foreach ( $supported_formats as $format ) {			
				if ( array_key_exists( $format, $atts ) ) {
					$is_video_available = 1;
				}				
			}
			
			if ( 0 == $is_video_available ) {			
				$args = array(				
					'post_type' => 'aiovg_videos',			
					'post_status' => 'publish',
					'posts_per_page' => 1,
					'fields' => 'ids',
					'no_found_rows' => true,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false
				);
		
				$aiovg_query = new WP_Query( $args );
				
				if ( $aiovg_query->have_posts() ) {
					$posts = $aiovg_query->posts;
					$post_id = (int) $posts[0];

					$atts['id'] = $post_id;
				}			
			}			
		}

		// Enqueue dependencies
		wp_enqueue_style( AIOVG_PLUGIN_SLUG . '-public' );		
			
		// Return
		$pre = apply_filters( 'aiovg_pre_shortcode_aiovg_video', false, $atts, $content );		
		if ( ! empty( $pre ) ) {
			return $pre;
		}

		return aiovg_get_player_html( $post_id, $atts );		
	}

	/**
	 * Filter video sources.
	 *
	 * @since  2.6.5
	 * @param  array $sources Video sources.
	 * @param  array $params  Player params.
	 * @return array $sources Filtered video sources.
	 */
	public function player_sources( $sources, $params = array() ) {	
		$post_id = 0;
		$type    = '';

		if ( ! empty( $params ) ) {
			$post_id = (int) $params['post_id'];
		} else {
			$post_id = (int) get_query_var( 'aiovg_video' );
		}		

		if ( $post_id > 0 ) {
			$type = get_post_meta( $post_id, 'type', true );
		}

		if ( isset( $sources['mp4'] ) && ! isset( $sources['hls'] ) ) {
			$mp4_src = $sources['mp4']['src'];

			if ( strpos( $mp4_src, 'videos.files.wordpress.com' ) !== false ) {
				$hls_src = str_replace( '.mp4', '.master.m3u8', $mp4_src );
				$has_hls = 0;

				$query = parse_url( $mp4_src, PHP_URL_QUERY );
				parse_str( $query, $parsed_url );

				if ( isset( $parsed_url['isnew'] ) ) {
					$has_hls = (int) $parsed_url['isnew'];
				} else {					
					$hls_response = wp_remote_get( $hls_src );

					if ( 200 == wp_remote_retrieve_response_code( $hls_response ) ) {
						$has_hls = 1;
					}

					if ( $post_id > 0 && 'default' == $type ) {
						update_post_meta( $post_id, 'mp4', aiovg_sanitize_url( add_query_arg( 'isnew', $has_hls, $mp4_src ) ) );
					}
				}

				if ( $has_hls ) {
					$hls_source = array(
						'hls' => array(
							'type' => 'application/x-mpegurl',
							'src'  => $hls_src
						)
					);

					$sources = array_merge( $hls_source, $sources );
				}
			}
		}
		
		return $sources;	
	}	
	
	/**
	 * Filter the post content.
	 *
	 * @since  1.0.0
	 * @param  string $content Content of the current post.
	 * @return string $content Modified Content.
	 */
	public function the_content( $content ) {	
		if ( is_singular( 'aiovg_videos' ) && in_the_loop() && is_main_query() ) {		
			global $post, $wp_query;
			
			if ( $post->ID != $wp_query->get_queried_object_id() ) {
				return $content;
			}
			
			if ( post_password_required( $post->ID ) ) {
				return $content;
			}
			
			// Vars
			$video_settings = get_option( 'aiovg_video_settings' );
			$related_videos_settings = get_option( 'aiovg_related_videos_settings' );
			$categories_settings = get_option( 'aiovg_categories_settings' );
			
			$attributes = array(
				'id'              => $post->ID,				
				'show_category'   => isset( $video_settings['display']['category'] ),
				'show_tag'        => isset( $video_settings['display']['tag'] ),
				'show_date'       => isset( $video_settings['display']['date'] ),
				'show_user'       => isset( $video_settings['display']['user'] ),
				'show_views'      => isset( $video_settings['display']['views'] ),
				'related'         => isset( $video_settings['display']['related'] ),
				'columns'         => $related_videos_settings['columns'],
				'limit'           => $related_videos_settings['limit'],
				'orderby'         => $related_videos_settings['orderby'],
				'order'           => $related_videos_settings['order'],
				'show_pagination' => isset( $related_videos_settings['display']['pagination'] )
			);
			
			$attributes['categories'] = wp_get_object_terms( 
				get_the_ID(), 
				'aiovg_categories',
				array(
					'orderby' => sanitize_text_field( $categories_settings['orderby'] ),
					'order'   => sanitize_text_field( $categories_settings['order'] )
				) 
			);

			$attributes['tags'] = wp_get_object_terms( 
				get_the_ID(), 
				'aiovg_tags',
				array(
					'orderby' => sanitize_text_field( $categories_settings['orderby'] ),
					'order'   => sanitize_text_field( $categories_settings['order'] )
				) 
			);
			
			// Enqueue dependencies
			wp_enqueue_style( AIOVG_PLUGIN_SLUG . '-public' );
			
			// Process output
			ob_start();
			include apply_filters( 'aiovg_load_template', AIOVG_PLUGIN_DIR . 'public/templates/single-video.php' );
			$content = ob_get_clean();			
		}
		
		return $content;	
	}

	/**
     * Filters whether the current video post is open for comments.
     *
     * @since 2.5.6
     *
     * @param  bool $open    Whether the current post is open for comments.
     * @param  int  $post_id The post ID.
	 * @return bool $open    True if the comments are open, false if not.
     */
	public function comments_open( $open, $post_id ) {
		if ( $post_id > 0 ) {
			$post_type = get_post_type( $post_id );
				
			if ( 'aiovg_videos' == $post_type ) {
				$video_settings = get_option( 'aiovg_video_settings' );

				$has_comments = (int) $video_settings['has_comments'];

				if ( $has_comments == 2 ) { // Forcefully enable comments on all the video pages
					$open = true;
				}

				if ( $has_comments == -2 ) { // Forcefully disable comments on all the video pages
					$open = false;
				}
			}
		}

		return $open;
	}

	/**
	 * Update video views count.
	 *
	 * @since 1.0.0
	 */
	public function ajax_callback_update_views_count() {
		if ( isset( $_REQUEST['post_id'] ) ) {		
			$post_id = (int) $_REQUEST['post_id'];
						
			if ( $post_id > 0 ) {
				check_ajax_referer( 'aiovg_views_nonce', 'security' );
				aiovg_update_views_count( $post_id );
			}		
		}
		
		wp_send_json_success();	
	}

	/**
	 * Force download the video file.
	 *
	 * @since 2.5.8
	 */
	public function download_video() {
		if ( ! isset( $_GET['vdl'] ) ) {
			return;
		}	
		
		if ( is_numeric( $_GET['vdl'] ) ) {
			$file = get_post_meta( (int) $_GET['vdl'], 'mp4', true );
		} else {
			$file = get_transient( sanitize_text_field( $_GET['vdl'] ) );
		}

		if ( empty( $file ) ) {
			die( esc_html__( 'File is not readable or not found.', 'all-in-one-video-gallery' ) );
           	exit;
        }

		// Vars
		$is_remote_file = true;
        $formatted_path = 'url';        	
		$mime_type      = 'video/mp4'; 
		$file_size      = '';		

		// Removing spaces and replacing with %20 ascii code
        $file = preg_replace( '/\s+/', '%20', trim( $file ) );  
	  	$file = str_replace( '         ', '%20', $file );
	  	$file = str_replace( '        ', '%20', $file );
	  	$file = str_replace( '       ', '%20', $file );
	  	$file = str_replace( '      ', '%20', $file );
	  	$file = str_replace( '     ', '%20', $file );
	  	$file = str_replace( '    ', '%20', $file );
	  	$file = str_replace( '   ', '%20', $file );
	  	$file = str_replace( '  ', '%20', $file );
	  	$file = str_replace( ' ', '%20', $file );

		// Detect the file type	
		if ( strpos( $file, home_url() ) !== false ) {
			$is_remote_file = false;
		}		        		
          
        if ( preg_match( '#http://#', $file ) || preg_match( '#https://#', $file ) ) {
          	$formatted_path = 'url';
        } else {
          	$formatted_path = 'filepath';
        }
        
        if ( $formatted_path == 'url' ) {
          	$file_headers = @get_headers( $file );
  
          	if ( is_array( $file_headers ) && $file_headers[0] == 'HTTP/1.1 404 Not Found' ) {
           		die( esc_html__( 'File is not readable or not found.', 'all-in-one-video-gallery' ) );
           		exit;
          	}          
        } elseif ( $formatted_path == 'filepath' ) {		
          	if ( ! @is_readable( $file ) ) {
				die( esc_html__( 'File is not readable or not found.', 'all-in-one-video-gallery' ) );
               	exit;
          	}
        }
        
       	// Fetching File Size
       	if ( $is_remote_file || $formatted_path == 'url' ) {         
          	$data = @get_headers( $file, true );
          
          	if ( ! empty( $data['Content-Length'] ) ) {
          		$file_size = (int) $data[ 'Content-Length' ];          
          	} else {               
               	// If get_headers fails then try to fetch fileSize with curl
               	$ch = @curl_init();

               	if ( ! @curl_setopt( $ch, CURLOPT_URL, $file ) ) {
                 	@curl_close( $ch );
                 	@exit;
               	}
               
               	@curl_setopt( $ch, CURLOPT_NOBODY, true );
               	@curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
               	@curl_setopt( $ch, CURLOPT_HEADER, true );
               	@curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
               	@curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
               	@curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
               	@curl_exec( $ch );
               
               	if ( ! @curl_errno( $ch ) ) {
                	$http_status = (int) @curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                    if ( $http_status >= 200 && $http_status <= 300 )
                    	$file_size = (int) @curl_getinfo( $ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD );
               	}

               	@curl_close( $ch );               
          	}          
		} elseif ( $formatted_path == 'filepath' ) {		   
		    $file_size = (int) @filesize( $file );			   			   
       	}
          
		// Get the extension of the file
		$path = @parse_url( $file, PHP_URL_PATH ); 
		$ext  = @pathinfo( $path, PATHINFO_EXTENSION );

		switch ( $ext ) {          
			case 'mp3':
				$mime_type = "audio/mpeg";
				break;
			case 'wav':
				$mime_type = "audio/x-wav";
				break;
			case 'au':
				$mime_type = "audio/basic";
				break;
			case 'snd':
				$mime_type = "audio/basic";
				break;
			case 'm3u':
				$mime_type = "audio/x-mpegurl";
				break;
			case 'ra':
				$mime_type = "audio/x-pn-realaudio";
				break;
			case 'mp2':
				$mime_type = "video/mpeg";
				break;
			case 'mov':
				$mime_type = "video/quicktime";
				break;
			case 'qt':
				$mime_type = "video/quicktime";
				break;
			case 'mp4':
				$mime_type = "video/mp4";
				break;
			case 'webm':
				$mime_type = "video/webm";
				break;
			case 'ogv':
				$mime_type = "video/ogg";
				break;
			case 'm4v':
				$mime_type = "video/x-m4v";
				break;
			case 'm4a':
				$mime_type = "audio/mp4";
				break;
			case 'mp4a':
				$mime_type = "audio/mp4";
				break;
			case 'm4p':
				$mime_type = "audio/mp4";
				break;
			case 'm3a':
				$mime_type = "audio/mpeg";
				break;
			case 'm2a':
				$mime_type = "audio/mpeg";
				break;
			case 'mp2a':
				$mime_type = "audio/mpeg";
				break;
			case 'mp2':
				$mime_type = "audio/mpeg";
				break;
			case 'mpga':
				$mime_type = "audio/mpeg";
				break;
			case '3gp':
				$mime_type = "video/3gpp";
				break;
			case '3g2':
				$mime_type = "video/3gpp2";
				break;
			case 'mp4v':
				$mime_type = "video/mp4";
				break;
			case 'mpg4':
				$mime_type = "video/mp4";
				break;
			case 'm2v':
				$mime_type = "video/mpeg";
				break;
			case 'm1v':
				$mime_type = "video/mpeg";
				break;
			case 'mpe':
				$mime_type = "video/mpeg";
				break;
			case 'avi':
				$mime_type = "video/x-msvideo";
				break;
			case 'midi':
				$mime_type = "audio/midi";
				break;
			case 'mid':
				$mime_type = "audio/mid";
				break;
			case 'amr':
				$mime_type = "audio/amr";
				break;            
		
			default:
				$mime_type = "application/octet-stream";
		}

		if ( 'application/octet-stream' == $mime_type ) {
			die( esc_html__( 'Invalid file format.', 'all-in-one-video-gallery' ) );
			exit;
		}
        
        // Off output buffering to decrease Server usage
        @ob_end_clean();
        
        if ( ini_get( 'zlib.output_compression' ) ) {
        	ini_set( 'zlib.output_compression', 'Off' );
        }
        
        header( 'Content-Description: File Transfer' );
        header( 'Content-Type: '. $mime_type );
        header( 'Content-Disposition: attachment; filename=' . (string) @basename( $file ) );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Expires: Wed, 07 May 2013 09:09:09 GMT' );
	    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	    header( 'Cache-Control: post-check=0, pre-check=0', false );
	    header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	    header( 'Pragma: no-cache' );
        header( 'Content-Length: '. $file_size);        
        
        // Will Download 1 MB in chunkwise
        $chunk = 1 * ( 1024 * 1024 );

        if ( $nfile = @fopen( $file, 'rb' ) ) {
			while ( ! feof( $nfile ) ) {                 
				print( @fread( $nfile, $chunk ) );
				@ob_flush();
				@flush();
			}
			@fclose( $nfile );
		}		
	}
	
}