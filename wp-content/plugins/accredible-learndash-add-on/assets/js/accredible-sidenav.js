/**
 * jquery-sidebar v3.3.2
 * http://jillix.github.io/jQuery-sidebar/
 */
(function($){$.fn.sidebar=function(options){var self=this;if(self.length>1){return self.each(function(){$(this).sidebar(options)})}var width=self.outerWidth();var height=self.outerHeight();var settings=$.extend({speed:200,side:"left",isClosed:false,close:true},options);self.on("sidebar:open",function(ev,data){var properties={};properties[settings.side]=0;settings.isClosed=null;self.stop().animate(properties,$.extend({},settings,data).speed,function(){settings.isClosed=false;self.trigger("sidebar:opened")})});self.on("sidebar:close",function(ev,data){var properties={};if(settings.side==="left"||settings.side==="right"){properties[settings.side]=-self.outerWidth()}else{properties[settings.side]=-self.outerHeight()}settings.isClosed=null;self.stop().animate(properties,$.extend({},settings,data).speed,function(){settings.isClosed=true;self.trigger("sidebar:closed")})});self.on("sidebar:toggle",function(ev,data){if(settings.isClosed){self.trigger("sidebar:open",[data])}else{self.trigger("sidebar:close",[data])}});function closeWithNoAnimation(){self.trigger("sidebar:close",[{speed:0}])}if(!settings.isClosed&&settings.close){closeWithNoAnimation()}$(window).on("resize",function(){if(!settings.isClosed){return}closeWithNoAnimation()});self.data("sidebar",settings);return self};$.fn.sidebar.version="3.3.2"})(jQuery);

const accredibleSidenav = {};

jQuery(function(){
    accredibleSidenav.open = function(html, options) {
        if (!html) {
            html = `<div></div>`;
        }

        html = 
        `<div class="accredible-sidenav-overlay"></div>
        <div class="accredible-sidenav">
            <div class="accredible-sidenav-title">${options.title}</div>
            ${html}
        </div>`;

        // append sidebar content to DOM
        jQuery('#wpcontent').append(html);

        const sidenavOverlay = jQuery('.accredible-sidenav-overlay');
        const sidenavRef = jQuery('.accredible-sidenav').sidebar({side: "right"});

        accredibleSidenav.close = function() {
            sidenavOverlay.remove();
            sidenavRef.trigger("sidebar:close");
            // Clear content from DOM
            sidenavRef.on('sidebar:closed', function(){
                sidenavRef.remove();
            });
        }

        sidenavRef.trigger("sidebar:open", {});
    };
});
