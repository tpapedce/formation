<?php

namespace OCFram;
class RouterFactory {
	protected static $Router_a = [];
	
	/**
	 * @return Router
	 */
	public static function getRouter( $application_name ) {
		self::buildRouter( $application_name );
		
		return self::$Router_a[ $application_name ];
	}
	
	/**
	 * @param $application_name
	 */
	private static function buildRouter( $application_name ) {
		if ( isset( self::$Router_a[ $application_name ] ) ) {
			return;
		}
		$router = new Router;
		
		$xml = new \DOMDocument;
		$xml->load( __DIR__ . '/../../App/' . $application_name . '/Config/routes.xml' );
		
		$routes = $xml->getElementsByTagName( 'route' );
		
		// On parcourt les routes du fichier XML.
		foreach ( $routes as $route ) {
			$vars = [];
			
			// On regarde si des variables sont présentes dans l'URL.
			if ( $route->hasAttribute( 'vars' ) ) {
				$vars = explode( ',', $route->getAttribute( 'vars' ) );
			}
			
			// On ajoute la route au routeur.
			$router->addRoute( new Route( $route->getAttribute( 'url' ), $route->getAttribute( 'module' ), $route->getAttribute( 'action' ), $vars ) );
		}
		
		self::$Router_a[ $application_name ] = $router;
	}
}