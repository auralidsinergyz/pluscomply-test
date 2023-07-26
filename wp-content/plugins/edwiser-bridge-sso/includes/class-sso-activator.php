<?php

namespace ebsso;

if (!defined('ABSPATH')) {
    exit('This is not the way to call me!');
}

class SsoActivator
{

    /**
     * networkWide tells if the plugin was activated for the entire network or just for single site.
     * @since    1.1.1
     */
    private static $networkWide = false;

    /**
     * activation function.
     * @since    1.0.0
     */
    public static function activate($networkWide)
    {
        /**
         * deactivates legacy extensions
         */
        self::$networkWide = $networkWide;

        // create database tables & Pages
        self::checkSingleOrMultiSite();
    }

    /**
     * checks if the plugin is activated on a SIngle site or Network wide
     *
     * @since    1.1.1
     */
    public static function checkSingleOrMultiSite()
    {

        if (is_multisite()) {
            // print_r(is_plugin_active_for_network('edwiser-bridge/edwiser-bridge.php')); die();

            if (self::$networkWide) {
                $allSites = wp_get_sites();
                foreach ($allSites as $blog) {
                    self::handleNewBlog($blog['blog_id']);
                }
            } else {
                self::handleNewBlog($blog['blog_id']);
            }
        } else {
            self::createSocialLoginDB();
        }
    }

    public static function createSocialLoginDB()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $tblGpOauth      = $wpdb->prefix . 'gp_oauth_users';

        $stmtGpOauth = "CREATE TABLE $tblGpOauth (
            `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
            `oauth_provider` VARCHAR(255) NOT NULL ,
            `oauth_uid` VARCHAR(255) NOT NULL ,
            `first_name` VARCHAR(100) NOT NULL ,
            `last_name` VARCHAR(100) NOT NULL ,
            `email` VARCHAR(255) NOT NULL ,
            `gender` VARCHAR(10),
            `locale` VARCHAR(10),
            `picture` VARCHAR(255),
            `link` VARCHAR(255),
            `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `wp_user_id` BIGINT(20),
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($stmtGpOauth);
    }

    /**
     * handles addtion of new blog
     *
     * @since  1.1.1
     */
    public static function handleNewBlog($blog_id)
    {
        \switch_to_blog($blog_id);
        self::createSocialLoginDB();
        \restore_current_blog();
    }
}
