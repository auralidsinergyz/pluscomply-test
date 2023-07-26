<?php
/**
 * The main template.
 *
 * @since 4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">

	<div class="ulg-tools">

		<?php $this->get_header(); ?>

		<h2 class="sr-only">&nbsp;</h2>

		<?php $this->get_tabs(); ?>

		<?php $this->get_active_content(); ?>

	</div>

</div>
