<div class="users form">
	<?php echo $this->Form->create($model); ?>
		<fieldset>
			<legend><?php echo __d('users', 'Edit User'); ?></legend>
			<?php
	            echo $this->Form->hidden('id');
	            // echo $this->Form->input('username', array('label' => __d('users', 'Username')));
	            echo $this->Form->input('email', array('label' => __d('users', 'Email')));
				echo $this->Form->input('UserDetail.first_name');
				echo $this->Form->input('UserDetail.last_name');
				echo $this->Form->input('UserDetail.dob', array('type' => 'text'));
			?>
			<p>
				<?php echo $this->Html->link(__d('users', 'Change your password'), array('action' => 'change_password')); ?>
			</p>
		</fieldset>
	<?php echo $this->Form->submit(__d('users', 'Submit')); ?>
	<?php echo $this->Form->end(); ?>
</div>