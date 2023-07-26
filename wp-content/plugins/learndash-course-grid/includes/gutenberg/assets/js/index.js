!function(){"use strict";var e={n:function(t){var r=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(r,{a:r}),r},d:function(t,r){for(var a in r)e.o(r,a)&&!e.o(t,a)&&Object.defineProperty(t,a,{enumerable:!0,get:r[a]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,r=window.wp.i18n,a=window.wp.blocks,o=window.wp.blockEditor,l=window.wp.components,n=window.wp.serverSideRender,s=e.n(n);class i extends t.Component{constructor(e){super(e)}render(){const{context:e,course_grid_id:a,search:o,taxonomies:n,price:s,price_min:i,price_max:d,setAttributes:c}=this.props;let _="search",u="taxonomies",h="price",g="price_min",m="price_max";"page"==e&&(_="filter_search",u="filter_taxonomies",h="filter_price",g="filter_price_min",m="filter_price_max");const p=LearnDash_Course_Grid_Block_Editor.taxonomies;return(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Filter","learndash-course-grid"),initialOpen:"page"!=e},"widget"==e&&(0,t.createElement)(l.TextControl,{label:(0,r.__)("Course Grid ID","learndash-course-grid"),help:(0,r.__)("Course grid ID the filter is for.","learndash-course-grid"),value:a||"",type:"text",onChange:e=>c({course_grid_id:e})}),(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Search","learndash-course-grid"),checked:o,onChange:e=>{c({[_]:e})}}),(0,t.createElement)(l.BaseControl,null,(0,t.createElement)(l.SelectControl,{multiple:!0,label:(0,r.__)("Taxonomies","learndash-course-grid"),help:(0,r.__)("Hold ctrl on Windows or cmd on Mac to select multiple values.","learndash-course-grid"),options:p,value:n||[],onChange:e=>{c({[u]:e})}})),(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Price","learndash-course-grid"),checked:s,onChange:e=>{c({[h]:e})}}),(0,t.createElement)(l.BaseControl,null,(0,t.createElement)(l.TextControl,{label:(0,r.__)("Price Min","learndash-course-grid"),className:"left",value:i||0,type:"number",onChange:e=>{c({[g]:e})}}),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Price Max","learndash-course-grid"),className:"right",value:d||0,type:"number",onChange:e=>{c({[m]:e})}}),(0,t.createElement)("div",{style:{clear:"both"}})))}}var d=i;class c extends t.Component{constructor(e){super(e)}render(){const{name:e,value:a,label:o,display_state:n,setAttributes:s}=this.props;return(0,t.createElement)(l.BaseControl,{className:void 0===n[e]||n[e]?"show color-picker":"hide color-picker",label:o},(0,t.createElement)("div",{className:"color-wrapper"},(0,t.createElement)(l.ColorPalette,{colors:[],value:a||"",onChange:t=>{s({[e]:t})},clearable:!1}),(0,t.createElement)(l.Button,{className:"clear-button",variant:"tertiary",onClick:()=>{s({[e]:null})}},(0,r.__)("Clear","learndash-course-grid")),(0,t.createElement)("div",{className:"clear"})))}}var _=c,u=window.wp.data;function h(){return"ld-cg-"+(Date.now().toString(36)+Math.random().toString(36).substr(2)).substr(0,"10")}(0,a.registerBlockType)("learndash/ld-course-grid",{title:(0,r.__)("LearnDash Course Grid","learndash-course-grid"),description:(0,r.__)("Build LearnDash course grid easily.","learndash-course-grid"),icon:"grid-view",category:"learndash-blocks",supports:{customClassName:!0},attributes:{post_type:{type:"string",default:LearnDash_Course_Grid_Block_Editor.is_learndash_active?"sfwd-courses":"post"},per_page:{type:"string",default:9},orderby:{type:"string",default:"ID"},order:{type:"string",default:"DESC"},taxonomies:{type:"string",default:""},enrollment_status:{type:"string",default:""},progress_status:{type:"string",default:""},thumbnail:{type:"boolean",default:1},thumbnail_size:{type:"string",default:"course-thumbnail"},ribbon:{type:"boolean",default:1},content:{type:"boolean",default:1},title:{type:"boolean",default:1},title_clickable:{type:"boolean",default:1},description:{type:"boolean",default:1},description_char_max:{type:"string",default:120},post_meta:{type:"boolean",default:1},button:{type:"boolean",default:1},pagination:{type:"string",default:"button"},grid_height_equal:{type:"boolean",default:0},progress_bar:{type:"boolean",default:0},filter:{type:"boolean",default:1},skin:{type:"string",default:"grid"},card:{type:"string",default:"grid-1"},columns:{type:"string",default:3},min_column_width:{type:"string",default:250},items_per_row:{type:"string",default:5},font_family_title:{type:"string"},font_family_description:{type:"string",default:""},font_size_title:{type:"string",default:""},font_size_description:{type:"string",default:""},font_color_title:{type:"string",default:""},font_color_description:{type:"string",default:""},background_color_title:{type:"string",default:""},background_color_description:{type:"string",default:""},background_color_ribbon:{type:"string",default:""},font_color_ribbon:{type:"string",default:""},background_color_icon:{type:"string",default:""},font_color_icon:{type:"string",default:""},background_color_button:{type:"string",default:""},font_color_button:{type:"string",default:""},id:{type:"string",default:""},preview_show:{type:"boolean",default:1},display_state:{type:"object",default:{}},filter_search:{type:"boolean",default:1},filter_taxonomies:{type:"array",default:["category","post_tag"]},filter_price:{type:"boolean",default:1},filter_price_min:{type:"string",default:0},filter_price_max:{type:"string",default:1e3}},edit:e=>{const{attributes:{post_type:a,per_page:n,orderby:i,order:c,taxonomies:g,enrollment_status:m,progress_status:p,thumbnail:b,thumbnail_size:f,ribbon:y,content:C,title:E,title_clickable:v,description:w,description_char_max:k,post_meta:x,button:O,pagination:N,grid_height_equal:B,progress_bar:T,filter:P,skin:D,card:S,columns:A,min_column_width:F,items_per_row:z,font_family_title:G,font_family_description:I,font_size_title:L,font_size_description:j,font_color_title:M,font_color_description:q,background_color_title:H,background_color_description:R,background_color_ribbon:W,font_color_ribbon:Q,background_color_icon:U,font_color_icon:J,background_color_button:K,font_color_button:V,id:X,display_state:Y,preview_show:Z,filter_search:$,filter_taxonomies:ee,filter_price:te,filter_price_min:re,filter_price_max:ae},className:oe,clientId:le,setAttributes:ne}=e;if(X&&""!==X){if(function(e,t){return(0,u.select)("core/block-editor").getClientIdsWithDescendants().some((r=>{const{id:a}=(0,u.select)("core/block-editor").getBlockAttributes(r);return t!==r&&e===a}))}(X,le)){const e=h();ne({id:e})}}else{const e=h();ne({id:e})}const se=LearnDash_Course_Grid_Block_Editor.post_types,ie=LearnDash_Course_Grid_Block_Editor.paginations,de=LearnDash_Course_Grid_Block_Editor.skins,ce=LearnDash_Course_Grid_Block_Editor.cards,_e=[],ue={};for(const e in de)if(Object.hasOwnProperty.call(de,e)){const t={label:de[e].label,value:de[e].slug};_e.push(t),Object.hasOwnProperty.call(de[e],"disable")&&(ue[de[e].slug]=de[e].disable)}const he=[],ge=[],me={},pe={};for(const e in ce)if(Object.hasOwnProperty.call(ce,e)&&(Object.hasOwnProperty.call(ce[e],"disable")&&(pe[ce[e]]=ce[e].disable),Object.hasOwnProperty.call(ce[e],"skins")&&ce[e].skins.forEach((function(t){me[t]=me[t]||[],me[t].push(e)})),void 0!==ce[e].skins&&ce[e].skins.indexOf(D)>-1)){const t={label:ce[e].label,value:e};he.push(t),ge.push(e)}const be=LearnDash_Course_Grid_Block_Editor.image_sizes,fe=LearnDash_Course_Grid_Block_Editor.orderby,ye=[{label:(0,r.__)("Ascending","learndash-course-grid"),value:"ASC"},{label:(0,r.__)("Descending","learndash-course-grid"),value:"DESC"}],Ce=[{value:"",label:(0,r.__)("All","learndash-course-grid")},{value:"enrolled",label:(0,r.__)("Enrolled","learndash-course-grid")},{value:"not-enrolled",label:(0,r.__)("Not Enrolled","learndash-course-grid")}],Ee=[{value:"",label:(0,r.__)("All","learndash-course-grid")},{value:"completed",label:(0,r.__)("Completed","learndash-course-grid")},{value:"in_progress",label:(0,r.__)("In Progress","learndash-course-grid")},{value:"not_started",label:(0,r.__)("Not Started","learndash-course-grid")}];we(e);const ve=(0,t.createElement)(t.Fragment,{key:"learndash-course-grid-settings"},(0,t.createElement)(o.InspectorControls,{key:"controls"},(0,t.createElement)(l.Panel,{className:"learndash-course-grid-panel"},(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Template","learndash-course-grid"),initialOpen:!0},(0,t.createElement)(l.BaseControl,{className:void 0===Y.skin||Y.skin?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Skin","learndash-course-grid"),options:_e,value:D||"",onChange:t=>{ne({skin:t}),we(e)}})),(0,t.createElement)(l.BaseControl,{className:void 0===Y.card||Y.card?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Card","learndash-course-grid"),options:he,value:S||"",onChange:e=>{ne({card:e})}})),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Columns","learndash-course-grid"),value:A||"",type:"number",onChange:e=>ne({columns:e}),className:void 0===Y.columns||Y.columns?"show":"hide"}),["grid","masonry"].indexOf(D)>-1&&(0,t.createElement)(l.TextControl,{label:(0,r.__)("Min Column Width (in pixel)","learndash-course-grid"),value:F,type:"number",help:(0,r.__)("If column width reach value lower than this, the grid columns number will automatically be adjusted on display.","learndash-course-grid"),onChange:e=>ne({min_column_width:e}),className:void 0===Y.min_column_width||Y.min_column_width?"show":"hide"}),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Items Per Row","learndash-course-grid"),help:(0,r.__)("Number of items per row. Certain skins use this to customize the design.","learndash-course-grid"),value:z||"",type:"number",onChange:e=>ne({items_per_row:e}),className:void 0===Y.items_per_row||Y.items_per_row?"show":"hide"})),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Query","learndash-course-grid"),initialOpen:!1},(0,t.createElement)(l.BaseControl,{className:void 0===Y.post_type||Y.post_type?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Post Type","learndash-course-grid"),options:se,value:a||"",onChange:e=>ne({post_type:e})})),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Posts per page","learndash-course-grid"),help:(0,r.__)("Enter 0 show all items.","learndash-course-grid"),value:n||"",type:"number",onChange:e=>ne({per_page:e}),className:void 0===Y.per_page||Y.per_page?"show":"hide"}),(0,t.createElement)(l.BaseControl,{className:void 0===Y.orderby||Y.orderby?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Order By","learndash-course-grid"),options:fe,value:i||"",onChange:e=>ne({orderby:e})})),(0,t.createElement)(l.BaseControl,{className:void 0===Y.order||Y.order?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Order","learndash-course-grid"),options:ye,value:c||"",onChange:e=>ne({order:e})})),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Taxonomies","learndash-course-grid"),help:(0,r.__)("Format:","learndash-course-grid")+" taxonomy1:term1,term2; taxonomy2:term1,term2;",value:g||"",onChange:e=>ne({taxonomies:e}),className:void 0===Y.taxonomies||Y.taxonomies?"show taxonomies":"hide"}),["sfwd-courses","groups"].indexOf(a)>-1&&(0,t.createElement)(l.BaseControl,{className:void 0===Y.enrollment_status||Y.enrollment_status?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Enrollment Status","learndash-course-grid"),options:Ce,value:m,onChange:e=>ne({enrollment_status:e})})),["sfwd-courses"].indexOf(a)>-1&&"enrolled"==m&&(0,t.createElement)(l.BaseControl,{className:void 0===Y.progress_status||Y.progress_status?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Progress Status","learndash-course-grid"),options:Ee,value:p,onChange:e=>ne({progress_status:e})}))),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Elements","learndash-course-grid"),initialOpen:!1},ce[S].elements.indexOf("thumbnail")>-1&&(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Thumbnail","learndash-course-grid"),checked:b,onChange:e=>ne({thumbnail:e}),className:void 0===Y.thumbnail||Y.thumbnail?"show":"hide"}),ce[S].elements.indexOf("thumbnail")>-1&&b&&(0,t.createElement)(l.BaseControl,{className:void 0===Y.thumbnail_size||Y.thumbnail_size?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Thumbnail Size","learndash-course-grid"),options:be,value:f||"",onChange:e=>ne({thumbnail_size:e})})),ce[S].elements.indexOf("ribbon")>-1&&(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Ribbon","learndash-course-grid"),checked:y,onChange:e=>ne({ribbon:e}),className:void 0===Y.ribbon||Y.ribbon?"show":"hide"}),ce[S].elements.indexOf("content")>-1&&(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Content","learndash-course-grid"),help:(0,r.__)("Content includes elements in the area outside of the thumbnail.","learndash-course-grid"),checked:C,onChange:e=>ne({content:e}),className:void 0===Y.content||Y.content?"show":"hide"}),ce[S].elements.indexOf("title")>-1&&(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Title","learndash-course-grid"),checked:E,onChange:e=>ne({title:e}),className:void 0===Y.title||Y.title?"show":"hide"}),ce[S].elements.indexOf("title")>-1&&E&&(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Clickable Title","learndash-course-grid"),checked:v,onChange:e=>ne({title_clickable:e}),className:void 0===Y.title_clickable||Y.title_clickable?"show":"hide"}),ce[S].elements.indexOf("description")>-1&&(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Description","learndash-course-grid"),checked:w,onChange:e=>ne({description:e}),className:void 0===Y.description||Y.description?"show":"hide"}),ce[S].elements.indexOf("description")>-1&&w&&(0,t.createElement)(l.TextControl,{label:(0,r.__)("Max Description Character Count","learndash-course-grid"),value:k||"",type:"number",onChange:e=>{ne({description_char_max:e})}}),ce[S].elements.indexOf("post_meta")>-1&&(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Meta","learndash-course-grid"),checked:x,onChange:e=>ne({post_meta:e}),className:void 0===Y.post_meta||Y.post_meta?"show":"hide"}),ce[S].elements.indexOf("button")>-1&&(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Button","learndash-course-grid"),checked:O,onChange:e=>ne({button:e}),className:void 0===Y.button||Y.button?"show":"hide"}),(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Progress Bar","learndash-course-grid"),help:(0,r.__)("Available for LearnDash course and group.","learndash-course-grid"),checked:T,onChange:e=>ne({progress_bar:e}),className:void 0===Y.progress_bar||Y.progress_bar?"show":"hide"}),(0,t.createElement)(l.BaseControl,{className:void 0===Y.pagination||Y.pagination?"show":"hide"},(0,t.createElement)(l.SelectControl,{label:(0,r.__)("Pagination","learndash-course-grid"),options:ie,value:N||"",onChange:e=>ne({pagination:e})})),(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Filter","learndash-course-grid"),checked:P,onChange:e=>{ne({filter:e})},className:void 0===Y.filter||Y.filter?"show":"hide"})),P&&(0,t.createElement)(d,{context:"page",course_grid_id:X,search:$,taxonomies:ee,price:te,price_min:re,price_max:ae,setAttributes:ne}),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Styles","learndash-course-grid"),initialOpen:!1},"grid"==D&&(0,t.createElement)("div",{className:"grid-style"},(0,t.createElement)("h3",null,(0,r.__)("Grid","learndash-course-grid")),(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Equal Grid Height","learndash-course-grid"),checked:B,onChange:e=>ne({grid_height_equal:e}),className:void 0===Y.grid_height_equal||Y.grid_height_equal?"show":"hide"})),ce[S].elements.indexOf("title")>-1&&E&&(0,t.createElement)(t.Fragment,{key:"title-styles"},(0,t.createElement)("h3",null,(0,r.__)("Heading","learndash-course-grid")),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Heading Font Family","learndash-course-grid"),value:G||"",onChange:e=>ne({font_family_title:e}),className:void 0===Y.font_family_title||Y.font_family_title?"show":"hide"}),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Heading Font Size","learndash-course-grid"),help:(0,r.__)("Accepts full format, e.g. 18px, 2rem","learndash-course-grid"),value:L||"",onChange:e=>ne({font_size_title:e}),className:void 0===Y.font_size_title||Y.font_size_title?"show":"hide"}),(0,t.createElement)(_,{name:"font_color_title",value:M,label:(0,r.__)("Heading Font Color","learndash-course-grid"),display_state:Y,setAttributes:ne}),(0,t.createElement)(_,{name:"background_color_title",value:H,label:(0,r.__)("Heading Background Color","learndash-course-grid"),display_state:Y,setAttributes:ne})),ce[S].elements.indexOf("description")>-1&&w&&(0,t.createElement)(t.Fragment,{key:"description-styles"},(0,t.createElement)("h3",null,(0,r.__)("Description","learndash-course-grid")),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Description Font Family","learndash-course-grid"),value:I||"",onChange:e=>ne({font_family_description:e}),className:void 0===Y.font_family_description||Y.font_family_description?"show":"hide"}),(0,t.createElement)(l.TextControl,{label:(0,r.__)("Description Font Size","learndash-course-grid"),help:(0,r.__)("Accepts full format, e.g. 18px, 2rem","learndash-course-grid"),value:j||"",onChange:e=>ne({font_size_description:e}),className:void 0===Y.font_size_description||Y.font_size_description?"show":"hide"}),(0,t.createElement)(_,{name:"font_color_description",value:q,label:(0,r.__)("Description Font Color","learndash-course-grid"),display_state:Y,setAttributes:ne}),(0,t.createElement)(_,{name:"background_color_description",value:R,label:(0,r.__)("Description Background Color","learndash-course-grid"),display_state:Y,setAttributes:ne})),(0,t.createElement)("h3",null,(0,r.__)("Elements","learndash-course-grid")),ce[S].elements.indexOf("ribbon")>-1&&y&&(0,t.createElement)(t.Fragment,{key:"ribbon-styles"},(0,t.createElement)(_,{name:"font_color_ribbon",value:Q,label:(0,r.__)("Ribbon Font Color","learndash-course-grid"),display_state:Y,setAttributes:ne}),(0,t.createElement)(_,{name:"background_color_ribbon",value:W,label:(0,r.__)("Ribbon Background Color","learndash-course-grid"),display_state:Y,setAttributes:ne})),ce[S].elements.indexOf("icon")>-1&&(0,t.createElement)(t.Fragment,{key:"icon-styles"},(0,t.createElement)(_,{name:"font_color_icon",value:J,label:(0,r.__)("Icon Color","learndash-course-grid"),display_state:Y,setAttributes:ne}),(0,t.createElement)(_,{name:"background_color_icon",value:U,label:(0,r.__)("Icon Background Color","learndash-course-grid"),display_state:Y,setAttributes:ne})),ce[S].elements.indexOf("button")>-1&&O&&(0,t.createElement)(t.Fragment,{key:"button-styles"},(0,t.createElement)(_,{name:"font_color_button",value:V,label:(0,r.__)("Button Font Color","learndash-course-grid"),display_state:Y,setAttributes:ne}),(0,t.createElement)(_,{name:"background_color_button",value:K,label:(0,r.__)("Button Background Color","learndash-course-grid"),display_state:Y,setAttributes:ne}))),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Preview","learndash-course-grid"),initialOpen:!1},(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Show Preview","learndash-course-grid"),checked:!!Z,onChange:e=>ne({preview_show:e})})))),(0,t.createElement)(o.InspectorAdvancedControls,null,(0,t.createElement)(l.TextControl,{label:(0,r.__)("ID"),help:(0,r.__)("Unique ID for CSS styling purpose.","learndash-course-grid"),value:X||"",onChange:e=>ne({id:e}),className:void 0===Y.id||Y.id?"show":"hide"})));function we(e){const{attributes:t={skin:D,card:S,display_state:Y},setAttributes:r}=e;let a=[];if(void 0!==ue[D]&&(a=ue[D]),LearnDash_Course_Grid_Block_Editor.editor_fields.forEach((e=>{let t=Y;t[e]=!0,r({display_state:t})})),a.forEach((e=>{let t=Y;t[e]=!1,r({display_state:t})})),-1==ge.indexOf(S)&&Object.prototype.hasOwnProperty.call(me,"skin")&&Object.prototype.hasOwnProperty.call(me[D],0)){let e=S;e=me[D][0],r({card:e})}}return[ve,(ke=e.attributes,1==ke.preview_show?(0,t.createElement)(s(),{block:"learndash/ld-course-grid",attributes:ke,key:"learndash/ld-course-grid"}):(0,r.__)("[learndash_course_grid] shortcode output shown here","learndash-course-grid"))];var ke},save:e=>{}}),(0,a.registerBlockType)("learndash/ld-course-grid-filter",{title:(0,r.__)("LearnDash Course Grid Filter","learndash-course-grid"),description:(0,r.__)("LearnDash course grid filter widget.","learndash-course-grid"),icon:"filter",category:"learndash-blocks",supports:{customClassName:!1},attributes:{course_grid_id:{type:"string",default:""},search:{type:"boolean",default:1},taxonomies:{type:"array",default:["category","post_tag"]},price:{type:"boolean",default:1},price_min:{type:"string",default:0},price_max:{type:"string",default:1e3},preview_show:{type:"boolean",default:1}},edit:e=>{const{attributes:{course_grid_id:a,search:n,taxonomies:i,price:c,price_min:_,price_max:u,preview_show:h},setAttributes:g}=e,m=(LearnDash_Course_Grid_Block_Editor.taxonomies,(0,t.createElement)(t.Fragment,{key:"learndash-course-grid-filter-settings"},(0,t.createElement)(o.InspectorControls,{key:"controls"},(0,t.createElement)(l.Panel,{className:"learndash-course-grid-filter-panel"},(0,t.createElement)(d,{context:"widget",course_grid_id:a,search:n,taxonomies:i,price:c,price_min:_,price_max:u,setAttributes:g}),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Preview","learndash-course-grid"),initialOpen:!1},(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Show Preview","learndash-course-grid"),checked:!!h,onChange:e=>g({preview_show:e})}))))));return[m,(p=e.attributes,1==p.preview_show?(0,t.createElement)(s(),{block:"learndash/ld-course-grid-filter",attributes:p,key:"learndash/ld-course-grid-filter"}):(0,r.__)("[learndash_course_grid_filter] shortcode output shown here","learndash-course-grid"))];var p},save:e=>{}})}();