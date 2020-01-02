/*!
 * Socialite v1.0
 * http://socialitejs.com
 * Copyright (c) 2011 David Bushell
 * Dual-licensed under the BSD or MIT licenses: http://socialitejs.com/license.txt
 */
;window.Socialite=(function(i,j,e){var f={},c={},l={},h={},b={},g={},a={},k=i.setTimeout,m=encodeURIComponent,d=typeof j.getElementsByClassName==="function";c.appendScript=function(n,q,p){if(h[n]||b[n]===e){return false}var o=h[n]=j.createElement("script");o.async=true;o.src=b[n];o.onload=o.onreadystatechange=function(){if(c.hasLoaded(n)){return}var r=o.readyState;if(!r||r==="loaded"||r==="complete"){g[n]=true;o.onload=o.onreadystatechange=null;if(p!==e){if(typeof p==="function"){p()}}else{c.activateCache(n)}}};if(q){o.id=q}j.body.appendChild(o);return true};c.hasLoaded=function(n){return(typeof n!=="string")?false:g[n]===true};c.removeScript=function(n){if(!c.hasLoaded(n)){return false}j.body.removeChild(h[n]);h[n]=g[n]=false;return true};c.createIframe=function(p,n){var o=j.createElement("iframe");o.style.cssText="overflow: hidden; border: none;";o.setAttribute("allowtransparency","true");o.setAttribute("frameborder","0");o.setAttribute("scrolling","no");o.setAttribute("src",p);if(n!==e){o.onload=o.onreadystatechange=function(){var q=o.readyState;if(!q||q==="loaded"||q==="complete"){o.onload=o.onreadystatechange=null;c.activateInstance(n)}}}return o};c.activateInstance=function(n){if(n.loaded){return}n.loaded=true;n.container.className+=" socialite-loaded"};c.activateCache=function(o){if(a[o]!==e){for(var n=0;n<a[o].length;n++){c.activateInstance(a[o][n])}}};c.copyDataAttributes=function(s,r){var p,n=s.attributes;for(p=0;p<n.length;p++){var o=n[p].name,q=n[p].value;if(o.indexOf("data-")===0&&q.length){r.setAttribute(o,q)}}};c.getDataAttributes=function(t,v,n){var q,s="",p={},r=t.attributes;for(q=0;q<r.length;q++){var u=r[q].name,o=r[q].value;if(u.indexOf("data-")===0&&o.length){if(v===true){u=u.substring(5)}if(n){p[u]=o}else{s+=m(u)+"="+m(o)+"&"}}}return n?p:s};c.getElements=function(s,q){if(d){return s.getElementsByClassName(q)}var r=0,o=[],t=s.getElementsByTagName("*"),n=t.length;for(r=0;r<n;r++){var p=" "+t[r].className+" ";if(p.indexOf(" "+q+" ")!==-1){o.push(t[r])}}return o};f.activate=function(o,n){f.load(null,o,n)};f.load=function(p,r,s){p=(typeof p==="object"&&p!==null&&p.nodeType===1)?p:j;if(r===e||r===null){var y=c.getElements(p,"socialite"),o=y,q=y.length;if(!q){return}if(typeof o.item!==e){o=[];for(var w=0;w<q;w++){o[w]=y[w]}}f.load(p,o,s);return}if(typeof r==="object"&&r.length){for(var v=0;v<r.length;v++){f.load(p,r[v],s)}return}if(typeof r!=="object"||r.nodeType!==1){return}if(typeof s!=="string"||l[s]===e){s=null;var t=r.className.split(" ");for(var u=0;u<t.length;u++){if(l[t[u]]!==e){s=t[u];break}}if(typeof s!=="string"){return}}if(typeof l[s]==="string"){s=l[s]}if(typeof l[s]!=="function"){return}var n=j.createElement("div"),x=j.createElement("div");n.className="socialised "+s;x.className="socialite-button";var z=r.parentNode;if(z===null){z=(p===j)?j.body:p;z.appendChild(n)}else{z.insertBefore(n,r)}n.appendChild(x);x.appendChild(r);r.className=r.className.replace(/\bsocialite\b/,"");if(a[s]===e){a[s]=[]}var A={elem:r,button:x,container:n,parent:z,loaded:false};a[s].push(A);l[s](A,c)};f.extend=function(o,q,p){if(typeof o!=="string"||typeof q!=="function"){return false}o=(o.indexOf(" ")>0)?o.split(" "):[o];if(l[o[0]]!==e){return false}for(var n=1;n<o.length;n++){l[o[n]]=o[0]}if(p!==e&&typeof p==="string"){b[o[0]]=p}l[o[0]]=q;return true};return f})(window,window.document);(function(c,a,b,d){b.extend("googleplus",function(e,h){var f=e.elem,g=a.createElement("div");g.className="g-plusone";h.copyDataAttributes(f,g);e.button.replaceChild(g,f);if(typeof c.gapi==="object"&&typeof c.gapi.plusone==="object"&&typeof gapi.plusone.render==="function"){c.gapi.plusone.render(e.button,h.getDataAttributes(g,true,true));h.activateInstance(e)}else{if(!h.hasLoaded("googleplus")){h.appendScript("googleplus")}}},"//apis.google.com/js/plusone.js");b.extend("facebook",function(e,j){var f=e.elem,h=a.createElement("div"),i=a.getElementById("fb-root");if(!i&&!j.hasLoaded("facebook")){i=a.createElement("div");i.id="fb-root";a.body.appendChild(i);h.className="fb-like";j.copyDataAttributes(f,h);e.button.replaceChild(h,f);j.appendScript("facebook","facebook-jssdk")}else{var k="//www.facebook.com/plugins/like.php?";k+=j.getDataAttributes(f,true);var g=j.createIframe(k,e);e.button.replaceChild(g,f)}},"//connect.facebook.net/en_US/all.js#xfbml=1")})(window,window.document,window.Socialite);

/* ==========================================================
 * fsb.js v1.0.0
 * ==========================================================
 * Copyright 2013 WPBeginner.
 *
 * Licensed under the GPL License, Version 2.0 or later (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
(function($){
	$(function(){
		// Run our ajax request to handle updating the stats counters if needed.
		$(document.body).find('.fsb-social-bar').each(function(){
			// Prepare the data variable.
			var data = {
				action: 'fsb_load_stats',
				postid: $(this).data('post-id'),
			},
				services = {},
				$this 	 = $(this);

			// Add in all the services attached to the social bar.
			$(this).find('div a').each(function(i, el){
				services[i] = $(this).data('fsb-service');
			});

			// Add in the services to the data variable.
			data.services = services;

			// Make the AJAX request to update the stats.
			$.post(fsb.ajax, data, function(res){
				// If the response is empty, do nothing.
				if ( res.length === 0 ) return;

				// Loop through each response and update the visible counter and styling.
				$.each(res, function(k, v){
					// Pass over any items that still do not have any shares.
					if ( 0 === parseInt(v) ) return;

					// Update the visible share count and animate the class removal.
					$this.find('[data-service="' + k + '"] .fsb-count').text(v).parent().parent().removeClass('fsb-hide-count');
				});
			}, 'json');
		});

	    // Load Socialite when hovering over the social bar.
	    $('.fsb-social-bar').one('mouseenter', function(e){
	    	// Only load Socialite if the user has not defined a variable to prevent it.
	        if ( true === $(this).data('socialite') ) {
		    	Socialite.load($(this));
		    	$('.fsb-share-facebook, .fsb-share-google').removeClass('fsb-hide-count');
		    }
	    });

	    // Declare variables.
	    var fsb_top = $('.fsb-social-bar').offset().top;

	    // Attach to the scroll event to determine when to load items.
        $(window).scroll(function(){
            var y    = $(this).scrollTop(),
                maxY = $('#respond, #disqus_thread, #livefyre, .fb-comments, #comment-form, .idc-new, #comment-respond, #comments, #comment, #footer, .site-footer'), // Attempt to target as many comment systems as possible.
                nofo = false; // Flag if no div is found in the maxY var.

			// If for some reason one of our divs does not exist or it's height is zero, use the parent div as a scroll helper and set the nofo var to true.
			if ( $(maxY).eq(0).length === 0 || 0 === $(maxY).height() ) {
				maxY = $('.fsb-social-bar').parent();
				nofo = true;
			}

            // If we should not float or the height of the maxY var is equal to zero, don't do anything.
            if ( $('.fsb-social-bar').hasClass('fsb-no-float') || 0 === $(maxY).height() )
                return;

            // Get the offset of our respond helper.
            var offset = ( true === nofo ) ? fsb_top + $(maxY).height() + 50 : $(maxY).eq(0).offset().top;

            // If we are below the bar but above comments, set a fixed position.
            if ( y > fsb_top && y < offset )
              $('.fsb-social-bar').addClass('fsb-fixed').css('width', $('.fsb-social-bar').parent().width()).fadeIn();
            else if ( y >= offset )
              $('.fsb-social-bar').removeClass('fsb-fixed').css('display', 'none');
            else if ( y < fsb_top )
              $('.fsb-social-bar').show().removeClass('fsb-fixed');
        });

        // Open up the social services in a popup window.
        $('.fsb-facebook, .fsb-twitter, .fsb-google, .fsb-linkedin, .fsb-pinterest').on('click', function(e){
        	e.preventDefault();
        	switch ( $(this).data('fsb-service') ) {
				case 'facebook'  : window.open( $(this).attr('href'), 'fsbfacebook', 'menubar=1,resizable=1,width=600,height=400' ); return;
				case 'twitter' 	 : window.open( $(this).attr('href'), 'fsbtwitter', 'menubar=1,resizable=1,width=600,height=350' ); return;
				case 'google' 	 : window.open( $(this).attr('href'), 'fsbgoogle', 'menubar=1,resizable=1,width=600,height=600' ); return;
				case 'linkedin'  : window.open( $(this).attr('href'), 'fsblinkedin', 'menubar=1,resizable=1,width=580,height=450' ); return;
				case 'pinterest' : window.open( $(this).attr('href'), 'fsbpinterest', 'menubar=1,resizable=1,width=760,height=360' ); return;
        	}
        });
	});
}(jQuery));