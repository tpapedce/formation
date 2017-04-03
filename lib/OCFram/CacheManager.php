<?php
namespace OCFram;

class CacheManager {
	protected $name;
	protected $result_a = [] ;
	protected $managers_a = [];
	protected $managers;
	
	public function __construct( Managers $managers, $module ) {
		$this->managers = $managers;
		$this->name = $module;
	}
	
	public function __call( $name, $arguments ) {
		if ( isset( $this->result_a[ $this->name() . '_' . $name . '_' . implode( '_', $arguments ) ] ) ) {
			return $this->result_a[ $this->name() . '_' . $name . '_' . implode( '_', $arguments ) ];
		}
		
		if ( !isset( $this->managers_a[ $this->name ] ) ) {
			$manager = '\\Model\\' . $this->name() . 'Manager' . $this->managers->api();
			$this->managers_a[ $this->name() ] = new $manager( $this->managers->dao() );
		}
		
		return call_user_func_array([$this->managers_a[ $this->name() ], $name], $arguments);
	}
	
	
	public function name() {
		return $this->name;
	}
	
	public function managers_a(){
		return $this->managers_a;
	}
	
	
}