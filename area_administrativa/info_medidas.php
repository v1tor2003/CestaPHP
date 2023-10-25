<?php
	require("JSON.php");
	
	$medida_id = $_REQUEST['medida_id'];
	$medidas_info = array();
	
	$strsql = "SELECT * FROM tabela_unidade_medidas WHERE medida_id = '".$medida_id."'";
	
	$medidas = mysql_query($strsql) or die(mysql_error());
					
	if ($medidas && mysql_num_rows($medidas)>0)	
	{
		$json = new Services_JSON();
		
		while($row = mysql_fetch_array($medidas))
		{
			array_push($medidas_info,$row['medida_id']);
			array_push($medidas_info,$row['medida_descricao']);
			array_push($medidas_info,$row['medida_simbolo']);
		}
	}
	
	$output = $json->encode($medidas_info);
	print($output);
?>