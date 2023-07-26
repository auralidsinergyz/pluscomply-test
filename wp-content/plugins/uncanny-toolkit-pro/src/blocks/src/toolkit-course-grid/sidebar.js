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

export const addToolkitCourseGridSettings = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Check if we have to do something
        if ( props.name == 'uncanny-toolkit-pro/course-grid' && props.isSelected ){
            return (
                <Fragment>
                    <BlockEdit { ...props } />
                    <InspectorControls>

                        <PanelBody title={ __( 'Grid Content' ) }>
                            {/*<SelectControl*/}
                                {/*multiple*/}
                                {/*label={ __( 'Select some users:' ) }*/}
                                {/*value={ props.attributes.category } // e.g: value = [ 'a', 'c' ]*/}
                                {/*onChange={ ( value ) => { props.setAttributes({ category: value }); }}*/}
                                {/*options={ [*/}
                                    {/*{ value: 'a', label: 'User A' },*/}
                                    {/*{ value: 'b', label: 'User B' },*/}
                                    {/*{ value: 'c', label: 'User c' },*/}
                                {/*] }*/}
                            {/*/>*/}
                            <TextControl label={ __( 'Category' ) } value={ props.attributes.category } type='text' onChange={ ( value ) => { props.setAttributes({ category: value }); }} />
                            <TextControl label={ __( 'LearnDash Category' ) } value={ props.attributes.ldCategory } type='text' onChange={ ( value ) => { props.setAttributes({ ldCategory: value }); }} />
                            <TextControl label={ __( 'Tag' ) } value={ props.attributes.tag } type='text' onChange={ ( value ) => { props.setAttributes({ tag: value }); }} />
                            <TextControl label={ __( 'Course Tag' ) } value={ props.attributes.course_tag } type='text' onChange={ ( value ) => { props.setAttributes({ course_tag: value }); }} />
                            <TextControl label={ __( 'Enrolled Only' ) } value={ props.attributes.enrolledOnly } type='text' onChange={ ( value ) => { props.setAttributes({ enrolledOnly: value }); }} />
                            <TextControl label={ __( 'Not Enrolled Only' ) } value={ props.attributes.notEnrolled } type='text' onChange={ ( value ) => { props.setAttributes({ notEnrolled: value }); }} />
                            <TextControl label={ __( 'Limit' ) } value={ props.attributes.limit } type='text' onChange={ ( value ) => { props.setAttributes({ limit: value }); }} />
				        </PanelBody>

                        <PanelBody title={ __( 'Grid Style' ) }>
                            <TextControl label={ __( 'Columns' ) } value={ props.attributes.cols } type='text' onChange={ ( value ) => { props.setAttributes({ cols: value }); }} />
                            <TextControl label={ __( 'Hide View More' ) } value={ props.attributes.hideViewMore } type='text' onChange={ ( value ) => { props.setAttributes({ hideViewMore: value }); }} />
                            <TextControl label={ __( 'Hide Credits' ) } value={ props.attributes.hideCredits } type='text' onChange={ ( value ) => { props.setAttributes({ hideCredits: value }); }} />
                            <TextControl label={ __( 'Hide Description' ) } value={ props.attributes.hideDescription } type='text' onChange={ ( value ) => { props.setAttributes({ hideDescription: value }); }} />
                            <TextControl label={ __( 'Hide Progress' ) } value={ props.attributes.hideProgress } type='text' onChange={ ( value ) => { props.setAttributes({ hideProgress: value }); }} />
                            <TextControl label={ __( 'More' ) } value={ props.attributes.more } type='text' onChange={ ( value ) => { props.setAttributes({ more: value }); }} />
                            <TextControl label={ __( 'Show Images' ) } value={ props.attributes.showImage } type='text' onChange={ ( value ) => { props.setAttributes({ showImage: value }); }} />
                            <TextControl label={ __( 'Price' ) } value={ props.attributes.price } type='text' onChange={ ( value ) => { props.setAttributes({ price: value }); }} />
                            <TextControl label={ __( 'Currency' ) } value={ props.attributes.currency } type='text' onChange={ ( value ) => { props.setAttributes({ currency: value }); }} />
                            <TextControl label={ __( 'Link To Course' ) } value={ props.attributes.linkToCourse } type='text' onChange={ ( value ) => { props.setAttributes({ linkToCourse: value }); }} />
                            <TextControl label={ __( 'Order By' ) } value={ props.attributes.orderby } type='text' onChange={ ( value ) => { props.setAttributes({ orderby: value }); }} />
                            <TextControl label={ __( 'Order' ) } value={ props.attributes.order } type='text' onChange={ ( value ) => { props.setAttributes({ order: value }); }} />
                            <TextControl label={ __( 'Default Sorting' ) } value={ props.attributes.defaultSorting } type='text' onChange={ ( value ) => { props.setAttributes({ defaultSorting: value }); }} />
                            <TextControl label={ __( 'Ignore Default Sorting' ) } value={ props.attributes.ignoreDefaultSorting } type='text' onChange={ ( value ) => { props.setAttributes({ ignoreDefaultSorting: value }); }} />
                            <TextControl label={ __( 'Border Hover' ) } value={ props.attributes.borderHover } type='text' onChange={ ( value ) => { props.setAttributes({ borderHover: value }); }} />
                            <TextControl label={ __( 'View More Color' ) } value={ props.attributes.viewMoreColor } type='text' onChange={ ( value ) => { props.setAttributes({ viewMoreColor: value }); }} />
                            <TextControl label={ __( 'View More Hover' ) } value={ props.attributes.viewMoreHover } type='text' onChange={ ( value ) => { props.setAttributes({ viewMoreHover: value }); }} />
                            <TextControl label={ __( 'View More Text Color' ) } value={ props.attributes.viewMoreTextColor } type='text' onChange={ ( value ) => { props.setAttributes({ viewMoreTextColor: value }); }} />
                            <TextControl label={ __( 'View More Text' ) } value={ props.attributes.viewMoreText } type='text' onChange={ ( value ) => { props.setAttributes({ viewMoreText: value }); }} />
                            <TextControl label={ __( 'View Less Text' ) } value={ props.attributes.viewLessText } type='text' onChange={ ( value ) => { props.setAttributes({ viewLessText: value }); }} />
                            <TextControl label={ __( 'Category Selector' ) } value={ props.attributes.categoryselector } type='text' onChange={ ( value ) => { props.setAttributes({ categoryselector: value }); }} />
                            <TextControl label={ __( 'Course Category Selector' ) } value={ props.attributes.courseCategoryselector } type='text' onChange={ ( value ) => { props.setAttributes({ courseCategoryselector: value }); }} />
                            <TextControl label={ __( 'Start Course Button' ) } value={ props.attributes.startCourseButton } type='text' onChange={ ( value ) => { props.setAttributes({ startCourseButton: value }); }} />
                            <TextControl label={ __( 'Resume Course Button' ) } value={ props.attributes.resumeCourseButton } type='text' onChange={ ( value ) => { props.setAttributes({ resumeCourseButton: value }); }} />
                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }



        return <BlockEdit { ...props } />;
    };
}, 'addToolkitCourseGridSettings' );

addFilter( 'editor.BlockEdit', 'uncanny-toolkit-pro/course-grid', addToolkitCourseGridSettings );