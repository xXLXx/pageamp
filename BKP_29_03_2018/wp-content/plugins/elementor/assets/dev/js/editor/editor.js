/* global ElementorConfig */
var App;

Marionette.TemplateCache.prototype.compileTemplate = function( rawTemplate, options ) {
	options = {
		evaluate: /<#([\s\S]+?)#>/g,
		interpolate: /{{{([\s\S]+?)}}}/g,
		escape: /{{([^}]+?)}}(?!})/g
	};

	return _.template( rawTemplate, options );
};

App = Marionette.Application.extend( {
	helpers: require( 'elementor-editor-utils/helpers' ),
	heartbeat: require( 'elementor-editor-utils/heartbeat' ),
	imagesManager: require( 'elementor-editor-utils/images-manager' ),
	debug: require( 'elementor-editor-utils/debug' ),
	schemes: require( 'elementor-editor-utils/schemes' ),
	presetsFactory: require( 'elementor-editor-utils/presets-factory' ),
	templates: require( 'elementor-templates/manager' ),
	ajax: require( 'elementor-editor-utils/ajax' ),
	conditions: require( 'elementor-editor-utils/conditions' ),
	hotKeys: require( 'elementor-utils/hot-keys' ),
	history:  require( 'modules/history/assets/js/module' ),

	channels: {
		editor: Backbone.Radio.channel( 'ELEMENTOR:editor' ),
		data: Backbone.Radio.channel( 'ELEMENTOR:data' ),
		panelElements: Backbone.Radio.channel( 'ELEMENTOR:panelElements' ),
		dataEditMode: Backbone.Radio.channel( 'ELEMENTOR:editmode' ),
		deviceMode: Backbone.Radio.channel( 'ELEMENTOR:deviceMode' ),
		templates: Backbone.Radio.channel( 'ELEMENTOR:templates' )
	},

	// Exporting modules that can be used externally
	modules: {
		element: require( 'elementor-models/element' ),
		WidgetView: require( 'elementor-views/widget' ),
		panel: {
			Menu: require( 'elementor-panel/pages/menu/menu' )
		},
		controls: {
			Base: require( 'elementor-views/controls/base' ),
			BaseMultiple: require( 'elementor-views/controls/base-multiple' ),
			Color: require( 'elementor-views/controls/color' ),
			Dimensions: require( 'elementor-views/controls/dimensions' ),
			Image_dimensions: require( 'elementor-views/controls/image-dimensions' ),
			Media: require( 'elementor-views/controls/media' ),
			Slider: require( 'elementor-views/controls/slider' ),
			Wysiwyg: require( 'elementor-views/controls/wysiwyg' ),
			Choose: require( 'elementor-views/controls/choose' ),
			Url: require( 'elementor-views/controls/base-multiple' ),
			Font: require( 'elementor-views/controls/font' ),
			Section: require( 'elementor-views/controls/section' ),
			Tab: require( 'elementor-views/controls/tab' ),
			Repeater: require( 'elementor-views/controls/repeater' ),
			Wp_widget: require( 'elementor-views/controls/wp_widget' ),
			Icon: require( 'elementor-views/controls/icon' ),
			Gallery: require( 'elementor-views/controls/gallery' ),
			Select2: require( 'elementor-views/controls/select2' ),
			Date_time: require( 'elementor-views/controls/date-time' ),
			Code: require( 'elementor-views/controls/code' ),
			Box_shadow: require( 'elementor-views/controls/box-shadow' ),
			Text_shadow: require( 'elementor-views/controls/box-shadow' ),
			Structure: require( 'elementor-views/controls/structure' ),
			Animation: require( 'elementor-views/controls/select2' ),
			Hover_animation: require( 'elementor-views/controls/select2' ),
			Order: require( 'elementor-views/controls/order' ),
			Switcher: require( 'elementor-views/controls/switcher' ),
			Number: require( 'elementor-views/controls/number' )
		},
		templateLibrary: {
			ElementsCollectionView: require( 'elementor-panel/pages/elements/views/elements' )
		}
	},

	_defaultDeviceMode: 'desktop',

	addControlView: function( controlID, ControlView ) {
		this.modules.controls[ controlID[0].toUpperCase() + controlID.slice( 1 ) ] = ControlView;
	},

	getElementData: function( modelElement ) {
		var elType = modelElement.get( 'elType' );

		if ( 'widget' === elType ) {
			var widgetType = modelElement.get( 'widgetType' );

			if ( ! this.config.widgets[ widgetType ] ) {
				return false;
			}

			return this.config.widgets[ widgetType ];
		}

		if ( ! this.config.elements[ elType ] ) {
			return false;
		}

		return this.config.elements[ elType ];
	},

	getElementControls: function( modelElement ) {
		var self = this,
			elementData = self.getElementData( modelElement );

		if ( ! elementData ) {
			return false;
		}

		var elType = modelElement.get( 'elType' ),
			isInner = modelElement.get( 'isInner' ),
			controls = {};

		_.each( elementData.controls, function( controlData, controlKey ) {
			if ( isInner && controlData.hide_in_inner || ! isInner && controlData.hide_in_top ) {
				return;
			}

			controls[ controlKey ] = _.extend( {}, self.config.controls[ controlData.type ], controlData  );
		} );

		return controls;
	},

	getControlView: function( controlID ) {
		return this.modules.controls[ controlID[0].toUpperCase() + controlID.slice( 1 ) ] || this.modules.controls.Base;
	},

	getPanelView: function() {
		return this.getRegion( 'panel' ).currentView;
	},

	initComponents: function() {
		var EventManager = require( 'elementor-utils/hooks' ),
			Settings = require( 'elementor-editor/settings/settings' );

		this.hooks = new EventManager();

		this.settings = new Settings();

		/**
		 * @deprecated - use `this.settings.page` instead
		 */
		this.pageSettings = this.settings.page;

		this.templates.init();

		this.initDialogsManager();

		this.heartbeat.init();

		this.ajax.init();
	},

	initDialogsManager: function() {
		this.dialogsManager = new DialogsManager.Instance();
	},

	initElements: function() {
		var ElementModel = elementor.modules.element,
			config = this.config.data;

		// If it's an reload, use the not-saved data
		if ( this.elements ) {
			config = this.elements.toJSON();
		}

		this.elements = new ElementModel.Collection( config );
	},

	initPreview: function() {
		this.$previewWrapper = Backbone.$( '#elementor-preview' );

		this.$previewResponsiveWrapper = Backbone.$( '#elementor-preview-responsive-wrapper' );

		var previewIframeId = 'elementor-preview-iframe';

		// Make sure the iFrame does not exist.
		if ( ! this.$preview ) {
			this.$preview = Backbone.$( '<iframe>', {
				id: previewIframeId,
				src: this.config.preview_link + '&' + ( new Date().getTime() ),
				allowfullscreen: 1
			} );

			this.$previewResponsiveWrapper.append( this.$preview );
		}

		this.$preview.on( 'load', _.bind( this.onPreviewLoaded, this ) );
	},

	initFrontend: function() {
		var frontendWindow = this.$preview[0].contentWindow;

		window.elementorFrontend = frontendWindow.elementorFrontend;

		frontendWindow.elementor = this;

		elementorFrontend.init();

		elementorFrontend.elementsHandler.initHandlers();

		this.trigger( 'frontend:init' );
	},

	initClearPageDialog: function() {
		var self = this,
			dialog;

		self.getClearPageDialog = function() {
			if ( dialog ) {
				return dialog;
			}

			dialog = this.dialogsManager.createWidget( 'confirm', {
				id: 'elementor-clear-page-dialog',
				headerMessage: elementor.translate( 'clear_page' ),
				message: elementor.translate( 'dialog_confirm_clear_page' ),
				position: {
					my: 'center center',
					at: 'center center'
				},
				strings: {
					confirm: elementor.translate( 'delete' ),
					cancel: elementor.translate( 'cancel' )
				},
				onConfirm: function() {
					self.getRegion( 'sections' ).currentView.collection.reset();
				}
			} );

			return dialog;
		};
	},

	initHotKeys: function() {
		var keysDictionary = {
			del: 46,
			d: 68,
			l: 76,
			m: 77,
			p: 80,
			s: 83
		};

		var $ = jQuery,
			hotKeysHandlers = {},
			hotKeysManager = this.hotKeys;

		hotKeysHandlers[ keysDictionary.del ] = {
			deleteElement: {
				isWorthHandling: function( event ) {
					var isEditorOpen = 'editor' === elementor.getPanelView().getCurrentPageName();

					if ( ! isEditorOpen ) {
						return false;
					}

					var $target = $( event.target );

					if ( $target.is( ':input, .elementor-input' ) ) {
						return false;
					}

					return ! $target.closest( '.elementor-inline-editing' ).length;
				},
				handle: function() {
					elementor.getPanelView().getCurrentPageView().getOption( 'editedElementView' ).removeElement();
				}
			}
		};

		hotKeysHandlers[ keysDictionary.d ] = {
			duplicateElement: {
				isWorthHandling: function( event ) {
					return hotKeysManager.isControlEvent( event );
				},
				handle: function() {
					var panel = elementor.getPanelView();

					if ( 'editor' !== panel.getCurrentPageName() ) {
						return;
					}

					panel.getCurrentPageView().getOption( 'editedElementView' ).duplicate();
				}
			}
		};

		hotKeysHandlers[ keysDictionary.l ] = {
			showTemplateLibrary: {
				isWorthHandling: function( event ) {
					return hotKeysManager.isControlEvent( event ) && event.shiftKey;
				},
				handle: function() {
					elementor.templates.showTemplatesModal();
				}
			}
		};

		hotKeysHandlers[ keysDictionary.m ] = {
			changeDeviceMode: {
				devices: [ 'desktop', 'tablet', 'mobile' ],
				isWorthHandling: function( event ) {
					return hotKeysManager.isControlEvent( event ) && event.shiftKey;
				},
				handle: function() {
					var currentDeviceMode = elementor.channels.deviceMode.request( 'currentMode' ),
						modeIndex = this.devices.indexOf( currentDeviceMode );

					modeIndex++;

					if ( modeIndex >= this.devices.length ) {
						modeIndex = 0;
					}

					elementor.changeDeviceMode( this.devices[ modeIndex ] );
				}
			}
		};

		hotKeysHandlers[ keysDictionary.p ] = {
			changeEditMode: {
				isWorthHandling: function( event ) {
					return hotKeysManager.isControlEvent( event );
				},
				handle: function() {
					elementor.getPanelView().modeSwitcher.currentView.toggleMode();
				}
			}
		};

		hotKeysHandlers[ keysDictionary.s ] = {
			saveEditor: {
				isWorthHandling: function( event ) {
					return hotKeysManager.isControlEvent( event );
				},
				handle: function() {
					elementor.getPanelView().getFooterView()._publishBuilder();
				}
			}
		};

		_.each( hotKeysHandlers, function( handlers, keyCode ) {
			_.each( handlers, function( handler, handlerName ) {
				hotKeysManager.addHotKeyHandler( keyCode, handlerName, handler );
			} );
		} );

		hotKeysManager.bindListener( this.$window.add( elementorFrontend.getElements( '$window' ) ) );
	},

	preventClicksInsideEditor: function() {
		this.$previewContents.on( 'click', function( event ) {
			var $target = Backbone.$( event.target ),
				editMode = elementor.channels.dataEditMode.request( 'activeMode' ),
				isClickInsideElementor = !! $target.closest( '#elementor, .pen-menu' ).length,
				isTargetInsideDocument = this.contains( $target[0] );

			if ( isClickInsideElementor && 'edit' === editMode || ! isTargetInsideDocument ) {
				return;
			}

			if ( $target.closest( 'a:not(.elementor-clickable)' ).length ) {
				event.preventDefault();
			}

			if ( ! isClickInsideElementor ) {
				var panelView = elementor.getPanelView();

				if ( 'elements' !== panelView.getCurrentPageName() ) {
					panelView.setPage( 'elements' );
				}
			}
		} );
	},

	showFatalErrorDialog: function( options ) {
		var defaultOptions = {
			id: 'elementor-fatal-error-dialog',
			headerMessage: '',
			message: '',
			position: {
				my: 'center center',
				at: 'center center'
			},
			strings: {
				confirm: elementor.translate( 'learn_more' ),
				cancel: elementor.translate( 'go_back' )
			},
			onConfirm: null,
			onCancel: function() {
				parent.history.go( -1 );
			},
			hide: {
				onBackgroundClick: false,
				onButtonClick: false
			}
		};

		options = jQuery.extend( true, defaultOptions, options );

		this.dialogsManager.createWidget( 'confirm', options ).show();
	},

	onStart: function() {
		this.$window = Backbone.$( window );

		NProgress.start();
		NProgress.inc( 0.2 );

		this.config = ElementorConfig;

		Backbone.Radio.DEBUG = false;
		Backbone.Radio.tuneIn( 'ELEMENTOR' );

		this.initComponents();

		this.channels.dataEditMode.reply( 'activeMode', 'edit' );

		this.listenTo( this.channels.dataEditMode, 'switch', this.onEditModeSwitched );

		this.setWorkSaver();

		this.initClearPageDialog();

		this.$window.trigger( 'elementor:init' );

		this.initPreview();

		this.logSite();
	},

	onPreviewLoaded: function() {
		NProgress.done();

		var previewWindow = this.$preview[0].contentWindow;

		if ( ! previewWindow.elementorFrontend ) {
			this.onPreviewLoadingError();

			return;
		}

		this.$previewContents = this.$preview.contents();

		var $previewElementorEl = this.$previewContents.find( '#elementor' );

		if ( ! $previewElementorEl.length ) {
			this.onPreviewElNotFound();

			return;
		}

		this.initFrontend();

		this.initElements();

		this.initHotKeys();

		var iframeRegion = new Marionette.Region( {
			// Make sure you get the DOM object out of the jQuery object
			el: $previewElementorEl[0]
		} );

		this.schemes.init();

		this.schemes.printSchemesStyle();

		this.preventClicksInsideEditor();

		var Preview = require( 'elementor-views/preview' ),
			PanelLayoutView = require( 'elementor-layouts/panel/panel' );

		this.addRegions( {
			sections: iframeRegion,
			panel: '#elementor-panel'
		} );

		this.getRegion( 'sections' ).show( new Preview( {
			collection: this.elements
		} ) );

		this.getRegion( 'panel' ).show( new PanelLayoutView() );

		this.$previewContents
		    .children() // <html>
		    .addClass( 'elementor-html' )
		    .children( 'body' )
		    .addClass( 'elementor-editor-active' );

		this.setResizablePanel();

		this.changeDeviceMode( this._defaultDeviceMode );

		Backbone.$( '#elementor-loading, #elementor-preview-loading' ).fadeOut( 600 );

		_.defer( function() {
			elementorFrontend.getElements( 'window' ).jQuery.holdReady( false );
		} );

		this.enqueueTypographyFonts();

		this.onEditModeSwitched();

		this.trigger( 'preview:loaded' );
	},

	onEditModeSwitched: function() {
		var activeMode = this.channels.dataEditMode.request( 'activeMode' );

		if ( 'edit' === activeMode ) {
			this.exitPreviewMode();
		} else {
			this.enterPreviewMode( 'preview' === activeMode );
		}
	},

	onPreviewLoadingError: function() {
		this.showFatalErrorDialog( {
			headerMessage: this.translate( 'preview_not_loading_header' ),
			message: this.translate( 'preview_not_loading_message' ),
			onConfirm: function() {
				open( elementor.config.help_preview_error_url, '_blank' );
			}
		} );
	},

	onPreviewElNotFound: function() {
		this.showFatalErrorDialog( {
			headerMessage: this.translate( 'preview_el_not_found_header' ),
			message: this.translate( 'preview_el_not_found_message' ),
			onConfirm: function() {
				open( elementor.config.help_the_content_url, '_blank' );
			}
		} );
	},

	setFlagEditorChange: function( status ) {
		elementor.channels.editor
			.reply( 'status', status )
			.trigger( 'status:change', status );
	},

	isEditorChanged: function() {
		return ( true === elementor.channels.editor.request( 'status' ) );
	},

	setWorkSaver: function() {
		this.$window.on( 'beforeunload', function() {
			if ( elementor.isEditorChanged() ) {
				return elementor.translate( 'before_unload_alert' );
			}
		} );
	},

	setResizablePanel: function() {
		var self = this,
			side = elementor.config.is_rtl ? 'right' : 'left';

		self.panel.$el.resizable( {
			handles: elementor.config.is_rtl ? 'w' : 'e',
			minWidth: 200,
			maxWidth: 680,
			start: function() {
				self.$previewWrapper
					.addClass( 'ui-resizable-resizing' )
					.css( 'pointer-events', 'none' );
			},
			stop: function() {
				self.$previewWrapper
					.removeClass( 'ui-resizable-resizing' )
					.css( 'pointer-events', '' );

				elementor.channels.data.trigger( 'scrollbar:update' );
			},
			resize: function( event, ui ) {
				self.$previewWrapper
					.css( side, ui.size.width );
			}
		} );
	},

	enterPreviewMode: function( hidePanel ) {
		var $elements = this.$previewContents.find( 'body' );

		if ( hidePanel ) {
			$elements = $elements.add( 'body' );
		}

		$elements
			.removeClass( 'elementor-editor-active' )
			.addClass( 'elementor-editor-preview' );

		if ( hidePanel ) {
			// Handle panel resize
			this.$previewWrapper.css( elementor.config.is_rtl ? 'right' : 'left', '' );

			this.panel.$el.css( 'width', '' );
		}
	},

	exitPreviewMode: function() {
		this.$previewContents
			.find( 'body' )
			.add( 'body' )
			.removeClass( 'elementor-editor-preview' )
			.addClass( 'elementor-editor-active' );
	},

	changeEditMode: function( newMode ) {
		var dataEditMode = elementor.channels.dataEditMode,
			oldEditMode = dataEditMode.request( 'activeMode' );

		dataEditMode.reply( 'activeMode', newMode );

		if ( newMode !== oldEditMode ) {
			dataEditMode.trigger( 'switch', newMode );
		}
	},

	saveEditor: function( options ) {
		options = _.extend( {
			status: 'draft',
			onSuccess: null
		}, options );

		var self = this,
			newData = elementor.elements.toJSON( { removeDefault: true } );

		return this.ajax.send( 'save_builder', {
	        data: {
		        post_id: this.config.post_id,
				status: options.status,
		        data: JSON.stringify( newData )
	        },
			success: function( data ) {
				self.setFlagEditorChange( false );

				self.config.data = newData;

				self.channels.editor.trigger( 'saved', data );

				if ( _.isFunction( options.onSuccess ) ) {
					options.onSuccess.call( this, data );
				}
			}
		} );
	},

	reloadPreview: function() {
		Backbone.$( '#elementor-preview-loading' ).show();

		this.$preview[0].contentWindow.location.reload( true );
	},

	clearPage: function() {
		this.getClearPageDialog().show();
	},

	changeDeviceMode: function( newDeviceMode ) {
		var oldDeviceMode = this.channels.deviceMode.request( 'currentMode' );

		if ( oldDeviceMode === newDeviceMode ) {
			return;
		}

		Backbone.$( 'body' )
			.removeClass( 'elementor-device-' + oldDeviceMode )
			.addClass( 'elementor-device-' + newDeviceMode );

		this.channels.deviceMode
			.reply( 'previousMode', oldDeviceMode )
			.reply( 'currentMode', newDeviceMode )
			.trigger( 'change' );
	},

	enqueueTypographyFonts: function() {
		var self = this,
			typographyScheme = this.schemes.getScheme( 'typography' );

		_.each( typographyScheme.items, function( item ) {
			self.helpers.enqueueFont( item.value.font_family );
		} );
	},

	translate: function( stringKey, templateArgs, i18nStack ) {
		if ( ! i18nStack ) {
			i18nStack = this.config.i18n;
		}

		var string = i18nStack[ stringKey ];

		if ( undefined === string ) {
			string = stringKey;
		}

		if ( templateArgs ) {
			string = string.replace( /{(\d+)}/g, function( match, number ) {
				return undefined !== templateArgs[ number ] ? templateArgs[ number ] : match;
			} );
		}

		return string;
	},

	compareVersions: function( versionA, versionB, operator ) {
		var prepareVersion = function( version ) {
			version = version + '';

			return version.replace( /[^\d.]+/, '.-1.' );
		};

		versionA  = prepareVersion( versionA );
		versionB = prepareVersion( versionB );

		if ( versionA === versionB ) {
			return ! operator || /^={2,3}$/.test( operator );
		}

		var versionAParts = versionA.split( '.' ).map( Number ),
			versionBParts = versionB.split( '.' ).map( Number ),
			longestVersionParts = Math.max( versionAParts.length, versionBParts.length );

		for ( var i = 0; i < longestVersionParts; i++ ) {
			var valueA = versionAParts[ i ] || 0,
				valueB = versionBParts[ i ] || 0;

			if ( valueA !== valueB ) {
				return this.conditions.compare( valueA, valueB, operator );
			}
		}
	},

	logSite: function() {
		var text = '',
			style = '';

		if ( -1 !== navigator.userAgent.search( 'Firefox' ) ) {
			var asciiText = [
				' ;;;;;;;;;;;;;;; ',
				';;;  ;;       ;;;',
				';;;  ;;;;;;;;;;;;',
				';;;  ;;;;;;;;;;;;',
				';;;  ;;       ;;;',
				';;;  ;;;;;;;;;;;;',
				';;;  ;;;;;;;;;;;;',
				';;;  ;;       ;;;',
				' ;;;;;;;;;;;;;;; '
			];

			text += '%c' + asciiText.join( '\n' ) + '\n';

			style = 'color: #C42961';
		} else {
			text += '%c00';

			style = 'line-height: 1.6; font-size: 20px; background-image: url("' + elementor.config.assets_url + 'images/logo-icon.png"); color: transparent; background-repeat: no-repeat; background-size: cover';
		}

		text += '%c\nLove using Elementor? Join our growing community of Elementor developers: %chttps://github.com/pojome/elementor';

		setTimeout( console.log.bind( console, text, style, 'color: #9B0A46', '' ) );
	}
} );

module.exports = ( window.elementor = new App() ).start();
