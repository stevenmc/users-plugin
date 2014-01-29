<?php
App::uses('UsersAppController', 'Users.Controller');

class UsersController extends UsersAppController {

    public $name = 'Users';

/**
 * Helpers
 * @var array
 */
    public $helpers = array(
        'Html',
        'Time',
        'Text'
    );

/**
 * Components
 * @var array
 */
    public $components = array(
        // 'Auth',
        'Session',
        'Cookie',
        'Paginator',
        'Security',
        'Users.RememberMe'
    );

/**
 * Actions that dont require login
 * @var array
 */
    public $_publicActions = array(
        // 'reset', 'verify', 'logout', 'view', 'reset_password', 'login',
        'admin_login', 'admin_reset', 'admin_verify', 'admin_logout', 'admin_reset_password',
        'moderator_login', 'moderator_reset', 'moderator_verify', 'moderator_logout', 'moderator_reset_password',
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
 * Returns $this->plugin with a dot, used for plugin loading using the dot notation
 *
 * @return mixed string|null
 */
    protected function _pluginDot() {
        if (is_string($this->plugin)) {
            return $this->plugin . '.';
        }
        return $this->plugin;
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
        $this->Auth->allow($this->_publicActions);

        $this->set('model', $this->modelClass);

        if (!Configure::read('App.defaultEmail')) {
            Configure::write('App.defaultEmail', 'reply@' . env('HTTP_HOST'));
        }
    }

    public function beforeRender() {
        if (in_array($this->request->action, $this->_publicActions)) {
            $this->layout = 'guest';
        }
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
        $user = $this->{$this->modelClass}->read(null, $this->Auth->user('id'));
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
            $this->set('user', $this->{$this->modelClass}->view($slug));
        } catch (Exception $e) {
            return $this->flashMessage($e->getMessage(), 'alert-error', '/');
        }
    }

/**
 * User register action
 *
 * @return void
 */
    public function add() {
        if ($this->Auth->user()) {
            return $this->flashMessage(__d('users', 'You are already registered and logged in!'), 'alert-warning', '/');
        }

        if (!empty($this->request->data)) {
            $user = $this->{$this->modelClass}->register($this->request->data);
            if ($user !== false) {
                $this->_sendVerificationEmail($this->{$this->modelClass}->data);
                return $this->flashMessage(__d('users', 'Your account has been created. You should receive an e-mail shortly to authenticate your account. Once validated you will be able to login.'), 'alert-success', array('action' => 'login'));
            } else {
                unset($this->request->data[$this->modelClass]['password']);
                unset($this->request->data[$this->modelClass]['temppassword']);
                return $this->flashMessage(__d('users', 'Your account could not be created. Please, try again.'), 'alert-error');
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
        try {
            $result = $this->{$this->modelClass}->edit($this->Auth->user('id'), $this->request->data);
            if ($result === true) {
                $this->flashMessage(__d('users', 'The user has been updated'), 'alert-success', array('action' => 'index'));
            } else {
                $this->request->data = $result;
            }
        } catch (OutOfBoundsException $e) {
            $this->request->data = null;
            $this->flashMessage($e->getMessage(), 'alert-error', array('action' => 'index'));
        }
    }

/**
 * Admin Index
 *
 * @return void
 */
    public function admin_index() {
        $this->{$this->modelClass}->recursive = 0;
        $this->set('users', $this->paginate());
    }

/**
 * Admin view
 *
 * @param string $id User ID
 * @return void
 */
    public function admin_view($id = null) {
        if (!$id) {
            $this->flashMessage(__d('users', 'Invalid User.'), 'alert-error', array('action' => 'index'));
        }

        $this->{$this->modelClass}->id = $id;
        if (!$this->{$this->modelClass}->exists()) {
            throw new NotFoundException(__d('users', 'Invalid user'));
        }
        $this->set('user', $this->{$this->modelClass}->read(null, $id));
    }

/**
 * Admin add
 *
 * @return void
 */
    public function admin_add() {
        if (!empty($this->request->data)) {
            $this->request->data[$this->modelClass]['email_verified'] = true;

            if ($this->{$this->modelClass}->add($this->request->data)) {
                $this->flashMessage(__d('users', 'The user has been created'), 'alert-success', array('action' => 'index'));
            }
        }
        $this->set('roles', Configure::read('Users.roles'));
    }

/**
 * Admin edit
 *
 * @param string $id User ID
 * @return void
 */
    public function admin_edit($userId = null) {
        try {
            $result = $this->{$this->modelClass}->edit($userId, $this->request->data);
            if ($result === true) {
                $this->flashMessage(__d('users', 'The user has been updated'), 'alert-success', array('action' => 'index'));
            } else {
                $this->request->data = $result;
            }
        } catch (OutOfBoundsException $e) {
            $this->flashMessage($e->getMessage(), 'alert-error', array('action' => 'index'));
        }

        if (empty($this->request->data)) {
            $this->request->data = $this->{$this->modelClass}->read(null, $userId);
        }

        $this->set('roles', Configure::read('Users.roles'));
    }

    public function admin_delete($userId = null) {
        if ($this->{$this->modelClass}->delete($userId)) {
            $this->flashMessage(__d('users', 'The user has been deleted'), 'alert-success', array('action' => 'index'));
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

    public function moderator_login() {
        $this->login();
        $this->render('admin_login');
    }

    public function moderator_logout() {
        $this->logout();
    }



/**
 * Common login action
 *
 * @return void
 */
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->getEventManager()->dispatch(new CakeEvent('Users.afterLogin', $this, array(
                    'isFirstLogin' => !$this->Auth->user('last_login'))));

                if ($this->here == $this->Auth->loginRedirect) {
                    $this->Auth->loginRedirect = '/';
                }

                if (!empty($this->request->data)) {
                    $data = $this->request->data[$this->modelClass];
                    if (empty($this->request->data[$this->modelClass]['remember_me'])) {
                        $this->RememberMe->destroyCookie();
                    } else {
                        $this->_setCookie();
                    }
                }

                if (empty($data['return_to'])) {
                    $data['return_to'] = $this->Auth->redirectUrl();
                }

                return $this->flashMessage(__d('users', 'You have successfully logged in'), 'alert-success', $data['return_to']);
            } else {
                $this->flashMessage(__d('users', 'Invalid e-mail / password combination.  Please try again'), 'alert-error');
            }
        }
        if (isset($this->request->params['named']['return_to'])) {
            $this->set('return_to', urldecode($this->request->params['named']['return_to']));
        } else {
            $this->set('return_to', false);
        }

        $allowRegistration = Configure::read('Users.allowRegistration');
        $this->set('allowRegistration', (is_null($allowRegistration) ? true : $allowRegistration));
    }

/**
 * Common logout action
 *
 * @return void
 */
    public function logout() {
        $this->Session->destroy();
        $this->Cookie->destroy();
        $this->RememberMe->destroyCookie();
        return $this->redirect($this->Auth->logout());
    }

/**
 * Confirm email action
 *
 * @param string $type Type, deprecated, will be removed. Its just still there for a smooth transistion.
 * @param string $token Token
 * @return void
 */
    public function verify($type = 'email', $token = null) {
        if ($type == 'reset') {
            // Backward compatiblity
            $this->request_new_password($token);
        }

        try {
            $this->{$this->modelClass}->verifyEmail($token);
            return $this->flashMessage(__d('users', 'Your e-mail has been validated!'), 'alert-success', $this->Auth->loginAction);
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

        $data = $this->{$this->modelClass}->validateToken($token, true);

        if (!$data) {
            return $this->flashMessage(__d('users', 'The url you accessed is not longer valid'), 'alert-error', '/');
        }

        $email = $data[$this->modelClass]['email'];
        unset($data[$this->modelClass]['email']);

        if ($this->{$this->modelClass}->save($data, array('validate' => false))) {
            $this->_sendNewPassword($email, $data);
            return $this->flashMessage(__d('users', 'Your password was sent to your registered email account'), 'alert-info', $this->Auth->loginAction);
        }

        $this->flashMessage(__d('users', 'There was an error verifying your account. Please check the email you were sent, and retry the verification link.'), 'alert-error', '/');
    }

/**
 * Sends the password reset email
 *
 * @param array
 * @return void
 */
    protected function _sendNewPassword($userData) {
        $Email = $this->_getMailInstance();
        $Email->from(Configure::read('App.defaultEmail'))
            ->to($userData[$this->modelClass]['email'])
            ->replyTo(Configure::read('App.defaultEmail'))
            ->return(Configure::read('App.defaultEmail'))
            ->subject(env('HTTP_HOST') . ' ' . __d('users', 'Password Reset'))
            ->template($this->_pluginDot() . 'new_password')
            ->viewVars(array(
                'model' => $this->modelClass,
                'userData' => $userData))
            ->send();
    }


/**
 * Allows the user to enter a new password, it needs to be confirmed by entering the old password
 *
 * @return void
 */
    public function change_password() {
        if ($this->request->is('post')) {
            $this->request->data[$this->modelClass]['id'] = $this->Auth->user('id');
            if ($this->{$this->modelClass}->changePassword($this->request->data)) {
                $this->flashMessage(__d('users', 'Password changed.'), 'alert-info', $this->Auth->loginAction);
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
            'template' => 'Users.account_verification',
            'layout' => 'default'
        );

        $options = array_merge($defaults, $options);
        $Email = $this->_getMailInstance();
        $Email->template($options['template'], $options['layout'])
            ->viewVars(array(
                'model' => $this->modelClass,
                'user' => $userData
            ))
            ->to($userData[$this->modelClass]['email'])
            ->from($options['from'])
            ->subject($options['subject'])
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
            'template' => $this->_pluginDot() . 'password_reset_request',
            'layout' => 'default'
        );

        $options = array_merge($defaults, $options);

        if (!empty($this->request->data)) {
            $user = $this->{$this->modelClass}->passwordReset($this->request->data);

            if (!empty($user)) {

                $Email = $this->_getMailInstance();
                $Email->to($user[$this->modelClass]['email'])
                    ->from($options['from'])
                    ->subject($options['subject'])
                    // ->emailFormat('both')
                    ->template($options['template'], $options['layout'])
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

        if ($admin) {
            return $this->render('admin_request_password_change');
        }

        return $this->render('request_password_change');
    }

/**
 * Sets the cookie to remember the user
 *
 * @param array RememberMe (Cookie) component properties as array, like array('domain' => 'yourdomain.com')
 * @param string Cookie data keyname for the userdata, its default is "User". This is set to User and NOT using the model alias to make sure it works with different apps with different user models across different (sub)domains.
 * @return void
 * @link http://book.cakephp.org/2.0/en/core-libraries/components/cookie.html
 * @deprecated Use the RememberMe Component
 */
    protected function _setCookie($options = array(), $cookieKey = 'rememberMe') {
        $this->RememberMe->settings['cookieKey'] = $cookieKey;
        $this->RememberMe->configureCookie($options);
        $this->RememberMe->setCookie();
    }

/**
 * This method allows the user to change his password if the reset token is correct
 *
 * @param string $token Token
 * @return void
 */
    protected function _resetPassword($token) {
        $user = $this->{$this->modelClass}->checkPasswordToken($token);
        if (empty($user)) {
            $this->flashMessage(__d('users', 'Invalid password reset token, try again.'), 'alert-error', array('action' => 'reset_password'));
        }

        if (!empty($this->request->data) && $this->{$this->modelClass}->resetPassword(Set::merge($user, $this->request->data))) {
            $this->flashMessage(__d('users', 'Password changed, you can now login with your new password.'), 'alert-success',
            $this->Auth->loginAction);
        }

        $this->set('token', $token);
    }

/**
 * Returns a CakeEmail object
 *
 * @return object CakeEmail instance
 * @link http://book.cakephp.org/2.0/en/core-utility-libraries/email.html
 */
    protected function _getMailInstance() {
        App::uses('CakeEmail', 'Network/Email');
        $emailConfig = Configure::read('Users.emailConfig');
        if ($emailConfig) {
            return new CakeEmail($emailConfig);
        } else {
            return new CakeEmail('default');
        }
    }
}