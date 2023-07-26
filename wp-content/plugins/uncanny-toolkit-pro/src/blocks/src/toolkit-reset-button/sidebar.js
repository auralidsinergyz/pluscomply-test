const { __ } = wp.i18n;

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

export const addToolkitResetButtonSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/reset-button' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Reset Button Settings', 'uncanny-pro-toolkit' ) }>
                            <TextControl
                                label={ __( 'Course ID', 'uncanny-pro-toolkit' ) }
                                value={ props.attributes.courseId }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        courseId: value
                                    });
                                }}
                            />
                            <TextControl
                                label={ __( 'Reset TinCanny Data', 'uncanny-pro-toolkit' ) }
                                value={ props.attributes.resetTincanny }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        resetTincanny: value
                                    });
                                }}
                            />
				        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit { ...props } />;
    };
}, 'addToolkitResetButtonSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/reset-button', addToolkitResetButtonSettings );