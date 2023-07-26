import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    ToolkitPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


if (typeof ultpModules.active != null && ultpModules.active.hasOwnProperty("uncanny_pro_toolkit\\LearnDashTranscript")) {

    registerBlockType('uncanny-toolkit-pro/learn-dash-transcript', {
        title: __( 'Learner Transcript', 'uncanny-pro-toolkit' ),

        description: __( 'Add printable transcripts to the front end for your learners. This is a great way for learners to have a record of all course progress and overall standing.', 'uncanny-pro-toolkit' ),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl','uncanny-pro-toolkit'),
        ],

        supports: {
            html: false
        },

        attributes: {
            logoUrl: {
                type: 'string',
                default: ''
            },
            dateFormat: {
                type: 'string',
                default: 'F j, Y'
            }
        },

        edit({className, attributes, setAttributes}) {
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        {__( 'Learner Transcript', 'uncanny-pro-toolkit' )}
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
