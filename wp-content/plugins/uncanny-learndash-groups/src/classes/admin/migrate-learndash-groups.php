<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class MigrateLearndashGroups
 *
 * @package uncanny_learndash_groups
 */
class MigrateLearndashGroups {

	/**
	 * MigrateLearndashGroups constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'setup_groups_upgrade' ) );
		add_action(
			'upgrade_learndash_groups',
			array(
				$this,
				'upgrade_learndash_groups',
			)
		);
		add_action( 'admin_init', array( $this, 'upgrade_learndash_group' ) );
		add_action( 'init', array( $this, 'reconcile_learndash_group' ) );

	}

	/**
	 *
	 */
	public function setup_groups_upgrade() {
		if ( ( ulgm_filter_has_var( 'wpnonce' ) && wp_verify_nonce( ulgm_filter_input( 'wpnonce' ), 'ulgm' ) ) && ( ulgm_filter_has_var( 'migrate' ) && 'yes' === ulgm_filter_input( 'migrate' ) ) ) {
			wp_schedule_single_event( time() + 15, 'upgrade_learndash_groups' );
			add_action(
				'admin_notices',
				array(
					$this,
					'admin_notice__success',
				)
			);
		}
	}

	/**
	 *
	 */
	public function admin_notice__success() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'Upgrade is processing in background!', 'uncanny-learndash-groups' ); ?></p>
		</div>
		<?php
	}

	/**
	 *
	 */
	public function upgrade_learndash_groups() {

		$groups                 = new \WP_Query(
			array(
				'post_type'      => 'groups',
				'posts_per_page' => 999,
				'meta_key'       => SharedFunctions::$code_group_id_meta_key,
				'meta_compare'   => 'NOT EXISTS',
			)
		);
		$upgrade_in_single_pass = 50;
		$groups_upgraded        = 0;
		if ( $groups->posts ) {
			$total_groups_to_upgrade = count( $groups->posts );
			foreach ( $groups->posts as $post ) {
				if ( $this->process_upgrade( $post ) ) {
					$groups_upgraded ++;
				}
				$groups_upgraded ++;
				if ( $groups_upgraded >= $upgrade_in_single_pass && $total_groups_to_upgrade > $upgrade_in_single_pass ) {
					wp_schedule_single_event( time() + 30, 'upgrade_learndash_groups' );
					break;
				}
			}
		}
	}

	/**
	 *
	 */
	public function upgrade_learndash_group() {
		if ( ! ulgm_filter_has_var( 'migrate_one' ) ) {
			return;
		}
		if ( empty( ulgm_filter_input( 'migrate_one' ) ) ) {
			return;
		}
		if ( ! ulgm_filter_has_var( 'wpnonce' ) ) {
			return;
		}
		if ( ! wp_verify_nonce( ulgm_filter_input( 'wpnonce' ), 'ulgm' ) ) {
			return;
		}

		$post = get_post( absint( ulgm_filter_input( 'migrate_one' ) ) );
		if ( $this->process_upgrade( $post ) ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'migrate_one',
						'wpnonce',
					)
				)
			);
			die();
		}

		wp_safe_redirect( remove_query_arg( array( 'migrate_one', 'wpnonce' ) ) );
		die();
	}

	/**
	 * @param $post
	 *
	 * @return bool
	 */
	public function process_upgrade( $post ) {
		$group_id        = $post->ID;
		$group_leader_id = 0;
		$gl_users        = array();
		$seat_users      = array();
		$group_leaders   = LearndashFunctionOverrides::learndash_get_groups_administrators( $group_id );
		$group_users     = LearndashFunctionOverrides::learndash_get_groups_users( $group_id );

		if ( $group_leaders ) {
			foreach ( $group_leaders as $gl ) {
				$gl_users[ $gl->ID ] = array(
					'ID'         => $gl->ID,
					'user_email' => $gl->user_email,
					'role'       => 'group_leader',
				);
				if ( 0 === $group_leader_id ) {
					$group_leader_id = $gl->ID;
				}
			}
		}

		if ( $group_users ) {
			foreach ( $group_users as $user ) {
				if ( ! key_exists( $user->ID, $seat_users ) ) {
					$seat_users[ $user->ID ] = array(
						'ID'         => $user->ID,
						'user_email' => $user->user_email,
						'role'       => 'user',
					);
				}
			}
		}

		if ( empty( $group_leaders ) && empty( $group_users ) ) {
			$number_of_seats = 0;
		} else {
			$diff            = array_intersect( array_keys( $seat_users ), array_keys( $gl_users ) );
			$number_of_seats = count( $diff );
		}
		$order_id      = ulgm()->group_management->get_random_order_number();
		$attr          = array(
			'user_id'    => $group_leader_id,
			'order_id'   => $order_id,
			'group_id'   => $group_id,
			'group_name' => $post->post_title,
			'qty'        => $number_of_seats,
		);
		$code_group_id = ulgm()->group_management->add_code_group( $attr );
		if ( $number_of_seats > 0 ) {
			$codes = ulgm()->group_management->generate_random_codes( $number_of_seats );
		} else {
			$codes = array();
		}
		$code_group_id = ulgm()->group_management->add_codes( $attr, $codes, $code_group_id );

		update_post_meta( $group_id, '_ulgm_is_custom_group_created', 'yes' );
		update_post_meta( $group_id, '_ulgm_is_upgraded', 'yes' );
		update_post_meta( $group_id, '_ulgm_total_seats', $number_of_seats );
		update_post_meta( $group_id, SharedFunctions::$code_group_id_meta_key, $code_group_id );
		update_user_meta( $group_leader_id, '_ulgm_custom_order_id', $order_id );
		if ( empty( $seat_users ) && empty( $gl_users ) ) {
			return true;
		}

		foreach ( $seat_users as $user ) {

			$user_data = array(
				'user_email' => $user['user_email'],
				'user_id'    => $user['ID'],
			);
			if ( 'group_leader' === $user['role'] ) {
				$status = SharedFunctions::$redeem_status;
			} else {
				$status = SharedFunctions::$not_started_status;
			}

			Group_Management_Helpers::add_existing_user( $user_data, true, $group_id, $order_id, $status, false, true );
		}

		return true;
	}

	/**
	 *
	 */
	public function reconcile_learndash_group() {
		if ( ! ulgm_filter_has_var( 'reconcile_seat' ) ) {
			return;
		}

		if ( ! ulgm_filter_has_var( 'wpnonce' ) ) {
			return;
		}
		if ( ! wp_verify_nonce( ulgm_filter_input( 'wpnonce' ), 'ulgm' ) ) {
			return;
		}

		if ( empty( ulgm_filter_input( 'reconcile_seat' ) ) ) {
			return;
		}
		$group_id = absint( ulgm_filter_input( 'reconcile_seat' ) );
		LearndashGroupsPostEditAdditions::update_group_seat_counts( $group_id, true );

		wp_safe_redirect( remove_query_arg( array( 'reconcile_seat', 'wpnonce' ) ) );
		die();
	}
}
