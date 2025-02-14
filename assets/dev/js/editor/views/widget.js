var BaseElementView = require( 'qazana-views/base-element' ),
	WidgetView;

WidgetView = BaseElementView.extend( {
	_templateType: null,

	getTemplate: function() {
		var editModel = this.getEditModel();

		if ( 'remote' !== this.getTemplateType() ) {
			return Marionette.TemplateCache.get( '#tmpl-qazana-' + editModel.get( 'elType' ) + '-' + editModel.get( 'widgetType' ) + '-content' );
		} else {
			return _.template( '' );
		}
	},

	className: function() {
		return BaseElementView.prototype.className.apply( this, arguments ) + ' qazana-widget';
	},

	events: function() {
		var events = BaseElementView.prototype.events.apply( this, arguments );

		events.click = 'onClickEdit';

		return events;
	},

	initialize: function() {
		BaseElementView.prototype.initialize.apply( this, arguments );

		var editModel = this.getEditModel();

		editModel.on( {
			'before:remote:render': _.bind( this.onModelBeforeRemoteRender, this ),
			'remote:render': _.bind( this.onModelRemoteRender, this )
		} );

		if ( 'remote' === this.getTemplateType() && ! this.getEditModel().getHtmlCache() ) {
			editModel.renderRemoteServer();
		}

		var onRenderMethod = this.onRender;

		this.render = _.throttle( this.render, 300 );

		this.onRender = function() {
			_.defer( _.bind( onRenderMethod, this ) );
		};
	},

	render: function() {
		if ( this.model.isRemoteRequestActive() ) {
			this.handleEmptyWidget();

			this.$el.addClass( 'qazana-element' );

			return;
		}

		Marionette.CompositeView.prototype.render.apply( this, arguments );
	},

	handleEmptyWidget: function() {
		// TODO: REMOVE THIS !!
		// TEMP CODING !!
		this.$el
			.addClass( 'qazana-widget-empty' )
			.append( '<i class="qazana-widget-empty-icon ' + this.getEditModel().getIcon() + '"></i>' );
	},

	getTemplateType: function() {
		if ( null === this._templateType ) {
			var editModel = this.getEditModel(),
				$template = Backbone.$( '#tmpl-qazana-' + editModel.get( 'elType' ) + '-' + editModel.get( 'widgetType' ) + '-content' );

			this._templateType = $template.length ? 'js' : 'remote';
		}

		return this._templateType;
	},

	onModelBeforeRemoteRender: function() {
		this.$el.addClass( 'qazana-loading' );
	},

	onBeforeDestroy: function() {
		// Remove old style from the DOM.
		qazana.$previewContents.find( '#qazana-style-' + this.model.cid ).remove();
	},

	onModelRemoteRender: function() {
		if ( this.isDestroyed ) {
			return;
		}

		this.$el.removeClass( 'qazana-loading' );
		this.render();
	},

	getHTMLContent: function( html ) {
		var htmlCache = this.getEditModel().getHtmlCache();

		return htmlCache || html;
	},

	attachElContent: function( html ) {
		var self = this,
			htmlContent = self.getHTMLContent( html );

		_.defer( function() {
			qazanaFrontend.getElements( 'window' ).jQuery( self.el ).html( htmlContent );

			self.bindUIElements(); // Build again the UI elements since the content attached just now
		} );

		return this;
	},

	onRender: function() {
        var self = this;

		BaseElementView.prototype.onRender.apply( self, arguments );

	    var editModel = self.getEditModel(),
	        skinType = editModel.getSetting( '_skin' ) || 'default';

        self.$el
	        .attr( 'data-element_type', editModel.get( 'widgetType' ) + '.' + skinType )
            .removeClass( 'qazana-widget-empty' )
	        .addClass( 'qazana-widget-' + editModel.get( 'widgetType' ) + ' qazana-widget-can-edit' )
            .children( '.qazana-widget-empty-icon' )
            .remove();

		// TODO: Find better way to detect if all images are loaded
		self.$el.imagesLoaded().always( function() {
			setTimeout( function() {
				if ( 1 > self.$el.height() ) {
					self.handleEmptyWidget();
				}
			}, 200 );
			// Is element empty?
		} );
	}
} );

module.exports = WidgetView;
