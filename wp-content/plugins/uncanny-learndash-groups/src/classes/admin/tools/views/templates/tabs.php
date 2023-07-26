<?php
/**
 * The status page template.
 *
 * @since 4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<nav class="nav-tab-wrapper">

	<div class="ulgm-admin-nav">

		<div class="ulgm-admin-nav-items">

			<?php foreach ( $this->get_tab_list() as $tools_tab ) : ?>

				<a href="<?php echo esc_url( $this->get_action_url( $tools_tab['id'] ) ); ?>" 
					class="<?php echo $this->get_tab_class_attribute( $tools_tab['id'] ); ?>"> <?php //phpcs:ignore ?>

					<?php echo esc_html( $tools_tab['label'] ); ?>

				</a>

			<?php endforeach; ?>

			<span class="ulgm-admin-nav-social-icons">

				<a href="https://www.facebook.com/UncannyOwl/" target="_blank"
					class="ulgm-admin-nav-social-icon ulgm-admin-nav-social-icon--facebook"
					ulg-tooltip-admin="<?php esc_attr_e( 'Follow us on Facebook', 'uncanny-learndash-groups' ); ?>">
					<span class="ulg-icon ulg-icon--facebook"></span>
				</a>

				<a href="https://twitter.com/UncannyOwl" target="_blank"
					class="ulgm-admin-nav-social-icon ulgm-admin-nav-social-icon--twitter"
					ulg-tooltip-admin="<?php esc_attr_e( 'Follow us on Twitter', 'uncanny-learndash-groups' ); ?>">
					<span class="ulg-icon ulg-icon--twitter"></span>
				</a>

				<a href="https://www.linkedin.com/company/uncannyowl" target="_blank"
					class="ulgm-admin-nav-social-icon ulgm-admin-nav-social-icon--linkedin"
					ulg-tooltip-admin="<?php esc_attr_e( 'Follow us on LinkedIn', 'uncanny-learndash-groups' ); ?>">
					<span class="ulg-icon ulg-icon--linkedin"></span>
				</a>

			</span>

		</div><!--.ulgm-admin-nav-items-->

	</div><!--.ulgm-admin-nav-->

</nav><!--.nav-tab-wrapper-->
