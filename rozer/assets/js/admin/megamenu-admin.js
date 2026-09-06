(function ( $ ) {
	"use strict";
	$.RtMegamenu   = $.RtMegamenu || {};
	$.RtMegamenu['data']    = {};
	$.RtMegamenu['allow_submit'] = false;

	// Add button show modal setting
	function add_setting_button(){
		
		$("#menu-to-edit").on("mouseenter mouseleave", "li.menu-item", function() {
	        var menu_item = $(this);

	        if (!menu_item.data("megamenu_has_button")) {

	            menu_item.data("megamenu_has_button", "true");

	            $(".item-title", menu_item).append('<span class="rt-setting-btn button-primary">Settings</span>');
	        }
	    });
	}

	function add_menu_data(){
		$( '#menu-to-edit > .menu-item' ).each( function(){
			var _this = $(this);
			var id = parseInt( _this.attr('id').split( 'menu-item-' )[1] );
			var level = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split( ' ' )[0] );
			$.RtMegamenu['data'][id] = get_item_data( id, level );
		})
	}

	function button_expand(){
        var list_menu_item = $( '#menu-to-edit > .menu-item' );
		var has_expand     = false;

		$.each( list_menu_item, function( key, val ){
			var _this      = $(this);
			var level      = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split( ' ' )[0] );
			var el_next    = $( list_menu_item[ key + 1 ] );
			var level_next = level + 1;

			if( el_next.hasClass( 'menu-item-depth-' + level_next ) ) {
				if( ! _this.find( '.menu-item-bar .wr-expand' ).length ) {
					_this.find( '.menu-item-bar' ).append( '<span class="button-expand"></span>' );
				}
			} else {
				_this.find( '.menu-item-bar .wr-expand' ).remove();
			}
		});
	}

    // Add expand all and collapse all
	function button_expand_collapse_all(){

		var dom = '<ul class="expand-collapse"><li class="expand-all">Expand all</li><li class="collapse-all">Collapse all</li></ul>';
		$( dom ).insertBefore( '#menu-to-edit' );


		$( 'body' ).on( 'click', '.expand-collapse .expand-all', function(){
			$( '#menu-to-edit > .menu-item:not(".menu-item-depth-0")' ).show();
			$( '#menu-to-edit > .menu-item .button-expand.collapse' ).removeClass( 'collapse' );
		} );

		$( 'body' ).on( 'click', '.expand-collapse .collapse-all', function(){
			$( '#menu-to-edit > .menu-item:not(".menu-item-depth-0")' ).hide();
			$( '#menu-to-edit > .menu-item .button-expand' ).addClass( 'collapse' );
		} );

		/*===*===*===*===*===*===*===*===*===*     Expand     *===*===*===*===*===*===*===*===*===*/
		$( '#menu-to-edit' ).on( 'click', '.button-expand', function() {
			var _this = $(this);
			var hide_flag  = true;

			if( _this.hasClass( 'collapse' ) ) {
				_this.removeClass( 'collapse' );
				hide_flag = false;
			} else {
				_this.addClass( 'collapse' );
			}

			var parent         = _this.closest( '.menu-item' );
			var level          = parseInt( parent.attr( 'class' ).split( 'menu-item-depth-' )[1].split(' ')[0] );
			var list_menu_item = $( '#menu-to-edit .menu-item' );
			var index_current  = list_menu_item.index( parent );

			$.each( list_menu_item, function( key, val ){
				if( key > index_current ) {
					var _this          = $(this);
					var level_children = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split(' ')[0] );

					if( level_children <= level ) {
						return false;
					} else {
						if( hide_flag ) {
							_this.hide();
						} else {
							_this.show();

							_this.find( '.button-expand.collapse' ).removeClass( 'collapse' );
						}
					}
				}

			} );
		});

		// Delete menu parent
		$( '#menu-to-edit' ).on( 'click', '.item-delete', function(){
			var _this          = $(this);
			var parent         = _this.closest( '.menu-item' );
			var level          = parseInt( parent.attr( 'class' ).split( 'menu-item-depth-' )[1].split(' ')[0] );
			var list_menu_item = $( '#menu-to-edit .menu-item' );
			var index_current  = list_menu_item.index( parent );

			$.each( list_menu_item, function( key, val ){
				if( key > index_current ) {
					var _this          = $(this);
					var level_children = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split(' ')[0] );

					if( level_children <= level ) {
						return false;
					} else {
						_this.show();
					}
				}
			} );
		} );
	}

	function get_item_data( id, level ){
		var level_default = ( level > 1 ) ? 2 : level;
		level_default++;

		var data = {};
		if( $.RtMegamenu['data'][id] != undefined ) {
			data = $.extend( {}, rtmegamenu_data_default[ 'lvl_'+ level_default ], $.RtMegamenu['data'][id] );
		} else if( rt_data_megamenu[id] != undefined ){
			data = $.extend( {}, rtmegamenu_data_default[ 'lvl_'+ level_default ], rt_data_megamenu[id] );
		} else {
			data = rtmegamenu_data_default[ 'lvl_'+ level_default ];
		}

		data['level'] = level;

		return data;
	}
	function update_item_data( element, val, key ){
		if( key ) {
			var id = element.closest( '.rt-wrapper' ).attr( 'data-id' );
    		$.RtMegamenu.data[id][key] = val;
		}
	}
	function load_content_element( type, content ) {
		var html_render = '';

		switch( type ){
			case 'html':
				var wp_editor = $( 'script#rt-html-element' ).html();
				wp_editor = wp_editor.replace( '_WR_CONTENT_', content );
				$( '.rt-wrapper .element-content' ).html( wp_editor );
				var render_editor = function(){
					var intTimeout = 5000;
			        var intAmount  = 100;
			        var iframe_load_completed = true;
			        var ifLoadedInt = setInterval(function(){
			            if (iframe_load_completed || intAmount >= intTimeout) {
			                ( function() {
			                    var init, id, $wrap;
			                    // Render Visual Tab
			                    for ( id in tinyMCEPreInit.mceInit ) {
			                        if ( id != 'rt-editor' )
			                            continue;
			                        init  = tinyMCEPreInit.mceInit[id];
			                        $wrap = tinymce.$( '#wp-' + id + '-wrap' );
			                        tinymce.remove(tinymce.get('rt-editor'));
			                        tinymce.init( init );
			                        setTimeout( function(){
			                            $( '#wp-rt-editor-wrap' ).removeClass( 'html-active' );
			                            $( '#wp-rt-editor-wrap' ).addClass( 'tmce-active' );
			                        }, 10 );
			                        if ( ! window.wpActiveEditor )
			                                window.wpActiveEditor = id;
			                        break;
			                    }
			                    // Render Text tab
			                    for ( id in tinyMCEPreInit.qtInit ) {
			                        if ( id != 'rt-editor' )
			                            continue;
			                        quicktags( tinyMCEPreInit.qtInit[id] );
			                        // Re call inset quicktags button
			                        QTags._buttonsInit();
			                        if ( ! window.wpActiveEditor )
			                            window.wpActiveEditor = id;
			                        break;
			                    }
			                }());
			                iframe_load_completed = false;
			                window.clearInterval(ifLoadedInt);
			            }
			        },
			        intAmount
			        );
				};
				render_editor();
				break;
			case '':
				$( '.rt-wrapper .element-content' ).html( '' );
				break;
		}

		return html_render;
	}

	function content_element_actions(type){
		if(type == 'html'){
			$('.hide-label-control').show();
		}else{
			$('.hide-label-control').hide();
		}
	}

	function save_ajax(){
		var data_options = {};
		data_options['event'] = $('#megamenu-event').val();
		data_options['type'] = $('#megamenu-type').val();
		$.ajax( {
			type   : "POST",
			url    : rt_megamenu.ajaxurl,
			data   : {
				action           : 'rt_save_options',
				_nonce           : rt_megamenu._nonce,
				menu_id          : rt_megamenu.menu_id,
				data             : data_options,
				data_last_update : 'ok'
			},
			success: function ( data_return ) {

			}
		});
		// Remove data null before udpate
		var data_save = {};

		$.each( $.RtMegamenu.data, function( key, val ){
			data_save[key] = {};

			$.each( val, function( key_item, val_item ) {

				if( typeof val_item == 'string' )
					val_item = val_item.trim();
				if( val_item !== '' ) {
					switch( val['level'] ) {
					    case 0:
					    		data_save[key][key_item] = val_item;
					        break;
					    case 1:
					    		data_save[key][key_item] = val_item;
					        break;
					    default:
					    		data_save[key][key_item] = val_item;
					}
				}

			});
		});
		$.ajax( {
			type   : "POST",
			url    : rt_megamenu.ajaxurl,
			data   : {
				action           : 'rt_save_megamenu',
				_nonce           : rt_megamenu._nonce,
				menu_id          : rt_megamenu.menu_id,
				data             : data_save,
				data_last_update : 'ok',
			},
			success: function ( data_return ) {
				// Parse data
				var data_return = ( data_return ) ? JSON.parse( data_return ) : '';
				if( data_return.status == 'true' ) {
					if( $( '.rt-error' ).length ) {
						$( '.rt-error' ).remove();
					}
					$.RtMegamenu.allow_submit = true;
					// Submit form
					$( '.wp-admin #update-nav-menu' ).submit();
				} else if( data_return.status == 'updating' ) {
					$.each( data_return.list_id_updated , function ( value, key ) {
						delete $.RtMegamenu.data[ key ];
					});

					// Update next data
					$.RtMegamenu.save_ajax();

				} else if( data_return.status == 'false' ) {
					if( $( '.rt-loading' ).length ) {
						$( '.rt-loading' ).remove();
					}

					// Show error
					$( '.major-publishing-actions .publishing-action' ).prepend( '<p class="rt-error">' + data_return.message + '</p>' );
				}
			}
		});

	}
	function get_childs(id) {
		var item_parent = $(this).parent();
		var add_allow = false;
		var childs = [];
		var _index = 0;
		$('#menu-to-edit .menu-item').each( function(){
			var _this = $(this);
			var menu_id = parseInt( _this.attr('id').split( 'menu-item-' )[1] );
			var level = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split( ' ' )[0] );
			if(add_allow && (level == 0)) add_allow = false;
			if(add_allow && (level == 1)) {
				childs[_index] =  $( '#menu-item-' + menu_id + ' .menu-item-title' ).text();
				_index++;
			}
			if(menu_id == id) add_allow = true;
		});
		return childs;
	}

	function event_listen(){
		$( '#menu-to-edit' ).on( 'click', '.rt-setting-btn', function(){

			var _this       = $(this);
			var item_parent = _this.closest( '.menu-item' );

			// Get id menu item
			var id = parseInt( item_parent.attr('id').split( 'menu-item-' )[1] );

			// Get level menu item
			var level = parseInt( item_parent.attr( 'class' ).split( 'menu-item-depth-' )[1].split( ' ' )[0] );

			$.RtMegamenu['data'][id] = get_item_data( id, level );

			var mega_active        = 0;
			var submenu_type        = '';
			var total_menu_item_lv_2 = 0;

			if( level == 0 ) {
				submenu_type = parseInt( $.RtMegamenu['data'][id]['submenu_type'] );
			} else if( level == 1 ) {
				var parent_lv_0 = item_parent.prevAll( '.menu-item-depth-0:first' );
				var parent_id   = parseInt( parent_lv_0.attr('id').split( 'menu-item-' )[1] );

				mega_active   = ( $.RtMegamenu['data'][parent_id] != undefined ) ? $.RtMegamenu['data'][parent_id]['mega'] : 0;
			} else if( level == 2 ) {
				var parent_lv_0 = item_parent.prevAll( '.menu-item-depth-0:first' );
				var grandparent_id   = parseInt( parent_lv_0.attr('id').split( 'menu-item-' )[1] );
				mega_active   = ( $.RtMegamenu['data'][grandparent_id] != undefined ) ? $.RtMegamenu['data'][grandparent_id]['mega'] : 0;
			}

			/* Check level 0 has menu children */
			var has_children = false;
			var cols = [];
			if( level == 0 && item_parent.next( '.menu-item-depth-1' ).length != 0 ){
				has_children = true;
			}
			/* Check level 1 has menu children */
			if( level == 1 && item_parent.next( '.menu-item-depth-2' ).length != 0){
				has_children = true;
			}
			/* Check level 2 has menu children */
			if( level == 2 && item_parent.next( '.menu-item-depth-3' ).length != 0){
				has_children = true;
			}
			var childs = get_childs(id);

			// Get html
			var template_show = _.template( $( "script#rt-template" ).html() )({
				data_item         : $.RtMegamenu['data'][id],
				title_modal       : item_parent.find( '.menu-item-title' ).text(),
				level             : level,
				submenu_type	  : submenu_type,
				id                : id,
				"$"               : jQuery,
				has_children      : has_children,
				childs			  : childs,
				cols			  : cols
			});
			$( 'body' ).append( $( 'script#rt-modal-html' ).html() );
			$( '.rt-dialog' ).html( template_show );
			$( '.rt-modal' ).addClass( 'main-settings' );

			// Load content element for menu item level 2
			if( level == 1 ) {
				var _content = '';
				if($.RtMegamenu['data'][id]['element_type'] == 'html')
					_content = $.RtMegamenu['data'][id]['html_data'];
				load_content_element( $.RtMegamenu['data'][id]['element_type'], _content );
				content_element_actions( $.RtMegamenu['data'][id]['element_type'] );
			}

			/* Action for modal */
			var modal         = $( '.rt-dialog' );
			var modal_info    = modal[0].getBoundingClientRect();
			var window_el     = $(window);
			var scroll_top    = window_el.scrollTop();
			var height_window = window_el.height();
			var top_position  = 0;

			if( modal_info.height < height_window ) {
				top_position = scroll_top + ( ( height_window - modal_info.height ) / 2 );
			} else {
				top_position = scroll_top + 10;
			}
			modal.css( 'top', top_position );
			m_colorPicker();

		})
		// Close popup
		$('body').on('click', '.dialog-title .close , .bottom-bar button', function() {
			$(this).closest( '.rt-modal' ).remove();

			$( '.rt-modal.main-settings.hidden' ).removeClass( 'hidden' );
		});

		$('body').on('change', '.rt-wrapper .submenu-type', function() {
			var _this = $( this );
			var value = _this.val();
			//Get id current
			var parent_current 	= _this.closest( '.rt-wrapper' );

			var id_el = $( '#menu-item-' + parent_current.attr( 'data-id' ) ) ;
			if(value != 'mega') {
				parent_current.find( '.mega-options' ).stop( true, false ).slideUp();
				// Add active
				id_el.addClass( 'megamenu-active' );
			} else {
				parent_current.find( '.mega-options' ).stop( true, false ).slideDown();
				// Remove active
				id_el.removeClass( 'megamenu-active' );
			}
			update_item_data( _this, value, 'submenu_type');
		});
		// Disable link
		$('body').on('click', '.rt-wrapper .disable-link', function() {
			var _this = $( this );
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( _this, value, 'disable_link');
		});
		// Disable link
		$('body').on('click', '.rt-wrapper .column-heading', function() {
			var _this = $( this );
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( _this, value, 'column_heading');
		});
		//Use icon
		$('body').on('click', '.rt-wrapper .use-icon', function() {
			var _this = $( this );
			if($(this).is(":checked")) {
				var value = '1';
				$( this ).closest( '.rt-icon-fields' ).find( '.icon-form' ).removeAttr( 'style' );
			} else {
				var value = '0';
				$( this ).closest( '.rt-icon-fields' ).find( '.icon-form' ).hide();
			}
			update_item_data( $( this ), value, 'use_icon');
		});
		// Upload icon
		$('body').on('click', '.rt-wrapper .select-icon', function(e) {
			var _this = $( this );
			
			e.preventDefault();
            var image_frame;
            if(image_frame){
                image_frame.open();
            }
            // Define image_frame as wp.media object
            image_frame = wp.media({
                title: 'Select Media',
                multiple : false,
                library : {
                    type : 'image',
                }
            });

            image_frame.on('close',function() {
                  // On close, get selections and save to the hidden input
                  // plus other AJAX stuff to refresh the image preview
                  var selection =  image_frame.state().get('selection');
                  var gallery_url = new Array();
                  var my_index = 0;
                  selection.each(function(attachment) {
                     gallery_url[my_index] = attachment['attributes']['url'];
                     my_index++;
                  });
                  var ids = gallery_url.join(",");
                  update_item_data( _this, ids, 'icon');
                  $('.m-icon-display img').attr('src',ids);
                  if(ids) $('.remove-icon').removeClass('button-invisible').addClass('button-visible');
            });

            image_frame.on('open',function() {
                // On open, get the id from the hidden input
                // and select the appropiate images in the media manager
                var selection =  image_frame.state().get('selection');
                var image_url = $('.m-icon-display img').attr('src');
                var attachment = wp.media.attachment(image_url);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );

            });

            image_frame.open();
     		
			
		});
		$('body').on('click', '.rt-wrapper .remove-icon', function(e) {
			var _this = $( this );
			update_item_data( _this, '', 'icon');
			$('.remove-icon').removeClass('button-visible').addClass('button-invisible');
			$('.m-icon-display img').attr('src','');
		});

		
		// column heading
		$('body').on('click', '.rt-wrapper .column-heading', function() {
			var _this = $( this );
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( _this, value, 'column_heading');
		});
		//width change
		$('body').on('blur', '.rt-wrapper .number-width', function() {
			var value = $( this ).val().replace(/[^0-9]/gi, '');
			update_item_data( $( this ), value, 'width');
		});
		//width type change
		$('body').on('change', '.rt-wrapper .width-type', function() {
			var value = $(this).val();
			update_item_data( $( this ), value, 'width_type');

			if( value == 'fixed' ){
				$( this ).closest( '.mega-option' ).find( '.width-box' ).removeAttr( 'style' );
			} else {
				$( this ).closest( '.mega-option' ).find( '.width-box' ).hide();
			}
		});
		//subtitle
		$('body').on('blur', '.rt-wrapper .subtitle', function() {
			var value = $( this ).val();
			update_item_data( $( this ), value, 'subtitle');
		});
		//subtitle
		$('body').on('blur', '.rt-wrapper .custom-class', function() {
			var value = $( this ).val();
			update_item_data( $( this ), value, 'custom_class');
		});
		//column width
		$('body').on('change', '.rt-wrapper .column-width', function() {
			var value = $(this).val();
			update_item_data( $( this ), value, 'column_width');
		});
		
		//swith content element
		$('body').on('change', '.rt-wrapper .element-type', function() {
			var value = $(this).val();
			var parent_current 	= $(this).closest( '.rt-wrapper' );
			var id = parent_current.attr( 'data-id');
			var _content = '';
			if(value == 'html') {
				if($.RtMegamenu['data'][id]['html_data'] != null)
					_content = $.RtMegamenu['data'][id]['html_data'];
			}
			load_content_element( value, _content);
			content_element_actions( value );
			update_item_data( $( this ), value, 'element_type');
		});
		// Change value text element
		$('body').on('change', '.rt-wrapper .rt-html-element .rt-editor-hidden', function( e ) {
			var _this       = $(this);
			var data_insert = _this.val();
			if($('.rt-wrapper .element-type').val() == 'html')
				update_item_data( _this, data_insert, 'html_data');
		});
		// Hide label
		$('body').on('click', '.rt-wrapper .hide-label', function() {
			var _this = $( this );
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( _this, value, 'hide_label');
		});
		//Responsive hide desktop
		$('body').on('click', '.rt-wrapper .hide-desktop', function() {
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( $(this), value, 'hide_desktop');
		});
		//Responsive hide mobile
		$('body').on('click', '.rt-wrapper .hide-mobile', function() {
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( $(this), value, 'hide_mobile');
		});
		/*===*===*===*===*===*===*===*===*===*     SAVE DATA     *===*===*===*===*===*===*===*===*===*/
		// Save data menu
		$( '.wp-admin #update-nav-menu' ).on( "submit", function( e ) {
			if( Object.keys( $.RtMegamenu.data ).length ) {
				if( $.RtMegamenu.allow_submit == false ) {
					e.preventDefault();
					// Save data
					save_ajax();
				}
			}
		});
	}
	function m_colorPicker(){
		$('.color-field').each(function(){
			var _this = $(this);
			_this.wpColorPicker({
				change: function(event, ui){
					var color = ui.color.toString();
					update_item_data( _this, color, 'subtitle_background');
				},
				clear: function() {
					update_item_data( _this, '', 'subtitle_background');
				},
				
			});
		})
		
	};

	add_setting_button();
	add_menu_data();
	button_expand();
	button_expand_collapse_all();
	event_listen();

})( jQuery );
