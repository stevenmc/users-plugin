<div class="users view">
<h2><?php echo __d('users', 'User'); ?></h2>
	<?php echo $user[$model]['username']; ?>
	<?php echo $user['UserDetail']['first_name']; ?>
	<?php echo $user['UserDetail']['last_name']; ?>
	<?php echo $user['UserDetail']['dob']; ?>
	<?php echo $user['UserDetail']['country']; ?>
	<?php echo $user[$model]['created']; ?>
</div>