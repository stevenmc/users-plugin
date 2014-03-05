<div class="users view">
    <h2><?php echo __d('users', 'User'); ?></h2>
    <dl>
        <dt><?php echo __d('users', 'Username'); ?></dt>
        <dd><?php echo $user[$model]['username']; ?></dd>
        <dt><?php echo __d('users', 'Email address'); ?></dt>
        <dd><?php echo $user[$model]['email']; ?></dd>
        <dt><?php echo __d('users', 'Created'); ?></dt>
        <dd><?php echo $this->Time->niceShort($user[$model]['created']); ?></dd>
        <dt><?php echo __d('users', 'Modified'); ?></dt>
        <dd><?php echo $this->Time->niceShort($user[$model]['modified']); ?></dd>
    </dl>
</div>