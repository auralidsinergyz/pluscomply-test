const {__} = wp.i18n;

const {
    assign
} = lodash;

const {
    addFilter
} = wp.hooks;

const {
    SelectControl,
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

export const addUoGroupCourseReportSettings = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        // Check if we have to do something
        if (props.name == 'uncanny-learndash-groups/uo-groups-course-report' && props.isSelected) {
            return (
                <Fragment>
                    <BlockEdit {...props} />
                    <InspectorControls>

                        <PanelBody title={__('Group Management Settings', 'uncanny-learndash-groups')}>

                            <TextControl
                                label={ __( 'Transcript Page ID', 'uncanny-learndash-groups' ) }
                                value={ props.attributes.transcriptPageId }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        transcriptPageId: value
                                    });
                                }}
                            />

                            <SelectControl
                                label={__('Course Order', 'uncanny-learndash-groups')}
                                value={props.attributes.courseOrder}
                                options={ [
                                    { label: 'Title', value: 'title' },
                                    { label: 'ID', value: 'ID' },
                                    { label: 'Date', value: 'date' },
                                    { label: 'Menu Order', value: 'menu_order' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({courseOrder: value});
                                }}
                            />
                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit {...props} />;
    };
}, 'addUoGroupCourseReportSettings');

addFilter('editor.BlockEdit', 'uncanny-learndash-groups/uo-groups-course-report', addUoGroupCourseReportSettings);
