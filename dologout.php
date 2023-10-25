<?php
	
	require("libs/mysql.lib");
	
	session_start();
	
	$_sid = session_id();
	
	$strsql = "DELETE FROM tabela_sessao WHERE sessao_id = '$_sid'";
	
	if (!mysql_query($strsql)) die(mysql_error());
		session_destroy();
  
  	header("Location: index.php");
?>