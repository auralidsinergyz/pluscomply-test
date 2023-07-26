<?php

/**
 * Plugin Dashboard.
 *
 * @link    https://plugins360.com
 * @since   1.6.5
 *
 * @package All_In_One_Video_Gallery
 */
?>

<div id="aiovg-dashboard" class="wrap about-wrap full-width-layout aiovg-dashboard">
	<h1><?php 
esc_html_e( 'All-in-One Video Gallery', 'all-in-one-video-gallery' );
?></h1>
    
    <p class="about-text">
        <?php 
esc_html_e( 'Add responsive video galleries anywhere on your website – no coding required. Includes HTML5 Player, Thumbnail Grid, Slider, Popup & more.', 'all-in-one-video-gallery' );
?>
    </p>

    <?php 
?>
        
	<div class="wp-badge aiovg-badge"><?php 
printf( esc_html__( 'Version %s', 'all-in-one-video-gallery' ), AIOVG_PLUGIN_VERSION );
?></div>
    
    <h2 class="nav-tab-wrapper wp-clearfix">
		<?php 
foreach ( $tabs as $tab => $title ) {
    $url = admin_url( add_query_arg( 'tab', $tab, 'admin.php?page=all-in-one-video-gallery' ) );
    $class = ( $tab == $active_tab ? 'nav-tab nav-tab-active' : 'nav-tab' );
    
    if ( 'issues' == $tab ) {
        $class .= ' aiovg-text-error';
        $title .= sprintf( ' <span class="count">(%d)</span>', count( $issues['found'] ) );
    }
    
    printf(
        '<a href="%s" class="%s">%s</a>',
        esc_url( $url ),
        $class,
        $title
    );
}
?>
    </h2>

    <?php 
require_once AIOVG_PLUGIN_DIR . "admin/partials/{$active_tab}.php";
?>    
</div>