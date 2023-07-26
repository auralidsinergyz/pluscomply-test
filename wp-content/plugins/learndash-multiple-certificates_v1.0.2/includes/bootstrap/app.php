<?php
namespace LDMC\Bootstrap;

if ( ! class_exists( 'LDMC\Bootstrap\App' ) ) {
    class App {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;

        public function __construct() {
            add_filter( 'plugin_action_links_' .LD_MC_MAIN_FILE_RELATIVE_PATH, array($this, 'register_plugin_actions_links'), 10, 4 );
            add_filter( 'plugin_row_meta', array( $this, 'register_plugin_row_meta_links' ), 10, 4 );
            add_action('ld_mc_loaded', array($this, 'init_hooks'), 10 );

            add_action( 'bp_init', array($this,'bp_tol_start'));
        }

        public function register_plugin_actions_links( $actions, $plugin_file, $plugin_data, $context ){
            if (LD_MC_MAIN_FILE_RELATIVE_PATH == $plugin_file) {
                $settings_url = add_query_arg(
                    array(
                        'page' => 'ld-mc-admin-dashboard',
                    ),
                    admin_url('admin.php')
                );
                $actions[] = '<a href="'. esc_url( $settings_url ) .'">'.__( 'Settings', LD_MC_TEXT_DOMAIN ).'</a>';
            }
            return $actions;
        }

        public function register_plugin_row_meta_links( $plugin_meta, $plugin_file, $plugin_data, $status ){
            if ( LD_MC_MAIN_FILE_RELATIVE_PATH === $plugin_file ) {
                $plugin_meta[] = '<a href="#" target="_blank">'.__('Documentation', LD_MC_TEXT_DOMAIN ).'</a>';
                $plugin_meta[] = '<a href="https://wooninjas.com/open-support-ticket/" target="_blank">'.__('Support', LD_MC_TEXT_DOMAIN).'</a>';
            }
            return $plugin_meta;
        }

        public function init_hooks(){
//            \LDMC\Admin\Settings\Email::get_instance();
            \LDMC\Admin\Settings\GroupEmail::get_instance();
            \LDMC\Admin\Settings\CourseEmail::get_instance();
            \LDMC\Admin\Settings\QuizEmail::get_instance();
            \LDMC\Admin\Settings\License::get_instance();

//            \LDMC\Admin\Ajax\License::get_instance();

            \LDMC\Admin\Settings\GeneralSettings::get_instance();
            add_action( 'init', array($this, 'load_textdomain'), 10);
            add_action( 'init', array($this, 'license_init'), 10 );
            add_action( 'init', array( $this, 'init_admin_frontend' ), 10 );
            remove_action('template_redirect', 'learndash_certificate_display', 5 );
            add_action( 'template_redirect', array( $this, 'learndash_certificate_display'), 5 );

            add_action( 'init', array( $this, 'create_certificates_folder' ) );

            add_action('init', array($this, 'init_license'), 10);
            add_action('admin_notices', array($this, 'license_notices'), 10);
            add_action('init', array($this, 'init_plugin_updater'), 10);
        }

        public function init_license(){
            // $this->write_log('license_notices');
            $license_key = get_option('ld_mc_license_key');
            // $this->write_log('$license_key');
            // $this->write_log($license_key);
            $this->get_license_manager()->set_license_key($license_key);
            $this->get_license_manager()->check();
        }

        public function license_notices(){
            // $this->write_log('license_notices');


            if( $this->get_license_manager()->get_status_type() == 'error' || $this->get_license_manager()->get_status_type() == 'warning' ){
                $class = 'notice notice-'.$this->get_license_manager()->get_status_type();
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), __( $this->get_license_manager()->get_status_message() ) );
            }elseif( $this->get_license_manager()->get_status_type() == 'success' || $this->get_license_manager()->get_status_type() == 'info' ){
                do_action('ld_mc_check_plugin_update');
            }

        }

        public function init_plugin_updater(){
            // $this->write_log('init_plugin_updater');
            $plugins_updates = get_option('update_plugins');
            // $this->write_log('$plugins_updates');
            // $this->write_log($plugins_updates);
            // to check plugin update
            // you can run the following SQL query
            // SELECT * FROM wp_options AS op WHERE op.option_name LIKE '%update_plugins%';

            if( $this->get_license_manager()->get_status_type() == 'success' ){
                // $this->write_log('init_plugin_updater - case 01');
                new \LDMC\License\PluginUpdater( LD_MC_AUTHOR_SITE, LD_MC_MAIN_FILE_ABSOLUTE_PATH, array(
                    'version'   => LD_MC_VERSION,
                    'license'   => get_option( 'ld_mc_license_key' ),
                    'item_name' => LD_MC_NAME,
                    'author'    => LD_MC_AUTHOR_NAME
                ) );
            }
        }

        public function load_textdomain() {
            load_plugin_textdomain( LD_MC_TEXT_DOMAIN, false, dirname( LD_MC_MAIN_FILE_RELATIVE_PATH ) . '/languages' );
        }


        public function license_init(){
//            new \LDMC\License\License_Manager();
        }

        public function init_admin_frontend() {
            \LDMC\Admin\Bootstrap::get_instance();
            \LDMC\FrontEnd\Bootstrap::get_instance();
        }

        public function create_certificates_folder() {
            $wp_filesystem = $this->wp_filesystem();

            if ( ! $wp_filesystem->exists( LD_MC_PATH_CERTIFICATES ) ) {
                $wp_filesystem->mkdir( LD_MC_PATH_CERTIFICATES );
            }
        }




        //buddyboss Start
        public function bp_tol_register_template_location() {
            // var_dump(LD_MC_DIR_PATH);
            return \LD_MC_DIR_PATH . 'buddyboss/templates/';
        }
         
         
        // replace member-header.php with the template overload from the plugin
        public function bp_tol_maybe_replace_template( $templates, $slug, $name ) {
             
            if( 'members/single/courses/certificates' != $slug )
                return $templates;
                 
            return array( 'members/single/courses/certificates.php' );
        }
         
         
        public function bp_tol_start() {
             
            if( function_exists( 'bp_register_template_stack' ) )
                bp_register_template_stack( array($this,'bp_tol_register_template_location') );
             
            // if viewing a member page, overload the template
            if ( bp_is_user()  ) 
                add_filter( 'bp_get_template_part', array( $this,'bp_tol_maybe_replace_template' ), 10, 3 );
             
        }
        //buddyboss End


    }
}