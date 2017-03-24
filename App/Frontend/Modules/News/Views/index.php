<?php
foreach ( $listeNews as $news ) {
	?>
	<h2><a href="news-<?= ( $news[ 'id' ] ) ?>.html"><?= htmlspecialchars( $news[ 'titre' ] ) ?></a></h2>
	<p><?= htmlspecialchars( nl2br( $news[ 'contenu' ] ) ) ?></p>
	<?php
}