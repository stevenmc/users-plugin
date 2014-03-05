<?php
Router::connect('/users', array('plugin' => 'users', 'controller' => 'users'));
Router::connect('/users/index/*', array('plugin' => 'users', 'controller' => 'users'));
Router::connect('/users/:action', array('plugin' => 'users', 'controller' => 'users'));
Router::connect('/users/users/:action', array('plugin' => 'users', 'controller' => 'users'));

Router::connect('/admin/users', array('plugin' => 'users', 'controller' => 'users', 'admin' => true));
Router::connect('/admin/users/:action/*', array('plugin' => 'users', 'controller' => 'users', 'admin' => true));
Router::connect('/admin/login/*', array('plugin' => 'users', 'controller' => 'users', 'action' => 'login', 'admin' => true));
Router::connect('/admin/logout/*', array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout', 'admin' => true));