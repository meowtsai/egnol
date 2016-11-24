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
  `deposit_total` float DEFAULT NULL,
  `consume_total` float DEFAULT NULL,
  `peak_user_count` int(11) DEFAULT NULL,
  `total_time` int(11) DEFAULT NULL,
  `paid_total_time` int(11) DEFAULT NULL,
  `deposit_login_count` int(11) DEFAULT NULL,
  `new_deposit_login_count` int(11) DEFAULT NULL,
  `new_user_deposit_count` int(11) DEFAULT NULL,
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
  `new_user_deposit_count` int(11) DEFAULT NULL,
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
  `new_user_deposit_count` int(11) DEFAULT NULL,
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
  `transaction_id` int(11) DEFAULT NULL,
  `partner_order_id` varchar(100) DEFAULT NULL,
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
  `mycard_billing_id` int(11) DEFAULT NULL,
  `vip_ticket_id` int(11) DEFAULT NULL,
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
