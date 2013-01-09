<?PHP

include ('lang_adr.php');

use OpenFantasy as API,
    Database as DB;


Class ADR
{

	public static function getGeneralConfig()
	{
		$db = DB::instance();
		$config = $db->select('adr_general', array());
		$return = array();
		foreach ($config as $key => $value)
		{
			$return[$value['config_name']] = $value['config_value'];
		}
		return $return;
	}

	public static function Lang($key)
	{
		$value = Lang::$lang[$key];
		if ($value == null) throw new Exception('Invalid Language Key');
		return Lang::$lang[$key];
	}
}
//taken straight from adr_global.php, with front-end & phpbb related functions removed or edited

function adr_previous( $lang_key , $direct , $nav='' )
{
	global $lang , $phpEx ;

	$lang_key = $lang[$lang_key];
	$temp = ( !$nav ) ? $direct.'.'.$phpEx : $direct.'.'.$phpEx.'?'.$nav;
	$direction = append_sid("$temp");

	$message = $lang_key .'<br /><br />'.sprintf($lang['Adr_return'],"<a href=\"" . $direction . "\">", "</a>") ;
	message_die( GENERAL_MESSAGE,$message);
}

// ADR/User
function adr_substract_points($id, $sum, $direct, $nav)
{
	global $lang , $phpEx , $userdata , $db ;

	$id = intval($id);

	$sql = "SELECT user_points FROM  " . USERS_TABLE . "
		WHERE user_id = $id ";
	if( !($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not check user points',"", __LINE__, __FILE__, $sql);
	}
	$user_infos = $db->sql_fetchrow($result);
	$user_points = $user_infos['user_points'];

	$sum = intval($sum);
	if ( $user_points < $sum )
	{	
		adr_previous( Adr_lack_points , $direct , $nav );	
	}
	else
	{
		$sql = "UPDATE ".USERS_TABLE."
			SET user_points = (user_points - $sum)
			WHERE user_id = $id ";
		if( !$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update user points',"", __LINE__, __FILE__, $sql);
		}
	}
}

// ADR/User
function adr_add_points( $id , $sum )
{
	global $db ;

	$sql = "UPDATE ".USERS_TABLE."
		SET user_points = user_points + $sum
		WHERE user_id = $id ";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not update user points',"", __LINE__, __FILE__, $sql);
	}
}

// ADR/Character
function adr_substract_sp( $id , $sum , $direct , $nav )
{
	global $lang , $phpEx , $userdata , $db ;

	$id = intval($id);

	$sql = "SELECT character_sp FROM  " . ADR_CHARACTERS_TABLE . "
		WHERE character_id = $id ";
	if( !($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not check character SP',"", __LINE__, __FILE__, $sql);
	}
	$user_infos = $db->sql_fetchrow($result);
	$character_sp = $user_infos['character_sp'];

	$sum = intval($sum);
	if ( $character_sp < $sum )
	{	
		adr_previous( Adr_lack_sp , $direct , $nav );	
	}
	else
	{
		$sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
			SET character_sp = character_sp - $sum
			WHERE character_id = $id ";
		if( !$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update character SP',"", __LINE__, __FILE__, $sql);
		}
	}
}

// ADR/Character
function adr_add_sp( $id , $sum )
{
	global $db ;

	$sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
		SET character_sp = character_sp + $sum
		WHERE character_id = $id ";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not update character SP',"", __LINE__, __FILE__, $sql);
	}
}

function adr_get_lang($key)
{
	global $lang;

	$lang_key = isset($lang[$key]) ? $lang[$key] : stripslashes(trim($key));

	return $lang_key ;
}

function adr_template_file($file)
{
}

function adr_advanced_template_file($file,$name='')
{
}

// ADR/Character
function adr_get_user_infos($user_id)
{
	global $db;

	$user_id = intval($user_id);

	$sql = "SELECT * FROM  " . ADR_CHARACTERS_TABLE . "
		WHERE character_id = $user_id ";
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(GENERAL_ERROR , 'Can not query the user characteristics');
	}
	$row = $db->sql_fetchrow($result);

	return $row;
}

function adr_make_time($stamp_age)
{
	global $db , $lang ;

	$time = '';
	$days = floor($stamp_age/86400);
	$stamp_age = $stamp_age - ( $days * 86400 );
	$hours = floor($stamp_age/3600);
	$stamp_age = $stamp_age - ( $hours * 3600 );
	$minutes = floor($stamp_age/60);
	if ( $days > 1 )
	{
		$time .= $days.'&nbsp;'.$lang['Adr_days'].'&nbsp;';
	}
	else if ( $days != 0 )
	{
		$time .= $days.'&nbsp;'.$lang['Adr_day'].'&nbsp;';
	}
	if ( $hours > 1 )
	{
		$time .= $hours.'&nbsp;'.$lang['Adr_hours'].'&nbsp;';
	}
	else if ( $hours != 0 )
	{
		$time .= $hours.'&nbsp;'.$lang['Adr_hour'].'&nbsp;';
	}
	if ( $minutes > 1 )
	{
		$time .= $minutes.'&nbsp;'.$lang['Adr_minutes'].'&nbsp;';
	}
	else if ( $minutes != 0 )
	{
		$time .= $minutes.'&nbsp;'.$lang['Adr_minute'].'&nbsp;';
	}

	if($time < '1') $time = $lang['Adr_less_min'];

	return $time;
}

function adr_make_bars($hp, $hp_max, $width_value)
{
}

// Taken from [saltymeat at hotmail dot com] at php.net
function adr_array_replace($a, $tofind, $toreplace)
{
   $i = array_search($tofind, $a);
   if ($i === false)
   {
       return $a;
   }
   else
   {
       $a[$i] = $toreplace;
       return adr_array_replace($a, $tofind, $toreplace);
   }
}

// aUsTiN's phpbb_security function...very handy!
function adr_link_length($link, $max)
{
	if (strlen($link) > $max)
		$newlink = substr($link, 0, ($max - 3)) .'...';
	else
		$newlink = $link;
return $newlink;
}

function adr_enable_check()
{
}

function adr_weight_stats($level, $race_weight, $race_weight_per_level, $str)
{
	$level = intval($level);
	$race_weight = intval($race_weight);
	$race_weight_per_level = intval($race_weight_per_level);
	$str = (adr_modifier_calc($str) *10);

	$max_weight = ($race_weight + ((($race_weight *$race_weight_per_level) /100) *$level));

	return ($max_weight + $str);
}

function adr_modifier_calc($skill_level)
{
	$skill_level = intval($skill_level);
	$modifier = 0;

	if($skill_level > '11')
	{
		// Must have at least 12 points in the skill/characteristic before positive modifier is applied
		// Gains +1 modifier for every 2 levels, ie. 12-13 (+1), 14-15 (+2), 16-17 (+3), etc, etc
		for($p = 12; $p < $skill_level; $p++)
		{
			$modifier = floor((($skill_level - 12) /2) + 1);
		}
	}

	return $modifier;
}

// ADR/Character
function adr_character_age($user_id, $years)
{
	global $db, $adr_general, $lang, $board_config;

	$user_id = intval($user_id);
	$years = intval($years);

	$sql = "SELECT character_birth FROM  " . ADR_CHARACTERS_TABLE . "
		WHERE character_id = '$user_id'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Error checking if user has character');
	}
	$row = $db->sql_fetchrow($result);

	$current_age = (time() - $row['character_birth']);
	$char_years = ($board_config['Adr_character_age'] -1) + (ceil($current_age /7862400));
	$age = ($char_years < 2) ? sprintf($lang['Adr_character_age_old1'], $char_years) : sprintf($lang['Adr_character_age_old2'], $char_years);

	return $age;
}

// ADR/Character
function adr_character_replenish_timer($user_id)
{
	global $db, $adr_general, $lang;

	$user_id = intval($user_id);
	$adr_user = adr_get_user_infos($user_id);

	// Show time until next replenish
	$replenish_timer = (24 - floor((((time() - $adr_user['character_birth']) /86400) - ($adr_user['character_limit_update'] + ($adr_general['Adr_limit_regen_duration'] -1))) *24));
	$replenish_timer = adr_make_time($replenish_timer);
	$replenish_timer = ($replenish_timer < '5') ? $lang['Adr_update_quota_timer'] : intval($replenish_timer);

	return $replenish_timer;
}

// ADR/Character
function adr_character_replen_quota()
{
	global $db, $phpEx, $adr_general, $board_config, $lang, $table_prefix, $phpbb_root_path;

	// define some constants
	define('ADR_SHOPS_TABLE', $table_prefix.'adr_shops');
	define('IN_ADR_SETTINGS', 1);
	define('IN_ADR_VAULT', 1);
	define('IN_ADR_BATTLE', 1);
	define('IN_ADR_CHARACTER', 1);

	$sql = "SELECT * FROM ". ADR_CHARACTERS_TABLE."";
	$result = $db->sql_query($sql);
	if (!$result)
		message_die(GENERAL_ERROR, 'Could not obtain character list', '', __LINE__, __FILE__, $sql);
	$characters = $db->sql_fetchrowset($result);

	for($c = 0; $c < count($characters); $c++){
		if(($characters[$c]['character_id'] != '0') && ($characters[$c]['character_id'] != '')){
			// Define some variables
			$character_id = $characters[$c]['character_id'];
			$points = get_reward($character_id);
			$adr_user['character_birth'] = $characters[$c]['character_birth'];
			$adr_user['character_limit_update'] = $characters[$c]['character_limit_update'];
			$adr_user['character_warehouse_update'] = $characters[$c]['character_warehouse_update'];
			$adr_user['character_warehouse'] = $characters[$c]['character_warehouse'];
			$adr_user['character_shop_update'] = $characters[$c]['character_shop_update'];
			$pm_wh = FALSE;
			$pm_shop = FALSE;
			$wh_msg = '';
			$shop_msg = '';
	
			// Vault account check
			$sql = "SELECT account_sum FROM " . ADR_VAULT_USERS_TABLE . "
				WHERE owner_id = '$character_id'";
			if(!($result = $db->sql_query($sql))){
				message_die(GENERAL_ERROR, 'Could not query vault user table', '', __LINE__, __FILE__, $sql);}
			$vault_check = $db->sql_fetchrow($result);
	
			// Shop stats check
			$sql = "SELECT shop_id FROM " . ADR_SHOPS_TABLE . "
				WHERE shop_owner_id = '$character_id'";
			if(!($result = $db->sql_query($sql))){
				message_die(GENERAL_ERROR, 'Could not query shops table', '', __LINE__, __FILE__, $sql);}
			$shop_check = $db->sql_fetchrow($result);
	
			// Work out how many days have passed since character_creation
			$character_days = floor((time() - $adr_user['character_birth']) /86400);

			// START battle & skill regening
			if((is_numeric($character_id)) && ($adr_general['Adr_character_limit_enable'] != '0') && ($character_days > ($adr_user['character_limit_update'] + ($adr_general['Adr_limit_regen_duration'] - 1))))
			{
				$new_battle_limit = $adr_general['Adr_character_battle_limit'];
				$new_skill_limit = $adr_general['Adr_character_skill_limit'];
				$new_trading_limit = $adr_general['Adr_character_trading_limit'];
				$new_thief_limit = $adr_general['Adr_character_thief_limit'];
	
				$sql = " UPDATE " . ADR_CHARACTERS_TABLE . "
					SET character_battle_limit = $new_battle_limit,
						character_skill_limit = $new_skill_limit,
						character_trading_limit = $new_trading_limit,
						character_thief_limit = $new_thief_limit,
						character_limit_update = $character_days
					WHERE character_id = '$character_id'";
				if(!($results = $db->sql_query($sql))){
					message_die(GENERAL_MESSAGE, 'Error regening user battle & skill limits');}
			}// END battle & skill regening

			// START warehouse tax check
			if((is_numeric($character_id)) && ($character_days > ($adr_user['character_warehouse_update'] + ($board_config['Adr_warehouse_duration'] - 1))))
			{
				$warehouse_days = ($character_days - ($adr_user['character_warehouse_update'] + ($board_config['Adr_warehouse_duration'] - 1)));
				$warehouse_tax = ($board_config['Adr_warehouse_tax'] *$warehouse_days);
	
				if(($adr_user['character_warehouse'] != '0') && ($board_config['Adr_warehouse_tax'] > '0')){
					if($points >= $warehouse_tax){
						// Remove tax from user_points
						subtract_reward($character_id, $warehouse_tax);
						$wh_msg = sprintf($lang['Adr_character_warehouse_tax'], $warehouse_tax, get_reward_name());
					}
					elseif(($points < $warehouse_tax) && (is_numeric($vault_check['account_sum'])) && ($vault_check['account_sum'] >= $warehouse_tax)){
						// Else remove from Vault
						$sql = "UPDATE " . ADR_VAULT_USERS_TABLE . "
								SET account_sum = (account_sum - $warehouse_tax)
								WHERE owner_id = '$character_id'";
						if(!$db->sql_query($sql)){
							message_die(CRITICAL_ERROR, 'Error removing points for WH tax (vault)');}
	
						$wh_msg = sprintf( $lang['Adr_character_warehouse_tax'], $warehouse_tax, get_reward_name());
					}
					else{
						// Close warehouse
						$sql = " UPDATE " . ADR_CHARACTERS_TABLE . "
							SET character_warehouse = 0
								WHERE character_id = '$character_id'";
						if(!$db->sql_query($sql)){
							message_die(GENERAL_MESSAGE, 'Error closing warehouse');}
	
						// Remove warehouse status from itemsif rent not paid
						$sql = "UPDATE " . ADR_SHOPS_ITEMS_TABLE ."
							SET item_in_warehouse = 0
							WHERE item_owner_id = '$character_id'";
						if(!$db->sql_query($sql)){
							message_die(GENERAL_ERROR, 'Could not remove WH status for items', "", __LINE__, __FILE__, $sql);}
	
						$wh_msg = sprintf($lang['Adr_character_warehouse_closed'], $warehouse_tax, get_reward_name());
					}
					
					// Check for any messages to use later on in PM to user
					$pm_wh = TRUE;
				}

				// Update user warehouse update time
				$sql = " UPDATE " . ADR_CHARACTERS_TABLE . "
					SET character_warehouse_update = $character_days
						WHERE character_id = '$character_id'";
				if(!($results = $db->sql_query($sql))){
					message_die(GENERAL_MESSAGE, 'Error updating warehouse');}
	
			}// END warehouse tax check

			// START open shop tax check
			if((is_numeric($character_id)) && ($character_days > ($adr_user['character_shop_update'] + ($board_config['Adr_shop_duration'] -1))))
			{
				$shop_days = ($character_days - ($adr_user['character_shop_update'] + ($board_config['Adr_shop_duration'] - 1)));
				$shop_tax = $board_config['Adr_shop_tax'] *$shop_days;
	
				// see if user has a shop open
				if((is_numeric($shop_check['shop_id'])) && ($board_config['Adr_shop_tax'] > '0')){
					if($points >= $shop_tax){
						// Remove tax from user_points
						subtract_reward($character_id, $shop_tax);
						$shop_msg = sprintf($lang['Adr_character_shop_tax'], $shop_tax, get_reward_name());

					}
					elseif(($points < $shop_tax) && (is_numeric($vault_check['account_sum'])) && ($vault_check['account_sum'] >= $shop_tax)){
						$sql = "UPDATE " . ADR_VAULT_USERS_TABLE . "
								SET account_sum = (account_sum - $shop_tax)
								WHERE owner_id = '$character_id'";
						if(!$db->sql_query($sql)){
							message_die(CRITICAL_ERROR, 'Error removing points for shop (vault)');}
	
 						$shop_msg = sprintf($lang['Adr_character_shop_tax'], $shop_tax, get_reward_name());
					}
					else{
						// Remove shop status from items & close shop if rent not paid
						$sql = "UPDATE " . ADR_SHOPS_ITEMS_TABLE ."
							SET item_in_shop = 0
							WHERE item_owner_id = '$character_id'";
						if(!$db->sql_query($sql)){
							message_die(GENERAL_ERROR, 'Could not remove shop status for items', "", __LINE__, __FILE__, $sql);}

						$sql = "DELETE FROM " . ADR_SHOPS_TABLE ."
							WHERE shop_owner_id = '$character_id'";
						if(!$db->sql_query($sql)){
							message_die(GENERAL_ERROR, 'Could not delete shop', "", __LINE__, __FILE__, $sql);}

						// Remove all transaction logs for user store
						$sql = "DELETE FROM " . ADR_STORES_USER_HISTORY ."
							WHERE user_store_owner_id = '$character_id'";
						if( !$db->sql_query($sql) ){
							message_die(GENERAL_ERROR, 'Could not delete user store trans logs', "", __LINE__, __FILE__, $sql);}

						$shop_msg = sprintf($lang['Adr_character_shop_closed'], $shop_tax, get_reward_name());
					}
					
					// Check for any messages to use later on in PM to user
					$pm_shop = TRUE;
				}

				// Last shop update
				$sql = " UPDATE " . ADR_CHARACTERS_TABLE . "
					SET character_shop_update = $character_days
						WHERE character_id = '$character_id'";
				if(!($results = $db->sql_query($sql))){
					message_die(GENERAL_MESSAGE, 'Error updating warehouse');}

			}// END open shop tax check

			// Here we need to check whether to send user a PM notification of charges
			if(($pm_wh === TRUE) || ($pm_shop === TRUE)){
				$subject = sprintf($lang['Adr_tax_pm_sub']);
				if($pm_wh === TRUE)	$message = $wh_msg.'&nbsp;';
				if($pm_shop === TRUE) $message .= $shop_msg;
				adr_send_pm($character_id, $subject, $message, 2);
			}
		}
	}

	return;
}

function adr_timezone()
{
	global $db, $board_config;

	// Show current ADR hour
	$diff = ((time() - $board_config['Adr_time_start']) *10);
	$adr_years = 0; $adr_months = 0; $adr_days = 0; $adr_hours = 0; $adr_mins = 0; $adr_secs = 0;

	$sec_in_a_year = ((((60 *60) *24) *30) *12);
		while($diff >= $sec_in_a_year){$adr_years++; $diff -= $sec_in_a_year;}
	$sec_in_a_month = (((60 *60) *24) *30);
		while($diff >= $sec_in_a_month){$adr_months++; $diff -= $sec_in_a_month;}
	$sec_in_a_day = ((60 *60) *24);
		while($diff >= $sec_in_a_day){$adr_days++; $diff -= $sec_in_a_day;}
	$sec_in_an_hour = (60 *60);
		while($diff >= $sec_in_an_hour){$adr_hours++; $diff -= $sec_in_an_hour;}
	$sec_in_a_min = 60;
		while($diff >= $sec_in_a_min){$adr_mins++; $diff -= $sec_in_a_min;}
	$secsDiff = $diff;

	// Prevent
	$adr_months = ($adr_months + 1);
	$adr_days = ($adr_days + 1);
	if($adr_months > '12') $adr_months = 1; // Prevent month 13
	if($adr_days > '30') $adr_days = 1; // Prevent month longer than 30 days
	if($adr_hours > '23') $adr_hours = 0; // Prevent hour 24
	if($adr_mins > '59') $adr_mins = 0; // Prevent min 60

	// Add prefix zero if required
	if($adr_months < '10') $adr_months = "0".$adr_months;
	if($adr_days < '10') $adr_days = "0".$adr_days;
	if($adr_hours < '10') $adr_hours = "0".$adr_hours;
	if($adr_mins < '10') $adr_mins = "0".$adr_mins;

	$current_adr_time = $adr_years.":".$adr_months.":".$adr_days.":".$adr_hours.":".$adr_mins;

	return $current_adr_time;
}


?>