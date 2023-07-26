import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    GroupsPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType('uncanny-learndash-groups/uo-groups-enrollment-key-redemption', {
	title: __('Enrollment Key Redemption', 'uncanny-learndash-groups'),

	description: __('Signed in users can redeem group or course codes from anywhere on a LearnDash site by using this.', 'uncanny-learndash-groups'),

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
	},

	edit({className, attributes, setAttributes}) {
		return (
			<div className={className}>
				<GroupsPlaceholder>
					{__('User Redeem Code', 'uncanny-learndash-groups')}
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
