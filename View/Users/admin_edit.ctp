<div class="users form">
    <?php echo $this->Form->create($model); ?>
        <h2><?php echo $this->Html->link(__d('users', 'Users'), array('action' => 'index')); ?> - <?php echo __('Edit'); ?></h2>
        <?php
            echo $this->Form->hidden('id');
            echo $this->Form->input('username', array('label' => __d('users', 'Username')));
            echo $this->Form->input('email', array('label' => __d('users', 'Email')));
            echo $this->Form->input('is_admin', array('label' => __d('users', 'Admin')));
            echo $this->Form->input('active', array('label' => __d('users', 'Active')));
        ?>
        <?php if($this->Form->value('id') === $this->Session->read('Auth.User.id')) { ?>
            <p class="controls"><?php echo $this->Html->link('Change your password', array('action' => 'change_password')); ?></p>
        <?php } ?>
        <div class="form-actions">
            <?php echo $this->Form->button(__d('users', 'Save'), array('class' => 'btn btn-primary')); ?>
            <?php echo $this->Form->button(__d('users', 'Reset'), array('class' => 'btn', 'type' => 'reset')); ?>
        </div>
    <?php echo $this->Form->end(); ?>
</div>
