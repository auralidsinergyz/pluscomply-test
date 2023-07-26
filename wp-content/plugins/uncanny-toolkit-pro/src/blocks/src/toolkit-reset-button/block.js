import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    ToolkitPlaceholder
} from '../components/editor';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

if ( typeof ultpModules.active != null && ultpModules.active.hasOwnProperty( 'uncanny_pro_toolkit\\LearnDashReset' ) ){

    registerBlockType( 'uncanny-toolkit-pro/reset-button', {
        title: __( 'Reset Button', 'uncanny-pro-toolkit' ),

        description: __( 'Displays a button that enables a user to reset their progress in a course.', 'uncanny-pro-toolkit' ),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl', 'uncanny-pro-toolkit'),
        ],

        supports: {
            html: false
        },

        attributes: {
            courseId: {
                type: 'string',
                default: ''
            },
            resetTincanny: {
                type: 'string',
                default: 'no'
            }
        },

        edit({ className, attributes, setAttributes }){
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        { __( 'Reset Button', 'uncanny-pro-toolkit' ) }
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
