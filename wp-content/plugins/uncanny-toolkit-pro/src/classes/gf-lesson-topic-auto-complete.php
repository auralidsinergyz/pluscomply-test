<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class GfLessonTopicAutoComplete
 * @package     uncanny_pro_toolkit
 * @since       2.0.5 Combined  `gform_pre_render` for both `mark_complete_button_related_settings` and `add_hidden_course_lesson_topic`
 *
 */
class GfLessonTopicAutoComplete extends toolkit\Config implements toolkit\RequiredFunctions {
	public static $auto_completed_post_types = array( 'sfwd-lessons', 'sfwd-topic' );
	public static $show_mark_complete = 0;

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
			/* ADD FILTERS ACTIONS FUNCTION */
			add_filter( 'gform_tooltips', array( __CLASS__, 'add_mark_complete_tooltip' ), 15, 2 );
			add_filter( 'gform_form_settings', array( __CLASS__, 'add_mark_complete_setting' ), 15, 2 );
			add_filter( 'gform_pre_form_settings_save', array( __CLASS__, 'save_mark_complete_setting' ), 15 );
			//add_filter( 'gform_pre_render', array( __CLASS__, 'mark_complete_button_related_settings' ), 22 );
			add_filter( 'gform_pre_render', array( __CLASS__, 'add_hidden_course_lesson_topic' ), 22 );
			add_filter( 'gform_pre_validation', array( __CLASS__, 'add_hidden_course_lesson_topic' ), 22 );
			add_filter( 'gform_pre_submission_filter', array( __CLASS__, 'add_hidden_course_lesson_topic' ), 22 );
			add_action( 'gform_after_submission', array( __CLASS__, 'maybe_mark_lesson_topic_complete' ), 15, 2 );
			//add_filter( 'gform_notification', array( __CLASS__, 'change_notification_email' ), 10, 3 );
			add_action( 'init', array( __CLASS__, 'is_save_and_continue' ), 99 );
			add_filter( 'learndash_mark_complete', array( __CLASS__, 'remove_mark_complete_button_focus' ), 10, 2 );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Autocomplete Lessons & Topics on Gravity Form Submission', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/gravity-forms-auto-completes-lessons-topics/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Automatically mark LearnDash lessons and topics as completed when the user submits Gravity Forms.', 'uncanny-pro-toolkit' );

		/* Icon as text - max four characters wil fit */
		$icon_styles = 'width: 40px;  padding-top: 5px; padding-left: 9px;';
		$class_icon  = '<img style="' . $icon_styles . '" src="' . self::get_admin_media( 'gravity-forms-icon-white.png' ) . '" />';

		$category = 'learndash';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => self::get_class_settings( $class_title ),
			'icon'             => $class_icon,
		);

	}

	/**
	 * HTML for modal to create settings
	 *
	 * @param String
	 *
	 * @return array || string Return either false or settings html modal
	 *
	 */
	public static function get_class_settings( $class_title ) {

		// Create options
		$options = array(

			array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Hide the Mark Complete button if the user has a previous entry', 'uncanny-pro-toolkit' ),
				'option_name' => 'uo-lesson-topic-hide-button',
			),
		);

		// Build html
		$html = self::settings_output(
			array(
				'class'   => __CLASS__,
				'title'   => $class_title,
				'options' => $options,
			) );

		return $html;
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

		/* Check for gravity forms */
		if ( ! class_exists( 'GFFormsModel' ) ) {
			return 'Plugin: Gravity Forms';

		}

		return true;
	}

	/**
	 * @param $settings
	 * @param $form
	 *
	 * @return array
	 */
	public static function add_mark_complete_setting( $settings, $form ) {

		/*if ( empty( $form ) && ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) ) {
			$form = \GFAPI::get_form( $_GET['id'] );
		}*/

		$checked      = ( rgar( $form, 'mark_complete_settings' ) ) ? ' checked="checked" ' : '';
		$new_settings = array(
			'LearnDash Mark Lesson & Topic Complete' => array(
				'mark_complete_settings' =>
					'<tr>
                    <th> Mark Lesson/Topic Complete ' . gform_tooltip( 'mark_complete_tooltip', '', true ) . '</th>                  
                    <td><input type="checkbox" value="1" ' . $checked . ' name="mark_complete_settings" id="mark_complete_settings"> <label for="mark_complete_settings">Enable to Mark Lesson/Topic Autocomplete</label></td>
                </tr>',
			),
		);

		$settings = array_merge( $new_settings, $settings );

		return $settings;
	}

	/**
	 * @param $form
	 *
	 * @return mixed
	 */
	public static function save_mark_complete_setting( $form ) {
		$form['mark_complete_settings'] = rgpost( 'mark_complete_settings' );

		return $form;
	}

	/**
	 * @param $tooltips
	 *
	 * @return mixed
	 */
	public static function add_mark_complete_tooltip( $tooltips ) {
		$tooltips['mark_complete_tooltip'] = '<h6>' . __( 'Mark Lesson / Topic complete', 'uncanny-pro-toolkit' ) . '</h6>' . __( 'When enabled, Autocomplete Pro Module will attempt to mark lesson or topic complete if this form is the last thing in it. It will also attempt to remove Mark Complete button.', 'uncanny-pro-toolkit' );

		return $tooltips;
	}

	/**
	 * @param $entry
	 * @param $form
	 */
	public static function maybe_mark_lesson_topic_complete( $entry, $form ) {
		//self::trace_logs( $entry, 'Entry', 'entry' );
		//self::trace_logs( $form, 'Form', 'entry' );
		if ( key_exists( 10001, $entry ) ) {
			$course_id = $entry[10001];
			$lesson_id = $entry[10002];
			$topic_id  = $entry[10003];
			$result    = false;
			$post      = array();
			$user      = wp_get_current_user();
			if ( 0 !== (int) $lesson_id ) {
				$post   = get_post( $lesson_id );
				$result = self::maybe_learndash_mark_complete( $post );

			} elseif ( 0 !== (int) $topic_id ) {
				$post   = get_post( $lesson_id );
				$result = self::maybe_learndash_mark_complete( $post );
			}
			if ( true === $result ) {
				if ( 'sfwd-lessons' === $post->post_type ) {
					if ( ! learndash_is_lesson_complete( get_current_user_id(), $post->ID ) ) {
						learndash_process_mark_complete( $user->ID, $post->ID );
					}
				} elseif ( 'sfwd-topic' === $post->post_type ) {
					if ( ! learndash_is_topic_complete( get_current_user_id(), $post->ID ) ) {
						learndash_process_mark_complete( $user->ID, $post->ID );
					}
				}

				$next_post_url = learndash_next_post_link('',true,$post);
				wp_safe_redirect($next_post_url);

			}
		}
	}

	/**
	 * @param $form
	 *
	 * @return mixed
	 */
	public static function add_hidden_course_lesson_topic( $form ) {
		global $post;
		global $learndash_post_types;
		$form_id = $form['id'];
		$user    = wp_get_current_user()->ID; // current logged in user ID
		$checked = ( rgar( $form, 'mark_complete_settings' ) ) ? 1 : 0;
		//$form    = \GFAPI::get_form( $form_id );

		if ( in_array( $post->post_type, $learndash_post_types, true ) && 1 === ( int ) $checked ) {
			$course_id = 0;
			$lesson_id = 0;
			$topic_id  = 0;
			if ( 'sfwd-courses' === $post->post_type ) {
				$course_id = $post->ID;
			}
			if ( 'sfwd-lessons' === $post->post_type ) {
				$course_id = learndash_get_course_id( $post->ID );
				$lesson_id = $post->ID;
			}
			if ( 'sfwd-topic' === $post->post_type ) {
				$course_id = learndash_get_course_id( $post->ID );
				$topic_id  = $post->ID;
			}

			$props = array(
				'id'           => 10001,
				'label'        => 'Course ID',
				'adminLabel'   => 'Course ID',
				'type'         => 'hidden',
				'defaultValue' => $course_id,
				'formId'       => $form['id'],
			);
			$field = \GF_Fields::create( $props );
			array_push( $form['fields'], $field );

			$props = array(
				'id'           => 10002,
				'label'        => 'Lesson ID',
				'adminLabel'   => 'Lesson ID',
				'type'         => 'hidden',
				'defaultValue' => $lesson_id,
				'formId'       => $form['id'],
			);
			$field = \GF_Fields::create( $props );
			array_push( $form['fields'], $field );

			$props = array(
				'id'           => 10003,
				'label'        => 'Topic ID',
				'adminLabel'   => 'Topic ID',
				'type'         => 'hidden',
				'defaultValue' => $topic_id,
				'formId'       => $form['id'],
			);
			$field = \GF_Fields::create( $props );
			array_push( $form['fields'], $field );

			$lead_id = self::get_lead_id( $user, $form_id ); // checking if there's any lead available for $user for $form_id.

			if ( ! empty( $lead_id ) && 'sfwd-lessons' === $post->post_type ) {
				if ( true === self::maybe_learndash_mark_complete( $post ) ) {
					self::$show_mark_complete = 1;
				} else {
					self::$show_mark_complete = 1;
				}
			} elseif ( ! empty( $lead_id ) && 'sfwd-topic' === $post->post_type ) {
				if ( true === self::maybe_learndash_mark_complete( $post ) ) {
					self::$show_mark_complete = 1;
				} else {
					self::$show_mark_complete = 1;
				}
			} else {
				if ( 1 === (int) $checked ) {
					self::$show_mark_complete = 0;
				} else {
					self::$show_mark_complete = 1;
				}
			}

			add_filter( 'learndash_mark_complete', array( __CLASS__, 'remove_mark_complete_button' ), 100, 2 );
		}

		return $form;
	}


	/**
	 * @param $post
	 *
	 * @return bool
	 */
	public static function maybe_learndash_mark_complete( $post ) {

		$current_user = wp_get_current_user();
		$userid       = $current_user->ID;

		if ( isset( $_POST['sfwd_mark_complete'] ) && isset( $_POST['post'] ) && $post->ID === $_POST['post'] ) {
			return false;
		}

		if ( 'sfwd-lessons' === $post->post_type ) {
			$progress = learndash_get_course_progress( null, $post->ID );

			if ( ! empty( $progress['this']->completed ) ) {
				if ( ! apply_filters( 'learndash_previous_step_completed', false, $progress['this']->ID, $current_user->ID ) ) {
					return false;
				}
			}

			if ( ! empty( $progress['prev'] ) && empty( $progress['prev']->completed ) && learndash_lesson_progression_enabled() ) {
				if ( ! apply_filters( 'learndash_previous_step_completed', false, $progress['prev']->ID, $current_user->ID ) ) {
					return false;
				}
			}

			if ( ! learndash_lesson_topics_completed( $post->ID ) ) {
				if ( ! apply_filters( 'learndash_previous_step_completed', false, $post->ID, $current_user->ID ) ) {
					return false;
				}
			}
		}

		if ( 'sfwd-topic' === $post->post_type ) {
			$progress = learndash_get_course_progress( null, $post->ID );

			if ( ! empty( $progress['this']->completed ) ) {
				if ( ! apply_filters( 'learndash_previous_step_completed', false, $progress['this']->ID, $current_user->ID ) ) {
					return false;
				}
			}

			if ( ! empty( $progress['prev'] ) && empty( $progress['prev']->completed ) && learndash_lesson_progression_enabled() ) {
				if ( ! apply_filters( 'learndash_previous_step_completed', false, $progress['prev']->ID, $current_user->ID ) ) {
					return false;
				}
			}

			if ( learndash_lesson_progression_enabled() ) {
				$lesson_id = learndash_get_setting( $post, 'lesson' );
				$lesson    = get_post( $lesson_id );

				if ( ! is_previous_complete( $lesson ) ) {
					if ( ! apply_filters( 'learndash_previous_step_completed', false, $lesson->ID, $current_user->ID ) ) {
						return false;
					}
				}
			}
		}

		if ( lesson_hasassignments( $post ) ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Filter HTML output to mark course complete button
	 *
	 * @param  string $return
	 * @param object  $post
	 *
	 * @return string $return
	 */
	public static function remove_mark_complete_button( $return, $post ) {

		/* LD 2.3 removed the next if until mark complete is clicked, since we removed the mark complete button
		 * there is no way to progress. LD allows to added the next to be added back in version 2.3.0.2. Let's use it!
		*/
		$settings_value = self::get_settings_value( 'uo-lesson-topic-hide-button', __CLASS__ );
		if ( ! empty( $settings_value ) ) {
			self::$show_mark_complete = 0;
		}

		add_filter( 'learndash_show_next_link', array( __CLASS__, 'learndash_show_next_link_progression' ), 10, 3 );

		$post_type = $post->post_type;
		if ( 0 === (int) self::$show_mark_complete ) {
			return '';
		}

		if ( true === self::maybe_learndash_mark_complete( $post ) ) {
			// Remove mark complete button if its a lesson or topic
			if ( in_array( $post_type, self::$auto_completed_post_types, true ) ) {
				if ( 1 !== (int) self::$show_mark_complete ) {
					return '';
				}
			}
		}

		return $return;

	}


	/*
		 * Filter to bring back next navigation links
		 *
		 * @param bool $show_next_link
		 * @param int $user_id
		 * @param int $post_id
		 *
		 * return bool
		 */
	/**
	 * @param bool $show_next_link
	 * @param int  $user_id
	 * @param int  $post_id
	 *
	 * @return bool
	 */
	public static function learndash_show_next_link_progression( $show_next_link = false, $user_id = 0, $post_id = 0 ) {
		return true;
	}


	/**
	 * @param $user_id
	 * @param $form_id
	 *
	 * @return bool
	 */
	public static function get_lead_id( $user_id, $form_id ) {
		global $wpdb;
		if ( method_exists( 'GFFormsModel', 'get_entry_table_name' ) ) {
			$db_table_name = \GFFormsModel::get_entry_table_name();
		} elseif ( method_exists( 'GFFormsModel', 'get_lead_table_name' ) ) {
			$db_table_name = \GFFormsModel::get_lead_table_name();
		} else {
			return false;
		}

		$lead_id = $wpdb->get_row( "SELECT id FROM {$db_table_name} WHERE created_by = $user_id AND form_id = $form_id" );

		if ( $lead_id ) {
			return $lead_id->id;
		} else {
			return false;
		}
	}

	/**
	 *
	 */
	public static function is_save_and_continue() {

		if ( isset( $_POST['gform_save'] ) && 1 === absint( $_POST['gform_save'] ) ) {
			self::$show_mark_complete = 0;
			add_filter( 'learndash_mark_complete', array( __CLASS__, 'remove_mark_complete_button' ), 99, 2 );
		}
	}
	
	/**
	 * Standalone filter function for mark complete button
	 *
	 * @param string $return content of current post.
	 * @param object $post   WP Post object.
	 *
	 * @return string
	 */
	public static function remove_mark_complete_button_focus( $return, $post ) {
		
		global $learndash_post_types;
		$form_id = 0;

		if ( function_exists( 'has_blocks' ) ) {
			if ( has_blocks( $post->post_content ) ) {
				$blocks = parse_blocks( $post->post_content );
				foreach ( $blocks as $block ) {
					if ( $block['blockName'] === 'gravityforms/block' ) {
						$form_id = $block['attrs']['formId'];
						break;
					}
				}
			}
		}

		
		if ( has_shortcode( $post->post_content, 'gravityform' ) ) {
			$short_codes = self::attribute_map( $post->post_content );
			if ( ! isset( $short_codes['gravityform'] ) && $form_id == 0 ) {
				return $return;
			}
			
			$form_id = isset( $short_codes['gravityform']['id'] ) ? $short_codes['gravityform']['id'] : $form_id;
			$form_id = isset( $short_codes['gravityform'][0]['id'] ) ? $short_codes['gravityform'][0]['id'] : $form_id;
		}
		
		if ( ! $form_id ) {
			return $return;
		}
		
		
		$form    = \GFAPI::get_form( $form_id );
		$user    = wp_get_current_user()->ID; // current logged in user ID
		$checked = ( rgar( $form, 'mark_complete_settings' ) ) ? 1 : 0;
		
		
		if ( in_array( $post->post_type, $learndash_post_types, TRUE ) && 1 === ( int ) $checked ) {
			$settings_value = self::get_settings_value( 'uo-lesson-topic-hide-button', __CLASS__ );
			if ( ! empty( $settings_value ) ) {
				self::$show_mark_complete = 0;
			}
			
			add_filter( 'learndash_show_next_link', [ __CLASS__, 'learndash_show_next_link_progression' ], 10, 3 );
			
			$post_type = $post->post_type;
			if ( 0 === (int) self::$show_mark_complete ) {
				return '';
			}
			
			if ( TRUE === self::maybe_learndash_mark_complete( $post ) ) {
				// Remove mark complete button if its a lesson or topic
				if ( in_array( $post_type, self::$auto_completed_post_types, TRUE ) ) {
					if ( 1 !== (int) self::$show_mark_complete ) {
						return '';
					}
				}
			}
		}
		
		return $return;
		
	}
	
	/**
	 * short code parsing function with attributes
	 *
	 * @param string $str.
	 * @param string $att.
	 *
	 * @return array
	 */
	private static function attribute_map($str, $att = null) {
		$res = array();
		$reg = get_shortcode_regex();
		preg_match_all('~'.$reg.'~',$str, $matches);
		foreach($matches[2] as $key => $name) {
			$parsed = shortcode_parse_atts($matches[3][$key]);
			$parsed = is_array($parsed) ? $parsed : array();
			
			if(array_key_exists($name, $res)) {
				$arr = array();
				if(is_array($res[$name])) {
					$arr = $res[$name];
				} else {
					$arr[] = $res[$name];
				}
				
				$arr[] = array_key_exists($att, $parsed) ? $parsed[$att] : $parsed;
				$res[$name] = $arr;
				
			} else {
				$res[$name][] = array_key_exists($att, $parsed) ? $parsed[$att] : $parsed;
			}
		}
		
		return $res;
	}
}