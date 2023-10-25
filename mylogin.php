<?php

  	require("mysql.lib");

  	$usuario = $_POST['nome'];
	$senha   = $_POST['senha'];

        $strsql = sprintf("SELECT * FROM tabela_usuarios WHERE usuario_nome = '%s' AND usuario_senha = '%s'", mysql_real_escape_string($usuario) , mysql_real_escape_string($senha));
  	//$strsql = "SELECT * FROM tabela_usuarios WHERE usuario_nome = '$usuario' AND usuario_senha = '$senha'";
  	
	$user_res = mysql_query($strsql);

	if ($user_res && mysql_num_rows($user_res) > 0)
	{
    	//Se houver usuario com nome e senha 
		session_start();
    	$_sid = session_id();
    	$user_res = mysql_fetch_array($user_res);
    	$CurrentTime = time();
		
		//Renova o tempo de requisi��es feitas no site
		$strsql = "DELETE FROM tabela_sessao WHERE sessao_id = '$_sid'";
		
		if (!mysql_query($strsql))
			die(mysql_error());
		
    	$strsql = "INSERT INTO tabela_sessao (sessao_id, sessao_usuario, sessao_ip, sessao_tempo) VALUES('$_sid', '".$user_res['usuario_id']."', '".$_SERVER['REMOTE_ADDR']."', '$CurrentTime')";        
    	
		if (!mysql_query($strsql))
			die(mysql_error());
		
		//Encaminha para a �rea administrativa
		echo "1";//header("Location: area_administrativa/index.php");
		
  	}
  	else // do if if ($user_res && mysql_num_rows($user_res) > 0)
	{
		echo "0";//"Usu&aacute;rio ou senha inv&aacute;lidos!";
  	}
	
?>