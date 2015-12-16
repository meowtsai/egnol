<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2015-06-16 00:23:46 --> 404 Page Not Found --> cron_bundle
ERROR - 2015-06-16 00:24:15 --> Severity: 8192  --> mysql_connect(): The mysql extension is deprecated and will be removed in the future: use mysqli or PDO instead /var/www/egnol/ci_system/2.1.2/database/drivers/mysql/mysql_driver.php 73
ERROR - 2015-06-16 00:24:15 --> Query: 
SELECT game_id, COUNT(uid) 'login_cnt', SUM(role) 'role_cnt'
FROM 
(
    SELECT lgl.uid, lgl.game_id, lgl.server_id,
        (SELECT IF(COUNT(id)>0, 1, 0) FROM characters
		    JOIN servers ON characters.server_id = servers.server_id
            WHERE characters.uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND characters.create_time >= lgl.create_time
        ) 'role'
FROM
log_game_logins lgl
WHERE DATE(lgl.create_time) = '2015-06-15'
AND lgl.is_first = 1
GROUP BY lgl.uid, lgl.game_id
) tmp
GROUP BY game_id
ERROR - 2015-06-16 00:24:15 --> Query error: Column 'id' in field list is ambiguous
ERROR - 2015-06-16 00:35:00 --> Severity: 8192  --> mysql_connect(): The mysql extension is deprecated and will be removed in the future: use mysqli or PDO instead /var/www/egnol/ci_system/2.1.2/database/drivers/mysql/mysql_driver.php 73
ERROR - 2015-06-16 00:35:02 --> Query: 
SELECT game_id, COUNT(uid) 'login_cnt', SUM(role) 'role_cnt', 
	SUM(retention) 'retention'
FROM 
(
    SELECT lgl.uid, lgl.game_id, lgl.server_id,
        (SELECT IF(COUNT(id)>0, 1, 0) FROM characters
		    JOIN servers ON characters.server_id = servers.server_id
            WHERE characters.uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND characters.create_time >= lgl.create_time
        ) 'role',
        (SELECT IF(COUNT(id)>0, 1, 0) FROM log_game_logins
		    JOIN servers ON log_game_logins.server_id = servers.server_id
            WHERE uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND DATE(create_time) = DATE_ADD(DATE(lgl.create_time), interval 1 day)
        ) 'retention'
FROM
log_game_logins lgl
WHERE DATE(lgl.create_time) = '2015-06-15'
AND lgl.is_first = 1
GROUP BY lgl.uid, lgl.game_id
) tmp
GROUP BY game_id
ERROR - 2015-06-16 00:35:02 --> Query error: Column 'id' in field list is ambiguous
ERROR - 2015-06-16 00:35:02 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /var/www/egnol/default/admin3/application/controllers/cron.php:53) /var/www/egnol/ci_system/2.1.2/core/Common.php 447
ERROR - 2015-06-16 00:35:42 --> Severity: 8192  --> mysql_connect(): The mysql extension is deprecated and will be removed in the future: use mysqli or PDO instead /var/www/egnol/ci_system/2.1.2/database/drivers/mysql/mysql_driver.php 73
ERROR - 2015-06-16 00:35:44 --> Query: 
SELECT game_id, COUNT(uid) 'login_cnt', SUM(role) 'role_cnt', 
	SUM(retention) 'retention'
FROM 
(
    SELECT lgl.uid, lgl.game_id, lgl.server_id,
        (SELECT IF(COUNT(id)>0, 1, 0) FROM characters
		    JOIN servers ON characters.server_id = servers.server_id
            WHERE characters.uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND characters.create_time >= lgl.create_time
        ) 'role',
        (SELECT IF(COUNT(id)>0, 1, 0) FROM log_game_logins
		    JOIN servers ON log_game_logins.server_id = servers.server_id
            WHERE uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND DATE(create_time) = DATE_ADD(DATE(lgl.create_time), interval 1 day)
        ) 'retention'
FROM
log_game_logins lgl
WHERE DATE(lgl.create_time) = '2015-06-15'
AND lgl.is_first = 1
GROUP BY lgl.uid, lgl.game_id
) tmp
GROUP BY game_id
ERROR - 2015-06-16 00:35:44 --> Query error: Column 'id' in field list is ambiguous
ERROR - 2015-06-16 00:35:44 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /var/www/egnol/default/admin3/application/controllers/cron.php:53) /var/www/egnol/ci_system/2.1.2/core/Common.php 447
ERROR - 2015-06-16 21:40:04 --> Severity: 8192  --> mysql_connect(): The mysql extension is deprecated and will be removed in the future: use mysqli or PDO instead /var/www/egnol/ci_system/2.1.2/database/drivers/mysql/mysql_driver.php 73
ERROR - 2015-06-16 21:40:48 --> Severity: 8192  --> mysql_connect(): The mysql extension is deprecated and will be removed in the future: use mysqli or PDO instead /var/www/egnol/ci_system/2.1.2/database/drivers/mysql/mysql_driver.php 73
ERROR - 2015-06-16 21:41:47 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:47 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:41:51 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:42:08 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:42:10 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:42:10 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:42:15 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:42:19 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:42:19 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:42:36 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
ERROR - 2015-06-16 21:42:52 --> Severity: Notice  --> geoip_country_code3_by_name(): Host 0.0.0.0 not found /var/www/egnol/default/admin3/application/controllers/testdata.php 97
