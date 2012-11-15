<?php $this->load('layout/header'); ?>
	<div class="row">
		<section>
			<h2>Logga in till ditt konto.</h2>
			<?php $this->load('include/messages'); ?>

			<?php echo $form->open($baseUrl.'user/login', 'post'); ?>
			<div class="controls">
			<?php echo $form->label('username', 'Användarnamn'); ?>
			<?php echo $form->input('text', 'username', array('value' => isset($_POST['username']) ? _e($_POST['username']) : '')); ?>
			</div>
			<div class="controls">
			<?php echo $form->label('password', 'Lösenord'); ?>
			<?php echo $form->input('password', 'password'); ?>
			</div>
			<?php echo $form->submit('Login', array('class' => 'btn')); ?>
			<?php echo $form->close(); ?>

		</section>
		<a href="<?php echo $baseUrl.'user/register' ?>">Inget konto? skapa ett</a>
	</div>
<?php $this->load('layout/footer'); ?>