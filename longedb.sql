-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: long_e
-- ------------------------------------------------------
-- Server version	5.5.41-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_permissions`
--

DROP TABLE IF EXISTS `admin_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `resource` varchar(50) NOT NULL,
  `operations` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1875 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_resources`
--

DROP TABLE IF EXISTS `admin_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_resources` (
  `resource` varchar(50) NOT NULL,
  `resource_desc` varchar(255) NOT NULL,
  `operation_list` varchar(255) NOT NULL DEFAULT '',
  `parent` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`resource`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_roles`
--

DROP TABLE IF EXISTS `admin_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_roles` (
  `role` varchar(50) NOT NULL,
  `role_desc` varchar(255) NOT NULL,
  `parent` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `admin_userscol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `characters`
--

DROP TABLE IF EXISTS `characters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `characters` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `account` varchar(50) NOT NULL,
  `character_name` varchar(50) DEFAULT NULL,
  `server_id` int(11) NOT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `create_status` char(1) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `game_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `abbr` varchar(20) DEFAULT NULL,
  `exchange_rate` float DEFAULT '1',
  `logo_path` varchar(50) DEFAULT NULL,
  `currency` varchar(50) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `rank` tinyint(4) DEFAULT NULL,
  `is_active` bit(1) DEFAULT NULL,
  `fanpage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`game_id`),
  UNIQUE KEY `game_id_UNIQUE` (`game_id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_admin_actions`
--

DROP TABLE IF EXISTS `log_admin_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_admin_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_uid` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `action` varchar(45) DEFAULT NULL,
  `function` varchar(45) DEFAULT NULL,
  `desc` text,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2671 DEFAULT CHARSET=utf8 COMMENT='記錄後台動作';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_game_consumes`
--

DROP TABLE IF EXISTS `log_game_consumes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_game_consumes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `server_id` varchar(20) DEFAULT NULL,
  `game_id` varchar(20) DEFAULT NULL,
  `account` varchar(50) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10055 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_game_logins`
--

DROP TABLE IF EXISTS `log_game_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_game_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `server_id` varchar(20) DEFAULT NULL,
  `game_id` varchar(20) DEFAULT NULL,
  `account` varchar(50) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `is_recent` bit(1) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logout_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_first` bit(1) DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10717512 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_logins`
--

DROP TABLE IF EXISTS `log_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `account` varchar(128) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `imei` varchar(50) DEFAULT NULL,
  `android_id` varchar(50) DEFAULT NULL,
  `is_recent` bit(1) DEFAULT b'0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notice_targets`
--

DROP TABLE IF EXISTS `notice_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notice_targets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_id` int(11) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `is_read` char(1) NOT NULL DEFAULT '0',
  `read_time` datetime DEFAULT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text,
  `url` varchar(255) DEFAULT NULL,
  `create_time` datetime NOT NULL,
  `is_active` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_users_statistics`
--

DROP TABLE IF EXISTS `online_users_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_users_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `game_id` varchar(20) NOT NULL,
  `count_0` int(20) DEFAULT '0',
  `count_1` int(20) DEFAULT '0',
  `count_2` int(20) DEFAULT '0',
  `count_3` int(20) DEFAULT '0',
  `count_4` int(20) DEFAULT '0',
  `count_5` int(20) DEFAULT '0',
  `count_6` int(20) DEFAULT '0',
  `count_7` int(20) DEFAULT '0',
  `count_8` int(20) DEFAULT '0',
  `count_9` int(20) DEFAULT '0',
  `count_10` int(20) DEFAULT '0',
  `count_11` int(20) DEFAULT '0',
  `count_12` int(20) DEFAULT '0',
  `count_13` int(20) DEFAULT '0',
  `count_14` int(20) DEFAULT '0',
  `count_15` int(20) DEFAULT '0',
  `count_16` int(20) DEFAULT '0',
  `count_17` int(20) DEFAULT '0',
  `count_18` int(20) DEFAULT '0',
  `count_19` int(20) DEFAULT '0',
  `count_20` int(20) DEFAULT '0',
  `count_21` int(20) DEFAULT '0',
  `count_22` int(20) DEFAULT '0',
  `count_23` int(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `picture_categories`
--

DROP TABLE IF EXISTS `picture_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `picture_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `game_id` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pictures`
--

DROP TABLE IF EXISTS `pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pictures` (
  `src` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` bit(1) NOT NULL DEFAULT b'1',
  `width` smallint(6) DEFAULT NULL,
  `height` smallint(6) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=213 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `question_assignees`
--

DROP TABLE IF EXISTS `question_assignees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_assignees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_assign_id` int(11) NOT NULL,
  `admin_uid` int(11) NOT NULL,
  `is_read` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `question_assigns`
--

DROP TABLE IF EXISTS `question_assigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_assigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `source` varchar(45) NOT NULL,
  `admin_uid` varchar(45) NOT NULL,
  `desc` text NOT NULL,
  `result` text,
  `status` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `question_replies`
--

DROP TABLE IF EXISTS `question_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `question_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL,
  `is_official` char(1) NOT NULL DEFAULT '0',
  `admin_uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(1) NOT NULL COMMENT '1:帳號問題\\n2:儲值問題\\n3:遊戲問題\\n4:BUG回報\\n5:玩家建議',
  `content` text NOT NULL,
  `server_id` int(11) NOT NULL,
  `character_name` varchar(45) DEFAULT NULL,
  `pic_path1` varchar(300) DEFAULT NULL,
  `pic_path2` varchar(300) DEFAULT NULL,
  `pic_path3` varchar(300) DEFAULT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` char(1) NOT NULL DEFAULT '1',
  `is_read` char(1) NOT NULL DEFAULT '0',
  `note` text,
  `admin_uid` int(11) DEFAULT NULL,
  `update_time` datetime NOT NULL,
  `phone` varchar(300) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  `allocate_admin_uid` int(11) DEFAULT NULL,
  `allocate_date` datetime DEFAULT NULL,
  `allocate_status` char(1) NOT NULL DEFAULT '0',
  `allocate_finish_date` datetime DEFAULT NULL,
  `allocate_result` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8809 DEFAULT CHARSET=utf8 COMMENT='客服提問';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servers`
--

DROP TABLE IF EXISTS `servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `game_id` varchar(20) NOT NULL,
  `server_id` varchar(20) NOT NULL,
  `logo_path` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `server_connection_key` varchar(64) DEFAULT NULL,
  `server_status` enum('public','maintenance','private','hide') DEFAULT NULL,
  `maintenance_msg` varchar(250) DEFAULT NULL,
  `exchange_rate` float DEFAULT NULL,
  `server_performance` varchar(255) DEFAULT NULL,
  `merge_address` varchar(45) DEFAULT NULL,
  `is_transaction_active` bit(1) DEFAULT b'0',
  `is_new_server` bit(1) DEFAULT b'0',
  `is_entry_server` bit(1) DEFAULT b'0',
  `is_test_server` bit(1) DEFAULT b'0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `server_id_UNIQUE` (`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics`
--

DROP TABLE IF EXISTS `statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `game_id` varchar(20) DEFAULT NULL,
  `login_count` int(11) DEFAULT NULL,
  `new_login_count` int(11) DEFAULT NULL,
  `new_character_count` int(11) DEFAULT NULL,
  `device_count` int(11) DEFAULT NULL,
  `one_retention_all_count` int(11) DEFAULT NULL,
  `one_retention_count` int(11) DEFAULT NULL,
  `three_retention_count` int(11) DEFAULT NULL,
  `seven_retention_count` int(11) DEFAULT NULL,
  `fourteen_retention_count` int(11) DEFAULT NULL,
  `thirty_retention_count` int(11) DEFAULT NULL,
  `deposit_user_count` int(11) DEFAULT NULL,
  `new_deposit_user_count` int(11) DEFAULT NULL,
  `consume_user_count` int(11) DEFAULT NULL,
  `new_consume_user_count` int(11) DEFAULT NULL,
  `currency_total` float DEFAULT NULL,
  `paid_currency_total` float DEFAULT NULL,
  `deposit_total` float DEFAULT NULL,
  `consume_total` float DEFAULT NULL,
  `peak_user_count` int(11) DEFAULT NULL,
  `total_time` int(11) DEFAULT NULL,
  `paid_total_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `testaccounts`
--

DROP TABLE IF EXISTS `testaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testaccounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `account` varchar(100) NOT NULL,
  `note` varchar(100) DEFAULT NULL COMMENT '註解',
  `creator` varchar(20) NOT NULL DEFAULT '',
  `create_time` int(11) DEFAULT NULL,
  `update` varchar(20) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_billing`
--

DROP TABLE IF EXISTS `user_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `transaction_type` varchar(20) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `billing_type` tinyint(3) DEFAULT NULL,
  `amount` int(6) DEFAULT NULL,
  `result` tinyint(3) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `note` text,
  `server_id` varchar(20) DEFAULT NULL,
  `plug` tinyint(1) DEFAULT NULL,
  `order_no` varchar(50) DEFAULT NULL,
  `is_confirmed` bit(1) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=568407 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_info`
--

DROP TABLE IF EXISTS `user_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `birthday` date DEFAULT NULL,
  `sex` tinyint(1) DEFAULT NULL,
  `nation` varchar(10) DEFAULT NULL,
  `ident` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `street` varchar(250) DEFAULT NULL,
  `ban_reason` mediumtext,
  `ban_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_UNIQUE` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `bind_uid` int(11) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` char(32) NOT NULL,
  `activation_code` varchar(20) DEFAULT NULL,
  `balance` int(6) DEFAULT NULL,
  `is_approved` bit(1) DEFAULT NULL,
  `is_banned` bit(1) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`)
) ENGINE=MyISAM AUTO_INCREMENT=2454664 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
