<?php
namespace OCFram;

abstract class Application {
	protected $httpRequest;
	protected $httpResponse;
	protected $name;
	protected $user;
	protected $config;
	
	public function __construct() {
		$this->httpRequest  = new HTTPRequest( $this );
		$this->httpResponse = new HTTPResponse( $this );
		$this->user         = new User( $this );
		$this->config       = new Config( $this );
		
		$this->name = '';
	}
	
	public function getController() {
		$router = RouterFactory::getRouter( $this->name() );
		
		try {
			// On récupère la route correspondante à l'URL.
			$matchedRoute = $router->getRoute( $this->httpRequest->requestURI() );
		}
		catch ( \RuntimeException $e ) {
			if ( $e->getCode() == Router::NO_ROUTE ) {
				// Si aucune route ne correspond, c'est que la page demandée n'existe pas.
				$this->httpResponse->redirect404();
			}
		}
		
		// On ajoute les variables de l'URL au tableau $_GET.
		$_GET = array_merge( $_GET, $matchedRoute->vars() );
		
		// On instancie le contrôleur.
		$controllerClass = 'App\\' . $this->name . '\\Modules\\' . $matchedRoute->module() . '\\' . $matchedRoute->module() . 'Controller';
		
		return new $controllerClass( $this, $matchedRoute->module(), $matchedRoute->action() );
	}
	
	abstract public function run();
	
	public function httpRequest() {
		return $this->httpRequest;
	}
	
	public function httpResponse() {
		return $this->httpResponse;
	}
	
	public function name() {
		return $this->name;
	}
	
	public function config() {
		return $this->config;
	}
	
	public function user() {
		return $this->user;
	}
	
	abstract public function getMenu();
	
	abstract public function generateHeader();
	
	abstract public function generateMenu();
}