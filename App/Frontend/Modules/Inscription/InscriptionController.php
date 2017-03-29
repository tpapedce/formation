<?php
namespace App\Frontend\Modules\Inscription;

use App\Backend\Modules\Connexion\ConnexionController;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \FormBuilder\MemberFormBuilder;
use \OCFram\FormHandler;

class InscriptionController extends BackController
{
	public function executeInscription(HTTPRequest $request)
	{
		if ($request->method() == 'POST')
		{
			$member = new Member([
				'user' => $request->postData('user'),
				'password' => $request->postData('password'),
				'email' => $request->postData('email'),
				'status' => 1
			]);
		}
		else
		{
			$member = new Member;
		}
		
		$formBuilder = new MemberFormBuilder($member, $this);
		$formBuilder->build();
		
		$form = $formBuilder->form();
		$formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Member'), $request);
		
		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Le membre a bien été ajouté !');
			ConnexionController::initMemberSession($this->app,$member);
			
			$this->app->httpResponse()->redirect('.');
		}
		
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('title', 'Inscription');
	}
	
	public static function getLinkToInscription() {
		return \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'Inscription', 'inscription' );
	}

}