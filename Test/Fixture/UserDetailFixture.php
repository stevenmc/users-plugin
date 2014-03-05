<?php
/**
 * Copyright 2010 - 2011, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2011, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * User Detail Fixture
 *
 * @package users
 * @subpackage users.test.fixtures
 */
class UserDetailFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
    public $name = 'UserDetail';

/**
 * Table
 *
 * @var array $table
 */
    public $table = 'user_details';

/**
 * Fields
 *
 * @var array $fields
 */
    public $fields = array(
        'id' => array('type'=>'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
        'user_id' => array('type'=>'string', 'null' => false, 'length' => 36),
        'first_name' => array('type'=>'string', 'null' => false),
        'last_name' => array('type'=>'string', 'null' => false),
        'dob' => array('type'=>'date', 'null' => true, 'default' => NULL),
        'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
        )
    );

/**
 * Records
 *
 * @var array $records
 */
    public $records = array(
        array(
            'id'  => '491d06d1-0648-407b-81f5-347182f0cb67',
            'user_id'  => '47ea303a-3b2c-4251-b313-4816c0a800fa', //phpnut
            'first_name' => 'Ricky',
            'last_name' => 'Dunlop',
            'dob' => '1983-08-16',
            'created'  => '2008-03-25 01:47:31',
            'modified'  => '2008-03-25 01:47:31'
        ),
        array(
            'id'  => '491d0704-5e68-4de3-91c7-345c82f0cb67',
            'user_id'  => 1,
            'first_name' => 'Ricky',
            'last_name' => 'Dunlop',
            'dob' => '1983-08-16',
            'created'  => '2008-03-25 01:47:31',
            'modified'  => '2008-03-25 01:47:31')
    );
}