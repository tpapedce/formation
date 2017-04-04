<?php
namespace Model;

use \OCFram\Manager;
use OCFram\Managers;

class CacheManager extends Manager {
	protected $result_a   = [];
	protected $manager;
	

	public function __construct( $dao, Manager $manager ) {
		parent::__construct( $dao );
		$this->manager = $manager;
	}
	
	public function __call( $function_name, $arguments ) {
		$key = $function_name . '_' . serialize( $arguments );
		if ( array_key_exists( $key, $this->result_a ) ) {
			return $this->result_a[ $key ];
		}
		
		
		
		return $this->result_a[ $key ] = call_user_func_array( [
			$this->manager,
			$function_name,
		], $arguments );
	}

}