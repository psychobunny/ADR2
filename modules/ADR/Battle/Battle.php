<?php

namespace ADR\Battle;

include ('../core/Modules/ADR/Battle/inc/adr_functions_battle.php');

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
    ADR\Races\Races as Races,
    ADR\Monsters\Monsters as Monsters;


/**
 * @uri /adr/battle
 * @uri /adr/battle/:method
 */
Class Battle Extends API
{
    /**
     * @method GET
     * @api
     */
    public function get_status()
    {
        Analytics::track('saidhello', 'HelloWorld', 0);
        return $this->response(array('status'=>1, 'message'=>"Hello World!"));
    }
    
    /**
     * @method POST
     * @api create
     */
    public function create()
    {
        $character_id = doubleval($_POST['character_id']);
        //$armor = intval($_POST['armor']);
        //$buckler = intval($_POST['buckler']);
        //$helm = intval($_POST['helm']);
        //$gloves = intval($_POST['gloves']);
        //$amulet = intval($_POST['amulet']);
        //$ring = intval($_POST['ring']);

        $characters = new Character();
        $char = $characters->getCharacterByID($character_id);        

        // Calculate the base stats
        $hp = 0;
        $mp = 0;
        $level = $char['character_level'];
        $char_element = $char['character_element'];
        $char_mp = $char['character_mp'];

        // Create base attack & defence stats
        $att = adr_battle_make_att($char['character_might'], $char['character_constitution']);
        $ma = adr_battle_make_magic_att($char['character_intelligence']);
        $def = adr_battle_make_def($char['character_ac'], $char['character_dexterity']);
        $md = adr_battle_make_magic_def($char['character_wisdom']);

        $monsters = new Monsters();
        $monsters = $monsters->getMonstersByLevel($char['character_level'], 1);
        $monsters = $monsters[0];


        // Be sure monsters of the user level exists
        if(!($monsters))
        {
            return $this->response(array('status'=>0, 'message'=>ADR::Lang('Adr_no_monsters')));
        }
            

        // Get this monster base stats
        $monster_id = $monsters['monster_id'];
        $monster_level = $monsters['monster_level'];
        $monster_base_hp = $monsters['monster_base_hp'];
        $monster_base_att = $monsters['monster_base_att'];
        $monster_base_def = $monsters['monster_base_def'];
        $monster_base_element = $monsters['monster_base_element'];
        $monster_base_mp = $monsters['monster_base_mp'];
        $monster_base_mp_power = $monsters['monster_base_mp_power'];
        $monster_base_ma = $monsters['monster_base_magic_attack'];
        $monster_base_md = $monsters['monster_base_magic_resistance'];
        $monster_base_sp = $monsters['monster_base_sp'];
        ##=== END: new monster selection code by Sederien ===##

        // If the user is higher level than the monster , update the monster stats
        if($monster_level < $level){
            if($adr_general['battle_calc_type']){
                // Xanathis's alternative battle modifier calculation for monster battles
                $modifier = (($adr_general['battle_monster_stats_modifier'] -100) /100) *($level - $monster_level) +1;}
            else{
                $modifier = ($adr_general['battle_monster_stats_modifier'] /100) *($level - $monster_level);}

            $monster_base_hp = ceil($monster_base_hp *$modifier);
            $monster_base_att = ceil($monster_base_att *$modifier);
            $monster_base_def = ceil($monster_base_def *$modifier);
            $monster_base_mp = ceil($monster_base_mp *$modifier);
            $monster_base_ma = ceil($monster_base_ma *$modifier);
            $monster_base_md = ceil($monster_base_md *$modifier);
            $monster_base_sp = ceil($monster_base_sp *$modifier);
        }

        ##=== START array for equipment id's ##
        //$equip_array = intval($helm_id).'-'.intval($armor_id).'-'.intval($gloves_id).'-'.intval($buckler_id).'-'.intval($amulet_id).'-'.intval($ring_id).'-'.intval($hp).'-'.intval($mp);
        // NEED ITEMS
        $equip_array = '[0,0,0,0,0,0,0,0]';
        ##=== END array for equipment id's ##

        ##=== START: Initiative Checks
        // 1d20 roll. Highest starts.
        $monster_dex = (10 + (rand(1, $monster_level) *2)); //temp
        $challenger_init_check = 0;//NEED SKILLS (rand(1,20) + adr_modifier_calc($char['character_dexterity']));
        $monster_init_check = 0;//NEED SKILLS (rand(1,20) + adr_modifier_calc($monster_dex));

        // Check who will start ELSE do a rand to determine.
        if($challenger_init_check >= $monster_init_check)
            $turn = 1;
        else $turn = 2;
        ##=== END: Initiative Checks

        $db = DB::instance();
        $battle = $db->insert('adr_battle_list', array(
                'battle_type' => 1,
                'battle_start' => ".time().",
                'battle_turn' => $turn,
                'battle_result' => 0,
                'battle_text' => '',
                'battle_challenger_id' => $character_id,
                'battle_challenger_hp' => $hp,
                'battle_challenger_mp' => $mp,
                'battle_challenger_att' => $att,
                'battle_challenger_def' => $def,
                'battle_challenger_element' => $char_element,
                'battle_challenger_magic_attack' => $ma,
                'battle_challenger_magic_resistance' => $md,
                'battle_challenger_equipment_info' => $equip_array,
                'battle_opponent_id' => $monster_id,
                'battle_opponent_hp' => $monster_base_hp,
                'battle_opponent_hp_max' => $monster_base_hp,
                'battle_opponent_mp' => $monster_base_mp,
                'battle_opponent_mp_max' => $monster_base_mp,
                'battle_opponent_mp_power' => $monster_base_mp_power,
                'battle_opponent_att' => $monster_base_att,
                'battle_opponent_def' => $monster_base_def,
                'battle_opponent_element' => $monster_base_element,
                'battle_opponent_magic_attack' => $monster_base_ma,
                'battle_opponent_magic_resistance' => $monster_base_md,
                'battle_opponent_sp' => $monster_base_sp,
            ));
        if ($battle == false) throw new Exception($db->getErrorMessage());

        return $this->response(array('status'=>1, 'opponent'=>$monsters, 'turn' => $turn));
    }

    private function updateBattleList($params)
    {
        $db = DB::instance();
        $db->update('adr_battle_list', $params, array(
                'battle_challenger_id' => $_POST['character_id'],
                'battle_result' => 0,
                'battle_type' => 1
            ));
    }
    private function incrementTurn()
    {
        $character_id = doubleval($_POST['character_id']);
        $db = DB::instance();
        $db->execute(
            "UPDATE adr_battle_list SET
                    battle_turn = (battle_turn + 1),
                    battle_round = (battle_round + 1)
                WHERE battle_challenger_id = '$character_id'
                AND battle_result = '0'
                AND battle_type = '1'"
        );
    }
    private function getBattleList()
    {
        $character_id = doubleval($_POST['character_id']);

        $db = DB::instance();
        $battle = $db->select('adr_battle_list', array(
            'battle_challenger_id' => $character_id,
            'battle_result' => 0,
            'battle_type' => 1
            ), 1);
        return $battle;
    }

    /**
     * @method POST
     * @api flee
     */
    public function flee()
    {
        $bat = $this->getBattleList();
        $characters = new Character();
        $challenger = $characters->getCharacterByID($_POST['character_id']);

        $dice = rand(1,20);
        $monster_dice = rand(1,20);

        // To flee you must roll higher than opponent or roll straight 20. 1= auto fail
        if((($dice > $monster_dice) && ($dice != '1')) || ($dice == '20'))
        {
            $this->updateBattleList(array('battle_result' => 3, 'battle_finish' => time()));

            /*$sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
                SET character_flees = (character_flees + 1)
                WHERE character_id = '$user_id'";
            if(!($result = $db->sql_query($sql)))
                message_die(GENERAL_ERROR, 'Could not update battle', '', __LINE__, __FILE__, $sql);

            // Delete stolen items from users inventory
            $sql = " DELETE FROM " . ADR_SHOPS_ITEMS_TABLE . "
                WHERE item_monster_thief = '1'
                AND item_owner_id = '$user_id'";
            if(!($result = $db->sql_query($sql)))
                message_die(GENERAL_ERROR, 'Could not delete stolen items', '', __LINE__, __FILE__, $sql);

            // Delete broken items from users inventory
            $sql = " DELETE FROM " . ADR_SHOPS_ITEMS_TABLE . "
                WHERE item_duration < '1'
                AND item_in_warehouse = '0'
                AND item_owner_id = '$user_id'";
            if(!($result = $db->sql_query($sql)))
                message_die(GENERAL_ERROR, 'Could not delete broken items', '', __LINE__, __FILE__, $sql);*/

            return $this->response(array('status' => 1, 'message' => sprintf(ADR::Lang('Adr_battle_flee'), $challenger['character_name'])));
        }
        else
        {
            $this->incrementTurn();
            return $this->response(array('status' => 1, 'message' => sprintf(ADR::Lang('Adr_battle_flee_fail'), $challenger['character_name']), 'turn'=>2));
        }
    }


    private function win($bat, $monster, $challenger)
    {
        // The monster is dead , give money and xp to the users , then update the database

        $adr_general = ADR::getGeneralConfig();

        // Get the experience earned
        $exp = rand ( $adr_general['battle_base_exp_min'] , $adr_general['battle_base_exp_max'] );
        if (( $monster['monster_level'] - $challenger['character_level'] ) > 1 )
        {
            $exp = floor( ( ( $monster['monster_level'] - $challenger['character_level'] ) * $adr_general['battle_base_exp_modifier'] ) / 100 );
        }

        // Get the money earned
        $reward = rand ( $adr_general['battle_base_reward_min'] , $adr_general['battle_base_reward_max'] );
        if (( $monster['monster_level'] - $challenger['character_level'] ) > 1 )
        {
            $reward = floor( ( ( $monster['monster_level'] - $challenger['character_level'] ) * $adr_general['battle_base_reward_modifier'] ) / 100 );
        }

        $this->updateBattleList(array(
                'battle_result' => 1,
                'battle_opponent_hp' => 0,
                'battle_finish' => time(),
                'battle_text' => ''
            ));

        $user = new User;
        $points = $user->getPoints();
        $user->addPoints($reward);

        /*$sql = " UPDATE  " . ADR_CHARACTERS_TABLE . " 
            SET character_xp = character_xp + $exp ,
                character_victories = character_victories + 1 ,
                character_sp = character_sp + '" . $bat['battle_opponent_sp'] . "'
            WHERE character_id = $user_id ";*/
        $db = DB::instance();
        $db->execute(
            "UPDATE adr_characters SET
                    character_xp = character_xp + $exp,
                    character_victories = character_victories + 1,
                    character_sp = character_sp + {$bat['battle_opponent_sp']}
                WHERE character_id = {$bat['battle_challenger_id']}");

        /*
        // Remove item stolen status
        $sql = "UPDATE " . ADR_SHOPS_ITEMS_TABLE . "
            SET item_monster_thief = 0 
            WHERE item_owner_id = $user_id ";
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not update stolen item status', '', __LINE__, __FILE__, $sql);
        }

        // Delete broken items from users inventory
        $sql = " DELETE FROM " . ADR_SHOPS_ITEMS_TABLE . "
            WHERE item_duration < 1 
            AND item_owner_id = $user_id ";
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not delete broken items', '', __LINE__, __FILE__, $sql);
        }*/

        $user_points = $points['user_points'] + $reward;
        $message = array(sprintf(ADR::Lang('Adr_battle_won'), $bat['battle_challenger_dmg'] , $exp , $bat['battle_opponent_sp'] , $reward , $adr_general['points_name']));
        
        $stolen = false;
        if ( $stolen && $stolen['item_name'] != '' )
        {
            array_push($message, sprintf(ADR::Lang('Adr_battle_stolen_items') , $monster['monster_name']));
        }
        
        return array('message' => $message, 'exp' => $exp, 'sp' => $bat['battle_opponent_sp'], 'reward'=> $reward, 'user_points' => $user_points);
    }

    private function lose($bat, $monster, $challenger)
    {
        // The character is dead , update the database

        $this->updateBattleList(array(
                'battle_result' => 2,
                'battle_finish' => time(),
                'battle_text' => ''
            ));

        $db = DB::instance();
        $db->execute(
            "UPDATE adr_characters SET
                    character_hp = 0,
                    character_defeats = character_defeats + {$challenger['character_defeats']}
                WHERE character_id = {$bat['battle_challenger_id']}");

        /*
        // Delete stolen items from users inventory
        $sql = " DELETE FROM " . ADR_SHOPS_ITEMS_TABLE . "
            WHERE item_monster_thief = 1
            AND item_owner_id = $user_id ";
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not delete stolen items', '', __LINE__, __FILE__, $sql);
        }

        // Delete broken items from users inventory
        $sql = " DELETE FROM " . ADR_SHOPS_ITEMS_TABLE . "
            WHERE item_duration < 1 
            AND item_owner_id = $user_id ";
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not delete broken items', '', __LINE__, __FILE__, $sql);
        }
        */

        $message = array(sprintf(ADR::Lang('Adr_battle_lost'), $monster['monster_name'], $bat['battle_opponent_dmg']));

        $stolen = false;
        if ($stolen && $stolen['item_name'] != '' )
        {
            array_push($message, sprintf(ADR::Lang('Adr_battle_stolen_items_lost'), $monster['monster_name']));
        }

        return array('message' => $message);
    }

    /**
     * @method POST
     * @api attack
     */
    public function attack()
    {
        $bat = $this->getBattleList();
        if ($bat == false) return $this->response(array('status'=> 0, 'message'=>'There is no active battle!'));

        $characters = new Character();
        $challenger = $characters->getCharacterByID($_POST['character_id']);
        
        $monsters = new Monsters();
        $monster = $monsters->getMonsterByID($bat['battle_opponent_id']);

        $user_ma = $bat['battle_challenger_magic_attack'];
        $user_md = $bat['battle_challenger_magic_resistance'];
        $monster_ma = $bat['battle_opponent_magic_attack'];
        $monster_md = $bat['battle_opponent_magic_resistance'];
        $challenger_element = $challenger['character_element'];
        $opponent_element = $monster['monster_base_element'];
        $battle_round = $bat['battle_round'];

        ### START armour info arrays ###
        // array info: 0=helm, 1=armour, 2=gloves, 3=buckler, 4=amulet, 5=ring, 6=hp_regen, 7=mp_regen
        $armour_info = explode(',', $bat['battle_challenger_equipment_info']);
        $helm_equip = ($armour_info[0] != '') ? $armour_info[0] : intval(0);
        $armour_equip = ($armour_info[1] != '') ? $armour_info[1] : intval(0);
        $gloves_equip = ($armour_info[2] != '') ? $armour_info[2] : intval(0);
        $buckler_equip = ($armour_info[3] != '') ? $armour_info[3] : intval(0);
        $amulet_equip = ($armour_info[4] != '') ? $armour_info[4] : intval(0);
        $ring_equip = ($armour_info[5] != '') ? $armour_info[5] : intval(0);

        // Define the base weapon quality and power
        $weap = isset($_POST['item_weapon']) ? intval($_POST['item_weapon']) : false;
        $power = 1;
        $quality = 0;

        $battle_message = array();

        $item = false;
        if ( $weap )
        {
            /*$sql = " SELECT * FROM " . ADR_SHOPS_ITEMS_TABLE . "
                WHERE item_in_shop = 0 
                AND item_in_warehouse = 0
                AND item_duration > 0
                $item_sql
                AND item_owner_id = $user_id 
                AND item_id = $weap ";
            if( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not query battle list', '', __LINE__, __FILE__, $sql);
            }
            $item = $db->sql_fetchrow($result);

            if ( $challenger['character_mp'] < $item['item_mp_use'] || $challenger['character_mp'] < 0 || $item['item_mp_use'] == '' ) 
            {   
                adr_previous ( Adr_battle_check , 'adr_battle' , '' );
            }

            if ( $item['item_mp_use'] > 0 )
            {
                $sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
                    SET character_mp = character_mp - " . $item['item_mp_use'] . "
                    WHERE character_id = $user_id ";
                if( !($result = $db->sql_query($sql)) )
                {
                    message_die(GENERAL_ERROR, 'Could not update battle', '', __LINE__, __FILE__, $sql);
                }
            }

            // Check for magic weap bonuses. Also get better crit threat range later on...
            $quality = ($item['item_type_use'] == '6') ? ($item['item_quality'] *2) : $item['item_quality'];
            $power = ($item['item_power'] + $item['item_add_power']);
            adr_use_item($weap , $user_id);*/
        }

        // Let's check if the attack succeeds
        $dice = rand(1,20);
        $diff = (($bat['battle_challenger_att'] + $quality + $dice + $challenger['character_level']) > ($bat['battle_opponent_def'] + $challenger['character_level'])) ? TRUE : FALSE;
        $power = ($power + adr_modifier_calc($challenger['character_might']));
        $damage = 1;

        $elements = new Elements();
        $elemental = $elements->getElementByID($opponent_element);

        // Grab item element infos if not bare hands strike
        //$element_name = ($item['item_name'] != '') ? adr_get_element_infos($item['item_element']) : '';

        ##=== START: Critical hit code
        $threat_range = 20; //NEED ITEMS $threat_range = ($item['item_type_use'] == '6') ? '19' : '20'; // magic weaps get slightly better threat range
        $crit_result = adr_battle_make_crit_roll($bat['battle_challenger_att'], $challenger['character_level'], $bat['battle_opponent_def'], $item['item_type_use'], $power, $quality, $threat_range);
        ##=== END: Critical hit code

        // Bare fist strike
        if(!$item || $item['item_name'] == ''){
            $monster_def_dice = rand(1,20);
            $monster_modifier = 0;//rand(1,20); // this is temp. until proper monster characteristics are added to ADR

            // Grab modifers
            $bare_power = adr_modifier_calc($challenger['character_might']);

            if(((($dice + $bare_power) >= ($monster_def_dice + $monster_modifier)) && ($dice != '1')) || ($dice == '20')){
                $damage = rand(1,3);
                $damage = ($damage > $bat['battle_opponent_hp']) ? $bat['battle_opponent_hp'] : $damage;
                array_push($battle_message, sprintf(ADR::Lang('Adr_battle_attack_bare'), $challenger['character_name'], $damage, $monster['monster_name']));
            }
            else{
                $damage = 0;
                array_push($battle_message, sprintf(ADR::Lang('Adr_battle_attack_bare_fail'), $challenger['character_name'], $monster['monster_name']));
            }
        }
        else{
            if((($diff === TRUE) && ($dice != '1')) || ($dice >= $threat_range)){
                // Prefix msg if crit hit
                if ($crit_result === TRUE) array_push($battle_message,  ADR::Lang('Adr_battle_critical_hit'));
                $damage = 1;

                // Work out attack type
                if(($item['item_element']) && ($item['item_element'] === $elemental['element_oppose_strong']) && ($item['item_duration'] > '1') && (!empty($item['item_name']))){
                    $damage = ceil(($power *($item['item_element_weak_dmg'] /100)));
                }
                elseif(($item['item_element']) && (!empty($item['item_name'])) && ($item['item_element'] === $opponent_element) && ($item['item_duration'] > '1')){
                    $damage = ceil(($power *($item['item_element_same_dmg'] /100)));
                }
                elseif(($item['item_element']) && (!empty($item['item_name'])) && ($item['item_element'] === $elemental['element_oppose_weak']) && ($item['item_duration'] > '1')){
                    $damage = ceil(($power *($item['item_element_str_dmg'] /100)));
                }
                else{
                    $damage = ceil($power);
                }

                // Fix dmg value
                $damage = ($damage < '1') ? rand(1,3) : $damage;
                $damage = ($damage > $bat['battle_opponent_hp']) ? $bat['battle_opponent_hp'] : $damage;

                // Fix attack msg type
                if(($item['item_element'] > '0') && ($element_name['element_name'] != '')){
                    array_push($battle_message, sprintf(ADR::Lang('Adr_battle_attack_success'), $challenger['character_name'], $monster['monster_name'], $item['item_name'], adr_get_lang($element_name['element_name']), $damage));}
                else{
                    array_push($battle_message, sprintf(ADR::Lang('Adr_battle_attack_success_norm'), $challenger['character_name'], $monster['monster_name'], $item['item_name'], $damage));}
            }
            else{
                $damage = 0;
                array_push($battle_message, sprintf(ADR::Lang('Adr_battle_attack_failure'), $challenger['character_name'], $monster['monster_name'], $item['item_name']));
            }
        }

        if($item && ($item['item_duration'] < '2') && ($item['item_name'] != '')){
            array_push($battle_message, sprintf(ADR::Lang('Adr_battle_attack_dura'), $challenger['character_name'], adr_get_lang($item['item_name'])));
        }

        $bat['battle_challenger_dmg'] = $damage;
        $opponent_hp = $bat['battle_opponent_hp'] - $damage;
        

        if ($opponent_hp <= 0)
        {            
            $return = $this->win($bat, $monster, $challenger);
            $return['message'] = array_merge($battle_message, $return['message']);
            return $this->response($return);
        }
        else
        {
            $this->updateBattleList(array(
                'battle_opponent_hp' => $opponent_hp,
                'battle_turn' => 2,
                'battle_round' => $bat['battle_round'] + 1,
                'battle_challenger_dmg' => $damage
            ));
            return $this->response(array('status'=>1, 'message' => $battle_message, 'damage' => $damage, 'opponent_hp' => $opponent_hp));
        }
        
    }


    /**
     * @method POST
     * @api opponent_turn
     */
    public function opponent_turn()
    {
        $bat = $this->getBattleList();
        if ($bat == false) return $this->response(array('status'=> 0, 'message'=>'There is no active battle!'));

        $adr_general = ADR::getGeneralConfig();

        $characters = new Character();
        $challenger = $characters->getCharacterByID($_POST['character_id']);        
        
        $monsters = new Monsters();
        $monster = $monsters->getMonsterByID($bat['battle_opponent_id']);

        $monster_name = adr_get_lang($monster['monster_name']);
        $character_name = $challenger['character_name'];
        $monster['monster_crit_hit_mod'] = 2;
        $monster['monster_crit_hit'] = 20;
        $monster['monster_int'] = (10 + (rand(1, $monster['monster_level']) *2)); //temp calc
        $monster['monster_str'] = (10 + (rand(1, $monster['monster_level']) *2)); //temp calc

        $user_ma = $bat['battle_challenger_magic_attack'];
        $user_md = $bat['battle_challenger_magic_resistance'];
        $monster_ma = $bat['battle_opponent_magic_attack'];
        $monster_md = $bat['battle_opponent_magic_resistance'];
        $challenger_element = $challenger['character_element'];
        $opponent_element = $monster['monster_base_element'];
        $battle_round = $bat['battle_round'];

        $battle_message = array();

        $def = false;
        if($def != TRUE)
            $power = ceil($monster['monster_level'] *rand(1,3));
        else
            $power = floor(($monster['monster_level'] *rand(1,3)) /2);

        // Has the monster the ability to steal from user?
        $thief_chance = 0;//NEED ITEMS $thief_chance = rand(1,20);

        if(($adr_general['thief_enable'] == '1') && ($thief_chance == '20')){
            /*$sql = "SELECT item_id, item_name FROM  " . ADR_SHOPS_ITEMS_TABLE . "
                WHERE item_monster_thief = '0'
                AND item_in_warehouse = '0'
                AND item_in_shop = '0'
                AND item_duration > '0'
                AND item_owner_id = '$user_id'
                AND item_id NOT IN ($helm_equip, $armour_equip, $gloves_equip, $buckler_equip, $amulet_equip, $ring_equip)
                ORDER BY rand() LIMIT 1";
            if(!($result = $db->sql_query($sql))){
                message_die(GENERAL_ERROR, 'Could not query items for monster item steal', '', __LINE__, __FILE__, $sql);}
            $item_to_steal = $db->sql_fetchrow($result);

            // Rand to check type of thief attack
            $success_chance = rand(1,20);
            $rand = rand(1,20);

            ##=== START: steal item checks
            $challenger_item_spot_check = (20 + adr_modifier_calc($challenger['character_skill_thief']));
            $monster_item_attempt = (((($rand + adr_modifier_calc($monster['monster_thief_skill'])) > $challenger_item_spot_check) && ($rand != '1')) || ($rand == '20')) ? TRUE : FALSE;
            ##=== END: steal item checks

            ##=== START: steal points checks
            $challenger_points_spot_check = (10 + adr_modifier_calc($challenger['character_skill_thief']));
            $monster_points_attempt = (((($rand + $monster['monster_thief_skill']) > $challenger_points_spot_check) && ($rand != '1')) || ($rand == '20')) ? TRUE : FALSE;
            ##=== END: steal points checks

            if(($success_chance == '20') && ($monster_item_attempt == TRUE) && ($item_to_steal['item_name'] != '')){
                $damage = 0;

                // Mark the item as stolen
                $sql = "UPDATE " . ADR_SHOPS_ITEMS_TABLE . "
                    SET item_monster_thief = 1
                    WHERE item_owner_id = '$user_id'
                    AND item_id = '" . $item_to_steal['item_id'] . "'";
                if(!($result = $db->sql_query($sql))){
                    message_die(GENERAL_ERROR, 'Could not update stolen item by monster', '', __LINE__, __FILE__, $sql);}

                    array_push($battle_message, sprintf(ADR::Lang('Adr_battle_opponent_thief_success'), $monster_name, adr_get_lang($item_to_steal['item_name']), $character_name));
            }
            elseif(($success_chance >= '15') && ($success_chance != '20') && ($user_points > '0') && ($monster_points_attempt == TRUE)){
                $damage = 0;
                $points_stolen = floor(($user_points / 100) *$adr_general['thief_points']);
                subtract_reward($user_id, $points_stolen);
                array_push($battle_message, sprintf(ADR::Lang('Adr_battle_opponent_thief_points'), $monster_name, $points_stolen, get_reward_name(), $character_name));
            }
            else{
                $damage = 0;
                array_push($battle_message, sprintf(ADR::Lang('Adr_battle_opponent_thief_failure'), $monster_name, adr_get_lang($item_to_steal['item_name']), $character_name));
            }*/
        }
        else{
            $attack_type = rand(1,20);
            ##=== START: Critical hit code
            $threat_range = $monster['monster_crit_hit'];
//          list($crit_result, $power) = explode('-', adr_battle_make_crit_roll($bat['battle_opponent_att'], $monster['monster_level'], $bat['battle_challenger_def'], 0, $power, 0, $threat_range, 0));
            ##=== END: Critical hit code

            if(($bat['battle_opponent_mp'] > '0') && ($bat['battle_opponent_mp'] >= $bat['battle_opponent_mp_power']) && ($attack_type > '16')){
                $damage = 1;
                $power = ceil($power + adr_modifier_calc($bat['battle_opponent_mp_power']));

                $elements = new Elements();
                $monster_elemental = $elements->getElementByID($opponent_element);


                // Sort out magic check & opponents saving throw
                $dice = rand(1,20);
                $magic_check = ceil($dice + $bat['battle_opponent_mp_power'] + adr_modifier_calc($monster['monster_int']));
                $fort_save = (11 + adr_modifier_calc($challenger['character_wisdom']));
                $success = ((($magic_check >= $fort_save) && ($dice != '1')) || ($dice >= $threat_range)) ? TRUE : FALSE;

                if($success === TRUE){
                    // Prefix msg if crit hit
                    if ($dice >= $threat_range) array_push($battle_message, ADR::Lang('Adr_battle_critical_hit'));

                    // Work out attack type
                    if($challenger_element === $monster_elemental['element_oppose_weak']){
                        $damage = ceil(($power *($monster_elemental['element_oppose_strong_dmg'] /100)));
                    }
                    elseif($challenger_element === $opponent_element){
                        $damage = ceil(($power *($monster_elemental['element_oppose_same_dmg'] /100)));
                    }
                    elseif($challenger_element === $monster_elemental['element_oppose_strong']){
                        $damage = ceil(($power *($monster_elemental['element_oppose_weak_dmg'] /100)));
                    }
                    else{
                        $damage = ceil($power);
                    }

                    // Fix dmg value
                    $damage = ($damage < '1') ? rand(1,3) : $damage;
                    $damage = ($dice >= $threat_range) ? ($damage * $monster['monster_crit_hit_mod']) : $damage;
                    $damage = ($damage > $challenger['character_hp']) ? $challenger['character_hp'] : $damage;

                    // Fix attack msg type
                    if($monster['monster_base_custom_spell'] != ''){
                        array_push($battle_message, sprintf(ADR::Lang('Adr_battle_opponent_spell_success'), $monster_name, $monster['monster_base_custom_spell'], $character_name, $damage));
                    }                        
                    else{
                        array_push($battle_message, sprintf(ADR::Lang('Adr_battle_opponent_spell_success2'), $monster_name, $character_name, $damage));
                    }
                }
                else{
                    $damage = 0;
                    array_push($battle_message, sprintf(ADR::Lang('Adr_battle_opponent_spell_failure'), $monster_name, $character_name));
                }

                // Remove monster MP
                $opponent_mp_remaining = $bat['battle_opponent_mp'] - $bat['battle_opponent_mp_power'];
                $this->updateBattleList(array('battle_opponent_mp' => $opponent_mp_remaining));
            }
            else{
                // Let's check if the attack succeeds
                $dice = rand(1,20);
                $success = (((($bat['battle_opponent_att'] + $dice) >= ($bat['battle_challenger_def'] + adr_modifier_calc($challenger['character_dexterity']))) && ($dice != '1')) || ($dice >= $threat_range)) ? TRUE : FALSE;
                $power = ceil(($power /2) + (adr_modifier_calc($monster['monster_str'])));
                $damage = 1;

                if($success == TRUE){
                    // Attack success , calculate the damage . Critical dice roll is still success
                    $damage = ($power < '1') ? rand(1,3) : $power;
                    $damage = ($dice >= $threat_range) ? ceil($damage *$monster['monster_crit_hit_mod']) : ceil($damage);
                    $damage = ($damage > $challenger['character_hp']) ? $challenger['character_hp'] : $damage;
                    if ($dice >= $threat_range) array_push($battle_message, ADR::Lang('Adr_battle_critical_hit'));
                    array_push($battle_message, sprintf(ADR::Lang('Adr_battle_opponent_attack_success'), $monster_name, $character_name, $damage));
                }
                else{
                    $damage = 0;
                    array_push($battle_message, sprintf(ADR::Lang('Adr_battle_opponent_attack_failure'), $monster_name, $character_name));
                }
            }
            
            $bat['battle_opponent_dmg'] = $damage;

            // Prevent instant kills at start of battle
            $challenger_hp = $challenger['character_hp'] - $damage;
            if(($bat['battle_round'] == 0) && ($challenger_hp < 1))
            {
                $challenger_hp = 1;
            }
            
            $db = DB::instance();
            $db->update('adr_characters', array(
                    'character_hp' => $challenger_hp
                ), array('character_id'=>$bat['battle_challenger_id']));
            

            if ($challenger_hp <= 0)
            {         
                $return = $this->lose($bat, $monster, $challenger);
                $return['message'] = array_merge($battle_message, $return['message']);
                
                return $this->response($return);
            }
            else
            {
                return $this->response(array('status'=>1, 'message' => $battle_message, 'damage' => $damage, 'challenger_hp' => $challenger_hp));
            }
        }
    }

}
?>