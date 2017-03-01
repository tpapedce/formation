<?php
namespace App\Frontend\Modules\Inscription;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \FormBuilder\MemberFormBuilder;
use \OCFram\FormHandler;

class InscriptionController extends BackController
{
	public function executeInscription(HTTPRequest $request)
	{
		$this->page->addVar('title', 'Inscription');
		
		echo 'oui';
	}
	
	public function processForm(HTTPRequest $request)
	{
		if ($request->method() == 'POST')
		{
			$member = new Member([
				'MMC_user' => $request->postData('username'),
				'MMC_password' => $request->postData('password'),
				'MMC_email' => $request->postData('email')
			]);
		}
		else
		{
			// L'identifiant de la news est transmis si on veut la modifier
			if ($request->getExists('MMC_id'))
			{
				$member = $this->managers->getManagerOf('Inscription')->getUnique($request->getData('MMC_id'));
			}
			else
			{
				$member = new Member;
			}
		}
		
		$formBuilder = new NewsFormBuilder($member);
		$formBuilder->build();
		
		$form = $formBuilder->form();
		$formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Member'), $request);
		
		if ($formHandler->process())
		{
			$this->app->user()->setFlash($member->isNew() ? 'Le membre a bien été ajouté !' : 'Le membre a bien été modifié !');
			$this->app->httpResponse()->redirect('/admin/');
		}
		
		$this->page->addVar('form', $form->createView());
	}

}