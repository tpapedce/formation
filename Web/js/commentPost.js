$( document ).ready( function() {
	
	// Lorsque je soumets le formulaire
	$( '.js-comment-form' ).on( 'submit', function( e ) {
		e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
		console.log( 'ICI' );
		var $this = $( this ); // L'objet jQuery du formulaire
		
		// Je récupère les valeurs
		var contenu = $( "#commentPost [name='contenu']" ).val();
		
		// Je vérifie une première fois pour ne pas lancer la requête HTTP
		// si je sais que mon PHP renverra une erreur
		if ( '' === contenu ) {
			alert( 'Les champs doivent êtres remplis' );
		}
		else {
			// Envoi de la requête HTTP en mode asynchrone
			$.ajax( {
				url      : $this.attr( 'data-ajax-action' ), // Le nom du fichier indiqué dans le formulaire
				type     : $this.attr( 'method' ), // La méthode indiquée dans le formulaire (get ou post)
				data     : $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
				dataType : "json",
				success  : function( data ) {
					// data success data error ajouter un commentaire sur la page
					if ( 'success' == data.result ) {
						alert( 'Le commentaire a bien été posté.' );
						//TODO: AJOUTER LE COMMENTAIRE A LA PAGE
						$("#comment").prepend('<p>HAHAHA</p>');
						// fonction APPEND
						// rajouter le commentaire au début du div "commentaire"
						// ajouter du code html
					}
					else {
						if ( 'error' == data.result ) {
							alert( 'Le commentaire n\'a pas pû être posté.' );
						}
					}
				},
				error    : function() {
					alert( 'Erreur lors du post de commentaire.' );
				}
			} );
		}
	} );
} );


	
