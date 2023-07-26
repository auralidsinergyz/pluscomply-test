import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    GroupsPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType('uncanny-learndash-groups/uo-groups-buy-courses', {
    title: __('Buy Courses', 'uncanny-learndash-groups'),

    description: __('The Buy Courses page allows Group Leaders and new customers to purchase courses and create new Groups directly. This page is only auto-generated if you activate the plugin after WooCommerce is installed and active.', 'uncanny-learndash-groups'),

    icon: UncannyOwlIconColor,

    category: 'uncanny-learndash-groups',

    keywords: [
        __('Uncanny Owl - Groups Plugin', 'uncanny-learndash-groups'),
    ],

    supports: {
        html: false
    },

    attributes: {
        productCat: {
            type: 'string',
            default: '',
        },
        productTag: {
            type: 'string',
            default: '',
        },
		minQty: {
			type: 'string',
			default: '',
		},
		maxQty: {
			type: 'string',
			default: '',
		},
    },

    edit({className, attributes, setAttributes}) {
        return (
            <div className={className}>
                <GroupsPlaceholder>
                    {__('Buy Courses', 'uncanny-learndash-groups')}
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
