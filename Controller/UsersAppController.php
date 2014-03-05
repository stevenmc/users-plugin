<?php
class UsersAppController extends AppController {

    /**
     *  Checks the provided prefix is a routing prefix specified in the core.
     *  Also checks the logged in users role is acceptable.
     *
     *  @return boolean
     */
    public function isAuthorized() {
        $role = $this->Auth->user('role'); 
        $neededRole = null; 
        $prefix = !empty($this->request->params['prefix']) ? $this->request->params['prefix'] : null;
        if (!empty($prefix) && in_array($prefix, Configure::read('Routing.prefixes')) ){
            $neededRole = $prefix;
        }
        return (
            empty($neededRole) || 
            strcasecmp($role, 'admin') == 0 || 
            strcasecmp($role, 'moderator') == 0 || 
            strcasecmp($role, $neededRole) == 0
        );
    }

}
