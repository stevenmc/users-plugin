<div class="page-header">
    <h1><?php echo __d('users', 'Change your password'); ?></h1>
</div>

<span class="help-block">
	<?php echo __d('users', 'Please enter your old password because of security reasons and then your new password twice.'); ?>
</span>
<?php
	echo $this->Form->create($model);
	echo $this->Form->input('old_password', array(
		'label' => __d('users', 'Old Password'),
		'type' => 'password'
	));
	echo $this->Form->input('new_password', array(
		'label' => __d('users', 'New Password'),
		'type' => 'password'
	));
	echo $this->Form->input('confirm_password', array(
		'label' => __d('users', 'Confirm password'),
		'type' => 'password'
	));
?>
<div class="form-actions">
    <?php echo $this->Form->button(__d('users', 'Save changes'), array('class' => 'btn btn-primary')); ?>
    <?php echo $this->Form->button(__d('users', 'Cancel'), array('class' => 'btn', 'type' => 'reset')); ?>
</div>
<?php echo $this->Form->end();?>