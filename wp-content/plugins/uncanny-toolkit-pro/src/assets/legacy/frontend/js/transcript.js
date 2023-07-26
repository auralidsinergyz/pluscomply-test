jQuery(function($){
	$(document).ready(function(){
		Transcript.init();
	});

	var Transcript = {
		init: function(){
			this.Print.init( this );
		},

		Print: {
			Transcript: null,

			elements: {
				$print_btn: null,
			},

			init: function( Transcript ){
				this.Transcript = Transcript;

				this.elements.$print_btn = $('#uo-ultp-transcript__print-trigger');
				this.elements.$document  = $('#uo-ultp-transcript__document');
				this.elements.$render 	 = $('#uo-ultp-transcript__render');

				this.bind();
			},

			bind: function(){
				let _this = this;

				this.elements.$print_btn.on('click', function(){
					_this.print( _this.elements.$document );
				});
			},

			print: function( $document ){
				var options,
					$this = $document,
					Print = this;

				if ( arguments.length > 0 ){
					options = arguments[0];
				}

				var defaults = {
					globalStyles: 			true,
					mediaPrint: 			false,
					stylesheet: 			null,
					noPrintSelector: 		'.uo-no-print',
					iframe: 				true,
					manuallyCopyFormValues: true,
					deferred: 				$.Deferred(),
					timeout: 				750,
					title: 					null,
					doctype: 				'<!DOCTYPE html>'
				};

				options = $.extend( {}, defaults, ( options || {} ) );

				var $styles = $('');

				if ( options.globalStyles ){
					$styles = $('style, link, meta, base, title');
				}
				else if ( options.mediaPrint ){
					$styles = $('link[media=print]');
				}

				if ( options.stylesheet ) {
					$styles = $.merge( $styles, $( '<link rel="stylesheet" href="' + options.stylesheet + '">' ) );
				}

				var copy = $this.clone();
				copy = $('<span/>').append( copy );

				copy.find( options.noPrintSelector ).remove();
				copy.append( $styles.clone() );

				if ( options.title ){
					var title = $( 'title', copy );

					if ( title.length === 0 ) {
						title = $('<title />');
						copy.append( title );				
					}

					title.text( options.title );			
				}

				if ( options.manuallyCopyFormValues ){

					copy.find('input')
						.each( function(){
							var $field = $(this);
							if ( $field.is( '[type="radio"]' ) || $field.is( '[type="checkbox"]' ) ) {
								if ( $field.prop( 'checked' ) ){
									$field.attr( 'checked', 'checked' );
								}
							}
							else {
								$field.attr( 'value', $field.val() );
							}
						});

					copy.find('select').each( function(){
						var $field = $(this);
						$field.find( ':selected' ).attr( 'selected', 'selected' );
					});
					copy.find('textarea').each( function(){
						var $field = $(this);
						$field.text( $field.val() );
					});
				}

				var content = copy.html();

				try {
					options.deferred.notify( 'generated_markup', content, copy );
				}
				catch (err) {
					console.warn( 'Error notifying deferred', err );
				}

				copy.remove();

				if ( options.iframe ){
					try {
						Print.print_content_in_iframe( content, options );
					}
					catch ( e ){
						console.error( 'Failed to print from iframe', e.stack, e.message );
						Print.print_content_in_new_window( content, options );
					}
				}
				else {
					Print.print_content_in_new_window( content, options );
				}
				return this;
			},

			get_jQuery_object: function( string ){
				var object 	= $(''),
					Print 	= this;

				try {
					object = $( string ).clone();
				}
				catch ( e ){
					object = $('<span />').html( string );
				}

				return object;
			},

			print_frame: function( frameWindow, content, options ){
				var def 	= $.Deferred(),
					Print 	= this;

				try {
					frameWindow = frameWindow.contentWindow || frameWindow.contentDocument || frameWindow;
					var wdoc = frameWindow.document || frameWindow.contentDocument || frameWindow;

					if( options.doctype ) {
						wdoc.write( options.doctype );
					}

					wdoc.write( content );
					wdoc.close();
					var printed = false;

					var request_print = function(){
						if ( printed ){
							return;
						}

						frameWindow.focus();

						try {
							if ( ! frameWindow.document.execCommand( 'print', false, null ) ) {
								frameWindow.print();
							}

							$('body').focus();
						}
						catch ( e ) {
							frameWindow.print();
						}

						frameWindow.close();
						printed = true;
						def.resolve();
					}

					$( frameWindow ).on( 'load', request_print );
					setTimeout( request_print, options.timeout );
				}
				catch ( err ){
					def.reject( err );
				}

				return def;
			},

			print_content_in_iframe: function( content, options ){
				var $iframe 	= $( options.iframe + '' ),
					iframeCount = $iframe.length,
					Print 		= this;

				if ( iframeCount === 0 ){
					$iframe = $( '<iframe height="0" width="0" border="0" wmode="Opaque"/>' ).prependTo('body').css({ 'position': 'absolute', 'top': -999, 'left': -999});
				}

				var frameWindow = $iframe.get(0);

				return  Print.print_frame( frameWindow, content, options )
						.done( function(){
							setTimeout( function(){
									if ( iframeCount === 0 ){
										$iframe.remove();
									}
								}, 1000);
						})
						.fail( function( err ){
							console.error( 'Failed to print from iframe', err );
							Print.print_content_in_new_window( content, options );
						})
						.always( function(){
							try {
								options.deferred.resolve();
							}
							catch ( err ){
								console.warn( 'Error notifying deferred', err );
							}
						});
			},

			print_content_in_new_window: function( content, options ){
				var frameWindow = window.open(),
					Print = this;

				return Print.print_frame( frameWindow, content, options )
					.always( function() {
						try {
							options.deferred.resolve();
						}
						catch ( err ){
							console.warn( 'Error notifying deferred', err );
						}
					});
			},

			is_node: function( o ){
				return !! ( typeof Node === 'object' ? o instanceof Node : o && typeof o === 'object' && typeof o.nodeType === 'number' && typeof o.nodeName === 'string' );
			}
		}
	}
});