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
					/** @var Member|null $member */
					$member = $user->getAttribute('Member');
					?>
					<p>Content de vous revoir <?php htmlspecialchars ($member->user())?>!
				<?php
				if (2 == $member->status()) {?>
						Vous disposez des droits administrateur.
				<?php }} ?>
					</p>
			</header>
			
			<nav>
				<ul>
					<li><a href="/">Accueil</a></li>
					<?php if ($user->isAuthenticated()) {
						/** @var Member|null $member */
						$member = $user->getAttribute('Member');
						if (2 === $member->status()){?>
						<li><a href="/admin/">Admin</a></li>
						<?php } ?>
						<li><a href="/admin/news-insert.html">Ajouter une news</a></li>
						<li><a href="/admin/logout.php">Se deconnecter</a></li>
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