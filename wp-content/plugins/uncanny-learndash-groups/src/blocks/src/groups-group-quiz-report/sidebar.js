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

export const addUoGroupQuizReportSettings = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        // Check if we have to do something
        if (props.name == 'uncanny-learndash-groups/uo-groups-quiz-report' && props.isSelected) {
            return (
                <Fragment>
                    <BlockEdit {...props} />
                    <InspectorControls>

                        <PanelBody title={__('Group Management Settings', 'uncanny-learndash-groups')}>

                            <SelectControl
                                label={__('Course Order By', 'uncanny-learndash-groups')}
                                value={props.attributes.courseOrderby}
                                options={ [
                                    { label: 'Title', value: 'title' },
                                    { label: 'ID', value: 'ID' },
                                    { label: 'Date', value: 'date' },
                                    { label: 'Menu Order', value: 'menu_order' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({courseOrderby: value});
                                }}
                            />

                            <SelectControl
                                label={__('Course Order', 'uncanny-learndash-groups')}
                                value={props.attributes.courseOrder}
                                options={ [
                                    { label: 'Ascending', value: 'ASC' },
                                    { label: 'Descending', value: 'DESC' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({courseOrder: value});
                                }}
                            />


                            <SelectControl
                                label={__('Quiz Order By', 'uncanny-learndash-groups')}
                                value={props.attributes.quizOrderby}
                                options={ [
                                    { label: 'Title', value: 'title' },
                                    { label: 'ID', value: 'ID' },
                                    { label: 'Date', value: 'date' },
                                    { label: 'Menu Order', value: 'menu_order' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({quizOrderby: value});
                                }}
                            />

                            <SelectControl
                                label={__('Quiz Order', 'uncanny-learndash-groups')}
                                value={props.attributes.quizOrder}
                                options={ [
                                    { label: 'Ascending', value: 'ASC' },
                                    { label: 'Descending', value: 'DESC' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({quizOrder: value});
                                }}
                            />
                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit {...props} />;
    };
}, 'addUoGroupQuizReportSettings');

addFilter('editor.BlockEdit', 'uncanny-learndash-groups/uo-groups-quiz-report', addUoGroupQuizReportSettings);
