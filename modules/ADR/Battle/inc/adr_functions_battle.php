<?php

function adr_make_restrict_sql($user)
{

	$restrict_sql = " AND (item_restrict_class LIKE '%".$user['character_class'].","."%' OR item_restrict_class_enable = '0')
		AND (item_restrict_race LIKE '%".$user['character_race'].","."%' OR item_restrict_race_enable = '0')
		AND (item_restrict_align LIKE '%".$user['character_alignment'].","."%' OR item_restrict_align_enable = '0')
		AND (item_restrict_element LIKE '%".$user['character_element'].","."%' OR item_restrict_element_enable = '0')
		AND item_restrict_level <= '".$user['character_level']."'
		AND item_restrict_str <= '".$user['character_might']."'
		AND item_restrict_dex <= '".$user['character_dexterity']."'
		AND item_restrict_con <= '".$user['character_constitution']."'
		AND item_restrict_int <= '".$user['character_intelligence']."'
		AND item_restrict_wis <= '".$user['character_wisdom']."'
		AND item_restrict_cha <= '".$user['character_charisma']."'";

	return $restrict_sql;
} 

function adr_battle_make_att($str, $con)
{

   $str = intval($str);
   $con = intval($con);

   // Make calculation
   $att = ceil(($str + ($str *0.5)) + adr_modifier_calc($con));

	return $att;
} 

function adr_battle_make_magic_att($int)
{

	$int = intval($int);

	// Make calculation
	$m_att = ceil($int + ($int *0.75));

	return $m_att;
}

function adr_battle_make_def($ac, $dex)
{

	$ac = intval($ac);
	$dex = intval($dex);

	// Make calculation
	$def = ceil(($ac + ($ac *0.5)) + adr_modifier_calc($dex));

	return $def;
}

function adr_battle_make_magic_def($wis)
{

	$wis = intval($wis);

	// Make calculation
	$m_def = ceil($wis + ($wis *0.75));

	return $m_def;
}

function adr_battle_make_crit_roll($att, $level, $opp_def, $item_type_use=0, $power, $quality=0, $threat_range=20, $party_bonus=0)
{
	global $dice, $item;

	$att = intval($att);
	$level = intval($level);
	$opp_def = intval($opp_def);
	$item_type_use = intval($item_type_use);
	$power = intval($power);
	$quality = intval($quality);
	$threat_range = intval($threat_range);
	$party_bonus = intval($party_bonus);
    $item['item_crit_hit_mod'] = intval(2); //temp

	$crit_result = FALSE;
	if($dice >= $threat_range){
		// Since the result from die roll was a threat & a 100% hit, we now make a crit roll...
		// this must be a hit for a crit strike otherwise we use dmg from first roll
		$crit_die = rand(1,20);
		$crit_result = (((($att + $quality + $crit_die + $level + $party_bonus) > ($opp_def + $level)) && ($crit_die != '1')) || ($crit_die >= $threat_range)) ? TRUE : FALSE;
		$power = ($crit_result == TRUE) ? ($power *$item['item_crit_hit_mod']) : $power;
	}
	return $crit_result.'-'.intval($power);
}

function adr_battle_quota_check($user_id)
{
	global $db , $lang, $adr_general;

	$user_id = intval($user_id);

	$sql = " SELECT character_battle_limit FROM  " . ADR_CHARACTERS_TABLE . " 
		WHERE character_id = $user_id ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query battle list', '', __LINE__, __FILE__, $sql);
	}
	$char = $db->sql_fetchrow($result);
	
	if ( $adr_general['Adr_character_limit_enable'] == 1 && $char['character_battle_limit'] < 1 ) 
	{	
		adr_previous ( Adr_battle_limit , adr_character , '' );
	}

	// Update battle limit for user
	$sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
		SET character_battle_limit = character_battle_limit - 1  
			WHERE character_id = $user_id ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update battle limit', '', __LINE__, __FILE__, $sql);
	}
}

function adr_weight_check($user_id)
{
	global $db, $lang, $adr_general;

	$user_id = intval($user_id);

	$sql = "SELECT c.*, r.race_weight, r.race_weight_per_level
		FROM  " . ADR_CHARACTERS_TABLE . " c, " . ADR_RACES_TABLE . " r
		WHERE c.character_id= '$user_id'
		AND r.race_id = c.character_race";
	if(!($result = $db->sql_query($sql)))
		message_die(CRITICAL_ERROR, 'Error Getting Adr Users!'); 
	$row = $db->sql_fetchrow($result);
	
	// START weight reqs
	$max_weight = adr_weight_stats($row['character_level'], $row['race_weight'], $row['race_weight_per_level'], $row['character_might']);

	// Count up characters current weight
	$sql = "SELECT SUM(item_weight) AS total FROM  " . ADR_SHOPS_ITEMS_TABLE . "
		WHERE item_owner_id = '$user_id'
		AND item_in_warehouse = '0'
		AND item_in_shop = '0'";
	if(!($result = $db->sql_query($sql)))
		message_die(CRITICAL_ERROR, 'Error Getting Adr Users!');
	$weight = $db->sql_fetchrow($result);
	$current_weight = $weight[total];

	if(($adr_general['weight_enable']) && ($current_weight > $max_weight))
		adr_previous(Adr_battle_over_weight, adr_character_inventory, '');
	// END Weight reqs
}

function adr_levelup_check($user_id)
{
	global $db , $lang , $adr_general;

	$user_id = intval($user_id);
	
	$sql = "SELECT c.* , r.race_weight , r.race_weight_per_level , cl.class_update_xp_req
		FROM  " . ADR_CHARACTERS_TABLE . " c , " . ADR_RACES_TABLE . " r , ". ADR_CLASSES_TABLE ." cl
		WHERE c.character_id= $user_id
		AND r.race_id = c.character_race 
		AND cl.class_id = c.character_class ";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(CRITICAL_ERROR, 'Error Getting Adr Users!'); 
	}	
	$row = $db->sql_fetchrow($result);

	$max_xp = $row['class_update_xp_req'];
	for ( $p = 1 ; $p < $row['character_level'] ; $p ++ )
	{
		$max_xp = floor($max_xp * ( ( $adr_general['next_level_penalty'] + 100 ) / 100 ));
	}

	if ( $row['character_xp'] > $max_xp )
	{
		adr_previous ( Adr_battle_force_lvl_up , adr_character , '' );
	}
}

function adr_hp_regen_check($user_id, $battle_challenger_hp)
{
	global $db, $lang, $adr_general, $challenger;

	$user_id = intval($user_id);
	$battle_challenger_hp = intval($battle_challenger_hp);
	$hp_regen = 0;

	if($battle_challenger_hp > '0'){
		// Regeneration of the hp if the user has an amulet
		if($challenger['character_hp'] < $challenger['character_hp_max']){
			$hp_regen = (($battle_challenger_hp + $challenger['character_hp']) > $challenger['character_hp_max']) ? ($challenger['character_hp_max'] - $challenger['character_hp']) : $battle_challenger_hp;

			// Regeneration of the hp if the user has an amulet
			$sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
				SET character_hp = (character_hp + $hp_regen)
				WHERE character_id = '$user_id'";
			if(!($result = $db->sql_query($sql))){
				message_die(GENERAL_ERROR, 'Could not update battle', '', __LINE__, __FILE__, $sql);}

			return $hp_regen;
		}
	}
	return $hp_regen;
}

function adr_mp_regen_check($user_id, $battle_challenger_mp)
{
	global $db, $lang, $adr_general, $challenger;

	$user_id = intval($user_id);
	$battle_challenger_mp = intval($battle_challenger_mp);
	$mp_regen = 0;

	if($battle_challenger_mp > '0'){
		// Regeneration of the mp if the user has a ring
		if($challenger['character_mp'] < $challenger['character_mp_max']){
			$mp_regen = (($battle_challenger_mp + $challenger['character_mp']) > $challenger['character_mp_max']) ? ($challenger['character_mp_max'] - $challenger['character_mp']) : $battle_challenger_mp;

			// Regeneration of the mp if the user has an amulet
			$sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
				SET character_mp = (character_mp + $mp_regen)
				WHERE character_id = '$user_id'";
			if(!($result = $db->sql_query($sql))){
				message_die(GENERAL_ERROR, 'Could not update battle', '', __LINE__, __FILE__, $sql);}

			return $mp_regen;
		}
	}
	return $mp_regen;
}
?>