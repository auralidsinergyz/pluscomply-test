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

export const addUoGroupProgressReportSettings = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        // Check if we have to do something
        if (props.name === 'uncanny-learndash-groups/uo-groups-progress-report' && props.isSelected) {
            return (
                <Fragment>
                    <BlockEdit {...props} />
                    <InspectorControls>

                        <PanelBody title={__('Group Management Settings', 'uncanny-learndash-groups')}>

                            <SelectControl
                                label={__('Course Order By', 'uncanny-learndash-groups')}
                                value={props.attributes.orderby}
                                options={ [
									{ label: 'ID', value: 'ID' },
									{ label: 'Title', value: 'title' },
                                    { label: 'Date', value: 'date' },
                                    { label: 'Menu Order', value: 'menu_order' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({orderby: value});
                                }}
                            />

                            <SelectControl
                                label={__('Course Order', 'uncanny-learndash-groups')}
                                value={props.attributes.order}
                                options={ [
                                    { label: 'Ascending', value: 'asc' },
                                    { label: 'Descending', value: 'desc' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({order: value});
                                }}
                            />

                            <SelectControl
                                label={__('Expand Course By Default', 'uncanny-learndash-groups')}
                                value={props.attributes.expandByDefault}
                                options={ [
                                    { label: 'No', value: 'no' },
                                    { label: 'Yes', value: 'yes' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({expandByDefault: value});
                                }}
                            />
                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit {...props} />;
    };
}, 'addUoGroupProgressReportSettings');

addFilter('editor.BlockEdit', 'uncanny-learndash-groups/uo-groups-progress-report', addUoGroupProgressReportSettings);
