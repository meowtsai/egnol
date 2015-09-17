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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `characters`
--

DROP TABLE IF EXISTS `characters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `server_id` varchar(20) NOT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `create_status` char(1) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
  `is_active` tinyint(1) DEFAULT '0',
  `fanpage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`game_id`),
  UNIQUE KEY `game_id_UNIQUE` (`game_id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gash_billing`
--

DROP TABLE IF EXISTS `gash_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gash_billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `COID` varchar(20) NOT NULL,
  `CUID` varchar(3) NOT NULL,
  `PAID` varchar(20) NOT NULL,
  `MSG_TYPE` varchar(4) NOT NULL,
  `PCODE` varchar(6) NOT NULL,
  `AMOUNT` float NOT NULL,
  `ERQC` varchar(30) DEFAULT NULL,
  `ERPC` varchar(30) DEFAULT NULL,
  `ERP_ID` varchar(20) DEFAULT NULL,
  `RRN` varchar(20) DEFAULT NULL,
  `PAY_STATUS` char(1) DEFAULT NULL,
  `RCODE` varchar(4) DEFAULT NULL,
  `PAY_RCODE` varchar(4) DEFAULT NULL,
  `TXTIME` varchar(14) DEFAULT NULL,
  `USER_IP` varchar(20) DEFAULT NULL,
  `status` char(1) DEFAULT '0',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `server_id` varchar(20) DEFAULT NULL,
  `country` varchar(45) DEFAULT 'global',
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='記錄後台動作';
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
  `ip` varchar(50) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
  `ip` varchar(50) DEFAULT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `is_recent` tinyint(1) DEFAULT 0,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logout_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_first` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
  `ip` varchar(50) DEFAULT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `imei` varchar(50) DEFAULT NULL,
  `android_id` varchar(50) DEFAULT NULL,
  `is_recent` tinyint(1) DEFAULT 0,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monthly_statistics`
--

DROP TABLE IF EXISTS `monthly_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monthly_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `one_retention_count` varchar(11) DEFAULT NULL,
  `one_retention_all_count` varchar(11) DEFAULT NULL,
  `return_count` int(11) DEFAULT NULL,
  `login_count` int(11) DEFAULT NULL,
  `new_login_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `width` smallint(6) DEFAULT NULL,
  `height` smallint(6) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
  `server_id` varchar(20) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='客服提問';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servers`
--

DROP TABLE IF EXISTS `servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servers` (
  `server_id` varchar(20) NOT NULL,
  `game_id` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `server_connection_key` varchar(64) DEFAULT NULL,
  `server_status` enum('public','maintenance','private','hide') DEFAULT NULL,
  `maintenance_msg` varchar(250) DEFAULT NULL,
  `exchange_rate` float DEFAULT NULL,
  `server_performance` varchar(255) DEFAULT NULL,
  `merge_address` varchar(45) DEFAULT NULL,
  `is_transaction_active` tinyint(1) DEFAULT 0,
  `is_new_server` tinyint(1) DEFAULT 0,
  `is_entry_server` tinyint(1) DEFAULT 0,
  `is_test_server` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
  `deposit_login_count` int(11) DEFAULT NULL,
  `new_deposit_login_count` int(11) DEFAULT NULL,
  `new_login_count_15` int(11) DEFAULT NULL,
  `new_login_count_30` int(11) DEFAULT NULL,
  `new_login_count_60` int(11) DEFAULT NULL,
  `new_login_count_90` int(11) DEFAULT NULL,
  `new_login_count_120` int(11) DEFAULT NULL,
  `new_login_count_more` int(11) DEFAULT NULL,
  `login_count_15` int(11) DEFAULT NULL,
  `login_count_30` int(11) DEFAULT NULL,
  `login_count_60` int(11) DEFAULT NULL,
  `login_count_90` int(11) DEFAULT NULL,
  `login_count_120` int(11) DEFAULT NULL,
  `login_count_more` int(11) DEFAULT NULL,
  `deposit_login_count_15` int(11) DEFAULT NULL,
  `deposit_login_count_30` int(11) DEFAULT NULL,
  `deposit_login_count_60` int(11) DEFAULT NULL,
  `deposit_login_count_90` int(11) DEFAULT NULL,
  `deposit_login_count_120` int(11) DEFAULT NULL,
  `deposit_login_count_more` int(11) DEFAULT NULL,
  `new_deposit_login_count_15` int(11) DEFAULT NULL,
  `new_deposit_login_count_30` int(11) DEFAULT NULL,
  `new_deposit_login_count_60` int(11) DEFAULT NULL,
  `new_deposit_login_count_90` int(11) DEFAULT NULL,
  `new_deposit_login_count_120` int(11) DEFAULT NULL,
  `new_deposit_login_count_more` int(11) DEFAULT NULL,
  `one_return_count` int(11) DEFAULT NULL,
  `three_return_count` int(11) DEFAULT NULL,
  `one_ltv` int(11) DEFAULT NULL,
  `seven_ltv` int(11) DEFAULT NULL,
  `fourteen_ltv` int(11) DEFAULT NULL,
  `thirty_ltv` int(11) DEFAULT NULL,
  `sixty_ltv` int(11) DEFAULT NULL,
  `ninety_ltv` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1760 DEFAULT CHARSET=utf8;
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
  `note` varchar(100) DEFAULT NULL COMMENT '註解',
  `creator` varchar(20) NOT NULL DEFAULT '',
  `create_time` int(11) DEFAULT NULL,
  `update` varchar(20) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
  `country_code` varchar(3) DEFAULT NULL,
  `gash_billing_id` int(11) DEFAULT NULL,
  `character_id` int(11) DEFAULT NULL,
  `is_confirmed` tinyint(1) DEFAULT 0,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
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
  `name` varchar(50) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `bind_uid` int(11) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` char(32) NOT NULL,
  `external_id` varchar(128) DEFAULT NULL,
  `activation_code` varchar(20) DEFAULT NULL,
  `balance` int(6) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `is_banned` tinyint(1) DEFAULT 0,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `external_id_UNIQUE` (`external_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `weekly_statistics`
--

DROP TABLE IF EXISTS `weekly_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weekly_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `one_retention_count` varchar(11) DEFAULT NULL,
  `one_retention_all_count` varchar(11) DEFAULT NULL,
  `return_count` int(11) DEFAULT NULL,
  `login_count` int(11) DEFAULT NULL,
  `new_login_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=367 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '活動編號',
  `game_id` varchar(20) DEFAULT NULL COMMENT '對應遊戲',
  `event_name` varchar(20) NOT NULL COMMENT '活動名稱',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '活動類型',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '活動狀態',
  `begin_time` datetime DEFAULT NULL COMMENT '活動開始時間',
  `end_time` datetime DEFAULT NULL COMMENT '活動結束時間',
  `fulfill_time` datetime DEFAULT NULL COMMENT '兌換期限(若有兌換獎勵)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `event_serial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `serial` varchar(20) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_UNIQUE` (`serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
