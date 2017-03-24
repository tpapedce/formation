<p>Par <em><?= $news[ 'auteur' ] ?></em>, le <?= $news[ 'dateAjout' ]->format( 'd/m/Y à H\hi' ) ?></p>
<h2><?= htmlspecialchars( $news[ 'titre' ] ) ?></h2>

<p><?= htmlspecialchars( nl2br( $news[ 'contenu' ] ) ) ?></p>

<?php
/** @var Member|null $member */
$member = $user->getAttribute( 'Member' );
if ( $user->isAuthenticated() && ( ( 2 == $member->status() ) || ( ( 1 == $member->status() ) && ( $member->user() === $news[ 'auteur' ] ) ) ) ) { ?>
	<a href="admin/news-update-<?= $news[ 'id' ] ?>.html">Modifier</a> |
	<a href="admin/news-delete-<?= $news[ 'id' ] ?>.html">Supprimer</a>
<?php } ?>

<?php if ( $news[ 'dateAjout' ] != $news[ 'dateModif' ] ) { ?>
	<p style="text-align: right;">
		<small><em>Modifiée le <?= $news[ 'dateModif' ]->format( 'd/m/Y à H\hi' ) ?></em></small>
	</p>
<?php } ?>



<?php
if ( empty( $comments ) ) {
	?>
	<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
	<?php
}

foreach ( $comments as $comment ) {
	?>
	<fieldset>
		<legend>
			Posté par <strong><?= htmlspecialchars( $comment[ 'auteur' ] ) ?></strong> le <?= $comment[ 'date' ]->format( 'd/m/Y à H\hi' ) ?>
			<?php
			/** @var Member|null $member */
			$member = $user->getAttribute( 'Member' );
			if ( $user->isAuthenticated() && ( ( 2 == $member->status() ) || ( ( 1 == $member->status() ) && ( $member->user() === $comment[ 'auteur' ] ) ) ) ) { ?> -
				<a href="admin/comment-update-<?= $comment[ 'id' ] ?>.html">Modifier</a> |
				<a href="admin/comment-delete-<?= $comment[ 'id' ] ?>.html">Supprimer</a>
			<?php } ?>
		</legend>
		<p class="comment"><?= htmlspecialchars( nl2br( $comment[ 'contenu' ] ) ) ?></p>
	</fieldset>
<?php } ?>

<p><a href="commenter-<?= $news[ 'id' ] ?>.html">Ajouter un commentaire</a></p>