var heartbeat;

heartbeat = {

	init: function() {
		var modal;

		this.getModal = function() {
			if ( ! modal ) {
				modal = this.initModal();
			}

			return modal;
		};

		Backbone.$( document ).on( {
			'heartbeat-send': function( event, data ) {
				data.qazana_post_lock = {
					post_ID: qazana.config.post_id
				};
			},
			'heartbeat-tick': function( event, response ) {
				if ( response.locked_user ) {
					if ( qazana.isEditorChanged() ) {
						qazana.saveEditor( { status: 'autosave' } );
					}

					heartbeat.showLockMessage( response.locked_user );
				} else {
					heartbeat.getModal().hide();
				}

				qazana.config.nonce = response.qazanaNonce;
			},
			'heartbeat-tick.wp-refresh-nonces': function( event, response ) {
				var nonces = response['qazana-refresh-nonces'];

				if ( nonces ) {
					if ( nonces.heartbeatNonce ) {
						qazana.config.nonce = nonces.qazanaNonce;
					}

					if ( nonces.heartbeatNonce ) {
						window.heartbeatSettings.nonce = nonces.heartbeatNonce;
					}
				}
			}
		} );

		if ( qazana.config.locked_user ) {
			heartbeat.showLockMessage( qazana.config.locked_user );
		}
    },

	initModal: function() {
		var modal = qazana.dialogsManager.createWidget( 'options', {
			headerMessage: qazana.translate( 'take_over' )
		} );

		modal.addButton( {
			name: 'go_back',
			text: qazana.translate( 'go_back' ),
			callback: function() {
				parent.history.go( -1 );
			}
		} );

		modal.addButton( {
			name: 'take_over',
			text: qazana.translate( 'take_over' ),
			callback: function() {
				wp.heartbeat.enqueue( 'qazana_force_post_lock', true );
				wp.heartbeat.connectNow();
			}
		} );

		return modal;
	},

	showLockMessage: function( lockedUser ) {
		var modal = heartbeat.getModal();

		modal
			.setMessage( qazana.translate( 'dialog_user_taken_over', [ lockedUser ] ) )
		    .show();
	}
};

module.exports = heartbeat;
