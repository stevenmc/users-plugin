<div class="users form">
    <div class="page-header">
        <h1><?php echo __d('users', 'Forgot your password?'); ?></h1>
    </div>
    <p><?php echo __d('users', 'Please enter the email you used for registration and you\'ll get an email with further instructions.'); ?></p>

    <?php
    	echo $this->Form->create($model, array('url' => array('action' => 'reset_password'), 'class' => 'form-vertical'));
    	echo $this->Form->input('email', array('label' => false, 'class' => 'span5', 'placeholder' => __d('users', 'Email')));
    ?>
    <?php echo $this->Form->button(__d('users', 'Submit'), array('class' => 'btn btn-primary')); ?>
    <?php echo $this->Form->end(); ?>
</div>