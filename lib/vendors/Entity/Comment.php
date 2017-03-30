<?php
namespace Entity;

use \OCFram\Entity;

class Comment extends Entity implements \JsonSerializable {
	protected $id, $news, $auteur, $fk_MMC, $contenu, $date;
	const AUTEUR_INVALIDE  = 1;
	const CONTENU_INVALIDE = 2;
	
	public function isValid() {
		return !( ( empty( $this->auteur ) && ( empty( $this->fk_MMC ) ) ) || empty( $this->contenu ) );
	}
	
	public function setNews( $news ) {
		$this->news = (int)$news;
	}
	
	public function setAuteur( $auteur ) {
		if ( !is_string( $auteur ) || empty( $auteur ) ) {
			$this->erreurs[] = self::AUTEUR_INVALIDE;
		}
		
		$this->auteur = $auteur;
	}
	
	public function setFk_MMC( $fk_MMC ) {
		$this->fk_MMC = $fk_MMC;
	}
	
	public function setContenu( $contenu ) {
		if ( !is_string( $contenu ) || empty( $contenu ) ) {
			$this->erreurs[] = self::CONTENU_INVALIDE;
		}
		
		$this->contenu = $contenu;
	}
	
	public function setDate( $date ) {
		$this->date = $date;
	}
	
	public function id() {
		return $this->id;
	}
	
	public function news() {
		return $this->news;
	}
	
	public function auteur() {
		return $this->auteur;
	}
	
	public function fk_MMC() {
		return $this->fk_MMC;
	}
	
	public function contenu() {
		return $this->contenu;
	}
	
	public function date() {
		return $this->date;
	}
	
	public function jsonSerialize() {
		return [
			'id' => $this->id(),
			'news' => $this->news(),
			'auteur' => $this->auteur(),
			'fk_MMC' => $this->fk_MMC(),
			'contenu' => $this->contenu(),
			'date' => $this->date()->format( 'd/m/Y à H\hi' ),
		];
	}
}