<?php
/***************************************************************************
 *                               mod_install.php
 *                            -------------------
 *   begin                : Sunday, April 14, 2002
 *   copyright            : (C) 2002 Bulletin Board Mods
 *   email                : support@inetangel.com
 *
 *   $Id: mod_install.php,v 1.0.1 2003/12/08 12:59:59 Napoleon Exp $
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
 
function _sql($sql, &$errored, &$error_ary, $echo_dot = true)
{
	global $db;

	if (!($result = $db->sql_query($sql)))
	{  
		$errored = true;
		$error_ary['sql'][] = (is_array($sql)) ? $sql[$i] : $sql;
		$error_ary['error_code'][] = $db->sql_error();
	}

	if ($echo_dot)
	{
		echo '.';
		flush();
	}

	return $result;
}

define('IN_PHPBB', 1);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'config.'.$phpEx);
include($phpbb_root_path . 'includes/constants.'.$phpEx);
include($phpbb_root_path . 'includes/db.'.$phpEx);
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if (!$userdata['session_logged_in'])
{
	header('Location: ' . append_sid("login.$phpEx?redirect=mod_install.$phpEx", true));
}

if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Mod Installation</title>
<meta http-equiv="Content-Type" content="text/html;">
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css">
<!--

font,th,td,p,body { font-family: "Courier New", courier; font-size: 11pt }

a:link,a:active,a:visited { color : #006699; }
a:hover		{ text-decoration: underline; color : #DD6900;}

hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}

.maintitle,h1,h2	{font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif; text-decoration: none; line-height : 120%; color : #000000;}

.ok	{color:green}

/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import	url("templates/subSilver/formIE.css"); 
-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#006699" vlink="#5584AA">

<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center"> 
	<tr>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><img src="templates/subSilver/images/logo_phpBB.gif" border="0" alt="Forum Home" vspace="1" /></td>
				<td align="center" width="100%" valign="middle"><span class="maintitle">Mod Installation</span></td>
			</tr>
		</table></td>
	</tr>
</table>

<br	clear="all" />

<h2>Information</h2>

<?php

echo '<p>Database type &nbsp; &nbsp;:: <b>' . SQL_LAYER . '</b><br />' . "\n";

$sql = "SELECT config_value  
	FROM " . CONFIG_TABLE . " 
	WHERE config_name = 'version'";
if (!($result = $db->sql_query($sql)))
{
	die("Couldn't obtain version info");
}

$row = $db->sql_fetchrow($result);

$sql = array();


$sql[] = "ALTER TABLE " . FORUMS_TABLE . " ADD points_disabled TINYINT(1) NOT NULL AFTER prune_enable";
$sql[] = "ALTER TABLE " . USERS_TABLE . " ADD user_notify_donation TINYINT(1) NOT NULL AFTER user_notify_pm";
$sql[] = "ALTER TABLE " . USERS_TABLE . " ADD user_points INT NOT NULL";
$sql[] = "ALTER TABLE " . USERS_TABLE . " ADD admin_allow_points TINYINT(1) DEFAULT '1' NOT NULL";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " VALUES ('points_reply', '1')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " VALUES ('points_topic', '2')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " VALUES ('points_post', '1')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " VALUES ('points_donate', '1')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " VALUES ('points_name', 'Points')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " VALUES ('points_user_group_auth_ids', '')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " VALUES ('points_system_version', '2.0.9')";

echo '<h2>Updating database schema & data</h2>' . "\n";
echo '<p>Progress :: <b>';
flush();

$error_ary = array();
$errored = false;
if (count($sql))
{
	for($i = 0; $i < count($sql); $i++)
	{
		_sql($sql[$i], $errored, $error_ary);
	}

	echo '</b> <b class="ok">Done</b><br />Result &nbsp; :: ' . "\n";

	if ($errored)
	{
		echo '<b>Some queries failed, the statements and errors are listing below</b>' . "\n";
		echo '<ul>';

		for($i = 0; $i < count($error_ary['sql']); $i++)
		{
			echo '<li>Error :: <b>' . $error_ary['error_code'][$i]['message'] . '</b><br />';
			echo 'SQL &nbsp; :: <b>' . $error_ary['sql'][$i] . '</b><br /><br /></li>';
		}

		echo '</ul>' . "\n";
		echo '<p>Contact me so I can fix the errors.</p>' . "\n";
		exit();
	}
	else
	{
		echo '<b>No errors</b>' . "\n";
	}
}


echo '<br><br><br><br><br><br><br>';

echo '<table width="100%" cellspacing="1" cellpadding="2" border="0" class="forumline">';
echo '<tr><th>Updating the database</th></tr><tr><td><span class="genmed"><ul type="circle">';


$sql = array();
$sql[] = "INSERT INTO " . $table_prefix . "config(config_name, config_value) VALUES('Adr_version', '0.4.4')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_use_cache_system', '0-0-0-0-0-0-0-0-0')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('stock_use', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('stock_time', '86400')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('stock_last_change', '0')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_experience_for_new', '10')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_experience_for_reply', '5')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_experience_for_edit', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_topics_display', '1-1-0-0-0-1')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_profile_display', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_time_start', 'time()')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_character_age', '16')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_skill_sp_enable', '0')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_character_sp_enable', '0')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_thief_enable', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_thief_points', '5')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_warehouse_duration', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_shop_duration', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_warehouse_tax', '10')";
$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('Adr_shop_tax', '10')";

$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_adr_ban tinyint(1) default '0' NOT NULL";
$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_cell_time INT(11) DEFAULT '0' NOT NULL";
$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_cell_time_judgement INT(11) DEFAULT '0' NOT NULL";
$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_cell_caution INT(8) DEFAULT '0' NOT NULL";
$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_cell_sentence TEXT DEFAULT ''";
$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_cell_enable_caution INT(8) DEFAULT '0' NOT NULL";
$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_cell_enable_free INT(8) DEFAULT '0' NOT NULL";
$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_cell_celleds INT(8) DEFAULT '0' NOT NULL";
$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_cell_punishment TINYINT(1) DEFAULT '0' NOT NULL";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_alignments (
  alignment_id smallint(8) NOT NULL default '0',
  alignment_name varchar(255) NOT NULL default '',
  alignment_desc text NOT NULL,
  alignment_level tinyint(1) NOT NULL default '0',
  alignment_img varchar(255) NOT NULL default '',
  PRIMARY KEY  (alignment_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_battle_list (
  battle_id int(8) NOT NULL auto_increment,
  battle_type tinyint(1) NOT NULL default '0',
  battle_turn tinyint(1) NOT NULL default '0',
  battle_result tinyint(1) NOT NULL default '0',
  battle_text text NOT NULL,
  battle_challenger_equipment_info varchar(255) NOT NULL default '',
  battle_round int(12) NOT NULL default '0',
  battle_start int(12) NOT NULL default '0',
  battle_finish int(12) NOT NULL default '0',
  battle_challenger_id int(8) NOT NULL default '0',
  battle_challenger_hp int(8) NOT NULL default '0',
  battle_challenger_mp int(8) NOT NULL default '0',
  battle_challenger_att int(8) NOT NULL default '0',
  battle_challenger_def int(8) NOT NULL default '0',
  battle_challenger_magic_attack int(8) NOT NULL default '0',
  battle_challenger_magic_resistance int(8) NOT NULL default '0',
  battle_challenger_dmg int(8) NOT NULL default '0',
  battle_challenger_element int(3) NOT NULL default '0',
  battle_opponent_id int(8) NOT NULL default '0',
  battle_opponent_hp int(8) NOT NULL default '0',
  battle_opponent_mp int(8) NOT NULL default '0',
  battle_opponent_att int(8) NOT NULL default '0',
  battle_opponent_def int(8) NOT NULL default '0',
  battle_opponent_magic_attack int(8) NOT NULL default '0',
  battle_opponent_magic_resistance int(8) NOT NULL default '0',
  battle_opponent_mp_power int(8) NOT NULL default '0',
  battle_opponent_sp int(12) NOT NULL default '0',
  battle_opponent_dmg int(8) NOT NULL default '0',
  battle_opponent_hp_max int(8) NOT NULL default '0',
  battle_opponent_mp_max int(8) NOT NULL default '0',
  battle_opponent_element int(3) NOT NULL default '0',
  PRIMARY KEY  (battle_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_battle_monsters (
  monster_id int(8) NOT NULL default '0',
  monster_name varchar(255) NOT NULL default '',
  monster_img varchar(255) NOT NULL default '',
  monster_level int(8) NOT NULL default '0',
  monster_base_hp int(8) NOT NULL default '0',
  monster_base_att int(8) NOT NULL default '0',
  monster_base_def int(8) NOT NULL default '0',
  monster_base_mp int(8) NOT NULL default '10',
  monster_base_mp_power int(8) NOT NULL default '1',
  monster_base_custom_spell varchar(255) NOT NULL default 'a magical spell',
  monster_base_magic_attack int(8) NOT NULL default '10',
  monster_base_magic_resistance int(8) NOT NULL default '10',
  monster_base_sp int(8) NOT NULL default '0',
  monster_thief_skill int(3) NOT NULL default '0',
  monster_base_element int(3) NOT NULL default '1',
  PRIMARY KEY  (monster_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_battle_pvp (
  battle_id int(8) NOT NULL auto_increment,
  battle_turn int(8) NOT NULL default '0',
  battle_result tinyint(1) NOT NULL default '0',
  battle_text text NOT NULL,
  battle_text_chat text NOT NULL,
  battle_start int(12) NOT NULL default '0',
  battle_finish int(12) NOT NULL default '0',
  battle_challenger_id int(8) NOT NULL default '0',
  battle_challenger_att int(8) NOT NULL default '0',
  battle_challenger_def int(8) NOT NULL default '0',
  battle_challenger_hp int(8) NOT NULL default '0',
  battle_challenger_mp int(8) NOT NULL default '0',
  battle_challenger_hp_max int(8) NOT NULL default '0',
  battle_challenger_mp_max int(8) NOT NULL default '0',
  battle_challenger_hp_regen int(8) NOT NULL default '0',
  battle_challenger_mp_regen int(8) NOT NULL default '0',
  battle_challenger_dmg int(8) NOT NULL default '0',
  battle_challenger_magic_attack int(8) NOT NULL default '0',
  battle_challenger_magic_resistance int(8) NOT NULL default '0',
  battle_challenger_element int(3) NOT NULL default '0',
  battle_opponent_id int(8) NOT NULL default '0',
  battle_opponent_att int(8) NOT NULL default '0',
  battle_opponent_def int(8) NOT NULL default '0',
  battle_opponent_hp int(8) NOT NULL default '0',
  battle_opponent_mp int(8) NOT NULL default '0',
  battle_opponent_hp_max int(8) NOT NULL default '0',
  battle_opponent_mp_max int(8) NOT NULL default '0',
  battle_opponent_hp_regen int(8) NOT NULL default '0',
  battle_opponent_mp_regen int(8) NOT NULL default '0',
  battle_opponent_dmg int(8) NOT NULL default '0',
  battle_opponent_magic_attack int(8) NOT NULL default '0',
  battle_opponent_magic_resistance int(8) NOT NULL default '0',
  battle_opponent_element int(3) NOT NULL default '0',
  PRIMARY KEY  (battle_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE adr_characters (
  character_id int(8) NOT NULL default '0',
  character_name varchar(255) NOT NULL default '',
  character_desc text NOT NULL,
  character_race int(8) NOT NULL default '0',
  character_class int(8) NOT NULL default '0',
  character_alignment int(8) NOT NULL default '0',
  character_element int(8) NOT NULL default '0',
  character_hp int(8) NOT NULL default '0',
  character_hp_max int(8) NOT NULL default '0',
  character_mp int(8) NOT NULL default '0',
  character_mp_max int(8) NOT NULL default '0',
  character_ac int(8) NOT NULL default '0',
  character_xp int(11) NOT NULL default '0',
  character_level int(8) NOT NULL default '1',
  character_might int(8) NOT NULL default '0',
  character_dexterity int(8) NOT NULL default '0',
  character_constitution int(8) NOT NULL default '0',
  character_intelligence int(8) NOT NULL default '0',
  character_wisdom int(8) NOT NULL default '0',
  character_charisma int(8) NOT NULL default '0',
  character_birth int(12) NOT NULL default '1093694853',
  character_limit_update int(8) NOT NULL default '1',
  character_battle_limit int(3) NOT NULL default '20',
  character_skill_limit int(3) NOT NULL default '30',
  character_trading_limit int(3) NOT NULL default '30',
  character_thief_limit int(3) NOT NULL default '10',
  character_sp int(12) NOT NULL default '0',
  character_magic_attack int(8) NOT NULL default '10',
  character_magic_resistance int(8) NOT NULL default '10',
  character_warehouse tinyint(1) NOT NULL default '0',
  character_warehouse_update int(8) NOT NULL default '0',
  character_shop_update int(8) NOT NULL default '0',
  character_skill_mining int(8) UNSIGNED NOT NULL default '0',
  character_skill_stone int(8) UNSIGNED NOT NULL default '0',
  character_skill_forge int(8) UNSIGNED NOT NULL default '0',
  character_skill_enchantment int(8) UNSIGNED NOT NULL default '0',
  character_skill_trading int(8) UNSIGNED NOT NULL default '0',
  character_skill_thief int(8) UNSIGNED NOT NULL default '0',
  character_skill_mining_uses int(8) UNSIGNED NOT NULL default '0',
  character_skill_stone_uses int(8) UNSIGNED NOT NULL default '0',
  character_skill_forge_uses int(8) UNSIGNED NOT NULL default '0',
  character_skill_enchantment_uses int(8) UNSIGNED NOT NULL default '0',
  character_skill_trading_uses int(8) UNSIGNED NOT NULL default '0',
  character_skill_thief_uses int(8) UNSIGNED NOT NULL default '0',
  character_victories int(8) NOT NULL default '0',
  character_defeats int(8) NOT NULL default '0',
  character_flees int(8) NOT NULL default '0',
  prefs_pvp_notif_pm tinyint(1) NOT NULL default '1',
  prefs_pvp_allow tinyint(1) NOT NULL default '1',
  prefs_tax_pm_notify TINYINT(1) NOT NULL default '1',
  equip_armor int(8) NOT NULL default '0',
  equip_buckler int(8) NOT NULL default '0',
  equip_helm int(8) NOT NULL default '0',
  equip_gloves int(8) NOT NULL default '0',
  equip_amulet int(8) NOT NULL default '0',
  equip_ring int(8) NOT NULL default '0',
  character_pref_give_pm int(1) NOT NULL default '1',
  character_pref_seller_pm int(1) NOT NULL default '1', 
  character_double_ko int(8) NOT NULL default '0',
  character_victories_pvp int(8) NOT NULL default '0',
  character_defeats_pvp int(8) NOT NULL default '0',
  character_flees_pvp int(8) NOT NULL default '0',
  character_fp int(12) NOT NULL default '0',
  PRIMARY KEY  (character_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_classes (
  class_id smallint(8) NOT NULL default '0',
  class_name varchar(255) NOT NULL default '',
  class_desc text NOT NULL,
  class_level tinyint(1) NOT NULL default '0',
  class_img varchar(255) NOT NULL default '',
  class_might_req int(8) NOT NULL default '0',
  class_dexterity_req int(8) NOT NULL default '0',
  class_constitution_req int(8) NOT NULL default '0',
  class_intelligence_req int(8) NOT NULL default '0',
  class_wisdom_req int(8) NOT NULL default '0',
  class_charisma_req int(8) NOT NULL default '0',
  class_base_hp int(8) NOT NULL default '0',
  class_base_mp int(8) NOT NULL default '0',
  class_base_ac int(8) NOT NULL default '0',
  class_update_hp int(8) NOT NULL default '0',
  class_update_mp int(8) NOT NULL default '0',
  class_update_ac int(8) NOT NULL default '0',
  class_update_xp_req int(8) NOT NULL default '0',
  class_update_of int(8) NOT NULL default '0',
  class_update_of_req int(8) NOT NULL default '0',
  class_selectable int(8) NOT NULL default '0',
  class_magic_attack_req int(8) NOT NULL default '0',
  class_magic_resistance_req int(8) NOT NULL default '0',
  PRIMARY KEY  (class_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_elements (
  element_id smallint(8) NOT NULL default '0',
  element_name varchar(255) NOT NULL default '',
  element_desc text NOT NULL,
  element_level tinyint(1) NOT NULL default '0',
  element_img varchar(255) NOT NULL default '',
  element_skill_mining_bonus int(8) NOT NULL default '0',
  element_skill_stone_bonus int(8) NOT NULL default '0',
  element_skill_forge_bonus int(8) NOT NULL default '0',
  element_skill_enchantment_bonus int(8) NOT NULL default '0',
  element_skill_trading_bonus int(8) NOT NULL default '0',
  element_skill_thief_bonus int(8) NOT NULL default '0',
  element_oppose_strong int(3) NOT NULL default '0',
  element_oppose_strong_dmg int(3) NOT NULL default '100',
  element_oppose_same_dmg int(3) NOT NULL default '100',
  element_oppose_weak int(3) NOT NULL default '0',
  element_oppose_weak_dmg int(3) NOT NULL default '100',
  element_colour varchar(255) NOT NULL default '',
  PRIMARY KEY  (element_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_general (
  config_name varchar(255) NOT NULL default '0',
  config_value int(15) NOT NULL default '0',
  PRIMARY KEY  (config_name)
) TYPE=MyISAM";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('max_characteristic', 20)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('min_characteristic', 3)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('allow_reroll', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('allow_character_delete', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('allow_shop_steal', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('new_shop_price', 500)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('item_modifier_power', 100)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('skill_trading_power', 2)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('skill_thief_failure_damage', 2000)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('skill_thief_failure_punishment', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('skill_thief_failure_type', 2)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('skill_thief_failure_time', 6)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('vault_loan_enable', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('interests_rate', 4)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('interests_time', 86400)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('loan_interests', 15)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('loan_interests_time', 864000)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('loan_max_sum', 5000)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('loan_requirements', 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('stock_max_change', 10)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('stock_min_change', 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('vault_enable', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_enable', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_monster_stats_modifier', 150)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_base_exp_min', 10)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_base_exp_max', 40)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_base_exp_modifier', 120)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_base_reward_min', 10)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_base_reward_max', 40)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_base_reward_modifier', 120)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('temple_heal_cost', 100)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('temple_resurrect_cost', 300)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('cell_allow_user_caution', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('cell_allow_user_judge', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('cell_allow_user_blank', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('cell_amount_user_blank', '5000')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('cell_user_judge_voters', '10')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('cell_user_judge_posts', '2')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('item_power_level', 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('training_skill_cost', 1000)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('training_charac_cost', 3000)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('training_upgrade_cost', 10000)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('training_allow_change', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('training_change_cost', 100)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('next_level_penalty', '10')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_pvp_enable', '1')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_pvp_defies_max', '5')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('pvp_base_exp_min', 10)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('pvp_base_exp_max', 40)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('pvp_base_exp_modifier', 120)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('pvp_base_reward_min', 10)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('pvp_base_reward_max', 40)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('pvp_base_reward_modifier', 120)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('weight_enable', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_disable_rpg', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_limit_regen_duration', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_character_limit_enable', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_character_battle_limit', 20)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_character_skill_limit', 30)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_character_trading_limit', 30)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_character_thief_limit', 10)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_base_sp_modifier', 120)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('posts_enable', 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('posts_min', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('battle_calc_type', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_cache_interval', 15)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_cache_last_updated', 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_shop_steal_sell', 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_shop_steal_min_lvl', 5)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_general (config_name, config_value) VALUES ('Adr_shop_steal_show', 0)";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_races (
  race_id smallint(8) NOT NULL default '0',
  race_name varchar(255) NOT NULL default '',
  race_desc text NOT NULL,
  race_level tinyint(1) NOT NULL default '0',
  race_img varchar(255) NOT NULL default '',
  race_might_bonus int(8) NOT NULL default '0',
  race_dexterity_bonus int(8) NOT NULL default '0',
  race_constitution_bonus int(8) NOT NULL default '0',
  race_intelligence_bonus int(8) NOT NULL default '0',
  race_wisdom_bonus int(8) NOT NULL default '0',
  race_charisma_bonus int(8) NOT NULL default '0',
  race_skill_mining_bonus int(8) NOT NULL default '0',
  race_skill_stone_bonus int(8) NOT NULL default '0',
  race_skill_forge_bonus int(8) NOT NULL default '0',
  race_skill_enchantment_bonus int(8) NOT NULL default '0',
  race_skill_trading_bonus int(8) NOT NULL default '0',
  race_skill_thief_bonus int(8) NOT NULL default '0',
  race_might_malus int(8) NOT NULL default '0',
  race_dexterity_malus int(8) NOT NULL default '0',
  race_constitution_malus int(8) NOT NULL default '0',
  race_intelligence_malus int(8) NOT NULL default '0',
  race_wisdom_malus int(8) NOT NULL default '0',
  race_charisma_malus int(8) NOT NULL default '0',
  race_weight int(12) NOT NULL default '1000',
  race_weight_per_level int(3) NOT NULL default '5',
  race_magic_attack_bonus int(8) NOT NULL default '0',
  race_magic_resistance_bonus int(8) NOT NULL default '0',
  race_magic_attack_malus int(8) NOT NULL default '0',
  race_magic_resistance_malus int(8) NOT NULL default '0',
  PRIMARY KEY  (race_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_shops (
  shop_id int(8) NOT NULL default '0',
  shop_owner_id int(8) NOT NULL default '0',
  shop_name varchar(255) NOT NULL default '',
  shop_desc varchar(255) NOT NULL default '',
  shop_last_updated int(12) NOT NULL default '0',
  PRIMARY KEY  (shop_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_stores (
  store_id int(8) NOT NULL auto_increment,
  store_name varchar(100) NOT NULL default '',
  store_desc varchar(255) NOT NULL default '',
  store_img varchar(255) NOT NULL default '',
  store_status tinyint(1) NOT NULL default '1',
  store_sales_status tinyint(1) NOT NULL default '0',
  store_admin tinyint(1) NOT NULL default '0',
  store_owner_id int(1) NOT NULL default '1',
  KEY store_id (store_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_stores_stats(
  store_stats_character_id int(12) NOT NULL default '0',
  store_stats_store_id int(12) NOT NULL default '0',
  store_stats_buy_total int(12) NOT NULL default '0',
  store_stats_buy_last int(12) NOT NULL default '0',
  store_stats_sold_total int(12) NOT NULL default '0',
  store_stats_sold_last int(12) NOT NULL default '0',
  store_stats_stolen_item_total int(12) NOT NULL default '0',
  store_stats_stolen_item_fail_total int(12) NOT NULL default '0',
  store_stats_stolen_item_last int(12) NOT NULL default '0',
  store_stats_stolen_points_total int(12) NOT NULL default '0',
  store_stats_stolen_points_last int(12) NOT NULL default '0',
  PRIMARY KEY  (store_stats_character_id, store_stats_store_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_stores_user_history(
  user_store_trans_id int(12) NOT NULL default '0',
  user_store_owner_id int(8) NOT NULL default '0',
  user_store_info TEXT NOT NULL default '',
  user_store_total_price int(12) NOT NULL default '0',
  user_store_date int(12) NOT NULL default '0',
  user_store_buyer TEXT NOT NULL default '',
  PRIMARY KEY(user_store_trans_id, user_store_owner_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_shops_items (
  item_id int(8) NOT NULL auto_increment,
  item_owner_id int(8) NOT NULL default '0',
  item_price int(8) NOT NULL default '0',
  item_quality int(8) NOT NULL default '0',
  item_power int(8) NOT NULL default '0',
  item_duration int(8) NOT NULL default '0',
  item_duration_max int(8) NOT NULL default '1',
  item_icon varchar(255) NOT NULL default '',
  item_name varchar(255) NOT NULL default '',
  item_desc varchar(255) NOT NULL default '',
  item_type_use int(8) NOT NULL default '16',
  item_in_shop tinyint(1) NOT NULL default '0',
  item_store_id int(8) NOT NULL default '1',
  item_weight int(12) NOT NULL default '25',
  item_auth int(1) NOT NULL default '0',
  item_max_skill int(8) NOT NULL default '25',
  item_add_power int(8) NOT NULL default '0',
  item_mp_use int(8) NOT NULL default '0',
  item_monster_thief tinyint(1) NOT NULL default '0',
  item_element int(4) NOT NULL default '0',
  item_element_str_dmg int(4) NOT NULL default '100',
  item_element_same_dmg int(4) NOT NULL default '100',
  item_element_weak_dmg int(4) NOT NULL default '100',
  item_in_warehouse tinyint(1) NOT NULL default '0',
  item_sell_back_percentage int(3) NOT NULL default '50',
  item_stolen_id int(12) NOT NULL default '0',
  item_steal_dc smallint(3) NOT NULL default '0',
  item_bought_timestamp int(12) NOT NULL default '0',
  item_restrict_align_enable tinyint(1) NOT NULL default '0',
  item_restrict_align varchar(255) NOT NULL default '0',
  item_restrict_class_enable tinyint(1) NOT NULL default '0',
  item_restrict_class varchar(255) NOT NULL default '0',
  item_restrict_element_enable tinyint(1) NOT NULL default '0',
  item_restrict_element varchar(255) NOT NULL default '0',
  item_restrict_race_enable tinyint(1) NOT NULL default '0',
  item_restrict_race varchar(255) NOT NULL default '0',
  item_restrict_level int(8) NOT NULL default '0',
  item_restrict_str int(8) NOT NULL default '0',
  item_restrict_dex int(8) NOT NULL default '0',
  item_restrict_int int(8) NOT NULL default '0',
  item_restrict_wis int(8) NOT NULL default '0',
  item_restrict_cha int(8) NOT NULL default '0',
  item_restrict_con int(8) NOT NULL default '0',
  item_crit_hit smallint(3) NOT NULL default '20',
  item_crit_hit_mod smallint(3) NOT NULL default '2',
  item_stolen_timestamp int(12) NOT NULL default '0',
  item_stolen_by varchar(255) NOT NULL default '',
  item_donated_timestamp int(12) NOT NULL default '0',
  item_donated_by varchar(255) NOT NULL default '',
  KEY item_id (item_id),
  KEY item_owner_id (item_owner_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_shops_items_quality (
  item_quality_id int(8) NOT NULL default '0',
  item_quality_modifier_price int(8) NOT NULL default '0',
  item_quality_lang varchar(255) NOT NULL default '',
  PRIMARY KEY  (item_quality_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_shops_items_type (
  item_type_id int(8) NOT NULL default '0',
  item_type_base_price int(8) NOT NULL default '0',
  item_type_lang varchar(255) NOT NULL default '',
  PRIMARY KEY  (item_type_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_skills (
  skill_id tinyint(1) NOT NULL default '0',
  skill_name varchar(255) NOT NULL default '',
  skill_desc text NOT NULL,
  skill_img varchar(255) NOT NULL default '',
  skill_req int(8) NOT NULL default '0',
  skill_chance mediumint(3) NOT NULL default '5',
  PRIMARY KEY  (skill_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_vault_blacklist (
  user_id int(8) NOT NULL default '0',
  user_due int(8) NOT NULL default '0',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_vault_exchange (
  stock_id int(8) NOT NULL default '0',
  stock_name varchar(40) NOT NULL default '',
  stock_desc varchar(255) NOT NULL default '',
  stock_price int(8) NOT NULL default '0',
  stock_previous_price int(8) NOT NULL default '0',
  stock_best_price int(8) NOT NULL default '0',
  stock_worst_price int(8) NOT NULL default '0',
  PRIMARY KEY  (stock_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_vault_exchange_users (
  stock_id mediumint(8) NOT NULL default '0',
  user_id mediumint(8) NOT NULL default '0',
  stock_amount mediumint(8) NOT NULL default '0',
  KEY stock_id (stock_id),
  KEY user_id (user_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_vault_users (
  owner_id int(8) NOT NULL default '0',
  account_id int(8) NOT NULL default '0',
  account_sum int(8) NOT NULL default '0',
  account_time int(11) NOT NULL default '0',
  loan_sum int(8) NOT NULL default '0',
  loan_time int(11) NOT NULL default '0',
  account_protect tinyint(1) NOT NULL default '0',
  loan_protect tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (owner_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_jail_users (
  celled_id int(8) NOT NULL default '0',
  user_id int(8) NOT NULL default '0',
  user_cell_date int(11) NOT NULL default '0',
  user_freed_by int(8) NOT NULL default '0',
  user_sentence text,
  user_caution int(8) NOT NULL default '0',
  user_time int(11) NOT NULL default '0',
  PRIMARY KEY  (celled_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_jail_votes (
  vote_id mediumint(8) NOT NULL default '0',
  voter_id mediumint(8) NOT NULL default '0',
  vote_result mediumint(8) NOT NULL default '0',
  KEY vote_id (vote_id),
  KEY voter_id (voter_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_create_exploit_fix (
  user_id int(10) NOT NULL default '0',
  power int(8) NOT NULL default '0',
  agility int(8) NOT NULL default '0',
  endurance int(8) NOT NULL default '0',
  intelligence int(8) NOT NULL default '0',
  willpower int(8) NOT NULL default '0',
  charm int(8) NOT NULL default '0',
  magic_attack int(8) NOT NULL default '0',
  magic_resistance int(8) NOT NULL default '0',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_battle_community(
  chat_id int(10) NOT NULL auto_increment,
  chat_posts int(10) NOT NULL default '0',
  chat_text text,
  chat_date date default NULL,
  PRIMARY KEY (chat_id)
) TYPE=MyISAM";

$sql[] = "CREATE TABLE " . $table_prefix . "adr_bug_fix(
  fix_id varchar(255) NOT NULL default '',
  fix_install_date int(12) NOT NULL default '0',
  fix_installed_by varchar(255) NOT NULL default '',
  PRIMARY KEY(fix_id)
) TYPE=MyISAM";

$sql[] = "INSERT INTO " . $table_prefix . "adr_alignments (alignment_id, alignment_name, alignment_desc, alignment_level, alignment_img) VALUES (1, 'Adr_alignment_neutral', 'Adr_alignment_neutral_desc', 0, 'Neutral.gif')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_alignments (alignment_id, alignment_name, alignment_desc, alignment_level, alignment_img) VALUES (2, 'Adr_alignment_evil', 'Adr_alignment_evil_desc', 0, 'Evil.gif')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_alignments (alignment_id, alignment_name, alignment_desc, alignment_level, alignment_img) VALUES (3, 'Adr_alignment_good', 'Adr_alignment_good_desc', 0, 'Good.gif')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_battle_monsters (monster_id, monster_name, monster_img, monster_level, monster_base_hp, monster_base_att, monster_base_def) VALUES (1, 'Globuz', 'Monster1.jpg', 1, 15, 30, 30)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_battle_monsters (monster_id, monster_name, monster_img, monster_level, monster_base_hp, monster_base_att, monster_base_def) VALUES (2, 'Kargh', 'Monster2.jpg', 2, 20, 40, 60)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_battle_monsters (monster_id, monster_name, monster_img, monster_level, monster_base_hp, monster_base_att, monster_base_def) VALUES (3, 'Bouglou', 'Monster3.jpg', 1, 14, 40, 70)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_battle_monsters (monster_id, monster_name, monster_img, monster_level, monster_base_hp, monster_base_att, monster_base_def) VALUES (4, 'Dretg', 'Monster4.jpg', 1, 25, 30, 30)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_battle_monsters (monster_id, monster_name, monster_img, monster_level, monster_base_hp, monster_base_att, monster_base_def) VALUES (5, 'Greyiok', 'Monster5.jpg', 1, 10, 70, 70)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_battle_monsters (monster_id, monster_name, monster_img, monster_level, monster_base_hp, monster_base_att, monster_base_def) VALUES (6, 'Itchy', 'Monster6.jpg', 2, 25, 90, 80)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_battle_monsters (monster_id, monster_name, monster_img, monster_level, monster_base_hp, monster_base_att, monster_base_def) VALUES (7, 'Globber', 'Monster7.jpg', 3, 45, 250, 200)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_battle_monsters (monster_id, monster_name, monster_img, monster_level, monster_base_hp, monster_base_att, monster_base_def) VALUES (8, 'Scratchy', 'Monster8.jpg', 4, 80, 350, 300)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (1, 'Adr_class_fighter', 'Adr_class_fighter_desc', 0, 'Fighter.gif', 0, 0, 0, 0, 0, 0, 30, 2, 15, 3, 0, 1, 1500, 0, 0, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (2, 'Adr_class_barbare', 'Adr_class_barbare_desc', 0, 'Barbare.gif', 12, 0, 10, 0, 0, 0, 40, 1, 10, 4, 0, 0, 2000, 1, 5, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (3, 'Adr_class_druid', 'Adr_class_druid_desc', 0, 'Druid.gif', 0, 0, 0, 0, 10, 0, 20, 20, 10, 1, 2, 2, 1800, 0, 0, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (4, 'Adr_class_bard', 'Adr_class_bard_desc', 0, 'Bard.gif', 3, 3, 3, 3, 3, 10, 15, 15, 15, 2, 2, 2, 1200, 0, 0, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (5, 'Adr_class_magician', 'Adr_class_magician_desc', 0, 'Magician.gif', 0, 0, 0, 14, 14, 0, 8, 30, 5, 0, 1, 3, 2500, 0, 0, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (6, 'Adr_class_monk', 'Adr_class_monk_desc', 0, 'Monk.gif', 5, 5, 5, 5, 5, 5, 25, 10, 20, 2, 2, 1, 2400, 0, 0, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (7, 'Adr_class_paladin', 'Adr_class_paladin_desc', 0, 'Paladin.gif', 10, 8, 10, 10, 8, 15, 40, 15, 20, 2, 4, 1, 3000, 0, 0, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (8, 'Adr_class_priest', 'Adr_class_priest_desc', 0, 'Priest.gif', 0, 0, 0, 10, 12, 0, 20, 20, 15, 1, 2, 2, 2000, 0, 0, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (9, 'Adr_class_sorceror', 'Adr_class_sorceror_desc', 0, 'Sorcerer.gif', 0, 0, 0, 16, 0, 0, 30, 100, 10, 0, 1, 10, 4500, 5, 10, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_classes (class_id, class_name, class_desc, class_level, class_img, class_might_req, class_dexterity_req, class_constitution_req, class_intelligence_req, class_wisdom_req, class_charisma_req, class_base_hp, class_base_mp, class_base_ac, class_update_hp, class_update_mp, class_update_ac, class_update_xp_req, class_update_of, class_update_of_req, class_selectable) VALUES (10, 'Adr_class_thief', 'Adr_class_thief_desc', 0, 'Thief.gif', 0, 12, 0, 0, 0, 0, 20, 10, 10, 1, 2, 1, 1500, 0, 0, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_elements (element_id, element_name, element_desc, element_level, element_img, element_skill_mining_bonus, element_skill_stone_bonus, element_skill_forge_bonus, element_skill_enchantment_bonus, element_skill_trading_bonus, element_skill_thief_bonus) VALUES (1, 'Adr_element_water', 'Adr_element_water_desc', 0, 'Water.gif', 0, 0, 0, 1, 0, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_elements (element_id, element_name, element_desc, element_level, element_img, element_skill_mining_bonus, element_skill_stone_bonus, element_skill_forge_bonus, element_skill_enchantment_bonus, element_skill_trading_bonus, element_skill_thief_bonus) VALUES (2, 'Adr_element_earth', 'Adr_element_earth_desc', 0, 'Earth.gif', 1, 1, 0, 0, 0, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_elements (element_id, element_name, element_desc, element_level, element_img, element_skill_mining_bonus, element_skill_stone_bonus, element_skill_forge_bonus, element_skill_enchantment_bonus, element_skill_trading_bonus, element_skill_thief_bonus) VALUES (3, 'Adr_element_holy', 'Adr_element_holy_desc', 2, 'Holy.gif', 1, 1, 1, 1, 1, 1)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_elements (element_id, element_name, element_desc, element_level, element_img, element_skill_mining_bonus, element_skill_stone_bonus, element_skill_forge_bonus, element_skill_enchantment_bonus, element_skill_trading_bonus, element_skill_thief_bonus) VALUES (4, 'Adr_element_fire', 'Adr_element_fire_desc', 0, 'Fire.gif', 0, 0, 1, 0, 0, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_races (race_id, race_name, race_desc, race_level, race_img, race_might_bonus, race_dexterity_bonus, race_constitution_bonus, race_intelligence_bonus, race_wisdom_bonus, race_charisma_bonus, race_skill_mining_bonus, race_skill_stone_bonus, race_skill_forge_bonus, race_skill_enchantment_bonus, race_skill_trading_bonus, race_skill_thief_bonus, race_might_malus, race_dexterity_malus, race_constitution_malus, race_intelligence_malus, race_wisdom_malus, race_charisma_malus) VALUES (1, 'Adr_race_human', 'Adr_race_human_desc', 0, 'Human.gif', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_races (race_id, race_name, race_desc, race_level, race_img, race_might_bonus, race_dexterity_bonus, race_constitution_bonus, race_intelligence_bonus, race_wisdom_bonus, race_charisma_bonus, race_skill_mining_bonus, race_skill_stone_bonus, race_skill_forge_bonus, race_skill_enchantment_bonus, race_skill_trading_bonus, race_skill_thief_bonus, race_might_malus, race_dexterity_malus, race_constitution_malus, race_intelligence_malus, race_wisdom_malus, race_charisma_malus) VALUES (2, 'Adr_race_half-elf', 'Adr_race_half-elf_desc', 0, 'Half-elf.gif', 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_races (race_id, race_name, race_desc, race_level, race_img, race_might_bonus, race_dexterity_bonus, race_constitution_bonus, race_intelligence_bonus, race_wisdom_bonus, race_charisma_bonus, race_skill_mining_bonus, race_skill_stone_bonus, race_skill_forge_bonus, race_skill_enchantment_bonus, race_skill_trading_bonus, race_skill_thief_bonus, race_might_malus, race_dexterity_malus, race_constitution_malus, race_intelligence_malus, race_wisdom_malus, race_charisma_malus) VALUES (3, 'Adr_race_half-orc', 'Adr_race_half-orc_desc', 0, 'Half-orc.gif', 2, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 3)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_races (race_id, race_name, race_desc, race_level, race_img, race_might_bonus, race_dexterity_bonus, race_constitution_bonus, race_intelligence_bonus, race_wisdom_bonus, race_charisma_bonus, race_skill_mining_bonus, race_skill_stone_bonus, race_skill_forge_bonus, race_skill_enchantment_bonus, race_skill_trading_bonus, race_skill_thief_bonus, race_might_malus, race_dexterity_malus, race_constitution_malus, race_intelligence_malus, race_wisdom_malus, race_charisma_malus) VALUES (4, 'Adr_race_elf', 'Adr_race_elf_desc', 0, 'Elf.gif', 0, 2, 0, 0, 0, 2, 0, 0, 0, 1, 1, 0, 1, 0, 2, 0, 0, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_races (race_id, race_name, race_desc, race_level, race_img, race_might_bonus, race_dexterity_bonus, race_constitution_bonus, race_intelligence_bonus, race_wisdom_bonus, race_charisma_bonus, race_skill_mining_bonus, race_skill_stone_bonus, race_skill_forge_bonus, race_skill_enchantment_bonus, race_skill_trading_bonus, race_skill_thief_bonus, race_might_malus, race_dexterity_malus, race_constitution_malus, race_intelligence_malus, race_wisdom_malus, race_charisma_malus) VALUES (5, 'Adr_race_gnome', 'Adr_race_gnome_desc', 0, 'Gnome.gif', 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 2, 0, 0, 2, 0, 0, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_races (race_id, race_name, race_desc, race_level, race_img, race_might_bonus, race_dexterity_bonus, race_constitution_bonus, race_intelligence_bonus, race_wisdom_bonus, race_charisma_bonus, race_skill_mining_bonus, race_skill_stone_bonus, race_skill_forge_bonus, race_skill_enchantment_bonus, race_skill_trading_bonus, race_skill_thief_bonus, race_might_malus, race_dexterity_malus, race_constitution_malus, race_intelligence_malus, race_wisdom_malus, race_charisma_malus) VALUES (6, 'Adr_race_halfeling', 'Adr_race_halfeling_desc', 2, 'Halfeling.gif', 0, 2, 0, 0, 2, 0, 0, 0, 0, 0, 0, 3, 0, 0, 2, 0, 0, 0)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_races (race_id, race_name, race_desc, race_level, race_img, race_might_bonus, race_dexterity_bonus, race_constitution_bonus, race_intelligence_bonus, race_wisdom_bonus, race_charisma_bonus, race_skill_mining_bonus, race_skill_stone_bonus, race_skill_forge_bonus, race_skill_enchantment_bonus, race_skill_trading_bonus, race_skill_thief_bonus, race_might_malus, race_dexterity_malus, race_constitution_malus, race_intelligence_malus, race_wisdom_malus, race_charisma_malus) VALUES (7, 'Adr_race_dwarf', 'Adr_race_dwarf_desc', 0, 'Dwarf.gif', 1, 0, 2, 0, 1, 0, 2, 2, 1, 0, 0, 0, 0, 2, 0, 0, 0, 3)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops (shop_id, shop_owner_id, shop_name, shop_desc) VALUES (1, 1, 'Adr_shop_forums', 'Adr_shop_forums_desc')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_stores(store_id, store_name, store_desc, store_img, store_status, store_sales_status, store_admin) VALUES(1, 'Forum Store', 'The general forum store', '',1 ,0 ,0 )";
$sql[] = "INSERT INTO " . $table_prefix . "adr_stores(store_name, store_desc, store_img, store_status, store_sales_status, store_admin) VALUES('Admin Only Store', 'Viewable only by the board admin', '',1 ,0 ,1 )";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items (`item_id`, `item_owner_id`, `item_price`, `item_quality`, `item_power`, `item_duration`, `item_duration_max`, `item_icon`, `item_name`, `item_desc`, `item_type_use`, `item_in_shop`, `item_store_id`, `item_weight`, `item_auth`, `item_max_skill`, `item_add_power`, `item_mp_use`, `item_monster_thief`, `item_element`, `item_element_str_dmg`, `item_element_same_dmg`, `item_element_weak_dmg`, `item_in_warehouse`, `item_sell_back_percentage`, `item_stolen_id`, `item_steal_dc`, `item_bought_timestamp`, `item_restrict_align_enable`, `item_restrict_align`, `item_restrict_class_enable`, `item_restrict_class`, `item_restrict_element_enable`, `item_restrict_element`, `item_restrict_race_enable`, `item_restrict_race`, `item_restrict_level`, `item_restrict_str`, `item_restrict_dex`, `item_restrict_int`, `item_restrict_wis`, `item_restrict_cha`, `item_restrict_con`, `item_crit_hit`, `item_crit_hit_mod`, `item_stolen_timestamp`, `item_stolen_by`, `item_donated_timestamp`, `item_donated_by`) VALUES 
(11, 1, 27721, 4, 5, 600, 600, 'ring2.gif', 'Adr_items_ring_2', 'Adr_items_ring_2_desc', 14, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0-', 0, '0-', 0, '0-', 0, '0-', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(10, 1, 11000, 3, 1, 150, 150, 'ring1.gif', 'Adr_items_ring_1', 'Adr_items_ring_1_desc', 14, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0-', 0, '0-', 0, '0-', 0, '0-', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(9, 1, 1078, 4, 3, 1, 1, 'scroll4.gif', 'Adr_items_scroll_4', 'Adr_items_scroll_4_desc', 12, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0-', 0, '0-', 0, '0-', 0, '0-', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(8, 1, 550, 3, 1, 1, 1, 'scroll3.gif', 'Adr_items_scroll_3', 'Adr_items_scroll_3_desc', 12, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0-', 0, '0-', 0, '0-', 0, '0-', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(7, 1, 100, 3, 10, 10, 10, 'scroll2.gif', 'Adr_items_scroll_2', 'Adr_items_scroll_2_desc', 11, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 50, 0, 0, 0, 0, '0', 1, '3,5,8,9,', 0, '0', 0, '0', 5, 0, 0, 20, 0, 0, 0, 20, 2, 0, '', 0, ''),
(6, 1, 550, 3, 1, 1, 1, 'scroll1.gif', 'Adr_items_scroll_1', 'Adr_items_scroll_1_desc', 11, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0-', 0, '0-', 0, '0-', 0, '0-', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(5, 1, 154, 3, 3, 200, 200, 'miner.gif', 'Adr_items_miner', 'Adr_items_miner_desc', 3, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0-', 0, '0-', 0, '0-', 0, '0-', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(4, 1, 111, 3, 1, 100, 100, 'tome.gif', 'Adr_item_tome', 'Adr_item_tome_desc', 4, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 1, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(3, 1, 555, 4, 5, 1, 1, 'diamond.gif', 'Adr_items_diamond', 'Adr_items_diamond_desc', 2, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(2, 1, 352, 3, 4, 1, 1, 'sapphire.gif', 'Adr_items_sapphire', 'Adr_items_sapphire_desc', 2, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0-', 0, '0-', 0, '0-', 0, '0-', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(1, 1, 56, 3, 1, 1, 1, 'ore.gif', 'Adr_item_ore', 'Adr_item_ore_desc', 1, 0, 1, 25, 0, 25, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0-', 0, '0-', 0, '0-', 0, '0-', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(15, 1, 35, 5, 1, 100, 100, 'armor.gif', 'Armour', '', 7, 0, 1, 10, 0, 15, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(16, 1, 25, 3, 1, 100, 100, 'bracer.gif', 'Tough Shield', '', 8, 0, 1, 10, 0, 15, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(13, 1, 13861, 4, 5, 650, 650, 'amulet2.gif', 'Adr_items_amulet_2', 'Adr_items_amulet_2_desc', 13, 0, 1, 25, 0, 25, 10, 0, 0, 0, 100, 100, 100, 0, 75, 0, 7, 0, 1, '2,3,', 1, '1,2,7,', 0, '0', 0, '0', 7, 0, 0, 15, 15, 0, 0, 20, 2, 0, '', 0, ''),
(17, 1, 15, 1, 1, 100, 100, 'glove.gif', 'Gloves', '', 1, 0, 1, 5, 0, 10, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(14, 1, 25, 4, 1, 100, 100, 'winged-helm.gif', 'Winged Helm', '', 9, 0, 1, 5, 0, 5, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(18, 1, 35, 4, 1, 100, 100, 'sword.gif', 'Sword', '', 5, 0, 1, 5, 0, 10, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(19, 1, 100, 6, 5, 250, 250, 'flail.gif', 'Flail', '', 5, 0, 1, 12, 0, 15, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 3, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(20, 1, 150, 4, 10, 300, 300, 'bow.gif', 'Bow', '', 5, 0, 1, 15, 0, 20, 0, 0, 0, 0, 100, 100, 100, 0, 75, 0, 0, 0, 0, '0', 1, '4,5,9,10,', 0, '0', 1, '1,2,4,', 5, 0, 20, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(21, 1, 10, 1, 10, 5, 5, 'potion1.gif', 'Health Potion', '', 15, 0, 1, 3, 0, 0, 0, 0, 0, 0, 100, 100, 100, 0, 5, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, ''),
(22, 1, 10, 4, 10, 5, 5, 'potion2.gif', 'Mana Potion', '', 16, 0, 1, 3, 0, 0, 0, 0, 0, 0, 100, 100, 100, 0, 5, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, '')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_quality (item_quality_id, item_quality_modifier_price, item_quality_lang) VALUES (0, 0, 'Adr_dont_care')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_quality (item_quality_id, item_quality_modifier_price, item_quality_lang) VALUES (1, 20, 'Adr_items_quality_very_poor')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_quality (item_quality_id, item_quality_modifier_price, item_quality_lang) VALUES (2, 50, 'Adr_items_quality_poor')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_quality (item_quality_id, item_quality_modifier_price, item_quality_lang) VALUES (3, 100, 'Adr_items_quality_medium')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_quality (item_quality_id, item_quality_modifier_price, item_quality_lang) VALUES (4, 140, 'Adr_items_quality_good')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_quality (item_quality_id, item_quality_modifier_price, item_quality_lang) VALUES (5, 200, 'Adr_items_quality_very_good')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_quality (item_quality_id, item_quality_modifier_price, item_quality_lang) VALUES (6, 300, 'Adr_items_quality_excellent')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (0, 0, 'Adr_dont_care')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (1, 50, 'Adr_items_type_raw_materials')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (2, 200, 'Adr_items_type_rare_raw_materials')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (3, 100, 'Adr_items_type_tools_pickaxe')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (4, 100, 'Adr_items_type_tools_magictome')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (5, 100, 'Adr_items_type_weapon')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (6, 1000, 'Adr_items_type_enchanted_weapon')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (7, 200, 'Adr_items_type_armor')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (8, 100, 'Adr_items_type_buckler')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (9, 50, 'Adr_items_type_helm')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (10, 50, 'Adr_items_type_gloves')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (11, 500, 'Adr_items_type_magic_attack')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (12, 500, 'Adr_items_type_magic_defend')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (13, 5000, 'Adr_items_type_amulet')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (14, 10000, 'Adr_items_type_ring')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (15, 20, 'Adr_items_type_health_potion')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (16, 20, 'Adr_items_type_mana_potion')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_shops_items_type (item_type_id, item_type_base_price, item_type_lang) VALUES (17, 1, 'Adr_items_type_misc')";
$sql[] = "INSERT INTO " . $table_prefix . "adr_skills (skill_id, skill_name, skill_desc, skill_img, skill_req, skill_chance) VALUES (1, 'Adr_mining', 'Adr_skill_mining_desc', 'skill_mining.gif', 100, 5)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_skills (skill_id, skill_name, skill_desc, skill_img, skill_req, skill_chance) VALUES (2, 'Adr_stone', 'Adr_skill_stone_desc', 'skill_stone.gif', 200, 5)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_skills (skill_id, skill_name, skill_desc, skill_img, skill_req, skill_chance) VALUES (3, 'Adr_forge', 'Adr_skill_forge_desc', 'skill_forge.gif', 50, 5)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_skills (skill_id, skill_name, skill_desc, skill_img, skill_req, skill_chance) VALUES (4, 'Adr_enchantment', 'Adr_skill_enchantment_desc', 'skill_enchantment.gif', 300, 5)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_skills (skill_id, skill_name, skill_desc, skill_img, skill_req, skill_chance) VALUES (5, 'Adr_trading', 'Adr_skill_trading_desc', 'skill_trading.gif', 80, 5)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_skills (skill_id, skill_name, skill_desc, skill_img, skill_req, skill_chance) VALUES (6, 'Adr_thief', 'Adr_skill_thief_desc', 'skill_thief.gif', 70, 5)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_vault_exchange (stock_id, stock_name, stock_desc, stock_price, stock_previous_price, stock_best_price, stock_worst_price) VALUES (1, 'Adr_vault_action_name_1', 'Adr_vault_action_desc_1', 113, 108, 113, 100)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_vault_exchange (stock_id, stock_name, stock_desc, stock_price, stock_previous_price, stock_best_price, stock_worst_price) VALUES (2, 'Adr_vault_action_name_2', 'Adr_vault_action_desc_2', 177, 192, 200, 177)";
$sql[] = "INSERT INTO " . $table_prefix . "adr_vault_exchange (stock_id, stock_name, stock_desc, stock_price, stock_previous_price, stock_best_price, stock_worst_price) VALUES (3, 'Adr_vault_action_name_3', 'Adr_vault_action_desc_3', 280, 288, 300, 280)";

for( $i = 0; $i < count($sql); $i++ )
{
	if( !$result = $db->sql_query ($sql[$i]) )
	{
		$error = $db->sql_error();

		echo '<li>' . $sql[$i] . '<br /> +++ <font color="#FF0000"><b>Error:</b></font> ' . $error['message'] . '</li><br />';
	}
	else
	{
		echo '<li>' . $sql[$i] . '<br /> +++ <font color="#00AA00"><b>Database update successful!</b></font></li><br />';
	}
}


echo '</ul></span></td></tr><tr><td class="catBottom" height="28">&nbsp;</td></tr>';

echo '<tr><th>End</th></tr><tr><td><span class="genmed">The ADR database updates are now finished but you still need to make the file edits to complete the entire ADR installation. <br><br><b>Please be sure to delete this file from the server for security.</b><br><br>If you have run into any errors, please visit the <a href="http://www.adr-support.com/adr_support/index.php?f=95" target="_phpbbsupport">adr-support.com</a> and ask someone for help.</span></td></tr>';
echo '<tr><td class="catBottom" height="28" align="center"><span class="genmed"><a href="' . append_sid("index.$phpEx") . '">Have a nice day</a></span></td></table>';




echo '<h2>Install completed</h2>' . "\n";
echo 'You can now delete this file. To undo any changes run the mod_uninstall.php file.';


?>

<br	clear="all" />

</body>
</html>