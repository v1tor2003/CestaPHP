<?php

	$situacao = $_REQUEST['situacao'];
	$pesquisa_id = $_REQUEST['hid'];
	$flag = true;
	$cidades = array();
	$produto_id = array();
	$preco_medio = array();
	$gasto_mensal = array();
	$tempo_trabalho = array();
	$variacao_mensal = array();
	$variacao_semestral = array();
	$variacao_no_ano = array();
	$gasto_mensal_cesta = 0;
	
	if($situacao != 0)
	{
		
		// Verifica - se a existência de coletas pertencentes a pesquisa ainda não encerradas
		$strsql = "SELECT * FROM tabela_coletas WHERE pesquisa_id = '".$pesquisa_id."' AND coleta_fechada = '0'";
		$res = mysql_query($strsql) or die(mysql_error());
		
		if($res && mysql_num_rows($res)>0)
			$flag = false;
		else
		{
			//Primeira atualiza para os produtos que compõem a cesta (1) e depois para os que não compõem (0)
			atualiza_pesquisa(1,$pesquisa_id);
			atualiza_pesquisa(0,$pesquisa_id);
		}
		
	}//else situacao != 0
	else
	{
		$strsql = "DELETE FROM tabela_pesquisa_resultados_produtos WHERE pesquisa_id = ".$pesquisa_id;
		mysql_query($strsql) or die(mysql_error());
		
		
		$strsql = "DELETE FROM tabela_pesquisas_cidades WHERE pesquisa_id = '".$pesquisa_id."'";
		mysql_query($strsql) or die(mysql_error());
	}
		
	
	if($flag)
	{
		$strsql = "SELECT salario_valor_bruto,salario_id FROM tabela_salarios A WHERE salario_em_uso = '1'";
		$res = mysql_query($strsql) or die(mysql_error());
		$row = mysql_fetch_array($res);
		$salario = $row['salario_valor_bruto'];
		$salario_id = $row['salario_id'];
			
		$strsql = "SELECT delimitador_id FROM tabela_delimitador_racao WHERE delimitador_em_uso = '1' AND delimitador_oficial='1'";
		
		$res = mysql_query($strsql) or die(mysql_error());
		$row = mysql_fetch_array($res);
		$delimitador_em_uso = $row['delimitador_id'];
	
		$salario_id = isNullBD($salario_id);
		$delimitador_em_uso = isNullBD($delimitador_em_uso);
		
		$strsql = "UPDATE tabela_pesquisas SET pesquisa_fechada = '".$situacao."',salario_id = ".$salario_id.",delimitador_id = ".$delimitador_em_uso." WHERE pesquisa_id = '".$pesquisa_id."'";
	
		mysql_query($strsql) or die(mysql_error());
		echo("");
	}
	else
		echo("Pesquisa nao pode ser concluida.\nHa coletas nao finalizadas!");
		
		
	function atualiza_pesquisa($oficial,$pesquisa)
	{
			//Se exitirem então deve - se pegar o salario utilizado e o delimitador em uso
			$strsql = "SELECT salario_valor_bruto,salario_id FROM tabela_salarios A WHERE salario_em_uso = '1'";
			$res = mysql_query($strsql) or die(mysql_error());
			$row = mysql_fetch_array($res);
			$salario = $row['salario_valor_bruto'];
			$salario_id = $row['salario_id'];
			
			$strsql = "SELECT delimitador_id FROM tabela_delimitador_racao WHERE delimitador_em_uso = '1' AND delimitador_oficial='".$oficial."'";
			
			$res = mysql_query($strsql) or die(mysql_error());
			$row = mysql_fetch_array($res);
			
			$delimitador_em_uso = $row['delimitador_id'];

			//Se todas coletas referentes a pesquisa de um determinado mês estiverem fechadas então pega - se o mês e o ano da pesquisa 
			$strsql = "SELECT EXTRACT(MONTH FROM pesquisa_data) AS mes_id,EXTRACT(YEAR FROM pesquisa_data)AS pesquisa_ano FROM tabela_pesquisas WHERE pesquisa_id = '".$pesquisa."'";
			
			$res = mysql_query($strsql) or die(mysql_error());
			$row = mysql_fetch_array($res);
			
			$pesquisa_mes = $row['mes_id'];
			$pesquisa_ano = $row['pesquisa_ano'];
			$data_mes_anterior = formata_mes_anterior($pesquisa_mes,$pesquisa_ano);
			$data_mes_semestre = formata_mes_semestre($pesquisa_mes,$pesquisa_ano);
			$data_ano_anterior = formata_ano_anterior($pesquisa_mes,$pesquisa_ano);
			
			//Pega - se todas as cidades que compõem o projeto da Cesta Básica
			$strsql = "SELECT cidade_id FROM tabela_cidades A";
			$res = mysql_query($strsql) or die(mysql_error());
		
			if($res && mysql_num_rows($res)>0)
				while ($row = mysql_fetch_array($res))
				{
					//E para cada cidade é realizado os cálculos que serão armazenados na tabela TABELA_PESQUISA_RESULTADOS_PRODUTOS TABELA_PESQUISA_CIDADES 
					$gasto_mensal_cesta = 0;
					$cidade_id = $row['cidade_id'];
					$strsql = "SELECT A.coleta_id,A.produto_id, C.produto_nome, AVG( A.precos_media ) AS media_produto,AVG(A.precos_total) AS media_total,D.racao_minima_quantidade FROM tabela_precos A NATURAL JOIN tabela_coletas B NATURAL JOIN tabela_produtos C NATURAL JOIN tabela_racao_minima D WHERE (B.pesquisa_id ='".$pesquisa."' AND B.estabelecimento_id IN (SELECT estabelecimento_id FROM tabela_estabelecimentos A NATURAL JOIN tabela_bairros B WHERE B.cidade_id = '".$cidade_id."')) AND C.produto_cesta = '".$oficial."' AND D.delimitador_id = '".$delimitador_em_uso."' GROUP BY produto_id";
					
					
					$produto_id = array();
					$preco_medio = array();
					$gasto_mensal = array();
					$tempo_trabalho = array();
					
					$res1 = mysql_query($strsql) or die(mysql_error());
						
					if($res1 && mysql_num_rows($res1)>0)
					{
						$i=0;
							
						while ($row1 = mysql_fetch_array($res1))
						{
							$produto_id[$i] = $row1['produto_id'];
							$preco_medio[$i] = round_valor($row1['media_produto'],2);
							$preco_total = $preco_medio[$i] * $row1['racao_minima_quantidade'];
							$gasto_mensal[$i] = round_valor($preco_total,2);
							$tempo_trabalho[$i] = round_valor((($preco_total*220)/$salario),2);
							$gasto_mensal_cesta += $gasto_mensal[$i];
							$i++;
						}

							
						$variacao_mensal = retorna_variacao($data_mes_anterior[0],$data_mes_anterior[1],$cidade_id,$gasto_mensal,$oficial);
						$variacao_semestral= retorna_variacao($data_mes_semestre[0],$data_mes_semestre[1],$cidade_id,$gasto_mensal,$oficial);
						$variacao_no_ano = retorna_variacao($data_ano_anterior[0],$data_ano_anterior[1],$cidade_id,$gasto_mensal,$oficial);
						
				
						$tam = count($produto_id);
				
						for($i=0;$i<$tam;$i++)
						{
							$strsql = "INSERT INTO tabela_pesquisa_resultados_produtos (produto_id,cidade_id,pesquisa_id,produto_preco_medio,produto_preco_total,produto_tempo_trabalho,produto_variacao_mensal,produto_variacao_semestral,produto_variacao_anual) VALUES ('".$produto_id[$i]."','".$cidade_id."','".$pesquisa."','".$preco_medio[$i]."','".$gasto_mensal[$i]."','".$tempo_trabalho[$i]."',".isNullBD($variacao_mensal[$i]).",".isNullBD($variacao_semestral[$i]).",".isNullBD($variacao_no_ano[$i]).")";
							
							mysql_query($strsql) or die(mysql_error());
						}
						
						if($oficial == 1)
						{
				
						$variacao[0]= retorna_variacao_cidades($data_mes_anterior[0],$data_mes_anterior[1],$cidade_id,$gasto_mensal_cesta);
						$variacao[1]= retorna_variacao_cidades($data_mes_semestre[0],$data_mes_semestre[1],$cidade_id,$gasto_mensal_cesta);
						$variacao[2]= retorna_variacao_cidades($data_ano_anterior[0],$data_ano_anterior[1],$cidade_id,$gasto_mensal_cesta);

							
						$tam_trabalho = count($tempo_trabalho);
						$total_trabalho = 0;
						
						for($i=0;$i<$tam_trabalho;$i++)
							$total_trabalho+=$tempo_trabalho[$i]; 
				
						$strsql = "INSERT INTO tabela_pesquisas_cidades (cidade_id,pesquisa_id,gasto_mensal_cesta,variacao_mensal,variacao_semestral,variacao_anual,tempo_trabalho) VALUES ('".$cidade_id."','".$pesquisa."','".$gasto_mensal_cesta."',".isNullBD($variacao[0]).",".isNullBD($variacao[1]).",".isNullBD($variacao[2]).",'".$total_trabalho."')";
									
							mysql_query($strsql) or die(mysql_error());
						}
						
				}//if se verifica se há pesquisa em determinada cidade
					
			}// while que verifica as cidades
			
		
		}
?>
