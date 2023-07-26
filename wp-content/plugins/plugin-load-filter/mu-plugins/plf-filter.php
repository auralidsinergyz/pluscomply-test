<?php
/*
  Plugin Name: plugin load filter [plf-filter]
  Description: Dynamically activated only plugins that you have selected in each page. [Note] plf-filter has been automatically installed / deleted by Activate / Deactivate of "load filter plugin".
  Version: 4.0.13
  Plugin URI: http://celtislab.net/en/wp-plugin-load-filter
  Author: enomoto@celtislab
  Author URI: http://celtislab.net/
  License: GPLv2
*/
defined( 'ABSPATH' ) || exit;

/***************************************************************************
 * pluggable.php defined function overwrite 
 * pluggable.php read before the query_posts () is processed by the current user undetermined
 **************************************************************************/
/**
 * Gets hash of given string.
 *
 * @param string $data   Plain text to hash.
 * @return string Hash of $data.
 */
 //wp_salt( $scheme ) を簡略化して logged_in cookie 限定
function plf_logged_in_hash( $data ) {
    $values = array(
        'key'  => '',
        'salt' => '',
    );
    foreach ( array( 'key', 'salt' ) as $type ) {
        $const = strtoupper( "logged_in_{$type}" );
        if ( defined( $const ) && constant( $const ) ) {
            $values[ $type ] = constant( $const );
        }
    }
    $salt = $values['key'] . $values['salt'];
    return hash_hmac( 'md5', $data, $salt );
}

if ( !function_exists('wp_get_current_user') ) :
/**
 * Retrieve the current user object.
 * @return WP_User Current user WP_User object
 */
function wp_get_current_user() {
	if ( ! function_exists( 'wp_set_current_user' ) ){
        /*
    	if ( defined( 'LOGGED_IN_COOKIE' ) && !empty( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) {
            $cookie_elements = explode( '|', $_COOKIE[ LOGGED_IN_COOKIE ] );
            if ( count( $cookie_elements ) === 4 ) {
                list( $username, $expiration, $token, $hmac ) = $cookie_elements;
        		if ( $expiration > time() ) {
                    $user = get_user_by( 'login', $username );
            		if ( $user ) {                    
                        $pass_frag = substr( $user->user_pass, 8, 4 );

                        $key = plf_logged_in_hash( $username . '|' . $pass_frag . '|' . $expiration . '|' . $token );

                        // If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
                        $algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
                        $hash = hash_hmac( $algo, $username . '|' . $expiration . '|' . $token, $key );

                        if ( hash_equals( $hash, $hmac ) ) {
                            $manager = WP_Session_Tokens::get_instance( $user->ID );
                            if ( $manager->verify( $token ) ) {
                                return $user;
                            }                            
                        }                        
                    }
                }                
            }
        }
         */
   		return 0;            
        
    } else {
        return _wp_get_current_user();
    }
}
endif;

if ( !function_exists('get_userdata') ) :
/**
 * Retrieve user info by user ID.
 * @param int $user_id User ID
 * @return WP_User|bool WP_User object on success, false on failure.
 */
function get_userdata( $user_id ) {
	return get_user_by( 'id', $user_id );
}
endif;

if ( !function_exists('get_user_by') ) :
/**
 * Retrieve user info by a given field
 * @param string $field The field to retrieve the user with. id | slug | email | login
 * @param int|string $value A value for $field. A user ID, slug, email address, or login name.
 * @return WP_User|bool WP_User object on success, false on failure.
 */
function get_user_by( $field, $value ) {
	$userdata = WP_User::get_data_by( $field, $value );

	if ( !$userdata )
		return false;

	$user = new WP_User;
	$user->init( $userdata );

	return $user;
}
endif;

if ( !function_exists('is_user_logged_in') ) :
/**
 * Checks if the current visitor is a logged in user.
 * @return bool True if user is logged in, false if not logged in.
 */
function is_user_logged_in() {
	if ( ! function_exists( 'wp_set_current_user' ) )
		return false;
        
	$user = wp_get_current_user();

	if ( ! $user->exists() )
		return false;

	return true;
}
endif;

/***************************************************************************
 * Plugin Load Filter 
 **************************************************************************/

if(in_array( 'plugin-load-filter/plugin-load-filter.php', (array) get_option( 'active_plugins', array() ) )){
    $plugin_load_filter = new Plf_filter();
} elseif ( is_multisite() ) {            
	$plugins = get_site_option( 'active_sitewide_plugins');
	if ( isset($plugins['plugin-load-filter/plugin-load-filter.php']) ){
        $plugin_load_filter = new Plf_filter();
    }
}

class Plf_filter {    
    static private $filter;  //Plugin Load Filter Setting option data
    static private $rlocale;
    static private $url_filter;
    static private $s_url_filter;
    static private $is_filterkey;
    static private $iniget_active_plugins;
    static private $iniget_active_sitewide_plugins;
    static private $base_plugins;
    static private $filtered_plugins;
    static private $cache;
    static private $url2id;
    static private $pre_post_id;
    static private $base_public_query_vars;

    function __construct() {    
        self::$rlocale = null;
        self::$cache  = array();
        self::$url2id = array();
        self::$pre_post_id = 0;
        self::$base_public_query_vars = array();        
        self::$url_filter   = array();
        self::$s_url_filter = array();
        self::$is_filterkey = false;
        self::$iniget_active_plugins = array();
        self::$iniget_active_sitewide_plugins = array();
        self::$base_plugins = array();
        self::$filtered_plugins = array();
        self::$filter = get_option('plf_option', array());
        if(!empty(self::$filter)){
            //Addon option get
            self::$iniget_active_plugins = $plugins = get_option( 'active_plugins', array() );
            if ( is_multisite() ) {
                self::$iniget_active_sitewide_plugins = (array) get_site_option( 'active_sitewide_plugins', array() );
                $plugins = array_merge( $plugins, array_keys( self::$iniget_active_sitewide_plugins ) );
            }
            if(!empty($plugins) && in_array( 'plugin-load-filter-addon/plugin-load-filter-addon.php', $plugins)){
                self::$url_filter = get_option('plf_addon_options', array());
            }
            
            //active_plugins filter
            if ( is_multisite() ) {
                add_filter('pre_site_option_active_sitewide_plugins', array('Plf_filter', 'active_sitewide_plugins'));
                add_action('update_site_option_active_sitewide_plugins', array($this, 'update_active_sitewide_plugins'), 99999, 4);
            }
            add_filter('pre_option_active_plugins', array('Plf_filter', 'active_plugins'));
            add_filter('pre_option_jetpack_active_modules', array('Plf_filter', 'active_jetmodules'));
            add_filter('pre_option_celtispack_active_modules', array('Plf_filter', 'active_celtismodules'));
            
            add_action('update_option_active_plugins', array($this, 'update_active_plugins'), 99999, 3);
            add_action('update_option_jetpack_active_modules', array($this, 'update_active_jetmodules'), 99999, 3);
            add_action('update_option_celtispack_active_modules', array($this, 'update_active_celtismodules'), 99999, 3);
            add_action('switch_theme', array($this, 'switch_theme'));

            add_action('plugins_loaded', array($this, 'plugins_loaded'));
            add_action('admin_init', array($this, 'cache_post_type'));
            //admin-bar filtered status
            if(!empty(self::$filter['admin_bar'])){
                add_action('admin_bar_menu', array($this,'custom_bar_menus'), 201);
            }
            //language locale filter
            if(!empty(self::$filter['language'])){
                add_filter( 'locale', array('Plf_filter', 'post_locale') );            
                add_filter( 'determine_locale', array('Plf_filter', 'post_determine_locale') );            
            }
        }
    }

    //Notice: scriptを使うと AMP でエラーとなるので target でオープン/クローズを行う
    static function plf_filtered_result_dialog( $text ) {
    ?> 
    <style>
        #wpadminbar #wp-admin-bar-plf-statlink .ab-icon:before { content: '\f106'; top: 2px;}
        #plf-status { display:none;}
        #plf-status:target { display:block;}
        #plf-status .dialog-overlay{ position:fixed; left:0px; top:0px; width:100%; height:100%; background-color:rgba(0,0,0,.5); z-index:999999999;}
        #plf-status div.dialog { position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); width:66%; max-width:420px; height:auto; padding:1.5em; color:#222; background:#fff; z-index:999999999;}
        #plf-status .dialog-title { margin: 0 0 1em; font-size: 16px;}
        #plf-status textarea { font-size: 13px;}
        #plf-status .button-group { display:block; margin-top:2em; text-align:right; font-size:1em;}
        #plf-status a.button { display:inline-block; text-decoration:none; margin: 0 0 0 1em; padding: .5em 1em; color: #0071a1; background: #f5f5f5; border:solid 1px #0071a1; border-radius:3px; cursor:pointer;}
        #plf-status a.button:hover { background: #e8ffff; border-color: #016087; color: #016087;}
    </style>  
    <div id="plf-status">
      <div class="dialog-overlay"></div>
      <div class="dialog">
          <p class="dialog-title"><strong><?php echo 'Plugin Load Filter status'; ?></strong></p>  
          <div><textarea id="plf-filtered-result" style="width:100%; height:320px; margin:5px 0;"><?php echo $text; ?></textarea></div>
          <div class="button-group">
            <a href="#" id="plf-status-close" class="button" aria-label="Close modal"><?php _e('Close'); ?></a>
          </div>
      </div>
    </div>
    <?php        
    }

    //filtered plugins list print 
    static function result_plugin_print( $e_plugins, $d_plugins ) {
        $text = PHP_EOL . "[Activate Plugins]" . PHP_EOL;
        $idx = 1;
        foreach ($e_plugins as $key) {
            if(!empty($key)){
                $key = preg_replace('|/.+?\.php|', '', $key);
                $text .= "$idx. $key" . PHP_EOL;
                $idx++;                
            }
        }
        $text .= PHP_EOL . "[Deactivate Plugins]" . PHP_EOL;
        $idx = 1;
        foreach ($d_plugins as $key) {
            if(!empty($key)){
                $key = preg_replace('|/.+?\.php|', '', $key);
                $text .= "$idx. $key" . PHP_EOL;
                $idx++;
            }
        }
        return $text;
    }

    public function custom_bar_menus($wp_admin_bar) {
        if (current_user_can( 'activate_plugins' )) {
            $base_plugins =  self::get_base_plugins();
            if(!empty($base_plugins)){
                $text = '==== Plugin Load Filter status ====' . PHP_EOL;
                $is_filterkey = self::is_filterkey();
                if($is_filterkey !== false){
                    $text .= '[Filter] ' . $is_filterkey . PHP_EOL;
                    $e_plugins = self::get_filtered_plugins();
                    $d_plugins = array_diff( $base_plugins, $e_plugins );
                } else {
                    $text .= '[Filter] Not Used' . PHP_EOL;
                    $e_plugins = $base_plugins;
                    $d_plugins = array();
                }
                ksort($e_plugins, SORT_STRING);
                ksort($d_plugins, SORT_STRING);

                $text .= self::result_plugin_print( $e_plugins, $d_plugins );
                self::plf_filtered_result_dialog( $text );
                $wp_admin_bar->add_menu( array(
                    'id'    => 'plf-statlink',
                    'title' => '<span class="ab-icon"></span>PLF',
                    'href'  => '#plf-status',
                ));
            }
        }
    }
        
    //active_sitewide plugins Filter add ver2.4.0
    static function active_sitewide_plugins( $default = false) {
        return self::plf_filter( 'active_sitewide_plugins', $default);
    }

    //active plugins Filter
    static function active_plugins( $default = false) {
        return self::plf_filter( 'active_plugins', $default);
    }

    //Jetpack module Filter
    static function active_jetmodules( $default = false) {
        return self::plf_filter( 'jetpack_active_modules', $default);
    }

    //Celtispack module Filter
    static function active_celtismodules( $default = false) {
        return self::plf_filter( 'celtispack_active_modules', $default);
    }

    function updated_plf_stat() {  
        $default = array( 
                    'post_type_query_vars' => array(),
                    'queryable_post_types' => array(),
                    //'wp_post_statuses' => array(),
                    //'wp_post_types' => array(),
                    //'wp_taxonomies' => array(),
                    'stat_change'  => false,
                   );
        $data = get_option('plf_queryvars', array());
		$data = wp_parse_args( (array) $data,  $default);
        //plugin bulk action repeated call
        if(empty($data['stat_change'])){
            $data['stat_change'] = true;
            update_option('plf_queryvars', $data);            
        }         
    }
        
    //Plugin/Module activate or deactivate stat change
    function update_active_sitewide_plugins( $option, $value, $old_value, $network_id ) {
        $this->updated_plf_stat();
    }
    function update_active_plugins( $old_value, $value, $option ) {
        $this->updated_plf_stat();
    }
    function update_active_jetmodules( $old_value, $value, $option ) {
        $this->updated_plf_stat();
    }
    function update_active_celtismodules( $old_value, $value, $option ) {
        $this->updated_plf_stat();
    }
    function switch_theme() {
        $this->updated_plf_stat();
    }
        
    //Make taxonomies and posts available to 'plugin load filter'.
    //force register_taxonomy (category, post_tag, post_format) 
    static function force_initial_taxonomies(){
        global $wp_actions;
        $wp_actions[ 'init' ] = 1;
        create_initial_taxonomies();
        create_initial_post_types();
        unset($wp_actions[ 'init' ]);
    }
   
    //all plugins loaded 
    function plugins_loaded() {
        //ver4.0.5 カスタムポストタイプのキャッシュデータを $wp_post_types グローバル変数には反映しないよう変更したのでこの処理は不要となるが、なんらかの影響があると嫌なのでとりあえず残しておく
        // woocommerce が init 後に register_post_type, register_taxonomy が実行されたか判定しているので該当データを一旦クリアして init で再登録させる
        if(post_type_exists( 'product' )){
            if(method_exists('WC_Post_Types', 'register_post_types')){
                unregister_post_type( 'product' );
                //ver4.0.2 wc_register_order_type で登録されるタイプも一旦クリアする
                if(post_type_exists( 'shop_order' )){
                    unregister_post_type( 'shop_order' );
                }
                if(post_type_exists( 'shop_order_refund' )){
                    unregister_post_type( 'shop_order_refund' );
                }                
            }
        }
  		if ( taxonomy_exists( 'product_type' ) ) {
            if(method_exists('WC_Post_Types', 'register_taxonomies')){
                unregister_taxonomy( 'product_type' );                
            }                    
        }
    }
    
    //Post Format Type, Custom Post Type Data Cache for parse request
    function cache_post_type() {  
        if (!is_admin() || self::$is_filterkey !== false || ( defined('DOING_AJAX') && DOING_AJAX ))
            return; 
        
        $default = array( 
                    'post_type_query_vars' => array(),
                    'queryable_post_types' => array(),
                    //'wp_post_statuses' => array(),
                    //'wp_post_types' => array(),
                    //'wp_taxonomies' => array(),
                    'stat_change'  => false,
                   );
        $data = get_option('plf_queryvars', array());
		$data = wp_parse_args( (array) $data,  $default);

        global $plugin_page;
        if(isset($plugin_page) && $plugin_page === 'plugin_load_filter_admin_manage_page'){
            $data['stat_change'] = true;
        }

        //init or plugin activate or deactivate stat change
        if(!empty($data['stat_change'])){
            global $wp;
            $public_query_vars = (!empty($wp->public_query_vars))? $wp->public_query_vars : array();;
            $post_type_query_vars = array();
            foreach ( get_post_types( array(), 'objects' ) as $post_type => $t ){
                if (!empty($t) && $t->query_var )
                    $post_type_query_vars[$t->query_var] = $post_type;
            }
            $queryable_post_types = get_post_types( array('publicly_queryable' => true) );
            
            //global $wp_post_types;
            //global $wp_post_statuses;
            //global $wp_taxonomies;
            //$data['wp_post_types']        = $wp_post_types;            
            //$data['wp_post_statuses']     = $wp_post_statuses;
            //$data['wp_taxonomies']        = $wp_taxonomies;
            //$data['public_query_vars']    = $public_query_vars;
            //ver4.0.11 使わないデータを取り除きオプションデータを縮小化
            if(!empty($data['rewrite_rules'])){
                unset($data['rewrite_rules']);  //データの残骸があれば使わないので取り除く
            }
            if(!empty($data['wp_post_types'])){
                unset($data['wp_post_types']);  
            }            
            if(!empty($data['wp_post_statuses'])){
                unset($data['wp_post_statuses']);  
            }            
            if(!empty($data['wp_taxonomies'])){
                unset($data['wp_taxonomies']);  
            }            
            if(!empty($data['public_query_vars'])){
                unset($data['public_query_vars']);  
            }            
            $add_query_vars = array_diff( $public_query_vars, self::$base_public_query_vars );            
            $data['add_public_query_vars']= $add_query_vars;
            $data['post_type_query_vars'] = $post_type_query_vars;
            $data['queryable_post_types'] = $queryable_post_types;
            $data['stat_change']          = false;
            update_option('plf_queryvars', $data);
        }
    }

	/**
	 * Filters the locale for the current request.
	 *
	 * @param string $locale The locale.
	 */
    static function post_locale( $locale ) {
        if(empty(self::$rlocale)) {
            $pid = 0;
            if(isset($_SERVER['REQUEST_URI'])){
                if ( strpos( $_SERVER['REQUEST_URI'], 'wp-json' ) !== false || preg_match( '/(admin|wc)-ajax/', $_SERVER['REQUEST_URI'])) {
                    $refurl = wp_get_raw_referer();
                    if(!empty( $refurl )){
                        if(preg_match( '/post=([0-9]+)?/', $refurl, $match )){
                            $pid = (int)$match[1];
                        } elseif(preg_match( '/post_ID=([0-9]+)?/', $refurl, $match )){
                            $pid = (int)$match[1];
                        } else {
                            $pid = (isset(self::$url2id[$refurl]))? self::$url2id[$refurl] : url_to_postid( $refurl );
                            self::$url2id[$refurl] = (int)$pid;
                        }                
                    }
                } elseif ( strpos( $_SERVER['REQUEST_URI'], 'wp-admin' ) !== false) {
                    if ( isset( $_GET['post'] ) ) {
                        $pid = (int) $_GET['post'];
                    } elseif ( isset( $_POST['post_ID'] ) ) {
                        $pid = (int) $_POST['post_ID'];
                    }
                } else {
                    global $wp_query;
                    if(!empty(self::$pre_post_id)){
                        $pid = self::$pre_post_id;
                    } elseif(is_object($wp_query->post) && !empty($wp_query->post->ID)){
                        $pid = $wp_query->post->ID;
                    } else {
                        $refurl = wp_get_raw_referer();         
                        if (!empty( $refurl )) {
                            $pid = (isset(self::$url2id[$refurl]))? self::$url2id[$refurl] : url_to_postid( $refurl );
                            self::$url2id[$refurl] = (int)$pid;
                        }
                    }                
                }                 
            }
            if(!empty($pid)){
        		$c_locale = get_post_meta( $pid, '_locale', true );
                if(!empty($c_locale)){
                    self::$rlocale = $c_locale;
                }
            }
        }
        if(!empty(self::$rlocale)) {
            $locale = self::$rlocale;
        }
        return $locale;
    }
    
    static function post_determine_locale( $locale ) {
        if(!empty(self::$rlocale)) {
            $locale = self::$rlocale;
        }
        return $locale;
    }

    //プラグインロード前は bbPress等 カスタムポストタイプのデバッグモードでのエラー表示抑制
    static function exclude_trigger_error( $trigger, $function, $message, $version) {
        if (did_action( 'plugins_loaded' ) === 0) {
            if($function === 'map_meta_cap'){
                $trigger = false;
            }
        }
        return $trigger;
    }
    //プラグインロード前は closed 等のカスタムステータスがまだ登録されてない状態でも取得 posts 情報がクリアされないよう抑制
    static function exclude_map_meta_cap( $caps, $cap, $user_id, $args) {
        if (did_action( 'plugins_loaded' ) === 0) {
            if(!empty($caps) && $caps[0] === 'edit_others_posts'){
                $caps = array();
            }
        }
        return $caps;
    }

    //parse_request Action Hook for Custom Post Type query add
    static function parse_request( &$args ) {
        if (did_action( 'plugins_loaded' ) === 0) {            
            $data = get_option('plf_queryvars', array());
            if(!empty($data['post_type_query_vars']) && !empty($data['queryable_post_types']) && empty($data['stat_change'])){
                //ver4.0.5 グローバルのカスタムポストタイプに事前設定するとプラグイン内での登録済みチェック処理とコンフリクトするので止める                
                //global $wp_post_statuses;
                //global $wp_post_types;
                //global $wp_taxonomies;            
                //$wp_post_statuses     = $data['wp_post_statuses'];
                //$wp_post_types        = $data['wp_post_types'];            
                //$wp_taxonomies        = $data['wp_taxonomies'];
                
                $post_type_query_vars = $data['post_type_query_vars'];
                $queryable_post_types = $data['queryable_post_types'];

                //query_vars に　query_posts()　で実行するSQL用のポストタイプデータをセット
                if(isset($data['add_public_query_vars'])){
                    //ver4.0.11 差分データにしたのでここでマージするが更新時にエラーとならないよう旧処理も残しておく
                    $args->public_query_vars = array_merge(self::$base_public_query_vars, $data['add_public_query_vars']);                     
                } else {
                    $args->public_query_vars = $data['public_query_vars'];                    
                }
                if ( isset( $args->matched_query ) ) {
                    parse_str($args->matched_query, $perma_query_vars);
                }
                
                foreach ( $args->public_query_vars as $wpvar ) {
                    if ( isset( $args->extra_query_vars[$wpvar] ) ){
                        $args->query_vars[$wpvar] = $args->extra_query_vars[$wpvar];
                    } elseif ( isset( $_GET[ $wpvar ] ) && isset( $_POST[ $wpvar ] ) && $_GET[ $wpvar ] !== $_POST[ $wpvar ] ) {
                        wp_die( __( 'A variable mismatch has been detected.' ), __( 'Sorry, you are not allowed to view this item.' ), 400 );                        
                    } elseif ( isset( $_POST[$wpvar] ) ){
                        $args->query_vars[$wpvar] = $_POST[$wpvar];
                    } elseif ( isset( $_GET[$wpvar] ) ){
                        $args->query_vars[$wpvar] = $_GET[$wpvar];
                    } elseif ( isset( $perma_query_vars[$wpvar] ) ){
                        $args->query_vars[$wpvar] = $perma_query_vars[$wpvar];
                    }
                    if ( !empty( $args->query_vars[$wpvar] ) ) {
                        if ( ! is_array( $args->query_vars[$wpvar] ) ) {
                            $args->query_vars[$wpvar] = (string) $args->query_vars[$wpvar];
                        } else {
                            foreach ( $args->query_vars[$wpvar] as $vkey => $v ) {
                                if ( is_scalar( $v ) ) {
                                    $args->query_vars[$wpvar][$vkey] = (string) $v;
                                }
                            }
                        }
                        
                        if ( isset($post_type_query_vars[$wpvar] ) ) {
                            $args->query_vars['post_type'] = $post_type_query_vars[$wpvar];
                            $args->query_vars['name'] = $args->query_vars[$wpvar];
                        }
                    }
                }

        		// Limit publicly queried post_types to those that are 'publicly_queryable'.
                if ( isset( $args->query_vars['post_type']) ) {
                    if ( ! is_array( $args->query_vars['post_type'] ) ) {
                        if ( ! in_array( $args->query_vars['post_type'], $queryable_post_types, true ) ) {
                            unset( $args->query_vars['post_type'] );
                        }
                    } else {
                        $args->query_vars['post_type'] = array_intersect( $args->query_vars['post_type'], $queryable_post_types );
                    }
                }              
            }
        }
    }

    //get filter name
    static function is_filterkey() {
        return self::$is_filterkey;
    }    
    //base plugins (initial activated plugins & module)
    static function get_base_plugins() {
        return self::$base_plugins;
    }
    //filtered plugins (plugins & module)
    static function get_filtered_plugins() {
        return self::$filtered_plugins;
    }
    
    /**
     * Get URL Filter (for addon optional)
     * @param $stat    : 'active' / 'deactive' / '' = all
     * @param $urltype : 'front' / 'admin' / '' = all
     * @param $device  : 'desktop' / 'mobile' / '' = all
     * @return url filter data array.
     */
    static function get_url_filter( $stat='', $urltype='', $device='' ) {    
        $frontlist = array();
        $adminlist = array();
        if(!empty(self::$url_filter)){
            foreach (self::$url_filter['filter'] as $slug => $v) {
                if(empty($stat) || (!empty($v['stat']) && $stat === 'active') || (empty($v['stat']) && $stat === 'deactive')){
                    if(empty($device) || (!empty($v['desktop']) && $device === 'desktop') || (!empty($v['mobile']) && $device === 'mobile')){
                        if(!empty($v['type'])){
                            $item = "{$v['group']}-{$v['slug']}";
                            if($v['type'] == 'front'){
                                $frontlist[$item] = $v;
                            } elseif($v['type'] == 'admin'){
                                $adminlist[$item] = $v;
                            }
                        }
                    }
                }
            }
            //group 毎に slug をソート A-Za-z 順となる
            ksort($frontlist, SORT_STRING);
            ksort($adminlist, SORT_STRING);
        }
        $list = array();
        if(empty($urltype) || $urltype === 'front'){
            foreach ($frontlist as $item => $v) {
                if(!empty($v)){
                    $list[] = $v;                    
                }
            }
        }
        if(empty($urltype) || $urltype === 'admin'){
            foreach ($adminlist as $item => $v) {
                if(!empty($v)){
                    $list[] = $v;
                }
            }
        }
        return $list;
    }
    //active group list
    static function get_active_group() {
        $grouplist = array();
        $sluglist = self::get_url_filter( 'active' );
        foreach ($sluglist as $v) {
            if(!empty($v['group']) && !in_array($v['group'], $grouplist)){
                $grouplist[] = $v['group'];
            }
        }
        return $grouplist;
    }
    //active slug filter data in group
    static function get_slug_filter( $group ) {
        $list = array();
        $sluglist = self::get_url_filter( 'active' );
        foreach ($sluglist as $v) {
            if(!empty($v['group']) && $v['group'] == $group){
                $list[] = $v;
            }
        }
        return $list;
    }

    //Is there a term in taxonomies
    static function is_term_in_taxonomy( $post_id, $term, $taxonomies) {
        $ret = false;
        $txs = explode(',', $taxonomies);
        foreach ( $txs as $slug ) {
            if(!empty($slug)){
                $names = '';
                $terms = get_the_terms( $post_id, $slug );
                if (!empty($terms) ) {
                    foreach ( $terms as $tobj ) {
                        if(!empty($tobj)){
                            $names .= $tobj->name . ',';                            
                        }
                    }
                    if(strpos($names, $term ) !== false){
                        $ret = true;
                        break;
                    }
                }                
            }
        }
        return $ret;
    }
    
    // url path, query parameter match check
    static function is_url_match( $req_url, $parse_url, $filter ) {
        if(empty($req_url) || empty($parse_url['path']))
            return false;
        $_url['url_path'] = $parse_url['path'];
        $_url['url_q_and'] = $_url['url_q_not'] = (!empty($parse_url['query']))? $parse_url['query'] : '';
        
        $match = array();
        $keynum = array();
        foreach (array('url_path', 'url_q_and', 'url_q_not') as $item) {
            $match[$item] = 0;
            $keynum[$item] = 0;
            if($item === 'url_path'){
                $reg_before = "(/?)";
                $reg_after  = "(/?$)";
            } else {
                $reg_before = "(^|&)";
                $reg_after  = "(=|&|$)";
            }

            $keylist = (!empty($filter[$item])) ? $filter[$item] : '';
            $keylist = str_replace( "*", ".+?", $keylist);
            $ar_key = (!empty($keylist))? array_filter( array_map("trim", explode(PHP_EOL, $keylist))) : array();
            $keynum[$item] = count($ar_key);
            if(empty($ar_key)) {
                if($item === 'url_path')
                    $match[$item] += 1;
            } else {
                $ar_new = array();
                foreach ($ar_key as $key) {
                    //v4.0.6 home (/) のみの指定は別判定しないとトレイルスラッシュで終わる全てのURLにマッチしてしまう
                    if($item === 'url_path' && $key === '/'){
                        if($_url['url_path'] === '/'){
                            $match[$item] += 1;
                        }
                    } elseif(preg_match("#{$reg_before}{$key}{$reg_after}#ui", $_url[$item])){
                        $match[$item] += 1;
                    }
                }
            }
        }
        $group = false;
        $hit = ($match['url_path'] > 0 && $match['url_q_and'] === $keynum['url_q_and'] && $match['url_q_not'] === 0)? true : false;
        if($hit){
            if((strpos($_url['url_path'], 'admin-ajax.php' ) !== false || strpos($_url['url_q_and'], 'wc-ajax' ) !== false) && (int)$filter['targetpage'] === 2){
                $action = '';
                if(isset($_REQUEST['action'])){
                    $action = esc_attr($_REQUEST['action']);
                } elseif(!empty( $_GET['wc-ajax'] )) {
                    $action = sanitize_text_field( wp_unslash( $_GET['wc-ajax'] ) );
                }
                if(!empty($filter['ajax_action'])){
                    if( $action == $filter['ajax_action'] ){
                        $group = $filter['group'];
                    }
                } else {
                    //exclude special action : plugin_load_filter, plf_urlfilter_test
                    if (! in_array( $action, array('plugin_load_filter', 'plf_urlfilter_test')) ){
                        $group = $filter['group'];
                    }                    
                }            
            } elseif(strpos($_url['url_path'], 'post-new.php' ) === false && (int)$filter['targetpage'] === 1){
                $post_id = 0;
                if ( isset( $_GET['post'] ) && isset( $_POST['post_ID'] ) && (int) $_GET['post'] !== (int) $_POST['post_ID'] ) {
                } elseif ( isset( $_GET['post'] ) ) {
                    $post_id = (int) $_GET['post'];
                } elseif ( isset( $_POST['post_ID'] ) ) {
                    $post_id = (int) $_POST['post_ID'];
                }
                if(empty($post_id)){
                    //クエリーパラメータ形式だとポストIDが取得できないようなので、シュミレータ以外なら $wp_query->post->ID を使う
                    if (isset($_REQUEST['action']) && isset($_POST['test_url']) ) {
                        $post_id = (isset(self::$url2id[$req_url]))? self::$url2id[$req_url] : url_to_postid( $req_url );
                        self::$url2id[$req_url] = (int)$post_id;
                    } else {
                        global $wp_query;
                        if(is_object($wp_query->post) && !empty($wp_query->post->ID)) {
                            $post_id = $wp_query->post->ID;
                        } else {
                            $post_id = (isset(self::$url2id[$req_url]))? self::$url2id[$req_url] : url_to_postid( $req_url );
                            self::$url2id[$req_url] = (int)$post_id;
                        }
                    }
                }
                if(!empty($post_id)){
                    $post = get_post( $post_id );
                    if ( is_object($post) && !empty($post->post_type)) {
                        if(!empty($filter['post_type'])){
                            if(preg_match("#{$post->post_type}#", $filter['post_type'])){
                                if(!empty($filter['taxonomies']) && !empty($filter['term'])){
                                    if(self::is_term_in_taxonomy( $post_id, $filter['term'], $filter['taxonomies'])){
                                        $group = $filter['group'];
                                    }
                                } else {
                                    $group = $filter['group'];
                                }
                            }
                        } else {
                            if(!empty($filter['taxonomies']) && !empty($filter['term'])){
                                if(self::is_term_in_taxonomy( $post_id, $filter['term'], $filter['taxonomies'])){
                                    $group = $filter['group'];
                                }
                            } else {
                                $group = $filter['group'];
                            }
                        }
                    }
                }
            } else {
                $group = $filter['group'];
            }           
        }
        return($group);
    }

    static function plugin_keygen( $fname, $option ) { 
        $key = false;
        if($option === 'jetpack_active_modules'){
            $key = 'jetpack_module/' . $fname;
        } elseif($option === 'celtispack_active_modules'){
            $key = 'celtispack_module/' . str_replace( '.php', '', basename( $fname ) );        
        } else {
            $key = $fname;
        }
        return $key;
    }
   
    /**
     * Get valid plugins list for groupkey (for addon optional)
     * @param $groupkey : url filter group name
     * @param $filter   : plf filtering setting data
     * @param $option   : option data eg. 'active_plugins', 'active_sitewide_plugins', 'jetpack_active_modules' ...
     * @param $plugins  : active plugins values before filtering
     * @return data values after filtering
     */
    static function filter_to_active_plugins( $groupkey, $filter, $option, $plugins ){
        $new_plugins = array();
        foreach ( $plugins as $item ) {
            if(!empty($item)){
                $unload = false;
                $p_key = self::plugin_keygen( $item, $option );
                if(!empty($filter['plfurlkey'][$groupkey]['plugins'])){
                    if(false !== strpos($filter['plfurlkey'][$groupkey]['plugins'], $p_key))
                        $unload = true;
                }
                if(!$unload) {
                    if($option === 'active_sitewide_plugins'){
                        // v4.0.6 $new_plugins[$item] = $plugins[$item];
                        $new_plugins[$item] = $item;
                    } else {
                        $new_plugins[] = $item;
                    }
                }                
            }
        }
        
        $new_plugins = apply_filters('plf_custom_changes_to_active_plugins', $new_plugins);        
        return $new_plugins;
    }
    
    //Plugin Load Filter Main (active plugins/modules filtering)
    static function plf_filter( $option, $default = false) {
        if ( defined( 'WP_SETUP_CONFIG' ) || did_action( 'mu_plugin_loaded' ) === 0)
            return false;
        
        //Check if the caller is wp-settings.php wp_get_active_network_plugins() / wp_get_active_and_valid_plugins()
        if( in_array($option, array('active_plugins', 'active_sitewide_plugins' ))){
            $is_caller_target = false;
            $trace = debug_backtrace();
            foreach ($trace as $stp) {
                if(isset($stp['file']) && strpos($stp['file'], 'wp-settings.php') !== false){
                    if(isset($stp['function']) && in_array($stp['function'], array('wp_get_active_network_plugins', 'wp_get_active_and_valid_plugins'))){
                        $is_caller_target = true;
                        break;
                    }
                }
            }
            if( !$is_caller_target ) {
                return false;
            }            
        }
        
        if ( ! defined( 'WP_INSTALLING' ) ) {
        	global $wpdb, $current_site;
        	if ( is_multisite() && $option === 'active_sitewide_plugins' ) {
                //get_network_option() current site ID 
                $network_id = $current_site->id;

                // prevent non-existent options from triggering multiple queries
                $notoptions_key = "$network_id:notoptions";
                $notoptions = wp_cache_get( $notoptions_key, 'site-options' );
                if ( isset( $notoptions[ $option ] ) ) {
                    return apply_filters( 'default_site_option_' . $option, $default, $option );
                }

                $cache_key = "$network_id:$option";
                $opt_value = wp_cache_get( $cache_key, 'site-options' );

                if ( ! isset( $opt_value ) || false === $opt_value ) {
                    $row = $wpdb->get_row( $wpdb->prepare( "SELECT meta_value FROM $wpdb->sitemeta WHERE meta_key = %s AND site_id = %d", $option, $network_id ) );

                    // Has to be get_row instead of get_var because of funkiness with 0, false, null values
                    if ( is_object( $row ) ) {
                        $opt_value = $row->meta_value;
                        $opt_value = maybe_unserialize( $opt_value );
                        wp_cache_set( $cache_key, $opt_value, 'site-options' );
                    } else {
                        if ( ! is_array( $notoptions ) ) {
                            $notoptions = array();
                        }
                        $notoptions[ $option ] = true;
                        wp_cache_set( $notoptions_key, $notoptions, 'site-options' );

                        /** This filter is documented in wp-includes/option.php */
                        $opt_value = apply_filters( 'default_site_option_' . $option, $default, $option );
                    }
                }
            } else {
                // prevent non-existent options from triggering multiple queries
                $notoptions = wp_cache_get( 'notoptions', 'options' );
                if ( isset( $notoptions[$option] ) )
                    return apply_filters( 'default_option_' . $option, $default );

                $alloptions = wp_load_alloptions();
                if ( isset( $alloptions[$option] ) ) {
                    $opt_value = $alloptions[$option];
                } else {
                    $opt_value = wp_cache_get( $option, 'options' );

                    if ( false === $opt_value ) {
                        $row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) );

                        // Has to be get_row instead of get_var because of funkiness with 0, false, null values
                        if ( is_object( $row ) ) {
                            $opt_value = $row->option_value;
                            wp_cache_add( $option, $opt_value, 'options' );
                        } else { // option does not exist, so we must cache its non-existence
                            if ( ! is_array( $notoptions ) ) {
                                 $notoptions = array();
                            }
                            $notoptions[$option] = true;
                            wp_cache_set( 'notoptions', $notoptions, 'options' );

                            /** This filter is documented in wp-includes/option.php */
                            return apply_filters( 'default_option_' . $option, $default, $option );
                        }
                    }
                }
            }
        } else {
            return false;
        }
        
        $req_url = (isset($_SERVER['REQUEST_URI']))? $_SERVER['REQUEST_URI'] : '';
        $req_url = str_replace( "\\", "/", $req_url);
        $parse_url = parse_url($req_url);
        $action = (!empty($parse_url['path']) && strpos($parse_url['path'], 'admin-ajax.php' ) !== false && isset($_REQUEST['action']))? esc_attr($_REQUEST['action']) : '';
        
        $act_plugins  = ($option === 'active_sitewide_plugins')? array_keys( (array)$opt_value ) : maybe_unserialize( $opt_value );
        if($option === 'celtispack_active_modules'){
            if(empty(self::$base_plugins['celtispack-addon/celtispack-addon.php'])){
                $opt = get_option( 'celtis_addon_options', array() );
                if(!empty($opt['active_modules'])){
                    $nact_plugins = array();
                    foreach ($act_plugins as $pkey) {
                        if(!empty($pkey)){
                            $key = str_replace( '.php', '', basename( $pkey ));
                            if(!empty($opt['active_modules'][$key]))
                                continue;
                            $nact_plugins[] = $pkey;                            
                        }
                    }
                    $act_plugins = $nact_plugins;                    
                }
            }
        }        
        
        //Equal treatment for when the wp_is_mobile is not yet available（wp-include/vars.php wp_is_mobile)
        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
            $is_mobile = false;
        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
                $is_mobile = true;
        } else {
            $is_mobile = false;
        }
        $is_mobile = apply_filters( 'custom_is_mobile' , $is_mobile );

        //get_option is called many times, intermediate processing data to cache
        $keyid = md5('plf_'. (string)$is_mobile . $req_url . $action);
        if(!empty(self::$cache[$keyid][$option])){
            return self::$cache[$keyid][$option];
        }

        //Before plugins loaded, it does not use conditional branch such as is_home, to set wp_query, wp in temporary query
        if(empty($GLOBALS['wp_the_query'])){
            // プラグイン無効化時等で rewrite_rule がクリアされると rewrite_rule が更新されるまでカスタムポストタイプを判定できずにカスタムポストタイプのページはホームへ飛ばされる
            // 但し、permlink structure が plain(基本) の場合は除く
            if(!empty(get_option( 'permalink_structure' ))){
                $rewrite_rules = get_option('rewrite_rules');
                // rewrite_rule を監視して変更された場合はプラグインのフィルタリングをスキップさせていたが、
                // plugin activate / deactivate を監視するように変更したのでここでは空の場合のみチェック
                if(empty($rewrite_rules)){
                    return false;
                }
                // Welcart usces_itemnew 新規商品追加時のエラー (A variable mismatch has been detected.) parse_request で発生してるので特別に回避
                if ( isset( $_GET[ 'page' ] ) && isset( $_POST[ 'page' ] ) && $_GET[ 'page' ] !== $_POST[ 'page' ] ) {
                    return false;
                }
            }
            
            $GLOBALS['wp_the_query'] = new WP_Query();
            $GLOBALS['wp_query'] = $GLOBALS['wp_the_query'];
            $GLOBALS['wp_rewrite'] = new WP_Rewrite();
            $GLOBALS['wp'] = new WP();
            self::$base_public_query_vars = $GLOBALS['wp']->public_query_vars;        
            //register_taxonomy(category, post_tag, post_format) support for is_archive 
            self::force_initial_taxonomies();
            //Post Format, Custom Post Type support
            add_action('parse_request', array('Plf_filter', 'parse_request'));
            add_filter('doing_it_wrong_trigger_error', array('Plf_filter', 'exclude_trigger_error'), 10, 4 );
            //custom parse request filter (for experimental development)
            $custom = apply_filters( 'plf_experimental_custom_parse_request', false );            
            if ( false ===  $custom) {
                $GLOBALS['wp']->parse_request('');
            }    
            $GLOBALS['wp']->query_posts();            
        }

        //active plugin data
        foreach ($act_plugins as $key) {
            if(!empty($key)){
                $key = self::plugin_keygen( $key, $option );
                self::$base_plugins[$key] = $key;               
            }
        }        

        $filter = self::$filter;
                
        //plf Addon Extract only target data
        $devtype = ($is_mobile)? 'mobile' : 'desktop';
        $urltype = (!empty($parse_url['path']) && strpos($parse_url['path'], '/wp-admin' ) !== false)? 'admin' : 'front';                        
        if(!empty(self::$url_filter) && empty(self::$s_url_filter)){
            self::$s_url_filter = self::get_url_filter( 'active', $urltype, $devtype );
        }

        
        //======== The following are Admin backend page processes ========
        
        if($urltype === 'admin'){
            //wp-admin/plugins.php or wp-admin/update-core.php request : Do not filter this request URL
            if(strpos($parse_url['path'], '/plugins.php' ) !== false || strpos($parse_url['path'], '/update-core.php' ) !== false){
                return false;
            }
            if(!empty(self::$s_url_filter)){
                //plf Addon Admin Backend URL filtering
                foreach (self::$s_url_filter as $key => $v) {
                    if(!empty($v)){
                        $groupkey = self::is_url_match( $req_url, $parse_url, $v );
                        if($groupkey !== false) {
                            self::$is_filterkey = 'url-group-filter : ' . $groupkey;
                            $new_plugins = self::filter_to_active_plugins( $groupkey, $filter, $option, $act_plugins );                        
                            self::$cache[$keyid][$option] = $new_plugins;
                            foreach ($new_plugins as $key) {
                                if(!empty($key)){
                                    $key = self::plugin_keygen( $key, $option );
                                    self::$filtered_plugins[$key] = $key;                                    
                                }
                            }
                            return $new_plugins;
                        }                        
                    }
                }
            }
            return false;
        }

        
        //======== The following are frontend page processes ======== 

        global $wp_query;
        $unknown = false;
        if( ! is_embed() ){
            if((is_home() || is_front_page() || is_archive() || is_search() || is_singular()) == false || (is_home() && !empty($_GET))) {
                //bbPress users page requests are treated the same as is_home
                //downloadmanager plugin downloadlink request [home]/?wpdmact=XXXXXX  exclude home GET query
                $unknown = true;
                
            } elseif(is_singular() && empty($wp_query->post)){
                //documentroot special php file (wp-login.php, wp-cron.php, etc)  これらはこの時点では singular とみなされるが post が設定されていないことで判別
                //フィルタリングしたい場合は urlfilter Addon を使用すること
                //但し、private (非公開）ページ時は post がこの時点ではまだ設定されていないので private でクエリー発行して再確認            
                if(!empty($parse_url['path']) && strpos( $parse_url['path'], '.php' ) === false && !empty($wp_query->query)) {
                    //get post for private
                    $query = $wp_query->query;
                    $query['post_status'] = 'private';
                    $r = new WP_Query( $query );
                    if ($r->have_posts()) {
                        if(!empty($r->post)){
                            $wp_query->posts = $r->posts;
                            $wp_query->post = $r->post;
                        }
                    } else {
                        //カスタムステータスがまだ登録されてない状態でも取得 posts 情報がクリアされないよう抑制
                        add_filter( 'map_meta_cap', array('Plf_filter', 'exclude_map_meta_cap'), 10, 4 );
                        $query['post_status'] = 'any';
                        $r = new WP_Query( $query );
                        if ($r->have_posts()) {
                            if(!empty($r->post)){
                                $wp_query->posts = $r->posts;
                                $wp_query->post = $r->post;
                            }
                        }
                    }                        
                }
                if(empty($wp_query->post)){
                    $unknown = true;
                }                
            }
        }
        
        $single_opt = array();
        if(is_singular() && is_object($wp_query->post)){
            self::$pre_post_id = $wp_query->post->ID;   //for post_locale()
            $myfilter = get_post_meta( $wp_query->post->ID, '_plugin_load_filter', true );
            $default = array( 'filter' => 'default', 'desktop' => '', 'mobile' => '');
            $single_opt = (!empty($myfilter))? $myfilter : $default;
            $single_opt = wp_parse_args( $single_opt, $default);
        }
        if(!empty(self::$s_url_filter)){
            //Addon frontend URL filtering
            //個別フィルター有効なシングラーは URL filtering 無効（個別フィルター優先）
            if(empty($single_opt) || $single_opt['filter'] === 'default'){
                foreach (self::$s_url_filter as $key => $v) {
                    if(!empty($v)){
                        $groupkey = self::is_url_match( $req_url, $parse_url, $v );
                        if($groupkey !== false) {
                            self::$is_filterkey = 'url-group-filter : ' . $groupkey;
                            $new_plugins = self::filter_to_active_plugins( $groupkey, $filter, $option, $act_plugins );                        
                            self::$cache[$keyid][$option] = $new_plugins;
                            foreach ($new_plugins as $key) {
                                if(!empty($key)){
                                    $key = self::plugin_keygen( $key, $option );
                                    self::$filtered_plugins[$key] = $key;                                    
                                }
                            }
                            return $new_plugins;
                        }
                    }
                }
            }
        }
        if($unknown || empty($parse_url['path']) || strpos($parse_url['path'], '/wp-cron' ) !== false || strpos($parse_url['path'], '/wp-json' ) !== false || (!empty($parse_url['query']) && strpos($parse_url['query'], 'rest_route=' ) !== false )){
            //url filter 処理後、REST api, cron, フィルター対象外の不明なリクエストはフィルタリング処理を行わない
            return false;
        }
        
        //plf standard filtering
        $type = false;       
        if(!empty($filter['_pagefilter']['plugins'])){
            if(is_embed()){
                $type = 'content-card';
            } elseif(is_home() || is_front_page()){
                $type = 'home';
            } elseif(is_archive()) {
                $type = 'archive';
            } elseif(is_search()) {
                $type = 'search';
            } elseif(is_attachment()) {
                $type = 'attachment';
            } elseif(is_page()) {
                $type = 'page';
            } elseif(is_single()){ //Post & Custom Post
                $type = get_post_type( $wp_query->post);
                //bbPress private (非公開）ページ時のポストタイプ取得
                if($type === false && isset($wp_query->query_vars['post_type'])){
                    $type = $wp_query->query_vars['post_type'];
                }
                if($type === 'post'){
                    $fmt = get_post_format( $wp_query->post);
                    $type = ($fmt === 'standard' || $fmt == false)? 'post' : "post-$fmt";
                }
            } else {
                $type = 'unknown';
            }
            self::$is_filterkey = 'page-type-filter : ' . $type;
            if(is_singular()){
                if(!empty($single_opt) && $single_opt['filter'] === 'include'){
                    self::$is_filterkey = 'page-type-filter : Single page option';
                }
            }
        } elseif(!empty($filter['_admin']['plugins'])){
           //Page Type 未指定時は admin type 除外フィルターのみ
           self::$is_filterkey = 'page-type-filter : admin filter';
        }

        $new_plugins = array();
        foreach ( $act_plugins as $item ) {
            if(!empty($item)){
                $unload = false;
                $p_key = self::plugin_keygen( $item, $option );
                //admin filter
                if(!empty($filter['_admin']['plugins'])){
                    if(in_array($p_key, array_map("trim", explode(',', $filter['_admin']['plugins'])))){
                        $unload = true;
                    }
                }
                //page filter
                if(!$unload){
                    if(!empty($filter['_pagefilter']['plugins'])){
                        if(in_array($p_key, array_map("trim", explode(',', $filter['_pagefilter']['plugins'])))){
                            $unload = true;

                            //desktop/mobile device disable filter
                            $dis_dev = true; 
                            if(is_singular() && is_object($wp_query->post)){
                                if(!empty($single_opt) && $single_opt['filter'] === 'include'){
                                    if(!empty($single_opt[$devtype])){
                                        if(false !== strpos($single_opt[$devtype], $p_key))
                                            $dis_dev = false; 
                                        elseif(strpos($p_key, 'jetpack/') !== false && strpos($single_opt[$devtype], 'jetpack_module/') !== false)
                                            $dis_dev = false; 
                                        elseif(strpos($p_key, 'celtispack/') !== false && strpos($single_opt[$devtype], 'celtispack_module/') !== false)
                                            $dis_dev = false;                                         
                                    }
                                }
                            }
                            if(empty($single_opt) || $single_opt['filter'] === 'default'){
                                if(!empty($filter['group'][$devtype]['plugins'])){
                                    if(false !== strpos($filter['group'][$devtype]['plugins'], $p_key))
                                        $dis_dev = false;
                                    elseif(strpos($p_key, 'jetpack/') !== false && strpos($filter['group'][$devtype]['plugins'], 'jetpack_module/') !== false)
                                        $dis_dev = false; 
                                    elseif(strpos($p_key, 'celtispack/') !== false && strpos($filter['group'][$devtype]['plugins'], 'celtispack_module/') !== false)
                                        $dis_dev = false; 
                                }
                            }
                            if(!$dis_dev){
                                //oEmbed Content API
                                if(is_embed()){
                                    if(!empty($type) && !empty($filter['group'][$type]['plugins'])){
                                        if(false !== strpos($filter['group'][$type]['plugins'], $p_key))
                                            $unload = false;
                                        elseif(strpos($p_key, 'jetpack/') !== false && strpos($filter['group'][$type]['plugins'], 'jetpack_module/') !== false)
                                            $unload = false;
                                        elseif(strpos($p_key, 'celtispack/') !== false && strpos($filter['group'][$type]['plugins'], 'celtispack_module/') !== false)
                                            $unload = false;
                                    }
                                } else {
                                    $pgfopt = false;
                                    if(is_singular()){
                                        if(!empty($single_opt) && $single_opt['filter'] === 'include'){
                                            $pgfopt = true;
                                            if(!empty($single_opt[$devtype])){
                                                if(false !== strpos($single_opt[$devtype], $p_key)){
                                                    $unload = false; 
                                                } else {
                                                    //Enable plugin because plugin module is selected
                                                    if(strpos($p_key, 'jetpack/') !== false && strpos($single_opt[$devtype], 'jetpack_module/') !== false)
                                                        $unload = false;
                                                    elseif(strpos($p_key, 'celtispack/') !== false && strpos($single_opt[$devtype], 'celtispack_module/') !== false)
                                                        $unload = false;
                                                }                                                
                                            }                                            
                                        }
                                    }
                                    if($pgfopt === false){
                                        if(!empty($type) && !empty($filter['group'][$type]['plugins'])){
                                            if(in_array($p_key, array_map("trim", explode(',', $filter['group'][$type]['plugins'])))){
                                                $unload = false;
                                            } else {
                                                if(strpos($p_key, 'jetpack/') !== false && strpos($filter['group'][$type]['plugins'], 'jetpack_module/') !== false)
                                                    $unload = false;
                                                else if(strpos($p_key, 'celtispack/') !== false && strpos($filter['group'][$type]['plugins'], 'celtispack_module/') !== false)
                                                    $unload = false;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if(!$unload) {
                    if($option === 'active_sitewide_plugins') {
                        // v4.0.6 $new_plugins[$item] = $opt_value[$item];
                        $new_plugins[$item] = $item;
                    } else {
                        $new_plugins[] = $item;
                    }
                }
            }
        }
        
        //https://wordpress.org/support/topic/function-to-retriev-user-before-init-hook-is-fired/
        $new_plugins = apply_filters('plf_custom_changes_to_active_plugins', $new_plugins);
        
        self::$cache[$keyid][$option] = $new_plugins;
        foreach ($new_plugins as $key) {
            if(!empty($key)){
                $key = self::plugin_keygen( $key, $option );
                self::$filtered_plugins[$key] = $key;                
            }
        }
        return $new_plugins;
    }
}
