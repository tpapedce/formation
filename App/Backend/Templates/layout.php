<!DOCTYPE html>
<html>
	<head>
		<title>
			<?= isset($title) ? $title : 'Mon super site' ?>
		</title>
		
		<meta charset="utf-8" />
		
		<link rel="stylesheet" href="/css/Envision.css" type="text/css" />
	</head>
	
	<body>
		<div id="wrap">
			<header>
				<h1><a href="/">Mon super site</a></h1>
				<?php if ($user->isAuthenticated()) {
					?>
					<p>Content de vous revoir  <?= htmlspecialchars( $user->getAttribute( 'Member' )[ 'user' ] ) ?>!
						<?php if ( 2 == $user->getAttribute( 'Member' )[ 'status' ] ) : ?>
							Vous disposez des droits administrateur.
						<?php endif; ?>
				<?php } ?>
					</p>
			</header>
			
			<nav>
				<ul>
					<li><a href="/">Accueil</a></li>
					<?php if ($user->isAuthenticated()) {
						/** @var Member|null $member */
						$member = $user->getAttribute('Member');
						if (2 === $member->status()){?>
							<li><a href="<?= \App\Backend\Modules\News\NewsController::getLinkToAdmin() ?>">Admin</a></li>
						<?php } ?>
						<li><a href="<?= \App\Backend\Modules\News\NewsController::getLinkToInsertNews() ?>">Ajouter une news</a></li>
						<li><a href="<?= \App\Backend\Modules\Connexion\ConnexionController::getLinkToLogout() ?>">Se deconnecter</a></li>
					<?php } ?>
				</ul>
			</nav>
			
			<div id="content-wrap">
				<section id="main">
					<?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>
					
					<?= $content ?>
				</section>
			</div>
			
			<footer></footer>
		</div>
	</body>
</html>