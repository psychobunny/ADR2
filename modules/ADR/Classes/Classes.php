<?php

namespace ADR\Classes;

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,
    ADR\User\User as User;

/**
 * @uri /adr/classes
 * @uri /adr/classes/:method
 */
Class Classes Extends API
{
    /**
     * @method GET
     * @api
     */
    public function get()
    {
        $db = DB::instance();
        $classes = $db->select('adr_classes', array());
        
        return $this->response($classes);
    }

    public function getClassByID($class_id)
    {
        $db = DB::instance();
        $class = $db->select('adr_classes', array('class_id' => $class_id), 1);
        
        return $class;
    }
}
?>