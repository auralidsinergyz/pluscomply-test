<?php

/**
 * Video Player - GDPR Consent.
 *
 * @link     https://plugins360.com
 * @since    1.6.0
 *
 * @package All_In_One_Video_Gallery
 */

$image = '';

if ( isset( $_GET['poster'] ) ) {
	$image = $_GET['poster'];
} elseif ( ! empty( $post_meta ) ) {
    $image_data = aiovg_get_image( $post_id, 'large' );
	$image = $image_data['src'];
}

if ( ! empty( $image ) ) {
    $image = aiovg_resolve_url( $image );
} else {
    foreach ( $embedded_sources as $source ) {
        $is_src_found = 0;

        if ( isset( $_GET[ $source ] ) ) {
            $is_src_found = 1;
            $src = urldecode( $_GET[ $source ] );
        }
        
        if ( $is_src_found ) {            
            switch ( $source ) {
                case 'youtube':
                    $image = aiovg_get_youtube_image_url( $src );					
                    break;
                case 'vimeo':
                    $vimeo = aiovg_get_vimeo_oembed_data( $src );
                    $image = $vimeo['thumbnail_url'];
                    break;				
                case 'dailymotion':
                    $image = aiovg_get_dailymotion_image_url( $src );
                    break;
            }
        }
    }
}

$consent_message = apply_filters( 'aiovg_translate_strings', $privacy_settings['consent_message'], 'consent_message' );
$consent_button_label = apply_filters( 'aiovg_translate_strings', $privacy_settings['consent_button_label'], 'consent_button_label' );
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
        
    <?php if ( $post_id > 0 ) : ?>    
        <title><?php echo wp_kses_post( get_the_title( $post_id ) ); ?></title>    
        <link rel="canonical" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" />
    <?php endif; ?>

	<style type="text/css">
        html, 
        body {			
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important; 
			padding: 0 !important; 
			font-family: Verdana, Geneva, sans-serif;
			font-size: 14px;
            line-height: 1.5;
            overflow: hidden;
        }

        #privacy-wrapper {            
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #222;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #FFF;
            text-align: center;
        }

        #privacy-consent-block {
            position: relative;
            margin: 0 30px;
            padding: 15px;
            top: 50%;
            background: #000;
            border-radius: 3px;
            opacity: 0.9;
            transform: translateY( -50% );
            -ms-transform: translateY(- 50% );
            -webkit-transform: translateY( -50% );
        }

        #privacy-consent-button {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 15px;
            background: #F00;
            border-radius: 3px;
            cursor: pointer;
        }

        #privacy-consent-button:hover {
            opacity: 0.8;
        }

        @media only screen and (max-width: 250px) {
            #privacy-consent-block {
                margin: 0;
                font-size: 12px;               
            }
        }
    </style>
</head>
<body>    
	<div id="privacy-wrapper" style="background-image: url(<?php echo esc_attr( $image ); ?>);">
		<div id="privacy-consent-block" >
			<div id="privacy-consent-message"><?php echo wp_kses_post( trim( $consent_message ) ); ?></div>
			<div id="privacy-consent-button"><?php echo esc_html( $consent_button_label ); ?></div>
		</div>
	</div>
				
	<script type="text/javascript">
		/**
		* Set cookie for accepting the privacy consent.
		*
		* @since 1.6.0
		*/
		function ajaxSubmit() {	
            document.getElementById( 'privacy-consent-button' ).innerHTML = '...';

			var xmlhttp;

			if ( window.XMLHttpRequest ) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject( 'Microsoft.XMLHTTP' );
			};
			
			xmlhttp.onreadystatechange = function() {				
				if ( 4 == xmlhttp.readyState && 200 == xmlhttp.status ) {					
					if ( xmlhttp.responseText ) {
                        var url = window.location.href;
                        var separator = url.indexOf( '?' ) > -1 ? '&' : '?';

                        window.parent.postMessage( 'aiovg.cookieConsent', '*' );
						window.location.href = url + separator + 'refresh=1'; // Reload document
					}						
				}					
			};	

			xmlhttp.open( 'POST', '<?php echo admin_url( 'admin-ajax.php' ); ?>?action=aiovg_set_cookie&security=<?php echo wp_create_nonce( 'aiovg_ajax_nonce' ); ?>', true );
			xmlhttp.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
			xmlhttp.send( 'action=aiovg_set_cookie' );							
		}
		
		document.getElementById( 'privacy-consent-button' ).addEventListener( 'click', ajaxSubmit );
	</script>
</body>
</html>
