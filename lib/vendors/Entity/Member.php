<?php
namespace Entity;

use \OCFram\Entity;

class Member extends Entity
{
	protected $id,
		$user,
		$password,
		$dateInscription,
		$email,
		$status;
	
	const USER_INVALIDE = 1;
	const PASSWORD_INVALIDE = 2;
	const EMAIL_INVALIDE = 3;
	const STATUS_INVALIDE = 4;
	
	public function isValid()
	{
		return !(empty($this->user) || empty($this->password) || empty($this->email) );
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
	
	public function setEmail($email)
	{
		if (!is_string($email) || empty($email))
		{
			$this->erreurs[] = self::EMAIL_INVALIDE;
		}
		
		$this->email = $email;
	}
	
	public function setStatus($status)
	{
		if (!is_int($status) || empty($status))
		{
			$this->erreurs[] = self::STATUS_INVALIDE;
		}
		
		$this->status = $status;
	}
	
	public function id()
	{
		return $this->id;
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
	
	public function email()
	{
		return $this->email;
	}
	
	public function status()
	{
		return $this->status;
	}
	
	
}