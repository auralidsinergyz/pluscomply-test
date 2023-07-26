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
    SelectControl,
    RadioControl,
    ToggleControl
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

export const addToolkitCourseDashboardSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/learn-dash-my-courses' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Filter Content', 'uncanny-pro-toolkit' ) }>

                            <TextControl
                                label={ __( 'WordPress Category ID', 'uncanny-pro-toolkit' ) }
                                value={ props.attributes.category }
                                type='number'
                                help={ __( 'Leave empty to show all courses', 'uncanny-pro-toolkit' ) }
                                onChange={ ( value ) => {
                                    props.setAttributes({ category: value });
                                }}
                            />

                            <TextControl
                                label={ __( 'LearnDash Category ID', 'uncanny-pro-toolkit' ) }
                                value={ props.attributes.ldCategory }
                                type='number'
                                help={ __( 'Leave empty to show all courses', 'uncanny-pro-toolkit' ) }
                                onChange={ ( value ) => {
                                    props.setAttributes({ ldCategory: value });
                                }}
                            />

                            <SelectControl
                                label={ __( 'Course Status', 'uncanny-pro-toolkit' ) }
                                value={ props.attributes.show }
                                options={[
                                    {
                                        value: 'all',
                                        label: __( 'All courses', 'uncanny-pro-toolkit' )
                                    },
                                    {
                                        value: 'enrolled',
                                        label: __( 'Enrolled courses only', 'uncanny-pro-toolkit' )
                                    },
                                    {
                                        value: 'open',
                                        label: __( 'Open courses only', 'uncanny-pro-toolkit' )
                                    },
                                ]}
                                onChange={ ( value ) => {
                                    props.setAttributes({ show: value });
                                }}
                            />

				        </PanelBody>

                        <PanelBody title={ __( 'Order', 'uncanny-pro-toolkit' ) }>

                            <SelectControl
                                label={ __( 'Order By', 'uncanny-pro-toolkit' ) }
                                value={ props.attributes.orderby }
                                options={[
                                    {
                                        value: 'ID',
                                        label: __( 'ID', 'uncanny-pro-toolkit' )
                                    },
                                    {
                                        value: 'title',
                                        label: __( 'Title', 'uncanny-pro-toolkit' )
                                    },
                                    {
                                        value: 'date',
                                        label: __( 'Date', 'uncanny-pro-toolkit' )
                                    },
                                    {
                                        value: 'menu_order',
                                        label: __( 'Menu order', 'uncanny-pro-toolkit' )
                                    },
                                ]}
                                onChange={ ( value ) => {
                                    props.setAttributes({ orderby: value });
                                }}
                            />

                            <RadioControl  
                                label={ __( 'Order Direction', 'uncanny-pro-toolkit' ) }
                                selected={ props.attributes.order }
                                options={[
                                    {
                                        value: 'asc',
                                        label: __( 'Ascending', 'uncanny-pro-toolkit' )
                                    },
                                    {
                                        value: 'desc',
                                        label: __( 'Descending','uncanny-pro-toolkit' )
                                    },
                                 ]}
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        order: value
                                    });
                                } }
                            />

                        </PanelBody>

                        <PanelBody title={ __( 'Element Visibility', 'uncanny-pro-toolkit' ) }>

                            <ToggleControl
                                label={ __( 'Show WordPress Category Selector', 'uncanny-pro-toolkit' ) }
                                checked={ props.attributes.categoryselector }
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        categoryselector: ! props.attributes.categoryselector
                                    });
                                } }
                            />

                            <ToggleControl
                                label={ __( 'Show LearnDash Category Selector', 'uncanny-pro-toolkit' ) }
                                checked={ props.attributes.course_categoryselector }
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        course_categoryselector: ! props.attributes.course_categoryselector
                                    });
                                } }
                            />

                        </PanelBody>

                        <PanelBody title={ __( 'Dashboard Behavior', 'uncanny-pro-toolkit' ) }>

                            <RadioControl  
                                label={ __( 'Expand by Default', 'uncanny-pro-toolkit' ) }
                                selected={ props.attributes.expand_by_default }
                                options={[
                                    {
                                        value: 'yes',
                                        label: __( 'Yes', 'uncanny-pro-toolkit' )
                                    },
                                    {
                                        value: 'no',
                                        label: __( 'No', 'uncanny-pro-toolkit' )
                                    },
                                 ]}
                                onChange={ ( value ) => {
                                    props.setAttributes({ expand_by_default: value });
                                } }
                            />

                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }



        return <BlockEdit { ...props } />;
    };
}, 'addToolkitCourseDashboardSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/learn-dash-my-courses', addToolkitCourseDashboardSettings );