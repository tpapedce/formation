<?php
namespace App\Frontend\Modules\News;

use Entity\Member;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\Form;
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
		
		$this->page->addVar( 'title', htmlspecialchars( $news->titre() ) );
		$this->page->addVar( 'news', $news );
		$this->page->addVar( 'comments', $this->managers->getManagerOf( 'Comments' )->getListOf( $news->id() ) );
		
		$this->page->addVar( 'form', ( new CommentFormBuilder( new Comment(), $this->app->user()->getAttribute( 'Member' ) ) )->build()->form()->createView() );
	}
	
	/**
	 * Traitement d'un formulaire commentaire
	 *
	 * @param HTTPRequest $request
	 */
	public function executeInsertComment( HTTPRequest $request ) {
		
		$News = $this->Managers()->getManagerOf( 'News' )->getUnique( $request->getData( 'id' ) );
		if ( !$News ) {
			$this->app->user()->setFlash( 'La news n\'existe pas !' );
			$this->app()->httpResponse()->redirect404();
		}
		$FormHandler = $this->buildCommentForm( $request );
		if ( $FormHandler->process() ) {
			$this->app->user()->setFlash( 'Le commentaire a bien été ajouté, merci !' );
			$this->app()->httpResponse()->redirect( self::getLinkToNewsShow( $News ) );
		}
		
		$this->page()->addVar( 'form', $FormHandler->form()->createView() );
		$this->page()->addVar( 'news', $News );
	}
	
	/**
	 * Traitement d'un formulaire d'ajout d'un commentaire depuis la fonction javascript de gestion d'ajout en ajax
	 *
	 * @param HTTPRequest $request
	 */
	public function executeInsertCommentAjax( HTTPRequest $request ) {
		
		$News = $this->Managers()->getManagerOf( 'News' )->getUnique( $request->getData( 'id' ) );
		if ( !$News ) {
			$retour[ 'result' ] = "error";
		}
		$FormHandler = $this->buildCommentForm( $request );
		if ( $FormHandler->process() ) {
			$retour[ 'result' ] = "success";
		}
		else {
			$retour[ 'result' ] = "error";
		}
		/** @var Comment|null $Comment */
		$Comment = $FormHandler->form()->entity();
		$Comment = $this->managers->getManagerOf( 'Comments' )->getUnique( $Comment->id() );
		if ( !$this->app()->user()->isAuthenticated() ) {
			$retour[ 'auteur' ]      = $Comment->auteur();
			$retour[ 'isConnected' ] = 'false';
		}
		else {
			$retour[ 'auteur' ]      = $this->app()->user()->getAttribute( 'Member' )->user();
			$retour[ 'isConnected' ] = 'true';
			$retour[ 'linkUpdate' ]  = \App\Backend\Modules\News\NewsController::getLinkToUpdateComment( $Comment );
			$retour[ 'linkDelete' ]  = \App\Backend\Modules\News\NewsController::getLinkToDeleteComment( $Comment );
		}
		
		$retour[ 'contenu' ] = $Comment->contenu();
		$retour[ 'date' ]    = $Comment->date()->format( 'd/m/Y à H\hi' );
		echo json_encode( $retour );
		
		// donne à la vue $retour. la vue doit renvoyer du json
		die();
		//$this->page()->addVar( 'retour', $retour );
	}
	
	/**
	 * @param HTTPRequest $request
	 *
	 * @return FormHandler
	 */
	protected function buildCommentForm( HTTPRequest $request ) {
		/** @var Member|null $member */
		$member = $this->app->user()->getAttribute( 'Member' );
		// si membre connecté alors auteur = member connecté
		$auteur  = $member ? null : $request->postData( 'auteur' );
		$fk_MMC  = $member ? $member->id() : null;
		$comment = new Comment( [
			'news'    => $request->getData( 'id' ),
			'auteur'  => $auteur,
			'fk_MMC'  => $fk_MMC,
			'contenu' => $request->postData( 'contenu' ),
		] );
		
		$formBuilder = new CommentFormBuilder( $comment, $this->app->user()->getAttribute( 'Member' ) );
		$form        = $formBuilder->form();
		
		return new FormHandler( $form, $this->managers->getManagerOf( 'Comments' ), $request );
	}
	
	public function executeDelete( HTTPRequest $request ) {
		$newsId = $request->getData( 'id' );
		
		$this->managers->getManagerOf( 'News' )->delete( $newsId );
		$this->managers->getManagerOf( 'Comments' )->deleteFromNews( $newsId );
		
		$this->app->user()->setFlash( 'La news a bien été supprimée !' );
		
		$this->app->httpResponse()->redirect( '/' );
	}
	
	public static function getLinkToNewsIndex() {
		return \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'index' );
	}
	
	public static function getLinkToNewsShow( News $News ) {
		return \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'show', [ "id" => $News->id() ] );
	}
	
	public static function getLinkToInsertComment( News $News ) {
		return \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertComment', [ "id" => $News->id() ] );
	}
	
	public static function getLinkToInsertCommentAjax( News $News ) {
		return \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertCommentAjax', [ "id" => $News->id() ] );
	}
}