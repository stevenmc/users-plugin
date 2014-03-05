<div class="users index">
	<h2><?php echo __d('users', 'Users'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $this->Paginator->sort('username'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th class="actions"><?php echo __d('users', 'Actions'); ?></th>
	</tr>
	<?php foreach ($users as $user): ?>
		<tr>
			<td>
				<?php echo $this->Gravatar->image($user[$model]['email']); ?>
				<?php echo $this->Html->link($user[$model]['username'], array('action' => 'view', $user[$model]['id'])); ?>
			</td>
			<td><?php echo $user[$model]['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__d('users', 'View'), array('action' => 'view', $user[$model]['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php echo $this->element('pagination'); ?>
</div>