/*
 notifyMe.js v1.0.0
 Copyright (c)2014 Sergey Serafimovich
*/

(function ( $ ) {
	"use strict";
	
    $.fn.notifyingMe = function( options ) {

        // Default options.
        var settings = $.extend({
        	// Error and success message strings
            msgError404: "Service is not available at the moment. Please check your internet connection or try again later.",
			msgError503: "Oops. Looks like something went wrong. Please try again later.",
			msgErrorValidation: "This email address looks fake or invalid. Please enter a real email address.",
			msgErrorFormat: "Your e-mail address is incorrect. Please check it and try again.",
			msgSuccess: "Thanks!"
		}, options );

    
    	var $this = $(this);
		var input = $("button.submission");
		
		var action = $(this).attr("action");
		var note = $(this).find(".note");
		var message = $("<p class='message'></p>").appendTo($(this));
		var icon = $("<i id='process_icon'></i>")
		var iconProcess = "fa fa-spinner fa-spin"; 
		var iconSuccess = "fa fa-check-circle";
		var iconError = "fa fa-exclamation-circle";

		input.after(icon);
    
		$(this).on("submit", function(e){
			e.preventDefault();
			// Get value of input
			var email = 
			//$(this).find("input[name=email]"),
			document.getElementById("email").value,
				fName = 
				//$(this).find("input[name=fName]"),
				document.getElementById("fName").value,
				lName = 
				//$(this).find("input[name=lName]"),
				document.getElementById("lName").value,
				mesg = 
				//$(this).find("input[name=mesg]");
				document.getElementById("mesg").value;
			var formData = $('form').serialize();
			var data_2;
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    	$.ajax({
                type: "POST",
                url: 'http://31stbrklyn.com/wp-content/themes/gravit-child/google_captcha.php',
                data:formData,
                async:false,
                success: function(data) {
                	
                 if(data.nocaptcha==="true") {
                 	console.log(data.nocaptcha);
               data_2=1;
                  } else if(data.spam==="true") {
                  	console.log(data.spam);
               data_2=1;
                  } else {
               data_2=0;
                  }
                }
            });
            if(data_2!=0) {
              if(data_2==1) {
                sweetAlert("Please check the captcha", "error");
              } else {
                sweetAlert("Please Don't spam", "error");
              }
            } else 
				if(re.test(email)) {
			
				icon.removeClass();
				icon.addClass(iconProcess);
				$(this).removeClass("error success");
				message.text("");
				note.show();
	
				$.ajax({
					type: "POST",
					url: action,
					data: {Email: email, FirstName: fName, LastName: lName, Message: mesg},
					//dataType: "json",
					error: function(response){
						//log response
						console.log(data.status);
						console.log(response)
						// Add error class to form
						$this.addClass("error");
						
						note.hide();
						// Change the icon to error
						icon.removeClass();
						//icon.addClass(iconError);
						
						// Determine the status of response and display the message
						if(data.status == 404) {
							message.text(settings.msgError404);
							$(this).notifyMe(
								'bottom',
								'error',
								'Error 404',
								'Service is not available at the moment. Please check your internet connection or try again later.',
								500,
								3500
								);			
						} else {
							message.text(settings.msgError503);
								$(this).notifyMe(
								'bottom',
								'error',
								'Validation Error',
								'This email address looks fake or invalid. Please enter a real email address.',
								500,
								3500
								);
								
						}
					},
					
				}).done(function(response){
					// Hide note
					note.hide();
				
					if(response == "success") {
						// Add success class to form
						$this.addClass("success");
						// Change the icon to success
						icon.removeClass();
						//icon.addClass(iconSuccess);
						message.text(settings.msgSuccess);
						$(this).notifyMe(
					'top',
					'success',
					'Got It!',
					"Thanks for reaching out! We'll be in touch.",
					500,
					3500
					);
				$('input[name=fName]').val('');
				$('input[name=lName]').val('');	
				$('input[name=email]').val('');
				$('textarea[name=mesg]').val('');
				jQuery('#contact_form').removeClass('show');
				jQuery('.gif-vid').removeClass('open_form');

					} else {
						// Add error class to form
						$this.addClass("error");
						// Change the icon to error
						icon.removeClass();
						//icon.addClass(iconError);

						if (data.type == "ValidationError") {
							message.text(settings.msgErrorValidation);
						} else {
							message.text(settings.msgError503);
							$(this).notifyMe(
								'bottom',
								'error',
								'Validation Error - No',
								'No validation error but something went wrong...',
								200,
								3500
								);	
						}
					}
					
				});
				
			} else {
				// Add error class to form
				$(this).addClass("error");
				// Hide note
				//note.hide();
				// Change the icon to error
				icon.removeClass();
				//icon.addClass(iconError);
				// Display the message
				message.text(settings.msgErrorFormat);
				$(this).notifyMe(
								'bottom',
								'error',
								'Validation Error',
								msgErrorValidation,
								200,
								3500
								);
					}		
			});

   		};
 	}
 ( jQuery ));