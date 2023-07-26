<?php
/**
 * Accredible LearnDash Add-on admin issuance list page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-auto-issuance-list-helper.php';
require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance.php';

$accredible_learndash_current_page = isset( $_GET['page_num'] ) ? sanitize_key( wp_unslash( $_GET['page_num'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$accredible_learndash_page_size    = 20;
$accredible_learndash_page_results = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results(
	$accredible_learndash_current_page,
	$accredible_learndash_page_size
);
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title"><?php esc_html_e( 'Auto Issuances' ); ?></h1>

		<a	href="javascript:void(0);"
			data-accredible-sidenav="true"
			data-accredible-action-params="page=accredible_learndash_auto_issuance&page_num=<?php echo esc_attr( $accredible_learndash_current_page ); ?>"
			class="button accredible-button-primary accredible-button-large">
			<?php esc_html_e( 'New Configuration' ); ?>
		</a>
	</div>
	<div class="accredible-content">
		<?php
		Accredible_Learndash_Auto_Issuance_List_Helper::display_auto_issuance_list_info(
			$accredible_learndash_page_results,
			$accredible_learndash_current_page,
			$accredible_learndash_page_size
		);
		?>
	</div>
</div>

<script type="text/javascript">
	function getParams(actionParamsData) {
		params = {};
		actionParamsData.split('&').reduce(function(acc, curr) {
			const keyValue = curr.split('=');
			acc[keyValue[0]] = keyValue[1];
			return acc;
		}, params);

		return params;
	}

	function setupEditClickHandler() {
		jQuery('[data-accredible-sidenav]').off('click');
		jQuery('[data-accredible-sidenav]').on('click', function(event){
			accredibleToast.closeAll();
			const element = this;
			if (jQuery(element).data('accredibleSidenav')) {
				event.preventDefault();
				let sidenavTitle = 'Add Auto Issuance';
				const actionParamsData = jQuery(element).data('accredibleActionParams');
				const params = getParams(actionParamsData);
				const pageData = {
					page_num: params['page_num']
				};
				if (params['id']) {
					sidenavTitle = 'Edit Auto Issuance';
					pageData.id = params['id'];
				}
				accredibleAjax.loadIssuanceSidenav(pageData).done(function(res){
					accredibleSidenav.open(res.data, { title: sidenavTitle });
				});
			}
		});
	}

	jQuery(function(){
		setupEditClickHandler();
	});
</script>
