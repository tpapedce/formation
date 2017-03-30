<?php
namespace OCFram;

class Page extends ApplicationComponent {
	protected $contentFile;
	protected $vars = [];
	
	public function addVar( $var, $value ) {
		if ( !is_string( $var ) || is_numeric( $var ) || empty( $var ) ) {
			throw new \InvalidArgumentException( 'Le nom de la variable doit être une chaine de caractères non nulle' );
		}
		
		$this->vars[ $var ] = $value;
	}
	
	/** fonction qui définit quelle type de page générer */
	public function getGeneratedPage() {
		if ( !file_exists( $this->contentFile ) ) {
			throw new \RuntimeException( 'La vue spécifiée n\'existe pas' );
		}
		
		// si le request demande du json
		if ( ( preg_match( "^application/json^", $this->app()->httpRequest()->requestHTTPAccept() ) ) == 1 ) {
			return $this->getGeneratedPageJson();
		}
		else {
			return $this->getGeneratedPageHtml();
		}
	}
	
	/** fonction qui génère la page "JSON" (renvoie un objet JSON) */
	public function getGeneratedPageJson() {
		$this->app()->httpResponse()->addHeader( 'Content-Type: application/json' );
		extract( $this->vars );
		
		// le content = le return de la vue JSON
		$content = require $this->contentFile;
		
		return json_encode( require __DIR__ . '/../../App/' . $this->app->name() . '/Templates/layoutJson.php' );
	}
	
	/** fonction qui génère la page HTML */
	public function getGeneratedPageHtml() {
		$user = $this->app->user();
		
		extract( $this->vars );
		
		ob_start();
		require $this->contentFile;
		$content = ob_get_clean();
		
		ob_start();
		require __DIR__ . '/../../App/' . $this->app->name() . '/Templates/layout.php';
		
		return ob_get_clean();
	}
	
	public function setContentFile( $contentFile ) {
		if ( !is_string( $contentFile ) || empty( $contentFile ) ) {
			throw new \InvalidArgumentException( 'La vue spécifiée est invalide' );
		}
		
		$this->contentFile = $contentFile;
	}
}