<?php

namespace uncanny_learndash_codes;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">
    <div class="ulc">

    	<?php 

        // Add admin header and tabs
        $tab_active = 'uncanny-codes-plugins';
        include Config::get_template( 'admin-header.php' );

        ?>

        <div class="ulc__admin-content ulc-learndash-plugins">
			<?php

			$product_id = 5;
			$json       = wp_remote_get( 'https://www.uncannyowl.com/wp-json/uncanny-rest-api/v1/download/' . $product_id . '?wpnonce=' . wp_create_nonce( time() ) );

			if ( 200 === $json['response']['code'] ){
				$data = json_decode( $json['body'], true );
				if ( $data ){
					echo $data;
				}
			}

			?>
		</div>

	</div>
</div>