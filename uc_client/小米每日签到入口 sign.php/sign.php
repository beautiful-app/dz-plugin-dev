<?php
$mod = isset($_GET['mod']) ? addslashes($_GET['mod']) : 'sign';
$_GET['id'] = "k_misign:".$mod;
require "plugin.php";
?>