<div id="content-login">
	<div class="login-ins">
		<div class="login_box">
			<div class="login_member">
				<div class="login_info">
                    <div class="hd" >
                    </div>
                    <div class="bd" >
                        <div style="font-size:18px; color:#222; font-weight:bold;">
                            <?=$row->title?>
                            <span style="font-size:13px; color:#191;"><?=date("Y-m-d", strtotime($row->start_time))?></span>
                        </div>
                        <div class="fixck">
                            <?=$row->content?>
                        </div>
                    </div>
                    <div class="ft">
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	(function($){
		$(window).load(function(){
			$(".scrollbar").mCustomScrollbar();
		});
	})(jQuery);
</script>
