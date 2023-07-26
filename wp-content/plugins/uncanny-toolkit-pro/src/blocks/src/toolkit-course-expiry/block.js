import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    ToolkitPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;

if (typeof ultpModules.active != null && ultpModules.active.hasOwnProperty("uncanny_pro_toolkit\\CourseAccessExpiry")) {
    registerBlockType('uncanny-toolkit-pro/course-expiry', {
        title: __( 'Days Until Course Expiry', 'uncanny-pro-toolkit' ),

        description: __( 'Displays the number of days until the learner\'s access expires.', 'uncanny-pro-toolkit' ),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl', 'uncanny-pro-toolkit'),
        ],

        supports: {
            html: false
        },

        attributes: {
            preText: {
                type: 'string',
                default: __( 'Course Access Expires in', 'uncanny-pro-toolkit' )
            },
            courseId: {
                type: 'string',
                default: null
            }
        },

        edit({className, attributes, setAttributes}) {
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        {__( 'Days Until Course Expiry', 'uncanny-pro-toolkit' )}
                    </ToolkitPlaceholder>
                </div>
            );
        },

        save({className, attributes}) {
            // We're going to render this block using PHP
            // Return null
            return null;
        },
    });
}
