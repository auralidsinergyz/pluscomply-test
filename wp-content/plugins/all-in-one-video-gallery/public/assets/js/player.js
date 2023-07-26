(function( $ ) {
	'use strict';	

	/**
	 * Initialize the Player.
	 *
	 * @since 3.0.0
	 */
	function init_aiovg_player( $elem ) {
		// Vars		
		var id = $elem.data( 'id' );		
		var config = $elem.data( 'params' );
		var embed_url = $elem.data( 'src' );	
		var player = null;	

		// GDPR consent
		var gdpr_consent = function() {		
			var data = {
				'action': 'aiovg_set_cookie',
				'security': aiovg_player.ajax_nonce
			};

			$.post( 
				aiovg_player.ajax_url, 
				data, 
				function( response ) {
					if ( response.success ) {
						init_player();
						$elem.find( '.aiovg-privacy-wrapper' ).remove();

						$( '.aiovg-player-standard' ).trigger( 'aiovg.cookieConsent', { id: id } );
					}
				}
			);
		}

		// Init player
		var init_player = function() {
			// Is iframe?
			if ( 'iframe' == config.type ) {
				$( '#' + id ).replaceWith( '<iframe src="' + embed_url + '" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>' );
				update_views_count( config );
			} else {
				config.player.html5 = {
					vhs: {
						overrideNative: ! videojs.browser.IS_ANY_SAFARI,
					}
				};

				player = videojs( id, config.player );				

				var overlays = [];
				
				// Trigger ready event
				var options = {					
					id: id,
					config: config,
					player: player					
				};

				$elem.trigger( 'player.init', options );

				// Fired when the player is ready
				player.ready(function() {
					$elem.removeClass( 'vjs-waiting' );
				});

				// On metadata loaded
				player.one( 'loadedmetadata', function() {
					// Standard quality selector
					$elem.find( '.vjs-quality-selector .vjs-menu-item' ).each(function( e ) {
						var $this = $( this );

						var text = $this.find( '.vjs-menu-item-text' ).html();
						var resolution = text.replace( /\D/g, '' );

						if ( resolution >= 2160 ) {
							$this.append( '<span class="vjs-quality-menu-item-sub-label">4K</span>' );
						} else if ( resolution >= 720 ) {
							$this.append( '<span class="vjs-quality-menu-item-sub-label">HD</span>' );
						}
					});

					// Add support for SRT
					if ( config.hasOwnProperty( 'tracks' ) ) {
						for ( var i = 0, max = config.tracks.length; i < max; i++ ) {
							var track = config.tracks[ i ];

							var mode = '';
							if ( 0 == i && 1 == config.cc_load_policy ) {
								mode = 'showing';
							}

							if ( /srt/.test( track.src.toLowerCase() ) ) {
								add_srt_text_track( player, track, mode );
							} else {
								var track_obj = {
									src: track.src,
									srclang: track.srclang,
									label: track.label,
									kind: 'subtitles'
								};

								if ( '' != mode ) {
									track_obj.mode = mode;
								}

								player.addRemoteTextTrack( track_obj, true ); 
							}					               
						}
					}              
				});

				// Fired the first time a video is played
				var viewed = false;

				player.on( 'play', function( e ) {
					if ( ! viewed ) {
						viewed = true;
						update_views_count( config );
					}

					$( '.aiovg-player-standard' ).trigger( 'aiovg.playRequested', { id: id } );
				});

				$elem.on( 'aiovg.playRequested', function( event, args ) {
					if ( id != args.id ) {
						player.pause();
					}
				});

				player.on( 'playing', function() {
					player.trigger( 'controlsshown' );
				});
	
				player.on( 'ended', function() {
					player.trigger( 'controlshidden' );
				});

				// Standard quality selector
				player.on( 'qualitySelected', function( event, source ) {
					var resolution = source.label.replace( /\D/g, '' );

					player.removeClass( 'vjs-4k' );
					player.removeClass( 'vjs-hd' );

					if ( resolution >= 2160 ) {
						player.addClass( 'vjs-4k' );
					} else if ( resolution >= 720 ) {
						player.addClass( 'vjs-hd' );
					}
				});

				// HLS quality selector
				var src = player.src();

				if ( /.m3u8/.test( src ) || /.mpd/.test( src ) ) {
					if ( config.player.controlBar.children.indexOf( 'qualitySelector' ) !== -1 ) {
						player.qualityMenu();
					};
				};

				// Offset
				var offset = {};

				if ( config.start ) {
					offset.start = config.start;
				}

				if ( config.end ) {
					offset.end = config.end;
				}
				
				if ( Object.keys( offset ).length > 1 ) {
					offset.restart_beginning = false;
					player.offset( offset );
				}				

				// Share / Embed
				if ( config.share || config.embed ) {
					overlays.push({
						content: '<a href="javascript:void(0)" class="vjs-share-embed-button" style="text-decoration:none;"><span class="vjs-icon-share"></span></a>',
						class: 'vjs-share',
						align: 'top-right',
						start: 'controlsshown',
						end: 'controlshidden',
						showBackground: false					
					});					
				}

				// Download
				if ( config.download ) {
					var __class = 'vjs-download';

					if ( config.share || config.embed ) {
						__class += ' vjs-has-share';
					}

					overlays.push({
						content: '<a href="' + config.download_url + '" class="vjs-download-button" style="text-decoration:none;" target="_blank"><span class="aiovg-icon-download"></span></a>',
						class: __class,
						align: 'top-right',
						start: 'controlsshown',
						end: 'controlshidden',
						showBackground: false					
					});
				}

				// Logo
				if ( config.show_logo ) {
					init_logo( overlays );
				}

				// Overlay
				if ( overlays.length > 0 ) {
					player.overlay({
						content: '',
						overlays: overlays
					});

					if ( config.share || config.embed ) {
						var options = {};
						options.content = $elem.find( '.vjs-share-embed' ).get(0);
						options.temporary = false;
	
						var ModalDialog = videojs.getComponent( 'ModalDialog' );
						var modal = new ModalDialog( player, options );
						modal.addClass( 'vjs-modal-dialog-share-embed' );
	
						player.addChild( modal );
	
						var wasPlaying = true;
						$elem.find( '.vjs-share-embed-button' ).on( 'click', function() {
							wasPlaying = ! player.paused;
							modal.open();						
						});
	
						modal.on( 'modalclose', function() {
							if ( wasPlaying ) {
								player.play();
							}						
						});
					}
	
					if ( config.embed ) {
						$elem.find( '.vjs-copy-embed-code' ).on( 'focus', function() {
							$( this ).select();	
							document.execCommand( 'copy' );					
						});
					}
				}

				// Custom contextmenu
				if ( config.copyright_text ) {
					init_contextmenu();
				}
			}
		}		

		// Logo overlay
		var init_logo = function( overlays ) {
			var attributes = [];
			attributes['src'] = config.logo_image;

			if ( config.logo_margin ) {
				config.logo_margin = config.logo_margin - 5;
			}

			var align;
			switch ( config.logo_position ) {
				case 'topleft':
					align = 'top-left';
					attributes['style'] = 'margin: ' + config.logo_margin + 'px;';
					break;
				case 'topright':
					align = 'top-right';
					attributes['style'] = 'margin: ' + config.logo_margin + 'px;';
					break;					
				case 'bottomright':
					align = 'bottom-right';
					attributes['style'] = 'margin: ' + config.logo_margin + 'px;';
					break;
				default:						
					align = 'bottom-left';
					attributes['style'] = 'margin: ' + config.logo_margin + 'px;';
					break;					
			}

			if ( config.logo_link ) {
				attributes['onclick'] = "window.location.href='" + config.logo_link + "';";
			}

			overlays.push({
				content: '<img ' +  merge_attributes( attributes ) + ' alt="" />',
				class: 'vjs-logo',
				align: align,
				start: 'controlsshown',
				end: 'controlshidden',
				showBackground: false					
			});
		}

		// Custom contextmenu
		var init_contextmenu = function() {
			if ( ! $( '#aiovg-contextmenu' ).length ) {
				$( 'body' ).append( '<div id="aiovg-contextmenu" style="display: none;"><div class="aiovg-contextmenu-content">' + config.copyright_text + '</div></div>' );
			}

			var contextmenu = document.getElementById( 'aiovg-contextmenu' );
			var timeout_handler = '';
			
			$( '#' + id ).on( 'contextmenu', function( e ) {						
				if ( 3 === e.keyCode || 3 === e.which ) {
					e.preventDefault();
					e.stopPropagation();
					
					var width = contextmenu.offsetWidth,
						height = contextmenu.offsetHeight,
						x = e.pageX,
						y = e.pageY,
						doc = document.documentElement,
						scrollLeft = ( window.pageXOffset || doc.scrollLeft ) - ( doc.clientLeft || 0 ),
						scrollTop = ( window.pageYOffset || doc.scrollTop ) - ( doc.clientTop || 0 ),
						left = x + width > window.innerWidth + scrollLeft ? x - width : x,
						top = y + height > window.innerHeight + scrollTop ? y - height : y;
			
					contextmenu.style.display = '';
					contextmenu.style.left = left + 'px';
					contextmenu.style.top = top + 'px';
					
					clearTimeout( timeout_handler );

					timeout_handler = setTimeout(function() {
						contextmenu.style.display = 'none';
					}, 1500 );				
				}														 
			});
			
			document.addEventListener( 'click', function() {
				contextmenu.style.display = 'none';								 
			});
		}

		// ...
		if ( config.show_consent ) {
			$elem.find( '.aiovg-privacy-consent-button' ).on( 'click', function() {
				$( this ).html( '...' );

				if ( 'iframe' != config.type ) {
					config.player.autoplay = true;
				}

				gdpr_consent();
			});

			$elem.on( 'aiovg.cookieConsent', function( event, args ) {
				if ( id != args.id ) {
					init_player();
					$elem.find( '.aiovg-privacy-wrapper' ).remove();
				}
			});
		} else {
			init_player();
		}		
	}

	/**
	 * Merge attributes.
	 *
	 * @since 3.0.0
	 */
	function merge_attributes( attributes ) {
		var str = '';

		for ( var key in attributes ) {
			str += ( key + '="' + attributes[ key ] + '" ' );
		}

		return str;
	}		

	/**
	 * Convert SRT to WebVTT.
	 *
	 * @since 2.6.3
	 */
	function srt_to_webvtt( data ) {
		// Remove dos newlines
		var srt = data.replace( /\r+/g, '' );

		// Trim white space start and end
		srt = srt.replace( /^\s+|\s+$/g, '' );

		// Get cues
		var cuelist = srt.split( '\n\n' );
		var result = "";

		if ( cuelist.length > 0 ) {
		  result += "WEBVTT\n\n";
		  for ( var i = 0; i < cuelist.length; i = i+1 ) {
			  result += convert_srt_cue( cuelist[ i ] );
		  }
		}

		return result;
  	}

  	function convert_srt_cue( caption ) {
		// Remove all html tags for security reasons
		// srt = srt.replace( /<[a-zA-Z\/][^>]*>/g, '' );

		var cue = "";
		var s = caption.split( /\n/ );

		// Concatenate muilt-line string separated in array into one
		while ( s.length > 3 ) {
			for ( var i = 3; i < s.length; i++ ) {
				s[2] += "\n" + s[ i ]
			}
			s.splice( 3, s.length - 3 );
		}

		var line = 0;

		// Detect identifier
		if ( ! s[0].match( /\d+:\d+:\d+/ ) && s[1].match( /\d+:\d+:\d+/ ) ) {
		  cue += s[0].match( /\w+/ ) + "\n";
		  line += 1;
		}

		// Get time strings
		if ( s[ line ].match( /\d+:\d+:\d+/ ) ) {
		  // Convert time string
		  var m = s[1].match( /(\d+):(\d+):(\d+)(?:,(\d+))?\s*--?>\s*(\d+):(\d+):(\d+)(?:,(\d+))?/ );
		  if ( m ) {
				cue += m[1] + ":" + m[2] + ":" + m[3] + "." + m[4] + " --> " + m[5] + ":" + m[6] + ":" + m[7] + "." + m[8] + "\n";
				line += 1;
		  } else {
				// Unrecognized timestring
				return "";
		  }
		} else {
		  // File format error or comment lines
		  return "";
		}

		// Get cue text
		if ( s[ line ] ) {
		  cue += s[ line ] + "\n\n";
		}

		return cue;
  	}

	function add_srt_text_track( player, track, mode ) {
		var xmlhttp;

		if ( window.XMLHttpRequest ) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject( 'Microsoft.XMLHTTP' );
		};
		
		xmlhttp.onreadystatechange = function() {				
			if ( 4 == xmlhttp.readyState && 200 == xmlhttp.status ) {					
				if ( xmlhttp.responseText ) {
					var vtt_text = srt_to_webvtt( xmlhttp.responseText );

					if ( '' != vtt_text ) {
						var vtt_blob = new Blob([ vtt_text ], { type : 'text/vtt' });
						var blob_url = URL.createObjectURL( vtt_blob );

						var track_obj = {
							src: blob_url,
							srclang: track.srclang,
							label: track.label,
							kind: 'subtitles'
						};

						if ( '' != mode ) {
							track_obj.mode = mode;
						}

						player.addRemoteTextTrack( track_obj, true );
					}
				}						
			}					
		};	

		xmlhttp.open( 'GET', track.src, true );
		xmlhttp.send();							
	}	

	/**
	 * Update video views count.
	 *
	 * @since 2.4.0
	 */
	function update_views_count( obj ) {
		if ( 'aiovg_videos' == obj.post_type ) {
			var data = {
				'action': 'aiovg_update_views_count',
				'post_id': obj.post_id,
				'security': aiovg_player.views_nonce
			};

			$.post( 
				aiovg_player.ajax_url, 
				data, 
				function( response ) {
					// Do nothing
				}
			);
		}
	}

	/**
	 *Refresh iframe player elements upon cookie confirmation.
	 *
	 * @since 3.0.0
	 */
	window.onmessage = function( e ) {
		if ( e.data == 'aiovg.cookieConsent' ) {
			$( '.aiovg-player-iframe iframe' ).each(function() {
				var url = $( this ).attr( 'src' );
				if ( url.indexOf( 'refresh=1' ) === -1 ) {
                    var separator = url.indexOf( '?' ) > -1 ? '&' : '?';
					$( this ).attr( 'src', url + separator + 'refresh=1' );
				}
			});
		}
	};

	/**
	 * Called when the page has loaded.
	 *
	 * @since 1.0.0
	 */
	$(function() {
		
		// Update views count for the non-iframe embeds
		$( '.aiovg-player-raw' ).each(function() {
			var params = $( this ).data( 'params' );
			update_views_count( params );
		});

		// Init Player
		$( '.aiovg-player-standard' ).each(function() {
			init_aiovg_player( $( this ) );
		});		

		// Custom error message
		if ( typeof videojs !== "undefined" ) {
			videojs.hook( 'beforeerror', function( player, err ) {
				var error = player.error();

				// Prevent current error from being cleared out
				if ( err === null ) {
					return error;
				}

				// But allow changing to a new error
				if ( err.code == 2 || err.code == 4 ) {
					var src = player.src();

					if ( /.m3u8/.test( src ) || /.mpd/.test( src ) ) {
						return {
							code: err.code,
							message: aiovg_player.i18n.stream_not_found
						};
					}
				}
				
				return err;
			});
		}

	});

})( jQuery );
