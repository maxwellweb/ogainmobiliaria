var nfRadio = Backbone.Radio;

function nf_recaptcha_response( response ) {
	nfRadio.channel( 'recaptcha' ).request( 'update:response', response );
}