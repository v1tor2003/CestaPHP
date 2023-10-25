<?php
	
	$produto=$_REQUEST['hid'];

	$aux = preg_split ('/[\/]/', $produto); 

	
	$medida = $aux[0];
	$produto = $aux[1];
	$precos_id = $aux[2];
	
	$strsql =  "UPDATE tabela_precos SET medida_id = '".$medida."' WHERE precos_id = '".$precos_id."' AND produto_id = '".$produto."'"; 
	
	
	$res = mysql_query($strsql) or die(mysql_error()); 
	
	echo('Medida alterada com sucesso!');

?>