<?php
/*
  Plugin Name: plugin load filter
  Description: Dynamically activate the selected plugins for each page. Response will be faster by filtering plugins.
  Version: 4.0.13
  Plugin URI: https://celtislab.net/en/wp-plugin-load-filter
  Author: enomoto@celtislab
  Author URI: https://celtislab.net/
  Requires at least: 5.3
  Tested up to: 6.1
  Requires PHP: 7.2
  License: GPLv2
  Text Domain: plf
  Domain Path: /languages
 */
defined( 'ABSPATH' ) || exit;

/***************************************************************************
 * plugin activation / deactivation / uninstall
 **************************************************************************/
if(is_admin()){ 
    //deactivation
    function plugin_load_filter_deactivation( $network_deactivating ) {
        $act = false;
        if (is_multisite()) {
            if(! $network_deactivating){
                global $wpdb;
                $current_blog_id = get_current_blog_id();
                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                foreach ( $blog_ids as $blog_id ) {
                    if($blog_id == $current_blog_id){
                        //current site
                    }
                    else {
                        //other site active check
                        switch_to_blog( $blog_id );
                        if ( is_plugin_active( plugin_basename( __FILE__ )))
                            $act = true;
                    }
                }
                switch_to_blog( $current_blog_id );
            }
        }
        if($act === false){
            flush_rewrite_rules();  //options data 'rewrite_rules' clear for remake.
            if ( file_exists( WPMU_PLUGIN_DIR . "/plf-filter.php" )) { 
                @unlink( WPMU_PLUGIN_DIR . '/plf-filter.php' );
            }
        }
    }
    register_deactivation_hook( __FILE__,   'plugin_load_filter_deactivation' );

    //uninstall
    function plugin_load_filter_uninstall() {
        
        if ( !is_multisite()) {
            delete_option('plf_queryvars');
            delete_option('plf_option' );
            delete_option('plf_addon_options');
        } else {
            global $wpdb;
            $current_blog_id = get_current_blog_id();
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                delete_option('plf_queryvars');
                delete_option('plf_option' );
                delete_option('plf_addon_options');
            }
            switch_to_blog( $current_blog_id );
        }        
        if ( file_exists( WPMU_PLUGIN_DIR . "/plf-filter.php" )) { 
            @unlink( WPMU_PLUGIN_DIR . '/plf-filter.php' );
        }
    }
    register_uninstall_hook(__FILE__, 'plugin_load_filter_uninstall');
    
}

$Plf_setting = new Plf_setting();

class Plf_setting {
    
    static  $plugins_inf = '';  //active plugin/module infomation
    static  $filter = array();  //filter option data
    private $tab_num = 0;
        
    /***************************************************************************
     * Style Sheet
     **************************************************************************/
    function plf_css() { ?>
    <style type="text/css">
    #plugin-filter-select {margin-top: 12px;}
    #plugin-filter-select p {margin: 1em 0;}
    #page-filter-stat { margin-top: 12px;}
    #activation-table { padding-bottom: 8px; border: 1px solid #eee;}
    #activation-table th { text-align: center;}
    #activation-table td { font-size: 97%;}
    #activation-table input[type=checkbox] {  height: 25px; width: 25px; opacity: 0;}
    #plf-post-locale-select { margin-top: 16px;}
    thead .plugins-name { background-color: aliceblue;}
    thead .device-type { background-color: oldlace;}
    thead .plugins-name, tbody .plugins-name { min-width: 144px; max-width: 200px; padding: 3px 3px 3px 5px;}
    thead .device-type, tbody .device-type { min-width: 40px; max-width: 40px; text-align: center; padding: 5px 1px 2px;}
    .dashicons-yes:before { font-size: 20px; border: 1px solid #eee; background-color: whitesmoke;} 
    .device-type label { color: whitesmoke; }
    input.altcheckbox[type="checkbox"] { -webkit-appearance: none; appearance: none; position: absolute;}
    .device-type input.altcheckbox[type="checkbox"]:checked + span.dashicons-yes:before { background-color: yellowgreen; }
    .plf-option-info { font-size:12px; margin:10px 0; padding:3px; background-color:#fff8e5;}
    .edit-post-locale-link span.dashicons { width: 0.8em; height: 0.8em; margin: -0.4em 0.1em 0; vertical-align: middle; text-decoration: initial;}
    </style>
    <?php }    

    function jquery_tab_css() { ?>
    <style type="text/css">
    .ui-helper-reset { margin: 0; padding: 0; border: 0; outline: 0; line-height: 1.5; text-decoration: none; font-size: 100%; list-style: none; }
    .ui-helper-clearfix:before, .ui-helper-clearfix:after { content: ""; display: table; }
    .ui-helper-clearfix:after { clear: both; }
    .ui-helper-clearfix { zoom: 1; }
    .ui-tabs { position: relative; padding: .2em; zoom: 1; } /* position: relative prevents IE scroll bug (element with position: relative inside container with overflow: auto appear as "fixed") */
    .ui-tabs .ui-tabs-nav { margin: 1px 8px; padding: .2em .2em; }
    .ui-tabs .ui-tabs-nav li { list-style: none; float: left; position: relative; top: 0; margin: 1px .3em 0 0; border-bottom: 0; padding: 0; white-space: nowrap; }
    .ui-tabs .ui-tabs-nav li a { float: left; text-decoration: none; }
    .ui-tabs .ui-tabs-nav li.ui-tabs-active { margin-bottom: -1px; padding-bottom: 1px; }
    .ui-tabs .ui-tabs-panel { display: block; border-width: 0;  background: none; }
    .ui-tabs .ui-tabs-nav a { margin: 8px 10px; }
    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { border: 1px solid #dddddd; background-color: #f4f4f4; font-weight: bold; color: #0073ea; }
    .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { color: #0073ea; text-decoration: none; }
    .ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus,.ui-widget-header .ui-state-focus { border: 1px solid #0073ea; background-color: #0073ea; font-weight: bold; color: #ffffff; }
    .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active { border: 1px solid #dddddd; background-color: #0073ea; font-weight: bold; color: #ffffff; }
    .ui-state-hover a, .ui-state-hover a:hover, .ui-state-hover a:link, .ui-state-hover a:visited { color: #ffffff; text-decoration: none; }
    .ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { color: #ffffff; text-decoration: none; }

    #wrap_registration-table, #wrap_activation-table { overflow:auto; height:600px; position: relative;}
    #registration-table input[type=radio], #registration-table input[type=checkbox], #activation-table input[type=checkbox] { height: 25px; width: 25px; opacity: 0;}
    #registration-table th {text-align: center;}
    thead, tbody { display: block;}
    .widefat * { word-wrap: break-word !important;}
    .widefat thead { position:sticky; top:0px; z-index:1;}
    .widefat tr:first-of-type th:first-of-type {position: sticky; left: 0px; text-align: left; background-color: aliceblue; z-index:3;}    
    .widefat th { padding: 8px;}
    .widefat td { padding: 8px;}
    .widefat td:first-of-type { position: sticky; left: 0px; text-align: left; background-color: white;}    
    thead .filter-plugins-name, thead .plugins-name { background-color: aliceblue;}
    thead .urlfilter { background-color: lavenderblush;}
    .filter-none, .filter-admin, .filter-tmpl { background-color: honeydew;}
    thead .device-type { background-color: oldlace;}
    thead .ckbox-type { background-color: lavender;}
    thead .pformat { background-color: honeydew;}
    thead .tmpl-embed { background-color: lightyellow;}
    thead .tmpl-custom { background-color: lavenderblush;}
    thead .filter-plugins-name, tbody .filter-plugins-name { min-width: 240px; max-width: 240px;}
    thead .plugins-name, tbody .plugins-name { min-width: 180px; max-width: 180px;}
    thead .device-type, tbody .device-type, thead .ckbox-type, tbody .ckbox-type, thead .deny-type, tbody .deny-type { min-width: 40px; max-width: 40px; text-align: center;}
    thead .filter-type, tbody .filter-type { min-width: 56px; max-width: 56px; text-align:center;}
    .urlfilter-description { padding: 0 10px 15px;}
    .grid-row { display: flex; flex-flow: row wrap;}        
    .filter-description { padding: 0 10px; width:62%;}
    .side-info { width: 30%;  padding-left: 24px;}
    .exclude-pformat { padding: 5px 0 20px}
    .exclude-pformat label { white-space:nowrap;}
    .exclude-pformat span { margin-right: 12px; }
    .dashicons:before { font-size: 24px; }
    .radio-green label, .radio-red label { color: #ddd; margin-left: -32px; }
    .ckbox-type label { color: #ddd;}
    .radio-green input[type="radio"]:checked + span { color: #8bc34a; }
    .radio-red input[type="radio"]:checked + span { color: tomato; }    
    .ckbox-type input[type="checkbox"]:checked + span { color: #4caf50; }
    /* .dashicons-dismiss:before { background-color: yellowgreen; font-size: 20px; border-radius: 12px; } */
    .deny-type label { color: #4caf50;}
    .deny-type input.altcheckbox[type="checkbox"]:checked + span { color: #ddd; }
    .dashicons-yes:before { font-size: 20px; border: 1px solid #eee; } 
    .device-type input.altcheckbox[type="checkbox"]:checked + span.dashicons-yes:before { background-color: yellowgreen; }
    .language-option { margin-bottom: 28px; }
    </style>
    <?php }    

    /***************************************************************************
     * Plugin Load Filter Option Setting
     **************************************************************************/

    public function __construct() {

        load_plugin_textdomain('plf', false, basename( dirname( __FILE__ ) ).'/languages' );

        self::$filter = get_option('plf_option', array());
        if(empty(self::$filter['optver']) || self::$filter['optver'] < '2'){
            self::$filter['optver'] = '2';
            //ここにデータフォーマットが変わった場合の変換処理を記述
        }
        if(!empty(self::$filter['language'])){
            add_action( 'wp_head', array('Plf_setting', 'altenate_hreflang') );
        }        

        if(is_admin()) {
            add_action( 'plugins_loaded', array(&$this, 'plf_admin_start'), 9999 );
            add_action( 'admin_init', array(&$this, 'action_posts'));
            add_action( 'add_meta_boxes', array(&$this, 'load_meta_boxes'), 10, 2 );
        }
        add_action( 'wp_ajax_plugin_load_filter', array(&$this, 'plf_ajax_postidfilter'));
    }

    //Plugin Load Filter admin setting start 
    public function plf_admin_start() {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        
        self::$plugins_inf = get_plugins();
        //Ver4.0.0 でしか呼び出せないメソッドを呼び出さないよう新設した switch_theme があるかどうかで判定
        if(method_exists('Plf_filter', 'switch_theme')){
            if(class_exists('Plf_filter')){
                if ( is_multisite() )
                    remove_filter('pre_site_option_active_sitewide_plugins', array('Plf_filter', 'active_sitewide_plugins'));
                remove_filter('pre_option_active_plugins', array('Plf_filter', 'active_plugins'));            
            }
            foreach ( self::$plugins_inf as $plugin_key => $a_plugin ) {
                if(is_plugin_inactive( $plugin_key )){
                    unset(self::$plugins_inf[$plugin_key]);
                }
            }
            if(class_exists('Plf_filter')){
                if ( is_multisite() )
                    add_filter('pre_site_option_active_sitewide_plugins', array('Plf_filter', 'active_sitewide_plugins'));
                add_filter('pre_option_active_plugins', array('Plf_filter', 'active_plugins'));
            }

            //jetpack active module 
            if(method_exists('Jetpack', 'get_module')){
                if(class_exists('Plf_filter')){
                    remove_filter('pre_option_jetpack_active_modules', array('Plf_filter', 'active_jetmodules'));
                }
                $modules = Jetpack::get_active_modules();
                $modules = array_diff( $modules, array( 'vaultpress' ) );
                foreach ( $modules as $key => $module_name ) {
                    if(!empty($module_name)){
                        $module = Jetpack::get_module( $module_name );
                        if(!empty($module))
                            self::$plugins_inf['jetpack_module/' . $module_name] = $module;
                    }
                }
                if(class_exists('Plf_filter')){
                    add_filter('pre_option_jetpack_active_modules', array('Plf_filter', 'active_jetmodules'));
                }
            }
            //celtispack active module 
            if(method_exists('Celtispack', 'get_module')){
                if(class_exists('Plf_filter')){
                    remove_filter('pre_option_celtispack_active_modules', array('Plf_filter', 'active_celtismodules'));
                }
                $modules = Celtispack::get_active_modules();
                foreach ( $modules as $key => $module_name ) {
                    if(!empty($module_name)){
                        self::$plugins_inf['celtispack_module/' . $module_name] = Celtispack::get_module( $module_name );
                    }
                }
                if(class_exists('Plf_filter')){
                    add_filter('pre_option_celtispack_active_modules', array('Plf_filter', 'active_celtismodules'));
                }
            }            
            if ( empty( self::$plugins_inf ) ) 
                return;
        }

        add_action('admin_menu', array(&$this, 'plf_option_menu')); 
    }
    
    static function get_plugins_inf() {
        $inf = (!empty(self::$plugins_inf))? self::$plugins_inf : false;
        return $inf;
    }

    //Plugins sub menu add
    public function plf_option_menu() {
        if(current_user_can( 'activate_plugins' )){
            $page = add_menu_page( 'Plugin Load FIlter Settings', __('Plugin Load Filter', 'plf'), 'manage_options', 'plugin_load_filter_admin_manage_page', array(&$this, 'plf_option_page'), 'dashicons-filter', '65.1');
            add_submenu_page( 'plugin_load_filter_admin_manage_page', 'Plugin Load FIlter Settings', __('General Settings', 'plf'), 'manage_options', 'plugin_load_filter_admin_manage_page', array(&$this, 'plf_option_page') );
            add_action( 'admin_print_scripts-'.$page, array(&$this, 'plf_scripts') );
            add_action( 'admin_print_scripts-'.$page, array(&$this, 'deploy_mu_plugins'));
        }
    }

    //Plugin Load Filter setting page script 
    function plf_scripts() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-tabs' );
        add_action( 'admin_head', array(&$this, 'plf_css' ));
        add_action( 'admin_head', array(&$this, 'jquery_tab_css' ));
        add_action( 'admin_footer', array(&$this, 'activetab_script' ));
        add_action( 'admin_notices', array('Plf_setting', 'plf_notice'));       
    }

    //plf-filter.php mu-plugins module set
    public function deploy_mu_plugins() {
        if(wp_mkdir_p( WPMU_PLUGIN_DIR )){
            if ( !file_exists( WPMU_PLUGIN_DIR . "/plf-filter.php" )) { 
                @copy(__DIR__ . '/mu-plugins/plf-filter.php', WPMU_PLUGIN_DIR . '/plf-filter.php');
            }
            else {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
                $dp = get_plugin_data( WPMU_PLUGIN_DIR . "/plf-filter.php", false, false );
                $sp = get_plugin_data( __DIR__ . '/mu-plugins/plf-filter.php', false, false );
                if(version_compare( $dp['Version'], $sp['Version'], '!=')){
                    @copy(__DIR__ . '/mu-plugins/plf-filter.php', WPMU_PLUGIN_DIR . '/plf-filter.php');
                }
            }
        }
    }

    //Notice Message display
    static function plf_notice() {
        $notice = get_transient('plf_notice');
        if(!empty($notice)){
            echo '<div class="notice notice-warning"><p>Plugin Load Filter : ' . $notice . '</p></div>';
            delete_transient('plf_notice');
        }        
    }
    
    //plugin filter option action request (add, update, delete)
    function action_posts() {
        if (current_user_can( 'activate_plugins' )) {
            if( isset($_POST['edit_regist_filter']) ) {
                if(isset($_POST['plfregist'])){
                    check_admin_referer('plugin_load_filter');
                    //url filter
                    $groupkeys = (method_exists('Plf_filter', 'get_active_group'))? Plf_filter::get_active_group() : array();
                    if(!empty($groupkeys) && isset($_POST['plfurlkey'])){
                        $urlkeys = array_keys($_POST['plfurlkey']);
                        foreach( $groupkeys as $item){
                            if(empty( $_POST['plfurlkey'][$item])){
                                self::$filter['plfurlkey'][$item]['plugins'] = '';
                            } else {
                                $plugins = array();
                                foreach ( $_POST['plfurlkey'][$item] as $p_key => $val ) {
                                    if($val == '1')
                                        $plugins[] = $p_key;
                                }
                                $option["plugins"] = implode(",", $plugins);
                                self::$filter['plfurlkey'][$item] = $option;
                            }
                        }
                    }
                    //page type filter
                    foreach( array('_admin', '_pagefilter') as $item){
                        $plugins = array();
                        foreach ( $_POST['plfregist'] as $p_key => $val ) {
                            if($val == $item)
                                $plugins[$p_key] = $val;
                        }
                        if($item == '_pagefilter'){
                            //If all modules is specified filter, in some cases you want to deactivate plugin itself.
                            $jbase = $cbase = '';
                            $jall = $call = true;
                            foreach ( $_POST['plfregist'] as $p_key => $val ) {
                                if(strpos($p_key, 'jetpack/') !== false)
                                    $jbase = $p_key;
                                else if(strpos($p_key, 'celtispack/') !== false)
                                    $cbase = $p_key;
                                else if(strpos($p_key, 'jetpack_module/') !== false){
                                    if($val != '_pagefilter' && $val != '_admin'){
                                        $jall = false;
                                    }
                                }
                                else if(strpos($p_key, 'celtispack_module/') !== false){
                                    if($val != '_pagefilter' && $val != '_admin')
                                        $call = false;
                                }
                            }
                            if(!empty($jbase) && $jall === false)
                                unset($plugins[$jbase]);
                            if(!empty($cbase) && $call === false)
                                unset($plugins[$cbase]);
                        }
                        $option["plugins"] = implode(",", array_keys($plugins));
                        self::$filter[$item] = $option;
                    }
                    //exclude option
                    if(isset($_POST['plf_option']['exclude'])){
                        $exclude = array();
                        foreach ( $_POST['plf_option']['exclude'] as $ft => $v ) {
                            if(!empty($v))
                                $exclude[$ft] = true;
                        }
                        self::$filter['exclude'] = $exclude;
                    } else {
                        self::$filter['exclude'] = array();
                    }
                    
                    //admin bar (filtered stat)
                    self::$filter['admin_bar'] = (isset($_POST['plf_option']['admin_bar']))? 1 : 0;

                    //Language option
                    self::$filter['language'] = (isset($_POST['plf_option']['language']))? 1 : 0;
                    
                    update_option('plf_option', self::$filter );
                }
                header('Location: ' . admin_url('plugins.php?page=plugin_load_filter_admin_manage_page'));
                exit;
                
            } elseif( isset($_POST['clear_regist_filter']) ) {
                check_admin_referer('plugin_load_filter');
                if(!empty(self::$filter['plfurlkey'])){
                    foreach (self::$filter['plfurlkey'] as $key => $val) {
                        unset(self::$filter['plfurlkey'][$key]);
                    }
                }
                foreach( array('_admin', '_pagefilter') as $item){
                    self::$filter[$item] = array();
                }
                self::$filter['exclude'] = array();
                
                //old data unset 
                if(isset(self::$filter['urlkey'])){
                    unset(self::$filter['urlkey']);
                }
                if(isset(self::$filter['urlkeylist'])){
                    unset(self::$filter['urlkeylist']);
                }
                
                self::$filter['admin_bar'] = 0;
                self::$filter['language'] = 0;
                update_option('plf_option', self::$filter );
                header('Location: ' . admin_url('plugins.php?page=plugin_load_filter_admin_manage_page'));
                exit;
                
            } else if(isset($_POST['edit_activate_page_filter']) ) {
                if(isset($_POST['plfactive'])){
                    check_admin_referer('plugin_load_filter');
                    self::$filter['group'] = array();
                    $group = array_keys($_POST['plfactive']);
                    foreach( $group as $item){
                        $plugins = array();
                        foreach ( $_POST['plfactive'][$item] as $p_key => $val ) {
                            if($val == '1')
                                $plugins[] = $p_key;
                        }                            
                        $option["plugins"] = implode(",", $plugins);
                        self::$filter['group'][$item] = $option;
                    }
                    update_option('plf_option', self::$filter );
                }
                header('Location: ' . admin_url('plugins.php?page=plugin_load_filter_admin_manage_page&action=tab_1'));
                exit;
                
            } elseif( isset($_POST['clear_activate_page_filter']) ) {
                check_admin_referer('plugin_load_filter');
                self::$filter['group'] = array();
                update_option('plf_option', self::$filter );
                header('Location: ' . admin_url('plugins.php?page=plugin_load_filter_admin_manage_page&action=tab_1'));
                exit;
            }
            if(!empty($_GET['action']) && $_GET['action']=='tab_1') {
                $this->tab_num = 1;
            }
        }
    }

    //Plugin or Module key to name
    // $type : list/smart/tree
    static function pluginkey_to_name( $infkey, $type='list') {
        $name = '';
        if(strpos($infkey, 'jetpack_module/') !== false){
            if(!empty(self::$plugins_inf[$infkey]['name'])){
                $m_mark = ($type !== 'list')? '-' : 'Jetpack-';
                if($type === 'smart') {
                    if(empty(self::$filter['_pagefilter']['plugins']) || strpos(self::$filter['_pagefilter']['plugins'], 'jetpack/') === false)
                        $name = $m_mark . self::$plugins_inf[$infkey]['name'];
                } else {
                    $name = $m_mark . self::$plugins_inf[$infkey]['name'];
                }
            }
        } elseif(strpos($infkey, 'celtispack_module/') !== false){
            if(!empty(self::$plugins_inf[$infkey]['Name'])){
                $m_mark = ($type !== 'list')? '-' : 'Celtispack-';
                if($type === 'smart') {
                    if(empty(self::$filter['_pagefilter']['plugins']) || strpos(self::$filter['_pagefilter']['plugins'], 'celtispack/') === false)
                        $name = $m_mark . self::$plugins_inf[$infkey]['Name'];
                } else {
                    $name = $m_mark . self::$plugins_inf[$infkey]['Name'];
                }
            }
        } else {
            if(!empty(self::$plugins_inf[$infkey]['Name']))
                $name = self::$plugins_inf[$infkey]['Name'];
        }
        return($name);
    } 

    //Checkbox
	static function checkbox($name, $value, $label = '') {
        return "<label><input type='checkbox' name='$name' value='1' " . checked( $value, 1, false ).  "/> $label</label>";
	}
	static function altcheckbox($name, $value, $label = '') {
        //return "<input type='hidden' name='$name' value='0'><input type='checkbox' name='$name' value='1' " . checked( $value, 1, false ).  "/><label> $label</label>";
        return "<label><input type='checkbox' name='$name' class='altcheckbox' value='1' " . checked( $value, 1, false ).  "/> $label</label>";
	}

    //dropdown list
    static function dropdown($name, $items, $selected, $args = null) {
        $defaults = array(
            'id' => $name,
            'none' => false,
            'class' => null,
            'multiple' => false,
            'select_attr' => ""
        );

        if (!is_array($items))
            return;

        if (empty($items))
            $items = array();

        // Items is in key => value format.  If value is itself an array, use only the 1st column
        foreach ($items as $key => &$value) {
            if (is_array($value))
                $value = array_shift($value);
        }

        extract(wp_parse_args($args, $defaults));

        // If 'none' arg provided, prepend a blank entry
        if ($none) {
            if ($none === true)
                $none = '&nbsp;';
            $items = array('' => $none) + $items;    // Note that array_merge() won't work because it renumbers indexes!
        }

        if (!$id)
            $id = $name;

        $name = ($name) ? "name='$name'" : "";
        $id = ($id) ? "id='$id'" : "";
        $class = ($class) ? "class='$class'" : "";
        $multiple = ($multiple) ? "multiple='multiple'" : "";

        $html = "<select $name $id $class $multiple $select_attr>";

        foreach ((array) $items as $key => $label) {
            $key = esc_attr($key);
            $label = esc_attr($label);

            $html .= "<option value='$key' " . selected($selected, $key, false) . ">$label</option>";
        }
        $html .= "</select>";
        return $html;
    }
       
    public function plfregist_item($key, $chklist, $filter) {
        $p_name = self::pluginkey_to_name($key);
        $opt_name = "plfregist[$key]";
        ?>
        <tr id="plfregist_<?php echo $key; ?>">
          <td class="filter-plugins-name"><?php echo $p_name; ?></td>
          <?php
            foreach($chklist as $urlkey){
                $checked = (empty($filter['plfurlkey'][$urlkey]['plugins']) || false === strpos($filter['plfurlkey'][$urlkey]['plugins'], $key))? false : true;
                echo '<td class="deny-type filter-type">' . self::altcheckbox("plfurlkey[$urlkey][$key]", $checked, '<span class="dashicons dashicons-admin-plugins"></span>') . '</td>';
            }
            $radio = '';
            if(!empty($filter['_admin']['plugins']) && false !== strpos($filter['_admin']['plugins'], $key))
                $radio = '_admin';
            else if(!empty($filter['_pagefilter']['plugins']) && false !== strpos($filter['_pagefilter']['plugins'], $key))
                $radio = '_pagefilter';
          ?>
          <td class="radio-green filter-type"><label><input type="radio" name="<?php echo $opt_name; ?>" value='' <?php checked('', $radio); ?>/><span class="dashicons dashicons-admin-plugins"></span></label></td>
          <td class="radio-red filter-type"><label><input type="radio" name="<?php echo $opt_name; ?>" value="_admin" <?php checked('_admin', $radio); ?>/><span class="dashicons dashicons-admin-plugins"></span></label></td>
          <td class="radio-red filter-type"><label><input type="radio" name="<?php echo $opt_name; ?>" value="_pagefilter" <?php checked('_pagefilter', $radio); ?>/><span class="dashicons dashicons-admin-plugins"></span></label></td>
        </tr>
        <?php
    }

    //Filterring plugins select   
    public function plfregist_table($plugins, $filter) {
    ?>
    <div id="wrap_registration-table">        
    <table id="registration-table" class="widefat">
        <thead>
           <?php
            $groupkeys = (method_exists('Plf_filter', 'get_active_group'))? Plf_filter::get_active_group() : array();
            $urlnum = count($groupkeys);
           ?>
           <tr>
               <th class="filter-plugins-name" rowspan="2"><?php _e('Plugins'); ?></th>
               <?php if($urlnum > 0) { ?>
                 <th class="filter-type urlfilter" colspan="<?php echo $urlnum; ?>" style="font-weight:bold; font-size:smaller;"><?php _e('URL Group Filter', 'plf'); ?></th>
               <?php } ?>
               <th class="filter-type filter-tmpl" colspan="3" style="font-weight:bold; font-size:smaller;"><?php _e('Page Type Filter', 'plf'); ?></th>
           </tr>
           <tr>
               <?php //Addon URL filter group key                
               if($urlnum > 0) {
                    foreach ( $groupkeys as $v) {
                        $_flt = (method_exists('Plf_filter', 'get_slug_filter'))? Plf_filter::get_slug_filter( $v ) : array();
                        $hint = '';
                        foreach ($_flt as $_v) {
                            $hint .= '[' . $_v['slug'] . '] ' . $_v['summary'] . PHP_EOL;
                            
                            if((int)$_v['targetpage'] === 1) {
                                $post_type = (!empty($_v['post_type']))? $_v['post_type'] : '';
                                $taxonomy = '';
                                if(!empty($_v['taxonomies']) && !empty($_v['term'])){
                                    $taxonomy =  " : {$_v['taxonomies']}({$_v['term']})";
                                }
                                if(!empty($post_type)){
                                    $hint .= 'Singular (' . $post_type . ')' . $taxonomy . PHP_EOL;
                                } else {
                                    $hint .= 'Singular ' . $taxonomy .  PHP_EOL;
                                }                        
                            } elseif((int)$_v['targetpage'] === 2) {
                                if(!empty($_v['ajax_action'])){
                                    $hint .= 'Ajax request (' . $_v['ajax_action'] . ')' . PHP_EOL;
                                } else {
                                    $hint .= 'Ajax request' . PHP_EOL;
                                }
                            }
                            
                            $hint .= 'url filter : ';
                            $urlkey = (!empty($_v['url_path']))? array_filter( array_map("trim", explode(PHP_EOL, $_v['url_path']))) : array();
                            if(!empty($urlkey)){
                                foreach ($urlkey as $key) {
                                    $hint .= $key . ' ';
                                }
                            }
                            $urlkey = (!empty($_v['url_q_and']))? array_filter( array_map("trim", explode(PHP_EOL, $_v['url_q_and']))) : array();
                            if(!empty($urlkey)){
                                foreach ($urlkey as $key) {
                                    $hint .= ' AND ' . $key;
                                }                        
                            }
                            $urlkey = (!empty($filter['url_q_not']))? array_filter( array_map("trim", explode(PHP_EOL, $filter['url_q_not']))) : array();
                            if(!empty($urlkey)){
                                foreach ($urlkey as $key) {
                                    $hint .= ' NOT ' . $key;
                                }                        
                            }
                            $hint .= PHP_EOL . PHP_EOL;
                        }
                        echo "<th class='filter-type urlfilter'><span title='$hint' style='font-size:smaller'>{$v}</span></th>";
                    }
               } ?>
               <th class="filter-type filter-none"><span style="font-size:smaller"><?php _e('Normal', 'plf'); ?></span></th>
               <th class="filter-type filter-admin"><span style="font-size:smaller"><?php _e('Admin Type', 'plf'); ?></span></th>
               <th class="filter-type filter-tmpl"><span style="font-size:smaller"><?php _e('Page Type', 'plf'); ?></span></th>
           </tr>
        </thead>
        <tbody class="plugins-table-body">
        <?php
        //plugins filter registoration table
        $plist = array();
        foreach ( $plugins as $p_key => $val ) {
            $name = self::pluginkey_to_name($p_key);
            if(!empty($name)) 
                $plist[$p_key] = '';
        }
        $jlist = $clist = array();
        foreach ( $plist as $p_key => $val ) {
            if(strpos($p_key, 'jetpack_module/') !== false){
                $jlist[$p_key] = $plist[$p_key];
                unset($plist[$p_key]);
            }
            else if(strpos($p_key, 'celtispack_module/') !== false){
                $clist[$p_key] = $plist[$p_key];
                unset($plist[$p_key]);
            }
        }
        //Addon URL filter group key
        $chklist = array();
        if($urlnum > 0) {
            foreach ( $groupkeys as $key) {
                $chklist[] = $key;
            }
        }
        
        foreach ( $plist as $p_key => $val ) {
            $modules = array();
            if(strpos($p_key, 'jetpack/') !== false)
                $modules = $jlist;
            else if(strpos($p_key, 'celtispack/') !== false)
                $modules = $clist;
            else
                $this->plfregist_item($p_key, $chklist, $filter);
            if(!empty($modules)){
                echo "<input type='hidden' name='plfregist[$p_key]' value='_pagefilter'>";
                foreach ( $modules as $m_key => $val) {
                    $this->plfregist_item($m_key, $chklist, $filter);
                }
            }
        }
        ?>
        </tbody>
    </table>
    </div>
    <p></p>
    <div class="grid-row">
      <div class="filter-description">
        <p><strong>[ <?php _e('Page Type Filter', 'plf'); ?> ]</strong></p>
        <?php _e('<strong>Normal</strong> - Exclude plugin from Page Type filter', 'plf'); ?><br />
        <?php _e('<strong>Admin Type</strong> - If you only use plugins for Admin pages.', 'plf'); ?><br />
        <?php _e('<strong>Page Type</strong> - If you want to activate or deactivate plugins for each Page Type and Single page.', 'plf'); ?>
        <p>
        <?php
        $checked = (!empty(self::$filter['admin_bar']))? self::$filter['admin_bar'] : false;
        echo '<span class="admin-bar-option">' . self::checkbox("plf_option[admin_bar]", $checked, __('Add a link to admin bar for displaying the plugins filtered status', 'plf') ) . '</span>';
        ?>
        </p>
        <p><?php _e('* Plugins with `Page Type Filter` selected are blocked, but you can Activate it for various Page type in the `Page Type Activation` and Single Page setting.', 'plf'); ?></p>
    
        <div class="exclude-pformat">
            <p><?php _e('<strong>Exclude Post Format Type</strong> - Choose Post Format Type you are not using. To exclude from Page Type item subject.', 'plf'); ?></p>
            <?php
            $html =  '<div>';
            $pformat = array('image', 'gallery', 'video', 'audio', 'aside', 'status', 'quote', 'link', 'chat' );
            foreach ( $pformat as $type ) {
                $checked = (!empty(self::$filter['exclude'][$type]))? self::$filter['exclude'][$type] : false;
                $label = "<span>$type</span>";
                $html .= self::checkbox("plf_option[exclude][$type]", $checked, $label);
            }
            $html .= '</div>';
            echo $html;
            ?>
        </div>
        <p><strong>[ <?php _e('Post Language Locale', 'plf'); ?> ]</strong></p>
        <p><?php _e('A very simple multilingual feature that uses MO translation files for the selected locale per Post/Page editing screen.','plf'); ?></p>
        <?php
            $checked = (!empty(self::$filter['language']))? self::$filter['language'] : false;
            echo '<div class="language-option">' . self::checkbox("plf_option[language]", $checked, __('Language switching per post', 'plf') ) . '</div>';
        ?>         
      </div>
      <div class="side-info">
      <?php if(! is_plugin_active('plugin-load-filter-addon/plugin-load-filter-addon.php')){ ?>
        <div style="background-color: #f0fff0; border:1px solid #70c370; padding:4px 20px; margin: 10px 0;" >
         <p><strong><?php _e('Introduction of Addon', 'plf'); ?></strong></p>
         <p><?php _e('Thank you for using Plugin Load Filter. We offer URL filtering as Addon. Please consider using Addon!', 'plf'); ?></p>
         <p><?php _e('See more information ', 'plf'); ?><a target="_blank" rel="noopener" href="https://celtislab.net/en/wp-plugin-load-filter-addon/"> Plugin Load Filter Addon</a></p>
        </div>
      <?php } ?> 
      <?php if(! is_plugin_active('realtime-img-optimizer/realtime-img-optimizer.php')){ ?>
        <div style="background-color: #f0fff0; border:1px solid #70c370; padding:4px 20px; margin: 10px 0;" >
         <p><strong><?php _e('Introduction of Realtime Image Optimizer', 'plf'); ?></strong></p>
         <p><?php _e('We sell Image Optimization plugin. Reduce and speed up data size by converting to WebP / AVIF.', 'plf'); ?></p>
         <p><?php _e('See more information ', 'plf'); ?><a target="_blank" rel="noopener" href="https://celtislab.net/en/wp-realtime-image-optimizer/"> Realtime Image Optimizer</a></p>
        </div>
      <?php } ?>           
      </div>
    </div>
    <?php
    }
    
    //Activate plugins select from Page Filter  
    public function _plfactive_checkbox_row($p_key, $select_cvplugins, $chklist, $filter) {

        $selplugins = array_map("trim", explode(',', $select_cvplugins));
        $devlist = array('desktop','mobile');
        if(in_array( $p_key, $selplugins )){
            $p_name = self::pluginkey_to_name($p_key);                
            echo "<tr><td class='plugins-name'>$p_name</td>";
            foreach($devlist as $devtype){
                $checked = (empty($filter['group'][$devtype]['plugins']) || false === strpos($filter['group'][$devtype]['plugins'], $p_key))? false : true;
                echo '<td class="device-type">' . self::altcheckbox("plfactive[$devtype][$p_key]", $checked, '<span class="dashicons dashicons-yes"></span>') . '</td>';
            }
            foreach($chklist as $pgtype){
                $checked = (empty($filter['group'][$pgtype]['plugins']) || false === strpos($filter['group'][$pgtype]['plugins'], $p_key))? false : true;
                echo '<td class="ckbox-type">' . self::altcheckbox("plfactive[$pgtype][$p_key]", $checked, '<span class="dashicons dashicons-admin-plugins"></span>') . '</td>';
            }
            echo "</tr>";
        }
    }
    
    public function plfactive_table($plugins, $select_cvplugins, $filter) {
        if(empty($select_cvplugins))
            return;
        
    ?>
    <div id="wrap_activation-table">
    <table id="activation-table" class="widefat">
        <thead>
           <tr><th class="plugins-name"><?php _e('Plugins'); ?></th>
               <th class="device-type"><span title="<?php _e('Desktop Device', 'plf'); ?>" class="dashicons dashicons-desktop"></span><br /><span style="font-size:xx-small">Desktop</span></th>
               <th class="device-type"><span title="<?php _e('Mobile Device', 'plf'); ?>" class="dashicons dashicons-smartphone"></span><br /><span style="font-size:xx-small">Mobile</span></th>
               <th class="ckbox-type"><span title="<?php _e('Home/Front-page', 'plf'); ?>" class="dashicons dashicons-admin-home"></span><br /><span style="font-size:xx-small">Home</span></th>
               <th class="ckbox-type"><span title="<?php _e('Archive page', 'plf'); ?>" class="dashicons dashicons-list-view"></span><br /><span style="font-size:xx-small">Archive</span></th>
               <th class="ckbox-type"><span title="<?php _e('Search page', 'plf'); ?>" class="dashicons dashicons-search"></span><br /><span style="font-size:xx-small">Search</span></th>
               <th class="ckbox-type"><span title="<?php _e('Attachment page', 'plf'); ?>" class="dashicons dashicons-media-default"></span><br /><span style="font-size:xx-small">Attach</span></th>
               <th class="ckbox-type"><span title="<?php _e('Page', 'plf'); ?>" class="dashicons dashicons-admin-page"></span><br /><span style="font-size:xx-small">Page</span></th>
               <th class="ckbox-type pformat"><span title="<?php _e('Post : ', 'plf'); _e('Standard', 'plf'); ?>" class="dashicons dashicons-admin-post"></span><br /><span style="font-size:xx-small">Post</span></th>
               <?php
                $pformat = array('image', 'gallery', 'video', 'audio', 'aside', 'status', 'quote', 'link', 'chat' );
                $exclude = array();
                if(!empty($filter['exclude'])){
                    foreach ( $filter['exclude'] as $type => $v) {
                        if(!empty($v)){
                            $exclude[] = $type;
                        }
                    }
                }
                foreach ( $pformat as $type) {
                    if(!in_array($type, $exclude)){
                        $title = __('Post : ', 'plf') . $type;
                        $icon  = ($type === "link")? "dashicons-admin-links" : "dashicons-format-$type";
                        echo '<th class="ckbox-type pformat"><span title="' . $title . '" class="dashicons ' . $icon .'"></span><br /><span style="font-size:xx-small">' . $type .'</span></th>';
                    }
                }
                if(function_exists('is_embed')){
                    $title = __('WordPress Embed Content Card (API)', 'plf');
                    echo "<th class='ckbox-type tmpl-embed'><span title='$title' style='font-size:xx-small'>Embed Content</span></th>";
                }
                $post_types = get_post_types( array('public' => true, '_builtin' => false) );                    
                foreach ( $post_types as $post_type ) {
                    if(!empty($post_type)){
                       $title = __('Custom Post : ', 'plf') . $post_type;
                       echo "<th class='ckbox-type tmpl-custom'><span title='$title' style='font-size:xx-small'>$post_type</span></th>";
                    }
                }
               ?>
           </tr>
        </thead>
        <tbody class="plugins-table-body">
        <?php
        $nplugins = $jmodules = $cmodules = $allmodule = array();
        foreach ( $plugins as $p_key => $p_data ) {
            $p_name = self::pluginkey_to_name($p_key);
            if(empty($p_name))
                continue;
            if(strpos($p_key, 'jetpack_module/') !== false)
                $jmodules[] = $p_key;
            else if(strpos($p_key, 'celtispack_module/') !== false)
                $cmodules[] = $p_key;
            else 
                $nplugins[] = $p_key; 
        }
        $chklist = array('home', 'archive', 'search', 'attachment', 'page', 'post');
        foreach ( $pformat as $type) {
            if(!in_array($type, $exclude)){
                $chklist[] = "post-$type";
            }
        }
        if(function_exists('is_embed')){
            $chklist[] = 'content-card';
        }
        $post_types = get_post_types( array('public' => true, '_builtin' => false) );                    
        foreach ( $post_types as $post_type ) {
            $chklist[] = $post_type;
        }
        foreach ( $nplugins as $p_key ) {
            if(strpos($p_key, 'jetpack/') !== false){
                foreach ( $jmodules as $p_key ) {
                    $this->_plfactive_checkbox_row($p_key, $select_cvplugins, $chklist, $filter);
                }
            }
            else if(strpos($p_key, 'celtispack/') !== false){                
                foreach ( $cmodules as $p_key ) {
                    $this->_plfactive_checkbox_row($p_key, $select_cvplugins, $chklist, $filter);
                }
            }
            else {
                $this->_plfactive_checkbox_row($p_key, $select_cvplugins, $chklist, $filter);
            }
        }
        ?>
        </tbody>
    </table>
    </div>
    <?php
    }
    
    //Option Setting Form Display
    public function plf_option_page() {
        $clear_dialog = __('Plugin Load Filter Settings\nClick OK to clear it.', 'plf');
    ?>
    <h2><?php _e('Plugin Load Filter Settings', 'plf'); ?></h2>
    <p></p>
    <div id="plf-setting-tabs">
        <ul>
            <li><a href="#plf-registration-tab" ><?php _e('Filter Registration', 'plf'); ?></a></li>
            <li><a href="#plf-activation-tab" ><?php _e('Page Type Activation', 'plf'); ?></a></li>
        </ul>
        <div id="plf-registration-tab" style="display : none;">               
            <form method="post" autocomplete="off">
                <?php wp_nonce_field( 'plugin_load_filter'); ?>
                <?php $this->plfregist_table(self::$plugins_inf, self::$filter); ?>
                <p class="submit">
                    <input type="submit" class="button-primary" name="clear_regist_filter" value="<?php _e('Clear', 'plf'); ?>" onclick="return confirm('<?php echo $clear_dialog; ?>')" />&nbsp;&nbsp;&nbsp;
                    <input type="submit" class="button-primary" name="edit_regist_filter" value="<?php _e('Filter Entry &raquo;', 'plf'); ?>" />
                </p>
            </form>
        </div>
        <div id="plf-activation-tab" style="display : none;">
            <form method="post" autocomplete="off">
                <?php wp_nonce_field( 'plugin_load_filter'); ?>
                <?php
                $pgfilter = (!empty(self::$filter['_pagefilter']['plugins']))? self::$filter['_pagefilter']['plugins'] : array();
                if(!empty($pgfilter)){
                    $this->plfactive_table(self::$plugins_inf, $pgfilter, self::$filter);
                    ?>
                    <br />
                    <p><?php _e('Select plugins to be activated for each page type by clicking on <span class="dashicons dashicons-admin-plugins"></span> mark from "page type filter" registered plugins.', 'plf') ?><br />
                       <?php _e('You can also select plugins to activate from Post/Page content editing screen.', 'plf') ?>
                    </p>
                    <p class="submit">
                      <input type="submit" class="button-primary" name="clear_activate_page_filter" value="<?php _e('Clear', 'plf'); ?>" onclick="return confirm('<?php echo $clear_dialog; ?>')" />&nbsp;&nbsp;&nbsp;
                      <input type="submit" class="button-primary" name="edit_activate_page_filter" value="<?php _e('Activate Plugin Entry &raquo;', 'plf'); ?>" />
                    </p>
                    <?php
                } else {
                    ?>
                    <br />
                    <p><span style="color: #ff0000;"><?php _e('Page Filter is not registered', 'plf') ?></span></p>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
    <?php
    }

    /***************************************************************************
     * Meta box
     * Individual of the plug-in filter meta box for Post/Page/CustomPost
     **************************************************************************/
    function load_meta_boxes( $post_type, $post ) {
        if ( current_user_can('activate_plugins', $post->ID) ) { 
          	add_meta_box( 'pluginfilterdiv', __( 'Plugin Load Filter', 'plf' ), array(&$this, 'plf_meta_box'), null, 'side' );
            //add_action( 'admin_head', array(&$this, 'plf_css' ));
            add_action( 'admin_footer', array(&$this, 'plf_meta_script' ));
        }
    }

    //Plugin pagefilter Selected Checkbox (plugin and modules list)
    // $p_key 
    // $select_cvplugins  : csv string : pagefilter selected plugins 
    // $checked_arcvplugins : array csv string : ['desktop']=desktop enable plugins, ['mobile']=mobile enable plugins
    function _pagefilter_plugins_checklist( $p_key, $select_cvplugins, $checked_arcvplugins ) {

        $html = '';
        $selplugins = array_map("trim", explode(',', $select_cvplugins));
        $device['desktop'] = $checked_arcvplugins['desktop'];
        $device['mobile']  = $checked_arcvplugins['mobile'];
        $devlist = array('desktop','mobile');
        if(in_array( $p_key, $selplugins )){
            $p_name = self::pluginkey_to_name($p_key);                
            $html .= "<tr><td class='plugins-name'>$p_name</td>";
            foreach($devlist as $devtype){
                $checked = (empty($device[$devtype]) || false === strpos($device[$devtype], $p_key))? false : true;
                $html .= "<td class='device-type $devtype'>" . self::altcheckbox("plf_option[$devtype][$p_key]", $checked, '<span class="dashicons dashicons-yes"></span>') . '</td>';
            }
            $html .= "</tr>";
        }
        return $html;
    }
    
    //Plugin pagefilter Selected Checkbox (plugin and modules list)
    // $plugins : array : all active plugins 
    // $select_cvplugins  : csv string : pagefilter selected plugins 
    // $checked_arcvplugins : array csv string : ['desktop']=desktop enable plugins, ['mobile']=mobile enable plugins
    function pagefilter_plugins_checklist( $plugins, $select_cvplugins, $checked_arcvplugins ) {
        
        if(empty($select_cvplugins))
            return __('Page Filter is not registered', 'plf');
        
        $html = '<table id="activation-table">';
        $html .= '<thead>';
        $html .= '<tr><th class="plugins-name">'. __('Plugins') . '</th>';
        $html .= '<th class="device-type"><span title="'. __('Desktop Device', 'plf'). '" class="dashicons dashicons-desktop"></span><br /><span style="font-size:xx-small">Desktop</span></th>';
        $html .= '<th class="device-type"><span title="'. __('Mobile Device', 'plf'). '" class="dashicons dashicons-smartphone"></span><br /><span style="font-size:xx-small">Mobile</span></th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody class="plugins-table-body meta-boxes-plugins-table">';
        $nplugins = $jmodules = $cmodules = array();
        foreach ( $plugins as $p_key => $p_data ) {
            $p_name = self::pluginkey_to_name($p_key);
            if(empty($p_name))
                continue;
            if(strpos($p_key, 'jetpack_module/') !== false)
                $jmodules[] = $p_key;
            else if(strpos($p_key, 'celtispack_module/') !== false)
                $cmodules[] = $p_key;
            else 
                $nplugins[] = $p_key; 
        }

        foreach ( $nplugins as $p_key ) {
            if(strpos($p_key, 'jetpack/') !== false){
                foreach ( $jmodules as $p_key ) {
                    $html .= $this->_pagefilter_plugins_checklist( $p_key, $select_cvplugins, $checked_arcvplugins );                    
                }
            }
            else if(strpos($p_key, 'celtispack/') !== false){                
                foreach ( $cmodules as $p_key ) {
                    $html .= $this->_pagefilter_plugins_checklist( $p_key, $select_cvplugins, $checked_arcvplugins );                    
                }
            }
            else {
                $html .= $this->_pagefilter_plugins_checklist( $p_key, $select_cvplugins, $checked_arcvplugins );                    
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

    //get_locale に加えたフィルターフックを外してサイトのロケールを取得
    static function get_site_locale(){
        global $locale;
        if(class_exists('Plf_filter')){
            remove_filter( 'locale', array('Plf_filter', 'post_locale') );
            $locale = null;
            $site_locale = get_locale();
            add_filter( 'locale', array('Plf_filter', 'post_locale') );            
        } else {
            $locale = null;
            $site_locale = get_locale();
        }
        return $site_locale;
    }
    
    function plf_meta_box( $post, $box ) {     
        if(is_object($post)){
            $myfilter = get_post_meta( $post->ID, '_plugin_load_filter', true );
            //ver2.2.0 for compatibility, set 'plugins' data to 'desktop' and 'mobile'
            if(is_array($myfilter) && !empty($myfilter['plugins'])){
                $myfilter['desktop'] = $myfilter['plugins'];
                $myfilter['mobile']  = $myfilter['plugins'];
                unset($myfilter['plugins']);
            }
            $default = array( 'filter' => 'default', 'desktop' => '', 'mobile' => '');
            $option = (!empty($myfilter) && is_array($myfilter))? $myfilter : $default;
            $option = wp_parse_args( $option, $default);
            $pgfilter = (!empty(self::$filter['_pagefilter']['plugins']))? self::$filter['_pagefilter']['plugins'] : array();
			$ajax_nonce = wp_create_nonce( 'plugin_load_filter-' . $post->ID );
            $this->plf_css();            
            ?>
            <div id="plugin-filter-select">
                <p><?php _e( 'Plugin filter for Single post', 'plf' ); ?></p>
                <label><input type="radio" name="pagefilter" value="default" <?php checked('default', $option['filter']); ?>/><?php _e('Not Use', 'plf' ); ?></label>
                <label><input type="radio" name="pagefilter" value="include" <?php checked('include', $option['filter']); ?>/><?php _e('Use', 'plf'); ?></label>
                <div id="page-filter-stat">
                <?php echo $this->pagefilter_plugins_checklist( self::$plugins_inf, $pgfilter, $option ); ?>
                </div>
                <div class="plf-option-info"><?php _e('Plugin Activate/Deactivate filter for this Post only', 'plf'); ?></div>
                <?php
                $c_locale = $o_post_id = '';
                $languages = array();
                $locale_mode = 'style="display:none;"';
                if(!empty(self::$filter['language'])){
                    $languages  = get_available_languages();
                    $site_locale = self::get_site_locale();
                    //bogo と同じ _locale ポストメタデータ採用
                    $c_locale = get_post_meta( $post->ID, '_locale', true );
                    if(empty($c_locale)){
                        $c_locale = $site_locale;
                    }
                    if ( ! in_array( $c_locale, $languages ) ) {
                        $c_locale = '';
                    }
                    $o_post_id = get_post_meta( $post->ID, '_original_post_id', true );
                    $o_post_id = (is_numeric($o_post_id))? $o_post_id : '';
                    $locale_mode = '';
                }
                echo '<p class="hide-if-no-js"><a id="plugin-filter-submit" class="button" href="#pluginfilterdiv" onclick="WPAddPagePluginLoadFilter(\'' . $ajax_nonce . '\');return false;" >'. __('Save') .'</a></p>';
                ?>
                <hr>
                <div id="plf-post-locale-select" <?php echo $locale_mode; ?>>
                    <p><?php _e( 'Language of this post', 'plf' ); ?> <span class="dashicons dashicons-translation" aria-hidden="true"></span></p>
                    <?php
                    wp_dropdown_languages(
                        array(
                            'name'                        => 'plf_locale',
                            'id'                          => 'plf_locale',
                            'selected'                    => $c_locale,
                            'languages'                   => $languages,
                            'show_available_translations' => false,
                        )
                    );
                    ?>
                    <p><?php _e( 'Original post ID for hreflang', 'plf' ); ?></p>
                    <input type="text" id="plf_original_post_id" name="plf_original_post_id" size="8" value="<?php echo $o_post_id; ?>" />
                    <div class="hflang-group-edit-lonk">
                        <?php
                        $id = (is_numeric($o_post_id))? $o_post_id : $post->ID;
                      	global $wpdb;
                        $ids = $wpdb->get_row($wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_original_post_id' AND meta_value = %d", $id ), ARRAY_N);
                        if(is_array($ids)){
                            if(!in_array((string)$id, $ids)){                              
                                $ids[] = (string)$id;
                            }
                            if(count($ids) > 1){
                                foreach ($ids as $v) {
                                    if( $post->ID != $v){
                                        $l = get_post_meta( $v, '_locale', true );
                                        if(empty($l)){
                                            $l = $site_locale;
                                        }
                                        $url = get_edit_post_link( $v );
                                        if(!empty($url)){
                                            echo '<p><a class="edit-post-locale-link" target="_blank"  href="' . $url .'" rel="external noreferrer noopener">' . __('Edit Post') . " ($l) " . '<span class="dashicons dashicons-external" aria-hidden="true"></span></a></p>';
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="plf-option-info"><?php _e('Use MO translation file for the selected locale. When Original Post ID is registered, posts with the same Original Post ID are treated as `hreflang` metadata group.', 'plf'); ?></div>
                    <?php echo '<p class="hide-if-no-js"><a id="plugin-filter-submit" class="button" href="#pluginfilterdiv" onclick="WPAddPagePluginLoadFilter(\'' . $ajax_nonce . '\');return false;" >'. __('Save') .'</a></p>'; ?>
                </div>
            </div>
        <?php
        }
    }    

    //wp_ajax_plugin_load_filter called function
    function plf_ajax_postidfilter() {
        if ( isset($_POST['post_id']) ) {
            $pid = (int) $_POST['post_id'];
            if ( !current_user_can( 'activate_plugins', $pid ) )
                wp_die( -1 );            
            check_ajax_referer( "plugin_load_filter-$pid" );
            
            if(!empty(self::$filter['language'])){
                if(isset($_POST['locale'])){
                    $s_locale = (!empty($_POST['locale']))? $_POST['locale'] : 'en_US';
                    $languages  = get_available_languages();
                    $languages[]  = 'en_US'; //add WP default locale 
                    if ( in_array( $s_locale, $languages ) ) {
                        $g_locale = get_post_meta( $pid, '_locale', true );
                        global $locale;
                        if(class_exists('Plf_filter')){
                            remove_filter( 'locale', array('Plf_filter', 'post_locale') );
                            $locale = null;
                            $o_locale = get_locale();
                            add_filter( 'locale', array('Plf_filter', 'post_locale') );
                        } else {
                            $locale = null;
                            $o_locale = get_locale();
                        }
                        if($s_locale !== $g_locale){
                            if($s_locale === $o_locale){
                                delete_post_meta( $pid, '_locale');
                            } else {
                                update_post_meta( $pid, '_locale', $s_locale );
                            }
                        }
                    }
                }
                if(isset($_POST['original_post_id'])){
                    if(empty($_POST['original_post_id'])){
                        delete_post_meta( $pid, '_original_post_id');
                    } elseif(preg_match( '/([0-9]+)?/', $_POST['original_post_id'], $match )){
                        $o_id = (int)$match[1];
                        $post = get_post($o_id);
                        if(is_object($post)){
                            $g_id = get_post_meta( $pid, '_original_post_id', true );
                            if($o_id !== $g_id){
                                update_post_meta( $pid, '_original_post_id', $o_id );
                            }
                        }
                    }
                }
            }
            $pgfilter = (!empty(self::$filter['_pagefilter']['plugins']))? self::$filter['_pagefilter']['plugins'] : array();
            $option["filter"] = (empty($_POST['filter']))? 'default' : $_POST['filter'];
            if('default' == $option["filter"]){
                delete_post_meta( $pid, '_plugin_load_filter');
            } else {
                $plugins = array();
                if( preg_match_all('/plf_option\[desktop\]\[(.+?)\]/u', $_POST['desktop'], $matches)){
                    if(!empty($matches[1])){ 
                        foreach ($matches[1] as $plugin){
                            $plugins[] = $plugin;
                        }
                        $option["desktop"] = implode(",", $plugins);
                    }
                }
                $plugins = array();
                if( preg_match_all('/plf_option\[mobile\]\[(.+?)\]/u', $_POST['mobile'], $matches)){
                    if(!empty($matches[1])){ 
                        foreach ($matches[1] as $plugin){
                            $plugins[] = $plugin;
                        }
                        $option["mobile"] = implode(",", $plugins);
                    }
                }
                update_post_meta( $pid, '_plugin_load_filter', $option );
            }
            
            $html = $this->pagefilter_plugins_checklist( self::$plugins_inf, $pgfilter, $option );
            wp_send_json_success($html);
        }
        wp_die( 0 );
    }

    //alternate hreflang meta tag
    static function altenate_hreflang() {
        if ( is_singular() ) {
            global $post, $wpdb;
            $pid = $post->ID;
            $oid = get_post_meta( $pid, '_original_post_id', true );
            $oid = (is_numeric($oid))? $oid : $pid;
            $ids = $wpdb->get_row($wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_original_post_id' AND meta_value = %d", $oid ), ARRAY_N);
            if(is_array($ids)){
                if(!in_array((string)$oid, $ids)){                              
                    $ids[] = (string)$oid;
                }
                if(count($ids) > 1){
                    $site_locale = self::get_site_locale();
                    foreach ($ids as $id) {
                        $l = get_post_meta( $id, '_locale', true );
                        if(empty($l)){
                            $l = $site_locale;
                        }
                        $url = get_permalink( $id );
                        echo '<link rel="alternate" hreflang="' . esc_attr($l) . '"  href="' . esc_url($url) .'" />' . PHP_EOL;
                    }
                }
            }
        }
    }    
    
    /***************************************************************************
     * Javascript 
     **************************************************************************/
    function activetab_script() { ?>
    <script type='text/javascript' >
    /* <![CDATA[ */
    var plf_activetab = <?php echo $this->tab_num; ?>
    /* ]]> */
    jQuery(document).ready(function ($) { 
        plf_setting_tabs(); 
        function plf_setting_tabs(){ $('#plf-setting-tabs').tabs({ active:plf_activetab, }); }
    });    
    </script>  
    <?php }
    
    function plf_meta_script() { 
        $reload_dialog = __('Plugin Load Filter setting has been updated.\nClick OK to reload the page.', 'plf');
    ?>
    <script type='text/javascript' >
    WPAddPagePluginLoadFilter = function(nonce){ 
        jQuery.ajax({ 
            type: 'POST', 
            url: ajaxurl, 
            data: { 
                action: "plugin_load_filter", 
                post_id : jQuery( '#post_ID' ).val(), 
                _ajax_nonce: nonce,
                locale: jQuery("select[name='plf_locale']").val(),
                original_post_id: jQuery("input[name='plf_original_post_id']").val(), 
                filter: jQuery("input[name='pagefilter']:checked").val(), 
                desktop: jQuery('.meta-boxes-plugins-table td.desktop input:checked').map(function(){ return jQuery(this).attr("name"); }).get().join(','), 
                mobile: jQuery('.meta-boxes-plugins-table td.mobile input:checked').map(function(){ return jQuery(this).attr("name"); }).get().join(','),
            }, 
            dataType: 'json', 
        }).then(
            function (response, dataType) {
                jQuery('#page-filter-stat').html(response.data);
                if(window.confirm('<?php echo $reload_dialog; ?>')){
                    location.reload();
                }
            },
            function () { /* alert("ajax error"); */ }
        );
    };
    </script>  
    <?php }
}