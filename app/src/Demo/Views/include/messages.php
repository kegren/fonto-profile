		<?php if ($session->has('error')) : ?>
			<div class="alert alert-error">
			  <strong>Ooops!</strong> <?php echo $session->flashMessage('error'); ?>
			</div>
		<?php endif; ?>
		<?php if ($session->has('success')) : ?>
			<div class="alert alert-success">
			  <strong>Lyckades! </strong> <?php echo $session->flashMessage('success'); ?>
			</div>
		<?php endif; ?>