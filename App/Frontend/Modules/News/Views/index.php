<?php
foreach ( $listeNews as $news ) {
	?>
	<h2><a href="<?= \App\Frontend\Modules\News\NewsController::getLinkToNewsShow($news) ?>"><?= htmlspecialchars( $news[ 'titre' ] ) ?></a></h2>
	<p><?= htmlspecialchars( nl2br( $news[ 'contenu' ] ) ) ?></p>
	<?php
}