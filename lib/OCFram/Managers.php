<?php
namespace OCFram;

use Model\CacheManager;

class Managers {
	protected $api = null;
	protected $dao = null;
	protected $managers_a;
	protected $managers_cached_a;
	
	public function __construct( $api, $dao ) {
		$this->api               = $api;
		$this->dao               = $dao;
		$this->managers_a        = [];
		$this->managers_cached_a = [];
	}
	
	public function getManagerOf( $module ) {
		if ( !is_string( $module ) || empty( $module ) ) {
			throw new \InvalidArgumentException( 'Le module spécifié est invalide' );
		}
		
		if ( !isset( $this->managers_a[ $module ] ) ) {
			$manager                     = '\\Model\\' . $module . 'Manager' . $this->api();
			$this->managers_a[ $module ] = new $manager( $this->dao() );
		}
		
		if ( !isset( $this->managers_cached_a[ $module ] ) ) {
			$this->managers_cached_a[ $module ] = new CacheManager( $this->dao(), $this->managers_a[ $module ] );
		}
		
		return $this->managers_cached_a[ $module ];
	}
	
	public function api() {
		return $this->api;
	}
	
	public function dao() {
		return $this->dao;
	}
}