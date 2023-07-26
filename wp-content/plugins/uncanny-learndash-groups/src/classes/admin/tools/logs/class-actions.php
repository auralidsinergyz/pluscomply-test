<?php
namespace uncanny_learndash_groups\Tools\Logs;

class Actions {

	protected $log_directory = '';

	const NONCE_KEY = 'Aut0mAt0r';

	public function __construct() {
		$this->log_directory = trailingslashit( WP_CONTENT_DIR );
	}

	public function get_delete_uri( $log = '' ) {
		return add_query_arg(
			array(
				'post_type'  => filter_input( INPUT_GET, 'post_type' ),
				'page'       => filter_input( INPUT_GET, 'page' ),
				'action'     => 'logs',
				'delete_log' => 'yes',
				'delete'     => $log,
				'_wpnonce'   => wp_create_nonce( self::NONCE_KEY ),
			),
			admin_url( 'admin.php' )
		);
	}

	public function get_log_directory() {

		return $this->log_directory;

	}

	public function get_log_files() {

		$log_files = array();

		$handle = opendir( $this->get_log_directory() );

		if ( $handle ) {
			$entry = readdir( $handle );
            while ( false !== ( $entry = readdir( $handle ) ) ) { // phpcs:ignore
				$is_uo_log_file = 0 === strpos( $entry, 'uo-' );
				if ( '.' !== $entry && '..' !== $entry ) {
					// Check if its a uo log file and if its not a directory.
					if ( $is_uo_log_file && ! is_dir( $this->get_log_directory() . sanitize_file_name( $entry ) ) ) {
						$log_files[] = sanitize_file_name( $entry );
					}
				}
			}
			closedir( $handle );
		}

		return $log_files;

	}

	public function is_log_dir_exists() {

		return ! file_exists( $this->get_log_directory() );

	}

	public function get_log_content( $log_file ) {

		$log_file = $this->get_log_directory() . sanitize_file_name( $log_file );

		/* translators: Log action */
		$content = sprintf( __( 'Failed to read the contents of: "%s". Log file does not exists', 'uncanny-learndash-groups' ), $log_file );

		if ( file_exists( $log_file ) ) {
			$content = file_get_contents( $log_file );
		}

		return $content;

	}

	public function remove_log( $log_file = '' ) {

		$file_abs_path = $this->get_log_directory() . sanitize_file_name( $log_file );

		if ( file_exists( $file_abs_path ) ) {

			unlink( $file_abs_path );

			$flash_message = array(
				'type'    => 'success',
				/* translators: Log action */
				'message' => sprintf( esc_html__( 'Log file: "%s" has been succesfully removed.', 'uncanny-learndash-groups' ), $file_abs_path ),
			);

			$has_deleted = true;

		} else {

			$flash_message = array(
				'type'    => 'error',
				/* translators: Log action */
				'message' => sprintf( esc_html__( 'Log file: "%s" does not exists.', 'uncanny-learndash-groups' ), $file_abs_path ),
			);

			$has_deleted = false;
		}

		set_transient( 'ulgm_log_file_flash_message', $flash_message, 0 );

		return $has_deleted;

	}

	public function get_flash_message() {

		$message = get_transient( 'ulgm_log_file_flash_message' );

		delete_transient( 'ulgm_log_file_flash_message' );

		return $message;

	}
}

