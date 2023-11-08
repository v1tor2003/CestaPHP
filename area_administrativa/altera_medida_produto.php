<?php
	
	$produto=$_REQUEST['hid'];

	$aux = preg_split ('/[\/]/', $produto); 

	
	$medida = $aux[0];
	$produto = $aux[1];
	$precos_id = $aux[2];
	
	$strsql =  "UPDATE tabela_precos SET medida_id = '".$medida."' WHERE precos_id = '".$precos_id."' AND produto_id = '".$produto."'"; 
	
	
	$res = mysqli_query($conn,$strsql) or die(mysqli_error($conn)); 
	
	echo('Medida alterada com sucesso!');

?>