<?php
session_start();
$_SESSION = array();
$ex="/ex/";
header("Location:".$ex);
?>