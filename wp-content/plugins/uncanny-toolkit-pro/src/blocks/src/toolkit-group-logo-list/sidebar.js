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

export const addToolkitGroupLogoSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/group-logo' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Group Logo Settings' ) }>
                            <TextControl
                                label={ __( 'Size' ) }
                                value={ props.attributes.size }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        size: value
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
}, 'addToolkitGroupLogoSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/group-logo', addToolkitGroupLogoSettings );

export const addToolkitGroupListSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/group-list' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Group List Settings' ) }>
                            <TextControl
                                label={ __( 'Separator' ) }
                                value={ props.attributes.separator }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        separator: value
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
}, 'addToolkitGroupListSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/group-list', addToolkitGroupListSettings );