<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

$csv_sample_file_link = plugins_url( basename( dirname( UO_FILE ) ) ) . '\src\assets\dist\backend\import_user_sample.csv';

?>

<div id="import-users-instructions">

	<p>
		<em>
			This is a complex module that allows users to be added to courses and LearnDash Groups from a
			CSV file. The Options tab includes settings that control how updates to users are managed and bulk imported
			into specific courses and groups. The Emails tab includes settings that control whether to send
			notifications
			to imported users and the email templates. These settings should be reviewed before proceeding with the
			import.
			Download the sample CSV on this page to see examples of the fields that can be included.
		</em>
	</p>

	<p>
		You should also review the <a href="https://www.uncannyowl.com/knowledge-base/import-learndash-users/">Knowledge Base article</a>
		which includes more detailed explanations and specific instructions for different use cases.

	</p>

	<h2 class="options-header-container">
		<ul class="steps">
			<li class="current">
				<a href="#" data-show="step-1" title="">
					<span class="step-title">Step 1</span>
				</a>
			</li>
			<li></li>
		</ul>
		<div class="steps-description">Review Options</div>
	</h2>
	<p>Use this tab to configure:</p>
	<ul class="import-user-list">
		<li>Whether to update or ignore users that already exist on your website</li>
		<li>Role to assign to imported users</li>
		<li>Course(s) to enroll imported users into</li>
		<li>LearnDash Group(s) to assign imported users to</li>
	</ul>

	<h2 class="options-header-container">
		<ul class="steps">
			<li class="current">
				<a href="#" data-show="step-1" title="">
					<span class="step-title">Step 2</span>
				</a>
			</li>
			<li></li>
		</ul>
		<div class="steps-description">Review Email Settings</div>
	</h2>
	<p>Use this tab to:</p>
	<ul class="import-user-list">
		<li>Enable email notifications to new and/or updated users</li>
		<li>Customize email templates</li>
	</ul>

	<h2 class="options-header-container">
		<ul class="steps">
			<li class="current">
				<a href="#" data-show="step-1" title="">
					<span class="step-title">Step 3</span>
				</a>
			</li>
			<li></li>
		</ul>
		<div class="steps-description">Create a CSV File <a class="options" href="<?php echo $csv_sample_file_link; ?>">Download Sample CSV</a></div>
	</h2>
	<p>
		Your CSV file must be comma-delimited with a .csv extension. It requires user_login and user_email columns,
		and can include any number of optional meta fields below.
	</p>

	<h2>Available Meta Fields</h2>

	<table class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<td>Column Heading</td>
			<td>Description</td>
			<td>Required/Optional</td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>user_email</td>
			<td>The user's email</td>
			<td>required</td>
		</tr>
		<tr>
			<td>user_login</td>
			<td>The user's username</td>
			<td>required</td>
		</tr>
		<tr>
			<td>user_pass</td>
			<td>The user's password</td>
			<td>optional</td>
		</tr>
		<tr>
			<td>learndash_courses</td>
			<td>
				One or more courses to enroll the user into, specified by course ID. If this column exists and cell is
				empty, course(s) in Options will be used. Multiple course IDs must be separated by semi-colons,
				e.g., 96;107;92
			</td>
			<td>optional</td>
		</tr>
		<tr>
			<td>learndash_groups</td>
			<td>
				One or more LearnDash groups to enroll the user into, specified by group ID. If this column exists
				and cell is empty, group(s) in Options will be used. Multiple group IDs must be separated by
				semi-colons, e.g., 91;102;98
			</td>
			<td>optional</td>
		</tr>
		<tr>
			<td>wp_role</td>
			<td>
				Role to assign to the imported user, specified by role slug. If this column exists and cell is empty,
				the role in Options will be used.
				<br> <b>Available role slugs:</b>
				<?php foreach ( get_editable_roles() as $role_name => $role_info ): ?>
					<?php echo ' ' . $role_name . ' ' ?>
				<?php endforeach; ?>
			</td>
			<td>optional</td>
		</tr>
		<tr>
			<td>first_name</td>
			<td>The user's first name</td>
			<td>optional</td>
		</tr>
		<tr>
			<td>last_name</td>
			<td>The user's last name</td>
			<td>optional</td>
		</tr>
		<tr>
			<td>display_name</td>
			<td>The user's display name</td>
			<td>optional</td>
		</tr>
		<tr>
			<td>**</td>
			<td>Any other meta column heading will be treated as a custom user meta value</td>
			<td>optional</td>
		</tr>
		</tbody>
	</table>

	<h2>Notes:</h2>
	<ul  class="import-user-list">
		<li>If no password value is present for new users, a password will be auto-generated.</li>
		<li>Username and email address cannot be updated via import.</li>
	</ul>

	<h2 class="options-header-container">
		<ul class="steps">
			<li class="current">
				<a href="#" data-show="step-1" title="">
					<span class="step-title">Step 4</span>
				</a>
			</li>
			<li></li>
		</ul>
		<div class="steps-description">Import Users</div>
	</h2>
	<p>Go to the Import Users tab once your CSV file is ready to begin the import.</p>

</div>


