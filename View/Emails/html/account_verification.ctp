<h2>Account activation</h2>
<p><?php echo __d('users', 'Hello %s,', $user[$model]['username']); ?></p>
<p><?php echo __d('users', 'to validate your account, you must visit the URL below within 24 hours'); ?></p>
<p><?php echo Router::url(array('admin' => true, 'plugin' => 'users', 'controller' => 'users', 'action' => 'verify', 'email', $user[$model]['email_token']), true);?></p>