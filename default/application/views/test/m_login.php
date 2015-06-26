<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>Test Game</title>
    <script type="text/javascript" src="http://www.cooz.com.tw/p/js/jquery-1.7.2.min.js?8"></script>
    <script type="text/javascript">

        // When the document has loaded, add an iFrame.
        $(
            function(){
                var jFrame = $( "<iframe name='CoozSDK' src='<?=base_url();?>/api/m_login_form?partner=tenone&game=eya&time=1321009871&hash=c996020f8285123946cc368c18c1150a&imei=356708042558351&redirect_url=http%3A%2F%2F203.75.245.16%2Fapi%2Fm_get_euid%3Fcode%3Dc996020f8285123946cc368c18c1150a'>" );

                // Set frame properties and add it to the body.
                jFrame
                    .css( "width", "320px" )
                    .css( "height", "568px" )
                    .appendTo( $( "body" ) )
                ;
            }
            );

        function receiveEuid(euid, code, token, channel) {
			//if (c=='m_facebook') window.location='http://203.75.245.16//gate/login/long_e?channel=facebook&amp;ad=&amp;redirect_url=http%3A%2F%2F203.75.245.16%2Ftest%2Fm_playing&amp;';
			if (channel=='m_facebook') window.location='<?=base_url();?>/gate/login/long_e?channel=facebook&ad=&redirect_url=<?=base_url();?>/test/m_playing?euid='+euid+'&token='+token+'&channel='+channel;
			alert('Game Start! euid='+euid+'&token='+token+'&channel='+channel);
		}
    </script>
</head>
<body>
 
    <h1>
        SDK
    </h1>
 
    <p>
        An iFrame will be added below this:
    </p>
	
 
</body>
</html>