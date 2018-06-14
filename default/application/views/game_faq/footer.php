    </div>
  </body>
</html>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script type="text/javascript">
$('.collapse').collapse()
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
