<?php
namespace OCFram;

class EmailValidator extends Validator {
	protected $comparison;
	
	public function __construct( $errorMessage ) {
		parent::__construct( $errorMessage );
	}
	
	public function isValid( $value ) {
		return filter_var( $value, FILTER_VALIDATE_EMAIL );
	}
}