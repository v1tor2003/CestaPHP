<?php
	$situacao = $_REQUEST['situacao'];
	$coleta_id = $_REQUEST['hid'];
	$media = 0;
	$total_coleta = 0;
	$prod_oficial = 0;
	$prod_n_oficial = 0;
	
	if($situacao == 1)
	{
	
		/**************************************************************************************************************
		*	
		*	Ao fechar uma coleta s�o atualizados os campos precos_media_observado,precos_media e precos_total da 
		*   tabela_precos de cada produto referente a mesma. 
		*	1 = produtos da cesta 	0 = produtos que n�o comp�em a cesta
		*   
		**************************************************************************************************************/
		
		$prod_oficial = atualiza_coletas($coleta_id,1);	
		$prod_n_oficial = atualiza_coletas($coleta_id,0);		
	}
	
	$total_coleta = $prod_oficial + $prod_n_oficial;
	
	
	
	//echo($prod_oficial);
	/**************************************************************************************************************
	*	
	*	A tabela_coletas � atualizada para 1(fechada) ou 0(aberta). E tamb�m o campo coleta_preco_cesta � 
	*   atualizado.
	*   
	**************************************************************************************************************/
	$strsql = "UPDATE tabela_coletas SET coleta_fechada = '".$situacao."',coleta_preco_cesta ='".$total_coleta."' WHERE coleta_id = '".$coleta_id."'";
	
	//die($strsql);
	mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	echo('');
?>
