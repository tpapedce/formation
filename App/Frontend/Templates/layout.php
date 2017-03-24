<!DOCTYPE html>
<html>
	<head>
		<title>
			<?= isset( $title ) ? $title : 'Mon super site' ?>
		</title>
		
		<meta charset="utf-8" />
		
		<link rel="stylesheet" href="/css/Envision.css" type="text/css" />
	</head>
	
	<body>
		<div id="wrap">
			<header>
				<h1><a href="/">Mon super site</a></h1>
				<?php if ( $user->isAuthenticated() ) : ?>
				<p>Content de vous revoir <?= htmlspecialchars( $user->getAttribute( 'Member' )[ 'user' ] ) ?>!
					<?php if ( 2 == $user->getAttribute( 'Member' )[ 'status' ] ) : ?>
						Vous disposez des droits administrateur.
					<?php endif; ?>
					<?php else : ?>
				<p>Bienvenue ! <a class="colorFuchsia" href="/admin/">Connectez vous</a> ou <a class="colorFuchsia" href="/inscription.html">créez un compte</a> gratuitement !
					<?php endif; ?>
				</p>
			</header>
			
			<nav>
				<ul>
					<li><a href="/">Accueil</a></li>
					<?php if ( $user->isAuthenticated() ) : ?>
						<?php if ( 2 == $user->getAttribute( 'Member' )[ 'status' ] ) : ?>
							<li><a href="/admin/">Admin</a></li>
						<?php endif; ?>
						<li><a href="/admin/news-insert.html">Ajouter une news</a></li>
						<li><a href="/admin/logout.php">Se deconnecter</a></li>
					<?php else : ?>
						<li><a href="/inscription.html">Inscription</a></li>
						<li><a href="/admin/">Connexion</a></li>
					<?php endif; ?>
				</ul>
			</nav>
			
			<div id="content-wrap">
				<section id="main">
					<?php if ( $user->hasFlash() ) {
						echo '<p style="text-align: center;">', $user->getFlash(), '</p>';
					} ?>
					
					<?= $content ?>
				</section>
			</div>
			
			<footer></footer>
		</div>
	</body>
</html>