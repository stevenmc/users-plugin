<div class="page-header">
    <h1><?php echo __d('users', 'Create a user'); ?></h1>
</div>

<?php
    echo $this->Form->create($model);
    echo $this->Form->input('username', array('label' => __d('users', 'Username')));
    echo $this->Form->input('email', array(
        'label' => __d('users', 'Email'),
        'error' => array(
            'isValid' => __d('users', 'Must be a valid email address'),
            'isUnique' => __d('users', 'An account with that email already exists')
        )
    ));
    echo $this->Form->input('role', array('label' => __d('users', 'Role')));
    echo $this->Form->input('password', array('label' => __d('users', 'Password'), 'type' => 'password'));
    echo $this->Form->input('temppassword', array('label' => __d('users', 'Confirm password'), 'type' => 'password'));
    echo $this->Form->hidden('is_admin', array('label' => __d('users', 'Admin'), 'value' => 1));
    echo $this->Form->hidden('active', array('label' => __d('users', 'Active'), 'value' => 1));
?>
<div class="form-actions">
    <?php echo $this->Form->button(__d('users', 'Create'), array('class' => 'btn btn-primary')); ?>
    <?php echo $this->Form->button(__d('users', 'Cancel'), array('class' => 'btn', 'type' => 'reset')); ?>
</div>
<?php echo $this->Form->end();?>