(function( $ ) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */


    $(document).ready(function(){
        $('body').append('<div id="ajax_loader_spinner" style="display: none"><div class="ajax_spinner"></div></div>');
    });


    jQuery(function($){

        if( $('.wpProQuiz_certificate > a').attr('href') == 'javascript:;' ){
            $('.wpProQuiz_certificate > a').addClass('ldmc_select_certificate');
        }


        $( '.ldmc_select_certificate' ).on( 'click', ldmc_select_certificate_popup );


        // multiple certificate
        if( $('.wpProQuiz_certificate > a').attr('href') == 'javascript:void(0)' ){
            $('.wpProQuiz_certificate > a').addClass('ldmc_download_multiple_certificates');
        }

         $( '.ldmc_download_multiple_certificates' ).on( 'click', ldmc_download_multiple_certificates_popup );
        // multiple certificate


    });




    // multiple certificate
    
    function ldmc_download_multiple_certificates_popup (e){

        e.preventDefault();
        e.stopImmediatePropagation();
        var download_button = $(this);
        ldmc_get_multiple_certificates_for_downlad( download_button );
       
    }


    function ldmc_get_multiple_certificates_for_downlad(download_button){
    
        console.log(download_button);

        jQuery.ajax({
            type    : 'POST',
            dataType: 'json',
            beforeSend: function() {
                $('#ajax_loader_spinner').show();

            },
            url     : ld_mc_frontend.ajax.certificates.get.url,
            data: {
                action     : ld_mc_frontend.ajax.certificates.get_multiple.action,
                security   : ld_mc_frontend.ajax.certificates.get_multiple.nonce,
                post_id  : ld_mc_frontend.settings.post_id,
            },
            success: function(response){
                $('#ajax_loader_spinner').hide();
                if (response.success) {
                    if( 0 != response.data,ld_mc_frontend.settings.post_id ){;
                        console.log(download_button);
                        ldmc_show_certificates_for_download(download_button, response.data,ld_mc_frontend.settings.post_id);
                    }
                } else if (false === response.success) {
                    Swal.fire(
                        'ERROR!',
                        response.data,
                        'error'
                    );
                }

             //   $(ldmc_overlay).remove();
            },
            error: function(error){
                console.error(error);
            }
        });
    }


    async function ldmc_show_certificates_for_download (download_button, certificates ,post_id ) {
       
        var tds=""; 
        
        $.each( certificates, function( key, value ) {
            tds += "<tr><td><i class='fa-solid fa-award'></i><td><td class='certificate-name'>"+value['text']+"</td><td><a id='repl' class='download-certifcate' href='"+value['url']+"' target='_blank' data-certId='" + key+"' data-postId='" + post_id+"' data-certId='{{$key}}'>Download<i class='fa-solid fa-download'></i></a><td></tr>"; 
    
        });  
        const { value: certificate } = await Swal.fire({
            imageUrl: ld_mc_frontend.images.swal_icon,
            imageHeight: 80,
            title: ld_mc_frontend.strings.swal_title_for_multiple_download, 
            html:`<table class="download-certificate-list"><tbody>${tds}</tbody></table>`,
            // input: 'select',
            inputOptions: certificates,
            // inputValue: ld_mc_frontend.strings.wdm_selected_school,
            inputPlaceholder: ld_mc_frontend.strings.swal_placeholder_for_multiple_download,
            showCancelButton: true,

        });
    }


    // multiple certificate




























    function ldmc_select_certificate_popup (e){

        e.preventDefault();
        e.stopImmediatePropagation();
        var download_button = $(this);
        console.log('download_button');
        console.log(download_button);

        // if( '' == download_button.attr('href') || download_button.attr('href') == '#' || download_button.attr('href') == 'javascript:;' ){
            ldmc_get_certificates( download_button );
        // }


    }

    function ldmc_get_certificates(download_button){
        console.log('ldmc_get_certificates');
        console.log('download_button - before ajax');
        console.log(download_button);




        // var ldmc_overlay = $(document.createElement('div')).addClass('ldmc_overlay');
        // var course_id = jQuery('#ldmc_course_id').val();

        jQuery.ajax({
            type    : 'POST',
            dataType: 'json',
            beforeSend: function() {
                $('#ajax_loader_spinner').show();
                // $(ldmc_overlay).append('<div class="ldmc_overlay__inner"><div class="ldmc_overlay__content"><span class="ldmc_spinner"></span></div></div>');

            },
            url     : ld_mc_frontend.ajax.certificates.get.url,
            data: {
                action     : ld_mc_frontend.ajax.certificates.get.action,
                security   : ld_mc_frontend.ajax.certificates.get.nonce,
                post_id  : ld_mc_frontend.settings.post_id,
            },
            success: function(response){
                $('#ajax_loader_spinner').hide();
                if (response.success) {
                    if( 0 != response.data,ld_mc_frontend.settings.post_id ){
                        console.log('download_button - after ajax');
                        console.log(download_button);
                        ldmc_show_certificates_select(download_button, response.data,ld_mc_frontend.settings.post_id);
                    }
                } else if (false === response.success) {
                    Swal.fire(
                        'ERROR!',
                        response.data,
                        'error'
                    );
                }

             //   $(ldmc_overlay).remove();
            },
            error: function(error){
                console.error(error);
            }
        });
    }

    async function ldmc_show_certificates_select (download_button, certificates ,post_id ) {
        console.log('ldmc_show_certificates_select');
        console.log('download_button');
        console.log(download_button);

        console.log(certificates); 
        var tds=""; 
        
        $.each( certificates, function( key, value ) {
            tds += "<tr><td><i class='fa-solid fa-award'></i><td><td class='certificate-name'>" + value+"</td><td><a  id='reply' class='download-certifcate' data-certId='" + key+"' data-postId='" + post_id+"' data-certId='{{$key}}'>Download<i class='fa-solid fa-download'></i></a><td></tr>"; 
    
        });  
        const { value: certificate } = await Swal.fire({
            imageUrl: ld_mc_frontend.images.swal_icon,
            imageHeight: 80,
            title: ld_mc_frontend.strings.swal_title, 
			html:`<table class="download-certificate-list"><tbody>${tds}</tbody></table>`,
            // input: 'select',
            inputOptions: certificates,
            // inputValue: ld_mc_frontend.strings.wdm_selected_school,
            inputPlaceholder: ld_mc_frontend.strings.swal_placeholder,
            showCancelButton: true,

        });

        if (certificate) {
            ldmc_set_certificate_in_user_meta(download_button, certificate, post_id);
        }
    }
	
	
	(function (jQuery) {
  //   jQuery(document).on("click","#repl",function(e){
  //   //    e.preventDefault(); // do not follow the link or move to top
		// var download_button = $(this);
  //       var certId = $(this).attr('data-certId');
		// var postId = $(this).attr('data-postId');
     			
		//  jQuery.ajax({
  //           type    : 'POST',
  //          dataType: 'text',
  //           url     : ld_mc_frontend.ajax.user_metas.set_certificate.url,
  //           data: {
  //               action         : ld_mc_frontend.ajax.user_metas.set_certificate.action,
  //               security       : ld_mc_frontend.ajax.user_metas.set_certificate.nonce,
  //               certificate_id : certId,
  //               post_id 	   : postId,
  //           },
  //           beforeSend: function() {
  //               $('#ajax_loader_spinner').show();
  //           },
  //           success: function(response){
  //               $('#ajax_loader_spinner').hide();
  //               if (response) {


  //               }
  //           },
  //           error: function(error){
  //               console.error('ldmc_loader ' + error);
  //           }
  //       });	
			
			
			
  //   })


    jQuery(document).on("click","#repl",function(e){
    //    e.preventDefault(); // do not follow the link or move to top
        var download_button = $(this);
        var certId = $(this).attr('data-certId');
        var postId = $(this).attr('data-postId');
                
         jQuery.ajax({
            type    : 'POST',
           dataType: 'text',
            url     : ld_mc_frontend.ajax.user_metas.send_certificate.url,
            data: {
                action         : ld_mc_frontend.ajax.user_metas.send_certificate.action,
                security       : ld_mc_frontend.ajax.user_metas.send_certificate.nonce,
                certificate_id : certId,
                post_id        : postId,
            },
            beforeSend: function() {
                $('#ajax_loader_spinner').show();
            },
            success: function(response){
                $('#ajax_loader_spinner').hide();
                if (response) {
                    

                }
            },
            error: function(error){
                console.error('ldmc_loader ' + error);
            }
        }); 
            
            
            
    })

    
})(jQuery);
	
(function (jQuery) {
    jQuery(document).on("click","#reply",function(e){
        e.preventDefault(); // do not follow the link or move to top
		var download_button = $(this);
        var certId = $(this).attr('data-certId');
		var postId = $(this).attr('data-postId');
     			
		 jQuery.ajax({
            type    : 'POST',
            dataType: 'json',
            url     : ld_mc_frontend.ajax.user_meta.set_certificate.url,
            data: {
                action         : ld_mc_frontend.ajax.user_meta.set_certificate.action,
                security       : ld_mc_frontend.ajax.user_meta.set_certificate.nonce,
                certificate_id : certId,
                post_id 	   : postId,
            },
            beforeSend: function() {
                $('#ajax_loader_spinner').show();
            },
            success: function(response){
                $('#ajax_loader_spinner').hide();
                if (response) {

                    if( ld_mc_frontend.settings.post_type == 'quiz' ){
                        download_button.off("click");
                        download_button.removeClass( 'ldmc_select_certificate' );

                        console.log('download_button');
                        console.log(download_button);
                        console.log('before - '+ download_button.attr('href') );
                        download_button.attr('href', response.data.certificate_link);
                        console.log('after - '+ download_button.attr('href') );
                        download_button.text( ld_mc_frontend.strings.download_certificate_button_text);

				console.log('alert Close - before');
                        $('.wpProQuiz_certificate').html('<a class="btn-blue" href="'+response.data.certificate_link+'" target="_blank">'+ld_mc_frontend.strings.print_certificate_button_text+'</a>');
                        Swal.close();
                        console.log('alert Close - after ');

                    }else{
                        location.reload();
                    }

                }
            },
            error: function(error){
                console.error('ldmc_loader ' + error);
            }
        });	
			
			
			
    })
})(jQuery);
	

    function ldmc_set_certificate_in_user_meta(download_button, certificate, post_id ){
        console.log('ldmc_set_certificate_in_user_meta');
        console.log('download_button - before ajax');
        console.log(download_button);
        jQuery.ajax({
            type    : 'POST',
            dataType: 'json',
            url     : ld_mc_frontend.ajax.user_meta.set_certificate.url,
            data: {
                action         : ld_mc_frontend.ajax.user_meta.set_certificate.action,
                security       : ld_mc_frontend.ajax.user_meta.set_certificate.nonce,
                certificate_id : certificate,
                post_id 	   : post_id,
            },
            beforeSend: function() {
                $('#ajax_loader_spinner').show();
            },
            success: function(response){
                $('#ajax_loader_spinner').hide();
                if (response) {

                    if( ld_mc_frontend.settings.post_type == 'quiz' ){
                        download_button.off("click");
                        download_button.removeClass( 'ldmc_select_certificate' );

                        console.log('download_button');
                        console.log(download_button);
                        console.log('before - '+ download_button.attr('href') );
                        download_button.attr('href', response.data.certificate_link);
                        console.log('after - '+ download_button.attr('href') );
                        download_button.text( ld_mc_frontend.strings.download_certificate_button_text);

                        console.log('alert Close - before');
                        $('.wpProQuiz_certificate').html('<a class="btn-blue" href="'+response.data.certificate_link+'" target="_blank">'+ld_mc_frontend.strings.print_certificate_button_text+'</a>');
                        Swal.close();
                        console.log('alert Close - after ');
                    }else{
                        location.reload();
                    }

                }
            },
            error: function(error){
                console.error('ldmc_loader ' + error);
            }
        });
    }


})( jQuery );
