function tc_table_data_pro_timer(tableData, tableType, ID) {

    if ('coursesOverviewTable' === tableType) {
        for (let data in tableData) {
            tableData[data].avgTimeComplete = dataObject.additionalData.coursesOverview[tableData[data].ID].avgTimeComplete;
            tableData[data].avgTimeSpent = dataObject.additionalData.coursesOverview[tableData[data].ID].avgTimeSpent;
        }
    }

    if ('courseSingleOverviewSummaryTable' === tableType) {
        tableData[0].avgTimeComplete = dataObject.additionalData.coursesOverview[ID].avgTimeComplete;
    }

    return tableData;
}

function tc_table_headings_pro_timer(headings, tableType) {

    if ('coursesOverviewTable' === tableType) {
        headings[9] = {data: 'avgTimeComplete', title: uoPro.ls('Avg Time To Complete')};
        headings[10] = {data: 'avgTimeSpent', title: uoPro.ls('Avg Time Spent')};
    }

    if ('courseSingleOverviewSummaryTable' === tableType) {
        headings[2] = {data: 'avgTimeComplete', title: uoPro.ls('Avg Time To Complete')};
    }

    if ('courseSingleTable' === tableType) {
        headings[7] = {data: 'timeComplete', title: uoPro.ls('Time To Complete')};
        headings[8] = {data: 'timeSpent', title: uoPro.ls('Time Spent')};
    }

    if ('userSingleCoursesOverviewTable' === tableType) {
        headings[6] = {data: 'timeComplete', title: uoPro.ls('Time To Complete')};
        headings[7] = {data: 'timeSpent', title: 'Time Spent'};
    }

    if ('userSingleCourseProgressSummaryTable' === tableType) {
        headings[3] = {data: 'timeComplete', title: uoPro.ls('Time To Complete')};
        headings[4] = {data: 'timeSpent', title: uoPro.ls('Time Spent')};
    }

    return headings;
}

function tc_table_data_pro_timer_drawUserSingleCoursesOverviewTable(rowData, row) {

    if (typeof row !== 'undefined') {
        rowData.timeComplete = row.timeComplete;
        rowData.timeSpent = row.timeSpent;
    } else {
        rowData.timeComplete = '';
        rowData.timeSpent = '';
    }

    return rowData;

}

function tc_table_data_pro_timer_drawcourseSingleTableData(rowData, row, response) {

    if (typeof response !== 'undefined') {
        rowData.timeComplete = response.data[row].timeComplete;
        rowData.timeSpent = response.data[row].timeSpent;
    } else {
        rowData.timeComplete = '';
        rowData.timeSpent = '';
    }

    return rowData;

}

function tc_table_data_pro_timer_userSingleCourseProgressSummaryTable(rowData, row, response) {

    if (typeof response !== 'undefined') {
        rowData.timeComplete = response.data.timeComplete;
        rowData.timeSpent = response.data.timeSpent;
    } else {
        rowData.timeComplete = '';
        rowData.timeSpent = '';
    }

    return rowData;

}

if (typeof wp.hooks !== 'undefined') {

    wp.hooks.addFilter('tc_table_headings', 'tc', tc_table_headings_pro_timer, 10);
    wp.hooks.addFilter('tc_table_data', 'tc', tc_table_data_pro_timer, 10);
    wp.hooks.addFilter('tc_table_data_drawUserSingleCoursesOverviewTable', 'tc', tc_table_data_pro_timer_drawUserSingleCoursesOverviewTable, 10, 2);
    wp.hooks.addFilter('tc_table_data_drawcourseSingleTableData', 'tc', tc_table_data_pro_timer_drawcourseSingleTableData, 10, 3);
    wp.hooks.addFilter('tc_table_data_userSingleCourseProgressSummaryTable', 'tc', tc_table_data_pro_timer_userSingleCourseProgressSummaryTable, 10, 3);
}

let uoPro = {
    ls: function (string) {

        console.log(string);
        console.log(tincannyTimer.localizedStrings);
        console.log(tincannyTimer.localizedStrings[string]);



        if (
            typeof tincannyTimer !== 'undefined' &&
            typeof tincannyTimer.localizedStrings !== 'undefined' &&
            typeof tincannyTimer.localizedStrings[string] !== 'undefined'
        ) {
            string = tincannyTimer.localizedStrings[string];
        }

        return string
    }
}


