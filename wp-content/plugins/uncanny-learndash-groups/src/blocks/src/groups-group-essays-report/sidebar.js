const {__} = wp.i18n;

const {
	assign
} = lodash;

const {
	addFilter
} = wp.hooks;

const {
	SelectControl,
	PanelBody,
	TextControl,
	ToggleControl
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

export const addUoGroupEssaysReportSettings = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// Check if we have to do something
		if (
			props.name == 'uncanny-learndash-groups/uo-groups-essays-report'
			&& props.isSelected
		) {
			return (
				<Fragment>

					<BlockEdit {...props} />

					<InspectorControls>

						<PanelBody title={ __('General', 'uncanny-learndash-groups')}>

							<TextControl
								label={ __( 'Columns', 'uncanny-learndash-groups' ) }
								value={ props.attributes.columns }
								type="string"
								onChange={ ( value ) => {
									props.setAttributes({
										columns: value
									});
								}}
							/>

						</PanelBody>

						<PanelBody title={ __( 'Export buttons', 'uncanny-learndash-groups' ) }>

							<ToggleControl
								label={ __( 'CSV export', 'uncanny-learndash-groups' ) }
								checked={ props.attributes.csvExport == 'show' }
								onChange={ ( value ) => {
									props.setAttributes({
										csvExport: value ? 'show' : 'hide'
									});
								} }
							/>

							<ToggleControl
								label={ __( 'Excel export', 'uncanny-learndash-groups' ) }
								checked={ props.attributes.excelExport == 'show' }
								onChange={ ( value ) => {
									props.setAttributes({
										excelExport: value ? 'show' : 'hide'
									});
								} }
							/>
							
						</PanelBody>

					</InspectorControls>

				</Fragment>
			);
		}

		 return <BlockEdit { ...props } />;
	};
}, 'addUoGroupEssaysReportSettings' );

addFilter(
	'editor.BlockEdit', 
	'uncanny-learndash-groups/uo-groups-essays-report', 
	addUoGroupEssaysReportSettings
);
