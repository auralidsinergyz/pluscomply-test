import '../scss/admin.scss';
import Exporter from './export';

var exporter = new Exporter();
jQuery( document ).ready( function() {
	exporter.enableFilterflag( '#filter' );
	exporter.startExportAll( '.qre-export' );
	exporter.addAllStatisticIds();
});  

exporter.triggerExportLinks();
// on click of navigation buttons.
exporter.triggerExportLinksOnEvents( '#historyNavigation .navigationRight, #historyNavigation .navigationLeft' );
// on change of dropdown in navigation menu.
exporter.triggerExportLinksOnEvents( '.navigationCurrentPage', 'change' );
// on click of "History" tab
exporter.onOpenHistoryTab();
var timer = null;

function checkIstabledataLoaded(){
	timer = setInterval(function() {
		const isLoading = jQuery('#wpProQuiz_loadDataHistory').css("display");
		if(isLoading == "none"){
			exporter.addExportLink();
			clearInterval(timer);
		}
	  }, 1000);
}
jQuery('#filter').on('click',function(){
	checkIstabledataLoaded();
});

jQuery('.wpProQuiz_update').on('click',function(){
	checkIstabledataLoaded();
});
