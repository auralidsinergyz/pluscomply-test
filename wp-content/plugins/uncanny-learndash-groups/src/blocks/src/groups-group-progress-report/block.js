import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    GroupsPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType('uncanny-learndash-groups/uo-groups-progress-report', {
    title: __('Progress Report', 'uncanny-learndash-groups'),

    description: __('This hierarchy view of courses, lessons, topics and quizzes allows all of them be reviewed and marked complete or incomplete.', 'uncanny-learndash-groups'),

    icon: UncannyOwlIconColor,

    category: 'uncanny-learndash-groups',

    keywords: [
        __('Uncanny Owl - Groups Plugin', 'uncanny-learndash-groups'),
    ],

    supports: {
        html: false
    },

    attributes: {
        orderby: {
            type: 'string',
            default: 'ID'
        },
        order: {
            type: 'string',
            default: 'asc'
        },
		expandByDefault: {
            type: 'string',
            default: 'no'
        },
    },

    edit({className, attributes, setAttributes}) {
        return (
            <div className={className}>
                <GroupsPlaceholder>
                    {__('Progress Report', 'uncanny-learndash-groups')}
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
