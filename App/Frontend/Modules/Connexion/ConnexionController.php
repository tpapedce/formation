<?php
namespace App\Backend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;

class ConnexionController extends BackController
{
	public function executeInscription(HTTPRequest $request)
	{
		$this->page->addVar('title', 'Inscription');
		
		echo 'oui';
	}

}