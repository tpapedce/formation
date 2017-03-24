<p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news. En voici la liste :</p>

<table>
	<tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
	<?php
	foreach ($listeNews as $news)
	{
		echo '<tr><td>', htmlspecialchars($news['auteur']), '</td><td>', htmlspecialchars($news['titre']), '</td><td>le ', $news['dateAjout']->format('d/m/Y à H\hi'), '</td><td>', ($news['dateAjout'] == $news['dateModif'] ? '-' : 'le '.$news['dateModif']->format('d/m/Y à H\hi')), '</td><td><a href="news-update-', $news['id'], '.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="news-delete-', $news['id'], '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
	}
	?>
</table>

<p style="text-align: center">Il y a actuellement <?= $nombreMembre ?> membres. En voici la liste :</p>

<table>
	<tr><th>Pseudo</th><th>Email</th><th>Date d'inscription</th><th>Statut</th><th>Action</th></tr>
	<?php
	foreach ($listeMembre as $member)
	{
		echo '<tr><td>', htmlspecialchars($member['user']), '</td><td>', htmlspecialchars($member['email']), '</td><td>le ', $member['dateInscription']->format('d/m/Y à H\hi'),  '</td><td>', $member['status'], '</td><td><a href="member-update-', $member['id'], '.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="member-delete-', $member['id'], '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
	}
	?>
</table>