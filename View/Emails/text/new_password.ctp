<?php
echo __d('users', 'Your password has been reset');
echo __d('users', 'Please login using this password and change your password');
echo "\n";
__d('users', 'Your new password is: %s', $userData[$model]['new_password']);