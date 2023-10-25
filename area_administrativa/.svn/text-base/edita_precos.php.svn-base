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
		mysql_query($strsql) or die(mysql_error());	
	}
	else
		if($action == 'save')
		{
		
			$strsql = "DELETE FROM tabela_auxiliar_precos WHERE precos_id = '".$precos_id."'";
			mysql_query($strsql) or die(mysql_error());	
			
			$precos = preg_split ('/[\/]/', $preco_produto);
			$cont = count($precos);
			
			for($i=1;$i<$cont;$i++)
			{
				
				$strsql = "INSERT INTO tabela_auxiliar_precos (precos_id,preco_produto) VALUES ('".$precos_id."','".$precos[$i]."')";
				mysql_query($strsql) or die(mysql_error());
			
			}
			
			
			
		}
		
	$strsql = "SELECT coleta_id,pesquisa_id FROM tabela_coletas WHERE coleta_id = ".$coleta_id;
	$res = mysql_query($strsql) or die(mysql_error());
	$res = mysql_fetch_array($res);
	$pesquisa_id = $res['pesquisa_id'];
		
	header("Location: cadastro_coletas_precos.php?coleta_id=".$coleta_id."&pid=".$pesquisa_id."&hp=".$hp);

?>
