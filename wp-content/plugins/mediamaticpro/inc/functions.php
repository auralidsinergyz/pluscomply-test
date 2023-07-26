<?php

function mediamatic_core_init()
{
	if(class_exists('Mediamatic_Topbar'))
	{
		return;
	}
	add_action( 'wp_enqueue_scripts', 'mediamatic_register_scripts', 20);
}


function mediamatic_register_scripts()
{
	if (!is_user_logged_in())
	{
		return;
	}
	if (is_front_page())
	{
		return;
	}

	
	add_action('wp_enqueue_scripts', function(){wp_enqueue_media();});

	wp_enqueue_media();
	add_thickbox();
	
	wp_register_script( 'mediamatic-builder-util', MEDIAMATIC_URL . '/assets/js/mediamatic-util.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'mediamatic-builder-util' );

	wp_register_script( 'mediamatic-builder-upload-hook', MEDIAMATIC_URL . '/assets/js/hook-post-add-media.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'mediamatic-builder-upload-hook' );

};


function mediamatic_cores()
{
	$free = false;

	if (MEDIAMATIC_PLUGIN_NAME == 'Mediamatic Lite')
	{
		$free = true;
	}
    
	if ($free)
	{
		add_filter( 'plugin_action_links_' . MEDIAMATIC_PLUGIN_BASE, 'mediamatic_go_pro_version' );
	}
}


function mediamatic_go_pro_version($links)
{
	$links[] = '<a target="_blank" href="http://mediamatic.frenify.com/1/" style="color: #43B854; font-weight: bold">'. esc_html__('Go Pro', MEDIAMATIC_TEXT_DOMAIN) .'</a>';
	return $links;
}

call_user_func('mediamatic_core_init');
