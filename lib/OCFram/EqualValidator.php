<?php
namespace OCFram;

class EqualValidator extends Validator
{
	protected $comparison;
	
	public function __construct($errorMessage, $comparison)
	{
		parent::__construct($errorMessage);
		
		$this->setComparison($comparison);
	}
	
	public function isValid($)
	{
		return $value == $this->comparison;
	}
	
	public function setComparison($comparison)
	{
		
		if (is_null($comparison))
		{
			throw new \RuntimeException('Il faut comparer à quelque chose de non NULL.');
		}
		else
		{
			$this->comparison = $comparison;
		}
	}
}