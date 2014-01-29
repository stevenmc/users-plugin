<div class="page-header">
    <h1><?php echo __d('users', 'CMS Users'); ?></h1>
</div>

<table class="table table-striped table-bordered">
	<tr>
		<th><?php echo $this->Paginator->sort('username'); ?></th>
		<th><?php echo $this->Paginator->sort('email'); ?></th>
		<th><?php echo $this->Paginator->sort('role'); ?></th>
		<th><?php echo __d('users', 'Actions'); ?></th>
	</tr>
		<?php foreach ($users as $user) { ?>
		<tr>
			<td><?php echo $user[$model]['username']; ?></td>
			<td><?php echo $user[$model]['email']; ?></td>
			<td><?php echo $user[$model]['role']; ?></td>
			<td>
				<?php echo $this->Html->link(__d('users', 'Edit'), array('action' => 'edit', $user[$model]['id']), array('class' => 'btn btn-small btn-primary')); ?>
				<?php echo $this->Html->link(__d('users', 'Delete'), array('action' => 'delete', $user[$model]['id']), array('class' => 'btn btn-small btn-danger'), sprintf(__d('users', 'Are you sure you want to delete # %s?'), $user[$model]['id'])); ?>
			</td>
		</tr>
	<?php } ?>
</table>
<?php echo $this->Html->link(
	__d('users', 'Create a new user'),
	array('action' => 'add'),
	array('class' => 'btn btn-success')
); ?>
<?php echo $this->element('admin/pagination'); ?>