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

export const addUncannyUoCodeRedeemSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-learndash-codes/uo-code-registration' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />

                </Fragment>
            );
        }



        return <BlockEdit { ...props } />;
    };
}, 'addUncannyUoCodeRedeemSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-learndash-codes/uo-user-redeem-code', addUncannyUoCodeRedeemSettings );
