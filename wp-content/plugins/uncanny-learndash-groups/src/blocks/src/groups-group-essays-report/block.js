import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    GroupsPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType('uncanny-learndash-groups/uo-groups-essays-report', {
    title: __('Essay Management', 'uncanny-learndash-groups'),

    description: __('Allows Group Leaders to manage essays posted by their group members', 'uncanny-learndash-groups'),

    icon: UncannyOwlIconColor,

    category: 'uncanny-learndash-groups',

    keywords: [
        __('Uncanny Owl - Groups Plugin', 'uncanny-learndash-groups'),
    ],

    supports: {
        html: false
    },

    attributes: {
        columns: {
            type: 'string',
            default: 'Title, First Name, Last Name, Username, Status, Points, Question, Content, Course, Lesson, Quiz, Comments, Date'
        },
        csvExport: {
            type: 'string',
            default: 'hide'
        },
        excelExport: {
            type: 'string',
            default: 'hide'
        },
    },

    edit({className, attributes, setAttributes}) {
        return (
            <div className={className}>
                <GroupsPlaceholder>
                    {__('Group Essay Management', 'uncanny-learndash-groups')}
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
