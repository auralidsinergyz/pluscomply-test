<?php
namespace LDMC\FrontEnd\Themes\LD30;

if ( ! class_exists( '\LDMC\FrontEnd\Themes\LD30\Group' ) ) {
    class Group {

        /**
         * Traits used inside class
         */
        use \LDMC\Traits\Singleton;
        use \LDMC\Traits\Helpers;


        public function __construct( ) {
            /**
             * Add Custom CSS Class to the Group Certificate Download Button
             */
            add_filter( 'ld-alert-class', array( $this, 'add_custom_class_to_group_certificate_button'), 10, 3 );
            /**
             * Update Group Certificate Button Attribute
             */
            add_filter('ld_mc_group_certificate_button_atts', array($this, 'update_group_certificate_button_atts'), 10, 3);
            /**
             * Update Group Certificate Button HTML
             */
            add_filter('learndash_certificate_html', array($this, 'update_group_certificate_button_html'), 10, 3);
        }

        public function add_custom_class_to_group_certificate_button( $class, $type, $icon ) {
            global $post;
            if( $post->post_type == learndash_get_post_type_slug('group') ){
                // $this->write_log('add_custom_class_to_group_certificate_button');
                $user_id = get_current_user_id();
                if ( strpos( $class, 'ld-alert-certificate' ) !== false ) {
                    $user_selected_certificate_id = $this->get_user_selected_certificate_id($post->ID, $user_id);
                    // $this->write_log('$user_selected_certificate_id');
                    // $this->write_log($user_selected_certificate_id);
                    if ( 0 == $user_selected_certificate_id ) {
                        $class = $class.' ldm*-_select_certificate';
                    }
                }
            }
            return $class;
        }

        public function update_group_certificate_button_atts( $atts, $cert_button_html, $content ){
            if( isset($atts['group_id']) && ! empty($atts['group_id']) && 0 != $atts['group_id'] ) {
                // $this->write_log('update_group_certificate_button_atts');
                $group_status = learndash_get_user_group_status($atts['group_id'], $atts['user_id'], true);
	            $group_certificates = learndash_get_setting( $atts['group_id'], 'certificate' );
                if ($group_status == 'completed' && ! empty($group_certificates) ) {
                    // $this->write_log('update_group_certificate_button_atts - case 01');
                    $certificate_id = $this->get_user_selected_certificate_id($atts['group_id'], $atts['user_id']);
                    $atts['label'] = ($certificate_id) ? __('Download Certificate',LD_MC_TEXT_DOMAIN) : __('Select Certificate',LD_MC_TEXT_DOMAIN);
                    $cert_query_args['cert-nonce'] = wp_create_nonce($atts['group_id'] . $atts['user_id'] . $atts['user_id']);
                    $cert_query_args['group_id'] = $atts['group_id'];
                    $url = add_query_arg($cert_query_args, get_permalink(intval($certificate_id)));
                    $atts['cert_url'] = $url;
                } else {
                    // $this->write_log('update_group_certificate_button_atts - case 02');
                    $atts = [];
                }
                // $this->write_log('$atts');
                // $this->write_log($atts);
            }
            return $atts;
        }

        public function update_group_certificate_button_html( $cert_button_html, $atts, $content ){
            if( isset($atts['group_id']) && ! empty($atts['group_id']) && 0 != $atts['group_id'] ) {

                // $this->write_log('update_group_certificate_button_html');
                // $this->write_log('$cert_button_html');
                // $this->write_log($cert_button_html);
                // $this->write_log('$atts - before');
                // $this->write_log($atts);
                // $this->write_log('$content');
                // $this->write_log($content);


                $atts = apply_filters('ld_mc_group_certificate_button_atts', $atts, $cert_button_html, $content );


                // $this->write_log('$atts - after');
                // $this->write_log($atts);

	            if( ! isset($atts['cert_url']) ){
		            return $cert_button_html;
	            }

                if( ! empty($atts['cert_url']) && '#' != $atts['cert_url'] && 'javascript:;' != $atts['cert_url'] && ! empty($cert_button_html) ){
                    // $this->write_log('update_learndash_certificate_button_html - case 03');
                    return $cert_button_html;
                }else{
                    // $this->write_log('update_learndash_certificate_button_html - case 04');
                }

                if ( isset($atts['display_as']) && 'banner' === $atts['display_as'] ) {
                    $cert_button_html = learndash_get_template_part(
                        'modules/alert.php',
                        array(
                            'type'    => 'success ld-alert-certificate',
                            'icon'    => 'certificate',
                            'message' => __( 'You\'ve earned a certificate!', 'learndash' ),
                            'button'  => array(
                                'url'    => $atts['cert_url'],
                                'icon'   => 'download',
                                'label'  => __( $atts['label'] , 'learndash' ),
                                'target' => '_blank', //'_new',
                            ),
                        ),
                        false
                    );
                } else {
                    $cert_button_html = '<a href="' . esc_url( $atts['cert_url'] ) . '"' .
                        ( ! empty( $atts['class'] ) ? ' class="' . esc_attr( $atts['class'] ) : '' ) . '"' .
                        ( ! empty( $atts['id'] ) ? ' id="' . esc_attr( $atts['id'] ) . '"' : '' ) .
                        '>';

                    if ( ! empty( $atts['label'] ) ) {
                        $cert_button_html .= do_shortcode( $atts['label'] );
                    }

                    $cert_button_html .= '</a>';
                }
            }
            return $cert_button_html;
        }

    }
}
