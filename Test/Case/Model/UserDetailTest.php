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
 * DetailTestCase
 *
 * @package users
 * @subpackage users.tests.cases.models
 */
class UserDetailTestCase extends CakeTestCase {

/**
 * Detail instance
 *
 * @var object
 */
    public $UserDetail = null;

/**
 * Fixtures
 *
 * @var array
 */
    public $fixtures = array(
        'plugin.users.user',
        'plugin.users.user_detail');

/**
 * start
 *
 * @return void
 */
    public function setUp() {
        Configure::write('App.UserClass', null);
        $this->UserDetail = ClassRegistry::init('Users.UserDetail');
    }

    public function tearDown() {
        ClassRegistry::flush();
        unset($this->UserDetail);
    }
/**
 * testDetailInstance
 *
 * @return void
 */
    public function testDetailInstance() {
        $this->assertTrue(is_a($this->UserDetail, 'UserDetail'));
    }

/**
 * testDetailFind
 *
 * @return void
 */
    public function testUserDetailFind() {
        $this->UserDetail->recursive = -1;
        $results = $this->UserDetail->find('all');
        $this->assertTrue(!empty($results));
        $this->assertTrue(is_array($results));
    }

}