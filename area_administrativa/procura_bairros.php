<?php
	require("JSON.php");
	
	$cidade_id = $_REQUEST['hid'];
	$bairros_info = array();
	
	$strsql = "SELECT * FROM tabela_bairros WHERE cidade_id = '".$cidade_id."'";
	
	$bairros = mysql_query($strsql) or die(mysql_error());
					
	if ($bairros && mysql_num_rows($bairros)>0)	
	{
		$json = new Services_JSON();
		
		while($row = mysql_fetch_array($bairros))
		{
			array_push($bairros_info,$row['bairro_id']);
			array_push($bairros_info,$row['bairro_nome']);
		}
	}
	
	$output = $json->encode($bairros_info);
	print($output);
	
	
?>