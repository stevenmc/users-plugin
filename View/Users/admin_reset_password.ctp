<div class="users form">
    <div class="page-header">
        <h1><?php echo __d('users', 'Reset your password'); ?></h1>
    </div>
    <?php
        echo $this->Form->create($model, array('url' => array('action' => 'reset_password', $token), 'class' => 'form-vertical'));
        echo $this->Form->input('new_password', array('type' => 'password', 'label' => false, 'placeholder' => __d('users', 'New password'), 'class' => 'span5'));
        echo $this->Form->input('confirm_password', array('label' => false, 'placeholder' => __d('users', 'Confirm password'), 'type' => 'password', 'class' => 'span5'));
        echo $this->Form->button(__d('users', 'Submit'), array('class' => 'btn btn-primary'));
        echo $this->Form->end(); 
    ?>
</div>