var RepeaterRowView;

RepeaterRowView = Marionette.CompositeView.extend( {
	template: Marionette.TemplateCache.get( '#tmpl-elementor-repeater-row' ),

	className: 'repeater-fields',

	ui: {
		duplicateButton: '.elementor-repeater-tool-duplicate',
		editButton: '.elementor-repeater-tool-edit',
		removeButton: '.elementor-repeater-tool-remove',
		itemTitle: '.elementor-repeater-row-item-title'
	},

	behaviors: {
		HandleInnerTabs: {
			behaviorClass: require( 'elementor-behaviors/inner-tabs' )
		}
	},

	triggers: {
		'click @ui.removeButton': 'click:remove',
		'click @ui.duplicateButton': 'click:duplicate',
		'click @ui.itemTitle': 'click:edit'
	},

	templateHelpers: function() {
		return {
			itemIndex: this.getOption( 'itemIndex' )
		};
	},

	childViewContainer: '.elementor-repeater-row-controls',

	getChildView: function( item ) {
		var controlType = item.get( 'type' );

		return elementor.getControlView( controlType );
	},

	childViewOptions: function() {
		return {
			elementSettingsModel: this.model
		};
	},

	checkConditions: function() {
		var self = this;

		self.collection.each( function( model ) {
			var conditions = model.get( 'conditions' ),
				parentConditions = model.get( 'parent_conditions' ),
				isVisible = true;

			if ( conditions ) {
				isVisible = elementor.conditions.check( conditions, self.model.attributes );
			}

			if ( parentConditions ) {
				isVisible = elementor.conditions.check( parentConditions, self.getOption( 'parentModel' ).attributes );
			}

			var child = self.children.findByModelCid( model.cid );

			child.$el.toggleClass( 'elementor-panel-hide', ! isVisible );
		} );
	},

	updateIndex: function( newIndex ) {
		this.itemIndex = newIndex;
		this.setTitle();
	},

	setTitle: function() {
		var self = this,
			titleField = self.getOption( 'titleField' ),
			title = '';

		if ( titleField ) {
			var values = {};

			self.children.each( function( child ) {
				values[ child.model.get( 'name' ) ] = child.getControlValue();
			} );

			title = Marionette.TemplateCache.prototype.compileTemplate( titleField )( values );
		}

		if ( ! title ) {
			title = elementor.translate( 'Item #{0}', [ self.getOption( 'itemIndex' ) ] );
		}

		self.ui.itemTitle.html( title );
	},

	initialize: function( options ) {
		var self = this;

		self.elementSettingsModel = options.elementSettingsModel;

		self.itemIndex = 0;

		// Collection for Controls list
		self.collection = new Backbone.Collection( options.controlFields );

		self.listenTo( self.model, 'change', self.checkConditions );
		self.listenTo( self.getOption( 'parentModel' ), 'change', self.checkConditions );

		if ( options.titleField ) {
			self.listenTo( self.model, 'change', self.setTitle );
		}
	},

	onRender: function() {
		this.setTitle();
		this.checkConditions();
	},

	onChildviewResponsiveSwitcherClick: function( childView, device ) {
		if ( 'desktop' === device ) {
			elementor.getPanelView().getCurrentPageView().$el.toggleClass( 'elementor-responsive-switchers-open' );
		}
	}
} );

module.exports = RepeaterRowView;
