jQuery(document).ready(function($) {
	jQuery(".gwslau-closebtn").click(function(){
		jQuery(".gwslau-logout-box-inner").css("display", "none");
		jQuery(".gwslau-logout-user-icon").css("display", "flex");
	});

	jQuery(".gwslau-logout-user-icon").click(function(){
		jQuery(".gwslau-logout-user-icon").css("display", "none");
		jQuery(".gwslau-logout-box-inner").css("display", "block");
	});

	$("#gwslau_logout_btn_logout_box").on("click", function(e) {
		e.preventDefault();
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'post',
			data: {
				'action': 'gwslau_login_return_admin'
			},
			success: function(response) {
				if (localStorage.getItem('gwslau_admin_as_back_to') != '') {
					var gwslau_admin_as_back_to = localStorage.getItem('gwslau_admin_as_back_to').replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");
					window.location.replace(gwslau_admin_as_back_to);
				} else {
					window.location.replace(ajax_object.home_url);
				}
			},
		});
	});
});