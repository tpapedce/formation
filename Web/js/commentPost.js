function escapeHtml( text ) {
	var map = {
		'&' : '&amp;',
		'<' : '&lt;',
		'>' : '&gt;',
		'"' : '&quot;',
		"'" : '&#039;'
	};
	
	return text.replace( /[&<>"']/g, function( m ) {
		return map[ m ];
	} );
}

function cleanForm( form ) {
	$( ':input', form )
		.not( ':button, :submit, :reset, :hidden' )
		.val( '' );
}

$( document ).ready( function() {
	
	// Lorsque je soumets le formulaire
	$( '.js-comment-form' ).on( 'submit', function( e ) {
		e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
		var $this = $( this ); // L'objet jQuery du formulaire
		
		// Je récupère les valeurs
		var varcontenu = $( "#commentPost [name='contenu']" ).val();
		var varauteur  = $( "#commentPost [name='auteur']" ).val();
		// Je vérifie une première fois pour ne pas lancer la requête HTTP
		// si je sais que mon PHP renverra une erreur
		if ( '' === varcontenu || '' === varauteur ) {
			alert( 'Les champs doivent êtres remplis' );
		}
		else {
			// Envoi de la requête HTTP en mode asynchrone
			$.ajax( {
				url        : $this.attr( 'data-ajax-action' ), // Le nom du fichier indiqué dans le formulaire
				type       : $this.attr( 'method' ), // La méthode indiquée dans le formulaire (get ou post)
				data       : $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
				dataType   : "json",
				beforeSend : function() {
					$( "#submitComment" ).prop( 'disabled', true ); // disable button
					
				},
				success    : function( data ) {
					if ( 'success' == data.content.result ) {
						console.log(data.content.comment.auteur );
						cleanForm( '.js-comment-form' );
						if ( 'true' == data.content.isConnected ) {
							$href = ' - <a href="' + data.content.linkUpdate + '">Modifier</a> | <a href="' + data.content.linkDelete + '">Supprimer</a>';
						}
						else {
							$href = '';
						}
						if (null === data.content.comment.auteur) {
							$auteur = data.content.comment.fk_MMC.user;
						}
						else {
							$auteur = data.content.comment.auteur;
						}
						$( "#comment" )
							.prepend( '<fieldset> <legend>Posté par <strong>' + escapeHtml($auteur) + '</strong> le ' + data.content.comment.date + $href + '</legend><p class="comment">' + escapeHtml( data.content.comment.contenu ) + '</p></fieldset>' );
						$( "#submitComment" ).prop( 'disabled', false ); // enable button
					}
					else {
						if ( 'error' == data.content.result ) {
							cleanForm( '.js-comment-form' );
							alert( 'Le commentaire n\'a pas pû être posté.' );
							$( "#submitComment" ).prop( 'disabled', false );
						}
					}
				},
				error      : function() {
					cleanForm( '.js-comment-form' );
					alert( 'Erreur lors du post de commentaire.' );
					$( "#submitComment" ).prop( 'disabled', false );
					
				}
			} );
		}
	} );
} );


	
