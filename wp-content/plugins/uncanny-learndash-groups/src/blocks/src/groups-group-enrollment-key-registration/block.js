import './sidebar.js';

import {
	UncannyOwlIconColor
} from '../components/icons';

import {
	GroupsPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType('uncanny-learndash-groups/uo-groups-enrollment-key-registration', {
	title: __('Enrollment Key Registration', 'uncanny-learndash-groups'),

	description: __('This will add a very basic registration form to the page that includes a code redemption field.', 'uncanny-learndash-groups'),

	icon: UncannyOwlIconColor,

	category: 'uncanny-learndash-groups',

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
				<GroupsPlaceholder>
					{__('User Code Registration', 'uncanny-learndash-groups')}
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
