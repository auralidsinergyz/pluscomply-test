<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class ClonePostsPagesLearnDashContents
 * @package uncanny_pro_toolkit
 */
class ClonePostsPagesLearnDashContents extends toolkit\Config implements toolkit\RequiredFunctions {
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
			add_filter( 'post_row_actions', array( __CLASS__, 'add_clone_to_action_rows' ), 10, 2 );
			add_filter( 'page_row_actions', array( __CLASS__, 'add_clone_to_action_rows' ), 10, 2 );
			add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'uncanny_clone_post_publish_box' ) );
			add_action( 'admin_action_clone_learndash_quiz_contents', array( __CLASS__, 'uncanny_clone_quiz' ) );
			add_action( 'admin_action_clone_post_contents', array( __CLASS__, 'uncanny_clone_contents' ) );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Duplicate Pages & Posts', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/duplicate-pages-posts/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Easily clone pages, posts, LearnDash courses, lessons, topics, quizzes and more. This plugin handles quiz duplication properly.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-clone"></i><span class="uo_pro_text">PRO</span>';

		$category = 'wordpress';
		$type     = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'category'         => $category,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => false, // OR
			//'settings'         => self::get_class_settings( $class_title ),
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
	 * Add Clone link to Publish Box of WordPress Edit screen
	 */
	public static function uncanny_clone_post_publish_box() {
		$post = get_post( absint( $_GET['post'] ) );
		if ( 'sfwd-assignment' === $post->post_type || ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && 'product' === $post->post_type ) ) {
			return;
		}

		?>
		<div class="misc-pub-section misc-pub-clone" id="Clone">
			<?php
			if ( "sfwd-quiz" === $post->post_type ) {
				$action = "?action=clone_learndash_quiz_contents&return_to_post=true&post=" . $post->ID;
			} else {
				$action = "?action=clone_post_contents&return_to_post=true&post=" . $post->ID;
			}
			$post_type_object = get_post_type_object( $post->post_type );

			?>
			<style>
				.misc-pub-clone #post-clone-display::before {
					content: "\f105";
					font: 400 20px/1 dashicons;
					speak: none;
					display: inline-block;
					padding: 0 2px 0 0;
					top: 0;
					left: -1px;
					position: relative;
					vertical-align: top;
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
					text-decoration: none !important;
					color: #82878c;
				}
			</style>
			<span id="post-clone-display"><strong>Action:</strong> </span>
			<a href="<?php echo $action; ?>" class="edit-visibility hide-if-no-js"><span
						aria-hidden="true">Clone this <?php echo $post_type_object->labels->singular_name ?></span></a>
		</div>
		<?php
	}

	/**
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public static function add_clone_to_action_rows( $actions, $post ) {
		//Don't add Clone link to Woocommerce, or Products post type of woocommerce
		if ( 'sfwd-assignment' === $post->post_type || ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && 'product' === $post->post_type ) ) {
			return $actions;
		}
		if ( "sfwd-quiz" === $post->post_type ) {
			$action = "?action=clone_learndash_quiz_contents&post=" . $post->ID;
		} else {
			$action = "?action=clone_post_contents&post=" . $post->ID;
		}
		$post_type_object = get_post_type_object( $post->post_type );
		$can_edit_post    = current_user_can( $post_type_object->cap->edit_post, $post->ID );
		if ( $can_edit_post ) {
			$actions['uncanny_clone'] = '<a href="' . $action . '" title="' .
			                            esc_attr( sprintf( __( 'Clone this %s', 'uncanny-pro-toolkit' ), $post_type_object->labels->singular_name ) ) . '">' .
			                            sprintf( __( 'Clone this %s', 'uncanny-pro-toolkit' ), $post_type_object->labels->singular_name ) .
			                            '</a>';
		}

		return $actions;
	}

	/**
	 *
	 */
	public static function uncanny_clone_quiz() {

		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'clone_learndash_quiz_contents' === $_REQUEST['action'] ) ) ) {
			wp_die( __( 'No quiz to duplicate has been supplied!', 'uncanny-pro-toolkit' ) );
		}

		// Get the original post
		$id   = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
		$post = get_post( absint( $id ) );

		// Copy the post and insert it
		if ( isset( $post ) && $post != null ) {
			self::duplicate_quiz_create_duplicate( $post );

			wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
			exit;

		} else {
			wp_die( esc_attr( __( 'Copy creation failed, could not find original:', 'uncanny-pro-toolkit' ) ) . ' ' . htmlspecialchars( $id ) );
		}

	}

	/**
	 *
	 */
	public static function uncanny_clone_contents() {

		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'clone_post_contents' === $_REQUEST['action'] ) ) ) {
			wp_die( __( 'No quiz to duplicate has been supplied!', 'uncanny-pro-toolkit' ) );
		}

		// Get the original post
		$id   = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
		$post = get_post( absint( $id ) );

		// Copy the post and insert it
		if ( isset( $post ) && $post != null ) {
			$new_id = self::duplicate_contents_create_duplicate( $post, 'draft', $post->post_parent );
			if ( isset( $_GET['return_to_post'] ) ) {
				wp_redirect( admin_url( 'post.php?post=' . $new_id . '&action=edit' ) );
			} else {
				wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
			}
			exit;

		} else {
			wp_die( esc_attr( __( 'Copy creation failed, could not find original:', 'uncanny-pro-toolkit' ) ) . ' ' . htmlspecialchars( $id ) );
		}

	}

	/**
	 * @param        $post
	 * @param string $status
	 * @param string $parent_id
	 *
	 * @return int|\WP_Error
	 */
	public static function duplicate_quiz_create_duplicate( $post, $status = '', $parent_id = '' ) {
		global $wpdb;
		// We don't want to clone revisions
		if ( 'revision' === $post->post_type ) {
			return false;
		}

		if ( 'attachment' !== $post->post_type ) {
			$status = 'draft';
		}

		$new_post_author = get_current_user();

		$new_post                  = array(
			'menu_order'     => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author->ID,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent'    => $new_post_parent = empty( $parent_id ) ? $post->post_parent : $parent_id,
			'post_password'  => $post->post_password,
			'post_status'    => $new_post_status = ( empty( $status ) ) ? $post->post_status : $status,
			'post_title'     => $post->post_title . " (Duplicate Quiz)",
			'post_type'      => $post->post_type,
		);
		$new_post['post_date']     = $new_post_date = $post->post_date;
		$new_post['post_date_gmt'] = get_gmt_from_date( $new_post_date );

		$new_post_id = wp_insert_post( $new_post );

		// add taxonomies
		$categories = wp_get_post_terms( $post->ID, 'category' );
		$tags       = wp_get_post_terms( $post->ID, 'post_tag' );

		$all_categories = array();
		foreach ( $categories as $category ) {
			$all_categories[] = $category->term_id;
		}

		$all_tags = array();
		foreach ( $tags as $tag ) {
			$all_tags[] = $tag->term_id;
		}

		if ( ! empty( ( $all_categories ) ) ) {
			wp_set_object_terms( $new_post_id, $all_categories, 'category' );
		}

		if ( ! empty( ( $all_tags ) ) ) {
			wp_set_object_terms( $new_post_id, $all_tags, 'post_tag' );
		}

		// If the copy is published or scheduled, we have to set a proper slug.
		if ( $new_post_status == 'publish' || $new_post_status == 'future' ) {
			$post_name = wp_unique_post_slug( $post->post_name, $new_post_id, $new_post_status, $post->post_type, $new_post_parent );

			$new_post              = array();
			$new_post['ID']        = $new_post_id;
			$new_post['post_name'] = $post_name;

			// Update the post into the database
			wp_update_post( $new_post );
		}

		$current_quiz_id = get_post_meta( $post->ID, 'quiz_pro_id', true );

		//Clone quiz settings for new quiz
		$wpdb->query( "CREATE TEMPORARY TABLE tmpQuizPro SELECT * FROM " . $wpdb->prefix . "wp_pro_quiz_master WHERE id = $current_quiz_id;" );
		$wpdb->query( "UPDATE tmpQuizPro SET id = NULL WHERE id = $current_quiz_id;" );
		$wpdb->query( "INSERT INTO  " . $wpdb->prefix . "wp_pro_quiz_master  SELECT * FROM tmpQuizPro ;" );
		//Get new Quiz ID
		$new_quiz_id = $wpdb->insert_id;
		$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpQuizPro;" );

		//Clone existing post meta & update quiz ID to new clone quiz
		$current_post_meta = get_post_meta( $post->ID );

		foreach ( $current_post_meta as $key => $value ) {
			$val = maybe_unserialize( $value[0] );
			switch ( $key ):
				case 'quiz_pro_id':
					update_post_meta( $new_post_id, $key, $new_quiz_id );
					break;
				case 'quiz_pro_id_' . $current_quiz_id:
					update_post_meta( $new_post_id, 'quiz_pro_id_' . $new_quiz_id, $new_quiz_id );
					break;
				case 'quiz_pro_primary_' . $current_quiz_id:
					update_post_meta( $new_post_id, 'quiz_pro_primary_' . $new_quiz_id, $new_quiz_id );
					break;
				case '_sfwd-quiz':
					if ( is_array( $val ) ) {
						$sfwd_quiz = array();
						foreach ( $val as $qKey => $qVal ) {
							if ( 'sfwd-quiz_quiz_pro' === $qKey ) {
								$sfwd_quiz['sfwd-quiz_quiz_pro'] = $new_quiz_id;
							} else {
								$sfwd_quiz[ $qKey ] = $qVal;
							}
						}
						update_post_meta( $new_post_id, $key, $sfwd_quiz );
					}
					break;
				default:
					$key = str_replace( "_$current_quiz_id", "_$new_quiz_id", $key );
					$val = str_replace( $current_quiz_id, $new_quiz_id, $val );
					update_post_meta( $new_post_id, $key, $val );
			endswitch;

		}

		if ( \LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Quizzes_Builder', 'shared_questions' ) == 'yes' ) {
			//$quiz_sharing enabled; we don't need to create new questions, just assign previous questions
			$ld_questions = get_post_meta( $post->ID, 'ld_quiz_questions', true );
			update_post_meta( $new_post_id, 'ld_quiz_questions', $ld_questions );
		} else {
			$ld_questions = get_post_meta( $post->ID, 'ld_quiz_questions', true );
			if ( $ld_questions ) {
				$new_ld_questions = [];
				foreach ( $ld_questions as $question_post_id => $quiz_pro_ques_id ) {
					if ( ! empty( $question_post_id ) ) {
						//Clone Question & Answers for new quiz in new LD post type for questions
						$wpdb->query( "CREATE TEMPORARY TABLE tmpQuizProQuestion SELECT * FROM " . $wpdb->posts . " WHERE ID = $question_post_id;" );
						$wpdb->query( "UPDATE tmpQuizProQuestion SET ID = NULL WHERE ID = $question_post_id;" );
						$wpdb->query( "INSERT INTO  " . $wpdb->posts . "  SELECT * FROM tmpQuizProQuestion;" );
						$new_ques_id = $wpdb->insert_id;
						$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpQuizProQuestion;" );

						//Clone Questions for new quiz question in new LD post type for questions
						$wpdb->query( "CREATE TEMPORARY TABLE tmpQuizProQuestionMeta SELECT * FROM " . $wpdb->postmeta . " WHERE post_id = $question_post_id;" );
						$wpdb->query( "UPDATE tmpQuizProQuestionMeta SET meta_id = NULL, post_id = $new_ques_id WHERE post_id = $question_post_id;" );
						$wpdb->query( "INSERT INTO  " . $wpdb->postmeta . "  SELECT * FROM tmpQuizProQuestionMeta;" );
						$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpQuizProQuestionMeta;" );
						$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE post_id = $new_ques_id AND meta_key LIKE 'ld_quiz_%'" );

						//Clone Questions in Quiz Pro
						$wpdb->query( "CREATE TEMPORARY TABLE tmpQuizProQuestionMeta SELECT * FROM " . $wpdb->prefix . "wp_pro_quiz_question WHERE id = $quiz_pro_ques_id;" );
						$wpdb->query( "UPDATE tmpQuizProQuestionMeta SET id = NULL, quiz_id = $new_quiz_id WHERE id = $quiz_pro_ques_id;" );
						$wpdb->query( "INSERT INTO  " . $wpdb->prefix . "wp_pro_quiz_question  SELECT * FROM tmpQuizProQuestionMeta;" );
						$new_pro_ques_id = $wpdb->insert_id;
						$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpQuizProQuestionMeta;" );

						update_post_meta( $new_ques_id, 'ld_quiz_' . $new_post_id, $new_post_id );
						update_post_meta( $new_ques_id, 'question_pro_id', $new_pro_ques_id );
						update_post_meta( $new_ques_id, 'quiz_id', $new_post_id );
						update_post_meta( $new_ques_id, '_sfwd-question', [
							'0'                  => '',
							'sfwd-question_quiz' => $new_post_id,
						] );

						$new_ld_questions[ $new_ques_id ] = $new_pro_ques_id;
					}
				}
				//update new quiz with question ids
				update_post_meta( $new_post_id, 'ld_quiz_questions', $new_ld_questions );
			} else {
				//Clone Question & Answers for new quiz
				$wpdb->query( "CREATE TEMPORARY TABLE tmpQuizPro SELECT * FROM " . $wpdb->prefix . "wp_pro_quiz_question WHERE quiz_id = $current_quiz_id;" );
				$wpdb->query( "UPDATE tmpQuizPro SET id = NULL, quiz_id = $new_quiz_id WHERE quiz_id = $current_quiz_id;" );
				$wpdb->query( "INSERT INTO  " . $wpdb->prefix . "wp_pro_quiz_question  SELECT * FROM tmpQuizPro ;" );
				$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpQuizPro;" );
			}

		}

		return $new_post_id;
	}

	/**
	 * @param        $post
	 * @param string $status
	 * @param string $parent_id
	 *
	 * @return int|\WP_Error
	 */
	public static function duplicate_contents_create_duplicate( $post, $status = '', $parent_id = '' ) {
		global $wpdb;
		// We don't want to clone revisions
		if ( 'revision' === $post->post_type ) {
			return false;
		}

		if ( 'attachment' !== $post->post_type ) {
			$status = 'draft';
		}

		$new_post_author = get_current_user();

		$new_post = array(
			'menu_order'     => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author->ID,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent'    => $new_post_parent = empty( $parent_id ) ? $post->post_parent : $parent_id,
			'post_password'  => $post->post_password,
			'post_status'    => $new_post_status = ( empty( $status ) ) ? $post->post_status : $status,
			'post_title'     => $post->post_title . " (Duplicate)",
			'post_type'      => $post->post_type,
			'post_date'      => $post->post_date,
			'post_date_gmt'  => get_gmt_from_date( $post->post_date ),
		);

		$new_post_id = wp_insert_post( $new_post );

		// If the copy is published or scheduled, we have to set a proper slug.
		if ( 'publish' === $new_post_status || 'future' === $new_post_status ) {
			$post_name = wp_unique_post_slug( $post->post_name, $new_post_id, $new_post_status, $post->post_type, $new_post_parent );

			$new_post              = array();
			$new_post['ID']        = $new_post_id;
			$new_post['post_name'] = $post_name;

			// Update the post into the database
			wp_update_post( $new_post );
		}

		//Clone existing post meta & update quiz ID to new post / page / custom post type
		$current_post_meta = get_post_meta( $post->ID );

		if ( 'groups' === $post->post_type ) {
			//copy courses from cloned group
			if ( function_exists( 'learndash_group_enrolled_courses' ) ) {
				$courses = learndash_group_enrolled_courses( $post->ID );
				if ( ! empty( $courses ) ) {
					learndash_set_group_enrolled_courses( $new_post_id, $courses );
				}
			}
			//copy group leaders from cloned group
			if ( function_exists( 'learndash_get_groups_administrator_ids' ) ) {
				$group_leaders = learndash_get_groups_administrator_ids( $post->ID, true );
				if ( ! empty( $group_leaders ) ) {
					learndash_set_groups_administrators( $new_post_id, $group_leaders );
				}
			}
		}

		foreach ( $current_post_meta as $key => $value ) {
			$val = maybe_unserialize( $value[0] );
			// Dont set enrolled users
			if ( 'course_access_list' === $key ) {
				continue;
			}

			if ( '_sfwd-courses' === $key ) {
				if ( isset( $val['sfwd-courses_course_access_list'] ) ) {
					$val['sfwd-courses_course_access_list'] = '';
				}
			}

			switch ( $key ):
				/**
				 * Changes if Groups plugin is active!
				 * Sept 25,2019, issue happened on PE Club site
				 */
				case '_ulgm_code_group_id':
					$code_group_id = $val;
					if ( class_exists( 'uncanny_learndash_groups\\InitializePlugin' ) ) {
						if ( ! class_exists( 'uncanny_learndash_groups\\Database' ) ) {
							include_once( \uncanny_learndash_groups\Utilities::get_include( 'database.php' ) );
						}
						$codes_group_tbl = \uncanny_learndash_groups\SharedFunctions::$db_group_tbl;
						//Copy code group details
						$new_order_id      = \uncanny_learndash_groups\Database::get_random_order_number();
						$number_of_codes   = get_post_meta( $post->ID, '_ulgm_total_seats', true );
						$codes             = \uncanny_learndash_groups\SharedFunctions::generate_random_codes( $number_of_codes );
						$results           = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}$codes_group_tbl WHERE ID = $code_group_id" );
						$attr              = array(
							'user_id'    => $results->user_id,
							'order_id'   => $new_order_id,
							'group_id'   => $new_post_id,
							'group_name' => $post->post_title,
							'qty'        => $number_of_codes,
						);
						$new_code_group_id = \uncanny_learndash_groups\Database::add_codes( $attr, $codes );
						update_post_meta( $new_post_id, $key, $new_code_group_id );
						update_post_meta( $new_post_id, '_ulgm_is_custom_group_created', 'yes' );

						//Fix if group leader is part of group and/or takes up seat
						$group_leaders = learndash_get_groups_administrator_ids( $new_post_id, true );
						if ( count( $group_leaders ) ) {
							foreach ( $group_leaders as $group_leader ) {
								$rest_api  = new \uncanny_learndash_groups\RestApiEndPoints();
								$user      = get_user_by( 'ID', $group_leader );
								$user_data = array(
									'user_email' => $user->user_email,
									'user_id'    => $user->ID,
								);

								$status = 'redeemed';

								$is_member = \uncanny_learndash_groups\SharedFunctions::is_user_already_member_of_group( $user->ID, $new_post_id );
								if ( 'yes' !== get_option( 'do_not_add_group_leader_as_member', 'no' ) && 'no' === $is_member ) {
									$rest_api->add_existing_user( $user_data, true, $new_post_id, $new_order_id, $status, false );
								}
							}
						}
						//
					}
					break;
				default:
					update_post_meta( $new_post_id, $key, $val );
			endswitch;
		}

		$get_tags_terms_cats = $wpdb->get_var( "SELECT COUNT(object_id) AS total FROM $wpdb->term_relationships WHERE object_id = " . $post->ID );
		if ( $get_tags_terms_cats > 0 ) {
			//Clone quiz settings for new quiz
			$wpdb->query( "DELETE FROM  " . $wpdb->term_relationships . " WHERE object_id = $new_post_id;" );
			$wpdb->query( "CREATE TEMPORARY TABLE tmpCopyCats SELECT * FROM " . $wpdb->term_relationships . " WHERE object_id = " . $post->ID . ";" );
			$wpdb->query( "UPDATE tmpCopyCats SET object_id = $new_post_id WHERE object_id = $post->ID;" );
			$wpdb->query( "INSERT INTO  " . $wpdb->term_relationships . "  SELECT * FROM tmpCopyCats ;" );
			$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpCopyCats;" );

		}

		if ( 'sfwd-question' === $post->post_type ) {
			$quiz_pro_ques_id = reset( $current_post_meta['question_pro_id'] );

			//Clone Questions in Quiz Pro
			$wpdb->query( "CREATE TEMPORARY TABLE tmpQuizProQuestionMeta SELECT * FROM " . $wpdb->prefix . "wp_pro_quiz_question WHERE id = $quiz_pro_ques_id;" );
			$wpdb->query( "UPDATE tmpQuizProQuestionMeta SET id = NULL, quiz_id = $new_post_id WHERE id = $quiz_pro_ques_id;" );
			$wpdb->query( "INSERT INTO  " . $wpdb->prefix . "wp_pro_quiz_question  SELECT * FROM tmpQuizProQuestionMeta;" );
			$new_pro_ques_id = $wpdb->insert_id;
			$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpQuizProQuestionMeta;" );

			update_post_meta( $new_post_id, 'question_pro_id', $new_pro_ques_id );
		}

		if ( class_exists( 'LDLMS_Course_Steps' ) ) {
			if ( \LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Courses_Builder', 'enabled' ) == 'yes' ) {
				// Rebuild Course Steps
				$course_steps = new \LDLMS_Course_Steps( $new_post_id );
				$course_steps->load_steps();
				//$course_steps->build_steps();
				$course_steps->set_step_to_course();
			}
		}


		return $new_post_id;
	}
}