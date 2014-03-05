<?php
App::uses('UsersAppModel', 'Users.Model');

class UserDetail extends UsersAppModel {

    public $name = 'UserDetail';
    public $displayField = 'field';
    public $validate = array(
        'dob' => 'date',
    );

    public function __construct($id = false, $table = null, $ds = null) {
        $userClass = Configure::read('App.UserClass');
        if (empty($userClass)) {
            $userClass = 'Users.User';
        }
        $this->belongsTo['User'] = array(
            'className' => $userClass,
            'foreignKey' => 'user_id'
        );
        parent::__construct($id, $table, $ds);
    }

}