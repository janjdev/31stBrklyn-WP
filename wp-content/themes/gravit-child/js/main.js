jQuery(document).ready(function(){



//=================================== MAP =======================================================
function showMap(){
	setTimeout(function(){
	var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyADMbdBO-Q71ujNapAYhNlmh7DDa9FPX4A&callback=initialize"; 
        document.getElementsByTagName("head")[0].appendChild(script);
    }, 500);
}

	var themap = document.getElementById('map');
	var footermap = document.getElementById('footermap');

	jQuery('.directions').click(function(){

	  jQuery(themap).addClass('show_map');

	  showMap();
		});

	jQuery('.mapfooter').click(function(){
		jQuery(footermap).addClass('show_map');
		showMap();
	});

	jQuery('.map').click(function(){
		jQuery(themap).addClass('show_map');
		showMap();
	});

	var close = document.getElementById('close');
	var closefootermap = document.getElementById('footerclose');

	jQuery(close).click(function(){
	  classie.remove(themap, 'show_map');
	});

	jQuery(closefootermap).click(function(){
		classie.remove(footermap, 'show_map');
	});

//------------------- Contact Form-------------------------

	jQuery('.show_form').click(function(){

		jQuery('.gif-vid').addClass('open_form')
		jQuery('#contact_form').addClass('show');
	});
	jQuery('.fa-times-circle-o').click(function(){

		jQuery('#contact_form').removeClass('show');
		jQuery('#contact_form input').val("");
		jQuery('#contact_form textarea').val("");
		jQuery('.gif-vid').removeClass('open_form')
	});
	//---------------------Contact Form Textarea-------------
	var txar
	if(document.getElementById('mesg')){
		txar = document.getElementById('mesg');

		(function(){

			if( txar.value.trim() !== '' ) {

				classie.add( txar.parentNode, 'input--filled' );

				}
			// events:

			txar.addEventListener( 'focus', onInputFocus );

			txar.addEventListener( 'blur', onInputBlur );

		function onInputFocus( ev ) {

			classie.add( ev.target.parentNode, 'input--filled' );
		}

		function onInputBlur( ev ) {

			if( ev.target.value.trim() === '' ) {

				classie.remove( ev.target.parentNode, 'input--filled' );

				}

			}

		}) ();
	//--------------------Set Cursor for Textarea--------------------
			jQuery.fn.selectRange = function(start, end) {
			    if(!end) end = start; 
			    return this.each(function() {
			        if (this.setSelectionRange) {
			            this.focus();
			            this.setSelectionRange(start, end);
			        } else if (this.createTextRange) {
			            var range = this.createTextRange();
			            range.collapse(true);
			            range.moveEnd('character', end);
			            range.moveStart('character', start);
			            range.select();
			        }
			    });
			};
}
//---------------------Search Functions and Links----------------------------
	if(document.getElementById('title')){
		var the_tittle = document.getElementById('title').innerHTML;
		var the_tittle_text = the_tittle.trim();
		var the_links = document.getElementsByClassName('menu-item-object-programming_projects');
		for (var i = the_links.length - 1; i >= 0; i--) {
			var classstring = the_links[i].firstChild.className;
			var categoryclass = classstring.concat('red');
			if(the_links[i].firstChild.innerHTML === the_tittle_text){
				the_links[i].firstChild.className = categoryclass;
			}
		};
	}
	if(jQuery('.search_link a')){
		var search_link = jQuery('.search_link a');
		search_link.click(function(){
			search_link.removeClass('active_result_list');
			jQuery(this).addClass('active_result_list');
		});

		var all = jQuery('.all-results');
		var prog = jQuery('.program-results ');
		var art = jQuery('.artist-results');

		var prog_search = jQuery('.program-search');
		var art_search = jQuery('.artist-search');

		var prog_h2 = jQuery(prog_search.prev());
		var art_h2 = jQuery(art_search.prev());

		
		all.click(function(){
			prog_search.addClass('listShow');
			prog_h2.addClass('listShow');
			art_search.addClass('listShow');
			art_h2.addClass('listShow');
			prog_search.removeClass('listHide');
			prog_h2.removeClass('listHide');
			art_search.removeClass('listHide');
			art_h2.removeClass('listHide');
		});
		prog.click(function(){
			art_search.addClass('listHide');
			art_h2.addClass('listHide');
			art_search.removeClass('listShow');
			art_h2.removeClass('listShow');
			prog_search.removeClass('listHide');
			prog_h2.removeClass('listHide');
			prog_search.addClass('listShow');
			prog_h2.addClass('listShow');
		});
		art.click(function(){
			prog_search.addClass('listHide');
			prog_h2.addClass('listHide');
			prog_search.removeClass('listShow');
			prog_h2.removeClass('listShow');
			art_search.removeClass('listHide');
			art_h2.removeClass('listHide');
			art_search.addClass('listShow');
			art_h2.addClass('listShow');
		})
	}

/*-----------------------------------------------------------------------------------*/
/*	FLEXSLIDER
/*-----------------------------------------------------------------------------------*/
setTimeout(function(){
	jQuery('.flex-control-nav li a').click(function(){
			jQuery('.flex-pauseplay a').addClass('playPause');
		});
		jQuery('.flex-pauseplay a').click(function(){
			jQuery(this).removeClass('playPause');
		});
	}, 700)	;

	jQuery(window).load(function(){
		//About Slider
		jQuery('.flexslider').flexslider({
			animation: "slide",
			direction: 'horizontal',
			controlNav: true,
			directionNav: false,
			animationLoop: true,
			slideshow: true,
			video: true,
			prevText: "",
			nextText: "",
			pausePlay: true,
			playText: ""
		});
	});

	var modal = document.getElementById('myModal');
	var span = document.getElementsByClassName("close")[0];


	setTimeout(function(){
		jQuery(modal).show();
	}, 6000);


	jQuery(span).click(function() {
	    jQuery(modal).hide();
	});
	window.onclick = function(event) {
	    if (event.target == modal) {
	        modal.style.display = "none";
	    }
	}

});