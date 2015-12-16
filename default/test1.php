<?php
session_start();

$_SESSION['test'] = 'testing!';
header("location:http://203.75.245.16/test2.php");
exit();
?>