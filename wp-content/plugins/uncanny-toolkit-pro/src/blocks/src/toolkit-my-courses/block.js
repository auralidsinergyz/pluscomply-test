import './sidebar.js';

import {
    moduleIsActive
} from '../utilities';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    ToolkitPlaceholder
} from '../components/editor';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

if ( moduleIsActive( `uncanny_pro_toolkit\\learnDashMyCourses` ) ){
    
    registerBlockType( 'uncanny-toolkit-pro/learn-dash-my-courses', {
        title: __( 'Course Dashboard', 'uncanny-pro-toolkit' ),

        description: __( 'Displays a highly customizable grid of LearnDash courses.', 'uncanny-pro-toolkit'),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __( 'Uncanny Owl', 'uncanny-pro-toolkit' ),
        ],

        supports: {
            html: false
        },

        attributes: {
            orderby: {
                'type': 'string',
                'default': 'ID',
            },
            order: {
                'type': 'string',
                'default': 'asc',
            },
            show: {
                'type': 'string',
                'default': 'enrolled',
            },
            ldCategory: {
                'type': 'string',
                'default': 'all',
            },
            category: {
                'type': 'string',
                'default': 'all',
            },
            categoryselector: {
                'type': 'boolean',
                'default': false,
            },
            course_categoryselector: { 
                'type': 'boolean',
                'default': false,
            },
            expand_by_default: { 
                'type': 'string',
                'default': 'no',
            },
        },

        edit({ className, attributes, setAttributes }) {
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        {__( 'Course Dashboard', 'uncanny-pro-toolkit' )}
                    </ToolkitPlaceholder>
                </div>
            );
        },

        save({ className, attributes }) {
            // We're going to render this block using PHP
            // Return null
            return null;
        },
    });
}
