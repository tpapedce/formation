<?php

namespace App\Backend\Modules\News;

use Entity\Member;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \Entity\News;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \FormBuilder\MemberFormBuilder;
use \OCFram\FormHandler;

class NewsController extends BackController {
	protected function checkStatus( $minimal_status_number ) {
		/** @var Member $Member */
		$member = $this->app->user()->getAttribute( 'Member' );
		if ( $member->status() < $minimal_status_number ) {
			$this->app->user()->setFlash( 'Vous n\'avez pas les droits !' );
			$this->app->httpResponse()->redirect404();
		}
	}
	
	public function executeIndex( HTTPRequest $request ) {
		$this->checkStatus( 2 );
		
		$this->page->addVar( 'title', 'Gestion des news' );
		
		$manager_news   = $this->managers->getManagerOf( 'News' );
		$manager_member = $this->managers->getManagerOf( 'Member' );
		
		$this->page->addVar( 'listeMembre', $manager_member->getList() );
		$this->page->addVar( 'nombreMembre', $manager_member->count() );
		
		$this->page->addVar( 'listeNews', $manager_news->getList() );
		$this->page->addVar( 'nombreNews', $manager_news->count() );
	}
	
	public function executeInsert( HTTPRequest $request ) {
		$this->checkStatus( 1 );
		$this->processForm( $request );
		
		$this->page->addVar( 'title', 'Ajout d\'une news' );
	}
	
	public function executeUpdate( HTTPRequest $request ) {
		/** @var Member $Member */
		$member = $this->app->user()->getAttribute( 'Member' );
		if ( ( 2 == $member->status() )
			 || ( ( 1 == $member->status() )
				  && ( $member->id() == $this->managers->getManagerOf( 'News' )->getMemberUsingId( $request->getData( 'id' ) )->id() ) )
		) {
			$this->processForm( $request );
			
			$this->page->addVar( 'title', 'Modification d\'une news' );
		}
		else {
			$this->app->user()->setFlash( 'Vous n\'avez pas les droits !' );
			$this->app->httpResponse()->redirect404();
		}
	}
	
	public function executeDelete( HTTPRequest $request ) {
		
		/** @var Member $Member */
		$member = $this->app->user()->getAttribute( 'Member' );
		if ( ( 2 == $member->status() )
			 || ( ( 1 == $member->status() )
				  && ( $member->id() == $this->managers->getManagerOf( 'News' )->getMemberUsingId( $request->getData( 'id' ) )->id() ) )
		) {
			$this->managers->getManagerOf( 'News' )->delete( $request->getData( 'id' ) );
			
			$this->app->user()->setFlash( 'La news a bien été supprimée !' );
			
			$this->app->httpResponse()->redirect( '/' );
		}
		else {
			$this->app->user()->setFlash( 'Vous n\'avez pas les droits !' );
			$this->app->httpResponse()->redirect404();
		}
	}
	
	public function executeUpdateMember( HTTPRequest $request ) {
		
		$this->checkStatus( 2 );
		
		$this->processFormMember( $request );
		
		$this->page->addVar( 'title', 'Modification d\'un membre' );
	}
	
	public function executeDeleteMember( HTTPRequest $request ) {
		
		$this->checkStatus( 2 );
		
		$this->managers->getManagerOf( 'Member' )->delete( $request->getData( 'id' ) );
		
		$this->app->user()->setFlash( 'Le membre a bien été supprimé !' );
		
		$this->app->httpResponse()->redirect( '/' );
	}
	
	public function executeUpdateComment( HTTPRequest $request ) {
		
		/** @var Member $Member */
		$member = $this->app->user()->getAttribute( 'Member' );
		if ( ( 2 == $member->status() )
			 || ( ( 1 == $member->status() )
				  && ( $member->id() == $this->managers->getManagerOf( 'Comments' )->getMemberOfCommentUsingCommentId( $request->getData( 'id' ) )->id() ) )
		) {
			$this->page->addVar( 'title', 'Modification d\'un commentaire' );
			
			if ( $request->method() == 'POST' ) {
				/** @var Member|null $member */
				$member  = $this->app->user()->getAttribute( 'Member' );
				$comment = new Comment( [
					'id'      => $request->getData( 'id' ),
					'news'    => $this->managers->getManagerOf( 'Comments' )->getNewsUsingId( $request->getData( 'id' ) ),
					'auteur'  => $member->id(),
					'contenu' => $request->postData( 'contenu' ),
				] );
			}
			else {
				$comment = $this->managers->getManagerOf( 'Comments' )->get( $request->getData( 'id' ) );
			}
			
			$formBuilder = new CommentFormBuilder( $comment, $this->app->user()->getAttribute( 'Member' ) );
			$formBuilder->build();
			
			$form = $formBuilder->form();
			
			$formHandler = new \OCFram\FormHandler( $form, $this->managers->getManagerOf( 'Comments' ), $request );
			if ( $formHandler->process() ) {
				$this->app->user()->setFlash( 'Le commentaire a bien été modifié' );
				$this->app->httpResponse()->redirect( '/' );
			}
			
			$this->page->addVar( 'form', $form->createView() );
		}
		else {
			$this->app->user()->setFlash( 'Vous n\'avez pas les droits !' );
			$this->app->httpResponse()->redirect404();
		}
	}
	
	public function executeDeleteComment( HTTPRequest $request ) {
		/** @var Member $Member */
		$member = $this->app->user()->getAttribute( 'Member' );
		if ( ( 2 == $member->status() )
			 || ( ( 1 == $member->status() )
				  && ( $member->id() == $this->managers->getManagerOf( 'Comments' )->getMemberOfCommentUsingCommentId( $request->getData( 'id' ) )->id() ) )
		) {
			$this->managers->getManagerOf( 'Comments' )->delete( $request->getData( 'id' ) );
			
			$this->app->user()->setFlash( 'Le commentaire a bien été supprimé !' );
			
			$this->app->httpResponse()->redirect( '/' );
		}
		else {
			$this->app->user()->setFlash( 'Vous n\'avez pas les droits !' );
			$this->app->httpResponse()->redirect404();
		}
	}
	
	public function processForm( HTTPRequest $request ) {
		if ( $request->method() == 'POST' ) {
			/** @var Member $Member */
			$Member = $this->app->user()->getAttribute( 'Member' );
			$news   = new News( [
				'auteur'  => $Member->id(),
				'titre'   => $request->postData( 'titre' ),
				'contenu' => $request->postData( 'contenu' ),
			] );
			
			if ( $request->getExists( 'id' ) ) {
				$news->setId( $request->getData( 'id' ) );
			}
		}
		else {
			// L'identifiant de la news est transmis si on veut la modifier
			if ( $request->getExists( 'id' ) ) {
				$news = $this->managers->getManagerOf( 'News' )->getUnique( $request->getData( 'id' ) );
			}
			else {
				$news = new News;
			}
		}
		
		$formBuilder = new NewsFormBuilder( $news );
		$formBuilder->build();
		
		$form        = $formBuilder->form();
		$formHandler = new \OCFram\FormHandler( $form, $this->managers->getManagerOf( 'News' ), $request );
		
		if ( $formHandler->process() ) {
			$this->app->user()->setFlash( $news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !' );
			$this->app->httpResponse()->redirect( '/' );
		}
		
		$this->page->addVar( 'form', $form->createView() );
	}
	
	public function processFormMember( HTTPRequest $request ) {
		if ( $request->method() == 'POST' ) {
			$member = new Member( [
				'user'     => $request->postData( 'user' ),
				'password' => $request->postData( 'password' ),
				'email'    => $request->postData( 'email' ),
				'status'   => $request->postData( 'contenu' ),
			] );
			
			if ( $request->getExists( 'id' ) ) {
				$member->setId( $request->getData( 'id' ) );
			}
		}
		else {
			// L'identifiant du membre est transmis si on veut la modifier
			if ( $request->getExists( 'id' ) ) {
				$member = $this->managers->getManagerOf( 'Member' )->getUnique( $request->getData( 'id' ) );
			}
			else {
				$member = new Member;
			}
		}
		
		$formBuilder = new MemberFormBuilder( $member, $this );
		$formBuilder->build();
		
		$form        = $formBuilder->form();
		$formHandler = new \OCFram\FormHandler( $form, $this->managers->getManagerOf( 'Member' ), $request );
		
		if ( $formHandler->process() ) {
			$this->app->user()->setFlash( 'Le membre a bien été modifié' );
			$this->app->httpResponse()->redirect( '/' );
		}
		
		$this->page->addVar( 'form', $form->createView() );
	}
}
