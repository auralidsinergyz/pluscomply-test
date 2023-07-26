<?php

namespace ebsso;

/*
 * EDW General Settings
 *
 * @link       https://edwiser.org
 * @since      1.0.0
 *
 * @package    Edwiser Bridge
 * @subpackage Edwiser Bridge/admin
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SSOSettingsRedirection')) {

    /**
     * SSO Settings.
     */
    class SSOSettingsRedirection
    {

        /**
         * Function provides the functionality to send the
         * @global type $current_tab contains the value of the current selected settings tab value from edwiser bridge
         */
        public function getUserRedirectionSettings()
        {
            global $current_tab;
            $settings = get_option('eb_' . $current_tab . '_redirection');
            $this->getDefaultRedSettings($settings);
            $class    = "ebsso-hide";
            if (isset($settings['ebsso_role_base_redirect']) && $settings['ebsso_role_base_redirect'] == 'on') {
                $class = "";
            }
            ?>

            <div id="ebsso-role-redirect-setting-block" class="<?php echo $class; ?>">
                <?php
                $this->roleSettings($settings);
                ?>
            </div>
            <?php
        }

        /**
         * Function defines the default column name and provides the filter to add or modify colum rows.
         * @return array return the array of the colum headers for the user role based redirection settings.
         */
        private function getRoleBasedSettingsColNames()
        {
            $heders = array('User Roles', 'Redirect', 'Manage');
            return apply_filters("eb_sso_settings_role_redirect_table_headers", $heders);
        }

        /**
         * Function provides the functionality to display the default redirection settings.
         *
         * @param array $settings contains the old settings for the SSO plugin redirection
         */
        private function getDefaultRedSettings($settings)
        {
            $defRediURL    = isset($settings['ebsso_login_redirect_url']) ? $settings['ebsso_login_redirect_url'] : "";
            $roleBaseRedir = "";
            if (isset($settings['ebsso_role_base_redirect']) && $settings['ebsso_role_base_redirect'] == 'on') {
                $roleBaseRedir = 'checked';
            }
            ?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="ebsso_login_redirect_url"><?php _e('Common Login Redirect URL', 'single_sign_on_text_domain'); ?></label>
                        </th>
                        <td class="forminp forminp-url">
                            <input name="ebsso_login_redirect_url" id="ebsso_login_redirect_url" type="url" value='<?php echo $defRediURL; ?>' placeholder="e.g. http://mymoodle.com/my/">
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <?php _e("Enable user role based redirect", "single_sign_on_text_domain"); ?>
                        </th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e("Enable user role based redirect", "single_sign_on_text_domain"); ?></span>
                                </legend>
                                <label for="ebsso_role_base_redirect">
                                    <input name="ebsso_role_base_redirect" id="ebsso_role_base_redirect" type="checkbox" value="on" <?php echo $roleBaseRedir; ?>>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
        }

        /**
         * This will display the role based redirection settings.
         *
         * @param array $settings contains the old settings for the SSO plugin redirection
         */
        private function roleSettings($settings)
        {
            $exisitngRules   = $this->getExistingRedirRules($settings);
            $roles           = $this->getUserRoles($exisitngRules);
            $rolesToAddRules = $roles;
            ?>
            <h3 class="ebsso-role-redirect-settings">
                <?php echo __('User Role Based Redirection Settings', 'single_sign_on_text_domain'); ?>
            </h3>
            <?php
            $this->createRoleRediRule($rolesToAddRules, "top");
            $this->showSetRoleRedirections($exisitngRules);
            $this->createRoleRediRule($rolesToAddRules, "bottom");
        }

        /**
         * Function filters the roles and associated urls stored in the plugin settings.
         *
         * @param array $settings contains the old settings for the SSO plugin redirection.
         * @return returns the array of the old rules.
         */
        private function getExistingRedirRules($settings)
        {

            $redirectData = array();
            foreach ($settings as $key_id => $value) {
                if (strpos($key_id, "ebsso_login_redirect_url_") !== false) {
                    $key                = str_replace("ebsso_login_redirect_url_", "", $key_id);
                    $redirectData[$key] = $value;
                }
            }
            return $redirectData;
        }

        /**
         * Function provides the functionality to get the all the roles
         * available on the system and remove the previously added roles from the output array.
         *
         * @global Object $wp_roles This is the global variable defined for the user roles by WP.
         * @param array $exisitngRules The array of the roles previously added in setting.
         * @return array array of the role names where roles are not associated with the url in the sso settings.
         */
        private function getUserRoles($exisitngRules)
        {

            global $wp_roles;
            $roles = $wp_roles->get_names();
            foreach ($exisitngRules as $key => $value) {
                if (isset($roles[$key])) {
                    unset($roles[$key]);
                }
                unset($value);
            }
            return $roles;
        }

        /**
         * Function provides the functionality to generate the view to add the new user role
         *  and redirection rule for it.
         *
         * @param array $addRulesForRole This is the set of the user roles to add new rules.
         * @param String $pos This is the position of the view where it will be displayed like top/bottum.
         * This will be used for the identifying the element of the view
         */
        private function createRoleRediRule($addRulesForRole, $pos)
        {
            $urlInId = "ebsso_selected_login_redirect_url_" . $pos;
            $btnId   = "ebsso_add_role_setting_" . $pos;
            ?>
            <div class="ebsso-setting-red-rule">
                <?php $this->roleSelector($addRulesForRole, $pos); ?>
                <label class="ebsso-setting-filed-lbl"><?php _e("URL", "single_sign_on_text_domain"); ?></label>
                <input name="<?php echo $urlInId; ?>" id="<?php echo $urlInId; ?>" type="url" class="ebsso-role-redi-new-setting-url" placeholder="e.g. http://mymoodle.com/my/">
                <input type="button" id="<?php echo $btnId; ?>" name="<?php echo $btnId; ?>" class="ebsso-add-new-redirect-rule" value="<?php echo _e("Add", ""); ?>">
                <span class="ebsso-error" style="color:red"></span>
            </div>
            <?php
        }

        /**
         * Create the select box for the given roles.
         *
         * @param array $addRulesForRole Roles to add in the select options
         * @param String $pos Position of the select box
         */
        private function roleSelector($addRulesForRole, $pos)
        {
            ?>
            <label class="ebsso-setting-filed-lbl"><?php _e("Select user role", "single_sign_on_text_domain"); ?></label>
            <select name="ebsso-role-<?php echo $pos; ?>" class="ebsso-role-redi-new-setting-role" id="ebsso-role-<?php echo $pos; ?>">
                <option value=""><?php _e("Select Role", "single_sign_on_text_domain"); ?></option>
                <?php
                foreach ($addRulesForRole as $key => $value) {
                    ?>
                    <option value="<?php echo $key; ?>">
                        <?php _e($value, "single_sign_on_text_domain"); ?>
                    </option>
                    <?php
                }
                ?>
            </select>
            <?php
        }

        /**
         * Provides the functionality to display the user roles table.
         *
         * @param array $rulesTodisplay array of the roles where URL are set.
         */
        private function showSetRoleRedirections($rulesTodisplay)
        {
            $header = $this->getRoleBasedSettingsColNames();
            ?>
            <table id='ebsso-tbl-role-redirect-rule' class='ebsso-role-redirect-settings wp-list-table widefat fixed striped posts'>
                <thead>
                    <?php $this->getTableHeadingRow($header); ?>
                </thead>
                <tbody class='role-table-row'>
                    <?php $this->createTableBody($rulesTodisplay); ?>
                </tbody>
                <tfoot>
                    <?php $this->getTableHeadingRow($header); ?>
                </tfoot>
            </table>
            <?php
        }

        /**
         * Creates the table header
         * @param array $heders This is the array of the roles table column.
         */
        private function getTableHeadingRow($heders = array())
        {
            ?>
            <tr>
                <?php
                foreach ($heders as $lable) {
                    $this->getTableHeraderTag($lable);
                }
                ?>
            </tr>
            <?php
        }

        /**
         * Creates the table heading tag.
         * @param String $lable Table column heading text
         */
        private function getTableHeraderTag($lable)
        {
            ?>
            <th scope="col" class="manage-column column-title column-primary">
                <span><?php _e(ucfirst($lable), "single_sign_on_text_domain"); ?></span>
            </th>
            <?php
        }

        /**
         * Function provides the functionality to display the roles table body.
         * @global Object $wp_roles global variable of the all WP roles defined by the WP
         * @param array $rolesRedirRules  Array of the rules.
         */
        private function createTableBody($rolesRedirRules)
        {
            global $wp_roles;
            $allRoles = $wp_roles->get_names();

            foreach ($rolesRedirRules as $roleId => $role) {
                $roleDispName =$allRoles[$roleId]
                ?>
                <tr id="<?php echo "ebsso_login_redirect_row_" . $roleId ?>">
                    <?php $this->createTableRow($roleDispName, $roleId, $role); ?>
                </tr>
                <?php
            }
        }

        /**
         * Creates the table row cells elements.
         *
         * @param String $roleDispName role name to display
         * @param String $roleId user role id.
         * @param String $redirectURL redirect URL for the role.
         */
        private function createTableRow($roleDispName, $roleId, $redirectURL)
        {
            $fieldId = "ebsso_login_redirect_url_" . $roleId;
            do_action("ebsso_settings_at_redir_row_start", $roleId);
            ?>
            <td class="ebsso-setting-filed-lbl">
                <?php _e(ucfirst($roleDispName), "single_sign_on_text_domain"); ?>
            </td>
            <td>
                <input type="url" name="<?php echo $fieldId; ?>" id="<?php echo $fieldId; ?>" value="<?php echo $redirectURL; ?>"/>
            </td>
            <td>
                <input type="button" data-name='<?php echo $roleDispName; ?>' data-text="<?php echo $roleId; ?>" class="ebsso-edit-manage-redirect-rule" name="<?php echo $fieldId . "-btn"; ?>" id="<?php echo $fieldId . "-btn"; ?>" value="<?php _e("Delete", "single_sign_on_text_domain"); ?>" class="eb-sso-btn-dele-redire-setting"/>
                <?php do_action("ebsso_settings_tbl_redir_more_row_action", $roleId); ?>
            </td>
            <?php
            do_action("ebsso_settings_at_redir_row_end", $roleId);
        }
    }
}
