import './sidebar.js';

import {
    UncannyOwlIconColor
} from '../components/icons';

import {
    ToolkitPlaceholder
} from '../components/editor';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;

if (typeof ultpModules.active != null && ultpModules.active.hasOwnProperty("uncanny_pro_toolkit\\ShowAllCourses")) {

    registerBlockType('uncanny-toolkit-pro/course-grid', {
        title: __('Enhanced Course Grid', 'uncanny-pro-toolkit'),

        description: __('Displays a highly customizable grid of LearnDash courses.', 'uncanny-pro-toolkit'),

        icon: UncannyOwlIconColor,

        category: 'uncanny-learndash-toolkit',

        keywords: [
            __('Uncanny Owl', 'uncanny-pro-toolkit'),
        ],

        supports: {
            html: false
        },

        attributes: {
            category:
                {
                    'type': 'string',
                    'default': 'all',
                },
            ldCategory:
                {
                    'type': 'string',
                    'default': 'all',
                },
            tag:
                {
                    'type': 'string',
                    'default': 'all',
                },
            course_tag:
                {
                    'type': 'string',
                    'default': 'all',
                },
            enrolledOnly:
                {
                    'type': 'string',
                    'default': 'no',
                },
            notEnrolled:
                {
                    'type': 'string',
                    'default': 'no',
                },
            limit:
                {
                    'type': 'string',
                    'default': '4',
                },
            cols:
                {
                    'type': 'string',
                    'default': '4',
                },
            hideViewMore:
                {
                    'type': 'string',
                    'default': 'no',
                },
            hideCredits:
                {
                    'type': 'string',
                    'default': 'no',
                },
            hideDescription:
                {
                    'type': 'string',
                    'default': 'no',
                },
            hideProgress:
                {
                    'type': 'string',
                    'default': 'no',
                },
            more:
                {
                    'type': 'string',
                    'default': '',
                },
            showImage:
                {
                    'type': 'string',
                    'default': 'yes',
                },
            price:
                {
                    'type': 'string',
                    'default': 'yes',
                },
            currency:
                {
                    'type': 'string',
                    'default': '$',
                },
            linkToCourse:
                {
                    'type': 'string',
                    'default': 'yes',
                },
            orderby:
                {
                    'type': 'string',
                    'default': 'title',
                },
            order:
                {
                    'type': 'string',
                    'default': 'ASC',
                },
            defaultSorting:
                {
                    'type': 'string',
                    'default': 'course-progress,enrolled,not-enrolled,coming-soon,completed',
                },
            ignoreDefaultSorting:
                {
                    'type': 'string',
                    'default': 'no',
                }
            ,
            borderHover:
                {
                    'type': 'string',
                    'default': '',
                }
            ,
            viewMoreColor:
                {
                    'type': 'string',
                    'default': '',
                }
            ,
            viewMoreHover:
                {
                    'type': 'string',
                    'default': '',
                }
            ,
            viewMoreTextColor:
                {
                    'type': 'string',
                    'default': '',
                }
            ,
            viewMoreText:
                {
                    'type': 'string',
                    'default': 'View More <i class="fa fa fa-arrow-circle-right"></i>',
                }
            ,
            viewLessText:
                {
                    'type': 'string',
                    'default': 'View Less <i class="fa fa fa-arrow-circle-right"></i>',
                }
            ,
            categoryselector:
                {
                    'type': 'string',
                    'default': 'hide',
                }
            ,
            courseCategoryselector:
                {
                    'type': 'string',
                    'default': 'hide',
                }
            ,
            resumeCourseButton:
                {
                    'type': 'string',
                    'default': 'hide',
                }
            ,
            startCourseButton:
                {
                    'type': 'string',
                    'default': 'hide',
                }
        },

        edit({className, attributes, setAttributes}) {
            return (
                <div className={className}>
                    <ToolkitPlaceholder>
                        {__('Enhanced Course Grid', 'uncanny-pro-toolkit')}
                    </ToolkitPlaceholder>
                </div>
            );
        },

        save({className, attributes}) {
            // We're going to render this block using PHP
            // Return null
            return null;
        },
    });
}
