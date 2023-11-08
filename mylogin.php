<?php

  	require './libs/mysql.lib';

  	$usuario = $_POST['nome'];
	$senha   = $_POST['senha'];

        $strsql = sprintf("SELECT * FROM tabela_usuarios WHERE usuario_nome = '%s' AND usuario_senha = '%s'", mysqli_real_escape_string($conn, $usuario) , mysqli_real_escape_string($conn, $senha));
  	//$strsql = "SELECT * FROM tabela_usuarios WHERE usuario_nome = '$usuario' AND usuario_senha = '$senha'";
  	
	$user_res = mysqli_query($conn, $strsql);

	if ($user_res && mysqli_num_rows($user_res) > 0)
	{
    	//Se houver usuario com nome e senha 
		session_start();
    	$_sid = session_id();
    	$user_res = mysqli_fetch_array($user_res);
    	$CurrentTime = time();
		
		//Renova o tempo de requisi��es feitas no site
		$strsql = "DELETE FROM tabela_sessao WHERE sessao_id = '$_sid'";
		
		if (!mysqli_query($conn, $strsql))
			die(mysqli_error($conn));
		
    	$strsql = "INSERT INTO tabela_sessao (sessao_id, sessao_usuario, sessao_ip, sessao_tempo) VALUES('$_sid', '".$user_res['usuario_id']."', '".$_SERVER['REMOTE_ADDR']."', '$CurrentTime')";        
    	
		if (!mysqli_query($conn, $strsql))
			die(mysqli_error($conn));
		
		//Encaminha para a �rea administrativa
		echo "1";//header("Location: area_administrativa/index.php");
		
  	}
  	else // do if if ($user_res && mysqli_num_rows($user_res) > 0)
	{
		echo "0";//"Usu&aacute;rio ou senha inv&aacute;lidos!";
  	}
	
?>