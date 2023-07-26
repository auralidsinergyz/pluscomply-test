<?php

namespace uncanny_learndash_groups;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">
	<div class="ulgm">

		<?php

		// Add admin header and tabs
		$tab_active = 'uncanny-groups-plugins';
		include Utilities::get_template( 'admin/admin-header.php' );

		?>

		<div class="ulgm-learndash-plugins">
			<?php

			$product_id = 13839;
			$json       = wp_remote_get( 'https://www.uncannyowl.com/wp-json/uncanny-rest-api/v1/download/' . $product_id . '?wpnonce=' . wp_create_nonce( time() ) );

			if ( ! is_wp_error( $json ) ) {
				if ( 200 === wp_remote_retrieve_response_code( $json ) ) {
					$data = json_decode( $json['body'], true );
					if ( $data ) {
						echo $data;
					}
				}
			}

			?>
		</div>

	</div>
</div>
