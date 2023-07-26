<?php
/**
 * Accredible LearnDash Add-on admin settings page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-setting.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-form-helper.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-issuer-helper.php';

$accredible_learndash_issuer = null;
if ( ! empty( get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ) ) ) {
	$accredible_learndash_client   = new Accredible_Learndash_Api_V1_Client();
	$accredible_learndash_response = $accredible_learndash_client->organization_search();

	if ( ! isset( $accredible_learndash_response['errors'] ) ) {
		$accredible_learndash_issuer = $accredible_learndash_response['issuer'];
	}
}
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title"><?php esc_html_e( 'Settings' ); ?></h1>
	</div>
	<div class="accredible-content">
		<form id="settings-form">
			<?php settings_fields( Accredible_Learndash_Admin_Setting::OPTION_GROUP ); ?>
			<div class="accredible-form-field">
				<label for="api_key"><?php esc_html_e( 'API Key' ); ?></label>
				<input
					type='text'
					name='<?php echo esc_html( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ); ?>'
					value='<?php echo esc_html( get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ) ); ?>' 
					id='api_key'/>
			</div>


			<div class="accredible-form-field">
				<label><?php esc_html_e( 'Server Region' ); ?></label>
				<div class="accredible-radio-group">
					<div class="radio-group-item">
						<input
								type='radio'
								name='<?php echo esc_html( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION ); ?>'
								value='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_US ); ?>'
								id='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_US ); ?>'
								<?php
								checked(
									get_option( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION ),
									Accredible_Learndash_Admin_Setting::SERVER_REGION_US
								);
								?>
								/>
						<label class="radio-label" for='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_US ); ?>'>US</label>
					</div>
					<div class="radio-group-item">
						<input
								type='radio'
								name='<?php echo esc_html( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION ); ?>'
								value='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU ); ?>'
								id='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU ); ?>'
								<?php
								checked(
									get_option( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION ),
									Accredible_Learndash_Admin_Setting::SERVER_REGION_EU
								);
								?>
								/>
						<label class="radio-label" for='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU ); ?>'>EU</label>
					</div>
				</div>
			</div>

			<button type="submit" id="submit" name="submit" class="button accredible-button-primary accredible-button-large">Save</button>
		</form>

		<div class="status-tile">
			<div class="logo">
				<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/accredible_logo.png' ); ?>" alt="Accredible logo">
			</div>
			<div id="accredible-issuer-info">
				<?php Accredible_Learndash_Issuer_Helper::display_issuer_info( $accredible_learndash_issuer ); ?>
			</div>
			<div class="help-links">
				<div class="link-title"><?php esc_html_e( 'Need Help?' ); ?></div>
				<ul>
					<li><a href="<?php echo esc_url( 'https://help.accredible.com/integrate-with-accredible' ); ?>" target="_blank"><?php esc_html_e( 'Check our Help Center' ); ?></a></li>
					<li><a href="<?php echo esc_url( 'https://help.accredible.com/kb-tickets/new' ); ?>" target="_blank"><?php esc_html_e( 'Customer Support' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(function(){
		const submitBtn = jQuery('#submit');
		submitBtn.on('click', function(){
			jQuery(this).addClass('accredible-button-spinner');
		});
		jQuery('#settings-form').on('submit', function(){
			event.preventDefault();
			const settings = jQuery(this).serialize();
			jQuery.post('options.php', settings, function(res, status){
				accredibleToast.success('Settings saved successfully', 3000);
				accredibleAjax.loadIssuerInfo().always(function(res){
					const issuerHTML = res.data;
					jQuery('#accredible-issuer-info').html(issuerHTML);
				});
			}).fail(function(err){
				accredibleToast.error('Failed to save settings, please try again later', 3000);
			}).always(function(){
				submitBtn.removeClass('accredible-button-spinner');
			});
		});
	});
</script>
