<?php
echo __d('users', 'A request to reset your password was sent. To change your password click the link below.');
echo "\n";
echo Router::url(array('admin' => true, 'plugin' => 'users', 'controller' => 'users', 'action' => 'reset_password', $token), true);