<?php
/** Our namespace. */
namespace UserExportWithMeta;

/** Aliases. */

use Exception;
use League\Csv\EncloseField;
use League\Csv\EscapeFormula;
use League\Csv\Writer;
use SettingsAsWoocommerce\Tab;
use SplFileObject;
use SplTempFileObject;

/**
 * Class SettingsPage.
 */
class SettingsPage extends Tab {

	/**
	 * Model User.
	 *
	 * @var WPUsers
	 */
	public $users;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'tab1';
		$this->label = 'Export Users to CSV';

		/** Code for dealing with User records is on a separated class. */
		$this->users = new Users();

		/** Add custom field type. */
		add_action( 'uewm_settings_admin_field_select_with_text', array( $this, 'select_with_text' ) );

		/** Load jQuery's Sortable. */
		add_action( 'uewm_settings_load_page', array( $this, 'load_page' ) );

		/** Inherited. */
		parent::__construct();
	}

	/**
	 * Get assets (CSS/JS).
	 *
	 * @return array.
	 */
	public function get_assets() {
		return array(
			'assets/index.css',
			'assets/index.js',
			'node_modules/select2/dist/css/select2.css',
			'node_modules/select2/dist/js/select2.js',
		);
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = array(
			array(
				'type' => 'title',
				'name' => '',
			),
			array(
				'type'    => 'multiselect',
				'title'   => 'Roles',
				'id'      => 'uewm_roles',
				'class'   => 'select2',
				'options' => $this->users->get_all_roles(),
			),
			array(
				'type'    => 'multiselect',
				'title'   => 'Columns',
				'id'      => 'uewm_columns',
				'class'   => 'select2',
				'options' => $this->users->get_all_columns(),
			),
			array(
				'type'  => 'checkbox',
				'title' => 'Use a custom field separator (,) and text qualifier (")',
				'id'    => 'uewm_use_custom_csv_settings',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'general-section-end',
			),
			array(
				'type' => 'title',
				'name' => 'Field Separator and Text Qualifier',
				'id'   => 'uewm_custom_csv_settings',
			),
			array(
				'type'       => 'select_with_text',
				'name'       => 'Field Separator',
				'id'         => 'uewm_field_separator',
				'class'      => 'width_150',
				'options'    => array(
					'comma'     => 'Comma (,)',
					'semicolon' => 'Semicolon (;)',
					'tab'       => 'Tab (\t)',
					'space'     => 'Space ( )',
					'custom'    => 'Other',
				),
				'text_id'    => 'uewm_custom_field_separator',
				'text_title' => 'Define it here: ',
			),
			array(
				'type'       => 'select_with_text',
				'name'       => 'Text Qualifier',
				'id'         => 'uewm_text_qualifier',
				'class'      => 'width_150',
				'options'    => array(
					'double-quote' => 'Double-quote (")',
					'quote'        => 'Quote (\')',
					'custom'       => 'Other',
				),
				'text_id'    => 'uewm_custom_text_qualifier',
				'text_title' => 'Define it here: ',
			),
			array(
				'type' => 'hidden',
				'id'   => 'uewm_custom_field_separator',
			),
			array(
				'type' => 'hidden',
				'id'   => 'uewm_custom_text_qualifier',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'custom-delimiter-section-end',
			),
		);

		return $settings;
	}

	/**
	 * Load jQuery's sortable.
	 */
	public function load_page() {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	/**
	 * Save and generate CSV. Called on form submit.
	 */
	public function save() {
		/** Execute this block on a try-catch because CSV lib can throw exceptions. */
		try {
			/** Save settings. */
			$this->update_options( $this->get_settings() );

			/** SECURITY: Only valid column names. */
			$allowlist = $this->users->get_all_columns();

			/** SECURITY: Do not export sensitive data, even if they are on the allow list. */
			$denylist = array(
				'user_pass',
				'user_activation_key',
				'session_tokens',
				'wp_user-settings',
				'wp_user-settings-time',
				'wp_capabilities',
				'community-events-location',
			);

			/** Get selected columns. */
			$columns = get_option( 'uewm_columns' );
			$columns = $columns ? $columns : $allowlist; /** Empty = All. */

			/** Filter columns. */
			$columns = array_intersect( $columns, $allowlist );
			$columns = array_diff( $columns, $denylist );

			/**
			 * Delimiter / Enclosure.
			 */
			$delimiter_char      = null;
			$enclosure_char      = null;
			$has_custom_settings = get_option( 'uewm_use_custom_csv_settings' );
			if ( 'yes' === $has_custom_settings ) {
				$delimiter_char = get_option( 'uewm_field_separator' );
				$enclosure_char = get_option( 'uewm_text_qualifier' );
				if ( 'custom' === $delimiter_char ) {
					$delimiter_char = get_option( 'uewm_custom_field_separator' );
				}
				if ( 'custom' === $enclosure_char ) {
					$enclosure_char = get_option( 'uewm_custom_text_qualifier' );
				}
			}
			$enclosure_char = $enclosure_char ? $this->getCharByName( $enclosure_char ) : '"';
			$delimiter_char = $delimiter_char ? $this->getCharByName( $delimiter_char ) : ',';

			/** Get selected users (ids). */
			$roles = get_option( 'uewm_roles' );
			$ids   = $roles ? $this->users->get_user_ids_by_roles( $roles ) : $this->users->get_user_ids();

			$csv = (Writer::createFromStream(tmpfile()))
				->setDelimiter( $delimiter_char )
				->setEnclosure( $enclosure_char )
				->setEscape('\\')
				->addFormatter(new EscapeFormula('`'));

			EncloseField::addTo($csv, "\t\x1f");

			$csv->insertOne( $columns );

			/** Process in batches of 1k users */
			$page_size  = 1000;
			$page_count = floor( ( count( $ids ) - 1 ) / $page_size ) + 1;
			for ( $i = 0; $i < $page_count; $i ++ ) {
				$ids_page = array_splice( $ids, 0, $page_size );

				$data = $this->users->get_users_data( $ids_page );

				$csv_data = $this->get_csv_data( $data, $columns );

				$csv->insertAll( $csv_data );
			}

			/** Close stream and quit. */
			$csv->output(date( 'Y-m-d-H-i' ) . '-users.csv');

			die();
		} catch ( Exception $e ) {
			$this->wc->add_error( $e->getMessage() );
		}
	}

	/**
	 * Custom field type.
	 *
	 * @param array $value An associative array with the field data.
	 */
	public function select_with_text( $value ) {
		$option_value = $value['value'];
		?>
		<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
			<select
				class="<?php echo esc_attr( $value['class'] ); ?>"
				name="<?php echo esc_attr( $value['id'] ); ?>"
			>
		<?php
		/** Scan array. */
		foreach ( $value['options'] as $key => $val ) {
			$selected = $key === $option_value ? 'selected="selected" ' : '';
			echo '<option value="' . esc_attr( $key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $val ) . '</option>' . "\n";
		}
		echo "</select>\n";

		/** Text input wrapper. */
		echo '<span class="select-with-text-wrapper">';

		/** Label. */
		echo '<span>' . esc_attr( $value['text_title'] ) . '</span>';

		/** Input. */
		$saved_text_value = get_option( $value['text_id'] );
		echo '<input
				maxlength=1
				type="text"
				name="' . esc_attr( $value['text_id'] ) . '"
				value="' . esc_attr( $saved_text_value ) . '"
			/>';

		/** End wrapper. */
		echo '</span>';

		echo '</td></tr>';
	}

	protected function get_csv_data( $data, $columns ) {
		$result = array();

		foreach ( $data as $row ) {
			$output_row = array();

			/** Add missing columns to the row. */
			foreach ( $columns as $column_name ) {
				$output_row[] = array_key_exists( $column_name, $row )
					? $row[ $column_name ]
					: '';
			}

			$result[] = $output_row;
		}

		return $result;
	}

	protected function getCharByName($name) {
		/** Predefined options. */
		$predefined_options = array(
			'comma'     => ',',
			'semicolon' => ';',
			'tab'       => "\t",
			'space'     => ' ',
			'double-quote' => '"',
			'quote'        => "'",
		);

		/** [SECURITY] "=+-@" are not allowed! */
		$regex = '/^[\=\+\-\@](.*)$/';
		if ( 1 === preg_match( $regex, $name ) ) {
			throw new Exception( 'These chars are not allowed: =+-@' );
		}

		/** Using a predefined value. */
		if ( array_key_exists( $name, $predefined_options ) ) {
			return $predefined_options[ $name ];
		}

		return $name;
	}
}
