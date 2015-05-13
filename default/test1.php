<?php
session_start();

$_SESSION['test'] = 'testing!';
header("location:http://192.168.56.101/test2.php");
exit();
?>