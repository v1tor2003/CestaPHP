<?php
	
	require("./libs/mysql.lib");
	
	session_start();
	
	$_sid = session_id();
	
	$strsql = "DELETE FROM tabela_sessao WHERE sessao_id = '$_sid'";
	
	if (!mysqli_query($conn, $strsql)) die(mysqli_error($conn));
		session_destroy();
  
  	header("Location: index.php");
?>