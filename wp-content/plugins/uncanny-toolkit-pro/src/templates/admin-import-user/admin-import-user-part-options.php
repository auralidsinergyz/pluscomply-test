<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

$option_keys = array(
	'uo_import_add_to_group',
	'uo_import_enrol_in_courses',
	array( 'uo_import_existing_user_data', 'update' ),
	'uo_import_set_roles'
);

$options = array();

foreach ( $option_keys as $meta_key ) {

	if( is_array($meta_key) ){
		$option = get_option( $meta_key[0], $meta_key[1] );
	}else{
		$option = get_option( $meta_key );
	}

	// all meta value have comma separated values from an array implode except uo_import_existing_user_data
	if ( is_array($meta_key) && $meta_key[0] === 'uo_import_existing_user_data' ) {
		$options[ $meta_key[0] ] = $option;
	} else {
		$options[ $meta_key ] = explode( ',', $option );
	}
}

?>

<form id="uo_import_save_options" method="post" action="options.php">

	<table class="form-table">

		<tr valign="top" class="options-header-container">
			<th scope="row" colspan="2">
				<h2><?php _e( 'Existing Users', 'uncanny-pro-toolkit' ); ?></h2>
			</th>
		</tr>

		<tr valign="top" class="option-setting-container">
			<th scope="row">
				<h4><?php _e( 'For imported existing users,', 'uncanny-pro-toolkit' ); ?></h4></th>
			<td>
				<label>
					<select name="uo_import_existing_user_data" id="uo_import_existing_user_data">
						<option
							value="update" <?php echo ( 'update' === $options['uo_import_existing_user_data'] ) ? 'selected="selected"' : ''; ?>>
							Update
							(Default)
						</option>
						<option
							value="ignore" <?php echo ( 'ignore' === $options['uo_import_existing_user_data'] ) ? 'selected="selected"' : ''; ?>>
							Ignore
						</option>
					</select>
					<h4 style="display:inline"> user data.</h4>
				</label>
			</td>
		</tr>

		<tr class="options-spacer"></tr>

		<tr valign="top" class="options-header-container">
			<th scope="row" colspan="2">
				<h2><?php _e( 'User Roles', 'uncanny-pro-toolkit' ); ?></h2>
			</th>
		</tr>

		<tr valign="top" class="option-setting-container">
			<th scope="row">
				<h4><?php _e( 'Set the Role for imported users', 'uncanny-pro-toolkit' ); ?></h4>
				<ul class="import-user-list">
					<li>Users with no role specified in the CSV will receive the selected role.</li>
					<li>If no role is selected and no role specified for the user in the CSV, those users will be assigned
						the 'Subscriber' role.</li>
				</ul>
			</th>
			<td class="listed-label-input">
				<?php
				$editable_roles = get_editable_roles();
				foreach ( $editable_roles as $role => $details ) {

					?>
					<label>
						<input type="radio" name="uo_import_set_roles"
						       value="<?php echo esc_attr( $role ); ?>"
							<?php echo ( in_array(esc_attr( $role ), $options['uo_import_set_roles'] ) ) ? 'checked="checked"' : ''; ?>
						/>
						<?php echo $details['name']; ?>
					</label>
					<?php
				}
				?>
			</td>
		</tr>

		<tr class="options-spacer"></tr>

		<tr valign="top" class="options-header-container">
			<th scope="row" colspan="2">
				<h2><?php _e( 'Courses', 'uncanny-pro-toolkit' ); ?></h2>
			</th>
		</tr>

		<tr valign="top" class="option-setting-container">
			<th scope="row">
				<h4><?php _e( 'Enroll users in course(s)', 'uncanny-pro-toolkit' ); ?></h4>
				<ul class="import-user-list">
					<li>Users with no courses specified in the CSV will be enrolled in the specified courses.</li>
					<li>Note: Courses that are set to Type 'Open'  do not appear here, as all users are automatically enrolled in
						Open courses.</li>
				</ul>
			</th>
			<td class="listed-label-input">
				<?php

				$args = array(
					'post_type'   => 'sfwd-courses',
					'post_status' => 'publish',
					'posts_per_page'=> 1000

				);

				// the query
				$the_query = new WP_Query( $args ); ?>

				<?php if ( $the_query->have_posts() ) :

					while ( $the_query->have_posts() ) : $the_query->the_post();
						$meta = get_post_meta( get_the_ID(), '_sfwd-courses', true );

						if ( isset( $meta['sfwd-courses_course_price_type'] ) ) {
							if ( 'open' == $meta['sfwd-courses_course_price_type'] ) {
								continue;
							}
						} else {
							continue;
						}
						?>
						<label>
							<input type="checkbox" name="uo_import_enrol_in_courses['<?php echo get_the_ID() ?>']"
							       value="<?php echo get_the_ID() ?>"
								<?php echo ( in_array(get_the_ID(), $options['uo_import_enrol_in_courses'] ) ) ? 'checked="checked"' : ''; ?>
							/>
							<?php the_title(); ?>
						</label>
					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

				<?php else : ?>
					<p><?php _e( 'No Courses Published', 'uncanny-pro-toolkit' ); ?></p>
				<?php endif; ?>
			</td>
		</tr>

		<tr class="options-spacer"></tr>

		<tr valign="top" class="options-header-container">
			<th scope="row" colspan="2">
				<h2><?php _e( 'Groups', 'uncanny-pro-toolkit' ); ?></h2>
			</th>
		</tr>

		<tr valign="top" class="option-setting-container">
			<th scope="row">
				<h4><?php _e( 'Add users to group(s)', 'uncanny-pro-toolkit' ); ?></h4>
				<ul class="import-user-list">
					<li>Users with no groups specified in the CSV will be enrolled in the selected groups.</li>
				</ul>
			</th>
			<td class="listed-label-input">
				<?php

				$args = array(
					'post_type'   => 'groups',
					'post_status' => 'publish',
					'posts_per_page'=> 100

				);

				// the query
				$the_query = new WP_Query( $args ); ?>

				<?php if ( $the_query->have_posts() ) : ?>

					<!-- pagination here -->

					<!-- the loop -->
					<?php while ( $the_query->have_posts() ) : $the_query->the_post();
						?>
						<label>
							<input type="checkbox" name="uo_import_add_to_group['<?php echo get_the_ID() ?>']"
								<?php echo ( in_array(get_the_ID(), $options['uo_import_add_to_group'] ) ) ? 'checked="checked"' : ''; ?>
							       value="<?php echo get_the_ID() ?>"/>
							<?php the_title(); ?>
						</label>
					<?php endwhile; ?>
					<!-- end of the loop -->

					<!-- pagination here -->

					<?php wp_reset_postdata(); ?>

				<?php else : ?>
					<p><?php _e( 'No Groups Published', 'uncanny-pro-toolkit' ); ?></p>
				<?php endif; ?>
			</td>
		</tr>

		<tr class="options-spacer"></tr>

	</table>

	<input type="submit" id="btn-save_options" class="button button-primary" value="Save Changes"/>

</form>