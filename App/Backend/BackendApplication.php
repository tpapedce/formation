<?php
namespace App\Backend;

use App\Backend\Modules\Connexion\ConnexionController;
use App\Frontend\Modules\Inscription\InscriptionController;
use App\Frontend\Modules\News\NewsController;
use \OCFram\Application;

class BackendApplication extends Application {
	public function __construct() {
		parent::__construct();
		
		$this->name = 'Backend';
	}
	
	public function run() {
		if ( $this->user->isAuthenticated() ) {
			$controller = $this->getController();
		}
		else {
			$controller = new Modules\Connexion\ConnexionController( $this, 'Connexion', 'index' );
		}
		
		$controller->execute();
		
		$this->httpResponse->setPage( $controller->page() );
		$this->httpResponse->page()->addvar('string', $this->generateMenu());
		$this->httpResponse->send();
	}
	
	/**
	 * Fonction qui retourne un tableau avec les différentes parties du menu
	 * @return array
	 */
	public function getMenu(){
		$menu_a['Accueil'] =  NewsController::getLinkToNewsIndex();
		$menu_a['Ajouter une news'] = \App\Backend\Modules\News\NewsController::getLinkToInsertNews();
		$menu_a['Se déconnecter'] = ConnexionController::getLinkToLogout();
				//si l'utilisateur est un admin
		if ($this->user->getAttribute('Member')['status']== 2){
			$menu_a['Admin'] = \App\Backend\Modules\News\NewsController::getLinkToAdmin();
		}
		return $menu_a;
	}
	
	/**
	 * fonction qui génère le code html du menu
	 * @return string
	 */
	public function generateMenu() {
		$menu_a = $this->getMenu();
		$string = '';
		foreach ($menu_a as $key => $value){
			$string .= '<li><a href="'. $value .'">'. $key .'</a></li>';
		}
		return $string;
	}
}