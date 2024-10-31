/* global qazanaFrontendConfig */
( function( $ ) {
	var elements = {},
		EventManager = require( 'qazana-utils/hooks' ),
		Module = require( 'qazana-frontend/handler-module' ),
		ElementsHandler = require( 'qazana-frontend/elements-handler' ),
		YouTubeModule = require( 'qazana-frontend/utils/youtube' ),
		AnchorsModule = require( 'qazana-frontend/utils/anchors' ),
		LightboxModule = require( 'qazana-frontend/utils/lightbox' );
		CarouselModule = require( 'qazana-frontend/utils/carousel' );
		
	var QazanaFrontend = function() {
		var self = this,
			dialogsManager;

		this.config = qazanaFrontendConfig;

		this.Module = Module;

		var setDeviceModeData = function() {
			elements.$body.attr( 'data-qazana-device-mode', self.getCurrentDeviceMode() );
		};

		var initElements = function() {
			elements.window = window;

			elements.$window = $( window );

			elements.$document = $( document );

			elements.$body = $( 'body' );

			elements.$qazana = elements.$document.find( '.qazana' );
		};

		var bindEvents = function() {
			elements.$window.on( 'resize', setDeviceModeData );
		};

		var initOnReadyComponents = function() {
			self.utils = {
				youtube: new YouTubeModule(),
				anchors: new AnchorsModule(),
				//lightbox: new LightboxModule()
				//carousel: new CarouselModule()
			};

			self.modules = {
				StretchElement: require( 'qazana-frontend/modules/stretch-element' )
			};

			self.elementsHandler = new ElementsHandler( $ );
		};

		var initHotKeys = function() {
			self.hotKeys = require( 'qazana-utils/hot-keys' );

			self.hotKeys.bindListener( elements.$window );
		};

		var getSiteSettings = function( settingType, settingName ) {
			var settingsObject = self.isEditMode() ? qazana.settings[ settingType ].model.attributes : self.config.settings[ settingType ];

			if ( settingName ) {
				return settingsObject[ settingName ];
			}

			return settingsObject;
		};

		this.init = function() {
			self.hooks = new EventManager();

			initElements();

			bindEvents();

			setDeviceModeData();

			elements.$window.trigger( 'qazana/frontend/init' );

			if ( ! self.isEditMode() ) {
				initHotKeys();
			}

			initOnReadyComponents();
		};

		this.getElements = function( element ) {
			if ( element ) {
				return elements[ element ];
			}

			return elements;
		};

		this.getDialogsManager = function() {
			if ( ! dialogsManager ) {
				dialogsManager = new DialogsManager.Instance();
			}

			return dialogsManager;
		};

		this.getPageSettings = function( settingName ) {
			return getSiteSettings( 'page', settingName );
		};

		this.getGeneralSettings = function( settingName ) {
			return getSiteSettings( 'general', settingName );
		};

		this.isEditMode = function() {
			return self.config.isEditMode;
		};

		// Based on underscore function
		this.throttle = function( func, wait ) {
			var timeout,
				context,
				args,
				result,
				previous = 0;

			var later = function() {
				previous = Date.now();
				timeout = null;
				result = func.apply( context, args );

				if ( ! timeout ) {
					context = args = null;
				}
			};

			return function() {
				var now = Date.now(),
					remaining = wait - ( now - previous );

				context = this;
				args = arguments;

				if ( remaining <= 0 || remaining > wait ) {
					if ( timeout ) {
						clearTimeout( timeout );
						timeout = null;
					}

					previous = now;
					result = func.apply( context, args );

					if ( ! timeout ) {
						context = args = null;
					}
				} else if ( ! timeout ) {
					timeout = setTimeout( later, remaining );
				}

				return result;
			};
		};

		this.addListenerOnce = function( listenerID, event, callback, to ) {
			if ( ! to ) {
				to = self.getElements( '$window' );
			}

			if ( ! self.isEditMode() ) {
				to.on( event, callback );

				return;
			}

			if ( to instanceof jQuery ) {
				var eventNS = event + '.' + listenerID;

				to.off( eventNS ).on( eventNS, callback );
			} else {
				to.off( event, null, listenerID ).on( event, callback, listenerID );
			}
		};

		this.getCurrentDeviceMode = function() {
			return getComputedStyle( elements.$qazana[ 0 ], ':after' ).content.replace( /"/g, '' );
		};

		this.waypoint = function( $element, callback, options ) {
			var correctCallback = function() {
				var element = this.element || this;

				return callback.apply( element, arguments );
			};

			return $element.qazanaWaypoint( correctCallback, options );
		};
	};

	window.qazanaFrontend = new QazanaFrontend();
} )( jQuery );

if ( ! qazanaFrontend.isEditMode() ) {
	jQuery( qazanaFrontend.init );
}
