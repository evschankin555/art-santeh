var fmm = fmm || {};

(function ( $, _ ) {
	'use strict';

	var wp = window.wp;

	fmm = {
		init: function () {
			this.$body = $( 'body' );
			this.itemData = {};
			this.frame = wp.media( {
				library: {
					type: 'image'
				}
			} );

			this.initActions();
		},


		initActions: function () {
			fmm.$body
				.on( 'click', '.cenos_mega_menu_settings', this.openModal )
				.on( 'click', '.fmm-modal-backdrop, .fmm-modal-close, .fmm-button-cancel', this.closeModal );
		},

		openModal: function () {
			fmm.getItemData( this );
			const $this = $(this),
				  url         = $this.attr('url'),
				  item_id     = $this.data('item_id'),
				  popup       = $('.content-popup-megamenu'),
				  curent_item = $this.closest('.menu-item');
			if ( !curent_item.hasClass('menu-item-depth-0') ) {
				curent_item.find('.fmfw-menu-setting-for-depth-0').css('opacity', 0);
			} else {
				curent_item.find('.fmfw-menu-setting-for-depth-0').css('opacity', 1);
			}
			$this.addClass('loading');
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'fmfw_get_form_settings',
					item_id: item_id,
					iframe: url,
				},
				success: function (response) {
					if ( response.success == 'yes' ) {
						popup.append(response.html);
						$.magnificPopup.open({
							items: {
								src: response.html
							},
							type: 'inline'
						});
					}
				},
				complete: function () {
					$this.removeClass('loading');
					fmm.settings_image();
				}
			});
			return false;
		},

		closeModal: function () {
			fmm.$modal.hide().find( '.fmm-content' ).html( '' );
			fmm.$body.removeClass( 'modal-open' );
			return false;
		},

		switchPanel: function ( e ) {
			e.preventDefault();

			var $el = $( this ),
				panel = $el.data( 'panel' );

			$el.addClass( 'active' ).siblings( '.active' ).removeClass( 'active' );
			fmm.openSettings( panel );
		},

		render: function () {
			// Render menu
			fmm.$modal.find( '.fmm-frame-menu .fmm-menu' ).html( fmm.templates.menus( fmm.itemData ) );

			var $activeMenu = fmm.$modal.find( '.fmm-menu a.active' );

			// Render content
			this.openSettings( $activeMenu.data( 'panel' ) );
		},

		openSettings: function ( panel ) {
			var $content = fmm.$modal.find( '.fmm-frame-content .fmm-content' ),
				$panel = $content.children( '#fmm-panel-' + panel );

			if ( $panel.length ) {
				$panel.addClass( 'active' ).siblings().removeClass( 'active' );
			} else {
				$content.append( fmm.templates[panel]( fmm.itemData ) );
				$content.children( '#fmm-panel-' + panel ).addClass( 'active' ).siblings().removeClass( 'active' );

				if ( 'mega' == panel ) {
					fmm.initMegaColumns();
				}
				if ( 'background' == panel ) {
					fmm.initBackgroundFields();
				}
				if ( 'icon' == panel ) {
					fmm.initIconFields();
				}
			}

			// Render title
			var title = fmm.$modal.find( '.fmm-frame-menu .fmm-menu a[data-panel=' + panel + ']' ).data( 'title' );
			fmm.$modal.find( '.fmm-frame-title' ).html( fmm.templates.title( {title: title} ) );
		},

		resizeMegaColumn: function ( e ) {
			e.preventDefault();

			var $el = $( e.currentTarget ),
				$column = $el.closest( '.fmm-submenu-column' ),
				currentWidth = $column.data( 'width' ),
				widthData = fmm.getWidthData( currentWidth ),
				nextWidth;

			if ( ! widthData ) {
				return;
			}

			if ( $el.hasClass( 'fmm-resizable-w' ) ) {
				nextWidth = widthData.increase ? widthData.increase : widthData;
			} else {
				nextWidth = widthData.decrease ? widthData.decrease : widthData;
			}

			$column[0].style.width = nextWidth.width;
			$column.data( 'width', nextWidth.width );
			$column.find( '.fmm-column-width-label' ).text( nextWidth.label );
			$column.find( '.menu-item-depth-0 .menu-item-width' ).val( nextWidth.width );
		},

		getWidthData: function( width ) {
			var steps = [
				{width: '25.00%', label: '1/4'},
				{width: '33.33%', label: '1/3'},
				{width: '50.00%', label: '1/2'},
				{width: '66.66%', label: '2/3'},
				{width: '75.00%', label: '3/4'},
				{width: '100.00%', label: '1/1'}
			];

			var index = _.findIndex( steps, function( data ) { return data.width == width; } );

			if ( index === 'undefined' ) {
				return false;
			}

			var data = {
				index: index,
				width: steps[index].width,
				label: steps[index].label
			};

			if ( index > 0 ) {
				data.decrease = {
					index: index - 1,
					width: steps[index - 1].width,
					label: steps[index - 1].label
				};
			}

			if ( index < steps.length - 1 ) {
				data.increase = {
					index: index + 1,
					width: steps[index + 1].width,
					label: steps[index + 1].label
				};
			}

			return data;
		},

		initMegaColumns: function () {
			var $columns = fmm.$modal.find( '#fmm-panel-mega .fmm-submenu-column' ),
				defaultWidth = '25.00%';

			if ( !$columns.length ) {
				return;
			}

			// Support maximum 4 columns
			if ( $columns.length < 4 ) {
				defaultWidth = String( ( 100 / $columns.length ).toFixed( 2 ) ) + '%';
			}

			_.each( $columns, function ( column ) {
				var $column = $( column ),
					width = column.dataset.width;

				width = width || defaultWidth;

				var widthData = fmm.getWidthData( width );

				column.style.width = widthData.width;
				column.dataset.width = widthData.width;
				$( column ).find( '.menu-item-depth-0 .menu-item-width' ).val( width );
				$( column ).find( '.fmm-column-width-label' ).text( widthData.label );
			} );
		},

		initBackgroundFields: function () {
			fmm.$modal.find( '.background-color-picker' ).wpColorPicker();

			// Background image
			fmm.$modal.on( 'click', '.background-image .upload-button', function ( e ) {
				e.preventDefault();

				var $el = $( this );

				// Remove all attached 'select' event
				fmm.frame.off( 'select' );

				// Update inputs when select image
				fmm.frame.on( 'select', function () {
					// Update input value for single image selection
					var url = fmm.frame.state().get( 'selection' ).first().toJSON().url;

					$el.siblings( '.background-image-preview' ).html( '<img src="' + url + '">' );
					$el.siblings( 'input' ).val( url );
					$el.siblings( '.remove-button' ).removeClass( 'hidden' );
				} );

				fmm.frame.open();
			} ).on( 'click', '.background-image .remove-button', function ( e ) {
				e.preventDefault();

				var $el = $( this );

				$el.siblings( '.background-image-preview' ).html( '' );
				$el.siblings( 'input' ).val( '' );
				$el.addClass( 'hidden' );
			} );

			// Background position
			fmm.$modal.on( 'change', '.background-position select', function () {
				var $el = $( this );

				if ( 'custom' == $el.val() ) {
					$el.next( 'input' ).removeClass( 'hidden' );
				} else {
					$el.next( 'input' ).addClass( 'hidden' );
				}
			} );
		},

		initIconFields: function () {
			var $input = fmm.$modal.find( '#fmm-icon-input' ),
				$preview = fmm.$modal.find( '#fmm-selected-icon' ),
				$icons = fmm.$modal.find( '.fmm-icon-selector .icons i' );

			fmm.$modal.on( 'click', '.fmm-icon-selector .icons i', function () {
				var $el = $( this ),
					icon = $el.data( 'icon' );

				$el.addClass( 'active' ).siblings( '.active' ).removeClass( 'active' );

				$input.val( icon );
				$preview.html( '<i class="' + icon + '"></i>' );
			} );

			$preview.on( 'click', 'i', function () {
				$( this ).remove();
				$input.val( '' );
			} );

			fmm.$modal.on( 'keyup', '.fmm-icon-search', function () {
				var term = $( this ).val().toUpperCase();

				if ( !term ) {
					$icons.show();
				} else {
					$icons.hide().filter( function () {
						return $( this ).data( 'icon' ).toUpperCase().indexOf( term ) > -1;
					} ).show();
				}
			} );
		},

		getItemData: function ( menuItem ) {
			var $menuItem = $( menuItem ).closest( 'li.menu-item' ),
				$menuData = $menuItem.find( '.mega-data' ),
				children = $menuItem.childMenuItems(),
				megaData = $menuData.data( 'mega' );
				megaData.content = $menuData.html();

			fmm.itemData = {
				depth   : $menuItem.menuItemDepth(),
				megaData: megaData,
				data    : $menuItem.getItemData(),
				children: [],
				element : $menuItem.get( 0 )
			};

			if ( !_.isEmpty( children ) ) {
				_.each( children, function ( item ) {
					var $item = $( item ),
						$itemData = $item.find( '.mega-data' ),
						depth = $item.menuItemDepth(),
						megaData = $itemData.data( 'mega' );

					megaData.content = $itemData.html();

					fmm.itemData.children.push( {
						depth   : depth,
						subDepth: depth - fmm.itemData.depth - 1,
						data    : $item.getItemData(),
						megaData: megaData,
						element : item
					} );
				} );
			}
		},

		setItemData: function ( item, data ) {
			var $dataHolder = $( item ).find( '.mega-data' );

			if ( _.has( data, 'content' ) ) {
				$dataHolder.html( data.content );
				delete data.content;
			}

			$dataHolder.data( 'mega', data );
		},

		getFieldName: function ( name, id ) {
			name = name.split( '.' );
			name = '[' + name.join( '][' ) + ']';

			return 'menu-item-mega[' + id + ']' + name;
		},

		saveChanges: function () {
			var $inputs = fmm.$modal.find( '.fmm-content :input' ),
				$spinner = fmm.$modal.find( '.fmm-toolbar .spinner' );

			$inputs.each( function() {
				var $input = $( this );

				if ( $input.is( ':checkbox' ) && $input.is( ':not(:checked)' ) ) {
					$input.attr( 'value', '0' ).prop( 'checked', true );
				}
			} );

			var data = $inputs.serialize();

			$inputs.filter( '[value="0"]' ).prop( 'checked', false );

			$spinner.addClass( 'is-active' );
			$.post( ajaxurl, {
				action: 'sober_save_menu_item_data',
				data  : data
			}, function ( res ) {
				if ( !res.success ) {
					return;
				}

				var data = res.data['menu-item-mega'];

				// Update parent menu item
				if ( _.has( data, fmm.itemData.data['menu-item-db-id'] ) ) {
					fmm.setItemData( fmm.itemData.element, data[fmm.itemData.data['menu-item-db-id']] );
				}

				_.each( fmm.itemData.children, function ( menuItem ) {
					if ( !_.has( data, menuItem.data['menu-item-db-id'] ) ) {
						return;
					}

					fmm.setItemData( menuItem.element, data[menuItem.data['menu-item-db-id']] );
				} );

				$spinner.removeClass( 'is-active' );
				fmm.closeModal();
			} );
		}
	};

	$( function () {
		fmm.init();
	} );
})( jQuery, _ );
