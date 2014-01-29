<?php
class usersSchema extends CakeSchema {
	var $name = 'users';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $user_details = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'index'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150),
		'dob' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'country' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'city' => array('type' => 'string', 'null' => true, 'length' => 100),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'USER' => array('column' => 'user_id', 'unique' => 0)
		)
	);
	public $users = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 128),
		'password_token' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 128),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'email_verified' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'email_token' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email_token_expires' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'last_action' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'is_admin' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'role' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'BY_USERNAME' => array('column' => 'username', 'unique' => 0),
			'BY_EMAIL' => array('column' => 'email', 'unique' => 0)
		)
	);
}
