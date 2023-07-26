<?php
namespace LDMC\Admin\Pages;

if ( ! class_exists( '\LDMC\Admin\Pages\Dashboard' ) ) {
    class Dashboard
    {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;
        use \LDMC\Traits\Template;

        private $parent_slug;
        private $page_title;
        private $menu_title;
        private $capability;
        private $menu_slug;
        private $function;

        public $page_menu;
        public $tab;

        public function __construct()
        {
            $this->parent_slug = 'learndash-lms';
            $this->page_title = __( 'Multi Certificates', LD_MC_TEXT_DOMAIN );
            $this->menu_title = __( 'Multi Certificates', LD_MC_TEXT_DOMAIN );
            $this->capability = 'manage_options';
            $this->menu_slug = 'ld-mc-admin-dashboard';
            $this->function = array($this, 'page');
        }

        public function remove_all_admin_notices(){
            remove_all_actions('admin_notices');
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
            echo '<style>.notice{ display: none; }</style>';
        }

        public function CreatePageMenu()
        {
            do_action('ld_mc_admin_dashboard_page_before_created');
            $this->page_menu = add_submenu_page(
                $this->parent_slug,
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                $this->function,
                8
            );
//            add_action( "admin_head-{$this->page_menu}", array( $this, 'remove_all_admin_notices'), 10 );
            add_action("admin_print_scripts-{$this->page_menu}", array($this, 'dashboard_css_js'), 10000 );
            do_action('ld_mc_admin_dashboard_page_after_created');
        }


        public function page()
        {
            $this->get_template('admin.dashboard', array(), true, false );
        }

        public function dashboard_css_js(  )
        {
            wp_enqueue_style( 'ld_mc_admin_style', LD_MC_ASSETS_URL . 'admin/css/ld-mc-admin-style.css', array(), time(), 'all' );
            /**
             * JS
             */
            wp_enqueue_script( 'ld_mc_admin_script', LD_MC_ASSETS_URL . 'admin/js/ld-mc-admin-script.js', array( 'jquery' ), time(), true );

        }

    }
}