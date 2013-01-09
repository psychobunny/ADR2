<?php

namespace ADR\Races;

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,
    ADR\User\User as User;

/**
 * @uri /adr/races
 * @uri /adr/races/:method
 */
Class Races Extends API
{
    /**
     * @method GET
     * @api
     */
    public function get()
    {
        $db = DB::instance();
        $races = $db->select('adr_races', array());
        
        return $this->response($races);
    }

    public function getRaceByID($race_id)
    {
        $db = DB::instance();
        $race = $db->select('adr_races', array('race_id' => $race_id), 1);
        
        return $race;
    }
}
?>