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
				<p>Bienvenue ! <a class="colorFuchsia" href="<?= \App\Backend\Modules\News\NewsController::getLinkToAdmin() ?>">Connectez vous</a> ou <a class="colorFuchsia" href="<?= \App\Frontend\Modules\Inscription\InscriptionController::getLinkToInscription() ?>">créez un compte</a> gratuitement !
					<?php endif; ?>
				</p>
			</header>
			
			<nav>
				<ul>
					<li><a href="<?= \App\Frontend\Modules\News\NewsController::getLinkToNewsIndex() ?>">Accueil</a></li>
					<?php if ( $user->isAuthenticated() ) : ?>
						<?php if ( 2 == $user->getAttribute( 'Member' )[ 'status' ] ) : ?>
							<li><a href="<?= \App\Backend\Modules\News\NewsController::getLinkToAdmin() ?>">Admin</a></li>
						<?php endif; ?>
						<li><a href="<?= \App\Backend\Modules\News\NewsController::getLinkToInsertNews() ?>">Ajouter une news</a></li>
						<li><a href="<?= \App\Backend\Modules\Connexion\ConnexionController::getLinkToLogout() ?>">Se deconnecter</a></li>
					<?php else : ?>
						<li><a href="<?= \App\Frontend\Modules\Inscription\InscriptionController::getLinkToInscription() ?>">Inscription</a></li>
						<li><a href="<?= \App\Backend\Modules\News\NewsController::getLinkToAdmin() ?>">Connexion</a></li>
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