const {__} = wp.i18n;

const {
    assign
} = lodash;

const {
    addFilter
} = wp.hooks;

const {
    PanelBody,
    TextControl
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

export const addUoGroupBuyCoursesSettings = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        // Check if we have to do something
        if (props.name == 'uncanny-learndash-groups/uo-groups-buy-courses' && props.isSelected) {
            return (
                <Fragment>
                    <BlockEdit {...props} />
                    <InspectorControls>

                        <PanelBody title={__('Only include products from', 'uncanny-learndash-groups')}>

                            <TextControl
                                label={ __( 'Product Categories (IDs seperated by commas)', 'uncanny-learndash-groups' ) }
                                value={ props.attributes.productCat }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        productCat: value
                                    });
                                }}
                            />

                           <TextControl
                                label={ __( 'Product Tags (IDs seperated by commas)', 'uncanny-learndash-groups' ) }
                                value={ props.attributes.productCat }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        productTag: value
                                    });
                                }}
                            />

							<TextControl
								label={ __( 'Minimum Quantity', 'uncanny-learndash-groups' ) }
								value={ props.attributes.minQty }
								type="number"
								onChange={ ( value ) => {
									props.setAttributes({
										minQty: value
									});
								}}
							/>

							<TextControl
								label={ __( 'Maximum Quantity', 'uncanny-learndash-groups' ) }
								value={ props.attributes.maxQty }
								type="number"
								onChange={ ( value ) => {
									props.setAttributes({
										maxQty: value
									});
								}}
							/>
                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit {...props} />;
    };
}, 'addUoGroupBuyCoursesSettings');

addFilter('editor.BlockEdit', 'uncanny-learndash-groups/uo-groups-buy-courses', addUoGroupBuyCoursesSettings);
