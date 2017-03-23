<?php
namespace Model;

use \Entity\Member;

/**
 * Class MemberManagerPDO
 *
 * @package Model
 */
class MemberManagerPDO extends MemberManager
{
	
	protected function add(Member $member)
	{
		
			$q = $this->dao->prepare('INSERT INTO t_mem_memberc SET MMC_user = :user, MMC_password = :password, MMC_email = :email, MMC_dateinscription = NOW(), MMC_fk_MMY = 1');
			
			$q->bindValue(':user', $member->user());
			$q->bindValue(':password', $member->password());
			$q->bindValue(':email', $member->email());
			
			$q->execute();
			
			$member->setId($this->dao->lastInsertId());
	}
	
	protected function modify(Member $member)
	{
		$q = $this->dao->prepare('UPDATE t_mem_memberc SET MMC_user = :user, MMC_password = :password, MMC_email = :email WHERE MMC_id = :id');
		
		$q->bindValue(':user', $member->user());
		$q->bindValue(':password', $member->password());
		$q->bindValue(':email', $member->email());
		$q->bindValue(':id', $member->id(), \PDO::PARAM_INT);
		
		$q->execute();
	}
	
	public function get($id)
	{
		$q = $this->dao->prepare('SELECT MMC_id AS id, MMC_user AS user, MMC_password AS password, MMC_email AS email, MMC_dateinscription AS dateinscription, MMC_fk_MMY AS status FROM t_mem_memberc WHERE MMC_id = :id');
		$q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');
		
		return $q->fetch();
	}
	
	public function delete($id)
	{
		$this->dao->exec('DELETE FROM t_mem_memberc WHERE id = '.(int) $id);
	}

	public function existMemberUsingPseudo($pseudo)
	{
		$q = $this->dao->prepare('SELECT 1 FROM t_mem_memberc WHERE MMC_user = :pseudo');
		$q->bindValue(':pseudo', $pseudo);
		$q->execute();
		return (bool)$q->fetchColumn();
	}
	
	public function existMemberUsingEmail($email)
	{
		$q = $this->dao->prepare('SELECT 1 FROM t_mem_memberc WHERE MMC_email = :email');
		$q->bindValue(':email', $email);
		$q->execute();
		return (bool)$q->fetchColumn();
	}
	
	public function getMemberUsingLogin($login)
	{
		$q = $this->dao->prepare('SELECT MMC_id AS id, MMC_user AS user, MMC_password AS password, MMC_email AS email, MMC_dateinscription AS dateinscription, MMC_fk_MMY AS status FROM t_mem_memberc WHERE MMC_user = :login');
		$q->bindValue(':login', $login);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');
		
		return $q->fetch();
	}
	
}