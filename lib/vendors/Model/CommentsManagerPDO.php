<?php

namespace Model;

use \Entity\Comment;

class CommentsManagerPDO extends CommentsManager {
	protected function add( Comment $comment ) {
		$q = $this->dao->prepare( 'INSERT INTO comments SET news = :news, auteur = :auteur, contenu = :contenu, date = NOW()' );
		
		$q->bindValue( ':news', $comment->news(), \PDO::PARAM_INT );
		$q->bindValue( ':auteur', $comment->auteur() );
		$q->bindValue( ':contenu', $comment->contenu() );
		
		$q->execute();
		
		$comment->setId( $this->dao->lastInsertId() );
	}
	
	public function getListOf( $news ) {
		if ( !ctype_digit( $news ) ) {
			throw new \InvalidArgumentException( 'L\'identifiant de la news passé doit être un nombre entier valide' );
		}
		
		$q = $this->dao->prepare( 'SELECT C.id AS id, C.news AS news, COALESCE(M.MMC_user, C.auteur) AS auteur, C.contenu AS contenu, date FROM comments AS C LEFT OUTER JOIN T_MEM_MEMBERC AS M ON C.auteur = M.MMC_id  WHERE news = :news' );
		$q->bindValue( ':news', $news, \PDO::PARAM_INT );
		$q->execute();
		
		$q->setFetchMode( \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment' );
		
		$comments = $q->fetchAll();
		
		foreach ( $comments as $comment ) {
			$comment->setDate( new \DateTime( $comment->date() ) );
		}
		
		return $comments;
	}
	
	protected function modify( Comment $comment ) {
		$q = $this->dao->prepare( 'UPDATE comments SET contenu = :contenu WHERE id = :id' );
		
		$q->bindValue( ':contenu', $comment->contenu() );
		$q->bindValue( ':id', $comment->id(), \PDO::PARAM_INT );
		
		$q->execute();
	}
	
	public function getUnique( $id ) {
		$requete = $this->dao->prepare( 'SELECT C.id AS id, C.news AS news, COALESCE(M.MMC_user, C.auteur) AS auteur, C.contenu AS contenu, date FROM comments AS C LEFT OUTER JOIN T_MEM_MEMBERC AS M ON C.auteur = M.MMC_id  WHERE news = :news' );
		$requete->bindValue( ':id', (int)$id, \PDO::PARAM_INT );
		$requete->execute();
		
		$requete->setFetchMode( \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment' );
		
		if ( $comment = $requete->fetch() ) {
			$comment->setDate( new \DateTime( $comment->date() ) );
			
			return $comment;
		}
		
		return null;
	}
	
	public function get( $id ) {
		$q = $this->dao->prepare( 'SELECT id, news, auteur, contenu FROM comments WHERE id = :id' );
		$q->bindValue( ':id', (int)$id, \PDO::PARAM_INT );
		$q->execute();
		
		$q->setFetchMode( \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment' );
		
		return $q->fetch();
	}
	
	public function delete( $id ) {
		$q = $this->dao->prepare( 'DELETE FROM comments WHERE id = :id' );
		$q->bindValue( ':id', (int)$id, \PDO::PARAM_INT );
		$q->execute();
	}
	
	public function deleteFromNews( $news ) {
		$q = $this->dao->prepare( 'DELETE FROM comments WHERE news = :news');
		$q->bindValue( ':news', (int)$news, \PDO::PARAM_INT );
		$q->execute();
	}
	
	public function getNewsUsingId( $id ) {
		$q = $this->dao->prepare( 'SELECT N.id AS id, N.auteur AS auteur, N.titre AS titre, N.contenu AS contenu FROM news AS N INNER JOIN comments AS C ON C.news = N.id WHERE C.id = :id' );
		$q->bindValue( ':id', $id );
		$q->execute();
		
		$q->setFetchMode( \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News' );
		
		return $q->fetch();
	}
	
	public function getMemberOfCommentUsingCommentId( $id ) {
		$q = $this->dao->prepare( 'SELECT M.MMC_id AS id, M.MMC_user AS user, M.MMC_password AS password, M.MMC_email AS email, M.MMC_dateinscription AS dateinscription, M.MMC_fk_MMY FROM T_MEM_MEMBERC AS M INNER JOIN comments AS C ON C.auteur = M.MMC_id WHERE C.id = :id' );
		$q->bindValue( ':id', $id );
		$q->execute();
		
		$q->setFetchMode( \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member' );
		
		return $q->fetch();
	}
}