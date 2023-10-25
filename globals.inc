<?php
error_reporting (E_ALL & ~E_NOTICE);
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
  require("libs/mysql.lib");
  require("libs/funcoes.lib");
  $PathRedirect = "area_administrativa/index.php";
  require('libs/logged_sessions.lib');
?>