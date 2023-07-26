import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
	UncannyLearnDashCodesPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;

registerBlockType('uncanny-learndash-codes/uo-user-redeem-code', {
        title: __('User Redeem Code', 'uncanny-learndash-codes'),

        description: __('Signed in users can redeem group or course codes from anywhere on a LearnDash site by using this.', 'uncanny-learndash-codes'),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-codes',

        keywords: [
            __('Uncanny Owl'),
        ],

        supports: {
            html: false
        },

        attributes: {},

        edit({className, attributes, setAttributes}) {
            return (
                <div className={className}>
                    <UncannyLearnDashCodesPlaceholder>
                        {__('User Redeem Code', 'uncanny-learndash-codes')}
                    </UncannyLearnDashCodesPlaceholder>
                </div>
            );
        },

        save({className, attributes}) {
            // We're going to render this block using PHP
            // Return null
            return null;
        },
    });

