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
				
				<?= isset($header) ? $header : null ?>
				
			</header>
			
			<nav>
				<ul>
					
					<?= isset($menu) ? $menu : null ?>
					
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