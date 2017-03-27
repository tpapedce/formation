<?php
namespace App\Frontend\Modules\News;

use Entity\Member;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \Entity\News;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;

class NewsController extends BackController {
	
	public function executeIndex( HTTPRequest $request ) {
		$nombreNews       = $this->app->config()->get( 'nombre_news' );
		$nombreCaracteres = $this->app->config()->get( 'nombre_caracteres' );
		
		// On ajoute une définition pour le titre.
		$this->page->addVar( 'title', 'Liste des ' . $nombreNews . ' dernières news' );
		
		// On récupère le manager des news.
		$manager = $this->managers->getManagerOf( 'News' );
		
		$listeNews = $manager->getList( 0, $nombreNews );
		
		foreach ( $listeNews as $news ) {
			if ( strlen( $news->contenu() ) > $nombreCaracteres ) {
				$debut = substr( $news->contenu(), 0, $nombreCaracteres );
				$debut = substr( $debut, 0, strrpos( $debut, ' ' ) ) . '...';
				
				$news->setContenu( $debut );
			}
		}
		
		// On ajoute la variable $listeNews à la vue.
		$this->page->addVar( 'listeNews', $listeNews );
	}
	
	public function executeShow( HTTPRequest $request ) {
		$news = $this->managers->getManagerOf( 'News' )->getUnique( $request->getData( 'id' ) );
		
		if ( empty( $news ) ) {
			$this->app->httpResponse()->redirect404();
		}
		
		$this->page->addVar( 'title', htmlspecialchars ($news->titre()) );
		$this->page->addVar( 'news', $news );
		$this->page->addVar( 'comments', $this->managers->getManagerOf( 'Comments' )->getListOf( $news->id() ) );
		
		$this->executeInsertComment($request);
	}
	
	public function executeInsertComment(HTTPRequest $request)
	{
		// Si le formulaire a été envoyé.
		if ($request->method() == 'POST')
		{
			/** @var Member|null $member */
			$member = $this->app->user()->getAttribute('Member');
			if (null === $member){
				$comment = new Comment([
					'news' => ($request->getData('id')),
					'auteur' => $request->postData('auteur'),
					'contenu' => $request->postData('contenu')
				]);
			}
			else{
				$comment = new Comment([
					'news' => $request->getData('id'),
					'auteur' => $member->id(),
					'contenu' => $request->postData('contenu')
				]);
			}
			
		}
		else
		{
			$comment = new Comment;
		}
		
		$formBuilder = new CommentFormBuilder($comment, $this->app->user()->getAttribute('Member'));
		$formBuilder->build();
		
		$form = $formBuilder->form();
		
		$formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
		
		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');
			$this->app->httpResponse()->redirect('news-'.$request->getData('id').'.html');
		}
		
		$this->page->addVar('comment', $comment);
		$this->page->addVar('form', $form->createView());
		//$this->page->addVar('title', 'Ajout d\'un commentaire');
	}
	
	public function executeDelete( HTTPRequest $request )
	{
		$newsId = $request->getData('id');
		
		$this->managers->getManagerOf('News')->delete($newsId);
		$this->managers->getManagerOf('Comments')->deleteFromNews($newsId);
		
		$this->app->user()->setFlash('La news a bien été supprimée !');
		
		$this->app->httpResponse()->redirect('/');
	}
	
	public static function getLinkToNewsIndex() {
		return \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'index');
	}
	
	public static function getLinkToNewsShow(News $news) {
		$vars = array("id" => $news->id());
		return \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'show', $vars);
	}
	
	public static function getLinkToInsertComment(News $news) {
		$vars = array("id" => $news->id());
		return \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertComment', $vars);
	}
}