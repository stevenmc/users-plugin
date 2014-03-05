<div class="users form">
    <h2><?php echo $this->Html->link(__d('users', 'Users'), array('action' => 'index')); ?> - <?php echo __d('users', 'Add'); ?></h2>
    <?php
        echo $this->Form->create($model);
        echo $this->Form->input('username', array('class' => 'input-xlarge'));
        echo $this->Form->input('email', array(
            'label' => __d('users', 'E-mail (used as login)'),
            'error' => array(
                'isValid' => __d('users', 'Must be a valid email address'),
                'isUnique' => __d('users', 'An account with that email already exists')
            ),
            'class' => 'input-xlarge'
        ));
        echo $this->Form->input('password', array('label' => __d('users', 'Password'), 'type' => 'password', 'class' => 'input-xlarge'));
        echo $this->Form->input('temppassword', array('label' => __d('users', 'Password (confirm)'), 'type' => 'password', 'class' => 'input-xlarge'));
        echo $this->Form->input('is_admin', array('label' => __d('users', 'Admin')));
        echo $this->Form->input('email_verified', array('label' => __d('users', 'Email verified')));
        echo $this->Form->submit(__d('users', 'Save'), array('class' => 'btn btn-primary'));
        echo $this->Form->end();
    ?>
</div>