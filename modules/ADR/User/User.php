<?php

namespace ADR\User;

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,
    User\User as DefaultUser;

/**
 * @uri /adr/user
 * @uri /adr/user/:method
 */
Class User Extends DefaultUser
{
   /**
     * @method POST
     * @api register
     */
    public function register()
    {   
        $result = parent::register();
        $userID = $result->body['userID'];

        $db = DB::instance();
        $db->insert('adr_users', array(
                'userID' => $userID,
                'user_points' => 1000
            ));

        return $result;
    }

    /**
     * @method GET
     * @api points
     */
    public function get_points()
    {
        return $this->response($this->getPoints());
    }

    public function subtractPoints($points)
    {
        $points = intval($points);
        $userID = $this::getID();
        $db = DB::instance();
        return $db->execute("UPDATE adr_users SET user_points = user_points - $points");        
    }

    public function addPoints($points)
    {
        $points = intval($points);
        $userID = $this::getID();
        $db = DB::instance();
        return $db->execute("UPDATE adr_users SET user_points = user_points + $points");        
    }

    public function getPoints()
    {
        $userID = $this::getID();
        $db = DB::instance();
        $points = $db->query("SELECT user_points FROM adr_users WHERE userID = $userID");
        return $points[0];
    }

}
?>