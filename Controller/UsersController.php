<?php
App::uses('UsersAppController', 'Users.Controller');

class UsersController extends UsersAppController {

    public $name = 'Users';
    public $helpers = array('Html', 'Time', 'Text');
    public $components = array(
        'Auth' => array('authenticate' => 'BcryptForm'), 
        'Session',
        'Cookie',
        'Paginator',
        'Security'
    );
    public $_publicActions = array(
        'admin_login', 'admin_reset', 'admin_verify', 'admin_logout', 'admin_reset_password'
    );
/**
 * Constructor
 *
 * @param CakeRequest $request Request object for this controller. Can be null for testing,
 *  but expect that features that use the request parameters will not work.
 * @param CakeResponse $response Response object for this controller.
 */
    public function __construct($request, $response) {
        $this->_setupHelpers();
        parent::__construct($request, $response);
    }

/**
 * Setup helpers based on plugin availability
 *
 * @return void
 */ 
    protected function _setupHelpers() {
        if (App::import('Helper', 'Gravatar')) {
            $this->helpers[] = 'Gravatar';
        }
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->_setupAuth();

        $this->set('model', $this->modelClass);

        if (!Configure::read('App.defaultEmail')) {
            Configure::write('App.defaultEmail', 'reply@' . env('HTTP_HOST'));
        }
    }

    public function _setupAuth() {
        $this->Auth->allow($this->_publicActions);

        $this->Auth->authenticate = array(
            'BcryptForm' => array(
                'fields' => array(
                    'username' => 'email',
                    'password' => 'password'
                ),
                'userModel' => 'Users.User', 
                'scope' => array(
                    'User.active' => 1,
                    'User.email_verified' => 1
                )
            )
        );

        $this->Auth->loginRedirect = array('plugin' => 'users', 'controller' => 'users', 'action' => 'dashboard');
        $this->Auth->loginAction = array('plugin' => 'users', 'controller' => 'users', 'action' => 'login');
        $this->Auth->flash = array('element' => 'notifications/default', 'key' => 'auth', 'params' => array('class' => 'alert-error'));
    }

    public function beforeRender() {
        if(in_array($this->request->action, $this->_publicActions)) {
            $this->layout = 'guest';
        }
    }

/**
 * Common login action
 *
 * @return void
 */
    public function login() {
        if($this->request->is('post')) {
            if($this->Auth->login()) {
                if ($this->Auth->user()) {
                    $this->User->updateLastActivity($this->Auth->user('id'), 'last_login');
                    if (!empty($this->request->data)) {
                        $data = $this->request->data[$this->modelClass];
                        $this->_setCookie();
                    }
                    return $this->redirect($this->Auth->redirect());
                }
            } else {
                $this->flashMessage(__d('users', 'Invalid e-mail / password combination.  Please try again'), 'alert-error');
            }
        }

        if (isset($this->request->params['named']['return_to'])) {
            $this->set('return_to', urldecode($this->request->params['named']['return_to']));
        } else {
            $this->set('return_to', false);
        }
    }

/**
 * Common logout action
 *
 * @return void
 */
    public function logout() {
        $this->Session->destroy();
        $this->Cookie->destroy();
        $this->flashMessage(__d('users', 'You have successfully logged out'), 'alert-success', $this->Auth->logout());
    }

/**
 * Simple listing of all users
 *
 * @return void
 */
    public function index() {
        $this->paginate = array(
            'limit' => 12,
            'conditions' => array(
                $this->modelClass . '.active' => 1, 
                $this->modelClass . '.email_verified' => 1
            )
        );
        $this->set('users', $this->paginate($this->modelClass));
    }

/**
 * The homepage of a users giving him an overview about everything
 *
 * @return void
 */
    public function dashboard() {
        $user = $this->User->read(null, $this->Auth->user('id'));
        $this->set('user', $user);
    }

/**
 * Shows a users profile
 *
 * @param string $slug User Slug
 * @return void
 */
    public function view($slug = null) {
        try {
            $this->set('user', $this->User->view($slug));
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage());
            $this->redirect('/');
        }
    }

/**
 * User register action
 *
 * @return void
 */
    public function add() {
        if ($this->Auth->user()) {
            $this->Session->setFlash(__d('users', 'You are already registered and logged in!'));
            $this->redirect('/');
        }

        if (!empty($this->request->data)) {
            $user = $this->User->register($this->request->data);
            if ($user !== false) {
                $this->_sendVerificationEmail($this->User->data);
                $this->Session->setFlash(__d('users', 'Your account has been created. You should receive an e-mail shortly to authenticate your account. Once validated you will be able to login.'));
                $this->redirect(array('action' => 'login'));
            } else {
                unset($this->request->data[$this->modelClass]['password']);
                unset($this->request->data[$this->modelClass]['temppassword']);
                $this->Session->setFlash(__d('users', 'Your account could not be created. Please, try again.'), 'default', array('class' => 'message warning'));
            }
        }
    }

/**
 * Edit
 *
 * @param string $id User ID
 * @return void
 */
    public function edit() {
        if (!empty($this->request->data)) {
            $this->User->id = $this->Auth->user('id');
            if ($this->User->saveAssociated($this->request->data)) {
                $this->Session->setFlash(__d('users', 'Profile saved.'));
            } else {
                $this->Session->setFlash(__d('users', 'Could not save your profile.'));
            }
        } else {
            $this->request->data = $this->User->read(null, $this->Auth->user('id'));
        }
    }

/**
 * Confirm email action
 *
 * @param string $token Token
 * @return void
 */
    public function verify($token = null) {
        try {
            $this->User->verifyEmail($token);
            return $this->flashMessage(__d('users', 'Your e-mail has been validated!'), 'success', $this->Auth->loginAction);
        } catch (RuntimeException $e) {
            return $this->flashMessage($e->getMessage(), 'alert-error', '/');
        }
    }

/**
 * This method will send a new password to the user
 *
 * @param string $token Token
 * @return void
 */
    public function request_new_password($token = null) {
        if (Configure::read('Users.sendPassword') !== true) {
            throw new NotFoundException();
        }

        $data = $this->User->validateToken($token, true);

        if (!$data) {
            return $this->flashMessage(__d('users', 'The url you accessed is not longer valid'), 'alert-error', '/');
        }

        $email = $data[$this->modelClass]['email'];
        unset($data[$this->modelClass]['email']);

        if ($this->User->save($data, array('validate' => false))) {
            $this->_sendNewPassword($email, $data);
            return $this->flashMessage(__d('users', 'Your password was sent to your registered email account'), 'alert-info', 
            $this->Auth->loginAction);
        }

        $this->flashMessage(__d('users', 'There was an error verifying your account. Please check the email you were sent, and retry the verification link.'), 'alert-error', '/');
    }

/**
 * Allows the user to enter a new password, it needs to be confirmed by entering the old password
 *
 * @return void
 */
    public function change_password() {
        if ($this->request->is('post')) {
            $this->request->data['User']['id'] = $this->Auth->user('id');
            if ($this->User->changePassword($this->request->data)) {
                $this->flashMessage(__d('users', 'Password changed.'), 'alert-info', '/');
            }
        }
    }

/**
 * Reset Password Action
 *
 * Handles the trigger of the reset, also takes the token, validates it and let the user enter
 * a new password.
 *
 * @param string $token Token
 * @param string $user User Data
 * @return void
 */
    public function reset_password($token = null, $user = null) {
        if (empty($token)) {
            $admin = false;
            if ($user) {
                $this->request->data = $user;
                $admin = true;
            }
            $this->_sendPasswordReset($admin);
        } else {
            $this->_resetPassword($token);
        }
    }

    public function admin_index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function admin_dashboard() {
        $user = $this->User->read(null, $this->Auth->user('id'));
        $this->set('user', $user);
    }

    public function admin_view($id = null) {
        if (!$id) {
            $this->flashMessage(__d('users', 'Invalid User.'), 'alert-error', array('action' => 'index'));
        }

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function admin_add() {
        if ($this->User->add($this->request->data)) {
            $this->flashMessage(__d('users', 'The User has been saved'), 'alert-success', array('action' => 'index'));
        }
    }

    public function admin_edit($userId = null) {
        try {
            $result = $this->User->edit($userId, $this->request->data);
            if ($result === true) {
                $this->flashMessage(__d('users', 'The User has been saved'), 'alert-success', array('action' => 'index'));
            } else {
                $this->request->data = $result;
            }
        } catch (OutOfBoundsException $e) {
            $this->flashMessage($e->getMessage(), 'alert-error', array('action' => 'index'));
        }

        if (empty($this->request->data)) {
            $this->request->data = $this->User->read(null, $userId);
        }
    }

    public function admin_delete($userId = null) {
        if ($this->User->delete($userId)) {
            $this->flashMessage(__d('users', 'The User has been deleted'), 'alert-success', array('action' => 'index'));
        } else {
            $this->flashMessage('Invalid User.', 'alert-error', array('action' => 'index'));
        }
    }

    public function admin_login() {
        $this->login();
    }

    public function admin_logout() {
        $this->logout();
    }

    public function admin_verify($token = null) {
        $this->verify($token);
    }

    public function admin_request_new_password($token = null) {
        $this->request_new_password($token);
    }

    public function admin_change_password() {
        $this->change_password();
    }

    public function admin_reset_password($token = null, $user = null) {
        $this->reset_password($token, $user);
    }

/**
 * Sends the verification email
 *
 * This method is protected and not private so that classes that inherit this
 * controller can override this method to change the varification mail sending
 * in any possible way.
 *
 * @param string $to Receiver email address
 * @param array $options EmailComponent options
 * @return boolean Success
 */
    protected function _sendVerificationEmail($userData, $options = array()) {
        $defaults = array(
            'from' => Configure::read('App.defaultEmail'),
            'subject' => __d('users', 'Account verification'),
            'template' => 'Users.account_verification'
        );

        $options = array_merge($defaults, $options);

        $Email = $this->_getMailInstance();
        $Email->to($userData[$this->modelClass]['email'])
            ->from($options['from'])
            ->subject($options['subject'])
            ->emailFormat('both')
            ->template($options['template'])
            ->viewVars(array(
                'model' => $this->modelClass,
                'user' => $userData)
            )
            ->send();
    }

/**
 * Sends the password reset email
 *
 * @param array
 * @return void
 */
    protected function _sendNewPassword($email, $userData) {
        $Email = $this->_getMailInstance();
        $Email->from(Configure::read('App.defaultEmail'))
            ->to($email)
            ->replyTo(Configure::read('App.defaultEmail'))
            ->subject(env('HTTP_HOST') . ' ' . __d('users', 'Password Reset'))
            ->emailFormat('both')
            ->template('new_password')
            ->viewVars(array(
                'model' => 'User',
                'userData' => $userData
            ))
            ->send();
    }


/**
 * Checks if the email is in the system and authenticated, if yes create the token
 * save it and send the user an email
 *
 * @param boolean $admin Admin boolean
 * @param array $options Options
 * @return void
 */
    protected function _sendPasswordReset($admin = null, $options = array()) {
        $defaults = array(
            'from' => Configure::read('App.defaultEmail'),
            'subject' => __d('users', 'Password Reset'),
            'template' => 'Users.password_reset_request'
        );

        $options = array_merge($defaults, $options);

        if (!empty($this->request->data)) {
            $user = $this->User->passwordReset($this->request->data);

            if (!empty($user)) {

                $Email = $this->_getMailInstance();
                $Email->to($user[$this->modelClass]['email'])
                    ->from($options['from'])
                    ->subject($options['subject'])
                    ->emailFormat('both')
                    ->template($options['template'])
                    ->viewVars(array(
                        'model' => $this->modelClass,
                        'user' => $this->User->data,
                        'token' => $this->User->data[$this->modelClass]['password_token']))
                    ->send();

                if ($admin) {
                    $this->flashMessage(sprintf(
                        __d('users', '%s has been sent an email with instructions to reset their password.'),
                        $user[$this->modelClass]['email']), 'alert-info', array('action' => 'index', 'admin' => true));
                } else {
                    $this->flashMessage(__d('users', 'You should receive an email with further instructions shortly'), 'alert-info', 
                    $this->Auth->loginAction);
                }
            } else {
                $this->flashMessage(__d('users', 'No user was found with that email.'), 'alert-error');
            }
        }
        $this->render('admin_request_password_change');
    }

/**
 * This method allows the user to change his password if the reset token is correct
 *
 * @param string $token Token
 * @return void
 */
    protected function _resetPassword($token) {
        $user = $this->User->checkPasswordToken($token);
        if (empty($user)) {
            $this->flashMessage(__d('users', 'Invalid password reset token, try again.'), 'alert-error', array('action' => 'reset_password'));
        }

        if (!empty($this->request->data) && $this->User->resetPassword(Set::merge($user, $this->request->data))) {
            $this->flashMessage(__d('users', 'Password changed, you can now login with your new password.'), 'alert-success', 
            $this->Auth->loginAction);
        }

        $this->set('token', $token);
    }

/**
 * Sets the cookie to remember the user
 *
 * @param array Cookie component properties as array, like array('domain' => 'yourdomain.com')
 * @param string Cookie data keyname for the userdata, its default is "User". This is set to User and NOT using the model alias to make sure it works with different apps with different user models across different (sub)domains.
 * @return void
 * @link http://book.cakephp.org/2.0/en/core-libraries/components/cookie.html
 */
    protected function _setCookie($options = array(), $cookieKey = 'User') {
        if (empty($this->request->data[$this->modelClass]['remember_me'])) {
            $this->Cookie->delete($cookieKey);
        } else {
            $validProperties = array('domain', 'key', 'name', 'path', 'secure', 'time');
            $defaults = array('name' => 'rememberMe');

            $options = array_merge($defaults, $options);
            foreach ($options as $key => $value) {
                if (in_array($key, $validProperties)) {
                    $this->Cookie->{$key} = $value;
                }
            }

            $cookieData = array(
                'username' => $this->request->data[$this->modelClass]['username'],
                'password' => $this->request->data[$this->modelClass]['password']
            );
            $this->Cookie->write($cookieKey, $cookieData, true, '1 Month');
        }
        unset($this->request->data[$this->modelClass]['remember_me']);
    }

/**
 * Returns a CakeEmail object
 *
 * @return object CakeEmail instance
 * @link http://book.cakephp.org/2.0/en/core-utility-libraries/email.html
 */
    protected function _getMailInstance() {
        App::uses('CakeEmail', 'Network/Email');
        return new CakeEmail();
    }

}
