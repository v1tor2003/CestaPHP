<?php
/*******************************************************************************
 * ** ** ** **  ** ** ** ** EXTRAÇÃO DOS DADOS DO BD  ** ** ** ** ** ** ** ** **
 *******************************************************************************/
		        /*
			 * Extrai mes/ano do das datas inicial e final do periodo
			 */
		        list($f['mes_inicio'],$f['ano_inicio']) = extrai_mes_ano_form($f['p_inicio']);
		        list($f['mes_final'],$f['ano_final'])   = extrai_mes_ano_form($f['p_final']);

			include_once CLASS_DATA_PATH;// or die("Classe data nao foi encontrada!");/*Classe Data*/

			$data_inicio = "'".$f['ano_inicio'].'/'.$f['mes_inicio']."/00'";
			$data_final = $f['ano_final'].'/'.$f['mes_final'];
			$datas_possiveis = array($data_inicio);

			$m=$f['mes_inicio']+$f['periodoMeses'];
			$a=$f['ano_inicio'];
			if($m>12){
				$m= $m-12;
				$a+=1;
			}
			$df = new Data(1,$f['mes_final'],$f['ano_final']);

			for($i=1;Data::diff(new Data(1,$m,$a), $df)<=0;$i++){
				$datas_possiveis[$i] = "'$a/$m/00'";
				$m+=$f['periodoMeses'];
				if($m>12){
					$m= $m-12;
					$a+=1;
				}

			}



			/*
			 * Seleciona as cidades, produtos e periodos de cada
			 * produto no banco dedados mysql
			 */
			if($f['tipoconsulta'] == 'cesta_custo_total'){
				$sql = "SELECT P.pesquisa_id, EXTRACT(MONTH FROM P.pesquisa_data) AS mes, EXTRACT(YEAR FROM P.pesquisa_data) AS ano  ,TPC.gasto_mensal_cesta, C.cidade_id, C.cidade_nome
				FROM tabela_pesquisas_cidades TPC 
				NATURAL JOIN tabela_pesquisas P
				INNER JOIN tabela_cidades C ON C.cidade_id = TPC.cidade_id
				WHERE TPC.cidade_id IN (".implode(',',$f['cidades']).") AND P.pesquisa_data"." IN (".implode(',',$datas_possiveis).")
				ORDER BY C.cidade_nome, P.pesquisa_data";

				$res = mysqli_query($conn,$sql);
				$info = array();
				$produtos = array();
				$cidades  =array();

				while($row=mysqli_fetch_array($res,MYSQL_ASSOC)){
					$info[$row['cidade_nome']][$row['mes'].'/'.$row['ano']][$row['gasto_mensal_cesta']]= $row[$f['gasto_mensal_cesta']];
					if(!in_array($row['cidade_nome'], $cidades)) $cidades[] =$row['cidade_nome'];
				}
			}

			else{			
				$sql = "SELECT
				PRP.".$f['tipoconsulta'].",
				EXTRACT(MONTH FROM P.pesquisa_data) AS mes,
				EXTRACT(YEAR FROM P.pesquisa_data) AS ano  ,
				C.cidade_id ,
				C.cidade_nome ,
				tabela_produtos.produto_nome_visualizacao as produto_nome
				FROM tabela_pesquisa_resultados_produtos PRP

				NATURAL JOIN tabela_pesquisas P
				NATURAL JOIN tabela_produtos
				INNER JOIN tabela_cidades C on PRP.cidade_id = C.cidade_id

				WHERE PRP.produto_id IN (".implode(',',$f['produtos']).")
				AND PRP.cidade_id IN (".implode(',',$f['cidades']).")
				AND P.pesquisa_data".
				" IN (".implode(',',$datas_possiveis).") ".
					//BETWEEN '".$data_inicio."/00' AND '".$data_final."/00'
				"ORDER BY C.cidade_nome, tabela_produtos.produto_nome_visualizacao,P.pesquisa_data";

				$res = mysqli_query($conn,$sql);
				$info = array();
				$produtos = array();
				$cidades  =array();

			/*
			 * Gera vetores associativos
			 * $info[cidade][periodo][produto] = valor
			 * $cidades[n] = nome_da_cidade
			 * $prodtos[n] = nome_do_produto
			 */
			while($row=mysqli_fetch_array($res)){
				$info[$row['cidade_nome']]
				[$row['mes'].'/'.$row['ano']]
				[$row['produto_nome']]= $row[$f['tipoconsulta']];
				if(!in_array($row['produto_nome'], $produtos)) $produtos[] = $row['produto_nome'];
				if(!in_array($row['cidade_nome'], $cidades)) $cidades[] =$row['cidade_nome'];
			}
		}
			/*
			 * Seleciona no banco de dados o tipo de moeda usada nas pesquisas
			 * Ex.:
			 *  R$ (BRL, Real Brasileiro)
			 *  US$ (USD, Dolar Americano)
			 *  ...
			 */
			$strsql = "SELECT * FROM tabela_salarios  WHERE salario_em_uso = '1'";

			$res = mysqli_query($conn,$strsql) or die(mysqli_error());
			$row = mysqli_fetch_array($res);
			$salario_simbolo = $row['salario_simbolo'];
			?>
