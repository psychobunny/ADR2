<?php
/***************************************************************************
 *                                 adr_functions_alone.php
 *                            -------------------
 *   begin                : 11/02/2004
 *   copyright            : Dr DLP / Malicious Rabbit
 *   email                : ukc@wanadoo.fr
 *
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/


function adr_seek_levelup($user_id)
{
	global $db, $adr_general;

	$user_id = intval($user_id);
	$level_up = FALSE;

	$sql = "SELECT cl.class_update_xp_req , c.character_xp , c.character_level FROM " . ADR_CHARACTERS_TABLE . " c , " . ADR_CLASSES_TABLE . " cl
		WHERE cl.class_id = c.character_class 
		AND c.character_id = $user_id ";
	if (!($result = $db->sql_query($sql) ))
	{
		message_die(GENERAL_ERROR, 'Could not check user experience',"", __LINE__, __FILE__, $sql);
	}
	$level = $db->sql_fetchrow($result);

	$max_hp = $level['class_update_xp_req'];
	for ( $p = 1 ; $p < $level['character_level'] ; $p ++ )
	{
		$max_hp = floor($max_hp * ( ( $adr_general['next_level_penalty'] + 100 ) / 100 ));
	}

	if ( $level['character_xp'] >= $max_hp )
	{
		$level_up = TRUE;
	}

	return $level_up;
}

function adr_level_up($user_id , $from )
{
	global $db , $lang , $phpEx , $adr_general;

	$user_id = intval($user_id);

	$sql = "SELECT cl.* , c.* FROM " . ADR_CHARACTERS_TABLE . " c , " . ADR_CLASSES_TABLE . " cl
		WHERE cl.class_id = c.character_class 
		AND c.character_id = $user_id ";
	if (!($result = $db->sql_query($sql) ))
	{
		message_die(GENERAL_ERROR, 'Could not check user experience',"", __LINE__, __FILE__, $sql);
	}
	$level = $db->sql_fetchrow($result);

	$max_hp = $level['class_update_xp_req'];
	for ( $p = 1 ; $p < $level['character_level'] ; $p ++ )
	{
		$max_hp = floor($max_hp * ( ( $adr_general['next_level_penalty'] + 100 ) / 100 ));
	}

	// Damned vicious users :)
	if ( ( $level['character_xp'] < $max_hp ) && $from == 'character_page' )
	{
		exit;
	}

	$xp_req = $max_hp;
	$hp = intval($level['class_update_hp']);
	$mp = intval($level['class_update_mp']);
	$ac = intval($level['class_update_ac']);

	switch($from)
	{
		case 'character_page':
			$direction = append_sid("adr_character.$phpEx");
			$more_sql = 'character_xp = character_xp - '.$xp_req;
			break;

		case 'training':	
			$direction = append_sid("adr_character_training.$phpEx");
			$more_sql = 'character_xp = 0 ';
			break;
	}

	$sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
		SET character_level = character_level + 1 ,
			character_ac = character_ac + $ac,
			character_mp_max = character_mp_max + $mp,
			character_hp_max = character_hp_max + $hp,
			$more_sql
		WHERE character_id = $user_id ";
	if (!($result = $db->sql_query($sql) ))
	{
		message_die(GENERAL_ERROR, 'Could not update user experience',"", __LINE__, __FILE__, $sql);
	}

	$new_level = $level['character_level'] + 1 ;

	adr_update_posters_infos();

	$message = sprintf($lang['Adr_level_up_congrats'] , $new_level);
	$message .= '<br /><br />'.sprintf($lang['Adr_return'],"<a href=\"" . $direction . "\">", "</a>") ;

	message_die(GENERAL_MESSAGE, $message);
}

?>