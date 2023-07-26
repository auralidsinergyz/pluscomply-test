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
    TextControl,
    TextareaControl,
    ToggleControl,
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

export const addGroupsUOSettings = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        // Check if we have to do something
        if (props.name == 'uncanny-learndash-groups/uo-groups' && props.isSelected) {

            const firstLastNameRequired = <ToggleControl
                label={ __( 'First and last name required', 'uncanny-learndash-groups' ) }
                help={ __( 'Require a first and last name for new students that are added via the Bulk Add & Invite Users tool.', 'uncanny-learndash-groups' ) }
                checked={ props.attributes.firstLastNameRequired }
                onChange={ ( value ) => {
                    props.setAttributes({
                        firstLastNameRequired: value
                    });
                } }
            />

            return (
                <Fragment>
                    <BlockEdit {...props} />
                    <InspectorControls>

                        <PanelBody title={__('Group Management Settings', 'uncanny-learndash-groups')}>

                            <SelectControl
                                label={__('Group Name Selector', 'uncanny-learndash-groups')}
                                value={props.attributes.groupNameSelector}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({groupNameSelector: value});
                                }}
                            />

                            <SelectControl
                                label={__('Group Courses', 'uncanny-learndash-groups')}
                                value={props.attributes.groupCoursesSection}
                                options={ [
                                        { label: 'Show', value: 'show' },
                                        { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({groupCoursesSection: value});
                                }}
                            />
                            <SelectControl
                                label={__('Add Courses Button', 'uncanny-learndash-groups')}
                                value={props.attributes.addCoursesButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({addCoursesButton: value});
                                }}
                            />

                            <SelectControl
                                label={__('Seats Quantity', 'uncanny-learndash-groups')}
                                value={props.attributes.seatsQuantity}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({seatsQuantity: value});
                                }}
                            />

                            <SelectControl
                                label={__('Add Seats Button', 'uncanny-learndash-groups')}
                                value={props.attributes.addSeatsButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({addSeatsButton: value});
                                }}
                            />

                            <SelectControl
                                label={__('Remove User Button', 'uncanny-learndash-groups')}
                                value={props.attributes.removeUserButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({removeUserButton: value});
                                }}
                            />

                            <SelectControl
                                label={__('Upload Users Button', 'uncanny-learndash-groups')}
                                value={props.attributes.uploadUsersButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({uploadUsersButton: value});
                                }}
                            />  

                            <SelectControl
                                label={__('Download Keys Button', 'uncanny-learndash-groups')}
                                value={props.attributes.downloadKeysButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({downloadKeysButton: value});
                                }}
                            />

                            <SelectControl
                                label={__('Progress Report Button', 'uncanny-learndash-groups')}
                                value={props.attributes.progressReportButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({progressReportButton: value});
                                }}
                            />

                            <SelectControl
                                label={__('Quiz Report Button', 'uncanny-learndash-groups')}
                                value={props.attributes.quizReportButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({quizReportButton: value});
                                }}
                            />
                            <SelectControl
                                label={__('Key Column', 'uncanny-learndash-groups')}
                                value={props.attributes.keyColumn}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({keyColumn: value});
                                }}
                            />

                            <SelectControl
                                label={__('Group Leader Section', 'uncanny-learndash-groups')}
                                value={props.attributes.groupLeaderSection}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({groupLeaderSection: value});
                                }}
                            />

                            <SelectControl
                                label={__('Add Group Leader Button', 'uncanny-learndash-groups')}
                                value={props.attributes.addGroupLeaderButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({addGroupLeaderButton: value});
                                }}
                            />

                            <SelectControl
                                label={__('Key Options', 'uncanny-learndash-groups')}
                                value={props.attributes.keyOptions}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({keyOptions: value});
                                }}
                            />

                            <SelectControl
                                label={__('Group Email Button', 'uncanny-learndash-groups')}
                                value={props.attributes.groupEmailEutton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({groupEmailEutton: value});
                                }}
                            />

                            <SelectControl
                                label={__('CSV Export Button', 'uncanny-learndash-groups')}
                                value={props.attributes.csvExportButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({csvExportButton: value});
                                }}
                            />

                            <SelectControl
                                label={__('Excel export button', 'uncanny-learndash-groups')}
                                value={props.attributes.excelExportButton}
                                options={ [
                                    { label: 'Show', value: 'show' },
                                    { label: 'Hide', value: 'hide' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({excelExportButton: value});
                                }}
                            />
                        </PanelBody>

                        <PanelBody title={ __( 'Enrolled users', 'uncanny-learndash-groups' )}>

                            <ToggleControl
								label={ __( 'Show "Add user" button', 'uncanny-learndash-groups' ) }
								checked={ props.attributes.addUserButton == 'show' }
								onChange={ ( value ) => {
									props.setAttributes({
										addUserButton: value ? 'show' : 'hide'
									});
								} }
							/>

                            { ( props.attributes.addUserButton == 'show' ) ? firstLastNameRequired : '' }

                            <TextControl
                                label={ __( 'Page length', 'uncanny-learndash-groups' )}
                                value={ props.attributes.enrolledUsersPageLength }
                                help={ __( 'Number of rows to display on a single page when the page loads.', 'uncanny-learndash-groups' ) }
                                onChange={(value) => {
                                    // Strip all non-numeric characters
                                    value = value.replace( /[^\d-]/g, '' );

                                    // Check if it has just a dash. In that case, don't do anything yet
                                    if ( value != '-' ){
                                        // Parse the value as a number
                                        value = parseInt( value );

                                        // Check if it's a valid number
                                        if ( ! isNaN( value ) ){
                                            // Check if it's not bigger than -1
                                            if ( value < -1 ){
                                                value = -1;
                                            }
                                        }
                                        else {
                                            // Remove the value
                                            value = '';
                                        }
                                    }

                                    props.setAttributes({
                                        enrolledUsersPageLength: value.toString()
                                    });
                                }}
                            />

                            <TextareaControl
                                label={ __( 'Length menu', 'uncanny-learndash-groups' )}
                                value={ props.attributes.enrolledUsersLengthMenu }
                                help={ `
                                    ${ __( 'Entries used in the length drop down select list. Enter each choice on a new line. For more control, you may specify both a value and label like this:', 'uncanny-learndash-groups' ) }

                                    ${ __( '-1 : All', 'uncanny-learndash-groups' ) }
                                `}
                                onChange={(value) => {
                                    props.setAttributes({
                                        enrolledUsersLengthMenu: value
                                    });
                                }}
                            />

                        </PanelBody>

                        <PanelBody title={ __( 'Group leaders', 'uncanny-learndash-groups' )}>

                            <TextControl
                                label={ __( 'Page length', 'uncanny-learndash-groups' )}
                                value={ props.attributes.groupLeadersPageLength }
                                help={ __( 'Number of rows to display on a single page when the page loads.', 'uncanny-learndash-groups' ) }
                                onChange={(value) => {
                                    // Strip all non-numeric characters
                                    value = value.replace( /[^\d-]/g, '' );

                                    // Check if it has just a dash. In that case, don't do anything yet
                                    if ( value != '-' ){
                                        // Parse the value as a number
                                        value = parseInt( value );

                                        // Check if it's a valid number
                                        if ( ! isNaN( value ) ){
                                            // Check if it's not bigger than -1
                                            if ( value < -1 ){
                                                value = -1;
                                            }
                                        }
                                        else {
                                            // Remove the value
                                            value = '';
                                        }
                                    }

                                    props.setAttributes({
                                        groupLeadersPageLength: value.toString()
                                    });
                                }}
                            />

                            <TextareaControl
                                label={ __( 'Length menu', 'uncanny-learndash-groups' )}
                                value={ props.attributes.groupLeadersLengthMenu }
                                help={ `
                                    ${ __( 'Entries used in the length drop down select list. Enter each choice on a new line. For more control, you may specify both a value and label like this:', 'uncanny-learndash-groups' ) }

                                    ${ __( '-1 : All', 'uncanny-learndash-groups' ) }
                                `}
                                onChange={(value) => {
                                    props.setAttributes({
                                        groupLeadersLengthMenu: value
                                    });
                                }}
                            />

                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit {...props} />;
    };
}, 'addGroupsUOSettings');

addFilter('editor.BlockEdit', 'uncanny-learndash-groups/uo-groups', addGroupsUOSettings);

export const addGroupsUrlUOSettings = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        // Check if we have to do something
        if (props.name == 'uncanny-learndash-groups/uo-groups-url' && props.isSelected) {
            return (
                <Fragment>
                    <BlockEdit {...props} />
                    <InspectorControls>

                        <PanelBody title={__('Group Management Link Settings', 'uncanny-learndash-groups')}>

                            <TextControl
                                label={ __( 'Link Text', 'uncanny-learndash-groups' ) }
                                value={ props.attributes.text }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
                                        text: value
                                    });
                                }}
                            />
                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit {...props} />;
    };
}, 'addGroupsUrlUOSettings');

addFilter('editor.BlockEdit', 'uncanny-learndash-groups/uo-groups-url', addGroupsUrlUOSettings);
