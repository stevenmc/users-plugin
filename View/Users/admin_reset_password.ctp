<div class="page-header">
    <h1><?php echo __d('users', 'Reset your password'); ?></h1>
</div>

<?php
    echo $this->Form->create($model, array('url' => array('action' => 'reset_password', $token)));
    echo $this->Form->input('new_password', array('type' => 'password', 'placeholder' => __d('users', 'New password')));
    echo $this->Form->input('confirm_password', array('placeholder' => __d('users', 'Confirm password'), 'type' => 'password'));
?>
<div class="form-actions">
    <?php echo $this->Form->button(__d('users', 'Change password'), array('class' => 'btn btn-primary')); ?>
    <?php echo $this->Form->button(__d('users', 'Cancel'), array('class' => 'btn', 'type' => 'reset')); ?>
</div>
<?php echo $this->Form->end();?>