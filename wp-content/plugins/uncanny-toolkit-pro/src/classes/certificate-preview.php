<?php

namespace uncanny_pro_toolkit;

use TCPDF;
use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class CertificatePreview
 * @package uncanny_pro_toolkit
 */
class CertificatePreview extends toolkit\Config implements toolkit\RequiredFunctions {

	public static $current_time_stamp;

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {
			add_action( 'add_meta_boxes', [ __CLASS__, 'preview_certificate_add_meta_box' ] );
			add_action( 'admin_init', [ __CLASS__, 'display_preview_of_certificate' ] );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = __( 'Certificate Preview', 'uncanny-pro-toolkit' );

		//set to null or remove to disable the link to KB
		$kb_link = 'https://www.uncannyowl.com/knowledge-base/certificate-preview/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Get a preview of your quiz or course certificate without leaving the editor.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-file-pdf-o"></i><span class="uo_pro_text">PRO</span>';

		$category = 'learndash';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => null,
			'icon'             => $class_icon,
		);

	}

	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * @return boolean || string Return either true or name of function or plugin
	 *
	 */
	public static function dependants_exist() {

		/* Checks for LearnDash */
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		// Return true if no dependency or dependency is available
		return true;
	}

	/**
	 *
	 */
	public static function preview_certificate_add_meta_box() {
		add_meta_box(
			'preview_certificate-preview-certificate',
			__( 'Certificate Preview', 'uncanny-pro-toolkit' ),
			[ __CLASS__, 'preview_certificate_html' ],
			'sfwd-certificates',
			'side',
			'high'
		);
	}

	/**
	 * @param $post
	 */
	public static function preview_certificate_html( $post ) {
		?>
		<div style="display: table;">
		<p><?php _e( 'Save or Update Certificate before previewing.', 'uncanny-pro-toolkit' ) ?></p>
		<p>
			<a href="<?php echo admin_url( 'admin.php' ); ?>?certificate_id=<?php echo $post->ID; ?>&certificate_preview=true&wpnonce=<?php echo wp_create_nonce( time() ) ?>" target="_blank" class="button">
				<?php _e( 'Certificate Preview', 'uncanny-pro-toolkit' ) ?>
			</a></p>
		<p><?php _e( 'Note: Learndash shortcodes will be replaced with static preview values. Non-LearnDash shortcodes are not supported.', 'uncanny-pro-toolkit' ); ?></p>
		</div><?php
	}


	public static function display_preview_of_certificate() {
		if ( isset( $_GET['certificate_preview'] ) && isset( $_GET['certificate_id'] ) ) {
			$setup_parameters  = self::setup_parameters( $_GET['certificate_id'], 0, wp_get_current_user()->ID );
			$generate_pdf_args = [
				'certificate_post' => $_GET['certificate_id'],
				'save_path'        => null,
				'user'             => wp_get_current_user(),
				'file_name'        => 'preview_certificate',
				'parameters'       => $setup_parameters,
			];
			$file              = self::generate_pdf( $generate_pdf_args );
		}
	}

	/**
	 * @param $certificate_id
	 * @param $course_id
	 * @param $user_id
	 *
	 * @return array
	 */
	public static function setup_parameters( $certificate_id, $course_id, $user_id ) {
		$setup_parameters                      = [];
		$setup_parameters['userID']            = $user_id;
		$setup_parameters['course-id']         = $course_id;
		$setup_parameters['course-name']       = __( 'Certificate Preview', 'uncanny-pro-toolkit' );
		$setup_parameters['print-certificate'] = 0;
		$setup_parameters['certificate-post']  = $certificate_id;
		$setup_parameters['print-certificate'] = 1;

		return $setup_parameters;
	}

	/**
	 * @param $args
	 *
	 * @return string
	 */
	public static function generate_pdf( $args ) {
		$certificate_id  = $args['certificate_post'];
		$save_path       = $args['save_path'];
		$file_name       = $args['file_name'];
		$user            = $args['user'];
		$parameters      = $args['parameters'];
		$post_id         = intval( $certificate_id );
		$post_data       = get_post( $post_id );
		$monospaced_font = '';
		$l               = '';
		$config_lang     = 'eng';
		$ratio           = 1.25;
		$title           = strip_tags( $post_data->post_title );
		//$content             = $post_data->post_content;
		$target_post_id      = $post_id;
		$get_by_http_request = 0;
		$shortcode           = 'parse';
		$get_post            = get_post( $post_id );
		global $post;
		$post = $get_post;
		setup_postdata( $post );
		ob_start();

		$title = strip_tags( $title );

		$permalink   = get_permalink( $post_data->ID );
		$author_data = get_userdata( $post_data->post_author );

		if ( $author_data->display_name ) {
			$author = $author_data->display_name;
		} else {
			$author = $author_data->user_nicename;
		}


		if ( 1 === $get_by_http_request ) {
			$permalink_url = get_permalink( $post_id );
			$response_data = wp_remote_get( $permalink_url );
			$content       = preg_replace( '|^.*?<!-- post2pdf-converter-begin -->(.*?)<!-- post2pdf-converter-end -->.*?$|is', '$1', $response_data['body'] );
		} else {
			$content = $post_data->post_content;
		}

		if ( ! empty( $_GET['lang'] ) ) {
			$config_lang = substr( esc_html( $_GET['lang'] ), 0, 3 );
		}

		if ( ! empty( $_GET['file'] ) ) {
			$filename_type = $_GET['file'];
		}

		if ( 'title' === $filename_type && 0 === $target_post_id ) {
			$filename = $post_data->post_title;

		} else {
			$filename = $post_id;
		}

		$filename = substr( $filename, 0, 255 );

		$chached_filename = '';

		if ( 0 !== $target_post_id ) {
			$filename = WP_CONTENT_DIR . '/tcpdf-pdf/' . $filename;
		}


		if ( ! empty( $_GET['font'] ) ) {
			$font = esc_html( $_GET['font'] );
		}

		if ( ! empty( $_GET['monospaced'] ) ) {
			$monospaced_font = esc_html( $_GET['monospaced'] );
		}

		if ( ! empty( $_GET['fontsize'] ) ) {
			$font_size = intval( $_GET['fontsize'] );
		}

		if ( ! empty( $_GET['subsetting'] ) && ( $_GET['subsetting'] == 1 || $_GET['subsetting'] == 0 ) ) {
			$subsetting_enable = $_GET['subsetting'];
		}

		if ( 1 === $subsetting_enable ) {
			$subsetting = 'true';
		} else {
			$subsetting = 'false';
		}

		if ( ! empty( $_GET['ratio'] ) ) {
			$ratio = floatval( $_GET['ratio'] );
		}

		if ( ! empty( $_GET['header'] ) ) {
			$header_enable = $_GET['header'];
		}

		if ( ! empty( $_GET['logo'] ) ) {
			$logo_enable = $_GET['logo'];
		}

		if ( ! empty( $_GET['logo_file'] ) ) {
			$logo_file = esc_html( $_GET['logo_file'] );
		}

		if ( ! empty( $_GET['logo_width'] ) ) {
			$logo_width = intval( $_GET['logo_width'] );
		}

		if ( ! empty( $_GET['wrap_title'] ) ) {
			$wrap_title = $_GET['wrap_title'];
		}

		if ( ! empty( $_GET['footer'] ) ) {
			$footer_enable = $_GET['footer'];
		}

		if ( ! empty( $_GET['filters'] ) ) {
			$filters = $_GET['filters'];
		}

		if ( ! empty( $_GET['shortcode'] ) ) {
			$shortcode = esc_html( $_GET['shortcode'] );
		}

		//if ( 0 !== $target_post_id ) {
		//	$destination = 'F';
		//} else {
		$destination = 'I';
		//}
		$completion_time = current_time( 'timestamp' );
		$content         = preg_replace( '/\[courseinfo(.*?)(course_title)(.*?)\]/', __( 'Certificate Preview', 'uncanny-pro-toolkit' ), $content );
		$content         = preg_replace( '/\[courseinfo(.*?)(completed_on)(.*?)\]/', date_i18n( 'F d, Y', $completion_time ), $content );
		$content         = preg_replace( '/(\[usermeta)/', '[usermeta user_id="' . $user->ID . '" ', $content );
		$content         = apply_filters( 'uo_generate_course_certificate_content', $content, $user->ID, $parameters['course-id'] );
		preg_match_all( '/\[quizinfo(.+?)\]/', $content, $matches );
		//self::trace_logs( $matches, 'Matches', 'pdf' );

		if ( $matches ) {
			foreach ( $matches[0] as $quizinfo ) {
				if ( strpos( $quizinfo, 'timestamp' ) ) {
					$qinfo = str_replace( 'show="timestamp"', '', $quizinfo );
					preg_match( '/\"(.*)\"/', $qinfo, $date_format );
					//self::trace_logs( $date_format, 'Date Format', 'pdf' );
					if ( $date_format ) {
						$date = date_i18n( $date_format[1], $completion_time );
					} else {
						$date = date_i18n( 'F d, Y', $completion_time );
					}
					$content = str_ireplace( $quizinfo, $date, $content );
				}
				if ( strpos( $quizinfo, 'timespent' ) ) {
					$content = str_ireplace( $quizinfo, '88.9', $content );
				}
				if ( strpos( $quizinfo, 'percentage' ) ) {
					$content = str_ireplace( $quizinfo, '85', $content );
				}
				if ( strpos( $quizinfo, 'points' ) ) {
					$content = str_ireplace( $quizinfo, '8', $content );
				}
				if ( strpos( $quizinfo, 'total_points' ) ) {
					$content = str_ireplace( $quizinfo, '10', $content );
				}
				if ( strpos( $quizinfo, 'pass' ) ) {
					$content = str_ireplace( $quizinfo, 'Yes', $content );
				}
				if ( strpos( $quizinfo, 'count' ) ) {
					$content = str_ireplace( $quizinfo, '8', $content );
				}
				if ( strpos( $quizinfo, 'score' ) ) {
					$content = str_ireplace( $quizinfo, '85', $content );
				}
			}
		}

		// Delete shortcode for POST2PDF Converter
		$content = preg_replace( '|\[pdf[^\]]*?\].*?\[/pdf\]|i', '', $content );

		// For WP Code Highlight
		if ( function_exists( 'wp_code_highlight_filter' ) ) {
			$content = wp_code_highlight_filter( $content );
			$content = preg_replace( '/<pre[^>]*?>(.*?)<\/pre>/is', '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		}

		// Parse shortcode before applied WP default filters
		if ( 'parse' === $shortcode ) {

			// For WP SyntaxHighlighter
			if ( function_exists( 'wp_sh_add_extra_bracket' ) ) {
				$content = wp_sh_add_extra_bracket( $content );
			}

			if ( function_exists( 'wp_sh_do_shortcode' ) ) {
				$content = wp_sh_do_shortcode( $content );
			}

			// For SyntaxHighlighter Evolved
			if ( class_exists( 'SyntaxHighlighter' ) ) {
				global $SyntaxHighlighter;
				if ( method_exists( 'SyntaxHighlighter', 'parse_shortcodes' ) && method_exists( 'SyntaxHighlighter', 'shortcode_hack' ) ) {
					$content = $SyntaxHighlighter->parse_shortcodes( $content );
				}
			}

			// For SyntaxHighlighterPro
			if ( class_exists( 'GoogleSyntaxHighlighterPro' ) ) {
				global $googleSyntaxHighlighter;
				if ( method_exists( 'GoogleSyntaxHighlighterPro', 'bbcode' ) ) {
					$content = $googleSyntaxHighlighter->bbcode( $content );
				}
			}

		} elseif ( 1 !== $get_by_http_request ) {

			// For WP SyntaxHighlighter
			if ( function_exists( 'wp_sh_strip_shortcodes' ) ) {
				$content = wp_sh_strip_shortcodes( $content );
			}

			// For SyntaxHighlighterPro
			if ( class_exists( 'GoogleSyntaxHighlighterPro' ) ) {
				global $googleSyntaxHighlighter;
				if ( method_exists( 'GoogleSyntaxHighlighterPro', 'bbcode_strip' ) ) {
					$content = $googleSyntaxHighlighter->bbcode_strip( $content );
				}
			}
		}

		// Apply WordPress default filters to title and content
		if ( 1 === $filters && 1 !== $get_by_http_request ) {

			if ( has_filter( 'the_title', 'wptexturize' ) ) {
				$title = wptexturize( $title );
			}

			if ( has_filter( 'the_title', 'convert_chars' ) ) {
				$title = convert_chars( $title );
			}

			if ( has_filter( 'the_title', 'trim' ) ) {
				$title = trim( $title );
			}

			if ( has_filter( 'the_title', 'capital_P_dangit' ) ) {
				$title = capital_P_dangit( $title );
			}

			if ( has_filter( 'the_content', 'wptexturize' ) ) {
				$content = wptexturize( $content );
			}

			if ( has_filter( 'the_content', 'convert_smilies' ) ) {
				$content = convert_smilies( $content );
			}

			if ( has_filter( 'the_content', 'convert_chars' ) ) {
				$content = convert_chars( $content );
			}

			if ( has_filter( 'the_content', 'wpautop' ) ) {
				$content = wpautop( $content );
			}

			if ( has_filter( 'the_content', 'shortcode_unautop' ) ) {
				$content = shortcode_unautop( $content );
			}

			if ( has_filter( 'the_content', 'prepend_attachment' ) ) {
				$content = prepend_attachment( $content );
			}

			if ( has_filter( 'the_content', 'capital_P_dangit' ) ) {
				$content = capital_P_dangit( $content );
			}
		}
		//$content = do_shortcode( $content );

		if ( defined( 'LEARNDASH_LMS_LIBRARY_DIR' ) ) {
			require_once LEARNDASH_LMS_LIBRARY_DIR . '/tcpdf/config/lang/' . $config_lang . '.php';
			require_once LEARNDASH_LMS_LIBRARY_DIR . '/tcpdf/tcpdf.php';
		} else {
			$dir = self::get_learndash_plugin_directory();
			if ( $dir ) {
				// Include TCPDF
				require_once $dir . 'includes/vendor/tcpdf/config/lang/' . $config_lang . '.php';
				require_once $dir . 'includes/vendor/tcpdf/tcpdf.php';
			} else {
				return false;
			}
		}

		$certificate_details = get_post_meta( $certificate_id, 'learndash_certificate_options', true );

		if ( $certificate_details ) {
			$page_size        = $certificate_details['pdf_page_format'];
			$page_orientation = $certificate_details['pdf_page_orientation'];
		} else {
			$page_size        = 'LETTER';
			$page_orientation = 'L';
		}

		// Create a new object
		$pdf = new TCPDF( $page_orientation, PDF_UNIT, $page_size, true, 'UTF-8', false, false );

		// Set document information
		$pdf->SetCreator( PDF_CREATOR );
		$pdf->SetAuthor( get_bloginfo( 'name' ) );
		$pdf->SetTitle( $title . '_' . $post_id . '_' . get_bloginfo( 'name' ) );
		// Set default monospaced font
		$pdf->SetDefaultMonospacedFont( $monospaced_font );

		// Set header data
		if ( mb_strlen( $title, 'UTF-8' ) < 42 ) {
			$header_title = $title;
		} else {
			$header_title = mb_substr( $title, 0, 42, 'UTF-8' ) . '...';
		}

		if ( 1 === $header_enable ) {
			if ( 1 === $logo_enable && $logo_file ) {
				$pdf->SetHeaderData( $logo_file, $logo_width, $header_title, 'by ' . $author . ' - ' . $permalink );
			} else {
				$pdf->SetHeaderData( '', 0, $header_title, 'by ' . $author . ' - ' . $permalink );
			}
		}

		// Set header and footer fonts
		if ( 1 === $header_enable ) {
			$pdf->setHeaderFont( array( $font, '', PDF_FONT_SIZE_MAIN ) );
		}

		if ( 1 === $footer_enable ) {
			$pdf->setFooterFont( array( $font, '', PDF_FONT_SIZE_DATA ) );
		}

		// Remove header/footer
		if ( 0 === $header_enable ) {
			$pdf->setPrintHeader( false );
		}

		if ( 0 === $header_enable ) {
			$pdf->setPrintFooter( false );
		}
		// Set margins
		$pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );

		if ( 1 === $header_enable ) {
			$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
		}

		if ( 1 === $footer_enable ) {
			$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
		}

		// Set auto page breaks
		$pdf->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );

		// Set image scale factor
		$pdf->setImageScale( $ratio );

		// Set some language-dependent strings
		$pdf->setLanguageArray( $l );

		// Set fontsubsetting mode
		$pdf->setFontSubsetting( $subsetting );

		// Set font
		$pdf->SetFont( $font, '', $font_size, true );

		// Add a page
		$pdf->AddPage();

		// Create post content to print
		if ( 1 === $wrap_title ) {
			if ( ! mb_strlen( $title, 'UTF-8' ) < 33 ) {
				$title = mb_substr( $title, 0, 33, 'UTF-8' ) . '<br />' . mb_substr( $title, 33, 222, 'UTF-8' );
			}
		}

		//self::trace_logs( $post, '$post', 'certs' );

		// Parse shortcode after applied WP default filters
		if ( 'parse' === $shortcode && 1 !== $get_by_http_request ) {

			// For WP QuickLaTeX
			if ( function_exists( 'quicklatex_parser' ) ) {
				$content = quicklatex_parser( $content );
			}

			// For WP shortcode API
			$content = do_shortcode( $content );
		} elseif ( 1 !== $get_by_http_request ) {

			// For WP shortcode API
			$content = strip_shortcodes( $content );
		}

		// Convert relative image path to absolute image path
		$content = preg_replace( "/<img([^>]*?)src=['\"]((?!(http:\/\/|https:\/\/|\/))[^'\"]+?)['\"]([^>]*?)>/i", '<img$1src="' . site_url() . '/$2"$4>', $content );

		// Set image align to center
		$content = preg_replace_callback( "/(<img[^>]*?class=['\"][^'\"]*?aligncenter[^'\"]*?['\"][^>]*?>)/i", [
			__CLASS__,
			'post2pdf_conv_image_align_center'
		], $content );

		// Add width and height into image tag
		$content = preg_replace_callback( "/(<img[^>]*?src=['\"]((http:\/\/|https:\/\/|\/)[^'\"]*?(jpg|jpeg|gif|png))['\"])([^>]*?>)/i", [
			__CLASS__,
			'post2pdf_conv_img_size'
		], $content );

		// For common SyntaxHighlighter
		$content = preg_replace( "/<pre[^>]*?class=['\"][^'\"]*?brush:[^'\"]*?['\"][^>]*?>(.*?)<\/pre>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		$content = preg_replace( "/<script[^>]*?type=['\"]syntaxhighlighter['\"][^>]*?>(.*?)<\/script>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		$content = preg_replace( "/<pre[^>]*?name=['\"]code['\"][^>]*?>(.*?)<\/pre>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		$content = preg_replace( "/<textarea[^>]*?name=['\"]code['\"][^>]*?>(.*?)<\/textarea>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
		$content = preg_replace( '/\n/', '<br/>', $content ); //"\n" should be treated as a next line

		// For WP-SynHighlight(GeSHi)
		if ( function_exists( 'wp_synhighlight_settings' ) ) {
			$content = preg_replace( "/<pre[^>]*?class=['\"][^>]*?>(.*?)<\/pre>/is", '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );
			$content = preg_replace( '|<div[^>]*?class="wp-synhighlighter-outer"><div[^>]*?class="wp-synhighlighter-expanded"><table[^>]*?><tr><td[^>]*?><a[^>]*?></a><a[^>]*?class="wp-synhighlighter-title"[^>]*?>[^<]*?</a></td><td[^>]*?><a[^>]*?><img[^>]*?/></a>[^<]*?<a[^>]*?><img[^>]*?/></a>[^<]*?<a[^>]*?><img[^>]*?/></a>[^<]*?</td></tr></table></div>|is', '', $content );
		}

		// For other sourcecode
		$content = preg_replace( '/<pre[^>]*?><code[^>]*?>(.*?)<\/code><\/pre>/is', '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $content );

		// For blockquote
		$content = preg_replace( '/<blockquote[^>]*?>(.*?)<\/blockquote>/is', '<blockquote style="color: #406040;">$1</blockquote>', $content );

		// Combine title with content
		$formatted_title = '<h1 style="text-align:center;">' . $title . '</h1>';

		//$formatted_post = $formatted_title . '<br/><br/>' . $content;    (Title will not appear on PDF)
		$formatted_post = '<br/><br/>' . $content;
		$formatted_post = preg_replace( '/(<[^>]*?font-family[^:]*?:)([^;]*?;[^>]*?>)/is', '$1' . $font . ',$2', $formatted_post );

		// get featured image
		$postid   = get_the_id(); //Get current post id
		$img_file = self::learndash_get_thumb_path( $certificate_id ); //The same function from theme's[twentytwelve here] function.php

		//Only print image if it exists
		if ( ! empty( $img_file ) ) {

			//Print BG image
			$pdf->setPrintHeader( false );

			// get the current page break margin
			$bMargin = $pdf->getBreakMargin();

			// get current auto-page-break mode
			$auto_page_break = $pdf->getAutoPageBreak();

			// disable auto-page-break
			$pdf->SetAutoPageBreak( false, 0 );

			// Get width and height of page for dynamic adjustments
			$pageH = $pdf->getPageHeight();
			$pageW = $pdf->getPageWidth();

			//Print the Background
			$pdf->Image( $img_file, $x = '0', $y = '0', $w = $pageW, $h = $pageH, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = [] );

			// restore auto-page-break status
			$pdf->SetAutoPageBreak( $auto_page_break, $bMargin );

			// set the starting point for the page content
			$pdf->setPageMark();
		}

		// Print post
		$pdf->writeHTMLCell( $w = 0, $h = 0, $x = '', $y = '', $formatted_post, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true );

		// Set background
		$pdf->SetFillColor( 255, 255, 127 );
		$pdf->setCellPaddings( 0, 0, 0, 0 );
		// Print signature

		ob_clean();
		// Output pdf document
		$full_path = $save_path . $file_name . '.pdf';

		$pdf->Output( $full_path, $destination ); /* F means saving on server. */
		wp_reset_postdata();

		//return $full_path;

	}


	/**
	 * @param $matches
	 *
	 * @return string
	 */
	public static function post2pdf_conv_image_align_center( $matches ) {
		$tag_begin = '<p class="post2pdf_conv_image_align_center">';
		$tag_end   = '</p>';

		return $tag_begin . $matches[1] . $tag_end;
	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	public static function post2pdf_conv_img_size( $matches ) {
		$size = null;

		if ( strpos( $matches[2], site_url() ) === false ) {
			return $matches[1] . $matches[5];
		}

		$image_path = ABSPATH . str_replace( site_url() . '/', '', $matches[2] );

		if ( file_exists( $image_path ) ) {
			$size = getimagesize( $image_path );
		} else {
			return $matches[1] . $matches[5];
		}

		return $matches[1] . ' ' . $size[3] . $matches[5];
	}

	/**
	 * @param $this ->post_id
	 *
	 * @return string
	 */
	public static function learndash_get_thumb_path( $post_id ) {
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		if ( $thumbnail_id ) {
			$img_path      = get_post_meta( $thumbnail_id, '_wp_attached_file', true );
			$upload_url    = wp_upload_dir();
			$img_full_path = $upload_url['basedir'] . '/' . $img_path;

			return $img_full_path;
		}
	}

	/**
	 * @return string
	 */
	public static function get_learndash_plugin_directory() {
		$all_plugins = get_plugins();
		$dir         = '';
		if ( $all_plugins ) {
			foreach ( $all_plugins as $key => $plugin ) {
				if ( 'LearnDash LMS' === $plugin['Name'] ) {
					$dir = plugin_dir_path( $key );

					return WP_PLUGIN_DIR . '/' . $dir;
					break;
				}
			}
		}

		return $dir;
	}

}