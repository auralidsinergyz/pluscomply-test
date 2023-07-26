const { __ } = wp.i18n;

const {
	assign
} = lodash;

const {
	addFilter
} = wp.hooks;

const {
	PanelBody,
	TextControl,
	SelectControl
} = wp.components;

const {
	Fragment
} = wp.element;

const {
	createHigherOrderComponent
} = wp.compose;

const {
	InspectorControls
} = wp.editor;

export const addUoEnrollmentKeyRedemptionSettings = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// Check if we have to do something
		if ( props.name == 'uncanny-learndash-groups/uo-groups-enrollment-key-redemption' && props.isSelected ){
			return (
				<Fragment>
					<BlockEdit { ...props } />
					<InspectorControls>

						<PanelBody title={ __( 'Enrollment Key Redemption Settings', 'uncanny-learndash-codes' ) }>
							<TextControl label={ __( 'redirect' ) } value={ props.attributes.redirect } type='text' onChange={ ( value ) => { props.setAttributes({ redirect: value }); }} />
						</PanelBody>

					</InspectorControls>
				</Fragment>
			);
		}

		return <BlockEdit { ...props } />;
	};
}, 'addUoEnrollmentKeyRedemptionSettings' );

addFilter(
	'editor.BlockEdit',
	'uncanny-learndash-groups/uo-groups-enrollment-key-redemption',
	addUoEnrollmentKeyRedemptionSettings
);
