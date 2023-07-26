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

export const addToolkitGroupOrgDetailsSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/group-org-details' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Group Organization Settings' ) }>
                            <TextControl
                                label={ __( 'Group ID' ) }
                                value={ props.attributes.groupId }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        groupId: value
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
}, 'addToolkitGroupOrgDetailsSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/group-org-details', addToolkitGroupOrgDetailsSettings );