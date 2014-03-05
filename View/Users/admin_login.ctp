<div class="page-header">
    <h1><?php echo __d('users', 'Login'); ?></h1>
</div>
<?php 
    echo $this->Form->create($model, array('class' => 'form-vertical'));
    echo $this->Form->input('email', array('placeholder' => 'Email', 'label' => false, 'class' => 'span5'));
    echo $this->Form->input('password', array('placeholder' => 'Password', 'label' => false, 'class' => 'span5'));
    // echo $this->Form->input('remember_me', array('opt-label' => __d('users', 'Remember Me'), 'type' => 'checkbox'));
    echo $this->Form->hidden('User.return_to', array('value' => $return_to)); 
?>
<p class="controls">
    <?php echo $this->Html->link(__d('users', 'I forgot my password'), array('action' => 'reset_password')); ?>
</p>
<?php echo $this->Form->button(__d('users', 'Login'), array('class' => 'btn btn-primary')); ?>
<?php echo $this->Form->end(); ?>
