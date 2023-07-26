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

export const addToolkitUoTimeSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/uo-time' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Course Time Settings', 'uncanny-pro-toolkit' ) }>
				            <TextControl
				                label={ __( 'User ID', 'uncanny-pro-toolkit' ) }
				                value={ props.attributes.userId }
				                type="string"
				                onChange={ ( value ) => {
				                    props.setAttributes({
				                    	userId: value
				                    });
				                }}
				            />
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
				        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit { ...props } />;
    };
}, 'addToolkitUoTimeSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/uo-time', addToolkitUoTimeSettings );