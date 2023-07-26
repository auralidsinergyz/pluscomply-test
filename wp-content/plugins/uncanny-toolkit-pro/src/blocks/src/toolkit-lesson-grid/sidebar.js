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

export const addToolkitLessonTopicGridSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/lesson-topic-grid' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Grid Content', 'uncanny-pro-toolkit' ) }>
                            <TextControl label={ __( 'courseId', 'uncanny-pro-toolkit' ) } value={ props.attributes.courseId } type='text' onChange={ ( value ) => { props.setAttributes({ courseId: value }); }} />
                            <TextControl label={ __( 'lessonId', 'uncanny-pro-toolkit' ) } value={ props.attributes.lessonId } type='text' onChange={ ( value ) => { props.setAttributes({ lessonId: value }); }} />
                            <TextControl label={ __( 'category', 'uncanny-pro-toolkit' ) } value={ props.attributes.category } type='text' onChange={ ( value ) => { props.setAttributes({ category: value }); }} />
                            <TextControl label={ __( 'tag', 'uncanny-pro-toolkit' ) } value={ props.attributes.tag } type='text' onChange={ ( value ) => { props.setAttributes({ tag: value }); }} />
				        </PanelBody>

                        <PanelBody title={ __( 'Grid Style', 'uncanny-pro-toolkit' ) }>
                            <TextControl label={ __( 'cols', 'uncanny-pro-toolkit' ) } value={ props.attributes.cols } type='text' onChange={ ( value ) => { props.setAttributes({ cols: value }); }} />
                            <TextControl label={ __( 'showImage', 'uncanny-pro-toolkit' ) } value={ props.attributes.showImage } type='text' onChange={ ( value ) => { props.setAttributes({ showImage: value }); }} />
                            <TextControl label={ __( 'borderHover', 'uncanny-pro-toolkit' ) } value={ props.attributes.borderHover } type='text' onChange={ ( value ) => { props.setAttributes({ borderHover: value }); }} />
                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }



        return <BlockEdit { ...props } />;
    };
}, 'addToolkitLessonTopicGridSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/lesson-topic-grid', addToolkitLessonTopicGridSettings );