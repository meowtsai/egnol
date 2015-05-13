<?php
/**
*
* GET ADS_INFO
* 
*/

require_once '../global.php';
//require_once('Inc/init.php');
//require_once('admin/library/init.php');
require_once('libraries/db.inc.php');

#if url="http://www.long_e.com.tw/ImageInfo.php" or "http://www.long_e.com.tw/ImageInfo.php?adsBanner="
#return long_e index..
    if($_GET['adsBanner'] == ''){
        echo "<script>window.location.href='index.php'</script>";
    }

###### Get an string( ads_info ) --- START --- #####
#result : 1. array to all string = { $ads_str = json_encode($ads) , $mj = json_encode($ads['mj']) , $sg = json_encode($ads['sg']) , $qjp = json_encode($ads['qjp']); }
#         2. get array rows = { $mjNo=count($ads['mj']) , $sgNo=count($ads['sg']) , $qjpNo=count($ads['qjp']); }    
    
    #Get ads_info by $_GET['adsBanner']
    if($_GET['adsBanner'] != ''){
        $game = $_GET['adsBanner'];        
        $query = "SELECT `img_path`,`img_link`,`img_alt` FROM `ads_control` WHERE `server_id`='".mysql_real_escape_string($game)."' AND `enable`='1' ORDER BY `priority` ASC";
                
        $result = mysql_query($query);
        if(mysql_num_rows($result)){
            while($row = mysql_fetch_assoc($result)){
                $ads[$game][] = $row;
            }
        }
        $ads[$game]['count']=count($ads[$game]);
        $ads[$game]['db']=$game;
        $ads_data = json_encode($ads[$game]);
        echo $ads_data;        
    }
   
    
###### Get an string( ads_info ) ---  END  --- #####

   
?>
