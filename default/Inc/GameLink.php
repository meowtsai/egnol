<?php
/*後端所有遊戲裝管理列表
 * 活動管理
 * 新聞管理
 * 公告管理
 * 遊戲指南管理
 * 遊戲資料類別管理
 */
 /* All games link list - 活動公告 */
 
 if(($_SESSION['gamelink']=='m_ActivitiesList_Index') || ($_SESSION['gamelink']=='manageActivitiesList')){
        $Glist= '<table>
                  <tr>
                    <td align="left"><font size="3" face="新細明體">這裡是<b>活動管理</b>頁面，請選擇遊戲類別：</font></td>
                  </tr>
                </table>
                
                <table style="padding: 3px;">
                  <tr>
                    <td><a href="http://www.longeplay.com.tw/admin/manageActivitiesList.php"><img width="180" height="70" src="adminImg/logo.gif"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageActivitiesList.php?game=mj"><img width="180" height="70" src="adminImg/mj.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageActivitiesList.php?game=sg"><img width="180" height="70" src="adminImg/sg.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageActivitiesList.php?game=qjp"><img width="180" height="70" src="adminImg/qjp.jpg"></a></td>
                  </tr>
                  <tr>
                    <td><a href="http://www.longeplay.com.tw/admin/manageActivitiesList.php?game=sl"><img width="180" height="70" src="adminImg/sl.jpg"></a></td><!--add "sl" 2011/01/25 -->
                    <td><a href="http://www.longeplay.com.tw/admin/manageActivitiesList.php?game=ms"><img width="180" height="70" src="adminImg/ms.jpg"></a></td>
                    <td><a href="http://admin.longeplay.com.tw/?game=xj"><img width="180" height="70" src="adminImg/xj.jpg"></a></td>
                  </tr>
                </table>
                <hr>';
        echo $Glist;
 }
 /* All games link list - 新聞公告 */    
 if(($_SESSION['gamelink']=='m_NewsList_Index')||($_SESSION['gamelink']=='manageNewsList')){
         $Glist= '<table>
                  <tr>
                    <td align="left"><font size="3" face="新細明體">這裡是<b>新聞管理</b>頁面，請選擇遊戲類別：</font></td>
                  </tr>
                </table>
                
                <table style="padding: 3px;">
                  <tr>
                    <td><a href="http://www.longeplay.com.tw/admin/manageNewsList.php"><img width="180" height="70" src="adminImg/logo.gif"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageNewsList.php?game=mj"><img width="180" height="70" src="adminImg/mj.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageNewsList.php?game=sg"><img width="180" height="70" src="adminImg/sg.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageNewsList.php?game=qjp"><img width="180" height="70" src="adminImg/qjp.jpg"></a></td>
                  </tr>
                  <tr>
                    <td><a href="http://www.longeplay.com.tw/admin/manageNewsList.php?game=sl"><img width="180" height="70" src="adminImg/sl.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageNewsList.php?game=ms"><img width="180" height="70" src="adminImg/ms.jpg"></a></td>
                    <td><a href="http://admin.longeplay.com.tw/?game=xj"><img width="180" height="70" src="adminImg/xj.jpg"></a></td>
                  </tr>
                </table>
                <hr>';
         echo $Glist;
 }
 /* All games link list - 系統公告 */    
 if(($_SESSION['gamelink']=='m_SystemList_Index')||($_SESSION['gamelink']=='manageSystemList')){
       $Glist= '<table>
                  <tr>
                    <td align="left"><font size="3" face="新細明體">這裡是<b>公告管理</b>頁面，請選擇遊戲類別：</font></td>
                  </tr>
                </table>
                
                <table style="padding: 3px;">
                  <tr>
                    <td><a href="http://www.longeplay.com.tw/admin/manageSystemList.php"><img width="180" height="70" src="adminImg/logo.gif"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageSystemList.php?game=mj"><img width="180" height="70" src="adminImg/mj.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageSystemList.php?game=sg"><img width="180" height="70" src="adminImg/sg.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageSystemList.php?game=qjp"><img width="180" height="70" src="adminImg/qjp.jpg"></a></td>
                  </tr>
                  <tr>
                    <td><a href="http://www.longeplay.com.tw/admin/manageSystemList.php?game=sl"><img width="180" height="70" src="adminImg/sl.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageSystemList.php?game=ms"><img width="180" height="70" src="adminImg/ms.jpg"></a></td>
                    <td><a href="http://admin.longeplay.com.tw/?game=xj"><img width="180" height="70" src="adminImg/xj.jpg"></a></td>
                  </tr>
                </table>
                <hr>';
       echo $Glist;
 }
 /* All games link list - 遊戲指南 */    
 if(($_SESSION['gamelink']=='m_guidelist_Index')||($_SESSION['gamelink']=='manageguidelist')){
       $Glist= '<table>
                  <tr>
                    <td align="left"><font size="3" face="新細明體">這裡是<b>遊戲指南管理</b>頁面，請選擇遊戲類別：</font></td>
                  </tr>
                </table>
                
                <table style="padding: 3px;">
                  <tr>
                    <td><a href="http://www.longeplay.com.tw/admin/manageguidelist.php?game=sg_guide"><img width="180" height="70" src="adminImg/sg.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageguidelist.php?game=qjp_guide"><img width="180" height="70" src="adminImg/qjp.jpg"></a></td>
                    <td><a href="http://www.longeplay.com.tw/admin/manageguidelist.php?game=sl_guide"><img width="180" height="70" src="adminImg/sl.jpg"></a></td>
                  </tr>
                  <tr>
                    <td><a href="http://www.longeplay.com.tw/admin/manageguidelist.php?game=ms_guide"><img width="180" height="70" src="adminImg/ms.jpg"></a></td>
                    <td><a href="http://admin.longeplay.com.tw/?game=xj"><img width="180" height="70" src="adminImg/xj.jpg"></a></td>
                  </tr>
                </table>
                <hr>';
       echo $Glist;
}
     
/* All games link list - 遊戲指南類別 */
if($_SESSION['gamelink']=='manage_guide_type'){
    $Gist= '<table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr><b>請點選下列遊戲選項..</b></tr><hr/>
                <tr>
                  <td>
                    <table style="padding: 3px;">
                      <tr>
                        <td><a href="http://www.longeplay.com.tw/admin/manage_guide_type.php?game=sg_guide"><img width="180" height="70" src="adminImg/sg.jpg"></a></td>
                        <td><a href="http://www.longeplay.com.tw/admin/manage_guide_type.php?game=qjp_guide"><img width="180" height="70" src="adminImg/qjp.jpg"></a></td>
                        <td><a href="http://www.longeplay.com.tw/admin/manage_guide_type.php?game=sl_guide"><img width="180" height="70" src="adminImg/sl.jpg"></a></td>
                      </tr>
                      <tr>
                        <td><a href="http://www.longeplay.com.tw/admin/manage_guide_type.php?game=ms"><img width="180" height="70" src="adminImg/ms.jpg"></a></td>
                        <td><a href="http://admin.longeplay.com.tw/?game=xj"><img width="180" height="70" src="adminImg/xj.jpg"></a></td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                    <td colspan="3" align="right">說明：各項遊戲之【遊戲資料／遊戲指南】類別管理頁面..<hr /></td>
                </tr>     
            </table>';
    echo $Gist;
}     

/* 頁面描述訊息 - NEWS */
 if($_SESSION['describe']=='NEWS'){
     $category_id = $_SESSION['Bulletin_type'];
        if($game!='long_e'){
            require_once '../libraries/db_game.inc.php'; //會依據$_SESSION['game']的值載入 DB & Table
        }
        
        if($game == 'long_e' || $game == 'mj'){
            $query = "SELECT `ArticleID` , `title` , `ReleaseDate` , `AddedBy` FROM `articles` WHERE `CategoryID`='".$category_id."' ORDER BY `ReleaseDate` DESC";
            if($game =='long_e'){
                $Description = " 您現在的所在地是：＜龍邑官網＞後端管理介面 . .";
            }
            else{
                $Description = " 您現在的所在地是：＜魔晶幻想＞後端管理介面 . .";
            }
        }
        else if($game == 'sg' || $game == 'qjp' || $game == 'sl' || $game == 'ms'){
            $query = "SELECT `bulletin_id`, `bulletin_title`, `creat_time`, `creator` FROM `bulletin` WHERE `category_id`='".$category_id."' ORDER BY `creat_time` DESC";
            if($game == 'sg'){    
                $Description = " 您現在的所在地是：＜三國風雲＞後端管理介面 . .";
            }
            if($game == 'sl'){    
                $Description = " 您現在的所在地是：＜小李飛刀＞後端管理介面 . .";
            }
            if($game == 'qjp'){
                $Description = " 您現在的所在地是：＜千軍破＞後端管理介面 . .";
            }
            if($game == 'ms'){
                $Description = " 您現在的所在地是：＜夢之仙境＞後端管理介面 . .";
            }
            if(empty($game)){
                $Description = "";
            }
        }
        else ;

        echo '<font style="padding-top: 5px;"><center>'.$Description.'</center></font>';
 }
 
 /* 頁面描述訊息 - GUIDE */
 if($_SESSION['describe']=='GUIDE'){
    if($game != 'long_e'){
        require_once '../libraries/db_game.inc.php'; //會依據$_SESSION['game']的值載入 DB & Table
    }
    //$query = "SELECT `guide_id`, `creat_time`, `guide_title`, `creator` FROM `guide` ORDER BY `creat_time` DESC";
    
    $query = "SELECT guide.guide_id, guide.category_id, guide_type.category_title, 
            guide.guide_title, guide.creator, guide.creat_time FROM guide 
            INNER JOIN guide_type ON guide.category_id = guide_type.category_id 
            ORDER BY guide.creat_time DESC";
    
    if($game == 'sg_guide'){
        $Description = " 您現在的所在地是：＜三國風雲＞後端管理介面 . .";
    }
    if($game == 'sl_guide'){
        $Description = " 您現在的所在地是：＜小李飛刀＞後端管理介面 . .";
    }
    if($game == 'qjp_guide'){
        $Description = " 您現在的所在地是：＜千軍破＞後端管理介面 . .";
    }
    if($game == 'ms_guide'){
        $Description = " 您現在的所在地是：＜夢之仙境＞後端管理介面 . .";
    }
    if(empty($game)){
        $Description = "";
    }

 
    
    echo '<font style="padding-top: 5px;"><center>'.$Description.'</center></font>';
 }

     
     
  


?>