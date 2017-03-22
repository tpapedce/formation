<?php
namespace OCFram;

class EqualValidator extends Validator
{
	protected $comparison;
	
	public function __construct($errorMessage, Field $field)
	{
		parent::__construct($errorMessage);
		
		$this->setComparison($field);
	}
	
	public function isValid($value)
	{
		return $value == $this->comparison;
	}
	
	public function setComparison(Field $field)
	{
		
		if (is_null($field))
		{
			throw new \RuntimeException('Il faut comparer à quelque chose de non NULL.');
		}
		else
		{
			$this->comparison = $field->value();
		}
	}
}