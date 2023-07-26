import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    ToolkitPlaceholder
} from '../components/editor';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

if ( typeof ultpModules.active != null && ultpModules.active.hasOwnProperty( 'uncanny_pro_toolkit\\LearnDashGroupSignUp' ) ){

    registerBlockType( 'uncanny-toolkit-pro/group-status', {
        title: __( 'Group Status', 'uncanny-pro-toolkit' ),

        description: __( 'Displays the Organization Details of all groups the current user is a member of.', 'uncanny-pro-toolkit' ),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl'),
        ],

        supports: {
            html: false
        },

        attributes: {},

        edit({ className, attributes, setAttributes }){
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        { __( 'Group Status', 'uncanny-pro-toolkit' ) }
                    </ToolkitPlaceholder>
                </div>
            );
        },

        save({ className, attributes }){
            // We're going to render this block using PHP
            // Return null
            return null;
        },
    });

    registerBlockType( 'uncanny-toolkit-pro/group-org-details', {
        title: __( 'Group Organization', 'uncanny-pro-toolkit' ),

        description: __( 'Displays the Organization Details of the group associated with the group registration page the shortcode is placed on. *Works only on group registration pages.*', 'uncanny-pro-toolkit' ),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl'),
        ],

        supports: {
            html: false
        },

        attributes: {
            groupId: {
                type: 'string',
                default: ', '
            }
        },

        edit({ className, attributes, setAttributes }){
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        { __( 'Group Organization', 'uncanny-pro-toolkit' ) }
                    </ToolkitPlaceholder>
                </div>
            );
        },

        save({ className, attributes }){
            // We're going to render this block using PHP
            // Return null
            return null;
        },
    });

    registerBlockType( 'uncanny-toolkit-pro/group-login', {
        title: __( 'Group Login Form', 'uncanny-pro-toolkit' ),

        description: __( 'When used on a group page, displays a login form. Any user that uses the form to log in is automatically added to the group.  *For use on group pages only.*', 'uncanny-pro-toolkit' ),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl'),
        ],

        supports: {
            html: false
        },

        attributes: {},

        edit({ className, attributes, setAttributes }){
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        { __( 'Group Login Form', 'uncanny-pro-toolkit' ) }
                    </ToolkitPlaceholder>
                </div>
            );
        },

        save({ className, attributes }){
            // We're going to render this block using PHP
            // Return null
            return null;
        },
    });
}
