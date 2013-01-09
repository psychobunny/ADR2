<?php

namespace ADR\Character;
include('../core/Modules/ADR/adr_global.php');
#include('../adr_global.php');

use OpenFantasy as API,
    Database as DB,
    Analytics,
    Exception,    

    ADR,
    ADR\Alignments\Alignments as Alignments,
    ADR\Classes\Classes as Classes, 
    ADR\Elements\Elements as Elements,
    ADR\Races\Races as Races,
    ADR\User\User as User;

/**
 * @uri /adr/character
 * @uri /adr/character/:method
 */
Class Character Extends API
{
    /**
     * @method GET
     * @api
     * gets all characters owned by a user
     */
    public function get()
    {
        $userID = isset($_GET['userID']) ? $_GET['userID'] : User::getID();

        $db = DB::instance();
        $characters = $db->select('adr_characters', array('userID'=>$userID));

        return $this->response($characters);
    }

    /**
     * @method GET
     * @api get_character
     * gets a specific character
     */
    public function get_character()
    {
        $db = DB::instance();
        $character = $db->select('adr_characters', array('character_id'=>$_GET['character_id']));

        return $this->response($character);
    }

    public function getCharacterByID($character_id)
    {
        $db = DB::instance();
        $character = $db->select('adr_characters', array('character_id' => $character_id), 1);
        
        return $character;
    }

    /**
     * @method POST
     * @api delete
     *
     */
    public function delete()
    {

    }

    /**
     * @method POST
     * @api edit_bio
     *
     */
    public function edit_bio()
    {
        $db = DB::instance();
        $character = $db->update('adr_characters', array('character_id'=>$_GET['character_id']));

        return $this->response($character);
    }

    private function isUsersCharacter()
    {
        $userID = User::getID();

        $db = DB::instance();
    }

    /**
     * @method POST
     * @api create
     *
     * ints - race_id, element_id, alignment_id, class_id
     *        element_id, alignment_id, character_might, character_dexterity, character_constitution, character_intelligence, character_wisdom, character_charisma, magic_attack, magic_resistance
     * string - character_name, character_desc
     */
    public function create()
    {
        $userID = User::getID();
        $db = DB::instance();

        $races = new Races();
        $race = $races->getRaceByID($_POST['race_id']);

        $elements = new Elements();
        $element = $elements->getElementByID($_POST['element_id']);

        $alignments = new Alignments();
        $alignment = $alignments->getAlignmentByID($_POST['alignment_id']);

        $classes = new Classes();
        $class = $classes->getClassByID($_POST['class_id']);        
        
        $config = ADR::getGeneralConfig();
 
        if (!$this->verify_roll($_POST))
        {
            return $this->response(array('status'=>0, 'message'=>'Invalid roll'));
        }
        
        $character_name = $_POST['character_name'];
        $character_desc = $_POST['character_desc'];
        $character_race = $_POST['race_id'];
        $character_class = $_POST['class_id'];

        $character_hp = $class['class_base_hp'];
        $character_hp_max = $class['class_base_hp'];
        $character_mp = $class['class_base_mp'];
        $character_mp_max = $class['class_base_mp'];
        $character_ac = $class['class_base_ac'];              

        $character_element = $_POST['element_id'];
        $character_alignment = $_POST['alignment_id'];
        $character_might = $_POST['character_might'] + ( $race['race_might_bonus'] - $race['race_might_penalty'] );
        $character_dexterity = $_POST['character_dexterity'] + ( $race['race_dexterity_bonus'] - $race['race_dexterity_penalty'] );
        $character_constitution = $_POST['character_constitution'] + ( $race['race_constitution_bonus'] - $race['race_constitution_penalty'] );
        $character_intelligence = $_POST['character_intelligence'] + ( $race['race_intelligence_bonus'] - $race['race_intelligence_penalty'] );
        $character_wisdom = $_POST['character_wisdom'] + ( $race['race_wisdom_bonus'] - $race['race_wisdom_penalty'] );
        $character_charisma = $_POST['character_charisma'] + ( $race['race_charisma_bonus'] - $race['race_charisma_penalty'] );
        $character_magic_attack = $_POST['magic_attack'] + ( $race['race_magic_attack_bonus'] - $race['race_magic_attack_penalty'] );
        $character_magic_resistance = $_POST['magic_resistance'] + ( $race['race_magic_resistance_bonus'] - $race['race_magic_resistance_penalty'] );  

        $character_skill_mining = 1 + ( $race['race_skill_mining_bonus'] + $element['element_skill_mining_bonus'] );
        $character_skill_stone = 1 + ( $race['race_skill_stone_bonus'] + $element['element_skill_stone_bonus'] );
        $character_skill_forge = 1 + ( $race['race_skill_forge_bonus'] + $element['element_skill_forge_bonus'] );
        $character_skill_enchantment = 1 + ( $race['race_skill_enchantment_bonus'] + $element['element_skill_enchantment_bonus'] );
        $character_skill_trading = 1 + ( $race['race_skill_trading_bonus'] + $element['element_skill_trading_bonus'] );
        $character_skill_thief = 1 + ( $race['race_skill_thief_bonus'] + $element['element_skill_thief_bonus'] );

        $character_birth = time();
        $character_battle_limit = $config['character_battle_limit'];
        $character_skill_limit = $config['character_skill_limit'];
        $character_trading_limit = $config['character_trading_limit'];
        $character_thief_limit = $config['character_thief_limit'];

        
        $character_id = $db->insert('adr_characters', array(
            'userID' => $userID,
            'character_name' => $character_name,
            'character_desc' => $character_desc,
            'character_race' => $character_race,
            'character_class' => $character_class,
            'character_hp' => $character_hp,
            'character_hp_max' => $character_hp_max,
            'character_mp' => $character_mp,
            'character_mp_max' => $character_mp_max,
            'character_ac' => $character_ac,
            'character_element' => $character_element,
            'character_alignment' => $character_alignment,
            'character_might' => $character_might,
            'character_dexterity' => $character_dexterity,
            'character_constitution' => $character_constitution,
            'character_intelligence' => $character_intelligence,
            'character_wisdom' => $character_wisdom,
            'character_charisma' => $character_charisma,
            'character_magic_attack' => $character_magic_attack,
            'character_magic_resistance' => $character_magic_resistance,
            'character_skill_mining' => $character_skill_mining,
            'character_skill_stone' => $character_skill_stone,
            'character_skill_forge' => $character_skill_forge,
            'character_skill_enchantment' => $character_skill_enchantment,
            'character_skill_trading' => $character_skill_trading,
            'character_skill_thief' => $character_skill_thief,
            'character_birth' => $character_birth,
            'character_battle_limit' => $character_battle_limit,
            'character_skill_limit' => $character_skill_limit,
            'character_trading_limit' => $character_trading_limit,
            'character_thief_limit' => $character_thief_limit,
        ));
        if ($character_id == false) throw new Exception($db->getErrorMessage());

        return $this->response(array('status'=>1, 'character_id'=>$character_id));
    } 



    private function verify_roll($stats)
    {
        return true; //debug

        $config = ADR::getConfig();
        $max_stat = $config['maximum_starting_stat'];

        $values = array($_POST['character_might'], $_POST['character_dexterity'], $_POST['character_constitution'], $_POST['character_intelligence'], $_POST['character_wisdom'], $_POST['character_charisma']);
        $total = count($values) * $max_stat;

        return !(array_sum($values) > $total);
    }

}
?>