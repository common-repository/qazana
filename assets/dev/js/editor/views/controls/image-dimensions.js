var ControlMultipleBaseItemView = require( 'qazana-views/controls/base-multiple' ),
	ControlImageDimensionsItemView;

ControlImageDimensionsItemView = ControlMultipleBaseItemView.extend( {
	ui: function() {
		return {
			inputWidth: 'input[data-setting="width"]',
			inputHeight: 'input[data-setting="height"]',

			btnApply: 'button.qazana-image-dimensions-apply-button'
		};
	},

	// Override the base events
	baseEvents: {
		'click @ui.btnApply': 'onApplyClicked'
	},

	onApplyClicked: function( event ) {
		event.preventDefault();

		this.setValue( {
			width: this.ui.inputWidth.val(),
			height: this.ui.inputHeight.val()
		} );
	}
} );

module.exports = ControlImageDimensionsItemView;
