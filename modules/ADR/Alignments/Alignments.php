<?php

namespace ADR\Alignments;

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,
    ADR\User\User as User;

/**
 * @uri /adr/alignments
 * @uri /adr/alignments/:method
 */
Class Alignments Extends API
{
    /**
     * @method GET
     * @api
     */
    public function get()
    {
        $db = DB::instance();
        $alignments = $db->select('adr_alignments', array());
        
        return $this->response($alignments);
    }

    public function getAlignmentByID($alignment_id)
    {
        $db = DB::instance();
        $alignment = $db->select('adr_alignments', array('alignment_id' => $alignment_id), 1);
        
        return $alignment;
    }
}
?>