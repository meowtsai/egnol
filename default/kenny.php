<?php
$memcache = new Memcache; //Star memcache
$memcache->connect('127.0.0.1', 11211) or die ("Could not connect"); //Connect Memcached
$memcache->set('uname', 'appleboy'); 
$get_value = $memcache->get('uname'); //
echo $get_value;
print "<br/>";
$b = $memcache->getStats();
print_r($b);exit;
$memcache->close();
?>
