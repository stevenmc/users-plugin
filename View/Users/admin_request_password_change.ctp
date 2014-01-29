<div class="page-header">
    <h1><?php echo __d('users', 'Forgot your password?'); ?></h1>
</div>

<span class="help-block">
    <?php echo __d('users', 'Please enter the email you used for registration and you\'ll get an email with further instructions.'); ?>
</span>

<?php
	echo $this->Form->create($model, array('url' => array('action' => 'reset_password')));
	echo $this->Form->input('email', array('placeholder' => __d('users', 'Email')));
?>
<div class="form-actions">
    <?php echo $this->Form->button(__d('users', 'Submit'), array('class' => 'btn btn-primary')); ?>
    <?php echo $this->Form->button(__d('users', 'Cancel'), array('class' => 'btn', 'type' => 'reset')); ?>
</div>
<?php echo $this->Form->end(); ?>