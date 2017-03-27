<?php
namespace App\Backend\Modules\Connexion;

use Entity\Member;
use OCFram\Application;
use \OCFram\BackController;
use \OCFram\HTTPRequest;

class ConnexionController extends BackController
{
	public function executeIndex(HTTPRequest $request)
	{
		$this->page->addVar('title', 'Connexion');
		
		if ($request->postExists('login'))
		{
			$login = $request->postData('login');
			$password = $request->postData('password');
			
			$member_manager = $this->managers->getManagerOf('Member');
			$member = $member_manager->getMemberUsingLogin($login);
			
			if (!$member) {
				$this->app->user()->setFlash( 'Le pseudo n\'existe pas.' );
				
				return;
			}
			
			if (null === $password || '' === $password) {
				$this->app->user()->setFlash('Veuillez rentrer votre mot de passe.');
				
				return;
			}
			
			if ($member->password() !== $password ) {
				$this->app->user()->setFlash('Le pseudo ou le mot de passe est incorrect.');
				
				return;
			}
			
			self::initMemberSession($this->app,$member);
			
			if(2 == $member->status()) {
				$this->app->httpResponse()->redirect('.');
			}
			
			if(1 == $member->status()) {
				$this->app->httpResponse()->redirect('/');
			}
		}
	}
	
	public static function initMemberSession(Application $App, Member $Member) {
		
		$App->user()->setAuthenticated(true);
		$App->user()->setAttribute('Member', $Member);
	}
	
	public function executeLogout()
	{
		session_unset();
		session_destroy();
		$this->app->httpResponse()->redirect('/');
	}
	
	public static function getLinkToLogout() {
		return \OCFram\RouterFactory::getRouter( 'Backend' )->getUrl( 'Connexion', 'logout' );
	}
	
}