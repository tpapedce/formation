<?php
namespace OCFram;

class Managers {
	protected $api = null;
	protected $dao = null;
	
	public function __construct( $api, $dao ) {
		$this->api = $api;
		$this->dao = $dao;
	}
	
	public function getManagerOf( $module ) {
		if ( !is_string( $module ) || empty( $module ) ) {
			throw new \InvalidArgumentException( 'Le module spécifié est invalide' );
		}
		
		return new CacheManager( $this, $module );
	}
	
	public function api() {
		return $this->api;
	}
	
	public function dao() {
		return $this->dao;
	}
}