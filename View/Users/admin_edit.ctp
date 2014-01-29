<div class="page-header">
    <h1><?php echo __d('users', 'Edit user'); ?></h1>
</div>

<?php echo $this->Form->create($model);?>
<?php
    echo $this->Form->hidden('id');
    echo $this->Form->input('username', array('label' => __d('users', 'Username')));
    echo $this->Form->input('email', array('label' => __d('users', 'Email')));
    echo $this->Form->input('role', array('label' => __d('users', 'Role')));
    echo $this->Form->hidden('is_admin', array('label' => __d('users', 'Admin'), 'value' => 1));
    echo $this->Form->hidden('active', array('label' => __d('users', 'Active'), 'value' => 1));
?>
<?php if ($this->Form->value('id') === $this->User->info('id')) { ?>
    <div class="input">
        <?php echo $this->Html->link('Change your password', array('action' => 'change_password')); ?>
    </div>
<?php } ?>
<div class="form-actions">
    <?php echo $this->Form->button(__d('users', 'Save'), array('class' => 'btn btn-primary')); ?>
    <?php echo $this->Form->button(__d('users', 'Cancel'), array('class' => 'btn', 'type' => 'reset')); ?>
</div>
<?php echo $this->Form->end(); ?>