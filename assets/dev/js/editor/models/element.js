var BaseSettingsModel = require( 'qazana-models/base-settings' ),
	WidgetSettingsModel = require( 'qazana-models/widget-settings' ),
	ColumnSettingsModel = require( 'qazana-models/column-settings' ),
	SectionSettingsModel = require( 'qazana-models/section-settings' ),

	ElementModel,
	ElementCollection;

ElementModel = Backbone.Model.extend( {
	defaults: {
		id: '',
		elType: '',
		isInner: false,
		settings: {},
		defaultEditSettings: {}
	},

	remoteRender: false,
	_htmlCache: null,
	_jqueryXhr: null,
	renderOnLeave: false,

	initialize: function( options ) {
		var elType = this.get( 'elType' ),
			elements = this.get( 'elements' );

		if ( undefined !== elements ) {
			this.set( 'elements', new ElementCollection( elements ) );
		}

		if ( 'widget' === elType ) {
			this.remoteRender = true;
			this.setHtmlCache( options.htmlCache || '' );
		}

		// No need this variable anymore
		delete options.htmlCache;

		// Make call to remote server as throttle function
		this.renderRemoteServer = _.throttle( this.renderRemoteServer, 1000 );

		this.initSettings();

		this.initEditSettings();

		this.on( {
			destroy: this.onDestroy,
			'editor:close': this.onCloseEditor
		} );
	},

	initSettings: function() {
		var elType = this.get( 'elType' ),
			settings = this.get( 'settings' ),
			settingModels = {
				widget: WidgetSettingsModel,
				column: ColumnSettingsModel,
				section: SectionSettingsModel
			},
			SettingsModel = settingModels[ elType ] || BaseSettingsModel;

		if ( Backbone.$.isEmptyObject( settings ) ) {
			settings = qazana.helpers.cloneObject( settings );
		}

		if ( 'widget' === elType ) {
			settings.widgetType = this.get( 'widgetType' );
		}

		settings.elType = elType;
		settings.isInner = this.get( 'isInner' );

		settings = new SettingsModel( settings );

		this.set( 'settings', settings );

		qazanaFrontend.config.elements.data[ this.cid ] = settings;
	},

	initEditSettings: function() {
		var editSettings = new Backbone.Model( this.get( 'defaultEditSettings' ) );

		this.set( 'editSettings', editSettings );

		qazanaFrontend.config.elements.editSettings[ this.cid ] = editSettings;
	},

	onDestroy: function() {
		// Clean the memory for all use instances
		var settings = this.get( 'settings' ),
			elements = this.get( 'elements' );

		if ( undefined !== elements ) {
			_.each( _.clone( elements.models ), function( model ) {
				model.destroy();
			} );
		}

		if ( settings instanceof BaseSettingsModel ) {
			settings.destroy();
		}
	},

	onCloseEditor: function() {
		this.initEditSettings();

		if ( this.renderOnLeave ) {
			this.renderRemoteServer();
		}
	},

	setSetting: function( key, value ) {
		this.get( 'settings' ).setExternalChange( key, value );
	},

	getSetting: function( key ) {
		var settings = this.get( 'settings' );

		if ( undefined === settings.get( key ) ) {
			return '';
		}

		return settings.get( key );
	},

	setHtmlCache: function( htmlCache ) {
		this._htmlCache = htmlCache;
	},

	getHtmlCache: function() {
		return this._htmlCache;
	},

	getTitle: function() {
		var elementData = qazana.getElementData( this );

		return ( elementData ) ? elementData.title : 'Unknown';
	},

	getIcon: function() {
		var elementData = qazana.getElementData( this );

		return ( elementData ) ? elementData.icon : 'unknown';
	},

	createRemoteRenderRequest: function() {
		var data = this.toJSON();

		return qazana.ajax.send( 'render_widget', {
			data: {
				post_id: qazana.config.post_id,
				data: JSON.stringify( data ),
				_nonce: qazana.config.nonce
			},
			success: _.bind( this.onRemoteGetHtml, this )
		} );
	},

	renderRemoteServer: function() {
		if ( ! this.remoteRender ) {
			return;
		}

		this.renderOnLeave = false;

		this.trigger( 'before:remote:render' );

		if ( this.isRemoteRequestActive() ) {
			this._jqueryXhr.abort();
		}

		this._jqueryXhr = this.createRemoteRenderRequest();
	},

	isRemoteRequestActive: function() {
		return this._jqueryXhr && 4 !== this._jqueryXhr.readyState;
	},

	onRemoteGetHtml: function( data ) {
		this.setHtmlCache( data.render );
		this.trigger( 'remote:render' );
	},

	clone: function() {
		var newModel = new this.constructor( qazana.helpers.cloneObject( this.attributes ) );

		newModel.set( 'id', qazana.helpers.getUniqueID() );

		newModel.setHtmlCache( this.getHtmlCache() );

		var elements = this.get( 'elements' );

		if ( ! _.isEmpty( elements ) ) {
			newModel.set( 'elements', elements.clone() );
		}

		return newModel;
	},

	toJSON: function( options ) {
		options = _.extend( { copyHtmlCache: false }, options );

		// Call parent's toJSON method
		var data = Backbone.Model.prototype.toJSON.call( this );

		_.each( data, function( attribute, key ) {
			if ( attribute && attribute.toJSON ) {
				data[ key ] = attribute.toJSON( options );
			}
		} );

		if ( options.copyHtmlCache ) {
			data.htmlCache = this.getHtmlCache();
		} else {
			delete data.htmlCache;
		}

		return data;
	}

} );

ElementCollection = Backbone.Collection.extend( {
	add: function( models, options, isCorrectSet ) {
		if ( ( ! options || ! options.silent ) && ! isCorrectSet ) {
			throw 'Call Error: Adding model to element collection is allowed only by the dedicated addChildModel() method.';
		}

		return Backbone.Collection.prototype.add.call( this, models, options );
	},

	model: function( attrs, options ) {
		var ModelClass = Backbone.Model;

		if ( attrs.elType ) {
			ModelClass = qazana.hooks.applyFilters( 'element/model', ElementModel, attrs );
		}

		return new ModelClass( attrs, options );
	},

	clone: function() {
		var tempCollection = Backbone.Collection.prototype.clone.apply( this, arguments ),
			newCollection = new ElementCollection();

		tempCollection.forEach( function( model ) {
			newCollection.add( model.clone(), null, true );
		} );

		return newCollection;
	}
} );

ElementCollection.prototype.sync = function() {
	return null;
};

ElementCollection.prototype.fetch = function() {
	return null;
};

ElementCollection.prototype.save = function() {
	return null;
};

ElementModel.prototype.sync = function() {
	return null;
};
ElementModel.prototype.fetch = function() {
	return null;
};
ElementModel.prototype.save = function() {
	return null;
};

module.exports = {
	Model: ElementModel,
	Collection: ElementCollection
};
