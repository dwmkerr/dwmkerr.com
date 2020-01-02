function bfaContains(a, obj) {
	 for (var i = 0; i < a.length; i++) {
		  if (a[i] === obj) {
				return true;
		  }
	 }
	 return false;
}

// NO submit/reset buttons on info tabs
var noDefaultButtons=["montezuma","editingcss","colorpickersolid","colorpickertransparent","aboutdropdownmenus","usingmenuicons","aboutmaintemplates","addmaintemplate","aboutsubtemplates", "addsubtemplate","exportsettings","importsettings"];
function toggleButtons( activeTab ) {
	if( jQuery.inArray( activeTab, noDefaultButtons ) > -1 ) {
		jQuery('#reset-all, #save-all').css('visibility', 'hidden');
	} else {
		jQuery('#reset-all, #save-all').css('visibility', 'visible');
	}
}
	
	
jQuery(document).ready(function($) {



	$('#contextual-help-link').text('Limited PHP Code');

	// 2nd close button for Limited PHP code
	$(document).on('click', '#slideUpHelpTag', function() {	
		$('#screen-meta').slideUp();
	});
	
	
	/*************************************************
	 *   Ajax default settings 
	 *************************************************/
	$.ajaxSetup({
		url: ajaxurl,
		type: 'post'
	});


	/*************************************************
	 *   Color Pickers 
	 *************************************************/
	$('.hslapicker').each( function() {
		var input = $(this),
		inputID = input.attr('id'),
		mode = inputID.replace('picker-', ''),
		inputVal = input.val();
		
		$('#' + inputID).colorPicker({
			format: mode,
			size: 350,
			colorChange: function(e, ui) {
				$(this).parents('.picker-container').css({
					backgroundColor: ui.color
				});
			}
		});
		$('#' + inputID).colorPicker( 'setColor', inputVal );			
	});
	

	/*************************************************
	 *   Used Colors 
	 *************************************************/
	var used_colors_string = '',
	color_array_length = bfa_used_colors.length;
	for (var i = 0; i < color_array_length; i++) {
		used_colors_string += '<span><i style="background:' + bfa_used_colors[i] + '"></i><input class="code" style="width:70px" type="text" value="' + bfa_used_colors[i] + '"></span>';
	}
	$('#bfa_used_colors').html( used_colors_string );
	 
	 
	/*************************************************
	 *   Show Ajax Loading Gif 
	 *************************************************/
	$(document).on('click', '#save-all', function() {
		$('#ataajaxloading').css( 'display', 'block' );	
	});


	
	/*************************************************
	 *   Open Help Tabs
	 *************************************************/	
	$(document).on('click', '.limitedphpcode', function() {
		$('#contextual-help-link').trigger('click');
		return false;
	});
			

	/*************************************************
	 *   Remember option pages
	 *************************************************/	
	// See if an opened tab was saved in a cookie
	var atacookie=new RegExp("atatabpos=[^;]+", "i"); 
	// Get first option tab, the one on the very top
	var firstchildid = $('#ataadminmenu > ul > li:first-child > ul > li:first-child').attr('id'); 
	// Current tab is either saved tab or first tab
	if( document.cookie.match(atacookie) ) {
		var curTab = document.cookie.match(atacookie)[0].split("=")[1];
	} else {
		var curTab = firstchildid.replace( 'menu-', '' );
	}

	// Close all option pages
	$('.ata-option-pack-container').css('display', 'none');
	
	// Open "curent tab" - either last opened or first in menu list
	$('#option-pack-' + curTab).css('display', 'block');
	
	// Initialize codemirror if any, in opened option page
	setTimeout(function() {
		$('#option-pack-' + curTab).find('.codemirrorarea').trigger('click');					
	}, 50);	
	
	// Remove any highlights from all tabs
	$('#ataadminmenu ul li ul li a').removeClass('tab-highlight');
	$('#ataadminmenu ul li a').removeClass('tab-parent-highlight');
	// Highlight active tab
	$('#ataadminmenu ul li ul li#menu-' + curTab + ' a').addClass('tab-highlight');
	$('#ataadminmenu ul li ul li#menu-' + curTab + ' a').parents('#ataadminmenu > ul > li').children('a').addClass('tab-parent-highlight');
	
	toggleButtons( curTab );

	
	$(document).on('click', '#ataadminmenu ul li ul li', function() {
		var target = $(this).attr('id'),
		targ = target.replace( 'menu-', '' );
		// Close all option pages
		$('.ata-option-pack-container').css('display', 'none');
		// Open clicked tab
		$('#option-pack-' + targ).css('display', 'block');

		// Remove any highlights from all tabs
		$('#ataadminmenu ul li ul li a').removeClass('tab-highlight');
		$('#ataadminmenu ul li a').removeClass('tab-parent-highlight');
		// Highlight active tab
		$('#ataadminmenu ul li ul li#menu-' + targ + ' a').addClass('tab-highlight');
		$('#ataadminmenu ul li ul li#menu-' + targ + ' a').parents('#ataadminmenu > ul > li').children('a').addClass('tab-parent-highlight');
	
		// Update clicked tab in cookie
		document.cookie = 'atatabpos=' + targ + ';path=/';
		
		// Activate codemirrors, :visible to avoid multiple triggers on same textarea
		// (already activated textarea will be invisible)
		$('#option-pack-' + targ).find('.codemirrorarea:visible').trigger('click');

		toggleButtons( targ );
		
		return false;
	});
	
	// Section Tabs
	// Close all
	$('#ataadminmenu > ul > li > ul').css('display', 'none');
	// Open the one with the active tab
	$('#ataadminmenu ul li a.tab-parent-highlight').parent().children('ul').css('display','block');
	
	$(document).on('click', '#ataadminmenu > ul > li > a', function() {
		$('#ataadminmenu > ul > li > ul').css('display', 'none');
		$(this).parent().children('ul').css('display', 'block');
		// Click first sub item
		$(this).next().find('li:first-child').trigger('click');
		return false;
	});
	
	
	/*************************************************
	 *   Import Settings 
	 *************************************************/
	$(document).on('click', '#import_montezuma_button', function() {
		
		var settings = encodeURIComponent( $('#import_montezuma_textarea').val() );
					
		$.ajax({
			data: 'action=bfa_import_settings&settings=' + settings + '&_ajax_nonce=' + bfa_import_settings_nonce,
			success: function(html){ 
				setTimeout(function() {
					window.location.reload();					
				}, 500);
				
				$('body').append('<div id="save-php-message"></div>');
				$('#save-php-message')
					.css({backgroundColor:'#ffffff', fontSize: '18px', border: 'solid 5px #000000', padding: '20px', position:'absolute', top: '200px', left: '300px', zIndex: 10000 })
					.html( html ).fadeIn("fast"); 	
			}
		}); 
		
		return false;
	});


	/*************************************************
	 *   Reset Settings 
	 *************************************************/
	$(document).on('click', '#reset-all', function() {
		if( confirm("Are you sure? This will reset ALL settings.") ) {	
			$.ajax({
				data: 'action=bfa_reset_settings&_ajax_nonce=' + bfa_reset_settings_nonce,
				success: function(html){ 
					setTimeout(function() {
						window.location.reload();					
					}, 500);
					
					$('body').append('<div id="save-php-message"></div>');
					$('#save-php-message')
						.css({backgroundColor:'#ffffff', fontSize: '18px', border: 'solid 5px #000000', padding: '20px', position:'absolute', top: '200px', left: '300px', zIndex: 10000 })
						.html( html ).fadeIn("fast"); 	
				}
			}); 
		}
		return false;
	});


	/*************************************************
	 *   Reset Single Setting 
	 *************************************************/
	$(document).on('click', '.reset-single', function() {
						
		var id_to_be_reset = $(this).attr('id');
		id_to_be_reset = id_to_be_reset.replace( 'reset-single-', '' );
		
		$.ajax({
			data: 'action=bfa_reset_single&id_to_be_reset=' + id_to_be_reset + '&_ajax_nonce=' + bfa_reset_single_nonce,
			success: function(html){ 
				setTimeout(function() {
					window.location.reload();					
				}, 500);
				
				$('body').append('<div id="save-php-message"></div>');
				$('#save-php-message')
					.css({backgroundColor:'#ffffff', fontSize: '18px', border: 'solid 5px #000000', padding: '20px', position:'absolute', top: '200px', left: '300px', zIndex: 10000 })
					.html( html ).fadeIn("fast"); 	
			}
		}); 
		
		return false;
	});
	
	
	/*************************************************
	 *   Add Item 
	 *************************************************/
	$(document).on('click', '.ata-add-item', function() {
		var item_type = $(this).attr('rel'),
		item_name = $(this).parent().find('.item_name').val(),
		copy_of = $(this).parent().find('#make_copy_of').val();
		
		// Since 1.1.2: Avoid empty name
		item_name = $.trim( item_name );
		copy_of = $.trim( copy_of );
		if( item_name == '' ) {
			alert( 'Item name is empty. Please put a name into the text input field' );
			return false;
		}
		
		$.ajax({
			data: 'action=bfa_add_item&item_type=' + item_type + '&item_name=' + item_name + '&copy_of=' + copy_of + '&_ajax_nonce=' + bfa_add_item_nonce,
			success: function(html){ 
				setTimeout(function() {
					window.location.reload();					
				}, 500);
				
				$('body').append('<div id="save-php-message"></div>');
				$('#save-php-message')
					.css({backgroundColor:'#ffffff', fontSize: '18px', border: 'solid 5px #000000', padding: '20px', position:'absolute', top: '200px', left: '300px', zIndex: 10000 })
					.html( html ).fadeIn("fast"); 	
			}
		}); 
		
		return false;
	});

	
	/*************************************************
	 *   Delete Item 
	 *************************************************/
	$(document).on('click', '.ata-delete-item', function() {
		var item = $(this).attr('id'),
		item_to_be_deleted = item.replace('deleteitem-', '');

		if( confirm("Delete '" + item_to_be_deleted + "'?") ) {
			$.ajax({
				data: 'action=bfa_delete_item&item_to_be_deleted=' + item_to_be_deleted + '&_ajax_nonce=' + bfa_delete_item_nonce,
				success: function(html){ 
					setTimeout(function() {
						window.location.reload();					
					}, 500);
					
					$('body').append('<div id="save-php-message"></div>');
					$('#save-php-message')
						.css({backgroundColor:'#ffffff', fontSize: '18px', border: 'solid 5px #000000', padding: '20px', position:'absolute', top: '200px', left: '300px', zIndex: 10000 })
						.html( html ).fadeIn("fast"); 	
				}
			}); 
		}
	
		return false;
	});
	

	// Save group of php files
	$('.wrap').on('click', '.save_php_files', function() {
		var file_group = $(this).attr('id');
		
		$.ajax({
			data: 'action=save_php_files_ajax&file_group=' + file_group + '&_ajax_nonce=' + php_file_nonce,
			success: function(html){ 
				$('body').append('<div id="save-php-message"></div>');
				$('#save-php-message')
					.css({position:'absolute', top: '200px', left: '300px', zIndex: 10000 })
					.html( html ).fadeIn("fast").fadeOut(3000).remove(); 
			}
		}); 
		
		return false;
	});
	

	
	/*************************************************
	 *   Initialize codemirror textareas on click 
	 *************************************************/
	$('.wrap').on('click', '.codemirrorarea', function() {
		var id = $(this).attr('id'),
		codemode = $(this).attr('rel');
		if (typeof codemode !== 'undefined' && codemode !== false) {
			if( codemode == 'css' ) 
				thismode = 'css';
			else 
				thismode = 'application/x-httpd-php';
		} else {
			thismode = 'css';
		}
		currentCodemirror = CodeMirror.fromTextArea( document.getElementById(id), { matchBrackets: true, mode: thismode } );
	});	



	/*************************************************
	 *   Show 2nd submit button on top if something was changed 
	 *************************************************/	
	$(document).on('change', '#' + optionID, function() {
		$('#topsubmit').css('display', 'block');
	});

	
	$(document).on('click', '#topsubmit', function() {
		$('#' + optionID).submit();
	});


	/*************************************************
	 *   Open close more boxes 
	 *************************************************/		
	$(document).on('click', '.showmore', function() {
	  $(this).next().toggle('slow');
	});
	
	
	// Open/close additional info boxes
	$('.addtl-info').each( function() {
		if( $(this).is(':checked') ) {
			$(this).parent().nextAll('span:first').css('display', 'inline-block');
			$(this).parent().parent().css('background', '#eeeeee');
		}
	});
	$(document).on('click', '.addtl-info', function() {
		if( $(this).is(':checked') ) {
			$(this).parent().nextAll('span:first').css('display', 'inline-block');
			$(this).parent().parent().css('background', '#eeeeee');
		} else {
			$(this).parent().nextAll('span:first').css('display', 'none');
			$(this).parent().parent().css('background', 'none');
		}
	});			


	/*************************************************
	 *   Tooltip 
	 *************************************************/
	$(document).on('click', '.clicktip', function(e) {
		$('#clickTipContainer').remove();
		var clickTip = $(this).next('.hidden').html();
		$('body').append('<div title="Click to close" id="clickTipContainer">' + clickTip + '</div>');
		$('#clickTipContainer').css({ 'display': 'block', 'top': e.pageY, 'left': e.pageX });
	});
	$(document).on('click', '#clickTipContainer', function() {
		$(this).remove();
	});			
	
	
}); 
