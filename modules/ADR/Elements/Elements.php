<?php

namespace ADR\Elements;

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,
    ADR\User\User as User;

/**
 * @uri /adr/elements
 * @uri /adr/elements/:method
 */
Class Elements Extends API
{
    /**
     * @method GET
     * @api
     */
    public function get()
    {
        $db = DB::instance();
        $elements = $db->select('adr_elements', array());
        
        return $this->response($elements);
    }

    public function getElementByID($element_id)
    {
        $db = DB::instance();
        $element = $db->select('adr_elements', array('element_id' => $element_id), 1);
        
        return $element;
    }
    
}
?>