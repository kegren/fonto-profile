<?php $this->load('layout/header'); ?>
	<div class="row">
		<section>
			<h2>Din profilsida.</h2>

			<?php $this->load('include/messages'); ?>

			<?php echo $form->open($baseUrl.'user/profile', 'post'); ?>
			<div class="controls">
				<?php echo $form->label('username', 'Användarnamn *'); ?>
				<?php echo $form->input('text', 'username', array('value' => isset($username) ? _e($username) : '')); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('username') : ''; ?>
			</span>
			</div>
			<div class="controls">
				<?php echo $form->label('password', 'Lösenord'); ?>
				<?php echo $form->input('password', 'password'); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('password') : ''; ?>
			</span>
			</div>
			<div class="controls">
				<?php echo $form->label('password_repeat', 'Upprepa lösenord'); ?>
				<?php echo $form->input('password', 'password_repeat'); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('password_repeat') : ''; ?>
			</span>
			</div>
			<div class="controls">
				<?php echo $form->label('name', 'Namn *'); ?>
				<?php echo $form->input('text', 'name', array('value' => isset($name) ? _e($name) : '')); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('name') : ''; ?>
			</span>
			</div>
			<div class="controls">
				<?php echo $form->label('email', 'E-postadress *'); ?>
				<?php echo $form->input('text', 'email', array('value' => isset($email) ? _e($email) : '')); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('email') : ''; ?>
			</span>
			</div>
			<?php echo $form->submit('Uppdatera', array('class' => 'btn btn-info')); ?>
			<?php echo $form->close(); ?>

			<h3>Du har följande roll/roller</h3>
			<?php if ($auth->hasRole()) : ?>
				<?php foreach ($auth->getUserRoles() as $roles ) : ?>
				<p><?php echo $roles; ?></p>
				<?php endforeach; ?>
			<?php endif; ?>


		</section>
	</div>

<?php $this->load('layout/footer'); ?>