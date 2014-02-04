<?php
App::uses('AppShell', 'Console/Command');

class CreateTask extends AppShell {
    public $uses = array('Users.User');

/**
 * Main execution of shell
 *
 * @return void
 */
    public function main() {
        if (empty($this->args)) {
            $this->_interactive();
            $this->_stop();
        }
    }

/**
 * Interactive interface
 *
 * @return void
 */
    protected function _interactive() {
        $this->hr();
        $this->out(__d('users_console', 'Add user:'));
        $this->hr();
        $done = false;

        while ($done == false) {
            $username = '';
            while ($username == '') {
                $username = $this->in(__d('users_console', "Username:"));
            }

            $email = '';
            while ($email == '') {
                $email = $this->in(__d('users_console', "Email:"));
            }

            $password = '';
            while ($password == '') {
                $password = $this->in(__d('user_console', 'Password: (min 6 chars)'));
            }

            $admin = '';
            while ($admin == '') {
                $admin = $this->in(__d('user_console', 'Admin?:'), null, 'n');
                if (in_array(strtolower($admin), array('y', 'yes'))) {
                    $admin = 1;
                    $role = 'admin';
                } else {
                    $admin = 0;
                    $role = 'default';
                }
            }

            $data['User'] = array(
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'active' => 1,
                'role' => $role,
                'email_verified' => 1,
                'is_admin' => $admin,
            );

            if($this->User->add($data)) {
                $done = true;
            }
        }
        $this->out("The user has been created sucessfully.");
        return true;
    }

}