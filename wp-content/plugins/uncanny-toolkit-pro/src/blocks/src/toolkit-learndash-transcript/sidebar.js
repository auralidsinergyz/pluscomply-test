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

export const addToolkitLearnDashTranscriptSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/learn-dash-transcript' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Learner Transcript Settings' ) }>
                            <TextControl
                                label={ __( 'Logo Url' ) }
                                value={ props.attributes.logoUrl }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        logoUrl: value
                                    });
                                }}
                            />
                            <TextControl
                                label={ __( 'Date Format' ) }
                                value={ props.attributes.dateFormat }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        dateFormat: value
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
}, 'addToolkitLearnDashTranscriptSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/learn-dash-transcript', addToolkitLearnDashTranscriptSettings );