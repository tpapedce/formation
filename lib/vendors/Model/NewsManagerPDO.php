<?php
namespace Model;

use \Entity\News;

class NewsManagerPDO extends NewsManager
{
	protected function add(News $news)
	{
		$requete = $this->dao->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');
		
		$requete->bindValue(':titre', $news->titre());
		$requete->bindValue(':auteur', $news->auteur());
		$requete->bindValue(':contenu', $news->contenu());
		
		$requete->execute();
	}
	
	public function count()
	{
		return $this->dao->query('SELECT COUNT(*) FROM news')->fetchColumn();
	}
	
	public function getList($debut = -1, $limite = -1)
	{
		$sql = 'SELECT N.id AS id, M.MMC_user AS auteur, N.titre AS titre, N.contenu AS contenu, N.dateAjout AS dateAjout, N.dateModif AS dateModif FROM news AS N INNER JOIN T_MEM_MEMBERC AS M ON N.auteur = M.MMC_id ORDER BY N.id DESC';
		
		if ($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}
		
		$requete = $this->dao->query($sql);
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
		
		$listeNews = $requete->fetchAll();
		
		foreach ($listeNews as $news)
		{
			$news->setDateAjout(new \DateTime($news->dateAjout()));
			$news->setDateModif(new \DateTime($news->dateModif()));
		}
		
		$requete->closeCursor();
		
		return $listeNews;
	}
	
	public function getUnique($id)
	{
		$requete = $this->dao->prepare('SELECT N.id AS id, M.MMC_user AS auteur, N.titre AS titre, N.contenu AS contenu, N.dateAjout AS dateAjout, N.dateModif AS dateModif FROM news AS N INNER JOIN T_MEM_MEMBERC AS M ON N.auteur = M.MMC_id WHERE id = :id');
		$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$requete->execute();
		
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
		
		if ($news = $requete->fetch())
		{
			$news->setDateAjout(new \DateTime($news->dateAjout()));
			$news->setDateModif(new \DateTime($news->dateModif()));
			
			return $news;
		}
		
		return null;
	}
	
	protected function modify(News $news)
	{
		$requete = $this->dao->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');
		
		$requete->bindValue(':titre', $news->titre());
		$requete->bindValue(':auteur', $news->auteur());
		$requete->bindValue(':contenu', $news->contenu());
		$requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);
		
		$requete->execute();
	}
	
	public function delete($id)
	{
		$this->dao->exec('DELETE FROM news WHERE id = '.(int) $id);
	}
}