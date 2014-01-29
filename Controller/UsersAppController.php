<?php
App::uses('AppController', 'Controller');

class UsersAppController extends AppController {

/**
 * Default isAuthorized method
 *
 * This is called to see if a user (when logged in) is able to access an action
 *
 * @param array $user
 * @return boolean True if allowed
 */
    public function isAuthorized($user) {
        return parent::isAuthorized($user);
    }

}
