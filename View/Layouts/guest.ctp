<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo $title_for_layout; ?></title>
	<?php
		echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex, nofollow'));

		/* Including CSS and favicon. Also outputting blocks of inline meta and CSS. */
		echo $this->Html->css(array('/assets/admin/css/libs/bootstrap.min.css', '/assets/admin/css/libs/bootstrap-responsive.min.css', '/assets/admin/css/login.css'));
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->Html->meta('icon');
	?>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span6 offset3">
				<div id="loginView">
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Session->flash('auth'); ?>
					<?php echo $this->fetch('content'); ?>
				</div>
			</div>
		</div>
	</div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/libs/jquery-1.7.2.min.js"><\/script>')</script>
	<?php
		/* Including scripts. Also outputting any inline script files and any inline buffered code. */
		echo $this->Html->script(array('/assets/admin/js/libs/bootstrap.min.js'));
		echo $this->fetch('script');
		echo $this->Js->writeBuffer(array('onDomReady' => true));
	?>
	<!--[if lt IE 7 ]>
		<script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})});</script>
	<![endif]-->
</body>
</html>
