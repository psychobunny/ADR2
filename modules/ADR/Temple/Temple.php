<?php

namespace ADR\Temple;

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,
    ADR\User\User as User,

    ADR,
    ADR\Alignments\Alignments as Alignments,
    ADR\Character\Character as Character,
    ADR\Classes\Classes as Classes, 
    ADR\Elements\Elements as Elements,
    ADR\Races\Races as Races;

/**
 * @uri /adr/temple
 * @uri /adr/temple/:method
 */
Class Temple Extends API
{
    public function __construct($app = null, $request = null, $urlParams = array())
    {
        define('IN_ADR_TEMPLE', true); 
        define('IN_ADR_BATTLE', true);
        define('IN_ADR_CHARACTER', true);

        parent::__construct($app, $request, $urlParams);
    }
    
    /**
     * @method GET
     * @api     
     */
    public function get_costs()
    {
        return $this->response($this->getCosts($_GET['character_id']));
    }

    private function getCosts($characterID) {
        $config = ADR::getGeneralConfig();

        $characters = new Character();
        $adr_char = $characters->getCharacterByID($characterID);

        return array(
                'heal_price' => ceil( $config['temple_heal_cost'] * $adr_char['character_level'] ),
                'resurrect_price' => ceil( $config['temple_resurrect_cost'] * $adr_char['character_level'] )
            );
    }

    /**
     * @method POST
     * @api heal
     */
    public function heal()
    {
        $characters = new Character();
        $adr_char = $characters->getCharacterByID($_POST['character_id']);

        $cost = $this->getCosts($_POST['character_id']);
        $cost = $cost['heal_price'];

        $user = new User;
        $points = $user->getPoints();

        if ($points['user_points'] < $cost)
        {
            return $this->response(array('status'=>0, 'message'=>ADR::Lang("Adr_lack_points")));
        }

        if (($adr_char['character_hp'] == $adr_char['character_hp_max']) && ($adr_char['character_mp'] == $adr_char['character_mp_max']))
        {
            return $this->response(array('status'=>0, 'message'=>ADR::Lang("Adr_temple_heal_not")));
        }

        $user->subtractPoints($cost);

        $this->replenish($adr_char);
        
        return $this->response(array('status'=>1, 'current_points'=>$points['user_points']-$cost, 'message'=>ADR::Lang("Adr_temple_healed")));
    }
    
    /**
     * @method POST
     * @api resurrect
     */
    public function resurrect()
    {
        $characters = new Character();
        $adr_char = $characters->getCharacterByID($_POST['character_id']);

        $cost = $this->getCosts($_POST['character_id']);
        $cost = $cost['resurrect_price'];

        $user = new User;
        $points = $user->getPoints();

        if ($points['user_points'] < $cost)
        {
            return $this->response(array('status'=>0, 'message'=>ADR::Lang("Adr_lack_points")));
        }

        if ( $adr_char['character_hp'] > 0 )
        {
            return $this->response(array('status'=>0, 'message'=>ADR::Lang("Adr_temple_heal_instead")));
        }

        $user->subtractPoints($cost);

        $this->replenish($adr_char);
        
        return $this->response(array('status'=>1, 'current_points'=>$points['user_points']-$cost, 'message'=>ADR::Lang("Adr_temple_resurrected")));
    } 

    private function replenish($adr_char)
    {
        $db = DB::instance();
        return $db->update('adr_characters', array(
                'character_hp' => $adr_char['character_hp_max'],
                'character_mp' => $adr_char['character_mp_max']
            ));
    }
}
?>