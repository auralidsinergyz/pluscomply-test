import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    GroupsPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType('uncanny-learndash-groups/uo-groups-quiz-report', {
    title: __('Quiz Report', 'uncanny-learndash-groups'),

    description: __('The Quiz Report allows Group Leaders to view quiz reports of their group members', 'uncanny-learndash-groups'),

    icon: UncannyOwlIconColor,

    category: 'uncanny-learndash-groups',

    keywords: [
        __('Uncanny Owl - Groups Plugin', 'uncanny-learndash-groups'),
    ],

    supports: {
        html: false
    },

    attributes: {
        courseOrderby: {
            type: 'string',
            default: 'title'
        },
        courseOrder: {
            type: 'string',
            default: 'ASC'
        },
        quizOrderby: {
            type: 'string',
            default: 'title'
        },
        quizOrder: {
            type: 'string',
            default: 'ASC'
        },
    },

    edit({className, attributes, setAttributes}) {
        return (
            <div className={className}>
                <GroupsPlaceholder>
                    {__('Quiz Report', 'uncanny-learndash-groups')}
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
