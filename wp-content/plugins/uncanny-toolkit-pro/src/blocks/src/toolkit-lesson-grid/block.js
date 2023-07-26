import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    ToolkitPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


if (typeof ultpModules.active != null && ultpModules.active.hasOwnProperty("uncanny_pro_toolkit\\LessonTopicGrid")) {

    registerBlockType('uncanny-toolkit-pro/lesson-topic-grid', {
        title: __('Enhanced Lesson Topic Grid', 'uncanny-pro-toolkit'),

        description: __('Displays a highly customizable grid of LearnDash lessons/topics.', 'uncanny-pro-toolkit'),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl', 'uncanny-pro-toolkit'),
        ],

        supports: {
            html: false
        },

        attributes: {
            courseId:
                {
                    'type': 'string',
                    'default': '',
                },
            lessonId:
                {
                    'type': 'string',
                    'default': '',
                },
            category:
                {
                    'type': 'string',
                    'default': 'all',
                },
            tag:
                {
                    'type': 'string',
                    'default': 'all',
                },
            cols:
                {
                    'type': 'string',
                    'default': '4',
                },
            showImage:
                {
                    'type': 'string',
                    'default': 'yes',
                },
            borderHover:
                {
                    'type': 'string',
                    'default': '',
                }
        },

        edit({className, attributes, setAttributes}) {
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        {__('Enhanced Lesson Topic Grid', 'uncanny-pro-toolkit')}
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
