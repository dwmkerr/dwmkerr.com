var ua = jQuery.browser;

var bfa_google_fonts = [ 'Yanone Kaffeesatz:400,300,200,700', 'Gruppo', 'Droid Sans:normal,bold' ];
/*************************
 *  GOOGLE FONTS
 *************************/
if (typeof bfa_google_fonts === 'undefined') {
	jQuery('body').css('opacity', 1); 
} else {
	WebFontConfig = {
		// google: { families: [ 'Yanone Kaffeesatz:400,300,200,700', 'Gruppo', 'Droid Sans:normal,bold' ] },
		google: { families: bfa_google_fonts },
		fontactive: function(fontFamily, fontDescription) {
			/* Avoids "FOUC" - Flash of unstyled content in Firefox 
			   Set "body { opacity: 0 }" in CSS stylesheet */
			jQuery('body').css('opacity', 1); 
		}
	};
	(function() {
		var wf = document.createElement('script');
		wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
			'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);
	})();
} 


  
jQuery(document).ready(function($) {

	
/*
		// clone image
		jQuery('.post-thumb').each(function(){
			var el = jQuery(this);
			el.css({"position":"absolute"}).wrap("<div class='img_wrapper' style='display: inline-block'>")
			el.wrap("<div class='img_wrapper' style='display: block'>")
			.clone().addClass('img_grayscale')
			.css({"position":"absolute","z-index":"998","opacity":"0"})
			.insertBefore(el)
			
			.queue(function(){
					var el = jQuery(this);
					//el.parent().css({"width":el.width(),"height":el.height()});
					el.parent().css({"width":this.width,"height":this.height});
					el.dequeue();
				});
			
			// this.src = grayscale(this.src);
			
		});
		
		// Fade image 


		jQuery('.hentry').mouseover(function(){
			// $(this).parent().find('img:first').stop().animate({opacity:1}, 300);
			// $(this).find('.post-thumb').parent().find('img:first').stop().animate({opacity:1}, 500);

			jQuery(this).find('.post-thumb').parent().find('img:first').stop().animate({opacity:1}, 1000);
			jQuery(this).find('.post-text a').stop().animate({color:'#0090d3'}, 700);
			jQuery(this).stop().animate({backgroundColor:'#f7f7f7', borderTopColor: '#dddddd', borderLeftColor: '#dddddd', borderRightColor: '#dddddd', borderBottomColor: '#dddddd' }, 400);
			
		}).mouseout(function(){
			jQuery(this).find('.img_grayscale').stop().animate({opacity:0}, 1000)
			.next().stop().animate({opacity:1}, 700);
			jQuery(this).find('.post-text a').stop().animate({color:'#000000'}, 700);
			jQuery(this).stop().animate({backgroundColor:'#ffffff', borderTopColor: '#ffffff', borderLeftColor: '#ffffff', borderRightColor: '#ffffff', borderBottomColor: '#ffffff' }, 300);
		});	
*/
		$('.widget').mouseover(function(){
			$(this).find('a:not(.tagcloud a)').stop().animate({color:'#0090d3'}, 1000);
		}).mouseout(function(){
			$(this).find('a:not(.tagcloud a)').stop().animate({color:'#000000'}, 1000);
		});			


	/*******************************
	 *  SPLIT TITLES
	 ******************************/
	/* Split titles: 2-color titles for site-, post- and widget titles 	*/
	$('#sitetitle a, .hentry h2 a[rel=bookmark], .widget h3 span' ).each( function() {
		var str = $(this).text();
		if( str.indexOf(' ') > 0 ) { var space = ' '; } 
		else { var space = ''; }
		
		var strArray = str.split(space),
		fullLength = strArray.length,
		halfLength = Math.ceil( fullLength / 2 ),
		restLength = fullLength - halfLength,
		newstr = '<span class="firstpart">';
		for( var i = 0; i < halfLength; i++ ) {
			newstr += strArray[i] + space;
		}
		newstr += '</span>' + space;
		for( var i = halfLength; i < fullLength; i++ ) {
			newstr += strArray[i] + space;
		}
		$(this).html( newstr );
	});



	// Add class to menu list items with children
	$('ul.children').parent('li').addClass('menu-parent');
	

	/* Insert <i>'s into various HTML elements, for CSS Sprites
	Using <i> instead of <span> for brevity. Without this extra markup 
	the CSS sprite images cannot be compact and thus have to increase 
	in both file size ("empty" space in an image consumes file size, too) 
	and image dimensions: You'd have to make sure neighbor icons of the icon 
	you want to use don't "lurk through" the background */
	$('.widget ul li, .widget h3, .breadcrumbs ol li, .hentry ul li, li.menu-parent a, .menu > ul > li a, .post-tags, .post-categories, .post-readmore').prepend('<i></i>');


	/*******************************
	 *  EQUAL COLUMNS
	 ******************************/
	/* equal height columns: add the class .ehc 
	to any column to make it same height as 
	neighbor columns	*/
	equalColumns();
	$(window).resize( equalColumns );
	function equalColumns() {
		$('.ehc').each( function() {
			var row = $(this);
			if ( ua.msie && parseInt( ua.version, 10 ) < 8 ) {
				var height = row.outerHeight(); // outerheight for IE < 8
			} else {
				var height = row.height();
			}
			row.find('> div').each( function() { 
				$(this).height( height ); 
			});
		});
	}


	/*******************************
	 *  SMOOTH MENU
	 ******************************/
	$('#menu1 > li').smoothMenu({
		zIndex: 10,
		duration: 700,
		easing: 'easeOutExpo',
		dockId: 'menu1-smooth'
	});
	

	
	/* For IE6/7 */
	/* if ( ua.msie && parseInt( ua.version, 10 ) < 9 ) {  */

		
		// var maxWidth = 350;
		var c = $('#content'),
		p = c.find('.post'),
		bc = p.find('.post-bodycopy'),
		maxWidth = c.width() 
			- ( p.outerWidth() - p.width() )
			- ( bc.outerWidth() - bc.width() );
			

/*
		$('img').each(function(i, e) { 
			var img = $(e),
			iow = img.outerWidth();

			if( iow > maxWidth ) { 
				var iw = img.width(), 
				ih = img.height(), 
				imw = maxWidth 
					- parseInt( img.css('padding-left'), 10 )
					- parseInt( img.css('padding-right'), 10 )
					- parseInt( img.css('border-left-width'), 10 )
					- parseInt( img.css('border-right-width'), 10 ),		
				imh = ( imw / iw * ih );				
				img.width( imw ).height( imh ); 
			}
		});
*/		
		/* needed for IE8, too */
		$('.wp-caption').each( function() { 
			var caption = $(this), 
			captionOuterWidth = caption.outerWidth();
			if( captionOuterWidth > maxWidth ) { 
				var image = caption.find('img'),
				imageMaxWidth = maxWidth - ( captionOuterWidth - caption.width() ),
				imageMaxHeight = ( imageMaxWidth / image.width() * image.height() );
				caption.width( maxWidth );
				image.width( imageMaxWidth ).height( imageMaxHeight );
			}
			// console.log( 'mw: ' + maxWidth + ' cow: ' + captionOuterWidth + ' cw: ' + caption.width() );
		});
		
		
		/* Videos */
		$('embed, iframe').each( function() {
			var video = $(this), 
			videoWidth = video.attr('width');
			if( videoWidth > maxWidth ) {
				videoHeight = video.attr('height'), 
				videoMaxHeight = ( maxWidth / videoWidth * videoHeight );
				video.attr({ width: maxWidth, height: videoMaxHeight });
			}
		});



	/* } */
	
}); 
