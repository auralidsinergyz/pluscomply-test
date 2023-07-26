import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    GroupsPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType('uncanny-learndash-groups/uo-groups-course-report', {
    title: __('Course Report', 'uncanny-learndash-groups'),

    description: __('The Course Report (which can also be accessed from the Group Management page) allows Group Leaders to view course reports of their group members.', 'uncanny-learndash-groups'),

    icon: UncannyOwlIconColor,

    category: 'uncanny-learndash-groups',

    keywords: [
        __('Uncanny Owl - Groups Plugin', 'uncanny-learndash-groups'),
    ],

    supports: {
        html: false
    },

    attributes: {
        transcriptPageId: {
            type: 'string',
            default: '0'
        },
        courseOrder: {
            type: 'string',
            default: 'title'
        },
    },

    edit({className, attributes, setAttributes}) {
        return (
            <div className={className}>
                <GroupsPlaceholder>
                    {__('Course Report', 'uncanny-learndash-groups')}
                </GroupsPlaceholder>
            </div>
        );
    },

    save({className, attributes}) {
        // We're going to render this block using PHP
        // Return null
        return null;
    },
});
