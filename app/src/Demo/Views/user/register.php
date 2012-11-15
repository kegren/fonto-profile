<?php $this->load('layout/header'); ?>
	<div class="row">
		<section>
			<h2>Skapa ny användare.</h2>

			<?php $this->load('include/messages'); ?>

			<?php echo $form->open($baseUrl.'user/register', 'post'); ?>
			<div class="controls">
				<?php echo $form->label('username', 'Användarnamn *'); ?>
				<?php echo $form->input('text', 'username', array('value' => isset($_POST['username']) ? _e($_POST['username']) : '')); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('username') : ''; ?>
			</span>
			</div>
			<div class="controls">
				<?php echo $form->label('password', 'Lösenord *'); ?>
				<?php echo $form->input('password', 'password'); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('password') : ''; ?>
			</span>
			</div>
			<div class="controls">
				<?php echo $form->label('password_repeat', 'Upprepa lösenord *'); ?>
				<?php echo $form->input('password', 'password_repeat'); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('password_repeat') : ''; ?>
			</span>
			</div>
			<div class="controls">
				<?php echo $form->label('name', 'Namn *'); ?>
				<?php echo $form->input('text', 'name', array('value' => isset($_POST['name']) ? _e($_POST['name']) : '')); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('name') : ''; ?>
			</span>
			</div>
			<div class="controls">
				<?php echo $form->label('email', 'E-postadress *'); ?>
				<?php echo $form->input('text', 'email', array('value' => isset($_POST['email']) ? _e($_POST['email']) : '')); ?>
			<span class="help-block text-error">
				<?php echo isset($validator) ? $validator->getErrorFor('email') : ''; ?>
			</span>
			</div>
			<?php echo $form->submit('Login', array('class' => 'btn')); ?>
			<?php echo $form->close(); ?>
		</section>
		<a href="<?php echo $baseUrl.'user/login' ?>">Har du redan ett konto? logga in</a>
	</div>
<?php $this->load('layout/footer'); ?>