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
  `password` varchar(50) NULL,
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
  `theme_id` int(11) NULL,
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
-- Table structure for table `mycard_billing`
--

DROP TABLE IF EXISTS `mycard_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mycard_billing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `auth_code` varchar(300) DEFAULT NULL,
  `trade_code` varchar(255) DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `trade_type` tinyint(1) NOT NULL DEFAULT '0',
  `mycard_type` tinyint(1) DEFAULT NULL,
  `mycard_card_id` varchar(255) DEFAULT NULL,
  `mycard_pwd` varchar(255) DEFAULT NULL,
  `game_id` tinyint(3) DEFAULT NULL,
  `payment_id` varchar(30) DEFAULT NULL,
  `trade_seq` varchar(20) NOT NULL,
  `mycard_trade_seq` varchar(255) DEFAULT NULL,
  `fac_trade_seq` varchar(20) DEFAULT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `promo_code` varchar(20) DEFAULT NULL,
  `status` tinyint(3) NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT '0',
  `currency` varchar(4) DEFAULT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `cash_out` tinyint(1) NOT NULL DEFAULT '0',
  `cash_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_time` datetime NOT NULL,
  `note` tinytext,
  `plug` tinyint(1) NOT NULL DEFAULT '1',
  `amount` int(10) NOT NULL DEFAULT '0',
  `server_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mycard_trade_seq` (`mycard_trade_seq`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `funapp_billing`
--

DROP TABLE IF EXISTS `funapp_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funapp_billing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `trans_no` varchar(30) DEFAULT NULL,
  `iap_id` varchar(20) DEFAULT NULL,
  `status` tinyint(3) NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT '0',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_time` datetime NOT NULL,
  `note` tinytext,
  `amount` int(10) NOT NULL DEFAULT '0',
  `server_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trans_no` (`trans_no`),
  KEY `uid` (`uid`)
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
  `device_id` varchar(50) DEFAULT NULL,
  `token` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_server_first_logins`
--

DROP TABLE IF EXISTS `user_server_first_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_server_first_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `server_id` varchar(20) DEFAULT NULL,
  `game_id` varchar(20) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `user_statistics`
--

DROP TABLE IF EXISTS `user_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `game_id` varchar(20) DEFAULT NULL,
  `login_count` int(11) DEFAULT NULL,
  `apk_login_count` int(11) DEFAULT NULL,
  `ios_login_count` int(11) DEFAULT NULL,
  `google_login_count` int(11) DEFAULT NULL,
  `new_login_count` int(11) DEFAULT NULL,
  `new_login_facebook_count` int(11) DEFAULT NULL,
  `new_login_google_count` int(11) DEFAULT NULL,
  `new_login_longe_count` int(11) DEFAULT NULL,
  `new_login_quick_count` int(11) DEFAULT NULL,
  `new_character_count` int(11) DEFAULT NULL,
  `new_character_facebook_count` int(11) DEFAULT NULL,
  `new_character_google_count` int(11) DEFAULT NULL,
  `new_character_longe_count` int(11) DEFAULT NULL,
  `new_character_quick_count` int(11) DEFAULT NULL,
  `device_count` int(11) DEFAULT NULL,
  `new_device_count` int(11) DEFAULT NULL,
  `new_device_facebook_count` int(11) DEFAULT NULL,
  `new_device_google_count` int(11) DEFAULT NULL,
  `new_device_longe_count` int(11) DEFAULT NULL,
  `new_device_quick_count` int(11) DEFAULT NULL,
  `deposit_user_count` int(11) DEFAULT NULL,
  `new_deposit_user_count` int(11) DEFAULT NULL,
  `consume_user_count` int(11) DEFAULT NULL,
  `new_consume_user_count` int(11) DEFAULT NULL,
  `currency_total` float DEFAULT NULL,
  `paid_currency_total` float DEFAULT NULL,
  `consume_total` float DEFAULT NULL,
  `peak_user_count` int(11) DEFAULT NULL,
  `total_time` int(11) DEFAULT NULL,
  `paid_total_time` int(11) DEFAULT NULL,
  `deposit_login_count` int(11) DEFAULT NULL,
  `new_deposit_login_count` int(11) DEFAULT NULL,
  `new_user_deposit_count` int(11) DEFAULT NULL,
  `deposit_total` float DEFAULT NULL,
  `new_user_deposit_total` int(11) DEFAULT NULL,
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
  `ios_download_count` int(8) DEFAULT NULL,
  `ios_tw_download_count` int(8) DEFAULT NULL,
  `ios_hk_download_count` int(8) DEFAULT NULL,
  `ios_mo_download_count` int(8) DEFAULT NULL,
  `ios_sg_download_count` int(8) DEFAULT NULL,
  `ios_my_download_count` int(8) DEFAULT NULL,
  `google_download_count` int(8) DEFAULT NULL,
  `google_tw_download_count` int(8) DEFAULT NULL,
  `google_hk_download_count` int(8) DEFAULT NULL,
  `google_mo_download_count` int(8) DEFAULT NULL,
  `google_sg_download_count` int(8) DEFAULT NULL,
  `google_my_download_count` int(8) DEFAULT NULL,
  `apk_download_count` int(8) DEFAULT NULL,
  `apk_tw_download_count` int(8) DEFAULT NULL,
  `apk_hk_download_count` int(8) DEFAULT NULL,
  `apk_mo_download_count` int(8) DEFAULT NULL,
  `apk_sg_download_count` int(8) DEFAULT NULL,
  `apk_my_download_count` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `weekly_user_statistics`
--

DROP TABLE IF EXISTS `weekly_user_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weekly_user_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `login_count` int(11) DEFAULT NULL,
  `login_facebook_count` int(11) DEFAULT NULL,
  `login_google_count` int(11) DEFAULT NULL,
  `login_longe_count` int(11) DEFAULT NULL,
  `login_quick_count` int(11) DEFAULT NULL,
  `apk_login_count` int(11) DEFAULT NULL,
  `ios_login_count` int(11) DEFAULT NULL,
  `google_login_count` int(11) DEFAULT NULL,
  `new_login_count` int(11) DEFAULT NULL,
  `new_login_facebook_count` int(11) DEFAULT NULL,
  `new_login_google_count` int(11) DEFAULT NULL,
  `new_login_longe_count` int(11) DEFAULT NULL,
  `new_login_quick_count` int(11) DEFAULT NULL,
  `device_count` int(11) DEFAULT NULL,
  `deposit_user_count` int(11) DEFAULT NULL,
  `new_deposit_user_count` int(11) DEFAULT NULL,
  `new_user_deposit_count` int(11) DEFAULT NULL,
  `deposit_total` float DEFAULT NULL,
  `new_user_deposit_total` int(11) DEFAULT NULL,
  `ios_download_count` int(8) DEFAULT NULL,
  `ios_tw_download_count` int(8) DEFAULT NULL,
  `ios_hk_download_count` int(8) DEFAULT NULL,
  `ios_mo_download_count` int(8) DEFAULT NULL,
  `ios_sg_download_count` int(8) DEFAULT NULL,
  `ios_my_download_count` int(8) DEFAULT NULL,
  `google_download_count` int(8) DEFAULT NULL,
  `google_tw_download_count` int(8) DEFAULT NULL,
  `google_hk_download_count` int(8) DEFAULT NULL,
  `google_mo_download_count` int(8) DEFAULT NULL,
  `google_sg_download_count` int(8) DEFAULT NULL,
  `google_my_download_count` int(8) DEFAULT NULL,
  `apk_download_count` int(8) DEFAULT NULL,
  `apk_tw_download_count` int(8) DEFAULT NULL,
  `apk_hk_download_count` int(8) DEFAULT NULL,
  `apk_mo_download_count` int(8) DEFAULT NULL,
  `apk_sg_download_count` int(8) DEFAULT NULL,
  `apk_my_download_count` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

--
-- Table structure for table `monthly_user_statistics`
--

DROP TABLE IF EXISTS `monthly_user_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monthly_user_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `login_count` int(11) DEFAULT NULL,
  `login_facebook_count` int(11) DEFAULT NULL,
  `login_google_count` int(11) DEFAULT NULL,
  `login_longe_count` int(11) DEFAULT NULL,
  `login_quick_count` int(11) DEFAULT NULL,
  `apk_login_count` int(11) DEFAULT NULL,
  `ios_login_count` int(11) DEFAULT NULL,
  `google_login_count` int(11) DEFAULT NULL,
  `new_login_count` int(11) DEFAULT NULL,
  `new_login_facebook_count` int(11) DEFAULT NULL,
  `new_login_google_count` int(11) DEFAULT NULL,
  `new_login_longe_count` int(11) DEFAULT NULL,
  `new_login_quick_count` int(11) DEFAULT NULL,
  `ios_download_count` int(9) DEFAULT NULL,
  `device_count` int(11) DEFAULT NULL,
  `deposit_user_count` int(11) DEFAULT NULL,
  `new_deposit_user_count` int(11) DEFAULT NULL,
  `new_user_deposit_count` int(11) DEFAULT NULL,
  `deposit_total` float DEFAULT NULL,
  `new_user_deposit_total` int(11) DEFAULT NULL,
  `ios_tw_download_count` int(9) DEFAULT NULL,
  `ios_hk_download_count` int(9) DEFAULT NULL,
  `ios_mo_download_count` int(9) DEFAULT NULL,
  `ios_sg_download_count` int(9) DEFAULT NULL,
  `ios_my_download_count` int(9) DEFAULT NULL,
  `google_download_count` int(9) DEFAULT NULL,
  `google_tw_download_count` int(9) DEFAULT NULL,
  `google_hk_download_count` int(9) DEFAULT NULL,
  `google_mo_download_count` int(9) DEFAULT NULL,
  `google_sg_download_count` int(9) DEFAULT NULL,
  `google_my_download_count` int(9) DEFAULT NULL,
  `apk_download_count` int(9) DEFAULT NULL,
  `apk_tw_download_count` int(9) DEFAULT NULL,
  `apk_hk_download_count` int(9) DEFAULT NULL,
  `apk_mo_download_count` int(9) DEFAULT NULL,
  `apk_sg_download_count` int(9) DEFAULT NULL,
  `apk_my_download_count` int(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `operation_statistics`
--

DROP TABLE IF EXISTS `operation_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operation_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `game_id` varchar(20) DEFAULT NULL,
  `one_retention_all_count` int(11) DEFAULT NULL,
  `one_retention_count` int(11) DEFAULT NULL,
  `one_retention_facebook_count` int(11) DEFAULT NULL,
  `one_retention_google_count` int(11) DEFAULT NULL,
  `one_retention_longe_count` int(11) DEFAULT NULL,
  `one_retention_quick_count` int(11) DEFAULT NULL,
  `three_retention_count` int(11) DEFAULT NULL,
  `three_retention_facebook_count` int(11) DEFAULT NULL,
  `three_retention_google_count` int(11) DEFAULT NULL,
  `three_retention_longe_count` int(11) DEFAULT NULL,
  `three_retention_quick_count` int(11) DEFAULT NULL,
  `seven_retention_count` int(11) DEFAULT NULL,
  `seven_retention_facebook_count` int(11) DEFAULT NULL,
  `seven_retention_google_count` int(11) DEFAULT NULL,
  `seven_retention_longe_count` int(11) DEFAULT NULL,
  `seven_retention_quick_count` int(11) DEFAULT NULL,
  `fourteen_retention_count` int(11) DEFAULT NULL,
  `fourteen_retention_facebook_count` int(11) DEFAULT NULL,
  `fourteen_retention_google_count` int(11) DEFAULT NULL,
  `fourteen_retention_longe_count` int(11) DEFAULT NULL,
  `fourteen_retention_quick_count` int(11) DEFAULT NULL,
  `thirty_retention_count` int(11) DEFAULT NULL,
  `thirty_retention_facebook_count` int(11) DEFAULT NULL,
  `thirty_retention_google_count` int(11) DEFAULT NULL,
  `thirty_retention_longe_count` int(11) DEFAULT NULL,
  `thirty_retention_quick_count` int(11) DEFAULT NULL,
  `one_return_count` int(11) DEFAULT NULL,
  `one_return_facebook_count` int(11) DEFAULT NULL,
  `one_return_google_count` int(11) DEFAULT NULL,
  `one_return_longe_count` int(11) DEFAULT NULL,
  `one_return_quick_count` int(11) DEFAULT NULL,
  `three_return_count` int(11) DEFAULT NULL,
  `three_return_facebook_count` int(11) DEFAULT NULL,
  `three_return_google_count` int(11) DEFAULT NULL,
  `three_return_longe_count` int(11) DEFAULT NULL,
  `three_return_quick_count` int(11) DEFAULT NULL,
  `one_return_rate` float(7,4) DEFAULT NULL,
  `one_return_facebook_rate` float(7,4) DEFAULT NULL,
  `one_return_google_rate` float(7,4) DEFAULT NULL,
  `one_return_longe_rate` float(7,4) DEFAULT NULL,
  `one_return_quick_rate` float(7,4) DEFAULT NULL,
  `three_return_rate` float(7,4) DEFAULT NULL,
  `three_return_facebook_rate` float(7,4) DEFAULT NULL,
  `three_return_google_rate` float(7,4) DEFAULT NULL,
  `three_return_longe_rate` float(7,4) DEFAULT NULL,
  `three_return_quick_rate` float(7,4) DEFAULT NULL,
  `one_ltv` int(11) DEFAULT NULL,
  `two_ltv` int(11) DEFAULT NULL,
  `three_ltv` int(11) DEFAULT NULL,
  `four_ltv` int(11) DEFAULT NULL,
  `five_ltv` int(11) DEFAULT NULL,
  `six_ltv` int(11) DEFAULT NULL,
  `seven_ltv` int(11) DEFAULT NULL,
  `fourteen_ltv` int(11) DEFAULT NULL,
  `thirty_ltv` int(11) DEFAULT NULL,
  `sixty_ltv` int(11) DEFAULT NULL,
  `ninety_ltv` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `weekly_operation_statistics`
--

DROP TABLE IF EXISTS `weekly_operation_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weekly_operation_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `one_retention_count` varchar(11) DEFAULT NULL,
  `one_retention_all_count` varchar(11) DEFAULT NULL,
  `return_count` int(11) DEFAULT NULL,
  `return_facebook_count` int(11) DEFAULT NULL,
  `return_google_count` int(11) DEFAULT NULL,
  `return_longe_count` int(11) DEFAULT NULL,
  `return_quick_count` int(11) DEFAULT NULL,
  `return_rate` float(7,4) DEFAULT NULL,
  `return_facebook_rate` float(7,4) DEFAULT NULL,
  `return_google_rate` float(7,4) DEFAULT NULL,
  `return_longe_rate` float(7,4) DEFAULT NULL,
  `return_quick_rate` float(7,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

--
-- Table structure for table `monthly_operation_statistics`
--

DROP TABLE IF EXISTS `monthly_operation_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monthly_operation_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `one_retention_count` varchar(11) DEFAULT NULL,
  `one_retention_all_count` varchar(11) DEFAULT NULL,
  `return_count` int(11) DEFAULT NULL,
  `return_facebook_count` int(11) DEFAULT NULL,
  `return_google_count` int(11) DEFAULT NULL,
  `return_longe_count` int(11) DEFAULT NULL,
  `return_quick_count` int(11) DEFAULT NULL,
  `return_rate` float(7,4) DEFAULT NULL,
  `return_facebook_rate` float(7,4) DEFAULT NULL,
  `return_google_rate` float(7,4) DEFAULT NULL,
  `return_longe_rate` float(7,4) DEFAULT NULL,
  `return_quick_rate` float(7,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `marketing_statistics`
--

DROP TABLE IF EXISTS `marketing_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marketing_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `game_id` varchar(20) NOT NULL,
  `platform` varchar(9) NOT NULL,
  `media` varchar(20) NOT NULL,
  `country_code` varchar(3) DEFAULT NULL,
  `click_count` int(8) DEFAULT NULL,
  `install_count` int(8) DEFAULT NULL,
  `pay_user_count` int(8) DEFAULT NULL,
  `pay_unique_event_count` int(8) DEFAULT NULL,
  `pay_event_count` int(8) DEFAULT NULL,
  `pay_amount` float(7,2) DEFAULT NULL,
  `af_login` int(8) DEFAULT NULL,
  `af_login_unique` int(8) DEFAULT NULL,
  `af_login_sales` float(7,2) DEFAULT NULL,
  `le_usercharactercreate` int(8) DEFAULT NULL,
  `le_usercharactercreate_unique` int(8) DEFAULT NULL,
  `le_usercharactercreate_sales` float(7,2) DEFAULT NULL,
  `le_usercharacterlevelup` int(8) DEFAULT NULL,
  `le_usercharacterlevelup_unique` int(8) DEFAULT NULL,
  `le_usercharacterlevelup_sales` float(7,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `priority` tinyint(2) NOT NULL DEFAULT '0',
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
  `close_admin_uid` int(11) DEFAULT NULL,
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
ALTER table questions
ADD system_closed char(1) NOT NULL DEFAULT 0;

ALTER table questions
ADD system_closed_start datetime NULL;

--
-- Table structure for table `ticket_replies`
--

DROP TABLE IF EXISTS `ticket_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL,
  `admin_uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(1) NOT NULL,
  `urgency` char(1) NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `game_id` varchar(20) DEFAULT NULL,
  `file_path1` varchar(300) DEFAULT NULL,
  `file_path2` varchar(300) DEFAULT NULL,
  `file_path3` varchar(300) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` char(1) NOT NULL DEFAULT '1',
  `is_read` char(1) NOT NULL DEFAULT '0',
  `admin_uid` int(11) DEFAULT NULL,
  `update_time` datetime NOT NULL,
  `allocate_admin_uid` int(11) DEFAULT NULL,
  `allocate_date` datetime DEFAULT NULL,
  `allocate_result` text,
  `cc_admin_uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='工作申請&回報單';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vip_tickets`
--

DROP TABLE IF EXISTS `vip_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vip_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vip_event_id` int(11) NOT NULL,
  `cost` int(8) NOT NULL,
  `uid` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` char(1) NOT NULL DEFAULT '1',
  `server_id` varchar(20) NOT NULL,
  `character_id` int(11) DEFAULT NULL,
  `admin_uid` int(11) DEFAULT NULL,
  `update_time` timestamp NOT NULL,
  `billing_time` timestamp NULL DEFAULT NULL,
  `deliver_time` timestamp NULL DEFAULT NULL,
  `billing_account` int(5) DEFAULT NULL,
  `billing_name` varchar(20) DEFAULT NULL,
  `auth_admin_uid` int(11) DEFAULT NULL,
  `auth_time` timestamp NULL DEFAULT NULL,
  `product_id` varchar(60) DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='VIP訂單';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vip_events`
--

DROP TABLE IF EXISTS `vip_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vip_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(1) NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `cost` int(8) NOT NULL,
  `game_id` varchar(20) DEFAULT NULL,
  `file_path1` varchar(300) DEFAULT NULL,
  `file_path2` varchar(300) DEFAULT NULL,
  `file_path3` varchar(300) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` char(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) DEFAULT 0,
  `admin_uid` int(11) DEFAULT NULL,
  `update_time` timestamp NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `auth_admin_uid` int(11) DEFAULT NULL,
  `auth_time` timestamp NULL DEFAULT NULL,
  `product_id` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='VIP活動';
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
  `transaction_id` varchar(50) DEFAULT NULL,
  `partner_order_id` varchar(100) DEFAULT NULL,
  `billing_type` tinyint(3) DEFAULT NULL,
  `amount` int(6) DEFAULT NULL,
  `result` tinyint(3) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `note` text,
  `server_id` varchar(20) DEFAULT NULL,
  `plug` tinyint(1) DEFAULT NULL,
  `order_no` varchar(50) DEFAULT NULL,
  `product_id` varchar(30) DEFAULT NULL,
  `verify_code` varchar(50) DEFAULT NULL,
  `country_code` varchar(3) DEFAULT NULL,
  `gash_billing_id` int(11) DEFAULT NULL,
  `mycard_billing_id` int(11) DEFAULT NULL,
  `funapp_billing_id` int(11) DEFAULT NULL,
  `vip_ticket_id` int(11) DEFAULT NULL,
  `character_id` int(11) DEFAULT NULL,
  `is_confirmed` tinyint(1) DEFAULT 0,
  `question_id` int(11) DEFAULT NULL,
  `transfer_result` tinyint(3) DEFAULT NULL,
  `transfer_message` varchar(50) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  `transfer_time` timestamp NULL DEFAULT NULL,
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
  `line` varchar(50) DEFAULT NULL,
  `note` text,
  `ban_reason` mediumtext,
  `ban_date` timestamp NULL DEFAULT NULL,
  `is_android_device` tinyint(1) DEFAULT NULL,
  `is_ios_device` tinyint(1) DEFAULT NULL,
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
-- Table structure for table `log_user_updates`
--
CREATE TABLE `log_user_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '活動編號',
  `game_id` varchar(20) DEFAULT NULL COMMENT '對應遊戲',
  `event_name` varchar(20) NOT NULL COMMENT '活動名稱',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '活動類型',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '活動狀態',
  `priority` tinyint(4) NOT NULL DEFAULT '0' COMMENT '顯示優先次序',
  `begin_time` datetime DEFAULT NULL COMMENT '活動開始時間',
  `end_time` datetime DEFAULT NULL COMMENT '活動結束時間',
  `fulfill_time` datetime DEFAULT NULL COMMENT '兌換期限(若有兌換獎勵)',
  `url` varchar(255) DEFAULT NULL COMMENT '活動官網或說明頁',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/* type=0, 序號發放活動 */
/* status=0, 關閉 */
/* status=1, 開啟 */

CREATE TABLE `event_serial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `serial` varchar(20) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `personal_id` varchar(128) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_UNIQUE` (`serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/* personal_id 欄位為使用在發獎勵給非會員時用來辨認使用者身分 */;


CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號',
  `game_id` varchar(20) DEFAULT NULL COMMENT '對應遊戲',
  `title` varchar(20) NOT NULL COMMENT '標題',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '類型',
  `content` varchar(1000) NOT NULL COMMENT '公告內容',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `link` varchar(255) DEFAULT NULL COMMENT '連結',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `bulletins`
--
CREATE TABLE `bulletins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) DEFAULT NULL,
  `game_id` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `admin_uid` int(11) DEFAULT NULL,
  `priority` tinyint(2) NOT NULL DEFAULT '0',
  `target` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `promotion_codes`
--
DROP TABLE IF EXISTS `promotion_codes`;
CREATE TABLE `promotion_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `code` varchar(64) NOT NULL,
  `title` varchar(64) DEFAULT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `server_id` varchar(20) DEFAULT NULL,
  `character_id` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`game_id`, `code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Table structure for table `iap_receipt`
--
CREATE TABLE `iap_receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_billing_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `receipt` text NOT NULL,
  PRIMARY KEY (`id`),
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `whale_users`;
CREATE TABLE `whale_users` (
`uid` int(11) NOT NULL ,
`site` varchar(50) ,
`char_name` varchar(50),
`char_in_game_id` varchar(50),
`server_name` varchar(255),
`deposit_total`  int(10),
`account_create_time` timestamp  NULL DEFAULT NULL,
`last_login` Datetime,
`is_added` tinyint(1) DEFAULT '0',
`create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`uid`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ;


DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `input_bgcolor` varchar(6) NOT NULL,
  `btn_bgcolor` varchar(6) NOT NULL,
  `btn_fgcolor` varchar(6) NOT NULL,
  `btn_bordercolor` varchar(6) NOT NULL,
  `body_bgcolor` varchar(6) NOT NULL,
  `body_fgcolor` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


INSERT INTO `themes` VALUES (1,'茶色','E7DAB8','784C25','E8C899','92714B','FEF7DE','5C5C5C'),(2,'綠色','9BD770','375F1B','66B032','1B3409','EBF7E3','1B3409'),(3,'藍色','678FFE','012998','DBE5FF','091534','DBE5FF','091534'),(4,'桃色','EC6988','7B0F28','FBDFE6','340913','FBDFE6','340913'),(5,'黃色','FEF590','C0B002','FFFCDC','343009','FFFCDC','343009'),(6,'紅色','FE8176','A70F01','FFDEDB','340D09','FFDEDB','340D09'),(7,'橙色','FE9F6D','9D3802','FFE8DC','341809','FFE8DC','341809'),(8,'萊姆','F1F791','A5B00C','FBFDDE','313409','FBFDDE','313409'),(9,'墨綠','79BEA8','23483C','E7F3EF','093426','E7F3EF','093426'),(10,'藍綠','67AFCB','1A3E4C','E4F1F6','092834','E4F1F6','092834'),(11,'酒紅','F37C84','950E17','FCDEE0','34090C','FCDEE0','34090C'),(12,'紫色','A33AF2','36065B','EFDDFD','210934','EFDDFD','210934');



CREATE TABLE `complaints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `server_id` varchar(20) NOT NULL,
  `reporter_uid` int(11) NOT NULL,
  `reporter_char_id` int(11) NOT NULL,
  `reporter_name` varchar(45) NOT NULL,
  `flagged_player_uid` int(11) NOT NULL,
  `flagged_player_char_id` int(11) NOT NULL,
  `flagged_player_name` varchar(45) NOT NULL,
  `category` char(1) NOT NULL COMMENT '1:言行不雅\\n2:暱稱不雅\\n3:使用外掛\\n4:利用bug\\n5:線下交易\\n6:欺詐行為\\n7:其他',
  `reason` varchar(300) NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` char(1) NOT NULL DEFAULT '1',
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='玩家申訴';

ALTER TABLE complaints DROP COLUMN reporter_uid;
ALTER TABLE complaints DROP COLUMN flagged_player_uid;


CREATE TABLE `log_gm_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_uid` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `action` varchar(45) DEFAULT NULL,
  `desc` text,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='記錄GMTools動作' ;


CREATE VIEW billing_data as
SELECT
    g.name,
    g.game_id,
    SUM(u.amount) 'total',
    SUM(CASE WHEN u.transaction_type='mycard_billing' THEN u.amount ELSE NULL END) 'mycard_total',
    SUM(CASE WHEN u.transaction_type='funapp_billing' THEN u.amount ELSE NULL END) 'funapp_total',
    SUM(CASE WHEN u.transaction_type='vip_billing' THEN u.amount ELSE NULL END) 'vip_total',
    SUM(CASE WHEN u.transaction_type='inapp_billing_ios' THEN u.amount ELSE NULL END) 'ios_total',
    SUM(CASE WHEN u.transaction_type='inapp_billing_google' THEN u.amount ELSE NULL END) 'google_total',
    SUM(CASE WHEN DATE(u.create_time)=CURDATE() THEN u.amount ELSE NULL END) 't_total',
    SUM(CASE WHEN DATE(u.create_time)=CURDATE() AND u.transaction_type='mycard_billing' THEN u.amount ELSE NULL END) 't_mycard_total',
    SUM(CASE WHEN DATE(u.create_time)=CURDATE() AND u.transaction_type='funapp_billing' THEN u.amount ELSE NULL END) 't_funapp_total',
    SUM(CASE WHEN DATE(u.create_time)=CURDATE() AND u.transaction_type='vip_billing' THEN u.amount ELSE NULL END) 't_vip_total',
    SUM(CASE WHEN DATE(u.create_time)=CURDATE() AND u.transaction_type='inapp_billing_ios' THEN u.amount ELSE NULL END) 't_ios_total',
    SUM(CASE WHEN DATE(u.create_time)=CURDATE() AND u.transaction_type='inapp_billing_google' THEN u.amount ELSE NULL END) 't_google_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN u.amount ELSE NULL END) 'y_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND u.transaction_type='mycard_billing' THEN u.amount ELSE NULL END) 'y_mycard_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND u.transaction_type='funapp_billing' THEN u.amount ELSE NULL END) 'y_funapp_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND u.transaction_type='vip_billing' THEN u.amount ELSE NULL END) 'y_vip_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND u.transaction_type='inapp_billing_ios' THEN u.amount ELSE NULL END) 'y_ios_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND u.transaction_type='inapp_billing_google' THEN u.amount ELSE NULL END) 'y_google_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) THEN u.amount ELSE NULL END) 'y2_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND u.transaction_type='mycard_billing' THEN u.amount ELSE NULL END) 'y2_mycard_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND u.transaction_type='funapp_billing' THEN u.amount ELSE NULL END) 'y2_funapp_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND u.transaction_type='vip_billing' THEN u.amount ELSE NULL END) 'y2_vip_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND u.transaction_type='inapp_billing_ios' THEN u.amount ELSE NULL END) 'y2_ios_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND u.transaction_type='inapp_billing_google' THEN u.amount ELSE NULL END) 'y2_google_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) THEN u.amount ELSE NULL END) 'y3_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND u.transaction_type='mycard_billing' THEN u.amount ELSE NULL END) 'y3_mycard_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND u.transaction_type='funapp_billing' THEN u.amount ELSE NULL END) 'y3_funapp_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND u.transaction_type='vip_billing' THEN u.amount ELSE NULL END) 'y3_vip_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND u.transaction_type='inapp_billing_ios' THEN u.amount ELSE NULL END) 'y3_ios_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND u.transaction_type='inapp_billing_google' THEN u.amount ELSE NULL END) 'y3_google_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) THEN u.amount ELSE NULL END) 'y4_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND u.transaction_type='mycard_billing' THEN u.amount ELSE NULL END) 'y4_mycard_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND u.transaction_type='funapp_billing' THEN u.amount ELSE NULL END) 'y4_funapp_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND u.transaction_type='vip_billing' THEN u.amount ELSE NULL END) 'y4_vip_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND u.transaction_type='inapp_billing_ios' THEN u.amount ELSE NULL END) 'y4_ios_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND u.transaction_type='inapp_billing_google' THEN u.amount ELSE NULL END) 'y4_google_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) THEN u.amount ELSE NULL END) 'y5_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND u.transaction_type='mycard_billing' THEN u.amount ELSE NULL END) 'y5_mycard_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND u.transaction_type='funapp_billing' THEN u.amount ELSE NULL END) 'y5_funapp_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND u.transaction_type='vip_billing' THEN u.amount ELSE NULL END) 'y5_vip_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND u.transaction_type='inapp_billing_ios' THEN u.amount ELSE NULL END) 'y5_ios_total',
    SUM(CASE WHEN DATE(u.create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND u.transaction_type='inapp_billing_google' THEN u.amount ELSE NULL END) 'y5_google_total',
    SUM(CASE WHEN DATE(u.create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) THEN u.amount ELSE NULL END) 'y6_total',
    SUM(CASE WHEN DATE(u.create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND u.transaction_type='mycard_billing' THEN u.amount ELSE NULL END) 'y6_mycard_total',
    SUM(CASE WHEN DATE(u.create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND u.transaction_type='funapp_billing' THEN u.amount ELSE NULL END) 'y6_funapp_total',
    SUM(CASE WHEN DATE(u.create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND u.transaction_type='vip_billing' THEN u.amount ELSE NULL END) 'y6_vip_total',
    SUM(CASE WHEN DATE(u.create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND u.transaction_type='inapp_billing_ios' THEN u.amount ELSE NULL END) 'y6_ios_total',
    SUM(CASE WHEN DATE(u.create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND u.transaction_type='inapp_billing_google' THEN u.amount ELSE NULL END) 'y6_google_total'
FROM user_billing u
JOIN servers s ON u.server_id=s.server_id
JOIN games g ON s.game_id=g.game_id
WHERE g.is_active='1' AND s.is_test_server=0 AND u.billing_type=1 AND u.result=1
AND u.uid NOT IN(SELECT uid FROM testaccounts)
GROUP BY g.game_id


CREATE VIEW account_data as
  SELECT
                COUNT(*) 'newuser_count',
                COUNT(CASE WHEN external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'newuser_facebook_count',
                COUNT(CASE WHEN external_id LIKE '%google' THEN 1 ELSE NULL END) 'newuser_google_count',
                COUNT(CASE WHEN external_id IS NULL THEN 1 ELSE NULL END) 'newuser_longe_count',
                COUNT(CASE WHEN external_id LIKE '%device' THEN 1 ELSE NULL END) 'newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() THEN 1 ELSE NULL END) 't_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 't_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() AND external_id LIKE '%google' THEN 1 ELSE NULL END) 't_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() AND external_id IS NULL THEN 1 ELSE NULL END) 't_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() AND external_id LIKE '%device' THEN 1 ELSE NULL END) 't_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1 ELSE NULL END) 'y_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) THEN 1 ELSE NULL END) 'y2_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y2_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y2_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y2_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y2_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) THEN 1 ELSE NULL END) 'y3_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y3_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y3_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y3_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y3_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) THEN 1 ELSE NULL END) 'y4_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y4_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y4_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y4_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y4_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) THEN 1 ELSE NULL END) 'y5_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y5_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y5_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y5_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y5_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) THEN 1 ELSE NULL END) 'y6_newuser_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y6_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y6_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y6_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y6_newuser_quick_count'
            FROM users


CREATE TABLE account_data_daily AS SELECT * FROM account_data;
CREATE TABLE billing_data_daily AS SELECT * FROM billing_data;



delimiter |
CREATE EVENT daily_report_event
    ON SCHEDULE
      EVERY 1 MINUTE
    COMMENT 'retrieve data from an existing view to load faster'
    DO
    BEGIN
    delete from account_data_daily;
    insert into account_data_daily select * from account_data;
    delete from billing_data_daily;
    insert into billing_data_daily select * from billing_data;
    END |
delimiter ;

ALTER EVENT daily_report_event
    ON SCHEDULE
      EVERY 12 HOUR
    STARTS CURRENT_TIMESTAMP + INTERVAL 4 HOUR;




    CREATE TABLE `h35vip_weekly_data` (
    `year` int DEFAULT 0,
     `week` int DEFAULT 0,
     `general` int DEFAULT 0,
     `silver` int DEFAULT 0,
     `gold` int DEFAULT 0,
     `platinum` int DEFAULT 0,
     `black` int DEFAULT 0,
     `accumulated_total` int DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8


    DELIMITER //
    CREATE PROCEDURE get_yearweek()
    BEGIN
      DECLARE done INT DEFAULT FALSE;
      DECLARE a CHAR(16);
      DECLARE c_yearweek,c_year, c_week INT;
      DECLARE cur1 CURSOR FOR
      SELECT YEARWEEK(create_time)
      FROM h35vip_orders
      GROUP BY YEARWEEK(create_time);
      DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

      OPEN cur1;

      read_loop: LOOP
        FETCH cur1 INTO c_yearweek;
        IF done THEN
          LEAVE read_loop;
        END IF;
          INSERT INTO h35vip_weekly_data
          SELECT LEFT(c_yearweek,4), RIGHT(c_yearweek,2), SUM(CASE WHEN a.VIP_RANK = '普R' THEN 1 ELSE 0 END) AS general,
          SUM(CASE WHEN a.VIP_RANK = '銀R' THEN 1 ELSE 0 END) AS silver,
          SUM(CASE WHEN a.VIP_RANK = '金R' THEN 1 ELSE 0 END) AS gold,
          SUM(CASE WHEN a.VIP_RANK = '白金R' THEN 1 ELSE 0 END) AS platinum,
          SUM(CASE WHEN a.VIP_RANK = '黑R' THEN 1 ELSE 0 END) AS black,
          SUM(SUM_Total) as accumulated_total
          from
          (select sum(amount) as SUM_Total,
          CASE
            WHEN sum(amount) <100000 THEN '普R'
            WHEN  sum(amount) >= 100000 AND sum(amount) <300000 THEN '銀R'
            WHEN  sum(amount) >= 300000 AND sum(amount) <600000 THEN '金R'
            WHEN  sum(amount) >= 600000 AND sum(amount) <1000000 THEN '白金R'
            ELSE '黑R' END as VIP_RANK
          from h35vip_orders
          where YEARWEEK(create_time) <= c_yearweek
          group by account
          having sum(amount)> 50000
          )  a
          ;
      END LOOP;

      CLOSE cur1;

    END;
    //
    DELIMITER ;
