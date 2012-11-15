<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Fonto Framework</title>
	<link rel="stylesheet" href="<?php echo $baseUrl; ?>web/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo $baseUrl; ?>web/css/style.css">

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
	<div class="container">
		<div class="row">
			<header>
				<span class="pull-right">
				<?php if ($auth->isAuthenticated()) : ?>
					<p>Inloggad som: <?php $user = $auth->getUser(); echo $user['username']; ?> <a href="<?php echo $baseUrl.'user/logout'; ?>">logga ut</a></p>
				<?php else : ?>
					<p>Ej inloggad.</p>
				<?php endif; ?>
				</span>
				<h1>Fonto Framework</h1>
			</header>
		</div>