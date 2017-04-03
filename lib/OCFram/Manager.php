<?php
namespace OCFram;

abstract class Manager {
	
	protected $resultRequest_a = [];
	
	/** @var  \PDO */
	protected $dao;
	
	public function __construct( $dao ) {
		$this->dao = $dao;
	}
	
	// tableau de la forme 'Entity.Fonction.param_fonction' => resultat_requete
	public function resultRequest(){
		return $this->resultRequest_a;
	}
	
}