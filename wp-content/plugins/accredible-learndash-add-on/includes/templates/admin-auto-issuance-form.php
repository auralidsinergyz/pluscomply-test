<?php
/**
 * Accredible LearnDash Add-on admin auto issuance form page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance.php';
require_once plugin_dir_path( __DIR__ ) . '/learndash/class-accredible-learndash-learndash-utils.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-form-helper.php';

$accredible_learndash_utils       = new Accredible_Learndash_Learndash_Utils();
$accredible_learndash_courses     = $accredible_learndash_utils->get_course_options();
$accredible_learndash_lessons     = array();
$accredible_learndash_group       = array();
$accredible_learndash_form_action = 'add_auto_issuance';
$accredible_learndash_issuance    = (object) array(
	'id'                  => null,
	'post_id'             => null,
	'accredible_group_id' => null,
	'kind'                => 'course_completed',
	'course_id'           => null,
	'lesson_id'           => null,
);

if ( ! is_null( $accredible_learndash_issuance_id ) ) {
	$accredible_learndash_issuance_row = Accredible_Learndash_Model_Auto_Issuance::get_row( "id = $accredible_learndash_issuance_id" );

	if ( isset( $accredible_learndash_issuance_row ) ) {
		$accredible_learndash_form_action         = 'edit_auto_issuance';
		$accredible_learndash_issuance            = $accredible_learndash_issuance_row;
		$accredible_learndash_issuance->course_id = $accredible_learndash_issuance->post_id;

		if ( 'lesson_completed' === $accredible_learndash_issuance->kind ) {
			$accredible_learndash_issuance->lesson_id = $accredible_learndash_issuance->post_id;
			$accredible_learndash_issuance->course_id = $accredible_learndash_utils->get_parent_course( $accredible_learndash_issuance->lesson_id )->ID;
			$accredible_learndash_lessons             = $accredible_learndash_utils->get_lesson_options( $accredible_learndash_issuance->course_id );
		}
	}
}
?>

<div class="accredible-wrapper">
	<div class="accredible-content">
		<div class="accredible-form-wrapper">
			<div class="accredible-info-tile">
				<?php esc_html_e( 'Credential groups need to have been created before configuring auto issuance. If none appear, check your API key to make sure your integration is set up properly.' ); ?>
			</div>

			<form id="issuance-form">
				<?php if ( 'add_auto_issuance' === $accredible_learndash_form_action ) { ?>
					<?php wp_nonce_field( $accredible_learndash_form_action, '_mynonce' ); ?>
				<?php } else { ?>
					<?php wp_nonce_field( $accredible_learndash_form_action . $accredible_learndash_issuance->id, '_mynonce' ); ?>
					<input type="hidden" name="id" value="<?php echo esc_attr( $accredible_learndash_issuance_id ); ?>">
				<?php } ?>

				<input type="hidden" id="call_action" name="call_action" value="<?php echo esc_attr( $accredible_learndash_form_action ); ?>">
				<input type="hidden" id="page_num" name="page_num" value="<?php echo esc_attr( $accredible_learndash_issuance_current_page ); ?>">

				<div class="accredible-form-field accredible-fill-width">
					<label><?php esc_html_e( 'Issuance Trigger' ); ?></label>

					<div class="accredible-radio-group">
						<div class="radio-group-item">
							<input type='radio' 
								id='course_trigger' 
								name='accredible_learndash_object[kind]'
								value='course_completed' 
								<?php checked( 'course_completed', $accredible_learndash_issuance->kind ); ?> 
								readonly>
							<label class="radio-label" for='course_trigger'>Course Completion</label>
						</div>
						<div class="radio-group-item">
							<input type='radio'
								id='lesson_trigger'
								name='accredible_learndash_object[kind]'
								value='lesson_completed'
								<?php checked( 'lesson_completed', $accredible_learndash_issuance->kind ); ?> 
								readonly>
							<label class="radio-label" for='lesson_trigger'>Lesson Completion</label>
						</div>
					</div>
				</div>

				<div class="accredible-form-field accredible-fill-width">
					<label for="accredible_learndash_course"><?php esc_html_e( 'Select a course' ); ?></label>

					<select id="accredible_learndash_course" name="accredible_learndash_object[post_id]" required>
						<option disabled selected value></option>
						<?php foreach ( $accredible_learndash_courses as $accredible_learndash_key => $accredible_learndash_value ) : ?>
							<option <?php selected( $accredible_learndash_key, $accredible_learndash_issuance->course_id ); ?> value="<?php echo esc_attr( $accredible_learndash_key ); ?>">
								<?php echo esc_html( $accredible_learndash_value ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php if ( empty( $accredible_learndash_courses ) ) : ?>
						<span class="accredible-form-field-error">
							<?php esc_html_e( 'No courses available. Add courses in LearnDash to continue.' ); ?>
						</span>
					<?php endif; ?>
				</div>

				<div id="accredible-learndash-lesson-form-field" class="accredible-form-field accredible-fill-width" style="display: none;">
					<label for="accredible_learndash_lesson"><?php esc_html_e( 'Select a lesson' ); ?></label>

					<select id="accredible_learndash_lesson">
						<option disabled selected value></option>
						<?php foreach ( $accredible_learndash_lessons as $accredible_learndash_key => $accredible_learndash_value ) : ?>
							<option <?php selected( $accredible_learndash_key, $accredible_learndash_issuance->lesson_id ); ?> value="<?php echo esc_attr( $accredible_learndash_key ); ?>">
								<?php echo esc_html( $accredible_learndash_value ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php if ( empty( $accredible_learndash_lessons ) ) : ?>
						<span class="accredible-form-field-error">
							<?php esc_html_e( 'No lessons available. Select a course or add lessons in LearnDash to continue.' ); ?>
						</span>
					<?php endif; ?>
				</div>

				<div class="accredible-form-field accredible-fill-width">
					<label for="accredible_learndash_group"><?php esc_html_e( 'Select the credential group' ); ?></label>

					<input
						type='text'
						id='accredible_learndash_group_autocomplete'
						placeholder="<?php echo esc_attr( 'Type to search credential group' ); ?>"
						<?php Accredible_Learndash_Admin_Form_Helper::value_attr( $accredible_learndash_group, 'name' ); ?>
						required/>

					<span id="accredible-form-field-group-error-msg" class="accredible-form-field-error accredible-form-field-hidden">
						<?php esc_html_e( 'A valid credential group is required.' ); ?>
					</span>

					<input
						type="hidden"
						id="accredible_learndash_group"
						name="accredible_learndash_object[accredible_group_id]"
						<?php Accredible_Learndash_Admin_Form_Helper::value_attr( $accredible_learndash_issuance, 'accredible_group_id' ); ?>
						readonly/>
				</div>

				<div class="accredible-sidenav-actions">
					<button type="button" id="cancel" class="button accredible-button-flat-natural accredible-button-large">Cancel</button>
					<button type="submit" id="submit" name="submit" class="button accredible-button-primary accredible-button-large">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery( function(){
		const courseControl = jQuery('#accredible_learndash_course');
		const lessonControl = jQuery('#accredible_learndash_lesson');
		const lessonFormField = jQuery('#accredible-learndash-lesson-form-field');

		function isLessonKind() {
			return jQuery('[name="accredible_learndash_object[kind]"]:checked').val() === 'lesson_completed';
		}

		function toggleControls(displayControl, submittedControl) {
			// Disable control from being submitted
			const attributeValue = displayControl.attr('name');
			displayControl.removeAttr('name');
			displayControl.removeAttr('required');
			// Enable control for submission
			submittedControl.attr('name', attributeValue);
			submittedControl.attr('required', true);
		}

		function toggleSelectControlToBeSubmitted() {
			if (isLessonKind()) {
				toggleControls(courseControl, lessonControl); // lesson control is submitted
				// Disable selection if no course is selected
				lessonControl.attr('disabled', !courseControl.val());
				lessonFormField.show();
			} else {
				lessonFormField.hide();
				toggleControls(lessonControl, courseControl); // default, course control is submitted
			}
		}

		function getCourseLessons() {
			const courseId = courseControl.val();
			if(courseId && isLessonKind()) {
				lessonControl.attr('disabled', false);
				// call BE to fetch lessons related to course
				accredibleAjax.getLessons(courseId).done(function(res){
					if (res.data) {
						let options = '';
						Object.keys(res.data).forEach(function(key) {
							options += `<option value="${key}">${res.data[key]}</option>`;
						});
						lessonFormField.find('.accredible-form-field-error').hide(); // hide "no lessons..." error
						lessonControl.html('<option disabled="" selected="" value=""></option>'); // clear existing options
						lessonControl.append(options); // update options
					} else {
						lessonFormField.find('.accredible-form-field-error').show(); // show "no lessons..." error
					}
				});
			}
		}

		function onSelectedKindChange() {
			jQuery('[name="accredible_learndash_object[kind]"]').on('click', function(event){
				toggleSelectControlToBeSubmitted();
				getCourseLessons(); // fetch courses if we have a selected course
			});
		}

		function onSelectedCourseChange() {
			courseControl.on('change', function(){
				getCourseLessons();
			});
		}

		function isFormValid() {
			let isValid = true;
			const controls = ['kind', 'post_id', 'accredible_group_id'];

			for(i=0; i < controls.length; i++){
				if (!jQuery(`[name="accredible_learndash_object[${controls[i]}]"]`).val()) {
					isValid = false;
					break;
				}
			};
			return isValid;
		}

		// Initialize groups autocomplete.
		accredibleAutoComplete.init();

		// Handle changes to kind and course values
		onSelectedKindChange();
		onSelectedCourseChange();

		// Check if we're editing
		if (jQuery('[name="id"]').length) {
			toggleSelectControlToBeSubmitted();
		}

		// Fetch saved group by id to fill autocomplete.
		const submitBtn = jQuery('#submit');
		const groupId = jQuery('#accredible_learndash_group').val();
		if (groupId) {
			submitBtn.attr('disabled', true);
			const autocompleteElem = jQuery('#accredible_learndash_group_autocomplete');
			autocompleteElem.addClass('ui-autocomplete-loading');
			accredibleAjax.getGroup(groupId).done(function(res){
				if (res.data) {
					autocompleteElem.removeClass('ui-autocomplete-loading');
					autocompleteElem.val(res.data.name);
					submitBtn.removeAttr('disabled');
				}
			});
		}

		// Add loading spinner on click.
		submitBtn.on('click', function(){
			if (isFormValid()) {
				jQuery(this).addClass('accredible-button-spinner');
			}
		});

		// Close dialog on cancel click.
		const cancelBtn = jQuery('#cancel');
		cancelBtn.on('click', function() {
			accredibleSidenav.close();
		});

		jQuery('#issuance-form').on('submit', function(event) {
			const formData = {};
			const group_id = jQuery('#accredible_learndash_group').val();
			if ( ! group_id ) {
				submitBtn.removeClass('accredible-button-spinner');
				jQuery('#accredible-form-field-group-error-msg').removeClass('accredible-form-field-hidden'); // show error
				return false; // prevent form submission
			}

			// build formdata object
			jQuery(this).serializeArray().reduce(function(acc, data){
				formData[data.name] = data.value;
				return formData;
			}, formData);

			// call BE
			accredibleAjax.doAutoIssuanceAction(
				formData
			).always(function(res){
				if ((typeof(res) === 'object')) {
					const message = res.data && res.data.message ? res.data.message : res.data;
					if (res.success) {
						if (res.data && res.data.id && res.data.nonce) {
							// update nonce
							jQuery('#_mynonce').val(res.data.nonce);
							// add id input
							jQuery(`<input type="hidden" id="id" name="id" value="${res.data.id}">`).insertAfter('#_mynonce');
							// update action
							jQuery('#call_action').val('edit_auto_issuance');
						}

						accredibleAjax.loadAutoIssuanceListInfo(formData.page_num).always(function(res){
							const issuerHTML = res.data;
							jQuery('.accredible-content').html(issuerHTML);
							// Re-initialise event handlers
							setupEditClickHandler();
							accredibleDialog.init();
							// close dialog
							accredibleSidenav.close();
							// show toast
							accredibleToast.success(message, 5000);
						});
					} else {
						accredibleToast.error(message, 5000);
						submitBtn.removeClass('accredible-button-spinner');
					}
				}
			});

			return false; // prevent form submission
		});
	});
</script>
