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

ALTER TABLE games
ADD site varchar(255) DEFAULT NULL;
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
ALTER TABLE games
ADD bg_path varchar(255) DEFAULT NULL;

ALTER TABLE games
ADD slogan varchar(255) DEFAULT NULL;

ALTER TABLE games MODIFY COLUMN logo_path varchar(255) DEFAULT NULL;
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


DROP TABLE IF EXISTS `question_favorites`;
CREATE TABLE `question_favorites` (
  `question_id` int(11) NOT NULL,
  `admin_uid` int(11) NOT NULL,
  CONSTRAINT PK_qf PRIMARY KEY (question_id,admin_uid),
  FOREIGN KEY (question_id)
      REFERENCES questions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER table question_favorites
ADD category char(1) NOT NULL DEFAULT 1 COMMENT'1 - 我的珍藏\\2 - 批次回覆';

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


ALTER table questions
ADD is_in_game char(1) NOT NULL DEFAULT 0 COMMENT'0 - 玩家自填\\1 - 遊戲內帶出';

ALTER table questions
ADD last_replied char(1) NOT NULL DEFAULT 'N' COMMENT 'trigger更新 N:尚未回覆\\O:官方回覆\\U:玩家回覆';

ALTER table questions
ADD last_replied_time datetime NULL COMMENT 'trigger 更新最後回覆時間';

drop TRIGGER ins_reply;
delimiter //
CREATE TRIGGER ins_reply BEFORE INSERT ON question_replies
   FOR EACH ROW
   BEGIN
   UPDATE questions SET last_replied = (CASE WHEN NEW.is_official=1 THEN 'O' ELSE 'U' END),last_replied_time=NOW()  WHERE id = NEW.question_id;
   END;//
delimiter ;


drop TRIGGER del_reply;
delimiter //
CREATE TRIGGER del_reply AFTER DELETE ON question_replies
   FOR EACH ROW
   BEGIN
   DECLARE _is_official CHAR(1);
   DECLARE _last_replied_time datetime;
   SELECT is_official, create_time into _is_official, _last_replied_time from question_replies where question_id=OLD.question_id order by id desc limit 1;
   UPDATE questions SET last_replied = (CASE WHEN _is_official=1 THEN 'O' ELSE 'U' END),last_replied_time=_last_replied_time  WHERE id = OLD.question_id;

   END;//
delimiter ;



select q.id, c.name as in_game_name,q.character_name,q.is_in_game from (select id,partner_uid,server_id ,character_name,is_in_game from questions order by id desc limit 10) q left join characters c
on c.partner_uid=q.partner_uid and c.server_id=q.server_id and c.name=q.character_name;


UPDATE questions q
left join characters c
on c.partner_uid=q.partner_uid and c.server_id=q.server_id and c.name=q.character_name
SET q.is_in_game = (CASE WHEN c.name is NULL THEN 0 ELSE 1 END)
WHERE q.id between 42000 and 43000






DROP TABLE IF EXISTS `question_pictures`;
CREATE TABLE `question_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL DEFAULT 0,
  `pic_path` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER table question_pictures
ADD reply_id int(11) NOT NULL DEFAULT 0;



CREATE TABLE `question_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `isp` varchar(100) DEFAULT NULL,
  `net_type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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


CREATE TABLE `vip_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `service_type` char(1) NOT NULL COMMENT '3 邀請加入line 1 服務 2 回報建議 ',
  `request_code` char(1) NOT NULL,
  `note` varchar(250) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1024 DEFAULT CHARSET=utf8 COMMENT='VIP服務紀錄'


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
  `uid` int(11) NOT NULL,
  `char_name` varchar(50) DEFAULT NULL,
  `char_in_game_id` varchar(50) NOT NULL DEFAULT '',
  `server_name` varchar(255) DEFAULT NULL,
  `deposit_total` int(10) DEFAULT NULL,
  `account_create_time` timestamp NULL DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_added` tinyint(1) DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `site` varchar(50) DEFAULT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `country` char(3) DEFAULT NULL,
  `latest_topup_date` datetime DEFAULT NULL,
  `vip_ranking` enum('general','silver','gold','platinum','black') DEFAULT NULL,
  `vip_ranking_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`,`char_in_game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


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


    drop TRIGGER upd_vip;
    delimiter //
    CREATE TRIGGER upd_vip BEFORE UPDATE ON whale_users
       FOR EACH ROW
       BEGIN
        IF NEW.site='h35naxx1hmt' THEN
          IF NEW.deposit_total <200000 and NEW.deposit_total>=150000 THEN
            SET NEW.vip_ranking = 'general';
          ELSEIF NEW.deposit_total >= 200000 AND NEW.deposit_total <400000  THEN
            SET NEW.vip_ranking = 'silver';
          ELSEIF NEW.deposit_total >= 400000 AND NEW.deposit_total <700000  THEN
            SET NEW.vip_ranking = 'gold';
          ELSEIF NEW.deposit_total >= 700000 AND NEW.deposit_total <1000000  THEN
            SET NEW.vip_ranking = 'platinum';
          ELSEIF NEW.deposit_total >= 1000000  THEN
            SET NEW.vip_ranking = 'black';
          END IF;
        ELSEIF NEW.site='L8na' THEN
          IF NEW.deposit_total <100000 and NEW.deposit_total>=50000 THEN
            SET NEW.vip_ranking = 'general';
          ELSEIF NEW.deposit_total >= 100000  THEN
            SET NEW.vip_ranking = 'silver';
          END IF;
        END IF;
           IF (NEW.vip_ranking <> OLD.vip_ranking) THEN
              SET NEW.vip_ranking_updated = NOW();
           END IF;

       END;//
    delimiter ;



    DROP TABLE IF EXISTS `h55_prereg`;
    CREATE TABLE `h55_prereg` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `email` varchar(300) DEFAULT NULL,
      `ip` varchar(20) DEFAULT NULL,
      `country` varchar(20) DEFAULT NULL,
      `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `status` char(1) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='第五人格預註冊';

CREATE TABLE `vip_daily_sum` (
  `date` date NOT NULL,
  `game_id` varchar(20) NOT NULL,
  `topup_sum` int(11) DEFAULT 0,
  `topup_count` int(11) DEFAULT 0,
  `chanel_dist` varchar(300) DEFAULT NULL,
  `top_users` varchar(300) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`date`,`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='訂單統計';



DROP PROCEDURE IF EXISTS create_vip_daily_sum;
DELIMITER //
CREATE PROCEDURE create_vip_daily_sum(input_game_id varchar(20))
BEGIN
  DECLARE done INT DEFAULT FALSE;
  DECLARE c_min_date VARCHAR(50);
  DECLARE c_date date;
  DECLARE cur1 CURSOR FOR
  SELECT `date`
  FROM vip_daily_sum
  where game_id = input_game_id  and chanel_dist is null;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;


  IF input_game_id ='h35naxx1hmt' THEN
    SET c_min_date = '2017-11-15';
  ELSEIF input_game_id ='L8na'  THEN
    SET c_min_date = '2018-02-24';
  END IF;

  DELETE FROM vip_daily_sum where game_id =input_game_id ORDER BY date DESC LIMIT 1;


  insert into vip_daily_sum (date,game_id,topup_sum,topup_count)
  SELECT DATE_FORMAT(create_time, '%Y-%m-%d') as date,
  game_id,
  SUM(amount) as topup_sum,
  COUNT(distinct account) as topup_count
  FROM negame_orders
  WHERE game_id = input_game_id and  DATE_FORMAT(create_time, '%Y-%m-%d') > (select ifnull(max(date),c_min_date) from vip_daily_sum  WHERE game_id = input_game_id)
  GROUP BY  DATE_FORMAT(create_time, '%Y-%m-%d');



  OPEN cur1;

  read_loop: LOOP
    FETCH cur1 INTO c_date;
    IF done THEN
      LEAVE read_loop;
    END IF;
        UPDATE vip_daily_sum set top_users =
        (select GROUP_CONCAT(content SEPARATOR ';' ) from
        (SELECT DATE_FORMAT(create_time, '%Y-%m-%d') as oDate, concat(role_name ,'-', sum(amount) ) as content, amount
        FROM negame_orders
        WHERE  game_id = input_game_id and  DATE_FORMAT(create_time, '%Y-%m-%d') =c_date
        GROUP BY DATE_FORMAT(create_time, '%Y-%m-%d'),role_name
        ORDER BY sum(amount) desc limit 5) aTable
        group by oDate)
        where game_id = input_game_id and date=c_date;

        UPDATE vip_daily_sum set chanel_dist =
        (select  GROUP_CONCAT(content SEPARATOR ';') from
        (SELECT DATE_FORMAT(create_time, '%Y-%m-%d') as oDate,concat(transaction_type ,'-', sum(amount) ) as content
        FROM negame_orders
        WHERE game_id = input_game_id and DATE_FORMAT(create_time, '%Y-%m-%d') =c_date
        GROUP BY DATE_FORMAT(create_time, '%Y-%m-%d'),transaction_type order by sum(amount) desc) aTable
        group by oDate)
        where game_id = input_game_id and date=c_date;

  END LOOP;

  CLOSE cur1;

END;//
delimiter ;
call create_vip_daily_sum('L8na');
call create_vip_daily_sum('h35naxx1hmt');



DROP PROCEDURE IF EXISTS batch_upd_q_reply_info;
DELIMITER //
CREATE PROCEDURE batch_upd_q_reply_info(id1 INT,id2 INT)
BEGIN
  DECLARE done INT DEFAULT FALSE;
  DECLARE c_qid INT;
  DECLARE _is_official CHAR(1);
  DECLARE _last_replied_time datetime;
  DECLARE cur1 CURSOR FOR
  SELECT id FROM questions WHERE last_replied_time is null and id between id1 and id2;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur1;
  read_loop: LOOP
  FETCH cur1 INTO c_qid;
  IF done THEN
    LEAVE read_loop;
  END IF;
  SELECT is_official, create_time into _is_official, _last_replied_time from question_replies where question_id=c_qid order by id desc limit 1;
  UPDATE questions SET last_replied = (CASE WHEN _is_official=1 THEN 'O' ELSE 'U' END),last_replied_time=_last_replied_time  WHERE id = c_qid;

  END LOOP;
  CLOSE cur1;

END;//
delimiter ;

call batch_upd_q_reply_info(17000,17310)
SELECT is_official, create_time into _is_official, _last_replied_time from question_replies where question_id=46265 order by id desc limit 1;


DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text,
  `priority` tinyint(2) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE faq MODIFY COLUMN create_time timestamp NULL DEFAULT CURRENT_TIMESTAMP

INSERT INTO `faq`(title,content,priority,start_time,end_time,is_active)
VALUES('※帳號遺失/換了手機/忘了綁定？','※帳號遺失/換了手機/忘了綁定？
若您是要反映『帳號遺失』問題，
還請您提供下列資訊：
☑伺服器(亞洲服/歐美服)：
☑帳號創建時間(年/月/日)：
☑帳號等級：
☑帳號暱稱：
☑帳號最後登入裝置版本型號：
☑最後登入時間：','2',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR))


INSERT INTO `faq`(title,content,priority,start_time,end_time,is_active)
VALUES('※帳號遺失/換了手機/忘了綁定？','若您是要反映『帳號遺失』問題， <br />還請您提供下列資訊： <br /> ☑伺服器(亞洲服/歐美服)： <br /> ☑帳號創建時間(年/月/日)： <br /> ☑帳號等級： <br /> ☑帳號暱稱： <br /> ☑帳號最後登入裝置版本型號： <br /> ☑最後登入時間：<br />','5',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1)



INSERT INTO `faq`(title,content,priority,start_time,end_time,is_active)
VALUES('※逢魔之戰看不到兵線、野怪、大蛇全部隱形?<br />','請<b>退出並重啟遊戲</b>下載更新包<br />更新版本之後就可以正常使用喔!<br />','5',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 1 DAY),1)



{"q":"※逢魔之戰看不到兵線、野怪、大蛇全部隱形?","a":"請<b>退出並重啟遊戲</b>下載更新包<br />更新版本之後就可以正常使用喔!<br />"},

INSERT INTO `faq`(title,content,priority,start_time,end_time,is_active) VALUES('※性別填錯了怎麼辦？','目前並未提供『變更性別』的服務喔。<br />','5',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※如何綁定帳號？','登入畫面點選右上方帳號進入用戶中心，<br />選擇使用Google Play、Facebook帳號來進行綁定。<br />','5',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※如何刪除帳號？','目前並未提供『刪除帳號』的服務喔。<br />','5',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※陸服有辦法移轉到台服嗎?','目前台版是全新的伺服器，所以無法移民喔 <br />提醒您： <br />※雙版本系統(ios與安卓)是無法共用帳號，<br />但是，伺服器是可以一起遊玩喔！ <br />※台灣版本與海外版本是無法共用帳號，<br />但是，伺服器是可以一起遊玩喔！<br />','5',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※扣款成功卻沒有拿到商品？','請您重新啟動遊戲再次確認。<br />','4',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※已經重新啟動還是沒拿到商品？','還請您提供下列資訊： <br /> ☑帳號ID：(於用戶中心的數字) <br /> ☑角色ID：(頭像旁的數字ID)	<br /> ☑交易時間： <br /> ☑訂單編號：	<br /> ☑購買品項名稱：	<br /> ☑未收到的商品：	<br /> ☑交易收據截圖：<br />','4',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※有其他（例如超商或點卡）付款方式嗎？','目前《第五人格》僅提供Google與Apple雙平台<br />儲值等方式，而未來若有增加其他儲值方式<br />都會公告於粉絲團或官網公告。<br />','4',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※無法儲值？','請詳細說明在哪個步驟出現什麼錯誤訊息，<br />能附上擷圖會加快我們處理問題。<br />','4',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※遊戲內沒有聲音?','可先使用「修復客戶端」<br />以及確認遊戲內「聲音設置」是否都有開啟。 <br />提醒您，裝置若於靜音下，遊戲中也不會有聲音唷。<br />','3',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※進入匹配都不是自身選擇的陣營?','若選擇求生者進行遊戲而求生者陣營人數過多時， <br />遊戲將會詢問『<b>是否要轉監管者</b>』，<br />這時候要點『<b>否</b>』才會是求生者陣營唷。<br />','3',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※為什麼會被禁言？','遊戲內若有不當言論時玩家可透過檢舉機制反饋，<br />當經查核屬實時，系統將會給予禁言的懲處，<br />請查看郵件即可得知被禁言的時間。<br />','3',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※遊戲操作有異常?','請您協助說明異常時間、 <br />異常地圖與人物以及詳細的操作流程，<br />讓相關人員能更快復現您的情形唷。<br />','1',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※遊戲很卡很lag？','嘗試互換您的網路連線選擇較佳的環境遊玩，<br />如Wifi / 4G 互相切換。<br />','3',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※換了網路環境還是很卡？','請您來信提供下列相關連線環境資訊：<br /> ☑(1)帳號ID(於用戶中心的數字) : <br /> ☑(2)電信供應商／連線方式 :<br /> ☑(3)戰鬥異常時間:<br /> ☑(4)ping數值:（可查看遊戲左上）<br /> ☑(5)伺服器:歐美服/亞洲服<br /> ☑(6)異常情形說明:(如爆Ping.LAG)<br /> ☑(7)使用裝置;(如iPhone X)<br />','3',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),
('※為什麼會一直退出遊戲?','若手機記憶體緩存空間不足則會發生此情形，<br />需將手機關機重開並清除已開啟的APP應用程式，<br />倘若仍有問題，請您協助提供以下資訊：<br />☑裝置型號：<br />☑作業系統版本：<br />☑網路電信業者 / 連線方式(3G/4G/WiFi)：<br />☑遊戲版本：<br />','3',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 YEAR),1),


INSERT INTO `faq_games`
select id, 'g78naxx2hmt' from faq where id=17

DROP TABLE IF EXISTS `faq_games`;
CREATE TABLE `faq_games` (
  `faq_id` int(11) NOT NULL,
  `game_id` varchar(20) NOT NULL,
  CONSTRAINT PK_faqgame PRIMARY KEY (faq_id,game_id),
  FOREIGN KEY (faq_id)
      REFERENCES faq(id),
  FOREIGN KEY (game_id)
      REFERENCES games(game_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `faq_types`
select id, '2' from faq where id between 6 and 9

INSERT INTO `faq_types`
select id, '3' from faq where id =17;

INSERT INTO `faq_types`
select id, '4' from faq where id =17;

INSERT INTO `faq_types`
select id, '8' from faq where id >13;

DROP TABLE IF EXISTS `faq_types`;
CREATE TABLE `faq_types` (
  `faq_id` int(11) NOT NULL,
  `type_id` char(1) NOT NULL,
  CONSTRAINT PK_faqtype PRIMARY KEY (faq_id,type_id),
  FOREIGN KEY (faq_id) REFERENCES faq(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE VIEW faq_summary AS
select faq.id,title,type_id ,game_id
from faq inner join faq_types on faq.id = faq_types.faq_id
inner join faq_games on faq_games.faq_id=faq.id


DROP VIEW IF EXISTS faq_summary;
CREATE VIEW faq_summary AS
  SELECT type_id,
  cast(concat('[', group_concat(title ORDER BY title SEPARATOR ','), ']') as json) AS title_array
  FROM (select distinct type_id from faq_types) a inner join faq
  on A.faq_id = faq.id

select  group_concat(title ORDER BY title SEPARATOR ',') AS title_array from faq

select  type_id, group_concat("q:",title ORDER BY title SEPARATOR ,',') AS title_array
from
(select title,type_id from faq inner join faq_types on faq.id = faq_types.faq_id ) a
group by type_id;

select id, title,type_id from faq inner join faq_types on faq.id = faq_types.faq_id;




  select type_id from (select distinct type_id from faq_types)

  SELECT
    person_id                                                                                              AS pfs_person_id,
    max(person_name)                                                                                       AS pfs_person_name,
    cast(concat('[', group_concat(json_quote(fruit_name) ORDER BY fruit_name SEPARATOR ','), ']') as json) AS pfs_fruit_name_array
  FROM
    person
    INNER JOIN person_fruit
      ON person.person_id = person_fruit.pf_person
    INNER JOIN fruit
      ON person_fruit.pf_fruit = fruit.fruit_id
  GROUP BY
    person_id;



DROP TABLE IF EXISTS `batch_tasks`;
CREATE TABLE `batch_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `admin_uid` int(11) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '1-處理中 4-回覆並立即結案 7-回覆並預約結案',
  FOREIGN KEY (game_id) REFERENCES games(game_id),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='批次回覆區';



DROP TABLE IF EXISTS `batch_questions`;
CREATE TABLE `batch_questions` (
  `batch_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  CONSTRAINT PK_qf PRIMARY KEY (question_id,batch_id),
  FOREIGN KEY (question_id) REFERENCES questions(id),
  FOREIGN KEY (batch_id) REFERENCES batch_tasks(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cpl_cases`;
CREATE TABLE `cpl_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `o_case_id` varchar(100) NOT NULL COMMENT '發文字號',
  `o_case_date` DATE NOT NULL COMMENT '發文日期',
  `appellant` varchar(20) NOT NULL COMMENT '申訴人姓名',
  `reason` varchar(50) NOT NULL COMMENT '申訴原因',
  `phone` varchar(100) DEFAULT NULL COMMENT '連絡電話',
  `game_id` varchar(20) DEFAULT NULL COMMENT '遊戲',
  `server_id` varchar(20) DEFAULT NULL COMMENT '伺服器',
  `role_name` varchar(20) DEFAULT NULL COMMENT '角色名稱',
  `admin_uid` int(11) DEFAULT NULL COMMENT '管理員',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL,
  `close_date` DATE NOT NULL COMMENT '結案日期',
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '1-處理中 2-已回覆 3-進入協調會 4-已結案',
  FOREIGN KEY (game_id) REFERENCES games(game_id),
  FOREIGN KEY (server_id) REFERENCES servers(server_id),
  UNIQUE KEY `case_id_UNIQUE` (`o_case_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='消保案件主表';

ALTER TABLE cpl_cases  ADD COLUMN deadline DATE NOT NULL COMMENT '回文期限';

update cpl_cases set deadline=DATE_ADD(o_case_date, INTERVAL 15 DAY);
 o_case_date +15

ALTER TABLE cpl_cases MODIFY COLUMN o_case_id varchar(100) NOT NULL COMMENT '發文字號'
ALTER TABLE cpl_mediations MODIFY COLUMN o_case_id varchar(100) NOT NULL COMMENT '發文字號'



DROP TABLE IF EXISTS `case_reference`;
CREATE TABLE `case_reference` (
  `case_id` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  CONSTRAINT PK_caseref PRIMARY KEY (case_id,ref_id),
  FOREIGN KEY (case_id) REFERENCES cpl_cases(id),
  FOREIGN KEY (ref_id) REFERENCES cpl_cases(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `cpl_replies`;
CREATE TABLE `cpl_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL COMMENT '案件編號',
  `claim` text NOT NULL COMMENT '訴求',
  `response` text NOT NULL COMMENT '我方回應',
  `contact_date` DATE NOT NULL COMMENT '聯絡日期',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_uid` int(11) DEFAULT NULL,
  FOREIGN KEY (case_id) REFERENCES cpl_cases(id),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COMMENT='消保案件往來回覆';


ALTER TABLE cpl_replies
ADD note text NOT NULL COMMENT '歷程紀錄';

update cpl_replies set note= concat(claim,response);

ALTER TABLE cpl_replies
ADD contact_time timestamp NOT NULL COMMENT '聯絡時間';

update cpl_replies set contact_time = contact_date;
ALTER TABLE `cpl_replies` DROP COLUMN `claim`;
ALTER TABLE `cpl_replies` DROP COLUMN `response`;

DROP TABLE IF EXISTS `cpl_attachments`;
CREATE TABLE `cpl_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL COMMENT '案件編號',
  `pic_path` varchar(300) DEFAULT NULL,
  FOREIGN KEY (case_id) REFERENCES cpl_cases(id),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='消保案件附件';

ALTER TABLE cpl_attachments
ADD title varchar(50) NOT NULL COMMENT '附件名稱';


DROP TABLE IF EXISTS `cpl_mediations`;
CREATE TABLE `cpl_mediations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL COMMENT '案件編號',
  `o_case_id` varchar(100) NOT NULL COMMENT '發文字號',
  `o_case_date` DATE NOT NULL COMMENT '發文日期',
  `req_date` datetime NOT NULL COMMENT '出席時間',
  `req_place` varchar(100) NOT NULL COMMENT '出席地點',
  `o_staff` varchar(20) NOT NULL COMMENT '主持人',
  `o_contact` varchar(20) NOT NULL COMMENT '聯絡人',
  `o_phone` varchar(100) DEFAULT NULL COMMENT '連絡電話',
  `representative` varchar(20) NULL COMMENT '我方出席人員',
  `close_date` DATE NULL COMMENT '結案日期',
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '1-處理中 4-已結案',
  `note` text NULL COMMENT '結果',
  `admin_uid` int(11) DEFAULT NULL COMMENT '管理員',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL,
  FOREIGN KEY (case_id) REFERENCES cpl_cases(id),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='調停會紀錄';


drop TRIGGER ins_reply_cpl;
delimiter //
CREATE TRIGGER ins_reply_cpl BEFORE INSERT ON cpl_replies
   FOR EACH ROW
   BEGIN
   UPDATE cpl_cases SET status=2  WHERE id = NEW.case_id;
   END;//
delimiter ;


SET foreign_key_checks = 0;
-- Drop tables
drop table ...
-- Drop views
drop view ...
SET foreign_key_checks = 1;



CREATE TABLE `g78_s2_result` (
  `player_id` varchar(30) NOT NULL,
  `season_5v5cnt` int  DEFAULT '0',
  `punish_cnt` int  DEFAULT '0',
  `punish_ag` float DEFAULT '0',
  `tag` int  DEFAULT '0',
  PRIMARY KEY (`player_id`)
);



DROP TABLE IF EXISTS `gov_letters`;
CREATE TABLE `gov_letters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `o_letter_id` varchar(40) NOT NULL COMMENT '發文字號',
  `o_letter_date` DATE NOT NULL COMMENT '發文日期',
  `contact` varchar(20) NOT NULL COMMENT '承辦人姓名',
  `game_id` varchar(20) DEFAULT NULL COMMENT '遊戲',
  `server_id` varchar(20) DEFAULT NULL COMMENT '伺服器',
  `role_name` varchar(20) DEFAULT NULL COMMENT '角色名稱',
  `note` text NULL COMMENT '備註記事',
  `file_path` varchar(300) DEFAULT NULL COMMENT '上傳檔案路徑',
  `admin_uid` int(11) DEFAULT NULL COMMENT '管理員',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL,
  `deadline` DATE NOT NULL COMMENT '回文期限',
  `close_date` DATE NOT NULL COMMENT '結案日期',
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '1-處理中 4-已結案',
  FOREIGN KEY (game_id) REFERENCES games(game_id),
  FOREIGN KEY (server_id) REFERENCES servers(server_id),
  UNIQUE KEY `letter_id_UNIQUE` (`o_letter_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='公函主表';



/* 2018 h55 yahoo購物活動玩家填序號log */;
DROP TABLE IF EXISTS `log_yahoo_event`;
CREATE TABLE `log_yahoo_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `char_id` int(11)  unsigned NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `serial_no` varchar(60) NOT NULL COMMENT '序號',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (char_id) REFERENCES characters(id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE `event_preregister` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `event_id` int(11) NOT NULL,
 `uid` BIGINT(64) DEFAULT NULL,
 `nick_name` varchar(128) DEFAULT NULL,
 `status` tinyint(4) NOT NULL DEFAULT '0',
 `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `update_time` datetime DEFAULT NULL,
 `email` varchar(128) DEFAULT NULL,
 `ip` varchar(20) DEFAULT NULL,
 `country` varchar(20) DEFAULT NULL,
 `auth_code` varchar(300) DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `fb_UNIQUE` (`event_id`, `uid`),
 UNIQUE KEY `email_UNIQUE` (`event_id`, `email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE `l20na_items` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `item_code` varchar(20) DEFAULT NULL,
 `item_name` varchar(50) DEFAULT NULL,
 `item_pic` varchar(100) DEFAULT NULL,
 `status` tinyint(4) NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`),
 UNIQUE KEY `code_UNIQUE` (`item_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE `l20na_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `desc` varchar(50) DEFAULT NULL,
  `event_uid` int(11) NOT NULL,
  `item_count` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_UNIQUE` (`date`,`event_uid`),
  FOREIGN KEY (event_uid)
      REFERENCES event_preregister(id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `l20na_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `o_id` int(11) NOT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  FOREIGN KEY (o_id)
      REFERENCES l20na_orders(id),
  FOREIGN KEY (item_code)
      REFERENCES l20na_items(item_code)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- 呼叫後自動增加十種亂數道具
DROP PROCEDURE IF EXISTS create_l20na_orders;
DELIMITER //
CREATE PROCEDURE create_l20na_orders(in_date date, in_desc varchar(50) CHARSET utf8, int_e_uid int)
BEGIN
  DECLARE my_o_id INT;
  DECLARE EXIT HANDLER FOR 1062
        SELECT 'error' as status, '重複發送' as message;
  INSERT INTO l20na_orders(`date`,`desc`,event_uid)
  VALUES(in_date,in_desc,int_e_uid);
  select LAST_INSERT_ID() into my_o_id;
  if my_o_id > 0
  then
    INSERT INTO l20na_detail(o_id,item_code)
    select my_o_id,item_code from l20na_items order by rand() limit 25;
  END IF;
END;
//
DELIMITER ;


id	臉書暱稱	Email	未使用/所有物品	ip	國家	時間
12	Sophie Tsai	11shihfan.tsai@gmail.com	35/60	61.220.44.200	Taiwan	2018-12-13 17:28:02
13	Kuanche Kao	kenzo.com@gmail.com	9/25	61.220.44.200	Taiwan	2018-12-13 18:07:09
14	Connie Huang	Yun_huang@longeplay.com.tw	9/50	61.220.44.200	Taiwan	2018-12-13 18:07:57
15	朱晉廷	aixiiae2005@yahoo.com.tw	2/50	61.220.44.200	Taiwan	2018-12-13 18:26:08
16	Pn Li	moetwchristine@gmail.com	13/25	61.220.44.200	Taiwan	2018-12-14 10:46:13
17	于承宏	seacielo0601@gmail.com	0/25	61.2

call create_l20na_orders('2018-12-7','測試送好禮',13);
call create_l20na_orders('2018-12-7','測試送好禮',14);
call create_l20na_orders('2018-12-7','測試送好禮',15);
call create_l20na_orders('2018-12-7','測試送好禮',16);
call create_l20na_orders('2018-12-7','測試送好禮',17);


DROP PROCEDURE IF EXISTS create_npc_affections;
DELIMITER //
CREATE PROCEDURE create_npc_affections(int_e_uid int)
BEGIN
  DECLARE EXIT HANDLER FOR 1062
        SELECT 'error' as status, '重複新增' as message;
  INSERT INTO l20na_npc_affections(event_uid,npc_code,affection)  SELECT int_e_uid, npc_code, 0 from l20na_npcs;
END;
//
DELIMITER ;



  CREATE TABLE `l20na_npcs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `npc_name` varchar(20) DEFAULT NULL,
   `npc_gender` enum('m','f') DEFAULT NULL,
   `npc_code` varchar(20) DEFAULT NULL,
   `npc_pic` varchar(100) DEFAULT NULL,
   `status` tinyint(4) NOT NULL DEFAULT '1',
   PRIMARY KEY (`id`),
   UNIQUE KEY `code_UNIQUE` (`npc_code`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;






insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('無情','m','wuq','wuq_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('顧惜朝','m','guxz','guxz_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('燕無歸','m','yanwg','yanwg_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('方應看','m','fangyk','fangyk_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('葉問舟','m','yewz','yewz_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('葉雪青','f','yexq','yexq_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('溫柔','f','wenr','wenr_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('李師師','f','liss','liss_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('花將離','f','huajl','huajl_body');
insert into l20na_npcs(npc_name,npc_gender,npc_code,npc_pic) values('姬蜜兒','f','jime','jime_body');

  CREATE TABLE `l20na_npc_affections` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `event_uid` int(11) NOT NULL,
   `npc_code` varchar(20) NOT NULL,
   `affection` tinyint(4) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY `npc_UNIQUE` (`event_uid`,`npc_code`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


  CREATE TABLE `l20na_npc_affections_log` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `aff_id` int(11) NOT NULL,
   `affection_change` tinyint(4) NOT NULL DEFAULT '0',
   `item_id` tinyint(4) NOT NULL DEFAULT '0',
   `note` varchar(200) NOT NULL DEFAULT '',
   PRIMARY KEY (`id`),
   UNIQUE KEY `npc_UNIQUE` (`aff_id`,`item_id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


  ALTER TABLE l20na_npc_affections_log
  ADD create_time timestamp DEFAULT CURRENT_TIMESTAMP;
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  alter table l20na_items add item_desc varchar(200) DEFAULT null;


Insert into event_preregister(event_id,uid,email,nick_name,ip,country)
values(12,10213412799650864,'sophie@gmail.com','Sophie Tsai','61.220.44.200','Taiwan')

give l20na_detail.id=46 item to l20na_npc_affections.id=16 npc
update l20na_detail set status=0 where id=@id;
find npc item score ,

mysql> select * from l20na_detail where o_id in(select id from l20na_orders where event_uid=14)
+----+------+-----------+---------------------+--------+
| id | o_id | item_code | create_time         | status |
+----+------+-----------+---------------------+--------+
| 46 |    9 | 1034      | 2018-12-10 15:45:24 |      1 |
| 47 |    9 | 1037      | 2018-12-10 15:45:24 |      1 |
| 48 |    9 | 1026      | 2018-12-10 15:45:24 |      1 |
| 49 |    9 | 1017      | 2018-12-10 15:45:24 |      1 |
| 50 |    9 | 1015      | 2018-12-10 15:45:24 |      1 |
| 51 |    9 | 1014      | 2018-12-10 15:45:24 |      1 |
| 52 |    9 | 1008      | 2018-12-10 15:45:24 |      1 |
| 53 |    9 | 1042      | 2018-12-10 15:45:24 |      1 |
| 54 |    9 | 1043      | 2018-12-10 15:45:24 |      1 |
| 55 |    9 | 1038      | 2018-12-10 15:45:24 |      1 |
+----+------+-----------+---------------------+--------+
10 rows in set (0.00 sec)

mysql> select * from l20na_npc_affections;
+----+-----------+----------+-----------+
| id | event_uid | npc_code | affection |
+----+-----------+----------+-----------+
| 16 |        14 | fangyk   |         0 |
| 17 |        14 | guxz     |         0 |
| 18 |        14 | huajl    |         0 |
| 19 |        14 | jime     |         0 |
| 20 |        14 | liss     |         0 |
| 21 |        14 | wenr     |         0 |
| 22 |        14 | wuq      |         0 |
| 23 |        14 | yanwq    |         0 |
| 24 |        14 | yewz     |         0 |
| 25 |        14 | yexq     |         0 |
+----+-----------+----------+-----------+

select a.id, a.affection, b.npc_name,b.npc_gender,b.npc_code,b.npc_pic
from l20na_npc_affections a
left join l20na_npcs b
on a.npc_code = b.npc_code
where a.event_uid=14


{"id":"3","npc_name":"燕無歸","npc_gender":"m","npc_code":"yanwg","npc_pic":"yanwg_body","status":"1"},

mysql> select * from l20na_npcs;
+----+-----------+------------+----------+-------------+--------+
| id | npc_name  | npc_gender | npc_code | npc_pic     | status |
+----+-----------+------------+----------+-------------+--------+
|  1 | 無情      | m          | wuq      | wuq_body    |      1 |
|  2 | 顧惜朝    | m          | guxz     | guxz_body   |      1 |
|  3 | 燕無歸    | m          | yanwg    | yanwg_body  |      1 |
|  4 | 方應看    | m          | fangyk   | fangyk_body |      1 |
|  5 | 葉問舟    | m          | yewz     | yewz_body   |      1 |
|  6 | 葉雪青    | f          | yexq     | yexq_body   |      1 |
|  7 | 溫柔      | f          | wenr     | wenr_body   |      1 |
|  8 | 李師師    | f          | liss     | liss_body   |      1 |
|  9 | 花將離    | f          | huajl    | huajl_body  |      1 |
| 10 | 姬蜜兒    | f          | jime     | jime_body   |      1 |
+----+-----------+------------+----------+-------------+--------+
mysql> select * from l20na_items;
select item_code, item_name into from l20na_items

select * from l20na_detail where o_id in(select id from l20na_orders where event_uid=14);
+----+------+-----------+---------------------+--------+
| id | o_id | item_code | create_time         | status |


select a.id,  b.item_code, b.item_name, b.item_pic
from l20na_detail a left join
l20na_items b on a.item_code = b.item_code
where a.o_id in(select id from l20na_orders where event_uid=14);
+-----+-----------+-----------------+---------------------------+--------+
| id  | item_code | item_name       | item_pic                  | status |
+-----+-----------+-----------------+---------------------------+--------+
|  64 | 1001      | 蹴鞠            | item_juqiu                |      1 |
|  65 | 1002      | 團扇            | item_tuanshan             |      1 |
|  66 | 1003      | 蒜香排骨        | item_food_tiaoshenrou     |      1 |
|  67 | 1004      | 阮              | item_task_ruanqin         |      1 |
|  68 | 1005      | 剝皮小刀        | item_task_dao             |      1 |
|  69 | 1006      | 一屜包子        | item_life_zhenglong       |      1 |
|  70 | 1007      | 撥浪鼓          | item_bolanggu             |      1 |
|  71 | 1008      | 繡花絹帕        | item_task_baishoupa       |      1 |
|  72 | 1009      | 水域全圖        | item_yhshuidao            |      1 |
|  73 | 1010      | 三合美酒        | Drink_suiyujiu            |      1 |
|  74 | 1011      | 明前龍井        | Tea_pubuxianming          |      1 |
|  75 | 1012      | 糖葫蘆          | icon_task_tanghulu        |      1 |
|  76 | 1013      | 西湖蓮蓬        | item_life_lianzi          |      1 |
|  77 | 1014      | 霹靂堂火器      | skill_Unload_ShouPao      |      1 |
|  78 | 1015      | 雲棲竹筍        | item_life_sun             |      1 |
|  79 | 1016      | 龍井黑豬肉      | item_life_zhurou          |      1 |
|  80 | 1017      | 霹靂堂炮仗      | item_task_paozhang        |      1 |
|  81 | 1018      | 熙春調味料      | item_jgxiangxin           |      1 |
|  82 | 1019      | 彩球            | item_task_sanseqiu        |      1 |
|  83 | 1020      | 風俗畫          | item_chungongtu           |      1 |
|  84 | 1024      | 西域葡萄        | item_life_putao           |      1 |
|  85 | 1025      | 碧血戰籍        | item_book_tongyong_971333 |      1 |
|  86 | 1026      | 碧血毒蠍        | item_xiezi                |      1 |
|  87 | 1027      | 鎖子甲          | item_bhls_suozijia        |      1 |
|  88 | 1028      | 和田玉石        | item_life_kongqueshi      |      1 |
|  89 | 1029      | 碧血戰鎧        | item_fashion_yuzu_s       |      1 |
|  90 | 1030      | 蛇骨手串        | item_21091136             |      1 |
|  91 | 1031      | 武林秘笈        | item_book_tongyong_971333 |      1 |
|  92 | 1033      | 桃溪泥人        | item_task_wanou           |      1 |
|  93 | 1034      | 布老虎          | item_bulaohu              |      1 |
|  94 | 1035      | 桃溪花枝        | item_huazhi               |      1 |
|  95 | 1037      | 孔雀翎          | item_kuileikongque        |      1 |
|  96 | 1038      | 蟈蟈籠          | item_szqx_guoguolong      |      1 |
|  97 | 1039      | 桃溪河蝦        | item_xia                  |      1 |
|  98 | 1041      | 火銃            | item_szqx_huochong        |      1 |
|  99 | 1042      | 機械鳥          | item_mutouxiaoniao        |      1 |
| 100 | 1043      | 玉扳指          | item_szqx_banzhi          |      1 |
| 101 | 1044      | 藥粥            | item_food_waguanlurou     |      1 |
| 102 | 1045      | 靈芝            | item_lingzhi              |      1 |
+-----+-----------+-----------------+---------------------------+--------+
39 rows in set (0.00 sec)


l20na_npcs_items;
+----+-----------+------------+----------+-------------+--------+
| id | npc_code  | item_code | response |  response_text   | response_voice |
+----+-----------+------------+----------+-------------+--------+
1  wuq        1001 5 ……不知送我此物，是何用意？  gift_female_chalou_wuqing_021

select c.npc_name,b.item_name, a.response,response_text,response_voice
from l20na_npcs_items a
left join l20na_items b on a.item_code = b.item_code
left join l20na_npcs c on a.npc_code=c.npc_code

response_text like '%{{玩家小名}}%'
  select * from l20na_npcs_items where response_text like '%{{玩家小名}}%';
update l20na_npcs_items set  response_text = '繡帕宜贈女子，於我無用，你還是自己留著吧。'  where response_text like '%{{玩家小名}}%';


CREATE TABLE `l20na_npcs_items` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `npc_code` varchar(20) DEFAULT NULL,
 `item_code` varchar(20) DEFAULT NULL,
 `response` enum('yuck','okla','awesome') DEFAULT NULL,
 `response_text` varchar(200) NOT NULL DEFAULT '',
 `response_voice` varchar(200) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`),
 UNIQUE KEY `npc_UNIQUE` (`npc_code`,`item_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


id  npc_code  item_code response  response_text response_voice
id npc_code item_code	response	response_text		response_voice



//update item code status
update l20na_detail set status=0 where id=@id;
//find npc_item score
select score from l20na_npcs_items where npc_code=@code and item_code=@item_code
//update npc affections
update l20na_npc_affections set affections=affections+score where uid=@uid and npc_id=@npc_id;
//keep log
insert into l20na_npc_affections_log(aff_id,affection_change,item_id,note)
  values(22,-5,46,'你送給無情一個布老虎,他並不喜歡')

//return result
return ok, "謝謝你", "voice_path", "total_score"


select concat(sum(case when status=1 then 1 else 0 end),'/',count(*)) as item_status from l20na_detail where o_id in (select id from l20na_orders where event_uid=14);

DROP PROCEDURE IF EXISTS l20na_give_item;
DELIMITER //
CREATE PROCEDURE l20na_give_item(my_item int, my_npc int)
BEGIN
DECLARE countRow INT;
update l20na_detail set status=2 where status=1 and id=my_item;
SET countRow =  ROW_COUNT();
IF (countRow >  0) THEN
  select item_code, item_name into @item_code,@item_name from l20na_items where item_code=(select item_code from l20na_detail where id=my_item);
  select npc_code,npc_name,npc_gender into @npc_code,@npc_name,@npc_gender from l20na_npcs where npc_code=(select npc_code from l20na_npc_affections where id=my_npc);
  select response,response_text,response_voice into @res,@res_text,@res_vp from l20na_npcs_items where npc_code=@npc_code and item_code=@item_code;
  select nick_name into @nick_name from event_preregister where id=(select event_uid from l20na_npc_affections where id=my_npc);
  update l20na_npc_affections set affection=affection+(CASE WHEN @res='awesome' THEN 20 WHEN @res='okla' THEN 10 ELSE -5 END) where id=my_npc;
  insert into l20na_npc_affections_log(aff_id,affection_change,item_id,note)
  values(my_npc,(CASE WHEN @res='awesome' THEN 20 WHEN @res='okla' THEN 10 ELSE -5 END),my_item,
  CONCAT(@nick_name, ' 送給 ',@npc_name,' 一個 ',@item_name,', ',(case when @npc_gender='m' then '他' else '她' end),
  (CASE WHEN @res='awesome' THEN '很喜歡' WHEN @res='okla' THEN '還算喜歡' ELSE '並不喜歡' END)));
  SELECT '1' AS rtn_code, @res as npc_res,@res_text as res_text,@res_vp as res_vp, affection_change, note FROM l20na_npc_affections_log WHERE id=LAST_INSERT_ID();
ELSE
    SELECT '0' AS rtn_code, '物品已經使用' as note;
END IF;
END;
//
DELIMITER ;


select * from event_preregister;
select * from l20na_npc_affections;
select * from l20na_npc_affections_log;
select * from l20na_orders;
select * from l20na_detail;

delete from l20na_detail;
delete from l20na_orders;
delete from l20na_npc_affections_log;
delete from l20na_npc_affections;
delete from event_preregister;


call l20na_give_item(100,64)
call l20na_give_item(8,1)

BEGIN
DECLARE countRow INT;
DECLARE roomTypeId INT;
        INSERT INTO room_type (room_type)
SELECT * FROM (SELECT paramRoomType) AS tmp
WHERE NOT EXISTS (
    SELECT room_type_id FROM room_type WHERE room_type = paramRoomType
) LIMIT 1;
SET countRow =  ROW_COUNT();

IF(countRow >  0) THEN
    SET roomTypeId =  LAST_INSERT_ID();
    INSERT hotel_has_room_type (hotel_id,room_type_id) VALUES (paramHotelId,roomTypeId);
END IF;
END
shareedit

 SELECT `desc`,create_time,
 (select count(*) from l20na_detail where o_id in(select id from l20na_orders where event_uid=14)) as total
 from l20na_orders where event_uid=14 and date=curdate();


SELECT * from l20na_orders where event_uid=14 and date=curdate()
select count(*) from l20na_detail where o_id in(select id from l20na_orders where event_uid=14);


user_register?eid=12&uid=10213412799650864&email=shihfan.tsai@gmail.com&personal_id=Sophie Tsai&accessToken=EAAEfWlUfSp8BAFUr4BzsFoPdVG89buatgu5jxOwvddm44ZAnd0CegJR6BmYF071Qx8ZAPDD989GGNZBWm2Lxq5LlZCToZCFoxDRu07hkZCUwDr6Tz0jubprnzBNqqJyOubSHVXottH6UWjB3hqeSy0BT8qvh2Q12Im4MfS80fjWr0QtKklYd5VYWWRnYSrHrZCbpFQ09jC3cQZDZD


SELECT a.id, a.status,  b.item_code, b.item_name, b.item_pic
from l20na_detail a left join
l20na_items b on a.item_code = b.item_code
where a.o_id in(select id from l20na_orders
where event_uid=8) and a.status=1

  //item_id=202&npc_id=151

select item_code, item_name from l20na_items where item_code=(select item_code from l20na_detail where id=202);

-----------+--------------+
| item_code | item_name    |
+-----------+--------------+
| 1033      | 桃溪泥人     |
+-----------+--------------+


select npc_code,npc_name,npc_gender  from l20na_npcs where npc_code=(select npc_code from l20na_npc_affections where id=151);

+----------+-----------+------------+
| npc_code | npc_name  | npc_gender |
+----------+-----------+------------+
| fangyk   | 方應看    | m          |
+----------+-----------+------------+


select response,response_text,response_voice from l20na_npcs_items where npc_code='fangyk' and item_code='1033';
| response | response_text                           | response_voice                     |
+----------+-----------------------------------------+------------------------------------+
| okla     | 禮物一般，心意還說得過去。              | gift_female_chalou_fangyingkan_022 |
+----------+-----------------------------------------+------------------------------------+


select nick_name  from event_preregister where id=(select event_uid from l20na_npc_affections where id=151);

+-------------+
| nick_name   |
+-------------+
| Kuanche Kao |
+-------------+

update l20na_npc_affections set affection=affection+(CASE WHEN @res='awesome' THEN 20 WHEN @res='okla' THEN 10 ELSE 5 END) where id=my_npc;


insert into l20na_npc_affections_log(aff_id,affection_change,item_id,note)
values(151,10,202,'你送給xxx');



l20na_npc_affections_log | CREATE TABLE `l20na_npc_affections_log` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `aff_id` int(11) NOT NULL,
 `affection_change` tinyint(4) NOT NULL DEFAULT '0',
 `item_id` tinyint(4) NOT NULL DEFAULT '0',
 `note` varchar(200) NOT NULL DEFAULT '',
 `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE KEY `npc_UNIQUE` (`aff_id`,`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8 |

ALTER TABLE l20na_npc_affections_log MODIFY COLUMN item_id int(11) DEFAULT '0';
alter table l20na_npc_affections_log

SELECT '1' AS rtn_code, @res as npc_res,@res_text as res_text,@res_vp as res_vp, affection_change, note FROM l20na_npc_affections_log WHERE id=LAST_INSERT_ID();
