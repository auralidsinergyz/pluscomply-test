<?php
namespace LDMC\Admin;

if ( ! class_exists( '\LDMC\Admin\Bootstrap' ) ) {
	class Bootstrap {

		/**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
		use \LDMC\Traits\Helpers;

		public function __construct() {
            add_action( 'admin_menu', array( \LDMC\Admin\Pages\Dashboard::get_instance(), 'CreatePageMenu' ), 10 );

            add_action( 'learndash_delete_user_data', array($this, 'delete_ld_mc_data'), 10, 1 );
            add_action( 'admin_init', array( $this, 'ld_custom_settings' ), 11 );

		}






        public function ld_custom_settings() {
            \LDMC\Admin\Settings\Course::get_instance();
            \LDMC\Admin\Settings\Group::get_instance();
            \LDMC\Admin\Settings\Quiz::get_instance();

            
        }

        public function delete_ld_mc_data( $user_id ){
            // $this->write_log('delete_ld_mc_data');
            global $wpdb;
            $sql = "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE '%ld_mc_get_selected_certificate%' AND user_id={$user_id}";
            // $this->write_log('$sql');
            // $this->write_log($sql);
            $deleted = $wpdb->query( $wpdb->prepare( $sql ) );
            if($deleted === false){
                // $this->write_log('deleted = true');
            }else{
                // $this->write_log('deleted = false');
            }
//            $course_ids = learndash_user_get_enrolled_courses( $user_id, array(), true );
//            foreach( $course_ids as $course_id ){
//                delete_user_meta($user_id,'ld_mc_get_selected_certificate_'.$course_id);
//            }
        }

	}
}
