<?php
namespace Entity;

use \OCFram\Entity;

class Member extends Entity
{
	protected $user,
		$password,
		$dateInscription;
	
	const USER_INVALIDE = 1;
	const PASSWORD_INVALIDE = 2;
	
	public function isValid()
	{
		return !(empty($this->user) || empty($this->password));
	}
	
	public function setUser($user)
	{
		if (!is_string($user) || empty($user))
		{
			$this->erreurs[] = self::USER_INVALIDE;
		}
		
		$this->user = $user;
	}
	
	public function setPassword($password)
	{
		if (!is_string($password) || empty($password))
		{
			$this->erreurs[] = self::PASSWORD_INVALIDE;
		}
		
		$this->password = $password;
	}
	
	public function setDateInscription(\DateTime $dateInscription)
	{
		$this->dateInscription = $dateInscription;
	}
	
	public function user()
	{
		return $this->user;
	}
	
	public function password()
	{
		return $this->password;
	}
	
	public function dateInscription()
	{
		return $this->dateInscription;
	}
	
}