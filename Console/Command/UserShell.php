<?php
App::uses('AppShell', 'Console/Command');

class UserShell extends AppShell {
    public $tasks = array('Users.Create');
/**
 * Main execution of shell
 *
 * @return void
 */
    public function main() {
        return $this->help();
    }

/**
 * Help for Benchmark shell
 *
 * @return void
 */
    public function help() {
        $this->out(__d('users', "Users Shell"));
        $this->out("");
        $this->out(__d('users', "\tCreate users."));
        $this->out("");
        $this->out(__d('users', "\tUse:"));
        $this->out(__d('users', "\t\tcake user create"));
        $this->out("");
        $this->out(__d('users', "\tExample Use:"));
        $this->out(__d('users', "\t\tcake Users.user create"));
        $this->out("");
        $this->hr();
        $this->out("");
    }

}