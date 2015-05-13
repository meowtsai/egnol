<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>test</title>
        <meta name="description" content="" />
        <script type="text/javascript"  src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="/p/register/sl2/js/swfobject.js"></script> 
        
        <script type="text/javascript">
        function onMovie(obj){
		var flashvars = {};
		var params = {menu: "false",scale: "noScale",allowFullscreen: "true",allowScriptAccess: "always",bgcolor: "",wmode: "direct" };
		var attributes = {id:"dragonclient"};
		swfobject.embedSWF(obj, "altContent", "100%", "100%", "10.0.0", "expressInstall.swf",flashvars, params, attributes);}
        </script>
        
        <script type="text/javascript">
            function Recharge(_rid, _e)
            {
                var rid = document.getElementById("username");
                var e = document.getElementById("money");
                rid.value = _rid;
                e.value = _e;
                document.getElementById("submit").click();
            }
            function game_reload()
            {
                location.reload();
                //history.go(0);
            }
        </script>

        <style>
            html, body { height:100%;width:100%; overflow:hidden;background-color:#3366cc;}
            body { margin:0; }
            .ct{float:left;width:100%;height:100%;margin:auto;padding:0px;background-color:#00ff00;text-align:center}
            .port {width:300px;height:100px;margin:auto;margin-top:250px;background-color:#ff0000;color:#ffff00;text-align:center}
            p{font-size:10px;}
        </style>
    </head>

    <body>
        <div id="mian" class="ct">
            <div id="altContent" style=""></div>
            <form  id="form1" name="alipayment"  action="/acc/phpserver/alipayto.php" method="post" target="_blank">
                <input id="username" type="hidden" name=username  value=""/>
                <input id="money" type="hidden"  name=money  value=""/>
                <button style="display:none" id="submit" type="submit"> </button>
            </form>
        </div>
        <div style="display:none;">
            <!-- dayoxi.com Baidu tongji analytics -->
            <script type="text/javascript" src="http://tajs.qq.com/stats?sId=21591533" charset="UTF-8"></script>
            <script type="text/javascript">
                var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
                document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F0a220e3fb4b47221718ec974ba4b965e' type='text/javascript'%3E%3C/script%3E"));
            </script>
        </div>
    </body>
	<?
		$time = time();
		$username = $user->euid;
		$flag = md5($username.$ad.$time.$key);
	?>
    <script  type="text/javascript">
        var ip = "<?=$server->address?>";
        var port = "4901";
        var clientPath = "http://<?=$server->address?>/lxf/lxf/";//"http://192.168.11.12/";//
        var resourcePath = "http://<?=$server->address?>/lxf/lxf/";//"http://192.168.11.12";//
        var key = "username=<?=$username?>|ad=<?=$ad?>|time=<?=$time?>|flag=<?=$flag?>";
        if ((key == null) || (key == ''))
        {
            location.replace("https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=100345016&redirect_uri=http://www.dayoxi.com/acc/phpserver/qq1.php&state=test");
	   		exit();
        }
        else
        {
            var obj = clientPath + "dragonclient.swf?version=" + Math.random() + "&&ip=" + ip + "&&path=" + resourcePath + "&&port=" + port + "&&islog=1&&areaId=1&&versionflag=2&&sessionKey=" + key;
            onMovie(obj);
        }
    </script>
</html>