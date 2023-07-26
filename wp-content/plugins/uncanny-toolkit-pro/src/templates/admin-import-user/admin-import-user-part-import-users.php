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

// New user template variables
$uo_import_users_send_new_user_email = ( get_option( 'uo_import_users_send_new_user_email' ) === 'true' ) ? 'WILL' : 'WILL NOT';

// Updated user template variables
$uo_import_users_send_updated_user_email = ( get_option( 'uo_import_users_send_updated_user_email' ) === 'true' ) ? 'WILL' : 'WILL NOT';

$options_link         = admin_url( 'users.php?page=learndash-toolkit-import-user&tab=options' );
$emails_link          = admin_url( 'users.php?page=learndash-toolkit-import-user&tab=emails' );
$csv_sample_file_link = plugins_url( basename( dirname( UO_FILE ) ) ) . '\src\assets\dist\backend\import_user_sample.csv';

?>

<div id="import-users-upload">

	<h2 class="options-header-container">Current Settings</h2>
	<p>Review your enrollment defaults and email options. Make any necessary changes before proceeding to Import
		LearnDash Users.</p>

	<h2>Default Course(s)</h2>
	<p>Users without valid course IDs in the spreadsheet will be enrolled in the following courses:</p>
	<ul class="import-user-list">
		<?php

		$args = array(
			'post_type'      => 'sfwd-courses',
			'post_status'    => 'publish',
			'posts_per_page' => 1000

		);

		// the query
		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$meta = get_post_meta( get_the_ID(), '_sfwd-courses', true );

				if ( isset( $meta['sfwd-courses_course_price_type'] ) ) {
					if ( 'open' == $meta['sfwd-courses_course_price_type'] ) {
						echo '<li>' . get_the_title() . '<span style="color:green;"> ( Course is Open to all )</span></li>';
						continue;
					}
				}

				if ( in_array( get_the_ID(), $options['uo_import_enrol_in_courses'] ) ) {
					echo '<li>' . get_the_title() . '</li>';
				}

			};

			wp_reset_postdata();


		} else {
			echo __( 'No Courses Published' );
		}
		?>
	</ul>
	<a href="?page=learndash-toolkit-import-user&tab=options" class="button button-secondary">Edit Options</a>

	<h2>Default Groups(s)</h2>
	<p>Users without valid group IDs in the spreadsheet will be added to the following groups:</p>
	<ul class="import-user-list">
		<?php

		$args = array(
			'post_type'      => 'groups',
			'post_status'    => 'publish',
			'posts_per_page' => 100

		);

		// the query
		$groups_query = new WP_Query( $args );

		if ( $groups_query->have_posts() ) {


			while ( $groups_query->have_posts() ) {

				$groups_query->the_post();

				if ( in_array( get_the_ID(), $options['uo_import_add_to_group'] ) ) {
					echo '<li>' . get_the_title() . '</li>';
				}
			}

			wp_reset_postdata();

		} else {
			echo __( 'No Groups Published' );
		}
		?>
	</ul>
	<a href="?page=learndash-toolkit-import-user&tab=options" class="button button-secondary">Edit Options</a>

	<h2>Default Role(s)</h2>
	<p>Users without a valid role in the spreadsheet will be set to the following role:</p>
	<ul class="import-user-list">
		<?php
		$editable_roles = get_editable_roles();
		foreach ( $editable_roles as $role => $details ) {

			if ( in_array( esc_attr( $role ), $options['uo_import_set_roles'] ) ) {
				echo '<li>' . $details['name'] . '</li>';
			}
		}
		?>
	</ul>
	<a href="?page=learndash-toolkit-import-user&tab=options" class="button button-secondary">Edit Options</a>

	<h2>Email Options</h2>
	<p>New users <?php echo $uo_import_users_send_new_user_email; ?> be sent an email.</p>
	<p>Updated users <?php echo $uo_import_users_send_updated_user_email; ?> be sent an email.</p>
	<a href="?page=learndash-toolkit-import-user&tab=emails" class="button button-secondary">Edit Email Settings</a>

	<h2 class="options-header-container">Import Users</h2>

	<p>Upload the CSV file and review verification results.</p>

	<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" id="file-upload" enctype="multipart/form-data">
		<input type="file" name="csv" id="csv-file"/>
		<br>
		<input type="submit" class="button button-primary" value="Upload file and verify records"/>
	</form>

</div>

<div id="import-users-validation">

	<h2 class="options-header-container">Validation Results</h2>

	<table class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<td>Type</td>
			<td>Number</td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>Total number of users found in CSV</td>
			<td id="total-rows"></td>
		</tr>
		<tr>
			<td>New users that will be created</td>
			<td id="new-emails"></td>
		</tr>
		<tr>
			<td>Existing users that
				<b><?php echo ( 'update' === $options['uo_import_existing_user_data'] ) ? 'will' : 'will not'; ?></b>
				be updated
			</td>
			<td id="existing-emails"></td>
		</tr>
		<tr>
			<td>Users with malformed email addresses</td>
			<td id="malformed-emails"></td>
		</tr>
		<tr>
			<td>Users with invalid course IDs</td>
			<td id="invalid-courses"></td>
		</tr>
		<tr>
			<td>Users with invalid group IDs</td>
			<td id="invalid-groups"></td>
		</tr>
		</tbody>
	</table>

	<h2 class="options-header-container">Skipped Users with Existing Emails</h2>
	<p>
		The emails listed below were found in the CSV, but are assigned to users that already exist in WordPress.
		WordPress requires that each email be unique.
	</p>
	<h4>These records will be ignored.</h4>
	<table id="existing-user-email-table" class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<td>CSV Row</td>
			<td>Email</td>
			<td>Conflicting User</td>
		</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<h2 class="options-header-container">Invalid Course IDs</h2>
	<p>
		The course IDs below were found in the CSV but do not correspond to an existing LearnDash course. If you
		proceed,
		they will be ignored.
	</p>
	<h4>These records will be ignored.</h4>
	<table id="invalid-courses-table" class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<td>CSV Row</td>
			<td>Invalid Courses</td>
		</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<h2 class="options-header-container">Invalid Group IDs</h2>
	<p>
		The group IDs below were found in the CSV but do not correspond to an existing LearnDash group. If you
		proceed,
		they will be ignored.
	</p>
	<h4>These records will be ignored.</h4>
	<table id="invalid-groups-table" class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<td>CSV Row</td>
			<td>Invalid Groups</td>
		</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<h2 class="options-header-container">Let's Add Some Users!</h2>
	<div class="perform-import-users">
		<button class="button  button-large" id="abort-import-users">Abort Import Process</button>
		<button class="button button-primary button-large" id="perform-import-users">Perform Import</button>
		<h3 id="perform-import-users-text">It is not possible to cancel the import process once it has
			begun.</h3>
		<button id="perform-import-users-review" class="button button-large">Let me review the validation results
			again
		</button>
		<button id="perform-import-users-ready" class="button button-primary button-large">I'm ready to import
		</button>
	</div>

</div>

<div id="import-users-progress">

	<h2 class="options-header-container">Import Progress</h2>

	<div class="import-progress-bar">
		<img src="<?php echo site_url(); ?>/wp-includes/js/thickbox/loadingAnimation.gif"
		     data-lazy-src="<?php echo site_url(); ?>/wp-includes/js/thickbox/loadingAnimation.gif" class="lazyloaded" scale="0">
		<div class="import-progress-bar-overlay"></div>
	</div>

	<h3>Do not reload this page while import is in progress</h3>

	<div id="import-users-results">

		<h2 class="options-header-container">Import Results</h2>

		<table id="import-users-results-table" class="wp-list-table widefat fixed striped posts">
			<tbody>
			<tr>
				<td>New Users Created</td>
				<td id="import-users-results-new-users"></td>
			</tr>
			<tr>
				<td>Existing Users Updated</td>
				<td id="import-users-results-updated-users"></td>
			</tr>
			<tr>
				<td>Emails Sent</td>
				<td id="import-users-results-emails-sent"></td>
			</tr>
			<tr>
				<td>Rows Ignored</td>
				<td id="import-users-results-rows-ignored"></td>
			</tr>
			</tbody>
		</table>

		<h2 class="options-header-container">The following rows in the spreadsheet were skipped:</h2>

		<table id="import-users-ignored-table" class="wp-list-table widefat fixed striped posts">
			<thead>
			<tr>
				<td>CSV Row</td>
				<td>Issue</td>
			</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

	</div>
</div>
