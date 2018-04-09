var BaseSettings = require( 'elementor-editor/settings/base/manager' );

module.exports = BaseSettings.extend( {
	changeCallbacks: {
		post_title: function( newValue ) {
			var $title = elementorFrontend.getElements( '$document' ).find( elementor.config.page_title_selector );

			$title.text( newValue );
		},

		template: function() {
			this.save( function() {
				elementor.reloadPreview();

				elementor.once( 'preview:loaded', function() {
					elementor.getPanelView().setPage( 'page_settings' );
				} );
			} );
		}
	},

	getDataToSave: function( data ) {
		data.id = elementor.config.post_id;

		return data;
	}
} );
