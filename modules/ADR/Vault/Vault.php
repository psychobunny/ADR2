<?php

namespace ADR\Character;

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,
    ADR\User\User as User;

/**
 * @uri /adr/vault
 * @uri /adr/vault/:method
 */
Class Vault Extends API
{
    /**
     * @method GET
     * @api
     */
    public function get()
    {
        Analytics::track('saidhello', 'HelloWorld', 0);
        return $this->response(array('status'=>1, 'message'=>"Hello World!"));
    }
    
    /**
     * @method POST
     * @api name
     */
    public function name()
    {    
        // if not logged in, getID is 0 (anonymous)
        Analytics::track('saidhello', 'HelloWorld', User::getID());
        return $this->response(array('status'=>1, 'message'=>"Hello " . $_POST['myName'] . "!"));
    } 

}
?>