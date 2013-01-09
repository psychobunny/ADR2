<?php

namespace ADR\Monsters;

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,
    ADR\User\User as User;

/**
 * @uri /adr/monsters
 * @uri /adr/monsters/:method
 */
Class Monsters Extends API
{
    /**
     * @method GET
     * @api
     */
    public function get()
    {
        $db = DB::instance();
        $monsters = $db->select('adr_battle_monsters', array());
        
        return $this->response($monsters);
    }

    public function getMonsterByID($monster_id)
    {
        $db = DB::instance();
        $monster = $db->select('adr_battle_monsters', array('monster_id' => $monster_id), 1);
        
        return $monster;
    }

    public function getMonstersByLevel($level, $limit)
    {
        $limit = intval($limit);
        $level = intval($level);

        $db = DB::instance();
        return $db->query(
            "SELECT * FROM adr_battle_monsters
                WHERE monster_level <= '$level'
                    ORDER BY RAND() LIMIT $limit");
    }

}
?>