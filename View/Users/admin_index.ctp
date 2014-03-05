<div class="users index">
    <div class="admin">
        <h2 class="col-sm-6"><?php echo __d('users', 'Users'); ?></h2>
        <?php echo $this->Html->link(__d('users', 'Add'), array('action' => 'add'), array('class' => 'btn btn-success icon icon-add')); ?>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><?php echo $this->Paginator->sort('username'); ?></th>
                    <th><?php echo $this->Paginator->sort('email'); ?></th>
                    <th><?php echo $this->Paginator->sort('email_verified'); ?></th>
                    <th><?php echo $this->Paginator->sort('active'); ?></th>
                    <th><?php echo $this->Paginator->sort('created'); ?></th>
                    <th><?php echo __d('users', 'Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user){ ?>
                <tr>
                    <td>
                        <?php echo $user[$model]['username']; ?>
                    </td>
                    <td>
                        <?php echo $user[$model]['email']; ?>
                    </td>
                    <td>
                        <?php echo $user[$model]['email_verified'] == 1 ? __d('users', 'Yes') : __d('users', 'No'); ?>
                    </td>
                    <td>
                        <?php echo $user[$model]['active'] == 1 ? __d('users', 'Yes') : __d('users', 'No'); ?>
                    </td>
                    <td>
                        <?php echo $this->Time->timeAgoInWords($user[$model]['created']); ?>
                    </td>
                    <td class="actions">
                        <?php echo $this->Html->link(__d('users', 'View'), array('admin' => true, 'plugin' => 'users', 'controller' => 'users', 'action'=>'view', $user[$model]['id'])); ?>
                        <?php echo $this->Html->link(__d('users', 'Edit'), array('action'=>'edit', $user[$model]['id'])); ?>
                        <?php echo $this->Html->link(__d('users', 'Delete'), array('action'=>'delete', $user[$model]['id']), null, sprintf(__d('users', 'Are you sure you want to delete # %s?'), $user[$model]['id'])); ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php echo $this->element('pagination'); ?>
</div>
