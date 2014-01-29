<?php
// Router::connect('/users', array('plugin' => 'users', 'controller' => 'users'), array('routeClass' => 'I18nRoute'));
// Router::connect('/users/index/*', array('plugin' => 'users', 'controller' => 'users'), array('routeClass' => 'I18nRoute'));
// Router::connect('/users/:action/*', array('plugin' => 'users', 'controller' => 'users'), array('routeClass' => 'I18nRoute'));
// Router::connect('/users/users/:action', array('plugin' => 'users', 'controller' => 'users'), array('routeClass' => 'I18nRoute'));

Router::connect('/admin/users', array('plugin' => 'users', 'controller' => 'users', 'admin' => true), array('routeClass' => 'I18nRoute'));
Router::connect('/admin/users/:action/*', array('plugin' => 'users', 'controller' => 'users', 'admin' => true), array('routeClass' => 'I18nRoute'));

Router::connect('/moderator/users', array('plugin' => 'users', 'controller' => 'users', 'moderator' => true), array('routeClass' => 'I18nRoute'));
Router::connect('/moderator/users/:action/*', array('plugin' => 'users', 'controller' => 'users', 'moderator' => true), array('routeClass' => 'I18nRoute'));
