<?php

	$precos_id = $_REQUEST['precos_id'];
	$preco_produto = $_REQUEST['preco_produto'];
	$coleta_id= $_REQUEST['coleta_id'];
	$precos = array();
	$action = $_REQUEST['action'];
	$hp = $_REQUEST['hp'];
	if($action == 'del')
	{
	
		$strsql = "DELETE FROM tabela_auxiliar_precos WHERE precos_id = '".$precos_id."' AND preco_produto ='".$preco_produto."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
	}
	else
		if($action == 'save')
		{
		
			$strsql = "DELETE FROM tabela_auxiliar_precos WHERE precos_id = '".$precos_id."'";
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			$precos = preg_split ('/[\/]/', $preco_produto);
			$cont = count($precos);
			
			for($i=1;$i<$cont;$i++)
			{
				
				$strsql = "INSERT INTO tabela_auxiliar_precos (precos_id,preco_produto) VALUES ('".$precos_id."','".$precos[$i]."')";
				mysqli_query($conn, $strsql) or die(mysqli_error($conn));
			
			}
			
			
			
		}
		
	$strsql = "SELECT coleta_id,pesquisa_id FROM tabela_coletas WHERE coleta_id = ".$coleta_id;
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$res = mysqli_fetch_array($res);
	$pesquisa_id = $res['pesquisa_id'];
		
	header("Location: cadastro_coletas_precos.php?coleta_id=".$coleta_id."&pid=".$pesquisa_id."&hp=".$hp);

?>
