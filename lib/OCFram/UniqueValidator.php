<?php
namespace OCFram;

class UniqueValidator extends Validator {
	protected $field;
	protected $Manager;
	protected $manager_method;
	
	public function __construct( $errorMessage, Manager $Manager, $manager_method ) {
		parent::__construct( $errorMessage );
		
		$this->setManager( $Manager );
		$this->setManagerMethod( $manager_method );
	}
	
	public function isValid( $value ) {
		return !$this->Manager->{$this->manager_method}( $value );
	}
	
	public function setManager( $Manager ) {
		
		if ( isset( $Manager ) ) {
			$this->Manager = $Manager;
		}
		else {
			throw new \RuntimeException( 'Le Manager n\'est pas valide' );
		}
	}
	
	public function setManagerMethod( $manager_method ) {
		
		if ( is_string( $manager_method ) ) {
			$this->manager_method = $manager_method;
		}
		else {
			throw new \RuntimeException( 'La méthode précisée doît être un string' );
		}
	}
}