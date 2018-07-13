    </div>
  </body>
</html>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script type="text/javascript">
$('.collapse').collapse()


var game_id = window.location.href.split('?')[1].split('=')[1];
if (game_id!== undefined)
{
    $( "#"+ game_id +"-tab" ).trigger( "click" );
}


//$("div[data-site*='g83tw']").hide();
//$( "a[hreflang|='en']" ).css( "border", "3px dotted green" );

//click search
$("form[class='app-search'] a").click(function() {
  console.log($("form[class='app-search'] input[type='text']").val());
  var keyword = $("form[class='app-search'] input[type='text']").val();

  $("div[class='card']").hide();
  $("div[class='card']:contains('"+ keyword +"')").show();

  //$( "div:contains('John')" )

});


//click category tab
$("ul[class='nav nav-tabs'] li[class='nav-item'] a[class*='nav-link']").click(function() {
  var site_id = $(this).attr("id").replace("-tab","");
  $("form[class='app-search'] input[type='text']").val('');
  if (site_id=='home')
  {
    $("div[class='card']").hide();
    $("div[class='card'][data-site=',']").show();
  }
  else {
    $("div[class='card']").hide();
    $("div[class='card'][data-site*='" + site_id + "']").show();
  }

});

</script>
