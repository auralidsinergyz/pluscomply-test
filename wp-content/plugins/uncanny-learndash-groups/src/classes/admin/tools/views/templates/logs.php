<?php
/**
 * Debug/logs template.
 *
 * @package uncanny_groups_for_learndash\Tools\Logs\Actions
 * @package uncanny_groups_for_learndash\Tools\Wrapper
 *
 * @since 4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Call jquery ui core and tabs.
wp_enqueue_script( 'jquery-ui-core' );
wp_enqueue_script( 'jquery-ui-tabs' );

// Get log files.
$log_files = $this->logger->get_log_files();

?>

<?php if ( $this->logger->is_log_dir_exists() || empty( $log_files ) ) : ?>
	<h4>
		<?php esc_html_e( 'There are no log files found.', 'uncanny-learndash-groups' ); ?>
	</h4>
	<?php return; ?>
<?php endif; ?>

<?php $flash_message = $this->logger->get_flash_message(); ?>

<?php if ( false !== $flash_message ) : ?>

	<div class="notice notice-<?php echo esc_attr( $flash_message['type'] ); ?> is-dismissible">
		<p>
			<?php echo esc_html( $flash_message['message'] ); ?>
		</p>
	</div>

<?php endif; ?>

<div class="wrap uap">

	<section id="tabs">

		<ul class="nav-tab-wrapper uap-nav-tab-wrapper">
			<?php if ( ! empty( $log_files ) ) : ?>
				<?php foreach ( $log_files as $log_file ) : ?>
					<li>
						<a class="nav-tab" href="#<?php echo esc_attr( sanitize_title( $log_file ) ); ?>">
							<?php echo esc_html( $log_file ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>

		<section class="uap-logs">
			<div class="uap-log-table-container">
				<?php if ( ! empty( $log_files ) ) : ?>
					<?php foreach ( $log_files as $log_file ) : ?>
						<section class="uap-logs" id="<?php echo esc_attr( sanitize_title( $log_file ) ); ?>">
							<h3 class="uap-logs-file-action">
								<?php echo esc_html( $log_file ); ?>
								<a class="ulgm-logs-delete button button-secondary button-small" href="<?php echo esc_url( $this->logger->get_delete_uri( $log_file ) ); ?>">
									<?php echo esc_html__( 'Delete log', 'uncanny-learndash-groups' ); ?>
								</a>
							</h3>
							<textarea rows="50" style="width:100%;font-family: monospace; font-size:12px;"><?php echo esc_textarea( $this->logger->get_log_content( $log_file ) ); ?></textarea>
						</section>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</section>

	</section>
</div>

<script>
jQuery( document ).ready(function( $ ) {
	"use strict";

	$("#tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
	$("#tabs li").removeClass("ui-corner-top").addClass("ui-corner-left");

	$('a.ulgm-logs-delete').on('click', function(e){
		return confirm( "<?php echo esc_html__( 'Are you sure you want to delete this log?', 'uncanny-learndash-groups' ); ?>" );
	});
});
</script>
