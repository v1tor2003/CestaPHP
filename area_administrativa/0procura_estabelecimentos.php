<?php

	require("JSON.php");
	
	$hid = $_REQUEST['hid'];
	$aux = split ('[/]', $hid);
	$cidade_id = $aux[0];
	$pesquisa_id = $aux[1];
	
	$estabelecimentos_info = array();

	$strsql = "SELECT * FROM tabela_estabelecimentos A,tabela_cidades B,tabela_bairros C WHERE (C.cidade_id = B.cidade_id AND A.bairro_id = C.bairro_id) AND C.cidade_id = '".$cidade_id."' AND A.estabelecimento_id NOT IN (SELECT estabelecimento_id FROM tabela_coletas WHERE pesquisa_id = '".$pesquisa_id."')";
	
	$estabelecimentos = mysql_query($strsql) or die(mysql_error());
					
	if ($estabelecimentos && mysql_num_rows($estabelecimentos)>0)	
	{
		while($row = mysql_fetch_array($estabelecimentos))
		{
		
			$json = new Services_JSON();
		
			array_push($estabelecimentos_info,$row['estabelecimento_id']);
			array_push($estabelecimentos_info,$row['estabelecimento_nome']." (".$row['bairro_nome'].")");
		}
		
	}
	
	$output = $json->encode($estabelecimentos_info);
	print($output);
?>