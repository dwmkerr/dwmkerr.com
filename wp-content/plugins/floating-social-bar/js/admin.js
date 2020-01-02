/* ==========================================================
 * admin.js v1.0.0
 * http://thomasgriffinmedia.com
 * ==========================================================
 * Copyright 2013 Thomas Griffin.
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
	    // Initialize sortable elements for the available services area.
	    $('#tgm-plugin-settings .available-sharing ul').sortable({
    	    connectWith: '#tgm-plugin-settings .enabled-sharing ul',
    	    items: 'li',
    	    cursor: 'move',
    	    opacity: 0.75,
    	    delay: 100,
    	    forcePlaceholderSize: true,
    	    placeholder: 'dropzone'
	    });

	    // Initialize sortable elements for the enabled services area.
	    $('#tgm-plugin-settings .enabled-sharing ul').sortable({
    	    connectWith: '#tgm-plugin-settings .available-sharing ul',
    	    items: 'li',
    	    cursor: 'move',
    	    opacity: 0.75,
    	    delay: 100,
    	    forcePlaceholderSize: true,
    	    placeholder: 'dropzone',
    	    receive: function(e, ui){
        	    fsb_save_order(e, ui);

        	    // Hide the helper text.
        	    $('#tgm-plugin-settings .enabled-sharing .sharing-text').hide();
    	    },
    	    stop: function(e, ui){
        	    fsb_save_order(e, ui);
        	    $('#tgm-plugin-settings .available-sharing ul').enableSelection();

        	    // If the enabled services holder is empty, show our helper text.
        	    if ( 0 == $('#tgm-plugin-settings .enabled-sharing ul li').length )
        	        $('#tgm-plugin-settings .enabled-sharing .sharing-text').show();
                else
                    $('#tgm-plugin-settings .enabled-sharing .sharing-text').hide();
    	    },
    	    start: function(e, ui){
        	    $('#tgm-plugin-settings .available-sharing ul').disableSelection();
    	    }
	    });

	    // If the enabled services holder is empty, show our helper text.
	    if ( 0 == $('#tgm-plugin-settings .enabled-sharing ul li').length )
	        $('#tgm-plugin-settings .enabled-sharing .sharing-text').show();
        else
            $('#tgm-plugin-settings .enabled-sharing .sharing-text').hide();

        // If the static option is checked, show the position option.
        if ( $('#fsb-static').is(':checked') )
        	$('#fsb-position-box').show();

        // If the static option is checked, show/hide the position option.
        $('#fsb-static').on('change', function(){
	        if ( $(this).is(':checked') )
	        	$('#fsb-position-box').fadeIn(300);
	        else
	        	$('#fsb-position-box').fadeOut(300);
        });

	    // Handle plugin settings updates.
		$('#tgm-plugin-settings-form').on('submit', function(){
	    	// Apply an overlay while processing the save on the server.
		    $('<div class="tgm-plugin-overlay"><div class="tgm-plugin-cover"><h2 class="tgm-plugin-processing">' + fsb.save + '</h2></div></div>').appendTo('body');
			fsb_resize_element(['.tgm-plugin-processing']);
	    });

	    // Helper function to save the order of social bar items.
	    function fsb_save_order(e, ui){
    	    var social_items = {}, data;
    	    $('#tgm-plugin-settings .enabled-sharing li').each(function(i, el){
        	    social_items[i] = $(this).data('service');
    	    });
	        data = {
    	        action: 'fsb_save_order',
    	        items: social_items
	        };
	        $.post(fsb.ajax, data, function(res){}, 'json');
	    }

	    // Helper function to resize elements to fit vertically and horizontally in a div.
	    function fsb_resize_element(elements){
		    $.each(elements, function(i, el){
		    	$(el).css({ top: ($(window).height() - $(el).outerHeight()) / 2, left: ($(window).width() - $(el).outerWidth()) / 2 });
		    	$(window).resize(function(){
			    	$(el).css({ top: ($(window).height() - $(el).outerHeight()) / 2, left: ($(window).width() - $(el).outerWidth()) / 2 });
		    	});
		    });
	    }
	});
}(jQuery));