<?php

namespace uncanny_learndash_groups;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class LearndashGroupsPostEditAdditions
 *
 * @package uncanny_learndash_groups
 */
class LearndashGroupsPostEditAdditions {

	/**
	 * class constructor
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'add_meta_boxes_groups', array( $this, 'add_metabox' ) );
			add_action( 'save_post', array( $this, 'perform_group_management_actions' ), 999, 2 );
		}

		//remove group related data from custom tables
		add_action( 'delete_post', array( $this, 'remove_related_groups_data' ) );
		// downgrade group
		add_action( 'admin_init', array( $this, 'downgrade_selected_group' ) );
	}

	/**
	 * Adds the meta box.
	 */
	public function add_metabox() {
		global $current_screen;
		if ( isset( $current_screen->post_type ) && 'groups' !== $current_screen->post_type ) {
			// Not groups.. bail
			return;
		}

		add_meta_box(
			'uo-group-management',
			__( 'Uncanny Group Management', 'uncanny-learndash-groups' ),
			array( $this, 'render_metabox' ),
			'groups',
			'advanced',
			'high'
		);

		if ( isset( $current_screen->action ) && 'add' === $current_screen->action ) {
			// New post, ignore these boxes.
			return;
		}

		add_meta_box(
			'uo-group-management-invited-users',
			__( 'Uncanny Group Management - Invited user(s)', 'uncanny-learndash-groups' ),
			array( $this, 'render_invite_metabox' ),
			'groups',
			'advanced',
			'high'
		);
		if ( Utilities::if_woocommerce_active() ) {
			add_meta_box(
				'uo-group-management-related-wooo',
				__( 'Uncanny Group Management - WooCommerce orders', 'uncanny-learndash-groups' ),
				array( $this, 'render_metabox_for_woo' ),
				'groups',
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Renders the meta box.
	 */
	public function render_metabox( $post ) {
		wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );
		$group_id               = $post->ID;
		$render_reconcile_seats = true;
		if ( ! SharedFunctions::is_a_parent_group( $group_id ) && SharedFunctions::is_pool_seats_enabled_for_current_parent_group( $group_id ) ) {
			$render_reconcile_seats = false;
		}
		$code_group_id    = ulgm()->group_management->seat->get_code_group_id( $group_id );
		$upgrade_required = 'no';
		// Echo out the field
		ob_start();
		if ( ! empty( $code_group_id ) ) {
			// Get the location data if its already been entered
			$total_seats     = ulgm()->group_management->seat->total_seats( $group_id );
			$remaining_seats = ulgm()->group_management->seat->remaining_seats( $group_id );
			$enrolled_seats  = (int) ulgm()->group_management->count_users_enrolled_in_group( $group_id ) + (int) ulgm()->group_management->users_invited_in_group( $group_id );

			if ( GroupManagementInterface::is_reconcile_required( $group_id, $total_seats, $enrolled_seats, $remaining_seats ) ) {
				$total_seats     = (int) ulgm()->group_management->seat->total_seats( $group_id );
				$remaining_seats = (int) ulgm()->group_management->seat->remaining_seats( $group_id );
			}
			if ( false === $render_reconcile_seats ) {
				?>
				<p class="attention">
					<strong><?php printf( __( 'This child %1$s is pooling seats from the parent %1$s. Reconcile seat count and seat change functionality are unavailable due to the %1$s hierarchy and pooled seats settings.', 'uncanny-learndash-groups' ), strtolower( \LearnDash_Custom_Label::get_label( 'group' ) ) ); ?></strong>
				</p>
				<?php
			}
			?>
			<div class="sfwd_input " id="sfwd-group_ulgm_total_seats">
				<span class="sfwd_option_label"
					  style="text-align:right;vertical-align:top;">
					<a class="sfwd_help_text_link" style="cursor:pointer;"
					   title="<?php _e( 'Click for help!', 'uncanny-learndash-groups' ); ?>"
					   onclick="toggleVisibility('uo-total-seats');">
						<img src="<?php echo LEARNDASH_LMS_PLUGIN_URL; ?>assets/images/question.png">
						<label
							class="sfwd_label textinput"><?php _e( 'Total seats', 'uncanny-learndash-groups' ); ?></label>
					</a></span><span class="sfwd_option_input">
					<div class="sfwd_option_div">
						<input name="_ulgm_total_seats"
							   id="_ulgm_total_seats"
							   <?php echo false === $render_reconcile_seats ? 'disabled="disabled"' : ''; ?>
							   type="text" size="57"
							   value="<?php echo $total_seats; ?>"
							   style="max-width: 100%;">
					</div>
					<div class="sfwd_help_text_div" style="display:none"
						 id="uo-total-seats">
						<label
							class="sfwd_help_text"><?php _e( 'Total number of seats available to the group administrator for dispersion.', 'uncanny-learndash-groups' ); ?></label>
					</div>
				</span>
				<p style="clear:left"></p>
			</div>
			<div class="sfwd_input " id="sfwd-groups_ulgm_available_seats">
				<span class="sfwd_option_label"
					  style="text-align:right;vertical-align:top;">
					<a class="sfwd_help_text_link" style="cursor:pointer;"
					   title="<?php _e( 'Click for help!', 'uncanny-learndash-groups' ); ?>"
					   onclick="toggleVisibility('uo-remaining-seats');">
						<img src="<?php echo LEARNDASH_LMS_PLUGIN_URL; ?>assets/images/question.png">
						<label
							class="sfwd_label textinput"><?php _e( 'Remaining seats', 'uncanny-learndash-groups' ); ?></label>
					</a></span><span class="sfwd_option_input">
					<div class="sfwd_option_div">
						<input name="_ulgm_remaining_seats"
							   id="_ulgm_remaining_seats" disabled="disabled"
							   type="text"
							   size="57"
							   value="<?php echo $remaining_seats; ?>"
							   style="max-width: 100%;">
					</div>
					<div class="sfwd_help_text_div" style="display:none"
						 id="uo-remaining-seats">
						<label
							class="sfwd_help_text"><?php _e( 'Total number of seats remaining to the group administrator for dispersion.', 'uncanny-learndash-groups' ); ?></label>
					</div>
				</span>
				<p style="clear:left"></p>
			</div>
			<?php
			if ( false !== $render_reconcile_seats ) {
				$link_upgrade         = add_query_arg(
					array(
						'reconcile_seat' => $post->ID,
						'wpnonce'        => wp_create_nonce( 'ulgm' ),
					),
					get_edit_post_link()
				);
				$per_seat_text        = get_option( 'ulgm_per_seat_text', 'Seat' );
				$per_seat_text_plural = get_option( 'ulgm_per_seat_text_plural', 'Seats' );
				?>
				<div class="sfwd_input "
					 id="sfwd-lessons_upgrade_learndash_group">
				<span class="sfwd_option_input" style="width:100%;">
					<div class="sfwd_option_div">
						<a id="reconcile_button"
						   href="<?php echo $link_upgrade; ?>"
						   class="button button-primary"><?php _e( 'Reconcile seat count', 'uncanny-learndash-groups' ); ?></a>
						<a id="reconcile_button_refresh"
						   style="float:right; display:none" href="javascript:;"
						   onclick="window.location.reload(false);"
						   class="button button-secondary"><?php _e( 'Refresh', 'uncanny-learndash-groups' ); ?></a>
					</div>
					<div class="sfwd_help_text_div" style="display:none"
						 id="uo-total-seats-count">
						<label
							class="sfwd_help_text"><?php _e( 'Total number of seats available to the group administrator for dispersion.', 'uncanny-learndash-groups' ); ?></label>
					</div>
				</span>
					<p style="clear:left"></p>
				</div>
				<div id="reconcile_confirmation" style="display: none;">
					<?php
					printf( __( 'This will assign a %1$s to every member of the group that is not currently assigned a %2$s and restore missing %3$s. Continue?', 'uncanny-learndash-groups' ), $per_seat_text, $per_seat_text, $per_seat_text_plural );
					?>
				</div>
				<script>
					jQuery(document).ready(function ($) {
						'use strict'

						///on class click
						$('#reconcile_button').click(function (e) {
							e.preventDefault() ///first, prevent the action
							var targetUrl = $(this).attr('href') ///the original delete call

							///construct the dialog
							$('#reconcile_confirmation').dialog({
								autoOpen: false,
								title: '<?php _e( 'Confirmation', 'uncanny-learndash-groups' ); ?>',
								modal: true,
								buttons: {
									'Yes': function () {
										///if the user confirms, proceed with the original action
										window.location.href = targetUrl
									},
									'Cancel': function () {
										///otherwise, just close the dialog; the delete event was already interrupted
										$(this).dialog('close')
									}
								}
							})

							///open the dialog window
							$('#reconcile_confirmation').dialog('open')
						})
					})
				</script>
				<?php
			}
		} else {
			$upgrade_required = 'yes';
			$link_upgrade     = add_query_arg(
				array(
					'migrate_one' => $post->ID,
					'wpnonce'     => wp_create_nonce( 'ulgm' ),
				),
				get_edit_post_link()
			);
			global $current_screen;
			$class = '';
			if ( isset( $current_screen->action ) && 'add' === $current_screen->action ) {
				// New post, ignore these boxes.
				$class = 'display: none;';
			}

			?>
			<div class="sfwd_input"
				 id="sfwd-lessons_upgrade_learndash_group_add"
				 style="<?php echo $class; ?>">
				<span class="sfwd_option_input">
					<div class="sfwd_option_div">
						<a href="<?php echo $link_upgrade; ?>"
						   id="_updgrade_group"
						   class="button"><?php _e( 'Upgrade group', 'uncanny-learndash-groups' ); ?></a>
					</div>
					<div class="sfwd_help_text_div" style="display:none"
						 id="uo-total-seats">
						<label
							class="sfwd_help_text"><?php _e( 'Total number of seats available to the group administrator for dispersion.', 'uncanny-learndash-groups' ); ?></label>
					</div>
				</span>
				<?php
				$url = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=uncanny-groups#Upgrade-LearnDash-Groups' ), __( 'Settings', 'uncanny-learndash-groups' ) );
				?>
				<p style="clear:left"><?php echo sprintf( '%s %s.', __( 'Clicking this button will enable seat management for this group and enable front end management using Uncanny Groups for LearnDash. To bulk upgrade all groups, use the button near the bottom of Uncanny Groups >', 'uncanny-learndash-groups' ), $url ); ?></p>
			</div>
			<?php
		}
		?>
		<script>
			jQuery(document).ready(() => {
				function update_meta_box() {
					let ur = '<?php echo $upgrade_required; ?>';
					if ('no' === ur) {
						<?php
						self::remove_users_from_group_admin_func( $post->ID );
						?>
						let ts = document.querySelector("#_ulgm_total_seats");
						let rs = document.querySelector("#_ulgm_remaining_seats");
						let ts_val = ts.value;
						let aval = '<?php echo ulgm()->group_management->count_users_enrolled_in_group( $post->ID ) + ulgm()->group_management->users_invited_in_group( $post->ID ); ?>'
						let rs_val = ts_val - aval;
						ts.value = ts_val;
						rs.value = rs_val;
						document.querySelector('#reconcile_button_refresh').style.display = 'block';
					}
					if ('yes' === ur) {
						document.querySelector('#sfwd-lessons_upgrade_learndash_group_add').style.display = 'block';
					}
				}

				let dispatch = wp.data.dispatch('core/edit-post');
				let oldMetaBoxUpdatesSuccess = dispatch.metaBoxUpdatesSuccess;
				dispatch.metaBoxUpdatesSuccess = function (...args) {
					setTimeout(function () {
						update_meta_box();
					}, 500)
					return oldMetaBoxUpdatesSuccess.apply(this, args);
				}
			});
		</script>
		<?php
		echo ob_get_clean();
	}

	/**
	 * Renders the meta box.
	 */
	public function render_invite_metabox( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );

		$group_id      = $post->ID;
		$code_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );

		if ( empty( $code_group_id ) ) {
			return;
		}
		// Echo out the field
		ob_start();

		?>
		<div class="sfwd_input " id="sfwd-lessons_forced_lesson_time">
			<table class="widefat" cellspacing="0"
				   style=" border-spacing: 0px;border-collapse: collapse;width: 100%;max-width: 100%;margin-bottom: 15px;background-color: transparent;text-align: left;">
				<thead>
				<tr style="background: #efefef">
					<th style="font-weight: bold; border: 1px solid #cccccc; padding: 8px;"><?php _e( 'First name', 'uncanny-learndash-groups' ); ?></th>
					<th style="font-weight: bold; border: 1px solid #cccccc; padding: 8px;"><?php _e( 'Last name', 'uncanny-learndash-groups' ); ?></th>
					<th style="font-weight: bold; border: 1px solid #cccccc; padding: 8px;"><?php _e( 'User email', 'uncanny-learndash-groups' ); ?></th>
					<th style="font-weight: bold; border: 1px solid #cccccc; padding: 8px;"><?php _e( 'Key', 'uncanny-learndash-groups' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$invited_users = ulgm()->group_management->users_invited_in_group( $group_id, false );
				$count         = 0;
				if ( $invited_users ) {
					foreach ( $invited_users as $user ) {
						?>
						<tr style="background: <?php echo( ++ $count % 2 ? '#fff' : '#f7f7f7' ); ?>">
							<td style="border: 1px solid #cccccc; padding: 8px;"><?php echo $user->first_name; ?></td>
							<td style="border: 1px solid #cccccc; padding: 8px;"><?php echo $user->last_name; ?></td>
							<td style="border: 1px solid #cccccc; padding: 8px;"><?php echo $user->user_email; ?></td>
							<td style="border: 1px solid #cccccc; padding: 8px;"><?php echo $user->code; ?></td>
						</tr>
						<?php
					}
					?>
				<?php } ?>
				</tbody>
			</table>
			<div class="sfwd_help_text_div" id="uo-invited-users">
				<label
					class="sfwd_help_text"><?php printf( __( 'A %s has been reserved for the users listed above, who have been sent an enrollment key but have not yet created an account.', 'uncanny-learndash-groups' ), strtolower( ulgm()->group_management->seat->get_per_seat_text() ) ); ?></label>
			</div>
			<p style="clear:left"></p>
		</div>
		<?php
		echo ob_get_clean();
	}

	/**
	 * Renders the meta box.
	 */
	public function render_metabox_for_woo( $post ) {

		$group_id      = $post->ID;
		$code_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );
		if ( empty( $code_group_id ) ) {
			return;
		}

		// Echo out the field
		ob_start();
		if ( ! empty( get_post_meta( $post->ID, SharedFunctions::$code_group_downgraded, true ) ) ) {
			$time = get_post_meta( $post->ID, SharedFunctions::$code_group_downgraded . '_time', true );
			if ( ! empty( $time ) ) {
				?>
				<h4><?php echo sprintf( '%s: %s', esc_html__( 'This group was downgraded on', 'uncanny-learndash-groups' ), date_i18n( get_option( 'date_format', $time ) ) ); ?></h4>
				<?php
			}

			return ob_get_clean();
		}

		$get_product = SharedFunctions::get_product_id_from_group_id( $post->ID );
		if ( empty( $get_product ) ) {
			echo esc_html__( 'No orders found.', 'uncanny-learndash-groups' );

			return;
		}
		if ( ! isset( $get_product['product_id'] ) || 0 === absint( $get_product['product_id'] ) ) {
			echo esc_html__( 'No orders found.', 'uncanny-learndash-groups' );

			return;
		}
		$product_id = absint( $get_product['product_id'] );
		$order_id   = absint( $get_product['order_id'] );
		$order      = wc_get_order( $order_id );
		if ( ! $order instanceof \WC_Order ) {
			echo esc_html__( 'No orders found.', 'uncanny-learndash-groups' );

			return;
		}
		$downgrade = '';
		if ( empty( get_post_meta( $post->ID, SharedFunctions::$code_group_downgraded, true ) ) ) {
			$msg       = __( 'Warning: This action cannot be reversed.\r\nDowngrading this group will mean the Group Leader can no longer add seats or courses; the related order will also be unlinked. Other group management functionality will not be affected.', 'uncanny-learndash-groups' );
			$downgrade = sprintf( ' | <a href="%s" onclick="javascript: return confirm(\'%s\')">%s</a>', admin_url( 'admin.php?downgrade-group=true&group-id=' . $post->ID . '&_wpnonce=' . wp_create_nonce( 'Uncanny0wL' ) ), $msg, __( 'Downgrade this group', 'uncanny-learndash-group' ) );
		}
		?>
		<ul>
			<li>
				<?php echo sprintf( '<h4>%s: <a href="%s">#%d %s %s</a> <i>(Status: %s)</i> %s</h4>', esc_html__( 'Main order', 'uncanny-learndash-groups' ), get_edit_post_link( $order_id ), $order_id, $order->get_billing_first_name(), $order->get_billing_last_name(), $order->get_status(), $downgrade ); ?>
			</li>
		</ul>
		<h4><?php echo sprintf( '%s: <a href="%s">%s</a>', esc_html__( 'Group license', 'uncanny-learndash-groups' ), get_edit_post_link( $product_id ), get_the_title( $product_id ) ); ?></h4>
		<?php
		$get_orders = SharedFunctions::get_orders_from_product_id( $product_id, false );
		if ( empty( $get_orders ) ) {
			return ob_get_clean();
		}
		$other_orders = array();
		$order_ids    = array_column( $get_orders, 'OrderID' );
		if ( ( $key = array_search( $order_id, $order_ids ) ) !== false ) {
			unset( $order_ids[ $key ] );
		}
		if ( empty( $order_ids ) ) {
			return ob_get_clean();
		}
		?>
		<h4><?php echo esc_html__( 'Additional group order(s):', 'uncanny-learndash-groups' ); ?></h4>
		<ol>
			<?php
			foreach ( $order_ids as $o_id ) {
				$order_type = get_post_meta( $o_id, 'order_type', true );
				$ordr       = wc_get_order( $o_id );
				if ( ! $ordr instanceof \WC_Order ) {
					continue;
				}
				if ( ! empty( $order_type ) ) {
					// try to assume the type of the order for backward compatibility
					$user = $ordr->get_customer_id();
					$k    = "_ulgm_user_buy_courses_{$user}_order";
					$meta = get_post_meta( $o_id, $k, true );
					if ( $meta && isset( $meta['order_details']['order_id'] ) && is_numeric( $meta['order_details']['order_id'] ) ) {
						$order_type = sprintf( _x( 'Additional %s(s) purchased.', 'LearnDash courses', 'uncanny-learndash-groups' ), strtolower( \LearnDash_Custom_Label::get_label( 'course' ) ) );
					} else {
						$k    = "_ulgm_user_{$user}_order";
						$meta = get_post_meta( $o_id, $k, true );
						if ( $meta && isset( $meta['order_details']['order_id'] ) && is_numeric( $meta['order_details']['order_id'] ) ) {
							$new_qty    = absint( $meta['new_qty'] );
							$order_type = sprintf( _x( 'Additional %1$s %2$s purchased.', 'Group seats count and per seat label', 'uncanny-learndash-groups' ), $new_qty, strtolower( SharedFunctions::get_per_seat_text( $new_qty ) ) );
						}
					}
				}
				if ( empty( $order_type ) ) {
					$other_orders[] = $o_id;
					continue;
				}
				?>
				<li>
					<?php echo sprintf( '<a href="%s">#%d %s %s</a><i>%s</i>', get_edit_post_link( $o_id ), $o_id, $ordr->get_billing_first_name(), $ordr->get_billing_last_name(), ' &mdash; ' . $order_type . '' ); ?>
				</li>
			<?php } ?>
		</ol>
		<?php

		if ( $other_orders ) {
			?>
			<h4><?php echo esc_html__( 'Other orders with the same license:', 'uncanny-learndash-groups' ); ?></h4>
			<ol>
				<?php
				$k     = 1;
				$limit = apply_filters( 'ulgm_other_similar_orders_limit', 15 );
				foreach ( $other_orders as $o_id ) {
					if ( $k <= $limit ) {
						$ordr = wc_get_order( $o_id );
						?>
						<li>
							<?php echo sprintf( '<a href="%s">#%d %s %s</a>', get_edit_post_link( $o_id ), $o_id, $ordr->get_billing_first_name(), $ordr->get_billing_last_name() ); ?>
						</li>
						<?php
					}
					$k ++;
				}
				?>
			</ol>
			<?php
			if ( count( $other_orders ) - $limit > 0 ) {
				$val = count( $other_orders ) - $limit;
				?>
				<p>.</p>
				<p>.</p>
				<p>
					<i><?php printf( _n( '%d additional order', '%d other orders.', $val, 'uncanny-learndash-groups' ), $val ); ?></i>
				</p>
				<?php
			}
		}
		echo ob_get_clean();
	}

	/**
	 * @param $post_id
	 * @param bool $force
	 * @param array $group_users
	 * @param array $group_leaders
	 */
	public static function update_user_redeemed_seat_func( $post_id, $force = false, $group_users = array(), $group_leaders = array() ) {
		$code_group_id = ulgm()->group_management->seat->get_code_group_id( $post_id );
		if ( empty( $code_group_id ) ) {
			return;
		}
		$seat_users          = array();
		$seat_groups_leaders = array();
		if ( ! $force ) {
			$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrators( $post_id, true );
			$group_users   = LearndashFunctionOverrides::learndash_get_groups_users( $post_id, true );
		}
		$group_leaders_dont_use_seat = get_option( 'group_leaders_dont_use_seats', 'no' );
		if ( $group_leaders && 'no' === (string) $group_leaders_dont_use_seat ) {
			foreach ( $group_leaders as $gl ) {
				$seat_groups_leaders[ $gl->ID ] = array(
					'ID'         => $gl->ID,
					'user_email' => $gl->user_email,
					'role'       => 'group_leader',
				);
			}
		}

		if ( $group_users ) {
			foreach ( $group_users as $key => $user ) {
				$seat_users[ $user->ID ] = array(
					'ID'         => $user->ID,
					'user_email' => $user->user_email,
					'role'       => user_can( $user, 'group_leader' ) ? 'group_leader' : 'user',
				);

				if ( user_can( $user, 'group_leader' ) && 'yes' === $group_leaders_dont_use_seat ) {
					unset( $seat_users[ $user->ID ] );
				}
			}
		}

		$number_of_seats = count( $seat_users );
		$existing_seats  = ulgm()->group_management->seat->total_seats( $post_id );
		if ( $number_of_seats > $existing_seats ) {
			$diff      = $number_of_seats - $existing_seats;
			$new_codes = ulgm()->group_management->generate_random_codes( $diff );
			$attr      = array(
				'qty'           => $diff,
				'code_group_id' => $code_group_id,
			);
			ulgm()->group_management->add_additional_codes( $attr, $new_codes );
			update_post_meta( $post_id, '_ulgm_total_seats', $number_of_seats );
		}

		$order_id = ulgm()->group_management->get_order_id_from_group_id( $post_id );

		if ( count( $seat_users ) ) {
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
				$is_member = ulgm()->group_management->is_user_already_member_of_group( $user['ID'], $post_id );
				if ( 'no' === $is_member ) {
					if ( 'group_leader' === $user['role'] && 'yes' === (string) get_option( 'group_leaders_dont_use_seats', 'no' ) ) {
						//If group leader do not take seat checkbox is checked,
						//and this user role is group_leader, skip it so that
						//they do not take seat
						continue;
					} else {
						Group_Management_Helpers::add_existing_user( $user_data, true, $post_id, $order_id, $status, false );
					}
				}
			}
		}
	}

	/**
	 * @param $post_id
	 * @param bool $force
	 * @param array $group_leaders
	 * @param array $group_users
	 */
	public static function remove_users_from_group_admin_func( $post_id, $force = false, $group_leaders = array(), $group_users = array() ) {
		if ( ! SharedFunctions::is_a_parent_group( $post_id ) ) {
			return;
		}
		$codes_group_id = ulgm()->group_management->seat->get_code_group_id( $post_id );
		global $wpdb;
		$codes_users      = array();
		$sql              = $wpdb->prepare(
			"SELECT student_id, ID FROM {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . "
		                        WHERE code_status != %s
		                        AND student_id REGEXP '^-?[0-9]+$'
		                        AND group_id = %d",
			SharedFunctions::$available_status,
			$codes_group_id
		);
		$code_group_users = $wpdb->get_results( $sql );
		if ( empty( $code_group_users ) ) {
			return;
		}
		foreach ( $code_group_users as $u ) {
			$codes_users[ $u->student_id ] = $u->ID;
		}

		$seat_users = array();
		if ( ! $force ) {
			$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrators( $post_id );
			$group_users   = LearndashFunctionOverrides::learndash_get_groups_users( $post_id );
		}
		if ( SharedFunctions::is_a_parent_group( $post_id ) && SharedFunctions::is_pool_seats_enabled_for_current_parent_group( $post_id, false ) ) {
			$group_children = learndash_get_group_children( $post_id );
			$children_users = array();
			$children_gls   = array();
			if ( ! empty( $group_children ) ) {
				foreach ( $group_children as $child_group_id ) {
					$children_users = array_merge( $children_users, LearndashFunctionOverrides::learndash_get_groups_users( $child_group_id ) );
					$children_gls   = array_merge( $children_users, LearndashFunctionOverrides::learndash_get_groups_administrators( $child_group_id ) );
				}
			}
			$group_users   = array_merge( $children_users, $group_users );
			$group_leaders = array_merge( $children_gls, $group_leaders );
		}
		if ( $group_leaders && 'no' !== (string) get_option( 'group_leaders_dont_use_seats', 'no' ) ) {
			foreach ( $group_leaders as $gl ) {
				$seat_users[ $gl->ID ] = array(
					'ID'         => $gl->ID,
					'user_email' => $gl->user_email,
					'role'       => 'group_leader',
				);
			}
		}

		if ( $group_users ) {
			foreach ( $group_users as $key => $user ) {
				if ( ! key_exists( $user->ID, $seat_users ) ) {
					$seat_users[ $user->ID ] = array(
						'ID'         => $user->ID,
						'user_email' => $user->user_email,
						'role'       => 'user',
					);
				}
			}
		}

		//If code is used and user is not group, fix count
		if ( count( $codes_users ) ) {
			foreach ( $codes_users as $user_id => $code_id ) {
				if ( ! key_exists( $user_id, $seat_users ) ) {
					$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . ' SET student_id = NULL, user_email = NULL, used_date = NULL, code_status = %s WHERE ID = %d', SharedFunctions::$available_status, $code_id );
					$wpdb->query( $sql );
				}
			}
		}

		//If group leader do not take seat checkbox is checked,
		//remove group leader seat count if they are added as member
		if ( 'yes' === (string) get_option( 'group_leaders_dont_use_seats', 'no' ) ) {
			foreach ( $codes_users as $user_id => $code_id ) {
				if ( key_exists( (int) $user_id, $seat_users ) && 'group_leader' === (string) $seat_users[ $user_id ]['role'] ) {
					$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . ' SET student_id = NULL, user_email = NULL, used_date = NULL, code_status = %s WHERE ID = %d', SharedFunctions::$available_status, absint( $code_id ) );
					$wpdb->query( $sql );
				}
			}
		}

	}

	/**
	 * @param $post_id
	 */
	public static function remove_users_invites_from_group_admin_func( $post_id ) {
		$codes_group_id = ulgm()->group_management->seat->get_code_group_id( $post_id );
		if ( empty( $codes_group_id ) ) {
			return;
		}
		global $wpdb;
		$codes_users      = array();
		$codes_users_ids  = array();
		$sql              = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . ' WHERE code_status != %s AND student_id IS NULL AND group_id =%d', SharedFunctions::$available_status, $codes_group_id );
		$code_group_users = $wpdb->get_results( $sql );
		// first recover not redeemed multiple invites
		if ( $code_group_users ) {
			// check all codes if email duplicates
			foreach ( $code_group_users as $u ) {
				if ( ! isset( $codes_users[ $u->user_email ] ) ) {
					$codes_users[ $u->user_email ] = $u->ID;
				} else {
					// this email is used twice then let's recover old code
					$code_id = $codes_users[ $u->user_email ];
					$sql     = $wpdb->prepare( "UPDATE {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . ' SET student_id = NULL, user_email = NULL, used_date = NULL, code_status = %s, first_name = NULL, last_name = NULL  WHERE ID = %d', SharedFunctions::$available_status, $code_id );
					$wpdb->query( $sql );
					// now replace it with latest one
					$codes_users[ $u->user_email ] = $u->ID;
				}
			}
		}
		// Now recover the invites where student already have an account and also have invite against its email address
		$sql              = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . ' WHERE code_status != %s AND student_id IS NOT NULL AND group_id =%d', SharedFunctions::$available_status, $codes_group_id );
		$code_group_users = $wpdb->get_results( $sql );
		if ( $code_group_users ) {
			// check all students emails in invites
			foreach ( $code_group_users as $u ) {
				//  Get email address of student
				$student = get_user_by( 'ID', $u->student_id );
				if ( isset( $codes_users[ $student->user_email ] ) ) {
					$code_id = $codes_users[ $student->user_email ];
					$sql     = $wpdb->prepare( "UPDATE {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . ' SET student_id = NULL, user_email = NULL, used_date = NULL, code_status = %s, first_name = NULL, last_name = NULL  WHERE ID = %d', SharedFunctions::$available_status, $code_id );
					$wpdb->query( $sql );
				}
				// Now check if user id are duplicated
				if ( ! isset( $codes_users_ids[ $u->student_id ] ) ) {
					$codes_users_ids[ $u->student_id ] = $u->ID;
				} else {
					// this email is used twice then let's recover old code
					$code_id = $codes_users_ids[ $u->student_id ];
					$sql     = $wpdb->prepare( "UPDATE {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . ' SET student_id = NULL, user_email = NULL, used_date = NULL, code_status = %s, first_name = NULL, last_name = NULL  WHERE ID = %d', SharedFunctions::$available_status, $code_id );
					$wpdb->query( $sql );
					// now replace it with latest one
					$codes_users_ids[ $u->student_id ] = $u->ID;
				}
			}
		}
	}

	/**
	 * @param $post_id
	 */
	public function remove_related_groups_data( $post_id ) {
		if ( ! $post_id ) {
			return;
		}

		global $wpdb;

		$group_detail_id = ulgm()->group_management->seat->get_code_group_id( $post_id );

		if ( $group_detail_id ) {
			$wpdb->query( "DELETE FROM {$wpdb->prefix}" . SharedFunctions::$db_group_tbl . " WHERE ID={$group_detail_id}" );
			$wpdb->query( "DELETE FROM {$wpdb->prefix}" . SharedFunctions::$db_group_codes_tbl . " WHERE group_id={$group_detail_id}" );
		}
	}

	/**
	 * @param $post_id
	 * @param $post
	 */
	public function perform_group_management_actions( $post_id, $post ) {
		if ( 'groups' !== $post->post_type ) {
			return;
		}
		$code_group_id = ulgm()->group_management->seat->get_code_group_id( $post_id );
		if ( empty( $code_group_id ) ) {
			return;
		}
		// Add nonce for security and authentication.
		$nonce_name   = ulgm_filter_has_var( 'custom_nonce', INPUT_POST ) ? ulgm_filter_input( 'custom_nonce', INPUT_POST ) : '';
		$nonce_action = 'custom_nonce_action';

		// Check if nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}

		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Should not update seat from child if pool seats are enabled
		if ( ! SharedFunctions::is_a_parent_group( $post_id ) && SharedFunctions::is_pool_seats_enabled_for_current_parent_group( $post_id ) ) {
			return;
		}

		/**
		 * Save Seats -- Start
		 */
		self::update_seat_count( $post_id, $code_group_id );
		/**
		 * Save Seats -- End
		 */

		/**
		 * Update user redeem seats -- Start
		 */
		$r = $this->update_user_redeem_seats( $post->ID );
		if ( empty( $r ) ) {
			return;
		}
		$group_users  = $r['group_users'];
		$group_admins = $r['group_admins'];
		/**
		 * Update user redeem seats -- End
		 */

		/**
		 * Remove users -- Start
		 */
		self::remove_users_from_group_admin_func( $post->ID, true, $group_admins, $group_users );
		/**
		 * Remove users -- End
		 */
	}

	/**
	 * @param $post_id
	 * @param $code_group_id
	 */
	public static function update_seat_count( $post_id, $code_group_id, $new_seats = null ) {
		$existing_seats = (int) ulgm()->group_management->seat->total_seats( $post_id );
		$new_seats      = null === $new_seats && ulgm_filter_has_var( '_ulgm_total_seats', INPUT_POST ) ? absint( ulgm_filter_input( '_ulgm_total_seats', INPUT_POST ) ) : absint( $new_seats );
		// Seats added
		if ( $new_seats > $existing_seats ) {
			$diff      = $new_seats - $existing_seats;
			$new_codes = ulgm()->group_management->generate_random_codes( $diff );

			$attr = array(
				'qty'           => $diff,
				'code_group_id' => $code_group_id,
			);
			ulgm()->group_management->add_additional_codes( $attr, $new_codes );
			update_post_meta( $post_id, '_ulgm_total_seats', absint( ulgm_filter_input( '_ulgm_total_seats', INPUT_POST ) ) );
		}
		// Seats removed
		if ( $new_seats < $existing_seats ) {
			global $wpdb;

			$diff             = $new_seats - $existing_seats;
			$diff             = $diff * - 1; //convert to positive
			$fetch_code_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(code) AS available FROM ' . $wpdb->prefix . SharedFunctions::$db_group_codes_tbl . ' WHERE group_id = %d AND student_id IS NULL LIMIT %d', $code_group_id, $diff ) );
			if ( ! empty( $fetch_code_count ) && $fetch_code_count >= $diff ) {
				//difference seats are empty, lets delete them
				$sql = $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . SharedFunctions::$db_group_codes_tbl . ' WHERE group_id = %d AND student_id IS NULL LIMIT %d', $code_group_id, $diff );
				$wpdb->query( $sql );
				update_post_meta( $post_id, '_ulgm_total_seats', $existing_seats - $diff );

				do_action( 'ulgm_seats_removed', $diff, $post_id, $code_group_id );
			}
		}

		//Change Group Title/Slug in codes table
		global $wpdb;
		$wpdb->update(
			$wpdb->prefix . SharedFunctions::$db_group_tbl,
			array( 'group_name' => ulgm_filter_input( 'post_title', INPUT_POST ) ),
			array(
				'ID'          => $code_group_id,
				'ld_group_id' => $post_id,
			),
			array( '%s' ),
			array( '%d', '%d' )
		);
	}

	/**
	 * @param $post_id
	 *
	 * @return array|array[]
	 */
	public function update_user_redeem_seats( $post_id ) {
		$r = $this->get_changed_users_admins( $post_id );
		if ( empty( $r ) ) {
			return array();
		}
		$group_users  = $r['group_users'];
		$group_admins = $r['group_admins'];
		self::update_user_redeemed_seat_func( $post_id, true, $group_users, $group_admins );
		if ( ! SharedFunctions::is_a_parent_group( $post_id ) && SharedFunctions::is_pool_seats_enabled_for_current_parent_group( $post_id ) ) {
			self::update_group_seat_counts( $post_id, false, $group_users, $group_admins );
		}

		return $r;
	}

	/**
	 * @param $post_id
	 *
	 * @return array|array[]
	 */
	public function get_changed_users_admins( $post_id ) {
		$group_users = array();
		$change      = 0;
		if ( ( isset( $_POST[ 'learndash_group_users-' . $post_id . '-changed' ] ) ) && ( ! empty( $_POST[ 'learndash_group_users-' . $post_id . '-changed' ] ) ) ) {
			if ( ulgm_filter_has_var( 'learndash_group_users', INPUT_POST ) && isset( ulgm_filter_input( 'learndash_group_users', INPUT_POST )[ $post_id ] ) ) {
				$group_user_ids = (array) json_decode( stripslashes( ulgm_filter_input( 'learndash_group_users', INPUT_POST )[ $post_id ] ) );
				if ( ! empty( $group_user_ids ) ) {
					$group_user_ids = array_map( 'absint', $group_user_ids );
					$group_users    = get_users( array( 'include' => $group_user_ids ) );
				}
			}
			$change = 1;
		}

		$group_admins = array();
		if ( ( isset( $_POST[ 'learndash_group_leaders-' . $post_id . '-changed' ] ) ) && ( ! empty( $_POST[ 'learndash_group_leaders-' . $post_id . '-changed' ] ) ) ) {
			if ( ulgm_filter_has_var( 'learndash_group_leaders', INPUT_POST ) && isset( ulgm_filter_input( 'learndash_group_leaders', INPUT_POST )[ $post_id ] ) ) {
				$group_admin_ids = (array) json_decode( stripslashes( ulgm_filter_input( 'learndash_group_leaders', INPUT_POST )[ $post_id ] ) );
				if ( ! empty( $group_admin_ids ) ) {
					$group_admin_ids = array_map( 'absint', $group_admin_ids );
					$group_admins    = get_users( array( 'include' => $group_admin_ids ) );
				}
			}
			$change = 1;
		}
		// If there's no change in users and group leaders, no need to reconcile.
		if ( 0 === $change ) {
			return array();
		}

		return array(
			'group_users'  => $group_users,
			'group_admins' => $group_admins,
			'change'       => $change,
		);
	}

	/**
	 *
	 */
	public function downgrade_selected_group() {
		if ( ! ulgm_filter_has_var( 'downgrade-group' ) ) {
			return;
		}

		if ( ! ulgm_filter_has_var( 'group-id' ) ) {
			return;
		}

		// Add nonce for security and authentication.
		$nonce_name   = ulgm_filter_has_var( '_wpnonce' ) ? ulgm_filter_input( '_wpnonce' ) : '';
		$nonce_action = 'Uncanny0wL';

		// Check if nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}

		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		$group_id = absint( ulgm_filter_input( 'group-id' ) );

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $group_id ) ) {
			return;
		}

		$code_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );
		if ( empty( $code_group_id ) ) {
			return;
		}

		$order_id = ulgm()->group_management->get_random_order_number();
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->prefix" . ulgm()->db->tbl_group_details . ' SET order_id = %d WHERE ID = %d AND ld_group_id = %d', $order_id, $code_group_id, $group_id ) );
		update_post_meta( $group_id, SharedFunctions::$code_group_downgraded, 'yes' );
		update_post_meta( $group_id, SharedFunctions::$code_group_downgraded . '_time', current_time( 'timestamp' ) );

		$redirect = admin_url( 'post.php?post=' . $group_id . '&action=edit' );
		wp_safe_redirect( $redirect );
		die();
	}

	/**
	 * @param $group_id
	 * @param bool $force
	 * @param array $group_users
	 * @param array $group_admins
	 *
	 * @return string|void
	 */
	public static function update_group_seat_counts( $group_id, $force = false, $group_users = array(), $group_admins = array() ) {
		if ( ! SharedFunctions::is_a_parent_group( $group_id ) && SharedFunctions::is_pool_seats_enabled_for_current_parent_group( $group_id ) ) {
			$group_id = ulgm()->group_management->seat->get_real_ld_group_id( $group_id );
		}
		// Reconcile parent group
		if ( SharedFunctions::is_a_parent_group( $group_id ) && SharedFunctions::is_pool_seats_enabled_for_current_parent_group( $group_id ) ) {
			self::update_user_redeemed_seat_func( $group_id, $force, $group_users, $group_admins );
			self::remove_users_from_group_admin_func( $group_id );
			self::remove_users_invites_from_group_admin_func( $group_id );
			$r = self::fix_total_number_of_hierarchy_users( $group_id, $group_users, $group_admins );
			if ( true !== $r ) {
				return $r;
			}
			// If pool seats enabled, reconcile all children group
			$group_children = learndash_get_group_children( $group_id );
			$group_children = array_reverse( $group_children );
			if ( ! empty( $group_children ) ) {
				foreach ( $group_children as $child_group_id ) {
					$child_group_id = absint( $child_group_id );
					self::update_user_redeemed_seat_func( $child_group_id );
					self::remove_users_from_group_admin_func( $child_group_id );
					self::remove_users_invites_from_group_admin_func( $child_group_id );
				}
			}

			return;
		}
		self::update_user_redeemed_seat_func( $group_id, $force, $group_users, $group_admins );
		self::remove_users_from_group_admin_func( $group_id );
		self::remove_users_invites_from_group_admin_func( $group_id );
	}

	/**
	 * @param $group_id
	 * @param array $group_users
	 * @param array $group_leaders
	 *
	 * @return bool|string|null
	 */
	public static function fix_total_number_of_hierarchy_users( $group_id, $group_users = array(), $group_leaders = array() ) {
		$group_leader_take_seat = 'no' !== (string) get_option( 'group_leaders_dont_use_seats', 'no' ) ? true : false;
		if ( true === $group_leader_take_seat && empty( $group_leaders ) ) {
			$group_leaders = LearndashFunctionOverrides::learndash_get_groups_administrators( $group_id, true );
		}
		if ( empty( $group_users ) ) {
			$group_users = LearndashFunctionOverrides::learndash_get_groups_users( $group_id, true );
		}
		global $wpdb;
		// Seats in parent group
		$seats_in_parent     = ulgm()->group_management->seat->total_seats( $group_id );
		$seats_across_groups = 0;
		$group_children      = learndash_get_group_children( $group_id );
		$group_children      = array_reverse( $group_children );
		if ( ! empty( $group_children ) ) {
			foreach ( $group_children as $child_group_id ) {
				if ( true === $group_leader_take_seat ) {
					$group_leaders = array_merge( $group_leaders, LearndashFunctionOverrides::learndash_get_groups_administrators( $child_group_id, true ) );
				}
				$group_users = array_merge( $group_users, LearndashFunctionOverrides::learndash_get_groups_users( $child_group_id, true ) );
				// get seat in each child group
				$child_seats         = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(c.ID)FROM {$wpdb->prefix}" . ulgm()->db->tbl_group_codes . " c JOIN {$wpdb->prefix}" . ulgm()->db->tbl_group_details . ' d ON c.group_id = d.ID WHERE d.ld_group_id = %d', $child_group_id ) );
				$seats_across_groups = (int) $seats_across_groups + $child_seats;
			}
		}

		$group_leader_ids = array_column( $group_leaders, 'ID' );
		$group_user_ids   = array_column( $group_users, 'ID' );
		$total            = array_unique( array_merge( $group_leader_ids, $group_user_ids ) );
		$count_users      = count( $total );
		// Parent group seems to be purchased via payment, bail and show error
		if (
			Utilities::if_woocommerce_active() &&
			empty( get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, SharedFunctions::$code_group_downgraded, true ) ) &&
			empty( get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, '_ulgm_is_upgraded', true ) ) &&
			empty( get_post_meta( GroupManagementInterface::$ulgm_current_managed_group_id, '_ulgm_is_custom_group_created', true ) ) &&
			$count_users > $seats_across_groups
		) {
			return __( 'Your group does not have enough seats to accommodate all users in the group hierarchy. Please add more seats and try again.', 'uncanny-learndash-groups' );
		}

		if ( $count_users >= ( $seats_across_groups + $seats_in_parent ) ) {
			update_post_meta( $group_id, '_ulgm_seats_before_pooling', $seats_in_parent );
			// users across all groups are
			// GT seats in all groups
			$diff = $count_users - ( $seats_across_groups + $seats_in_parent );
			if ( $diff > 0 ) {
				$add = (int) apply_filters( 'ulgm_pool_seats_add_extra_seats_in_parent', absint( 10 + $diff ), $diff, $group_id );
				self::increase_seat_count( $add, $group_id );
			}

			return true;
		}

		if ( ( $seats_across_groups - $seats_in_parent ) > 0 ) {
			update_post_meta( $group_id, '_ulgm_seats_before_pooling', $seats_in_parent );
			// total seats across all groups in hierarchy
			$diff = $seats_across_groups;
			self::increase_seat_count( $diff, $group_id );
		}

		return true;
	}

	/**
	 * @param $diff
	 * @param $group_id
	 *
	 * @return void
	 */
	public static function increase_seat_count( $diff, $group_id ) {
		$attr          = array(
			'group_id' => $group_id,
			'qty'      => $diff,
		);
		$code_group_id = ulgm()->group_management->seat->get_code_group_id( $group_id );
		$codes         = ulgm()->group_management->generate_random_codes( $diff );
		ulgm()->group_management->add_codes( $attr, $codes, $code_group_id );
	}
}
