<?php
		
	// CUSTO POR TEMPO DE TRABALHO
	// GASTO ( CUSTO DA CESTA COM AKELE ITEM , ITEM * QUANTIDADE)/SALÁRIO_MINIMO * (220	)
	// $file = file_get_contents('php://input');
	$json = null;
	$arr = array();
	$arr2 = array("1","2");

	if (isset($_POST["data"])):

		// TESTE PARA API
		// array_push($arr, $_POST["data"]);
		// array_push($arr, $_POST["data2"]);
		// array_push($arr, $_POST["gasto"]);
		// array_push($arr, $_POST["mes"]);
		// array_push($arr, $_POST["cidade"]);
		// array_push($arr, json_decode($_POST["itens"]));
		// echo json_encode($arr);

		$SEARCH_TYPE = $_POST["gasto"];
		$PRODUCTS = json_decode($_POST["itens"], true);
		$PRODUCTS = array_values($PRODUCTS);
		$CITY = $_POST["cidade"];
		$DATE_START = $_POST["data"];
		$DATE_END = $_POST["data2"];
		$MONTH_INTERVAL = $_POST["mes"];
		$DISPLAY_TYPE = $_POST["tipo"];

		require_once "data.php";
		// MUDAR LOGIN E SENHA

		$HostName = "localhost";

		$DatabaseName = "cesta_basica";

		$HostUser = "accb";

		$HostPass = "zDvD3jdcEZ3YRyhC";

		$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

		if ($conn->connect_error) {

		    die("Connection failed: " . $conn->connect_error);
		}

		// DATE PROCESS

			$f_start = explode("/", $DATE_START);
			$f_end = explode("/", $DATE_END);

			$data_inicio = "'".$f_start[1].'/'.$f_start[0]."/00'";
			$data_final = $f_end[1].'/'.$f_end[0];
			$datas_possiveis = array($data_inicio);


			$m = $f_start[0] + $MONTH_INTERVAL;
			$a = $f_start[1];
			if($m>12){
				$m= $m-12;
				$a+=1;
			}

			$df = new Data(1,$f_end[0],$f_end[1]);
			
			// LAÇO PARA EXTRAIR AS POSSIVEIS DATAS
			// LÓGICA : TENDO O MES ANO E INTERVALO D EXTRAÇÃO DE DATA , PEGAMOS E COMPARAMOS SE A DIFERENÇA ENTRE A DATA INICIAL E A FINAL EM SEGUNDOS É MENOR QUE 0 OU SEJA , NÃO ULTRAPASSAMOS O LIMITE DA DATA, CASO SEJA VERDADE É SALVO A DATA ATUAL EM UM ARRAY DE POSSIBILIDADES COM O FORMATO ANO/MES/DIA

			for( $i = 1; Data::diff(new Data(1,$m,$a), $df) <= 0; $i++){
				
				$datas_possiveis[$i] = "'$a/$m/00'";
				$m += $MONTH_INTERVAL;

				if($m>12){

					$m = $m-12;
					$a += 1;

				}

			}


		// DATE PROCESS


		if ($SEARCH_TYPE != "cesta_custo_total") {

			$sql = "
			SELECT PRP.".$SEARCH_TYPE.", 
			EXTRACT(MONTH FROM P.pesquisa_data) AS mes, 
			EXTRACT(YEAR FROM P.pesquisa_data) AS ano , 
			C.cidade_id , C.cidade_nome , 
			tabela_produtos.produto_nome_visualizacao 
			as produto_nome
			FROM tabela_pesquisa_resultados_produtos PRP 
			NATURAL JOIN tabela_pesquisas P 
			NATURAL JOIN tabela_produtos 
			INNER JOIN tabela_cidades C on PRP.cidade_id = C.cidade_id 
			WHERE PRP.produto_id IN (".implode(',',$PRODUCTS).")
			AND PRP.cidade_id IN ('$CITY') 
			AND P.pesquisa_data IN (".implode(',',$datas_possiveis).")
			ORDER BY mes,ano";

		}else{

			$sql = "
			SELECT P.pesquisa_id, EXTRACT(MONTH FROM P.pesquisa_data) 
			AS mes, EXTRACT(YEAR FROM P.pesquisa_data)
		    AS ano  ,TPC.gasto_mensal_cesta, C.cidade_id, C.cidade_nome
			FROM tabela_pesquisas_cidades TPC 
			NATURAL JOIN tabela_pesquisas P
			INNER JOIN tabela_cidades C 
			ON C.cidade_id = TPC.cidade_id
			WHERE TPC.cidade_id 
			IN ('$CITY') AND 
			P.pesquisa_data"." IN (".implode(',',$datas_possiveis).")
			ORDER BY C.cidade_nome, P.pesquisa_data";

		}

		$result = $conn->query($sql);
		$FINAL_INFO = array();
		$COUNT = 0;

		if ($result->num_rows > 0) {
		    while ($row[] = $result->fetch_assoc()) {

	    		$row[$COUNT]["preco"] = $row[$COUNT][$SEARCH_TYPE];
				unset($row[$COUNT][$SEARCH_TYPE]);

				if($DISPLAY_TYPE != 2):

					if ($SEARCH_TYPE != 'cesta_custo_total') {

				    	if ($CITY == 2) {
				    		$row[$COUNT]["cidade_nome"] = utf8_encode($row[$COUNT]["cidade_nome"]);
				    	}
				    	$row[$COUNT]["produto_nome"] = utf8_encode($row[$COUNT]["produto_nome"]);
				    	if (isset($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]])) {
				    		array_push($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]], $row[$COUNT]);
				    	}else{
				    		$FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]] = array();
				    		array_push($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]], $row[$COUNT]);
				    	}
				    	$COUNT++;

			    	}else{
		    				$row[$COUNT]["produto_nome"] = utf8_encode($row[$COUNT]["produto_nome"]);
					    	if (isset($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]])) {
					    		array_push($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]], $row[$COUNT]);
					    	}else{
					    		$FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]] = null;
					    		array_push($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]], $row[$COUNT]);
					    	}
					    	$COUNT++;

			    	}

			    else:

    				if ($SEARCH_TYPE != 'cesta_custo_total') {

				    	if ($CITY == 2) {
				    		$row[$COUNT]["cidade_nome"] = utf8_encode($row[$COUNT]["cidade_nome"]);
				    	}
				    	$row[$COUNT]["produto_nome"] = utf8_encode($row[$COUNT]["produto_nome"]);
				    	if (isset($FINAL_INFO[$row[$COUNT]["produto_nome"]])) {
				    		array_push($FINAL_INFO[$row[$COUNT]["produto_nome"]], $row[$COUNT]);

				    	}else{
				    		$FINAL_INFO[$row[$COUNT]["produto_nome"]] = array();
				    		array_push($FINAL_INFO[$row[$COUNT]["produto_nome"]], $row[$COUNT]);
				    	}

				    	$COUNT++;

			    	}else{

	    				if ($CITY == 2) {
				    		$row[$COUNT]["cidade_nome"] = utf8_encode($row[$COUNT]["cidade_nome"]);
				    	}
				    	$row[$COUNT]["produto_nome"] = utf8_encode($row[$COUNT]["produto_nome"]);
				    	if (isset($FINAL_INFO[$row[$COUNT]["produto_nome"]])) {
				    		array_push($FINAL_INFO[$row[$COUNT]["produto_nome"]], $row[$COUNT]);
				    	}else{
				    		$FINAL_INFO[$row[$COUNT]["produto_nome"]] = array();
				    		array_push($FINAL_INFO[$row[$COUNT]["produto_nome"]], $row[$COUNT]);
				    	}
				    	$COUNT++;

			    	}

				endif;

		    }
		    
		} else {
		    echo json_encode($arr2);
		}

		$conn->close();
		$json = json_encode($FINAL_INFO, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		echo $json;
		die;

	else:

			//TESTE DIRETO PELO NAVEGADOR
			$prod = json_decode('{"a":3,"b":1}',true);
			$PRODUCTS = array_values($prod);
			// $PRODUCTS = array(1, 2, 3);
			$SEARCH_TYPE = 'produto_preco_medio';
			$CITY = 2;
			$DATE_START = '06/2004';
			$DATE_END = '01/2005';
			$MONTH_INTERVAL = 1;
			$DISPLAY_TYPE = 2;

			require_once "data.php";
			// MUDAR LOGIN E SENHA
			$HostName = "localhost";

			$DatabaseName = "cesta_basica";

			$HostUser = "accb";

			$HostPass = "zDvD3jdcEZ3YRyhC";

			$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

			if ($conn->connect_error) {

			    die("Connection failed: " . $conn->connect_error);
			}

			
			$f_start = explode("/", $DATE_START);
			$f_end = explode("/", $DATE_END);

			$data_inicio = "'".$f_start[1].'/'.$f_start[0]."/00'";
			$data_final = $f_end[1].'/'.$f_end[0];
			$datas_possiveis = array($data_inicio);


			$m = $f_start[0] + $MONTH_INTERVAL;
			$a = $f_start[1];
			if($m>12){
				$m= $m-12;
				$a+=1;
			}

			$df = new Data(1,$f_end[0],$f_end[1]);
			
			// LAÇO PARA EXTRAIR AS POSSIVEIS DATAS
			// LÓGICA : TENDO O MES ANO E INTERVALO D EXTRAÇÃO DE DATA , PEGAMOS E COMPARAMOS SE A DIFERENÇA ENTRE A DATA INICIAL E A FINAL EM SEGUNDOS É MENOR QUE 0 OU SEJA , NÃO ULTRAPASSAMOS O LIMITE DA DATA, CASO SEJA VERDADE É SALVO A DATA ATUAL EM UM ARRAY DE POSSIBILIDADES COM O FORMATO ANO/MES/DIA

			for( $i = 1; Data::diff(new Data(1,$m,$a), $df) <= 0; $i++){
				
				$datas_possiveis[$i] = "'$a/$m/00'";
				$m += $MONTH_INTERVAL;

				if($m>12){

					$m = $m-12;
					$a += 1;

				}

			}

		if ($SEARCH_TYPE != "cesta_custo_total") {


			$sql = "
			SELECT PRP.".$SEARCH_TYPE.", 
			EXTRACT(MONTH FROM P.pesquisa_data) AS mes, 
			EXTRACT(YEAR FROM P.pesquisa_data) AS ano , 
			C.cidade_id , C.cidade_nome , 
			tabela_produtos.produto_nome_visualizacao 
			as produto_nome
			FROM tabela_pesquisa_resultados_produtos PRP 
			NATURAL JOIN tabela_pesquisas P 
			NATURAL JOIN tabela_produtos 
			INNER JOIN tabela_cidades C on PRP.cidade_id = C.cidade_id 
			WHERE PRP.produto_id IN (".implode(',',$PRODUCTS).")
			AND PRP.cidade_id = '$CITY'
			AND P.pesquisa_data IN (".implode(',',$datas_possiveis).")
			ORDER BY mes,ano";


		}else{

			$sql = "
			SELECT P.pesquisa_id, EXTRACT(MONTH FROM P.pesquisa_data) 
			AS mes, EXTRACT(YEAR FROM P.pesquisa_data)
		    AS ano  ,TPC.gasto_mensal_cesta, C.cidade_id, C.cidade_nome
			FROM tabela_pesquisas_cidades TPC 
			NATURAL JOIN tabela_pesquisas P
			INNER JOIN tabela_cidades C 
			ON C.cidade_id = TPC.cidade_id
			WHERE TPC.cidade_id 
			IN ('$CITY') AND 
			P.pesquisa_data"." IN (".implode(',',$datas_possiveis).")
			ORDER BY C.cidade_nome, P.pesquisa_data";

		}



		$result = $conn->query($sql);
		$FINAL_INFO = array();
		$COUNT = 0;

		if ($result->num_rows > 0) {
		    while ($row[] = $result->fetch_assoc()) {
			
	    		$row[$COUNT]["preco"] = $row[$COUNT][$SEARCH_TYPE];
				unset($row[$COUNT][$SEARCH_TYPE]);

				if($DISPLAY_TYPE != 2):

					if ($SEARCH_TYPE != 'cesta_custo_total') {

				    	if ($CITY == 2) {
				    		$row[$COUNT]["cidade_nome"] = utf8_encode($row[$COUNT]["cidade_nome"]);
				    	}
				    	$row[$COUNT]["produto_nome"] = utf8_encode($row[$COUNT]["produto_nome"]);
				    	if (isset($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]])) {
				    		array_push($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]], $row[$COUNT]);
				    	}else{
				    		$FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]] = array();
				    		array_push($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]], $row[$COUNT]);
				    	}
				    	$COUNT++;

			    	}else{
		    				$row[$COUNT]["produto_nome"] = utf8_encode($row[$COUNT]["produto_nome"]);
					    	if (isset($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]])) {
					    		array_push($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]], $row[$COUNT]);
					    	}else{
					    		$FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]] = null;
					    		array_push($FINAL_INFO[$row[$COUNT]["mes"]."/".$row[$COUNT]["ano"]], $row[$COUNT]);
					    	}
					    	$COUNT++;

			    	}

			    else:

    				if ($SEARCH_TYPE != 'cesta_custo_total') {

				    	if ($CITY == 2) {
				    		$row[$COUNT]["cidade_nome"] = utf8_encode($row[$COUNT]["cidade_nome"]);
				    	}
				    	$row[$COUNT]["produto_nome"] = utf8_encode($row[$COUNT]["produto_nome"]);
				    	if (isset($FINAL_INFO[$row[$COUNT]["produto_nome"]])) {
				    		array_push($FINAL_INFO[$row[$COUNT]["produto_nome"]], $row[$COUNT]);

				    	}else{
				    		$FINAL_INFO[$row[$COUNT]["produto_nome"]] = array();
				    		array_push($FINAL_INFO[$row[$COUNT]["produto_nome"]], $row[$COUNT]);
				    	}

				    	$COUNT++;

			    	}else{

	    				if ($CITY == 2) {
				    		$row[$COUNT]["cidade_nome"] = utf8_encode($row[$COUNT]["cidade_nome"]);
				    	}
				    	$row[$COUNT]["produto_nome"] = utf8_encode($row[$COUNT]["produto_nome"]);
				    	if (isset($FINAL_INFO[$row[$COUNT]["produto_nome"]])) {
				    		array_push($FINAL_INFO[$row[$COUNT]["produto_nome"]], $row[$COUNT]);
				    	}else{
				    		$FINAL_INFO[$row[$COUNT]["produto_nome"]] = array();
				    		array_push($FINAL_INFO[$row[$COUNT]["produto_nome"]], $row[$COUNT]);
				    	}
				    	$COUNT++;

			    	}

				endif;
		    	
		    }

		} else {
		    echo "deu ruim ein tio";
		}

		$conn->close();
		// $json = json_encode($FINAL_INFO, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		// print_r($FINAL_INFO);
		// echo $json;

		echo "Você não tem permissão para acessar essa URL. Tente novamente mais tarde.";
		die;

	endif;

	
