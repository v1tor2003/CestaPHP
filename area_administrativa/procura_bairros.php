<?php
	require("JSON.php");
	
	$cidade_id = $_REQUEST['hid'];
	$bairros_info = array();
	
	$strsql = "SELECT * FROM tabela_bairros WHERE cidade_id = '".$cidade_id."'";
	
	$bairros = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
	if ($bairros && mysqli_num_rows($bairros)>0)	
	{
		$json = new Services_JSON();
		
		while($row = mysqli_fetch_array($bairros))
		{
			array_push($bairros_info,$row['bairro_id']);
			array_push($bairros_info,$row['bairro_nome']);
		}
	}
	
	$output = $json->encode($bairros_info);
	print($output);
	
	
?>