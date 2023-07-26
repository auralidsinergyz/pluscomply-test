import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    GroupsPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType('uncanny-learndash-groups/uo-groups', {
    title: __('Group Management', 'uncanny-learndash-groups'),

    description: __('The Group Management page provides all the functionality for Group Leaders to manage their own groups.', 'uncanny-learndash-groups'),

    icon: UncannyOwlIconColor,

    category: 'uncanny-learndash-groups',

    keywords: [
        __('Uncanny Owl - Groups Plugin', 'uncanny-learndash-groups'),
    ],

    supports: {
        html: false
    },

    attributes: {
        groupNameSelector: {
            type: 'string',
            default: 'show'
        },
        groupCoursesSection: {
            type: 'string',
            default: 'show'
        },
        addCoursesButton: {
            type: 'string',
            default: 'show'
        },
        seatsQuantity: {
            type: 'string',
            default: 'show'
        },
        addSeatsButton: {
            type: 'string',
            default: 'show'
        },
        addUserButton: {
            type: 'string',
            default: 'show'
        },
        removeUserButton: {
            type: 'string',
            default: 'show'
        },
        uploadUsersButton: {
            type: 'string',
            default: 'show'
        },
        downloadKeysButton: {
            type: 'string',
            default: 'show'
        },
        progressReportButton: {
            type: 'string',
            default: 'show'
        },
        csvExportButton: {
            type: 'string',
            default: 'show'
        },
        excelExportButton: {
            type: 'string',
            default: 'hide'
        },
        quizReportButton: {
            type: 'string',
            default: 'show'
        },
        keyColumn: {
            type: 'string',
            default: 'show'
        },
        groupLeaderSection: {
            type: 'string',
            default: 'show'
        },
        addGroupLeaderButton: {
            type: 'string',
            default: 'show'
        },
        keyOptions: {
            type: 'string',
            default: 'show'
        },
        groupEmailEutton: {
            type: 'string',
            default: 'hide'
        },

        firstLastNameRequired: {
            type: 'boolean',
            default: false
        },
        enrolledUsersPageLength: {
            type: 'string',
            default: '50',
        },
        enrolledUsersLengthMenu: {
            type: 'string',
            default: `25\n50\n100\n-1 : ${ __( 'All', 'uncanny-learndash-groups' ) }`,
        },
        groupLeadersPageLength: {
            type: 'string',
            default: '50',
        },
        groupLeadersLengthMenu: {
            type: 'string',
            default: `25\n50\n100\n-1 : ${ __( 'All', 'uncanny-learndash-groups' ) }`,
        },
    },

    edit({className, attributes, setAttributes}) {
        return (
            <div className={className}>
                <GroupsPlaceholder>
                    {__('Group Management', 'uncanny-learndash-groups')}
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

registerBlockType('uncanny-learndash-groups/uo-groups-url', {
    title: __('Group Management Link', 'uncanny-learndash-groups'),

    description: __('Add a button that links to the Group Management Page.', 'uncanny-learndash-groups'),

    icon: UncannyOwlIconColor,

    category: 'uncanny-learndash-groups',

    keywords: [
        __('Uncanny Owl - Groups Plugin', 'uncanny-learndash-groups'),
    ],

    supports: {
        html: false
    },

    attributes: {
        text: {
            type: 'string',
            default: __( 'Group Management', 'uncanny-learndash-groups' )
        },
    },

    edit({className, attributes, setAttributes}) {
        return (
            <div className={className}>
                <GroupsPlaceholder>
                    {__('Group Management Link Settings', 'uncanny-learndash-groups')}
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

