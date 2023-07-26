<?php

/**
 * Video Player.
 *
 * @link    https://plugins360.com
 * @since   2.4.0
 *
 * @package All_In_One_Video_Gallery
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * AIOVG_Player class.
 *
 * @since 2.4.0
 */
class AIOVG_Player {

	/**
	 * The only instance of the class.
	 *
	 * @since  2.4.0
	 * @static
	 * @var    AIOVG_Player	 
	 */
	public static $instance;

	/**
	 * Current player uid.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @var    int	 
	 */
	protected $uid;

	/**
	 * Create a new instance of the main class.
	 *
	 * @since  2.4.0
	 * @static
	 * @return AIOVG_Player
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
            self::$instance = new self();
        }

		return self::$instance;
	}

	/**
	 * Get things started.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		$this->uid = 0;
	}

	/**
	 * Get the player HTML.
	 *
	 * @since  2.4.0
	 * @param  int    $post_id Post ID.
 	 * @param  array  $atts    Player configuration data.
 	 * @return string $html    Player HTML.
	 */
	public function create( $post_id, $atts ) {
		$post_id = (int) $post_id;
		$params  = $this->get_params( $post_id, $atts );
		$html    = '';

		switch ( $params['player'] ) {
			case 'amp':
				$html = $this->get_player_amp( $params );
				break;
			case 'raw':
				wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );

				$json_array = array(
					'type'      => 'raw',
					'post_id'   => $post_type,
					'post_type' => 'aiovg_videos'
				);
 
				$html = sprintf(
					'<div class="aiovg-player-raw" data-params=\'%s\'>%s</div>',
					wp_json_encode( $json_array ),
					$params['player_html']				
				);
				break;
			case 'standard':
				$html = $this->get_player_standard( $params );				
				break;
			default:
				if ( ! empty( $params['embed_url'] ) ) {
					if ( ! empty( $params['show_consent'] ) ) {
						wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );
					}

					$html = sprintf( 
						'<div class="aiovg-player-container" style="max-width: %s;">', 
						( ! empty( $params['width'] ) ? (int) $params['width'] . 'px' : '100%' )
					);	
	
					$html .= sprintf( 
						'<div class="aiovg-player aiovg-player-%s" style="padding-bottom: %s%%;" data-src="%s">',
						esc_attr( $params['player'] ), 
						(float) $params['ratio'],
						esc_attr( $params['embed_url'] )
					);	

					$html .= sprintf( 
						'<iframe src="%s" title="%s" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>', 
						esc_attr( $params['embed_url'] ),
						esc_attr( $params['post_title'] ) 
					);	

					$html .= '</div>';
					$html .= '</div>';
				}				
		}
	
		return apply_filters( 'aiovg_player_html', $html, $params );
	}
	
	/**
	 * Get the standard video player.
	 * 
	 * @since  2.4.0
	 * @access private
 	 * @param  array   $params Player params.
 	 * @return string          Player HTML.
	 */
	private function get_player_standard( $params ) {
		$player_html = '';
		$json_array = array();		

		if ( ! empty( $params['embed_url'] ) ) {
			wp_enqueue_style( AIOVG_PLUGIN_SLUG . '-public' );
			wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );

			$json_array = array(
				'type'         => 'iframe',
				'post_id'      => (int) $params['post_id'],
				'post_type'    => sanitize_text_field( $params['post_type'] ),				
				'show_consent' => (int) $params['show_consent']
			);

			$player_html .= sprintf(
				'<div id="%s"></div>',
				esc_attr( $params['uid'] )				
			);
		} else {
			$settings = array(
				'type'           => 'html5',
				'post_id'        => (int) $params['post_id'],
				'post_type'      => sanitize_text_field( $params['post_type'] ),
				'show_consent'   => (int) $params['show_consent'],
				'player'         => array(
					'controlBar'                => array(),
					'playbackRates'             => array( 0.5, 0.75, 1, 1.5, 2 ),
					'suppressNotSupportedError' => true
				)
			);

			if ( ! empty( $params['autoplay'] ) ) {
				$settings['player']['autoplay'] = true;
			}

			// Sources			
			$types = array( 'mp4', 'webm', 'ogv', 'hls', 'dash', 'youtube', 'vimeo', 'dailymotion' );
			$sources = array();

			foreach ( $types as $type ) {
				if ( ! empty( $params[ $type ] ) ) {
					$mime_type = "video/{$type}";
					$label = '';

					switch ( $type ) {
						case 'mp4':
							$ext = aiovg_get_file_ext( $params[ $type ] );
							if ( ! in_array( $ext, array( 'webm', 'ogv' ) ) ) {
								$ext = 'mp4';
							}

							$mime_type = "video/{$ext}";
		
							if ( ! empty( $params['quality_level'] ) ) {
								$label = $params['quality_level'];
							}
							break;
						case 'hls':
							$mime_type = 'application/x-mpegurl';
							break;
						case 'dash':
							$mime_type = 'application/dash+xml';
							break;
					}

					$sources[ $type ] = array(
						'type' => $mime_type,
						'src'  => $params[ $type ]
					);

					if ( ! empty( $label ) ) {
						$sources[ $type ]['label'] = $label;
					}
				}
			}

			if ( isset( $params['sources'] ) ) {
				foreach ( $params['sources'] as $source ) {
					if ( ! empty( $source['quality'] ) && ! empty( $source['src'] ) ) {	
						$ext = aiovg_get_file_ext( $source['src'] );
						if ( ! in_array( $ext, array( 'webm', 'ogv' ) ) ) {
							$ext = 'mp4';
						}
	
						$label = $source['quality'];
	
						$sources[ $label ] = array(
							'type'  => "video/{$ext}",
							'src'   => $source['src'],
							'label' => $label
						);
					}
				}
			}

			$params['sources'] = apply_filters( 'aiovg_player_sources', $sources, $params );

			// Tracks
			$tracks = array();

			if ( ! empty( $params['tracks'] ) ) {
				$tracks = $params['tracks'];

				$has_srt_found = 0;

				foreach ( $tracks as $index => $track ) {
					$ext = pathinfo( $track['src'], PATHINFO_EXTENSION );
					if ( 'srt' == strtolower( $ext ) ) {
						$has_srt_found = 1;			
						break;
					}
				}

				if ( $has_srt_found ) {
					$settings['tracks'] = $tracks;
					$settings['cc_load_policy'] = (int) $params['cc_load_policy'];
					
					$tracks = array();
				}
			}

			$params['tracks'] = apply_filters( 'aiovg_player_tracks', $tracks, $params );

			// Attributes
			$attributes = array(
				'id'          => $params['uid'],
				'class'       => 'vjs-fill',
				'style'       => 'width: 100%; height: 100%;', 
				'controls'    => '',
				'playsinline' => ''
			);

			$attributes['preload'] = esc_attr( $params['preload'] );

			if ( ! empty( $params['muted'] ) ) {
				$attributes['muted'] = true;
			}

			if ( ! empty( $params['loop'] ) ) {
				$attributes['loop'] = true;
			}

			if ( ! empty( $params['poster'] ) ) {
				$attributes['poster'] = esc_attr( $params['poster'] );
			}

			if ( ! empty( $params['copyright_text'] ) ) {
				$attributes['controlsList']  = 'nodownload';
				$attributes['oncontextmenu'] = 'return false;';
			}

			$params['attributes'] = apply_filters( 'aiovg_player_attributes', $attributes, $params );

			// Settings
			if ( ! empty( $params['share'] ) ) {
				$settings['share'] = 1;
			}

			if ( ! empty( $params['embed'] ) ) {
				$settings['embed'] = 1;
			}		

			if ( ! empty( $params['download'] ) ) {
				$settings['download']     = 1;
				$settings['download_url'] = esc_url( $params['download_url'] );
			}

			if ( ! empty( $params['show_logo'] ) ) {
				$settings['show_logo'] = (int) $params['show_logo'];
				$settings['logo_image'] = aiovg_sanitize_url( $params['logo_image'] );
				$settings['logo_link'] = esc_url_raw( $params['logo_link'] );
				$settings['logo_position'] = sanitize_text_field( $params['logo_position'] );
				$settings['logo_margin'] = (int) $params['logo_margin'];
			}

			if ( ! empty( $params['copyright_text'] ) ) {
				$settings['copyright_text'] = apply_filters( 'aiovg_translate_strings', sanitize_text_field( $params['copyright_text'] ), 'copyright_text' );
			}			

			$controls = array( 
				'playpause'  => 'PlayToggle', 
				'current'    => 'CurrentTimeDisplay', 
				'progress'   => 'progressControl', 
				'duration'   => 'durationDisplay',
				'tracks'     => 'SubtitlesButton',
				'audio'      => 'AudioTrackButton',				
				'speed'      => 'PlaybackRateMenuButton', 
				'quality'    => 'qualitySelector', 
				'volume'     => 'VolumePanel', 
				'fullscreen' => 'fullscreenToggle'
			);
			
			foreach ( $controls as $index => $control ) {		
				if ( ! in_array( $index, $params['controls'] ) ) {	
					unset( $controls[ $index ] );	
				} else {
					if ( 'tracks' == $index ) {
						$params['controls'][] = 'audio';
					}
				}	
			}
			
			$settings['player']['controlBar']['children'] = array_values( $controls );
			if ( empty( $settings['player']['controlBar']['children'] ) ) {
				$params['attributes']['class'] = 'vjs-no-control-bar';
			}

			if ( isset( $params['sources']['youtube'] ) ) {
				$settings['player']['techOrder'] = array( 'youtube' );
				$settings['player']['youtube']   = array( 
					'iv_load_policy' => 3 
				);

				parse_str( $params['sources']['youtube']['src'], $queries );

				if ( isset( $queries['start'] ) ) {
					$settings['start'] = (int) $queries['start'];
				}

				if ( isset( $queries['t'] ) ) {
					$settings['start'] = (int) $queries['t'];
				}

				if ( isset( $queries['end'] ) ) {
					$settings['end'] = (int) $queries['end'];
				}
			}
			
			if ( isset( $params['sources']['vimeo'] ) ) {
				$settings['player']['techOrder'] = array( 'vimeo2' );

				if ( strpos( $params['sources']['vimeo']['src'], 'player.vimeo.com' ) !== false ) {
					$oembed = aiovg_get_vimeo_oembed_data( $params['sources']['vimeo']['src'] );
					$params['sources']['vimeo']['src'] = 'https://vimeo.com/' . $oembed['video_id'];
				}
			}
			
			if ( isset( $params['sources']['dailymotion'] ) ) {
				if ( empty( $params['poster'] ) ) {
					$settings['player']['bigPlayButton'] = false;
				}
				$settings['player']['techOrder'] = array( 'dailymotion' );
			}

			$params['settings'] = apply_filters( 'aiovg_player_settings', $settings, $params );
			$json_array = $params['settings'];

			// Dependencies
			wp_enqueue_style( 
				AIOVG_PLUGIN_SLUG . '-videojs', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/video-js.min.css', 
				array(), 
				'7.21.1', 
				'all' 
			);

			if ( in_array( 'qualitySelector', $params['settings']['player']['controlBar']['children'] ) ) {
				if ( isset( $params['sources']['mp4'] ) || isset( $params['sources']['webm'] ) || isset( $params['sources']['ogv'] ) ) {
					wp_enqueue_style( 
						AIOVG_PLUGIN_SLUG . '-quality-selector', 
						AIOVG_PLUGIN_URL . 'vendor/videojs-plugins/quality-selector/quality-selector.min.css', 
						array(), 
						'1.2.5', 
						'all' 
					);
				}

				if ( isset( $params['sources']['hls'] ) || isset( $params['sources']['dash'] ) ) {
					wp_enqueue_style( 
						AIOVG_PLUGIN_SLUG . '-videojs-quality-menu', 
						AIOVG_PLUGIN_URL . 'vendor/videojs-plugins/videojs-quality-menu/videojs-quality-menu.min.css', 
						array(), 
						'2.0.1',
						'all' 
					);
				}
			}

			if ( ! empty( $params['settings']['share'] ) || ! empty( $params['settings']['embed'] ) || ! empty( $params['settings']['download'] ) || ! empty( $params['settings']['show_logo'] ) ) {
				wp_enqueue_style( 
					AIOVG_PLUGIN_SLUG . '-overlay', 
					AIOVG_PLUGIN_URL . 'vendor/videojs-plugins/overlay/videojs-overlay.min.css', 
					array(), 
					'2.1.5', 
					'all' 
				);
			}

			wp_enqueue_style( AIOVG_PLUGIN_SLUG . '-public' );

			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-videojs', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/video.min.js', 
				array(), 
				'7.21.1', 
				false 
			);

			if ( in_array( 'qualitySelector', $params['settings']['player']['controlBar']['children'] ) ) {
				if ( isset( $params['sources']['mp4'] ) || isset( $params['sources']['webm'] ) || isset( $params['sources']['ogv'] ) ) {
					wp_enqueue_script( 
						AIOVG_PLUGIN_SLUG . '-quality-selector', 
						AIOVG_PLUGIN_URL . 'vendor/videojs-plugins/quality-selector/silvermine-videojs-quality-selector.min.js', 
						array(), 
						'1.2.5', 
						false 
					);
				}

				if ( isset( $params['sources']['hls'] ) || isset( $params['sources']['dash'] ) ) {
					wp_enqueue_script( 
						AIOVG_PLUGIN_SLUG . '-videojs-quality-menu', 
						AIOVG_PLUGIN_URL . 'vendor/videojs-plugins/videojs-quality-menu/videojs-quality-menu.min.js', 
						array(), 
						'2.0.1', 
						false 
					);	
				}
			}

			if ( isset( $params['sources']['youtube'] ) ) {
				wp_enqueue_script( 
					AIOVG_PLUGIN_SLUG . '-youtube', 
					AIOVG_PLUGIN_URL . 'vendor/videojs-plugins/youtube/Youtube.min.js', 
					array(), 
					'2.6.1',
					false 
				);
			}

			if ( isset( $params['settings']['start'] ) || isset( $params['settings']['end'] ) ) {
				wp_enqueue_script( 
					AIOVG_PLUGIN_SLUG . '-offset', 
					AIOVG_PLUGIN_URL . 'vendor/videojs-plugins/offset/videojs-offset.min.js', 
					array(), 
					'2.1.3',
					false 
				);
			}

			if ( isset( $params['sources']['vimeo'] ) ) {
				wp_enqueue_script( 
					AIOVG_PLUGIN_SLUG . '-vimeo', 
					AIOVG_PLUGIN_URL . 'public/assets/videojs-plugins/vimeo/videojs-vimeo2.min.js', 
					array(), 
					'2.0.0', 
					false 
				);
			}

			if ( isset( $params['sources']['dailymotion'] ) ) {
				wp_enqueue_script( 
					AIOVG_PLUGIN_SLUG . '-dailymotion', 
					AIOVG_PLUGIN_URL . 'public/assets/videojs-plugins/dailymotion/videojs-dailymotion.min.js', 
					array(), 
					'2.0.0', 
					false 
				);
			}

			if ( ! empty( $params['settings']['share'] ) || ! empty( $params['settings']['embed'] ) || ! empty( $params['settings']['download'] ) || ! empty( $params['settings']['show_logo'] ) ) {
				wp_enqueue_script( 
					AIOVG_PLUGIN_SLUG . '-overlay', 
					AIOVG_PLUGIN_URL . 'vendor/videojs-plugins/overlay/videojs-overlay.min.js', 
					array(), 
					'2.1.5', 
					false 
				);
			}
			
			do_action( 'aiovg_player_scripts', $params );
			
			wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );

			// Output
			$player_html .= sprintf( '<video-js %s>', aiovg_combine_video_attributes( $params['attributes'] ) );
			
			foreach ( $params['sources'] as $source ) { // Sources
				$player_html .= sprintf( 
					'<source type="%s" src="%s" label="%s"/>', 
					esc_attr( $source['type'] ), 
					esc_attr( $source['src'] ),
					( isset( $source['label'] ) ? esc_attr( $source['label'] ) : '' ) 
				);
			}		
			
			foreach ( $params['tracks'] as $index => $track ) { // Tracks
				$player_html .= sprintf( 
					'<track src="%s" kind="subtitles" srclang="%s" label="%s"%s>', 
					esc_attr( $track['src'] ), 
					esc_attr( $track['srclang'] ), 
					esc_attr( $track['label'] ),
					( 0 == $index && 1 == (int) $params['cc_load_policy'] ? ' default' : '' ) 
				);
			}

			$player_html .= '</video-js>';
		}

		// Share / Embed
		if ( ! empty( $params['share'] ) || ! empty( $params['embed'] ) ) {
			$player_html .= '<div class="vjs-share-embed" style="display: none;">';
			$player_html .= '<div class="vjs-share-embed-content">';

			// Share Buttons
			if ( isset( $params['share_buttons'] ) ) {
				$player_html .= '<div class="vjs-share-buttons">';
				foreach ( $params['share_buttons'] as $button ) {
					$player_html .= sprintf( 
						'<a href="%s" class="vjs-share-button vjs-share-button-%s" target="_blank"><span class="%s"></span></a>',							
						esc_attr( $button['url'] ), 
						esc_attr( $button['service'] ),
						esc_attr( $button['iconClass'] ) 
					);
				}
				$player_html .= '</div>';
			}

			// Embed Code
			if ( isset( $params['embed_code'] ) ) {
				$player_html .= '<div class="vjs-embed-code">';
				$player_html .= '<p>' . esc_html__( 'Paste this code in your HTML page', 'all-in-one-video-gallery' ) . '</p>';
				$player_html .= '<input type="text" class="vjs-copy-embed-code" value="' . htmlspecialchars( $params['embed_code'] ) . '" readonly />';
				$player_html .= '</div>';
			}

			$player_html .= '</div>';
			$player_html .= '</div>';
		}

		// GDPR
		if ( ! empty( $params['show_consent'] ) ) {
			$consent_message = apply_filters( 'aiovg_translate_strings', $params['consent_message'], 'consent_message' );
			$consent_button_label = apply_filters( 'aiovg_translate_strings', $params['consent_button_label'], 'consent_button_label' );

			$player_html .= sprintf(
				'<div class="aiovg-privacy-wrapper" %s><div class="aiovg-privacy-consent-block"><div class="aiovg-privacy-consent-message">%s</div><div class="aiovg-privacy-consent-button">%s</div></div></div>',
				( ! empty( $params['poster'] ) ? 'style="background-image: url(' . esc_attr( $params['poster'] ) . ');"' : '' ),
				wp_kses_post( trim( $consent_message ) ),
				esc_html( $consent_button_label )
			);
		}

		// Return
		$html = sprintf( 
			'<div class="aiovg-player-container" style="max-width: %s;">', 
			( ! empty( $params['width'] ) ? (int) $params['width'] . 'px' : '100%' )
		);

		$html .= sprintf( 
			'<div class="aiovg-player aiovg-player-%s vjs-waiting" style="padding-bottom: %s%%;" data-id="%s" data-src="%s" data-params=\'%s\'>',
			esc_attr( $params['player'] ), 
			(float) $params['ratio'],
			esc_attr( $params['uid'] ),
			( isset( $params['embed_url'] ) ? esc_attr( $params['embed_url'] ) : '' ),
			wp_json_encode( $json_array )
		);

		$html .= $player_html;

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the AMP player.
	 * 
	 * @since  2.4.0
	 * @access private
 	 * @param  array   $params  Player params.
 	 * @return string  $html    Player HTML.
	 */
	private function get_player_amp( $params ) {
		$html = '';

		$width  = ! empty( $params['width'] ) ? (int) $params['width'] : 640;
		$ratio  = ! empty( $params['ratio'] ) ? (float) $params['ratio'] : 56.25;
		$height = ( $width * $ratio ) / 100;

		$attributes = array(
			'width'  => $width,
			'height' => $height,
			'layout' => 'responsive'
		);

		// Embedcode
		if ( ! empty( $params['embed_url'] ) ) {
			$placeholder = '';
			if ( ! empty( $params['poster'] ) ) {
				$placeholder = sprintf(
					'<amp-img layout="fill" src="%s" placeholder></amp-img>',
					esc_attr( $params['poster'] )
				);
			}

			$attributes['src'] = esc_attr( $params['embed_url'] );

			$attributes['sandbox'] = 'allow-scripts allow-same-origin allow-popups';
			$attributes['allowfullscreen'] = '';
			$attributes['frameborder'] = '0';

			$html = sprintf(
				'<amp-iframe %s>%s</amp-iframe>',
				aiovg_combine_video_attributes( $attributes ),
				$placeholder
			);

			return $html;
		}

		// youtube, vimeo & dailymotion
		$services = array( 'youtube', 'vimeo', 'dailymotion' );
		
		foreach ( $services as $service ) {			
			if ( ! empty( $params[ $service ] ) ) {
				$src = esc_url_raw( $params[ $service ] );

				switch ( $service ) {
					case 'youtube':
						$attributes['data-videoid'] = aiovg_get_youtube_id_from_url( $src );

						$attributes['data-param-showinfo'] = 0;
						$attributes['data-param-rel'] = 0;
						$attributes['data-param-iv_load_policy'] = 3;

						if ( empty( $params['controls'] ) ) {
							$attributes['data-param-controls'] = 0;
						}

						if ( ! in_array( 'fullscreen', $params['controls'] ) ) {
							$attributes['data-param-fs'] = 0;
						}

						if ( ! empty( $params['autoplay'] ) ) {
							$attributes['autoplay'] = '';
						}

						if ( ! empty( $params['loop'] ) ) {
							$attributes['loop'] = '';
						}                
						break;
					case 'vimeo':
						$oembed = aiovg_get_vimeo_oembed_data( $src );
						$attributes['data-videoid'] = $oembed['video_id'];

						if ( ! empty( $params['autoplay'] ) ) {
							$attributes['autoplay'] = '';
						}
						break;
					case 'dailymotion':
						$attributes['data-videoid'] = aiovg_get_dailymotion_id_from_url( $src );

						if ( empty( $params['controls'] ) ) {
							$attributes['data-param-controls'] = 'false';
						}

						if ( ! empty( $params['autoplay'] ) ) {
							$attributes['autoplay'] = '';
						}

						if ( ! empty( $params['muted'] ) ) {
							$attributes['mute'] = 'true';
						}

						$attributes['data-endscreen-enable'] = 'false';
						$attributes['data-sharing-enable'] = 'false';
						$attributes['data-ui-logo'] = 'false';

						$attributes['data-param-queue-autoplay-next'] = 0;
						$attributes['data-param-queue-enable'] = 0;
						break;					
				}                

				$html = sprintf(
					'<amp-%1$s %2$s></amp-%1$s>',
					$service,
					aiovg_combine_video_attributes( $attributes )
				);

				break;
			}
		}

		if ( ! empty( $html ) ) {
			return $html;
		}

		// mp4, webm, ogv, hls & dash
		$types = array( 'mp4', 'webm', 'ogv', 'hls', 'dash' );            
		$sources = array();

		foreach ( $types as $type ) {
			if ( ! empty( $params[ $type ] ) ) {
				$mime_type = "video/{$type}";
				if ( 'hls' == $type ) $mime_type = 'application/x-mpegurl';
				if ( 'dash' == $type ) $mime_type = 'application/dash+xml';

				$src = esc_attr( $params[ $type ] );
				$src = str_replace( 'http://', '//', $src );

				$sources[] = sprintf(
					'<source type="%s" src="%s" />',
					$mime_type,
					$src
				);
			}               
		}			

		if ( count( $sources ) > 0 ) {
			if ( ! empty( $params['tracks'] ) ) { // tracks
				$tracks = array();
				
				foreach ( $params['tracks'] as $track ) {
					$src = str_replace( 'http://', '//', $track['src'] );

					$sources[] = sprintf( 
						'<track src="%s" kind="subtitles" srclang="%s" label="%s">', 
						esc_attr( $src ), 
						esc_attr( $track['srclang'] ), 
						esc_attr( $track['label'] ) 
					);
				}
			}

			if ( ! empty( $params['controls'] ) ) {
				$attributes['controls'] = '';
			}

			if ( ! empty( $params['autoplay'] ) ) {
				$attributes['autoplay'] = '';
			}

			if ( ! empty( $params['loop'] ) ) {
				$attributes['loop'] = '';
			}            

			if ( ! empty( $params['poster'] ) ) {
				$attributes['poster'] = esc_attr( $params['poster'] );
			}

			$html = sprintf(
				'<amp-video %s>%s</amp-video>',
				aiovg_combine_video_attributes( $attributes ),
				implode( '', $sources )
			);
		}        

		return $html;
	}

	/**
	 * Get the player params.
	 *
	 * @since  2.4.0
	 * @param  int   $post_id Post ID.
 	 * @param  array $atts    Player configuration data.
 	 * @return array $params  Player params.
	 */
	private function get_params( $post_id, $atts ) {
		$player_settings      = get_option( 'aiovg_player_settings' );		
		$brand_settings       = get_option( 'aiovg_brand_settings', array() );
		$socialshare_settings = get_option( 'aiovg_socialshare_settings' );
		$privacy_settings     = get_option( 'aiovg_privacy_settings' );		

		$defaults = array(
			'uid'                  => 'aiovg-player-' . ++$this->uid,
			'player'               => $player_settings['player'],
			'post_id'              => $post_id,			
			'post_type'            => 'page',	
			'post_title'           => '',		
			'width'                => $player_settings['width'],
			'ratio'                => $player_settings['ratio'],
			'preload'              => $player_settings['preload'],
			'autoplay'             => $player_settings['autoplay'],
			'loop'                 => $player_settings['loop'],
			'muted'                => $player_settings['muted'],
			'playpause'            => isset( $player_settings['controls']['playpause'] ),
			'current'              => isset( $player_settings['controls']['current'] ),
			'progress'             => isset( $player_settings['controls']['progress'] ),
			'duration'             => isset( $player_settings['controls']['duration'] ),
			'tracks'               => isset( $player_settings['controls']['tracks'] ),
			'speed'                => isset( $player_settings['controls']['speed'] ),
			'quality'              => isset( $player_settings['controls']['quality'] ),			
			'volume'               => isset( $player_settings['controls']['volume'] ),
			'fullscreen'           => isset( $player_settings['controls']['fullscreen'] ),
			'share'			       => isset( $player_settings['controls']['share'] ),
			'embed'			       => isset( $player_settings['controls']['embed'] ),
			'download'			   => 0,
			'cc_load_policy'       => $player_settings['cc_load_policy'],
			'show_logo'            => ! empty( $brand_settings['logo_image'] ) ? $brand_settings['show_logo'] : 0,
			'copyright_text'       => ! empty( $brand_settings['copyright_text'] ) ? $brand_settings['copyright_text'] : '',
			'show_consent'         => 0,
			'consent_message'      => $privacy_settings['consent_message'],
			'consent_button_label' => $privacy_settings['consent_button_label'],
			'mp4'                  => '',
			'webm'                 => '',
			'ogv'                  => '',
			'hls'                  => '',
			'dash'                 => '',
			'youtube'              => '',
			'vimeo'                => '',
			'dailymotion'          => '',
			'rumble'               => '',
			'facebook'             => '',
			'poster'               => ''			
		);

		if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
			$defaults['player'] = 'amp';
		}

		if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
			$defaults['player'] = 'amp';
		}

		$params = array_merge( $defaults, $atts );		

		$params['width'] = ! empty( $params['width'] ) ? $params['width'] : '';
		$params['ratio'] = ! empty( $params['ratio'] ) ? $params['ratio'] : 56.25;

		if ( $post_id > 0 ) {
			$params['post_type']  = get_post_type( $post_id );
			$params['post_title'] = get_the_title( $post_id );
		}		

		if ( 'iframe' == $params['player'] ) {
			$params['embed_url'] = aiovg_get_player_page_url( $post_id, $atts );
		} else {
			// Controls
			$controls = array( 'playpause', 'current', 'progress', 'duration', 'tracks', 'speed', 'quality', 'volume', 'fullscreen' );		
			$params['controls'] = array();

			foreach ( $controls as $control ) {
				if ( ! empty( $params[ $control ] ) ) {	
					$params['controls'][] = $control;
				}

				unset( $params[ $control ] );
			}

			// Sources
			$post_meta = array();
			$embed_url = '';

			if ( $post_id > 0 && 'aiovg_videos' == $params['post_type'] ) {
				$post_meta = get_post_meta( $post_id );
				$source_type = $post_meta['type'][0];
				
				switch ( $source_type ) {
					case 'default':
						$params['mp4'] = isset( $post_meta['mp4'] ) ? $post_meta['mp4'][0] : '';
						$params['webm'] = isset( $post_meta['webm'] ) ? $post_meta['webm'][0] : '';
						$params['ogv'] = isset( $post_meta['ogv'] ) ? $post_meta['ogv'][0] : '';
						
						if ( ! empty( $post_meta['quality_level'][0] ) ) {
							$params['quality_level'] = $post_meta['quality_level'][0];
						}

						if ( ! empty( $post_meta['sources'][0] ) ) {
							$params['sources'] = unserialize( $post_meta['sources'][0] );
						}
						break;
					case 'adaptive':
						$params['hls'] = isset( $post_meta['hls'] ) ? $post_meta['hls'][0] : '';
						$params['dash'] = isset( $post_meta['dash'] ) ? $post_meta['dash'][0] : '';
						break;
					case 'youtube':
					case 'vimeo':
					case 'dailymotion':
					case 'rumble':
					case 'facebook':
						$params[ $source_type ] = isset( $post_meta[ $source_type ] ) ? $post_meta[ $source_type ][0] : '';
						break;
					case 'embedcode':
						$embedcode = isset( $post_meta['embedcode'] ) ? $post_meta['embedcode'][0] : '';

						$document = new DOMDocument();
						@$document->loadHTML( $embedcode );

						$iframes = $document->getElementsByTagName( 'iframe' ); 
						
						if ( $iframes->length > 0 ) {
							if ( $iframes->item(0)->hasAttribute( 'src' ) ) {
								$embed_url = $iframes->item(0)->getAttribute( 'src' );
							}
						} else {
							$params['player'] = 'raw'; 
							$params['player_html'] = $embedcode;
						}
						break;
				}

				// Poster
				$image_data = aiovg_get_image( $post_id, 'large' );
				$params['poster'] = $image_data['src'];

				// Tracks
				if ( in_array( 'tracks', $params['controls'] ) && ! empty( $post_meta['track'] ) ) {
					$params['tracks'] = array();

					foreach ( $post_meta['track'] as $track ) {
						$track = unserialize( $track );
						$track['src'] = aiovg_resolve_url( $track['src'] );

						$params['tracks'][] = $track;
					}
				}
			}

			// Rumble
			if ( ! empty( $params['rumble'] ) ) {
				$oembed = aiovg_get_rumble_oembed_data( $params['rumble'] );
				$html = $oembed['html'];						

				$document = new DOMDocument();
				@$document->loadHTML( $html );

				$iframes = $document->getElementsByTagName( 'iframe' ); 
				
				if ( $iframes->length > 0 ) {
					if ( $iframes->item(0)->hasAttribute( 'src' ) ) {
						$embed_url = $iframes->item(0)->getAttribute( 'src' );

						$embed_url = add_query_arg( 'rel', 0, $embed_url );	
						
						if ( ! empty( $params['autoplay'] ) ) {
							$embed_url = add_query_arg( 'autoplay', 2, $embed_url );	
						}									
					}
				}
			}

			// Facebook
			if ( ! empty( $params['facebook'] ) ) {
				$embed_url = 'https://www.facebook.com/plugins/video.php?href=' . urlencode(  $params['facebook'] ) . '&width=560&height=315&show_text=false&appId';
		
				$embed_url = add_query_arg( 'autoplay', (int) $params['autoplay'], $embed_url );				
				$embed_url = add_query_arg( 'loop', (int) $params['loop'], $embed_url );			
				$embed_url = add_query_arg( 'muted', (int) $params['muted'], $embed_url );
			}

			// Embedcode
			if ( ! in_array( $params['player'], array( 'amp', 'raw' ) ) ) {
				$services = array( 'youtube', 'vimeo', 'dailymotion' );

				foreach ( $services as $service ) {
					if ( isset( $player_settings['use_native_controls'][ $service ] ) || apply_filters( 'aiovg_use_native_controls', false, $service ) ) {
						if ( ! empty( $params[ $service ] ) ) {  
							$embed_url = $params[ $service ];

							switch ( $service ) {
								case 'youtube':
									parse_str( $embed_url, $parsed_url );

									$embed_url = 'https://www.youtube.com/embed/' . aiovg_get_youtube_id_from_url( $embed_url ) . '?showinfo=0&rel=0&iv_load_policy=3';									
									
									if ( isset( $parsed_url['start'] ) ) {
										$embed_url = add_query_arg( 'start', $parsed_url['start'], $embed_url );
									}
									
									if ( isset( $parsed_url['end'] ) ) {
										$embed_url = add_query_arg( 'end', $parsed_url['end'], $embed_url );
									}
									break;
								case 'vimeo':
									$oembed = aiovg_get_vimeo_oembed_data( $embed_url );
									$embed_url = 'https://player.vimeo.com/video/' . $oembed['video_id'] . '?title=0&byline=0&portrait=0';

									if ( ! empty( $oembed['html'] ) ) {
										$document = new DOMDocument();
										@$document->loadHTML( $oembed['html'] );
										
										$iframes = $document->getElementsByTagName( 'iframe' ); 
								
										if ( $iframes->item(0)->hasAttribute( 'src' ) ) {
											$original_src = $iframes->item(0)->getAttribute( 'src' );

											$query = parse_url( $original_src, PHP_URL_QUERY );
											parse_str( $query, $parsed_url );
				
											if ( isset( $parsed_url['h'] ) ) {
												$embed_url = add_query_arg( 'h', $parsed_url['h'], $embed_url );
											}
				
											if ( isset( $parsed_url['app_id'] ) ) {
												$embed_url = add_query_arg( 'app_id', $parsed_url['app_id'], $embed_url );
											}
										}
									}
									break;				
								case 'dailymotion':
									$embed_url = 'https://www.dailymotion.com/embed/video/' . aiovg_get_dailymotion_id_from_url( $embed_url ) . '?queue-autoplay-next=0&queue-enable=0&sharing-enable=0&ui-logo=0&ui-start-screen-info=0';
									break;								
							}
					
							if ( empty( $params['controls'] ) ) {
								$embed_url = add_query_arg( 'controls', 0, $embed_url );
							} else {
								if ( ! in_array( 'fullscreen', $params['controls'] ) ) {
									$embed_url = add_query_arg( 'fs', 0, $embed_url );
								}
							}
					
							$embed_url = add_query_arg( 'autoplay', (int) $params['autoplay'], $embed_url );				
							$embed_url = add_query_arg( 'loop', (int) $params['loop'], $embed_url );			
							$embed_url = add_query_arg( 'muted', (int) $params['muted'], $embed_url );	
							break;
						}
					}
				}
			}

			if ( ! empty( $embed_url ) ) {
				$params['embed_url'] = $embed_url;
			}
			
			// Share
			if ( ! empty( $params['share'] ) ) {
				$share_url = get_permalink( $post_id );
			
				$share_title = $params['post_title'];
				$share_title = str_replace( ' ', '%20', $share_title );
				$share_title = str_replace( '|', '%7C', $share_title );
			
				$share_image = $params['poster'];
			
				$share_buttons = array();
					
				if ( isset( $socialshare_settings['services']['facebook'] ) ) {
					$share_buttons[] = array(
						'service'   => 'facebook',		
						'url'       => "https://www.facebook.com/sharer/sharer.php?u={$share_url}",
						'iconClass' => 'vjs-icon-facebook'				
					);
				}
			
				if ( isset( $socialshare_settings['services']['twitter'] ) ) {
					$share_buttons[] = array(
						'service'   => 'twitter',			
						'url'       => "https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}",
						'iconClass' => 'vjs-icon-twitter'
					);
				}		
			
				if ( isset( $socialshare_settings['services']['linkedin'] ) ) {
					$share_buttons[] = array(
						'service'   => 'linkedin',			
						'url'       => "https://www.linkedin.com/shareArticle?url={$share_url}&amp;title={$share_title}",
						'iconClass' => 'vjs-icon-linkedin'
					);
				}
			
				if ( isset( $socialshare_settings['services']['pinterest'] ) ) {
					$pinterest_url = "https://pinterest.com/pin/create/button/?url={$share_url}&amp;description={$share_title}";
			
					if ( ! empty( $share_image ) ) {
						$pinterest_url .= "&amp;media={$share_image}";
					}
			
					$share_buttons[] = array(
						'service'   => 'pinterest',			
						'url'       => $pinterest_url,
						'iconClass' => 'vjs-icon-pinterest'
					);
				}
			
				if ( isset( $socialshare_settings['services']['tumblr'] ) ) {
					$tumblr_url = "https://www.tumblr.com/share/link?url={$share_url}&amp;name={$share_title}";
			
					$share_description = aiovg_get_excerpt( $post_id, 160, '', false ); 
					if ( ! empty( $share_description ) ) {
						$share_description = str_replace( ' ', '%20', $share_description );
						$share_description = str_replace( '|', '%7C', $share_description );	
			
						$tumblr_url .= "&amp;description={$share_description}";
					}
			
					$share_buttons[] = array(	
						'service'   => 'tumblr',		
						'url'       => $tumblr_url,
						'iconClass' => 'vjs-icon-tumblr'
					);
				}
			
				if ( isset( $socialshare_settings['services']['whatsapp'] ) ) {
					if ( wp_is_mobile() ) {
						$whatsapp_url = "whatsapp://send?text={$share_title} " . rawurlencode( $share_url );
					} else {
						$whatsapp_url = "https://api.whatsapp.com/send?text={$share_title}&nbsp;{$share_url}";
					}
			
					$share_buttons[] = array(
						'service'   => 'whatsapp',				
						'url'       => $whatsapp_url,
						'iconClass' => 'aiovg-icon-whatsapp'
					);
				}
			
				$params['share_buttons'] = apply_filters( 'aiovg_player_socialshare_buttons', $share_buttons );
			}

			// Embed
			if ( ! empty( $params['embed'] ) ) {
				$params['embed_code'] = sprintf(
					'<div style="position:relative;padding-bottom:%s%%;height:0;overflow:hidden;"><iframe src="%s" title="%s" width="100%%" height="100%%" style="position:absolute;width:100%%;height:100%%;top:0px;left:0px;overflow:hidden" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>',
					(float) $params['ratio'],
					esc_url( aiovg_get_player_page_url( $post_id, $atts ) ),
					esc_attr( $params['post_title'] )
				);
			}

			// Download
			if ( ! empty( $params['mp4'] ) ) {
				$params['download'] = isset( $player_settings['controls']['download'] );
				$download_url = '';

				if ( ! empty( $post_meta ) ) {
					if ( isset( $post_meta['download'] ) && empty( $post_meta['download'][0] ) ) {
						$params['download'] = 0;
					}

					$download_url =  home_url( '?vdl=' . $post_id );
				}

				if ( isset( $atts['download'] ) ) {
					$params['download'] = (int) $atts['download'];
				}

				if ( ! empty( $params['download'] ) ) {
					if ( empty( $download_url ) ) {
						$download_url = home_url( '?vdl=' . aiovg_get_temporary_file_download_id( $params['mp4'] ) );
					}

					$params['download_url'] = $download_url;
				}
			}

			// Logo
			if ( ! empty( $params['show_logo'] ) ) {
				$params['logo_image'] = $brand_settings['logo_image'];
				$params['logo_link'] = $brand_settings['logo_link'];
				$params['logo_position'] = $brand_settings['logo_position'];
				$params['logo_margin'] = $brand_settings['logo_margin'];
			}			

			// Resolve relative file paths as absolute URLs
			$fields = array( 'mp4', 'webm', 'ogv', 'poster', 'logo_image' );

			foreach ( $fields as $field ) {
				if ( ! empty( $params[ $field ] ) ) {
					$params[ $field ] = aiovg_resolve_url( $params[ $field ] );
				}
			}
		}

		// GDPR			
		if ( ! isset( $_COOKIE['aiovg_gdpr_consent'] ) && ! empty( $privacy_settings['show_consent'] ) && ! empty( $privacy_settings['consent_message'] ) && ! empty( $privacy_settings['consent_button_label'] ) ) {
			if ( ! in_array( $params['player'], array( 'amp', 'raw' ) ) ) {
				$services = array( 'youtube', 'vimeo', 'dailymotion', 'embed_url' );

				foreach ( $services as $service ) {
					if ( ! empty( $params[ $service ] ) ) {
						$params['show_consent'] = 1;
						$params['autoplay'] = 0;
					}
				}
			}
		}

		return $params;
	}
		
}
