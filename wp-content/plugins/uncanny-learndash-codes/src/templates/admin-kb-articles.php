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
		$tab_active = 'uncanny-codes-kb';
		include Config::get_template( 'admin-header.php' );

		?>

		<div class="ulc__admin-content ulc-help">
			<?php

			$kb_category = 'uncanny-learndash-code';
			$json        = wp_remote_get( 'https://www.uncannyowl.com/wp-json/uncanny-rest-api/v1/kb/' . $kb_category . '?wpnonce=' . wp_create_nonce( time() ) );

			if ( 200 === $json[ 'response' ][ 'code' ] ){
				$data = json_decode( $json[ 'body' ], true );
				if ( $data ){
					echo $data;
				}
			}

			?>
		</div>

		<?php if ( $license_is_active ){ ?>

			<a href="<?php echo menu_page_url( 'uncanny-codes-kb', false ) . '&send-ticket=true'; ?>">
				<?php _e( "I can't find the answer to my question.", 'uncanny-learndash-codes' ); ?>
				
			</a>

		<?php } ?>

    </div>
</div>