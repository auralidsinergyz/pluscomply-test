import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
	UncannyLearnDashCodesPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;

registerBlockType('uncanny-learndash-codes/uo-code-registration', {
        title: __('User Code Registration', 'uncanny-learndash-codes'),

        description: __('This will add a very basic registration form to the page that includes a code redemption field.', 'uncanny-learndash-codes'),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-codes',

        keywords: [
            __('Uncanny Owl'),
        ],

        supports: {
            html: false
        },

        attributes: {
			redirect:
                {
                    'type': 'string',
                    'default': '',
                },
			code_optional:
                {
                    'type': 'string',
                    'default': 'no',
                },
			auto_login:
                {
                    'type': 'string',
                    'default': 'yes',
                },
			role:
                {
                    'type': 'string',
                    'default': 'subscriber',
                },

        },

        edit({className, attributes, setAttributes}) {
            return (
                <div className={className}>
                    <UncannyLearnDashCodesPlaceholder>
                        {__('User Code Registration', 'uncanny-learndash-codes')}
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

