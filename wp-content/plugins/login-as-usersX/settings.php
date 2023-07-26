<?php
if(!defined('ABSPATH')) exit;

add_action('admin_menu', 'gwslau_gwslau_loginas_options_page');
function gwslau_gwslau_loginas_options_page() {
	add_menu_page(
		'Login As User',
		'Login As User',
		'administrator',
		'login-as-user-settings',
		'gwslau_login_as_settings_page_html'
	);
}

function gwslau_login_as_settings_page_html() {
		 
	if ( ! current_user_can( 'administrator' ) ) {
		return;
	}

	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'gwslau_loginas_messages', 'loginas_message', __( 'Settings Saved', 'gwslau_login_as_user' ), 'updated' );
	}
	settings_errors( 'gwslau_loginas_messages' );
	
	$option_data = get_option('gwslau_loginas_options');
	?>
	<h2><?php _e( 'Login As Customer or User', 'gwslau_login_as_user' );?></h2>
	<div id="">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="" class="">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div id="dashboard_quick_press" class="postbox ">
						<h2 class="hndle ui-sortable-handle">
							<span>
								<span class="hide-if-no-js"><?php _e( 'General Options', 'gwslau_login_as_user' ); ?></span> 
								<span class="hide-if-js"><?php _e( 'General Options', 'gwslau_login_as_user' ); ?></span>
							</span>
						</h2>
						<div class="inside">
							
							<form action="options.php" method="post">
								<div class="gwslau-input-text-wrap gwslau_login_as" id="gwslau-title-wrap">
									<?php
										settings_fields( 'gwslau_login_as' );
										do_settings_sections( 'gwslau_login_as' );
									?>
								</div>
								<p class="submit">
								<?php
								submit_button( 'Save Settings' );
								?>
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
 <?php
}

add_action('admin_init', 'gwslau_loginas_settings_init');
function gwslau_loginas_settings_init() {
	register_setting( 'gwslau_login_as', 'gwslau_loginas_options');
	add_settings_section(
		'gwslau_loginas_section_developers',
		__( 'Main Options', 'gwslau_login_as_user' ),
		array(),
		'gwslau_login_as'
	);
	add_settings_field(
		'gwslau_loginas_status', 
		__( 'Plugin Status:', 'gwslau_login_as_user' ),
		'gwslau_loginas_field_type_checkbox',
		'gwslau_login_as',
		'gwslau_loginas_section_developers',
		[
			'label_for' => 'gwslau_loginas_status',
			'class' => 'gwslau_loginas_row',
			'gwslau_loginas_custom_data' => 'custom',
		]
	); 
	
	add_settings_field(
		'gwslau_loginas_role', 
		__( 'Buttons Accessibility:', 'gwslau_login_as_user' ),
		'gwslau_loginas_field_type_roles',
		'gwslau_login_as',
		'gwslau_loginas_section_developers',
		[
			'label_for' => 'gwslau_loginas_role',
			'class' => 'gwslau_loginas_row',
			'gwslau_loginas_custom_data' => 'custom',
		]
	);

	add_settings_field(
		'gwslau_loginas_for', 
		__( 'Enable Plugin In:', 'gwslau_login_as_user' ),
		'gwslau_loginas_enable_for',
		'gwslau_login_as',
		'gwslau_loginas_section_developers',
		[
			'label_for' => 'gwslau_loginas_for',
			'class' => 'gwslau_loginas_row',
			'gwslau_loginas_custom_data' => 'custom',
		]
	);

	add_settings_field(
		'gwslau_loginas_redirect', 
		__( 'Redirect To:', 'gwslau_login_as_user' ),
		'gwslau_loginas_redirect_to',
		'gwslau_login_as',
		'gwslau_loginas_section_developers',
		[
			'label_for' => 'gwslau_loginas_redirect',
			'class' => 'gwslau_loginas_row',
			'gwslau_loginas_custom_data' => 'custom',
		]
	);

	add_settings_field(
		'gwslau_loginas_name_show', 
		__( 'Which Name to Show?:', 'gwslau_login_as_user' ),
		'gwslau_loginas_which_name_show',
		'gwslau_login_as',
		'gwslau_loginas_section_developers',
		[
			'label_for' => 'gwslau_loginas_name_show',
			'class' => 'gwslau_loginas_row',
			'gwslau_loginas_custom_data' => 'custom',
		]
	);

	add_settings_field(
		'gwslau_loginas_sticky_position', 
		__( 'Sticy Logout Box Position:', 'gwslau_login_as_user' ),
		'gwslau_loginas_set_sticky_position',
		'gwslau_login_as',
		'gwslau_loginas_section_developers',
		[
			'label_for' => 'gwslau_loginas_sticky_position',
			'class' => 'gwslau_loginas_row',
			'gwslau_loginas_custom_data' => 'custom',
		]
	);
	
}

function gwslau_loginas_field_type_checkbox( $args ) {
	$options = get_option( 'gwslau_loginas_options' );
	?>
	<span class="on_off off"><?php esc_html_e( 'OFF', 'gwslau_login_as_user' ); ?></span>
	<label class="gwslau-switch">
		<input type="checkbox" value="1" id="<?php esc_attr_e( $args['label_for'] ); ?>" data-custom="<?php esc_attr_e( $args['gwslau_loginas_custom_data'] ); ?>" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>]"  <?php isset( $options[ $args['label_for']] ) ? esc_html_e( checked( $options[ $args['label_for'] ], '1', false ) ) : esc_html_e( '' ); ?>>
	</label>
	<span class="on_off on"><?php esc_html_e( 'ON', 'gwslau_login_as_user' ); ?></span>
	<div class="gwslau-description">
		<p><?php esc_html_e( 'Using this option, you can enable or disable the plugin functionality', 'gwslau_login_as_user' ); ?></p>
	</div>
	<?php
}

function gwslau_loginas_field_type_roles( $args ) {
	$options = get_option( 'gwslau_loginas_options' );
	$value = isset($options[ $args['label_for']]) ? $options[ $args['label_for']] : array();
	global $wp_roles;
	$wp_roles = new WP_Roles();
	foreach($wp_roles->roles as $key => $role){
		if(array_key_exists("list_users",$role['capabilities'])){
			if($role['capabilities']['list_users'] == 1){
				$name = $role['name'];
                ?>
				<label class="gwslau-containercheckbox">
				<input type="checkbox" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>][<?php  esc_attr_e($name);?>]" value="<?php esc_attr_e($name);?>"  <?php if(isset($value[$name])){ esc_attr_e('checked'); }?>>
				<?php esc_html_e($name);?>
				</label>	
				<?php
			}
		}
	}
}

function gwslau_loginas_enable_for($args){
	$options = get_option( 'gwslau_loginas_options' );
	$value = isset($options[ $args['label_for']]) ? $options[ $args['label_for']] : array();
	?>
		<label class="gwslau-containercheckbox">
		  <input type="checkbox" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>][users_page]" value="users_page" <?php isset($value["users_page"]) ? esc_attr_e('checked') : esc_attr_e('') ?>>
		  Users Page
		</label>

		<label class="gwslau-containercheckbox">
		  <input type="checkbox" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>][users_profile_page]" value="users_profile_page" <?php isset($value["users_profile_page"]) ? esc_attr_e('checked') : esc_attr_e('') ?>>
		  User's Profile Page
		</label>

        <?php
        if ( is_plugin_active('woocommerce/woocommerce.php') ) {
            ?>
            <label class="gwslau-containercheckbox">
            <input type="checkbox" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>][orders_page]" value="orders_page" <?php isset($value["orders_page"]) ? esc_attr_e('checked') : esc_attr_e('') ?>>
            Orders Page
            </label>
    
            <label class="gwslau-containercheckbox">
            <input type="checkbox" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>][order_edit_page]" value="order_edit_page" <?php isset($value["order_edit_page"]) ? esc_attr_e('checked') : esc_attr_e('') ?>>
            Order Edit Page
            </label>
            <?php
        }
        ?>
	 <?php
}

function gwslau_loginas_redirect_to($args){
	$options = get_option( 'gwslau_loginas_options' );
	$value = isset($options[ $args['label_for']]) ? $options[ $args['label_for']] : '';
	?>
	<div class="gwslau-redirect-wrapper">
		<div class="gwslau-site-url-wrapper">
			<?php echo esc_url(home_url('/'));?>
		</div>
		<div class="gwslau-custom-url-wrapper">
			<input type="text" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="gwslau_loginas_redirect" placeholder="example: my-account" value="<?php esc_attr_e($value);?>">
		</div>
	</div>
	<div class="gwslau-description">
		<p>When you (Admin) will click on Login as this user button, it will redirect to this URL.</p>
		<p>You can enter any page path in the input field, so you will be redirected directly to that page.</p>
		<p>Leave it blank if you would like to be redirected to the home page (default)</p>
	</div>
	<?php
}

function gwslau_loginas_which_name_show($args){
	$options = get_option( 'gwslau_loginas_options' );
	$value = isset($options[ $args['label_for']]) ? $options[ $args['label_for']] : 'user_login';
	?>
	<select id="gwslau_loginas_name_show" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>]">
		<option value="user_login" <?php ($value == 'user_login') ? esc_attr_e("selected") : esc_attr_e("");?>>Username</option>
		<option value="firstname" <?php ($value == 'firstname') ? esc_attr_e("selected") : esc_attr_e("");?>>Firstname</option>
		<option value="full_name" <?php ($value == 'full_name') ? esc_attr_e("selected") : esc_attr_e("");?>>Full Name</option>
		<option value="nickname" <?php ($value == 'nickname') ? esc_attr_e("selected") : esc_attr_e("");?>>Nickname</option>
	</select>
	<div class="gwslau-description">
		<p>Choose which you name want to be displayed on the "Login as User" button.</p>
		<p>For example "Login as john", or "Login as John", or "Login as "Johnathan, or Login as "John Doe".</p>
	</div>
	<?php
}

function gwslau_loginas_set_sticky_position($args){
	$options = get_option( 'gwslau_loginas_options' );
	$value = isset($options[ $args['label_for']])?$options[ $args['label_for']]:'left';
	?>
	<select id="gwslau_loginas_sticky_position" name="gwslau_loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>]">
		<option value="left" <?php ($value == 'left') ? esc_attr_e("selected") : esc_attr_e("");?>>Left</option>
		<option value="right" <?php ($value == 'right') ? esc_attr_e("selected") : esc_attr_e("");?>>Right</option>
		<option value="top" <?php ($value == 'top') ? esc_attr_e("selected") : esc_attr_e("");?>>Top</option>
		<option value="bottom" <?php ($value == 'bottom') ? esc_attr_e("selected") : esc_attr_e("");?>>Bottom</option>
	</select>
	<div class="gwslau-description">
		<p>Choose at which position you want to see "Login as user" toolbar.</p>
	</div>
	<?php
}