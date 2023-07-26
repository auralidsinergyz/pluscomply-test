

<?php $active_sub_tab = isset( $_GET[ 'sub-tab' ] ) ? $_GET[ 'sub-tab' ] : 'group'; ?>

<form method="post" action="options.php">

    <ul class="ld-mc-admin-sub-tab">
        <li class=" <?php echo $active_sub_tab == 'group' ? 'active' : ''; ?>" ><a href="?page=ld-mc-admin-dashboard&tab=email-settings&sub-tab=group">Group Certificate Email</a></li>
        <li class=" <?php echo $active_sub_tab == 'course' ? 'active' : ''; ?>"><a href="?page=ld-mc-admin-dashboard&tab=email-settings&sub-tab=course">Course Certificate Email</a></li>
        <li class=" <?php echo $active_sub_tab == 'quiz' ? 'active' : ''; ?>"><a href="?page=ld-mc-admin-dashboard&tab=email-settings&sub-tab=quiz">Quiz Certificate Email</a></li>
    </ul>

    <?php
    if( $active_sub_tab == 'group' ){
        settings_fields( 'ld_mc_group_email_settings_group' );
        do_settings_sections( 'ld_mc_group_email_settings_page' );
    }elseif( $active_sub_tab == 'course' ){
        settings_fields( 'ld_mc_course_email_settings_group' );
        do_settings_sections( 'ld_mc_course_email_settings_page' );
    }elseif( $active_sub_tab == 'quiz' ){
        settings_fields( 'ld_mc_quiz_email_settings_group' );
        do_settings_sections( 'ld_mc_quiz_email_settings_page' );
    }

submit_button();
    ?>

</form>
