
<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap" id="ld-mc-admin-settings">
<h1></h1>

<!--    --><?php //settings_errors(); ?>

    <div class="ld-mc-admin-dashboard-header">
        <div class="ld-mc-plugin-info">
            <h3><?php echo LD_MC_NAME; ?></h3>
        </div>
        <div class="ld-mc-links-wraper">
            <a href="https://wooninjas.com/open-support-ticket/" class="ld-mc-admin-header-link">
                <span class="dashicons dashicons-sos"></span> <?php echo __('Support',LD_MC_TEXT_DOMAIN);  ?>
            </a>
            <a href="#" class="ld-mc-admin-header-link">
                <span class="dashicons dashicons-book-alt"></span> <?php echo __('Documentation',LD_MC_TEXT_DOMAIN);  ?>
            </a>
        </div>
    </div>

    <?Php
    // echo LD_MC_NAME;
    // die();
    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', LD_MC_TEXT_DOMAIN ), 'updated' );
    }
    ?>



    <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'license-settings'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=ld-mc-admin-dashboard&tab=license-settings" class="nav-tab <?php echo $active_tab == 'license-settings' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-network"></span> <?php echo __('License Settings',LD_MC_TEXT_DOMAIN);  ?></a>
        <a href="?page=ld-mc-admin-dashboard&tab=general-settings" class="nav-tab <?php echo $active_tab == 'general-settings' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-email-alt"></span> <?php echo __('General Settings',LD_MC_TEXT_DOMAIN);  ?> </a>
        <a href="?page=ld-mc-admin-dashboard&tab=email-settings" class="nav-tab <?php echo $active_tab == 'email-settings' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-email-alt"></span> <?php echo __('Email Settings',LD_MC_TEXT_DOMAIN);  ?></a>
    </h2>

    <div class="tab-content">
        <?php
            if( $active_tab == 'license-settings' ) {
                $this->get_template('admin.tabs.license-settings', array(), true, false );
            }
            if( $active_tab == 'general-settings' ) {
                $this->get_template('admin.tabs.general-settings', array(), true, false );
            }
            if( $active_tab == 'email-settings' ) {
                $this->get_template('admin.tabs.email-settings', array(), true, false );
            }
        ?>
    </div>

</div><!-- /.wrap -->