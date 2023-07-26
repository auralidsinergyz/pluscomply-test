<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}
global $post;

$redirect_url = admin_url( 'admin.php?page=uncanny-groups-create-group' );
$is_admin     = is_admin();

if ( Utilities::has_shortcode( $post, 'uo_groups_create_group' ) || Utilities::has_block( $post, 'uncanny-learndash-groups/uo-groups-create-group' ) ) {
	$redirect_url = get_permalink( $post->ID ) . '?is-custom-group=yes';
	$is_admin     = false;
}

?>

<div class="wrap">
	<div class="ulgm">

		<?php
		if ( is_admin() ) {
			// Add admin header and tabs
			$tab_active = 'uncanny-groups-create-group';
			include Utilities::get_template( 'admin/admin-header.php' );
		}
		?>

		<div id="ulg-create-group">
			<div class="ulgm-admin-content">
				<div class="
				<?php
				if ( $is_admin ) {
					echo 'uo-ulgm-admin';
				} else {
					echo 'uo-ulgm-front';
				}
				?>
				 form-table group-management-form">

					<form name="" method="post" action="<?php echo $redirect_url; ?>">
						<input type="hidden" value="<?php echo $redirect_url; ?>" name="redirect_to"/>
						<input type="hidden" value="yes" name="is_custom_group"/>
						<?php
						if ( ! $is_admin ) {
							?>
							<input type="hidden" value="yes" name="is_front_end"/>
							<input type="hidden" value="<?php echo $post->ID; ?>" name="group_page_id"/>
							<?php
						}
						?>
						<?php wp_nonce_field( 'ulgm_nonce', 'is_custom_group_nonce' ); ?>

						<!-- Group Details -->
						<div class="uo-admin-section uo-admin-section--first">
							<div class="uo-admin-header">
								<div class="uo-admin-title"><?php echo __( 'Group details', 'uncanny-learndash-groups' ); ?></div>
							</div>
							<div class="uo-admin-block">
								<div class="uo-admin-form">

									<!-- Group Name -->
									<div class="uo-admin-field uo-admin-field--group-name">
										<div class="uo-admin-label"><?php echo __( 'Group name', 'uncanny-learndash-groups' ); ?></div>
										<input class="uo-admin-input" name="ulgm_group_name" id="ulgm_group_name"
											   type="text" required="required"/>
									</div>

									<!-- Total Seats -->
									<div class="uo-admin-field uo-admin-field--total-seats">
										<div class="uo-admin-label"><?php echo __( 'Total seats', 'uncanny-learndash-groups' ); ?></div>
										<input class="uo-admin-input" name="ulgm_group_total_seats"
											   id="ulgm_group_total_seats" type="number" required="required"
											   placeholder="<?php echo __( 'Ex. 10', 'uncanny-learndash-groups' ); ?>"
											   min="1"/>
									</div>

									<!-- Group Courses -->
									<div class="uo-admin-field uo-admin-field--group-courses">
										<div class="uo-admin-label"><?php echo sprintf( _x( 'Group %s', 'Group courses', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?></div>

										<?php $group_courses_required = apply_filters( 'ulgm_create_group_courses_required', true ); ?>
										<select class="uo-admin-select" id="ulg-create-group__courses-list"
												multiple="multiple" name="ulgm_group_courses[]" 
												<?php
												if ( $group_courses_required ) :
													?>
													required="required"<?php endif; ?>
												size="8">
											<?php
											$args = array(
												'post_type' => 'sfwd-courses',
												'posts_per_page' => 9999,
												'post_status' => 'publish',
												'orderby' => 'title',
												'order'   => 'ASC',
											);

											if ( ! empty( $atts['category'] ) ) {
												$tax_query = array(
													'relation' => 'OR',
													array(
														'taxonomy' => 'category',
														'field'    => 'slug',
														'terms'    => array_map(
															'trim',
															explode( ',', $atts['category'] )
														),
													),
												);
											}
											if ( ! empty( $atts['course_category'] ) ) {
												$tax_query[] = array(
													'taxonomy' => 'ld_course_category',
													'field'    => 'slug',
													'terms'    => array_map(
														'trim',
														explode(
															',',
															$atts['course_category']
														)
													),
												);
											}
											if ( ! empty( $tax_query ) ) {
												$args['tax_query'] = $tax_query;
											}
											$courses = get_posts( $args );
											if ( $courses ) {
												foreach ( $courses as $course ) {
													?>
													<option value="<?php echo esc_attr( $course->ID ); ?>"><?php echo esc_attr( $course->post_title ); ?></option> 
																			  <?php
												}
											}
											?>
										</select>
										<div class="uo-admin-description"><?php echo sprintf( __( 'Press Ctrl to select multiple %s.', 'uncanny-learndash-groups' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?></div>
									</div>

									<!-- Total Seats -->
									<div class="uo-admin-field uo-admin-field--group-image">
										<div class="uo-admin-label"><?php echo __( 'Group image', 'uncanny-learndash-groups' ); ?></div>
										<div class='image-preview-wrapper'>
											<img id='ulgm_group_image_preview' src='' width='100' height='100'
												 style='max-height: 100px; width: 100px;display: none'>
										</div>
										<input id="ulgm_group_upload_image_button" type="button" class="button"
											   value="<?php echo __( 'Upload image', 'uncanny-learndash-groups' ); ?>"/>
										<input type='hidden' name='ulgm_group_image_attachment_id'
											   id='ulgm_group_image_attachment_id' value=''>
									</div>
								</div>
							</div>
						</div>

						<!-- Group Leader Details -->
						<div class="uo-admin-section">
							<div class="uo-admin-header">
								<div class="uo-admin-title"><?php echo __( 'Group leader details', 'uncanny-learndash-groups' ); ?></div>
							</div>
							<div class="uo-admin-block">
								<div class="uo-admin-form">

									<!-- First Name -->
									<div class="uo-admin-field uo-admin-field--leader-first-name">
										<div class="uo-admin-label"><?php echo __( 'First name', 'uncanny-learndash-groups' ); ?></div>
										<input class="uo-admin-input" name="ulgm_group_leader_first_name"
											   id="ulgm_group_leader_first_name" type="text"/>
									</div>

									<!-- Last Name -->
									<div class="uo-admin-field uo-admin-field--leader-last-name">
										<div class="uo-admin-label"><?php echo __( 'Last name', 'uncanny-learndash-groups' ); ?></div>
										<input class="uo-admin-input" name="ulgm_group_leader_last_name"
											   id="ulgm_group_leader_last_name" type="text"/>
									</div>

									<!-- Email -->
									<div class="uo-admin-field uo-admin-field--leader-email">
										<div class="uo-admin-label"><?php echo __( 'E-mail', 'uncanny-learndash-groups' ); ?></div>
										<input class="uo-admin-input" id="ulgm_group_leader_email"
											   name="ulgm_group_leader_email" type="email"/>
										<div class="uo-admin-description">
										<?php
										echo __(
											'Enter the email address of the new group leader. If the email
										                                  does not match an existing user, the user will be created and
										                                  sent the Add group leader/Create group Email. If the email matches an
										                                  existing user, the user will be sent the Existing Group Leader
										                                  Welcome Email. Leave empty if you are the group leader.',
											'uncanny-learndash-groups'
										)
										?>
											<?php
											if ( $is_admin ) {
												printf( __( 'Visit %s to customize the email text.', 'uncanny-learndash-groups' ), '<a href="' . get_admin_url( null, 'admin.php?page=uncanny-groups-email-settings' ) . '">' . __( 'Settings', 'uncanny-learndash-groups' ) . '</a>' );
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Submit -->
						<div class="uo-admin-section uo-admin-section-without-box">
							<div class="uo-admin-block">
								<div class="uo-admin-form">
									<input type="hidden" id="action" name="action" value="save-email-templates"/>
									<div class="uo-admin-field uo-admin-field--submit uo-admin-no-space">
										<input type="submit" name="submit" id="submit" class="uo-admin-form-submit"
											   value="<?php _e( 'Create group', 'uncanny-learndash-groups' ); ?>">
									</div>
								</div>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>
