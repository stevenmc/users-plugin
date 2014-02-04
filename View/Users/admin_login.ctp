<div class="page-header">
    <h1><?php echo __d('users', 'Login'); ?></h1>
</div>

<?php
    echo $this->Form->create($model);
    echo $this->Form->input('email', array('placeholder' => __d('users', 'Email')));
    echo $this->Form->input('password', array('placeholder' => __d('users', 'Password')));
    // echo $this->Form->input('remember_me', array('opt-label' => __d('users', 'Remember Me'), 'type' => 'checkbox'));
    echo $this->Form->hidden('User.return_to', array('value' => $return_to));
?>

<div class="form-group">
    <?php echo $this->Html->link(__d('users', 'I forgot my password'), array('action' => 'reset_password')); ?>
</div>

<div class="form-actions">
    <?php echo $this->Form->button(__d('users', 'Login'), array('class' => 'btn btn-primary')); ?>
</div>
<?php echo $this->Form->end();?>