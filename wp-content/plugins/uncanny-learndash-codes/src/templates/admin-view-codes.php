<?php

namespace uncanny_learndash_codes;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap uo-ulc-admin">
	<div class="ulc">
		<?php 

		// Add admin header and tabs
		$tab_active = 'uncanny-learndash-codes-view';
		include Config::get_template( 'admin-header.php' );

		?>

		<div class="ulc__admin-content">
			<div id="page_coupon_stat">
				<h2></h2> <!-- LearnDash notice will be shown here -->

				<div class="uo-codes-heading">
					<form class="uo-codes-search" method="get" action="">
						<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
			            <?php if ( ! empty( $_GET['group_id'] )){?>
			                <input type="hidden" name="group_id" value="<?php echo $_GET['group_id'] ?>"/>
			            <?php }?>
						<?php $table->search_box( __('Search Codes', 'uncanny-learndash-codes'), Config::get_project_name() ); ?>
					</form>
				</div>

				<div class="uo-codes-buttons">
					<?php $table->views(); ?>
				</div>

				<div class="uo-codes-list">
					<?php $table->display(); ?>
				</div>
			</div>
		</div>
	</div>
</div>