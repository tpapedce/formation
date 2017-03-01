<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Comment;

abstract class MemberManager extends Manager
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
	
}