/**
 * Custom ColorPalette component.
 * 
 * @since 2.0.7
 */

import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { BaseControl, Button, ColorPalette as GBColorPalette }  from '@wordpress/components';

class ColorPalette extends Component {
    constructor( props ) {
        super( props );
    }

    render() {
        const {
            name,
            value,
            label,
            display_state,
            setAttributes
        } = this.props;

        return(
            <BaseControl
                className={ typeof display_state[ name ] !== 'undefined' && ! display_state[ name ] ? 'hide color-picker' : 'show color-picker' }
                label={ label }
            >
                <div className="color-wrapper">
                    <GBColorPalette
                        colors={ [] }
                        value={ value || '' }
                        onChange={ ( new_value ) => {
                            setAttributes( { [ name ]: new_value } );
                        } }
                        clearable={ false }
                    />
                    <Button
                        className='clear-button'
                        variant='tertiary'
                        onClick={ () => {
                            setAttributes( {
                                [ name ]: null
                            } )
                        } }
                    >
                        { __( 'Clear', 'learndash-course-grid' ) }
                    </Button>
                    <div className="clear"></div>
                </div>
            </BaseControl>
        );
    }
}

export default ColorPalette;
