<?php
namespace Model;

use \Entity\Member;

class MemberManagerPDO extends MemberManager
{
	abstract protected function add(Member $member);
	
	public function save(Member $member)
	{
		if ($member->isValid())
		{
			$membert->isNew() ? $this->add($member) : $this->modify($member);
		}
		else
		{
			throw new \RuntimeException('Le membre doit être validé pour être enregistré');
		}
	}
	
	abstract protected function modify(Member $member);
	
	abstract public function get($id);
	
	abstract public function delete($id);
	
	
	
	
	protected function add(Member $member)
	{
		$q = $this->dao->prepare('INSERT INTO t_mem_memberc SET MMC_user = :user, MMC_password = :password, MMC_dateinscription = NOW()');
		
		$q->bindValue(':user', $member->user());
		$q->bindValue(':password', $member->password());
		
		$q->execute();
		
		$member->setId($this->dao->lastInsertId());
	}
	
	protected function modify(Member $member)
	{
		$q = $this->dao->prepare('UPDATE t_mem_memberc SET MMC_user = :user, MMC_password = :password WHERE MMC_id = :id');
		
		$q->bindValue(':user', $member->user());
		$q->bindValue(':password', $member->password());
		$q->bindValue(':id', $member->id(), \PDO::PARAM_INT);
		
		$q->execute();
	}
	
	public function get($id)
	{
		$q = $this->dao->prepare('SELECT MMC_id, MMC_user, MMC_password, MMC_dateinscription FROM t_mem_memberc WHERE MMC_id = :id');
		$q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');
		
		return $q->fetch();
	}
	
	public function delete($id)
	{
		$this->dao->exec('DELETE FROM t_mem_memberc WHERE id = '.(int) $id);
	}
	
}