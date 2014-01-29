<h2><?php echo __d('users', 'Forgot your password?'); ?></h2>
<p><?php echo __d('users', 'Please enter the email you used for registration and you\'ll get an email with further instructions.'); ?></p>
<?php
	echo $this->Form->create($model, array(
		'url' => array(
			'admin' => false,
			'action' => 'reset_password')));
	echo $this->Form->input('email', array(
		'label' => __d('users', 'Your Email')));
	echo $this->Form->submit(__d('users', 'Submit'));
	echo $this->Form->end();
?>