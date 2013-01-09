SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `adr_alignments` (
  `alignment_id` smallint(8) NOT NULL DEFAULT '0',
  `alignment_name` varchar(255) NOT NULL DEFAULT '',
  `alignment_desc` text NOT NULL,
  `alignment_level` tinyint(1) NOT NULL DEFAULT '0',
  `alignment_img` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`alignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_alignments` (`alignment_id`, `alignment_name`, `alignment_desc`, `alignment_level`, `alignment_img`) VALUES
(1, 'Adr_alignment_neutral', 'Adr_alignment_neutral_desc', 0, 'Neutral.gif'),
(2, 'Adr_alignment_evil', 'Adr_alignment_evil_desc', 0, 'Evil.gif'),
(3, 'Adr_alignment_good', 'Adr_alignment_good_desc', 0, 'Good.gif');

CREATE TABLE IF NOT EXISTS `adr_battle_community` (
  `chat_id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_posts` int(10) NOT NULL DEFAULT '0',
  `chat_text` text,
  `chat_date` date DEFAULT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `adr_battle_list` (
  `battle_id` int(8) NOT NULL AUTO_INCREMENT,
  `battle_type` tinyint(1) NOT NULL DEFAULT '0',
  `battle_turn` tinyint(1) NOT NULL DEFAULT '0',
  `battle_result` tinyint(1) NOT NULL DEFAULT '0',
  `battle_text` text NOT NULL,
  `battle_challenger_equipment_info` varchar(255) NOT NULL DEFAULT '',
  `battle_round` int(12) NOT NULL DEFAULT '0',
  `battle_start` int(12) NOT NULL DEFAULT '0',
  `battle_finish` int(12) NOT NULL DEFAULT '0',
  `battle_challenger_id` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_hp` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_mp` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_att` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_def` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_magic_attack` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_magic_resistance` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_dmg` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_element` int(3) NOT NULL DEFAULT '0',
  `battle_opponent_id` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_hp` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_mp` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_att` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_def` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_magic_attack` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_magic_resistance` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_mp_power` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_sp` int(12) NOT NULL DEFAULT '0',
  `battle_opponent_dmg` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_hp_max` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_mp_max` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_element` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`battle_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

CREATE TABLE IF NOT EXISTS `adr_battle_monsters` (
  `monster_id` int(8) NOT NULL DEFAULT '0',
  `monster_name` varchar(255) NOT NULL DEFAULT '',
  `monster_img` varchar(255) NOT NULL DEFAULT '',
  `monster_level` int(8) NOT NULL DEFAULT '0',
  `monster_base_hp` int(8) NOT NULL DEFAULT '0',
  `monster_base_att` int(8) NOT NULL DEFAULT '0',
  `monster_base_def` int(8) NOT NULL DEFAULT '0',
  `monster_base_mp` int(8) NOT NULL DEFAULT '10',
  `monster_base_mp_power` int(8) NOT NULL DEFAULT '1',
  `monster_base_custom_spell` varchar(255) NOT NULL DEFAULT 'a magical spell',
  `monster_base_magic_attack` int(8) NOT NULL DEFAULT '10',
  `monster_base_magic_resistance` int(8) NOT NULL DEFAULT '10',
  `monster_base_sp` int(8) NOT NULL DEFAULT '0',
  `monster_thief_skill` int(3) NOT NULL DEFAULT '0',
  `monster_base_element` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`monster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_battle_monsters` (`monster_id`, `monster_name`, `monster_img`, `monster_level`, `monster_base_hp`, `monster_base_att`, `monster_base_def`, `monster_base_mp`, `monster_base_mp_power`, `monster_base_custom_spell`, `monster_base_magic_attack`, `monster_base_magic_resistance`, `monster_base_sp`, `monster_thief_skill`, `monster_base_element`) VALUES
(1, 'Globuz', 'Monster1.jpg', 1, 15, 30, 30, 10, 1, 'a magical spell', 10, 10, 0, 0, 1),
(2, 'Kargh', 'Monster2.jpg', 2, 20, 40, 60, 10, 1, 'a magical spell', 10, 10, 0, 0, 1),
(3, 'Bouglou', 'Monster3.jpg', 1, 14, 40, 70, 10, 1, 'a magical spell', 10, 10, 0, 0, 1),
(4, 'Dretg', 'Monster4.jpg', 1, 25, 30, 30, 10, 1, 'a magical spell', 10, 10, 0, 0, 1),
(5, 'Greyiok', 'Monster5.jpg', 1, 10, 70, 70, 10, 1, 'a magical spell', 10, 10, 0, 0, 1),
(6, 'Itchy', 'Monster6.jpg', 2, 25, 90, 80, 10, 1, 'a magical spell', 10, 10, 0, 0, 1),
(7, 'Globber', 'Monster7.jpg', 3, 45, 250, 200, 10, 1, 'a magical spell', 10, 10, 0, 0, 1),
(8, 'Scratchy', 'Monster8.jpg', 4, 80, 350, 300, 10, 1, 'a magical spell', 10, 10, 0, 0, 1);

CREATE TABLE IF NOT EXISTS `adr_battle_pvp` (
  `battle_id` int(8) NOT NULL AUTO_INCREMENT,
  `battle_turn` int(8) NOT NULL DEFAULT '0',
  `battle_result` tinyint(1) NOT NULL DEFAULT '0',
  `battle_text` text NOT NULL,
  `battle_text_chat` text NOT NULL,
  `battle_start` int(12) NOT NULL DEFAULT '0',
  `battle_finish` int(12) NOT NULL DEFAULT '0',
  `battle_challenger_id` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_att` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_def` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_hp` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_mp` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_hp_max` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_mp_max` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_hp_regen` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_mp_regen` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_dmg` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_magic_attack` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_magic_resistance` int(8) NOT NULL DEFAULT '0',
  `battle_challenger_element` int(3) NOT NULL DEFAULT '0',
  `battle_opponent_id` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_att` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_def` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_hp` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_mp` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_hp_max` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_mp_max` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_hp_regen` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_mp_regen` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_dmg` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_magic_attack` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_magic_resistance` int(8) NOT NULL DEFAULT '0',
  `battle_opponent_element` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`battle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `adr_characters` (
  `character_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `character_name` varchar(255) NOT NULL DEFAULT '',
  `character_desc` text NOT NULL,
  `character_race` int(8) NOT NULL DEFAULT '0',
  `character_class` int(8) NOT NULL DEFAULT '0',
  `character_alignment` int(8) NOT NULL DEFAULT '0',
  `character_element` int(8) NOT NULL DEFAULT '0',
  `character_hp` int(8) NOT NULL DEFAULT '0',
  `character_hp_max` int(8) NOT NULL DEFAULT '0',
  `character_mp` int(8) NOT NULL DEFAULT '0',
  `character_mp_max` int(8) NOT NULL DEFAULT '0',
  `character_ac` int(8) NOT NULL DEFAULT '0',
  `character_xp` int(11) NOT NULL DEFAULT '0',
  `character_level` int(8) NOT NULL DEFAULT '1',
  `character_might` int(8) NOT NULL DEFAULT '0',
  `character_dexterity` int(8) NOT NULL DEFAULT '0',
  `character_constitution` int(8) NOT NULL DEFAULT '0',
  `character_intelligence` int(8) NOT NULL DEFAULT '0',
  `character_wisdom` int(8) NOT NULL DEFAULT '0',
  `character_charisma` int(8) NOT NULL DEFAULT '0',
  `character_birth` int(12) NOT NULL DEFAULT '1093694853',
  `character_limit_update` int(8) NOT NULL DEFAULT '1',
  `character_battle_limit` int(3) NOT NULL DEFAULT '20',
  `character_skill_limit` int(3) NOT NULL DEFAULT '30',
  `character_trading_limit` int(3) NOT NULL DEFAULT '30',
  `character_thief_limit` int(3) NOT NULL DEFAULT '10',
  `character_sp` int(12) NOT NULL DEFAULT '0',
  `character_magic_attack` int(8) NOT NULL DEFAULT '10',
  `character_magic_resistance` int(8) NOT NULL DEFAULT '10',
  `character_warehouse` tinyint(1) NOT NULL DEFAULT '0',
  `character_warehouse_update` int(8) NOT NULL DEFAULT '0',
  `character_shop_update` int(8) NOT NULL DEFAULT '0',
  `character_skill_mining` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_stone` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_forge` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_enchantment` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_trading` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_thief` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_mining_uses` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_stone_uses` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_forge_uses` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_enchantment_uses` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_trading_uses` int(8) unsigned NOT NULL DEFAULT '0',
  `character_skill_thief_uses` int(8) unsigned NOT NULL DEFAULT '0',
  `character_victories` int(8) NOT NULL DEFAULT '0',
  `character_defeats` int(8) NOT NULL DEFAULT '0',
  `character_flees` int(8) NOT NULL DEFAULT '0',
  `prefs_pvp_notif_pm` tinyint(1) NOT NULL DEFAULT '1',
  `prefs_pvp_allow` tinyint(1) NOT NULL DEFAULT '1',
  `prefs_tax_pm_notify` tinyint(1) NOT NULL DEFAULT '1',
  `equip_armor` int(8) NOT NULL DEFAULT '0',
  `equip_buckler` int(8) NOT NULL DEFAULT '0',
  `equip_helm` int(8) NOT NULL DEFAULT '0',
  `equip_gloves` int(8) NOT NULL DEFAULT '0',
  `equip_amulet` int(8) NOT NULL DEFAULT '0',
  `equip_ring` int(8) NOT NULL DEFAULT '0',
  `character_pref_give_pm` int(1) NOT NULL DEFAULT '1',
  `character_pref_seller_pm` int(1) NOT NULL DEFAULT '1',
  `character_double_ko` int(8) NOT NULL DEFAULT '0',
  `character_victories_pvp` int(8) NOT NULL DEFAULT '0',
  `character_defeats_pvp` int(8) NOT NULL DEFAULT '0',
  `character_flees_pvp` int(8) NOT NULL DEFAULT '0',
  `character_fp` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`character_id`),
  KEY `character_id` (`character_id`,`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=176 ;

CREATE TABLE IF NOT EXISTS `adr_classes` (
  `class_id` smallint(8) NOT NULL DEFAULT '0',
  `class_name` varchar(255) NOT NULL DEFAULT '',
  `class_desc` text NOT NULL,
  `class_level` tinyint(1) NOT NULL DEFAULT '0',
  `class_img` varchar(255) NOT NULL DEFAULT '',
  `class_might_req` int(8) NOT NULL DEFAULT '0',
  `class_dexterity_req` int(8) NOT NULL DEFAULT '0',
  `class_constitution_req` int(8) NOT NULL DEFAULT '0',
  `class_intelligence_req` int(8) NOT NULL DEFAULT '0',
  `class_wisdom_req` int(8) NOT NULL DEFAULT '0',
  `class_charisma_req` int(8) NOT NULL DEFAULT '0',
  `class_base_hp` int(8) NOT NULL DEFAULT '0',
  `class_base_mp` int(8) NOT NULL DEFAULT '0',
  `class_base_ac` int(8) NOT NULL DEFAULT '0',
  `class_update_hp` int(8) NOT NULL DEFAULT '0',
  `class_update_mp` int(8) NOT NULL DEFAULT '0',
  `class_update_ac` int(8) NOT NULL DEFAULT '0',
  `class_update_xp_req` int(8) NOT NULL DEFAULT '0',
  `class_update_of` int(8) NOT NULL DEFAULT '0',
  `class_update_of_req` int(8) NOT NULL DEFAULT '0',
  `class_selectable` int(8) NOT NULL DEFAULT '0',
  `class_magic_attack_req` int(8) NOT NULL DEFAULT '0',
  `class_magic_resistance_req` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_classes` (`class_id`, `class_name`, `class_desc`, `class_level`, `class_img`, `class_might_req`, `class_dexterity_req`, `class_constitution_req`, `class_intelligence_req`, `class_wisdom_req`, `class_charisma_req`, `class_base_hp`, `class_base_mp`, `class_base_ac`, `class_update_hp`, `class_update_mp`, `class_update_ac`, `class_update_xp_req`, `class_update_of`, `class_update_of_req`, `class_selectable`, `class_magic_attack_req`, `class_magic_resistance_req`) VALUES
(1, 'Adr_class_fighter', 'Adr_class_fighter_desc', 0, 'Fighter.gif', 0, 0, 0, 0, 0, 0, 30, 2, 15, 3, 0, 1, 1500, 0, 0, 1, 0, 0),
(2, 'Adr_class_barbare', 'Adr_class_barbare_desc', 0, 'Barbare.gif', 12, 0, 10, 0, 0, 0, 40, 1, 10, 4, 0, 0, 2000, 1, 5, 1, 0, 0),
(3, 'Adr_class_druid', 'Adr_class_druid_desc', 0, 'Druid.gif', 0, 0, 0, 0, 10, 0, 20, 20, 10, 1, 2, 2, 1800, 0, 0, 1, 0, 0),
(4, 'Adr_class_bard', 'Adr_class_bard_desc', 0, 'Bard.gif', 3, 3, 3, 3, 3, 10, 15, 15, 15, 2, 2, 2, 1200, 0, 0, 1, 0, 0),
(5, 'Adr_class_magician', 'Adr_class_magician_desc', 0, 'Magician.gif', 0, 0, 0, 14, 14, 0, 8, 30, 5, 0, 1, 3, 2500, 0, 0, 1, 0, 0),
(6, 'Adr_class_monk', 'Adr_class_monk_desc', 0, 'Monk.gif', 5, 5, 5, 5, 5, 5, 25, 10, 20, 2, 2, 1, 2400, 0, 0, 1, 0, 0),
(7, 'Adr_class_paladin', 'Adr_class_paladin_desc', 0, 'Paladin.gif', 10, 8, 10, 10, 8, 15, 40, 15, 20, 2, 4, 1, 3000, 0, 0, 1, 0, 0),
(8, 'Adr_class_priest', 'Adr_class_priest_desc', 0, 'Priest.gif', 0, 0, 0, 10, 12, 0, 20, 20, 15, 1, 2, 2, 2000, 0, 0, 1, 0, 0),
(9, 'Adr_class_sorceror', 'Adr_class_sorceror_desc', 0, 'Sorcerer.gif', 0, 0, 0, 16, 0, 0, 30, 100, 10, 0, 1, 10, 4500, 5, 10, 0, 0, 0),
(10, 'Adr_class_thief', 'Adr_class_thief_desc', 0, 'Thief.gif', 0, 12, 0, 0, 0, 0, 20, 10, 10, 1, 2, 1, 1500, 0, 0, 1, 0, 0);

CREATE TABLE IF NOT EXISTS `adr_elements` (
  `element_id` smallint(8) NOT NULL DEFAULT '0',
  `element_name` varchar(255) NOT NULL DEFAULT '',
  `element_desc` text NOT NULL,
  `element_level` tinyint(1) NOT NULL DEFAULT '0',
  `element_img` varchar(255) NOT NULL DEFAULT '',
  `element_skill_mining_bonus` int(8) NOT NULL DEFAULT '0',
  `element_skill_stone_bonus` int(8) NOT NULL DEFAULT '0',
  `element_skill_forge_bonus` int(8) NOT NULL DEFAULT '0',
  `element_skill_enchantment_bonus` int(8) NOT NULL DEFAULT '0',
  `element_skill_trading_bonus` int(8) NOT NULL DEFAULT '0',
  `element_skill_thief_bonus` int(8) NOT NULL DEFAULT '0',
  `element_oppose_strong` int(3) NOT NULL DEFAULT '0',
  `element_oppose_strong_dmg` int(3) NOT NULL DEFAULT '100',
  `element_oppose_same_dmg` int(3) NOT NULL DEFAULT '100',
  `element_oppose_weak` int(3) NOT NULL DEFAULT '0',
  `element_oppose_weak_dmg` int(3) NOT NULL DEFAULT '100',
  `element_colour` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_elements` (`element_id`, `element_name`, `element_desc`, `element_level`, `element_img`, `element_skill_mining_bonus`, `element_skill_stone_bonus`, `element_skill_forge_bonus`, `element_skill_enchantment_bonus`, `element_skill_trading_bonus`, `element_skill_thief_bonus`, `element_oppose_strong`, `element_oppose_strong_dmg`, `element_oppose_same_dmg`, `element_oppose_weak`, `element_oppose_weak_dmg`, `element_colour`) VALUES
(1, 'Adr_element_water', 'Adr_element_water_desc', 0, 'Water.gif', 0, 0, 0, 1, 0, 0, 0, 100, 100, 0, 100, ''),
(2, 'Adr_element_earth', 'Adr_element_earth_desc', 0, 'Earth.gif', 1, 1, 0, 0, 0, 0, 0, 100, 100, 0, 100, ''),
(3, 'Adr_element_holy', 'Adr_element_holy_desc', 2, 'Holy.gif', 1, 1, 1, 1, 1, 1, 0, 100, 100, 0, 100, ''),
(4, 'Adr_element_fire', 'Adr_element_fire_desc', 0, 'Fire.gif', 0, 0, 1, 0, 0, 0, 0, 100, 100, 0, 100, '');

CREATE TABLE IF NOT EXISTS `adr_general` (
  `config_name` varchar(255) NOT NULL DEFAULT '0',
  `config_value` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_general` (`config_name`, `config_value`) VALUES
('adr_version', '2'),
('allow_character_delete', '1'),
('allow_reroll', '1'),
('allow_shop_steal', '1'),
('battle_base_exp_max', '40'),
('battle_base_exp_min', '10'),
('battle_base_exp_modifier', '120'),
('battle_base_reward_max', '40'),
('battle_base_reward_min', '10'),
('battle_base_reward_modifier', '120'),
('battle_base_sp_modifier', '120'),
('battle_calc_type', '1'),
('battle_enable', '1'),
('battle_monster_stats_modifier', '150'),
('battle_pvp_defies_max', '5'),
('battle_pvp_enable', '1'),
('cache_interval', '15'),
('cache_last_updated', '0'),
('cell_allow_user_blank', '1'),
('cell_allow_user_caution', '1'),
('cell_allow_user_judge', '1'),
('cell_amount_user_blank', '5000'),
('cell_user_judge_posts', '2'),
('cell_user_judge_voters', '10'),
('character_age', '16'),
('character_battle_limit', '20'),
('character_limit_enable', '1'),
('character_skill_limit', '30'),
('character_sp_enable', '0'),
('character_thief_limit', '10'),
('character_trading_limit', '30'),
('disable_rpg', '1'),
('experience_for_edit', '1'),
('experience_for_new', '10'),
('experience_for_reply', '5'),
('interests_rate', '4'),
('interests_time', '86400'),
('item_modifier_power', '100'),
('item_power_level', '0'),
('limit_regen_duration', '1'),
('loan_interests', '15'),
('loan_interests_time', '864000'),
('loan_max_sum', '5000'),
('loan_requirements', '0'),
('max_characteristic', '20'),
('min_characteristic', '3'),
('new_shop_price', '500'),
('next_level_penalty', '10'),
('points_name', 'points'),
('posts_enable', '0'),
('posts_min', '1'),
('profile_display', '1'),
('pvp_base_exp_max', '40'),
('pvp_base_exp_min', '10'),
('pvp_base_exp_modifier', '120'),
('pvp_base_reward_max', '40'),
('pvp_base_reward_min', '10'),
('pvp_base_reward_modifier', '120'),
('shop_duration', '1'),
('shop_steal_min_lvl', '5'),
('shop_steal_sell', '1'),
('shop_steal_show', '0'),
('shop_tax', '10'),
('skill_sp_enable', '0'),
('skill_thief_failure_damage', '2000'),
('skill_thief_failure_punishment', '1'),
('skill_thief_failure_time', '6'),
('skill_thief_failure_type', '2'),
('skill_trading_power', '2'),
('stock_last_change', '0'),
('stock_max_change', '10'),
('stock_min_change', '0'),
('stock_time', '86400'),
('stock_use', '1'),
('temple_heal_cost', '100'),
('temple_resurrect_cost', '300'),
('thief_enable', '1'),
('thief_points', '5'),
('time_start', 'time()'),
('topics_display', '1-1-0-0-0-1'),
('training_allow_change', '1'),
('training_change_cost', '100'),
('training_charac_cost', '3000'),
('training_skill_cost', '1000'),
('training_upgrade_cost', '10000'),
('use_cache_system', '0-0-0-0-0-0-0-0-0'),
('vault_enable', '1'),
('vault_loan_enable', '1'),
('warehouse_duration', '1'),
('warehouse_tax', '10'),
('weight_enable', '1');

CREATE TABLE IF NOT EXISTS `adr_jail_users` (
  `celled_id` int(8) NOT NULL DEFAULT '0',
  `user_id` int(8) NOT NULL DEFAULT '0',
  `user_cell_date` int(11) NOT NULL DEFAULT '0',
  `user_freed_by` int(8) NOT NULL DEFAULT '0',
  `user_sentence` text,
  `user_caution` int(8) NOT NULL DEFAULT '0',
  `user_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`celled_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `adr_jail_votes` (
  `vote_id` mediumint(8) NOT NULL DEFAULT '0',
  `voter_id` mediumint(8) NOT NULL DEFAULT '0',
  `vote_result` mediumint(8) NOT NULL DEFAULT '0',
  KEY `vote_id` (`vote_id`),
  KEY `voter_id` (`voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `adr_races` (
  `race_id` smallint(8) NOT NULL DEFAULT '0',
  `race_name` varchar(255) NOT NULL DEFAULT '',
  `race_desc` text NOT NULL,
  `race_level` tinyint(1) NOT NULL DEFAULT '0',
  `race_img` varchar(255) NOT NULL DEFAULT '',
  `race_might_bonus` int(8) NOT NULL DEFAULT '0',
  `race_dexterity_bonus` int(8) NOT NULL DEFAULT '0',
  `race_constitution_bonus` int(8) NOT NULL DEFAULT '0',
  `race_intelligence_bonus` int(8) NOT NULL DEFAULT '0',
  `race_wisdom_bonus` int(8) NOT NULL DEFAULT '0',
  `race_charisma_bonus` int(8) NOT NULL DEFAULT '0',
  `race_skill_mining_bonus` int(8) NOT NULL DEFAULT '0',
  `race_skill_stone_bonus` int(8) NOT NULL DEFAULT '0',
  `race_skill_forge_bonus` int(8) NOT NULL DEFAULT '0',
  `race_skill_enchantment_bonus` int(8) NOT NULL DEFAULT '0',
  `race_skill_trading_bonus` int(8) NOT NULL DEFAULT '0',
  `race_skill_thief_bonus` int(8) NOT NULL DEFAULT '0',
  `race_might_penalty` int(8) NOT NULL DEFAULT '0',
  `race_dexterity_penalty` int(8) NOT NULL DEFAULT '0',
  `race_constitution_penalty` int(8) NOT NULL DEFAULT '0',
  `race_intelligence_penalty` int(8) NOT NULL DEFAULT '0',
  `race_wisdom_penalty` int(8) NOT NULL DEFAULT '0',
  `race_charisma_penalty` int(8) NOT NULL DEFAULT '0',
  `race_weight` int(12) NOT NULL DEFAULT '1000',
  `race_weight_per_level` int(3) NOT NULL DEFAULT '5',
  `race_magic_attack_bonus` int(8) NOT NULL DEFAULT '0',
  `race_magic_resistance_bonus` int(8) NOT NULL DEFAULT '0',
  `race_magic_attack_penalty` int(8) NOT NULL DEFAULT '0',
  `race_magic_resistance_penalty` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`race_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_races` (`race_id`, `race_name`, `race_desc`, `race_level`, `race_img`, `race_might_bonus`, `race_dexterity_bonus`, `race_constitution_bonus`, `race_intelligence_bonus`, `race_wisdom_bonus`, `race_charisma_bonus`, `race_skill_mining_bonus`, `race_skill_stone_bonus`, `race_skill_forge_bonus`, `race_skill_enchantment_bonus`, `race_skill_trading_bonus`, `race_skill_thief_bonus`, `race_might_penalty`, `race_dexterity_penalty`, `race_constitution_penalty`, `race_intelligence_penalty`, `race_wisdom_penalty`, `race_charisma_penalty`, `race_weight`, `race_weight_per_level`, `race_magic_attack_bonus`, `race_magic_resistance_bonus`, `race_magic_attack_penalty`, `race_magic_resistance_penalty`) VALUES
(1, 'Adr_race_human', 'Adr_race_human_desc', 0, 'Human.gif', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1000, 5, 0, 0, 0, 0),
(2, 'Adr_race_half-elf', 'Adr_race_half-elf_desc', 0, 'Half-elf.gif', 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 1000, 5, 0, 0, 0, 0),
(3, 'Adr_race_half-orc', 'Adr_race_half-orc_desc', 0, 'Half-orc.gif', 2, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 3, 1000, 5, 0, 0, 0, 0),
(4, 'Adr_race_elf', 'Adr_race_elf_desc', 0, 'Elf.gif', 0, 2, 0, 0, 0, 2, 0, 0, 0, 1, 1, 0, 1, 0, 2, 0, 0, 0, 1000, 5, 0, 0, 0, 0),
(5, 'Adr_race_gnome', 'Adr_race_gnome_desc', 0, 'Gnome.gif', 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 2, 0, 0, 2, 0, 0, 0, 1000, 5, 0, 0, 0, 0),
(6, 'Adr_race_halfeling', 'Adr_race_halfeling_desc', 2, 'Halfeling.gif', 0, 2, 0, 0, 2, 0, 0, 0, 0, 0, 0, 3, 0, 0, 2, 0, 0, 0, 1000, 5, 0, 0, 0, 0),
(7, 'Adr_race_dwarf', 'Adr_race_dwarf_desc', 0, 'Dwarf.gif', 1, 0, 2, 0, 1, 0, 2, 2, 1, 0, 0, 0, 0, 2, 0, 0, 0, 3, 1000, 5, 0, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `adr_shops` (
  `shop_id` int(8) NOT NULL DEFAULT '0',
  `shop_owner_id` int(8) NOT NULL DEFAULT '0',
  `shop_name` varchar(255) NOT NULL DEFAULT '',
  `shop_desc` varchar(255) NOT NULL DEFAULT '',
  `shop_last_updated` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_shops` (`shop_id`, `shop_owner_id`, `shop_name`, `shop_desc`, `shop_last_updated`) VALUES
(1, 1, 'Adr_shop_forums', 'Adr_shop_forums_desc', 0);

CREATE TABLE IF NOT EXISTS `adr_shops_items` (
  `item_id` int(8) NOT NULL AUTO_INCREMENT,
  `item_owner_id` int(8) NOT NULL DEFAULT '0',
  `item_price` int(8) NOT NULL DEFAULT '0',
  `item_quality` int(8) NOT NULL DEFAULT '0',
  `item_power` int(8) NOT NULL DEFAULT '0',
  `item_duration` int(8) NOT NULL DEFAULT '0',
  `item_duration_max` int(8) NOT NULL DEFAULT '1',
  `item_icon` varchar(255) NOT NULL DEFAULT '',
  `item_name` varchar(255) NOT NULL DEFAULT '',
  `item_desc` varchar(255) NOT NULL DEFAULT '',
  `item_type_use` int(8) NOT NULL DEFAULT '16',
  `item_in_shop` tinyint(1) NOT NULL DEFAULT '0',
  `item_store_id` int(8) NOT NULL DEFAULT '1',
  `item_weight` int(12) NOT NULL DEFAULT '25',
  `item_auth` int(1) NOT NULL DEFAULT '0',
  `item_max_skill` int(8) NOT NULL DEFAULT '25',
  `item_add_power` int(8) NOT NULL DEFAULT '0',
  `item_mp_use` int(8) NOT NULL DEFAULT '0',
  `item_monster_thief` tinyint(1) NOT NULL DEFAULT '0',
  `item_element` int(4) NOT NULL DEFAULT '0',
  `item_element_str_dmg` int(4) NOT NULL DEFAULT '100',
  `item_element_same_dmg` int(4) NOT NULL DEFAULT '100',
  `item_element_weak_dmg` int(4) NOT NULL DEFAULT '100',
  `item_in_warehouse` tinyint(1) NOT NULL DEFAULT '0',
  `item_sell_back_percentage` int(3) NOT NULL DEFAULT '50',
  `item_stolen_id` int(12) NOT NULL DEFAULT '0',
  `item_steal_dc` smallint(3) NOT NULL DEFAULT '0',
  `item_bought_timestamp` int(12) NOT NULL DEFAULT '0',
  `item_restrict_align_enable` tinyint(1) NOT NULL DEFAULT '0',
  `item_restrict_align` varchar(255) NOT NULL DEFAULT '0',
  `item_restrict_class_enable` tinyint(1) NOT NULL DEFAULT '0',
  `item_restrict_class` varchar(255) NOT NULL DEFAULT '0',
  `item_restrict_element_enable` tinyint(1) NOT NULL DEFAULT '0',
  `item_restrict_element` varchar(255) NOT NULL DEFAULT '0',
  `item_restrict_race_enable` tinyint(1) NOT NULL DEFAULT '0',
  `item_restrict_race` varchar(255) NOT NULL DEFAULT '0',
  `item_restrict_level` int(8) NOT NULL DEFAULT '0',
  `item_restrict_str` int(8) NOT NULL DEFAULT '0',
  `item_restrict_dex` int(8) NOT NULL DEFAULT '0',
  `item_restrict_int` int(8) NOT NULL DEFAULT '0',
  `item_restrict_wis` int(8) NOT NULL DEFAULT '0',
  `item_restrict_cha` int(8) NOT NULL DEFAULT '0',
  `item_restrict_con` int(8) NOT NULL DEFAULT '0',
  `item_crit_hit` smallint(3) NOT NULL DEFAULT '20',
  `item_crit_hit_mod` smallint(3) NOT NULL DEFAULT '2',
  `item_stolen_timestamp` int(12) NOT NULL DEFAULT '0',
  `item_stolen_by` varchar(255) NOT NULL DEFAULT '',
  `item_donated_timestamp` int(12) NOT NULL DEFAULT '0',
  `item_donated_by` varchar(255) NOT NULL DEFAULT '',
  KEY `item_id` (`item_id`),
  KEY `item_owner_id` (`item_owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

INSERT INTO `adr_shops_items` (`item_id`, `item_owner_id`, `item_price`, `item_quality`, `item_power`, `item_duration`, `item_duration_max`, `item_icon`, `item_name`, `item_desc`, `item_type_use`, `item_in_shop`, `item_store_id`, `item_weight`, `item_auth`, `item_max_skill`, `item_add_power`, `item_mp_use`, `item_monster_thief`, `item_element`, `item_element_str_dmg`, `item_element_same_dmg`, `item_element_weak_dmg`, `item_in_warehouse`, `item_sell_back_percentage`, `item_stolen_id`, `item_steal_dc`, `item_bought_timestamp`, `item_restrict_align_enable`, `item_restrict_align`, `item_restrict_class_enable`, `item_restrict_class`, `item_restrict_element_enable`, `item_restrict_element`, `item_restrict_race_enable`, `item_restrict_race`, `item_restrict_level`, `item_restrict_str`, `item_restrict_dex`, `item_restrict_int`, `item_restrict_wis`, `item_restrict_cha`, `item_restrict_con`, `item_crit_hit`, `item_crit_hit_mod`, `item_stolen_timestamp`, `item_stolen_by`, `item_donated_timestamp`, `item_donated_by`) VALUES
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
(22, 1, 10, 4, 10, 5, 5, 'potion2.gif', 'Mana Potion', '', 16, 0, 1, 3, 0, 0, 0, 0, 0, 0, 100, 100, 100, 0, 5, 0, 0, 0, 0, '0', 0, '0', 0, '0', 0, '0', 0, 0, 0, 0, 0, 0, 0, 20, 2, 0, '', 0, '');

CREATE TABLE IF NOT EXISTS `adr_shops_items_quality` (
  `item_quality_id` int(8) NOT NULL DEFAULT '0',
  `item_quality_modifier_price` int(8) NOT NULL DEFAULT '0',
  `item_quality_lang` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_quality_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_shops_items_quality` (`item_quality_id`, `item_quality_modifier_price`, `item_quality_lang`) VALUES
(0, 0, 'Adr_dont_care'),
(1, 20, 'Adr_items_quality_very_poor'),
(2, 50, 'Adr_items_quality_poor'),
(3, 100, 'Adr_items_quality_medium'),
(4, 140, 'Adr_items_quality_good'),
(5, 200, 'Adr_items_quality_very_good'),
(6, 300, 'Adr_items_quality_excellent');

CREATE TABLE IF NOT EXISTS `adr_shops_items_type` (
  `item_type_id` int(8) NOT NULL DEFAULT '0',
  `item_type_base_price` int(8) NOT NULL DEFAULT '0',
  `item_type_lang` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_shops_items_type` (`item_type_id`, `item_type_base_price`, `item_type_lang`) VALUES
(0, 0, 'Adr_dont_care'),
(1, 50, 'Adr_items_type_raw_materials'),
(2, 200, 'Adr_items_type_rare_raw_materials'),
(3, 100, 'Adr_items_type_tools_pickaxe'),
(4, 100, 'Adr_items_type_tools_magictome'),
(5, 100, 'Adr_items_type_weapon'),
(6, 1000, 'Adr_items_type_enchanted_weapon'),
(7, 200, 'Adr_items_type_armor'),
(8, 100, 'Adr_items_type_buckler'),
(9, 50, 'Adr_items_type_helm'),
(10, 50, 'Adr_items_type_gloves'),
(11, 500, 'Adr_items_type_magic_attack'),
(12, 500, 'Adr_items_type_magic_defend'),
(13, 5000, 'Adr_items_type_amulet'),
(14, 10000, 'Adr_items_type_ring'),
(15, 20, 'Adr_items_type_health_potion'),
(16, 20, 'Adr_items_type_mana_potion'),
(17, 1, 'Adr_items_type_misc');

CREATE TABLE IF NOT EXISTS `adr_skills` (
  `skill_id` tinyint(1) NOT NULL DEFAULT '0',
  `skill_name` varchar(255) NOT NULL DEFAULT '',
  `skill_desc` text NOT NULL,
  `skill_img` varchar(255) NOT NULL DEFAULT '',
  `skill_req` int(8) NOT NULL DEFAULT '0',
  `skill_chance` mediumint(3) NOT NULL DEFAULT '5',
  PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_skills` (`skill_id`, `skill_name`, `skill_desc`, `skill_img`, `skill_req`, `skill_chance`) VALUES
(1, 'Adr_mining', 'Adr_skill_mining_desc', 'skill_mining.gif', 100, 5),
(2, 'Adr_stone', 'Adr_skill_stone_desc', 'skill_stone.gif', 200, 5),
(3, 'Adr_forge', 'Adr_skill_forge_desc', 'skill_forge.gif', 50, 5),
(4, 'Adr_enchantment', 'Adr_skill_enchantment_desc', 'skill_enchantment.gif', 300, 5),
(5, 'Adr_trading', 'Adr_skill_trading_desc', 'skill_trading.gif', 80, 5),
(6, 'Adr_thief', 'Adr_skill_thief_desc', 'skill_thief.gif', 70, 5);

CREATE TABLE IF NOT EXISTS `adr_stores` (
  `store_id` int(8) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(100) NOT NULL DEFAULT '',
  `store_desc` varchar(255) NOT NULL DEFAULT '',
  `store_img` varchar(255) NOT NULL DEFAULT '',
  `store_status` tinyint(1) NOT NULL DEFAULT '1',
  `store_sales_status` tinyint(1) NOT NULL DEFAULT '0',
  `store_admin` tinyint(1) NOT NULL DEFAULT '0',
  `store_owner_id` int(1) NOT NULL DEFAULT '1',
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `adr_stores` (`store_id`, `store_name`, `store_desc`, `store_img`, `store_status`, `store_sales_status`, `store_admin`, `store_owner_id`) VALUES
(1, 'Forum Store', 'The general forum store', '', 1, 0, 0, 1),
(2, 'Admin Only Store', 'Viewable only by the board admin', '', 1, 0, 1, 1);

CREATE TABLE IF NOT EXISTS `adr_stores_stats` (
  `store_stats_character_id` int(12) NOT NULL DEFAULT '0',
  `store_stats_store_id` int(12) NOT NULL DEFAULT '0',
  `store_stats_buy_total` int(12) NOT NULL DEFAULT '0',
  `store_stats_buy_last` int(12) NOT NULL DEFAULT '0',
  `store_stats_sold_total` int(12) NOT NULL DEFAULT '0',
  `store_stats_sold_last` int(12) NOT NULL DEFAULT '0',
  `store_stats_stolen_item_total` int(12) NOT NULL DEFAULT '0',
  `store_stats_stolen_item_fail_total` int(12) NOT NULL DEFAULT '0',
  `store_stats_stolen_item_last` int(12) NOT NULL DEFAULT '0',
  `store_stats_stolen_points_total` int(12) NOT NULL DEFAULT '0',
  `store_stats_stolen_points_last` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`store_stats_character_id`,`store_stats_store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `adr_stores_user_history` (
  `user_store_trans_id` int(12) NOT NULL DEFAULT '0',
  `user_store_owner_id` int(8) NOT NULL DEFAULT '0',
  `user_store_info` text NOT NULL,
  `user_store_total_price` int(12) NOT NULL DEFAULT '0',
  `user_store_date` int(12) NOT NULL DEFAULT '0',
  `user_store_buyer` text NOT NULL,
  PRIMARY KEY (`user_store_trans_id`,`user_store_owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `adr_users` (
  `userID` int(11) NOT NULL,
  `user_points` int(11) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `adr_vault_blacklist` (
  `user_id` int(8) NOT NULL DEFAULT '0',
  `user_due` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `adr_vault_exchange` (
  `stock_id` int(8) NOT NULL DEFAULT '0',
  `stock_name` varchar(40) NOT NULL DEFAULT '',
  `stock_desc` varchar(255) NOT NULL DEFAULT '',
  `stock_price` int(8) NOT NULL DEFAULT '0',
  `stock_previous_price` int(8) NOT NULL DEFAULT '0',
  `stock_best_price` int(8) NOT NULL DEFAULT '0',
  `stock_worst_price` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adr_vault_exchange` (`stock_id`, `stock_name`, `stock_desc`, `stock_price`, `stock_previous_price`, `stock_best_price`, `stock_worst_price`) VALUES
(1, 'Adr_vault_action_name_1', 'Adr_vault_action_desc_1', 113, 108, 113, 100),
(2, 'Adr_vault_action_name_2', 'Adr_vault_action_desc_2', 177, 192, 200, 177),
(3, 'Adr_vault_action_name_3', 'Adr_vault_action_desc_3', 280, 288, 300, 280);

CREATE TABLE IF NOT EXISTS `adr_vault_exchange_users` (
  `stock_id` mediumint(8) NOT NULL DEFAULT '0',
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `stock_amount` mediumint(8) NOT NULL DEFAULT '0',
  KEY `stock_id` (`stock_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `adr_vault_users` (
  `owner_id` int(8) NOT NULL DEFAULT '0',
  `account_id` int(8) NOT NULL DEFAULT '0',
  `account_sum` int(8) NOT NULL DEFAULT '0',
  `account_time` int(11) NOT NULL DEFAULT '0',
  `loan_sum` int(8) NOT NULL DEFAULT '0',
  `loan_time` int(11) NOT NULL DEFAULT '0',
  `account_protect` tinyint(1) NOT NULL DEFAULT '0',
  `loan_protect` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

