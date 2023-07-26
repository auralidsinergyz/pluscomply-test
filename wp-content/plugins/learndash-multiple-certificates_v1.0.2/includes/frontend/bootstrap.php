<?php
namespace LDMC\FrontEnd;

if ( ! class_exists( '\LDMC\FrontEnd\Bootstrap' ) ) {
	class Bootstrap {

		/**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
		use \LDMC\Traits\Helpers;

		public function __construct() {
			add_action( 'wp_loaded', array( $this, 'themes_setup' ), 10 );
			add_action( 'wp_loaded', array( $this, 'ajax_setup' ), 12 );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_css_js' ), 10 );


        }

       


		public function themes_setup() {
			if ( learndash_is_active_theme( 'ld30' ) ) {
                \LDMC\FrontEnd\Themes\LD30\Profile::get_instance();
                \LDMC\FrontEnd\Themes\LD30\Group::get_instance();
				\LDMC\FrontEnd\Themes\LD30\Course::get_instance();
                \LDMC\FrontEnd\Themes\LD30\Quiz::get_instance();

			}
		}

		public function ajax_Setup() {
			\LDMC\FrontEnd\Ajax\Certificate::get_instance();
		}

		public function frontend_css_js() {
			/**
			 * CSS
			 */
			wp_enqueue_style( 'ld_mc_swa2_style', LD_MC_ASSETS_URL . 'frontend/css/sweetalert2.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'ld_mc_frontend_style', LD_MC_ASSETS_URL . 'frontend/css/ld-mc-frontend-style.css', array('ld_mc_swa2_style'), time(), 'all' ); 
			/**
			 * JS
			 */
			wp_enqueue_script( 'ld_mc_swa2_script', LD_MC_ASSETS_URL . 'frontend/js/sweetalert2.all.min.js', array( 'jquery' ), time(), true );
			wp_enqueue_script( 'ld_mc_font_awsome_script', LD_MC_ASSETS_URL . 'frontend/js/font-awsome.js', array( 'jquery' ), time(), true );
		}



		
	}
}
