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

export const addTookitCourseExpirySettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/course-expiry' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Course Expiry Settings', 'uncanny-pro-toolkit' ) }>
                            <TextControl
                                label={ __( 'Course ID', 'uncanny-pro-toolkit' ) }
                                value={ props.attributes.courseId }
                                type="number"
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
}, 'addTookitCourseExpirySettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/course-expiry', addTookitCourseExpirySettings );