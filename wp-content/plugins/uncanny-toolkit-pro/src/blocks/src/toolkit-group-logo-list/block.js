import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    ToolkitPlaceholder
} from '../components/editor';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

if ( typeof ultpModules.active != null && ultpModules.active.hasOwnProperty( 'uncanny_pro_toolkit\\GroupLogoList' ) ){

    registerBlockType( 'uncanny-toolkit-pro/group-logo', {
        title: __( 'Group Logo', 'uncanny-pro-toolkit' ),

        description: __( 'Displays the branding image associated with the current user\'s group. If the user is in more than one group with a branding logo, all logos will be displayed.', 'uncanny-pro-toolkit' ),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl'),
        ],

        supports: {
            html: false
        },

        attributes: {
            size: {
                type: 'string',
                default: 'full'
            }
        },

        edit({ className, attributes, setAttributes }){
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        { __( 'Group Logo', 'uncanny-pro-toolkit' ) }
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

    registerBlockType( 'uncanny-toolkit-pro/group-list', {
        title: __( 'Group List', 'uncanny-pro-toolkit' ),

        description: __( 'Displays a list of the groups the current user is a member of.', 'uncanny-pro-toolkit' ),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl'),
        ],

        supports: {
            html: false
        },

        attributes: {
            separator: {
                type: 'string',
                default: ', '
            }
        },

        edit({ className, attributes, setAttributes }){
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        { __( 'Group List', 'uncanny-pro-toolkit' ) }
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
