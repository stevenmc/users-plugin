<?php
echo __d('users', 'Hello %s,', $user[$model]['username']);
echo "\n";
echo __d('users', 'to validate your account, you must visit the URL below within 24 hours');
echo "\n";
echo Router::url(array('admin' => true, 'plugin' => 'users', 'controller' => 'users', 'action' => 'verify', 'email', $user[$model]['email_token']), true);
