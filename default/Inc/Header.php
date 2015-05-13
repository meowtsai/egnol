<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<link href="/classcss/TPC_e-newsStyle.css" rel="stylesheet" type="text/css" />
<link href="/classcss/stander.css" rel="stylesheet" type="text/css" />
    <title></title>
</head>

<body>
<!-- 功能列表 主區塊-->
<div class="TPC_e-newsContainer">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="20">
   </td>
    </tr>
    <tr>
      <td align="left" valign="top">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="167" align="left" valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="10">　</td>
              <td width="157" align="left" valign="top">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" valign="top"><img src="/img/tpc_e-newsicon007.jpg" name="tpc_e-newsicon007" id="tpc_e-newsicon007" /></td>
                </tr>
                <tr>
                  <td class="TPC_e-newsMenuBg02" align="left" valign="top">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10"></td>
                      <td align="left" valign="top">
                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php

			  //$query = "select ID,URL,Title,Description,sortorder from x_sitemap where ParentId=0 and visible=1  ORDER BY SortOrder asc";
              $query = "SELECT `ID`,`URL`,`Title`,`Description`,`sortorder` FROM `x_sitemap` WHERE `ParentId`='0' AND `visible`='1' ORDER BY `SortOrder` ASC";
	          $result = mysql_query($query);
                while ($row = mysql_fetch_array($result, MYSQL_BOTH)) 
                {
				 $parentid=$row["ID"];
?>
                                                 <tr>
                         <td height="10"></td>
                        </tr>
                        <tr>
                          <td align="left" valign="top">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="12">
								<img src="/img/tpc_e-newsicon045.jpg" name="tpc_e-newsicon111" id="tpc_e-newsicon111" /></td>
                              <td width="5"></td>
                              <td align="left" valign="top" class="TPC_e-newsMenuText">
									 <?php
                                     /** 大標題 **/
									 echo $row["Title"];
						 ?></td>
                            </tr>
                           </table>                          </td>
                        </tr>
                        <!-- 功能列表 子區塊 -->
                        <tr>
                          <td align="left" valign="top">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="12">　</td>
                              <td width="111" align="left" valign="top">
                              
                               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left" valign="top">
									<img src="/img/tpc_e-newsicon079.jpg" name="tpc_e-newsicon079" id="tpc_e-newsicon079" /></td>
                                </tr>
                                <tr>
                               <td class="TPC_e-newsListBg02" align="center" valign="top"><ul>
<?php
                $query1 = "SELECT ID,URL,Title,Description,sortorder FROM x_sitemap WHERE (ParentId=". $parentid.") AND (visible=1) AND (`Roles` LIKE '%$".$_SESSION['rolename_admin']."$%') ORDER BY SortOrder ASC";
	            $result1 = mysql_query($query1);
                if(mysql_num_rows($result1) == 0) echo '<li style="font-size:8px; color:gray;">無可存取之功能</li>';
                while ($row1 = mysql_fetch_array($result1, MYSQL_BOTH)) 
                {
				 	 echo  "<li><a href=\"".$row1["URL"]. "\">".$row1["Title"]. "</a></li>";
                }
?>                               
                                   </ul>
                                   </td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top">
									<img src="/img/tpc_e-newsicon086.jpg" alt="" name="tpc_e-newsicon110" width="111" height="9" border="0" id="tpc_e-newsicon110" /></td>
                                </tr>
                               </table> 
                               <!-- 功能列表 子區塊結束 -->                             </td>
                              <td style="width: 19px">　</td>
                            </tr>
                           </table>                          </td>
                        </tr>
<?php
}
?>    
                                                </table>
                      </td>
                      <td width="10"></td>
                    </tr>
                   </table>
                  </td>
                </tr>
                <tr>
                  <td>
					<img src="/img/tpc_e-newsicon088.jpg" alt="" name="tpc_e-newsicon088" width="157" height="22" border="0" id="tpc_e-newsicon088" /></td>
                </tr>
               </table>
              </td>
              <td width="10">　</td>
            </tr>
           </table>
          </td>
          <!--功能列表 主區塊結束 -->
          <!-- 中間區塊 上列橫幅 -->
            <td width="10" class="TPC_e-newsLine">　</td>
          <td align="left" valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <!-- 插入龍邑log -
            <td align="center"><img src="/img/logo.gif" alt="" name="logo" width="550" height="60" border="0" id="logo" /></td>
            <tr>  <td height="12"></td>   </tr>
            -->
            <tr>
              <!-- <td width="5">　</td> -->
              <td align="left" valign="top">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" valign="top">      
                   <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                      <td width="21"><img src="/img/tpc_e-newsicon009.jpg" alt="" name="tpc_e-newsicon010" width="26" height="28" border="0" id="tpc_e-newsicon010" /></td>
                      <td class="TPC_e-newsTitleBg02" align="left" valign="top">&nbsp;</td>
                      <td width="21"><img src="/img/tpc_e-newsicon013.jpg" alt="" name="tpc_e-newsicon014" width="26" height="28" border="0" id="tpc_e-newsicon014" /></td>
                    </tr>
                   </table>
                  </td>
                </tr>

                <tr>       
                  <td height="6"></td>
                </tr>
                <!-- 中間表格區塊 -->
                <tr>
                  <td align="left" valign="top">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="21">
				<img src="/img/tpc_e-newsicon020.jpg" name="tpc_e-newsicon021" id="tpc_e-newsicon021" width="21" height="21" /></td>
              <td class="TPC_e-newsContentBg01">　</td>
              <td width="21"><img src="/img/tpc_e-newsicon025.jpg" name="tpc_e-newsicon025" id="tpc_e-newsicon025" /></td>
            </tr>

                <tr>
                    <td class="TPC_e-newsContentBg04">
                    </td>
                    <td bgcolor="#FFFFFF" align="left" valign="top">