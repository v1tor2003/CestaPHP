<?php
	$pesquisa_ano = $_REQUEST['ano']; 
	$cidade = $_REQUEST['cit'];
	$pesquisa_id = $_REQUEST['hid'];
	
	$strsql = "SELECT B.mes_nome,B.mes_id FROM tabela_pesquisas A, tabela_mes B WHERE B.mes_id = EXTRACT(MONTH FROM A.pesquisa_data) AND A.pesquisa_id = '".$pesquisa_id."'";
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($res);
	$mes_atual = $row['mes_nome'];
	$mes_id = $row['mes_id'];
	$data_mes_anterior = formata_mes_anterior($mes_id,$pesquisa_ano);
	$pesquisa_mes_anterior = retorna_pesquisa($data_mes_anterior[0],$data_mes_anterior[1]);
	$tmp_trabalho = 0;
	
/**********************************************************************************************************
*
*	Pequisa para a tabela com o G.M., Variações e CRL de todos os meses do ano da pesquisa solicitada.
*
**********************************************************************************************************/

	$strsql = "SELECT * FROM tabela_pesquisas_cidades A,tabela_pesquisas B,tabela_mes C,tabela_salarios D WHERE A.cidade_id = '".$cidade."' AND EXTRACT(YEAR FROM B.pesquisa_data) = '".$pesquisa_ano."' AND EXTRACT(MONTH FROM B.pesquisa_data) = C.mes_id AND A.pesquisa_id = B.pesquisa_id AND B.salario_id = D.salario_id ORDER BY C.mes_id DESC";
	
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

	while($row = mysqli_fetch_array($res))
	{
		$mes[] = $row['mes_nome'];
		$gasto_mensal[] = $row['gasto_mensal_cesta'];
		$crl[] = calcula_crl($row['salario_valor_liquido'],$row['gasto_mensal_cesta']);
		$variacao_mensal[] = $row['variacao_mensal'];
		$variacao_semestral[] = $row['variacao_semestral'];
		$variacao_anual[] = $row['variacao_anual']; 
		$tempo_trabalho[] = $row['tempo_trabalho'];
		
	}
	
	$tam = (is_array($mes) || is_countable($mes)) ? count($mes) : 0;
	
/**********************************************************************************************************
*
*	Pequisa para a tabela 2 da página
*
**********************************************************************************************************/
	
	$strsql = "SELECT * FROM tabela_pesquisa_resultados_produtos A, tabela_pesquisas B,tabela_produtos C WHERE A.pesquisa_id = B.pesquisa_id AND A.pesquisa_id = '".$pesquisa_id."' AND A.cidade_id = '".$cidade."' AND A.produto_id = C.produto_id ORDER BY A.produto_id";
	
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	
	$i=0;
	$prod_gasto_total = 0;
	while($row = mysqli_fetch_array($res))
	{
		$produto_id[] = $row['produto_id'];
		$produto[] = $row['produto_nome_visualizacao'];
		$preco_medio[] = $row['produto_preco_medio'];
		$produto_gasto_mensal[] = $row['produto_preco_total'];
		$prod_gasto_total += $row['produto_preco_total'];
		$produto_variacao_mensal[] = $row['produto_variacao_mensal'];
		$produto_variacao_semestral[] = $row['produto_variacao_semestral'];
		$produto_variacao_anual[] = $row['produto_variacao_anual'];
		$produto_tempo_trabalho[] = $row['produto_tempo_trabalho'];
		$tmp_trabalho += $row['produto_tempo_trabalho'];
		$i++;
	}
	
	$tam_produtos = $i;
	$prod_buscar = "(";
	
	for($i=0;$i<$tam_produtos;$i++)
	{
		if($i != $tam_produtos-1)
			$prod_buscar =  $prod_buscar.$produto_id[$i].","; 
		else
			$prod_buscar = $prod_buscar.$produto_id[$i].")";
	}

	$message_no_prod = 'Sem produtos cadastrados para essa cidade';	
	if($prod_buscar === "("){
		echo "<script>alert('$message_no_prod');</script>";
		echo "<script>window.close();</script>";
	}

	$strsql = "SELECT * FROM tabela_racao_minima A,tabela_delimitador_racao B,tabela_unidade_medidas C WHERE A.delimitador_id = B.delimitador_id AND B.delimitador_em_uso = '1' AND A.produto_id IN ".$prod_buscar." AND A.racao_minima_medida = C.medida_id GROUP BY A.produto_id";

	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($res))
	{
		$quantidade[] = $row['racao_minima_quantidade'];
		//$unidade_medida[] = $row['medida_simbolo'];
	}
	
	$strsql = "SELECT * FROM tabela_pesquisa_resultados_produtos A, tabela_pesquisas B,tabela_produtos C WHERE A.pesquisa_id = B.pesquisa_id AND A.pesquisa_id = '".$pesquisa_mes_anterior['pesquisa_id']."' AND A.cidade_id = '".$cidade."' AND A.produto_id = C.produto_id ORDER BY A.produto_id";
	
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	
	$prod_gasto_total_anterior = 0;
	while($row = mysqli_fetch_array($res))
	{
		$gasto_mensal_anterior[] = $row['produto_preco_total'];
		$prod_gasto_total_anterior += $row['produto_preco_total'];
		$preco_medio_anterior[] = $row['produto_preco_medio'];
		$prod_mensal_anterior[] = $row['produto_variacao_mensal'];
	}
		
		
	$strsql = "SELECT * FROM tabela_pesquisas A,tabela_salarios B WHERE A.salario_id = B.salario_id AND A.pesquisa_id = '".$pesquisa_id."'";
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($res);
	$salario_simbolo = $row['salario_simbolo'];
	$salario_liquido = $row['salario_valor_liquido'];
	$salario_bruto = $row['salario_valor_bruto'];
	
	$strsql = "SELECT * FROM tabela_pesquisas A,tabela_delimitador_racao B WHERE A.pesquisa_id = '".$pesquisa_id."' AND A.delimitador_id = B.delimitador_id";
	
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($res);
	$delimitador = $row['delimitador_descricao'];
	
	$data_aux = formata_mes_anterior($pesquisa_mes_anterior['mes_id'],$pesquisa_mes_anterior['ano']);

	$qtd_acomp_mensal = 4;
	for($i=0;$i<$qtd_acomp_mensal;$i++)
	{
	
		$pes_aux[$i] = retorna_pesquisa($data_aux[0],$data_aux[1]);
		$strsql = "SELECT * FROM tabela_pesquisa_resultados_produtos A, tabela_pesquisas B,tabela_produtos C WHERE A.pesquisa_id = B.pesquisa_id AND A.pesquisa_id = '".$pes_aux[$i]['pesquisa_id']."' AND A.cidade_id = '".$cidade."' AND A.produto_id = C.produto_id ORDER BY A.produto_id";
		
		$j=0;
		
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		while($row = mysqli_fetch_array($res))
		{
			$analise_prod[$i][$j]['variacao_mensal'] = $row['produto_variacao_mensal']; 
			$j++;
		}
				
		
		$data_aux = formata_mes_anterior($data_aux[0],$data_aux[1]);
	}
	
	$strsql = "SELECT * FROM tabela_pesquisas A NATURAL JOIN tabela_pesquisas_cidades B WHERE A.pesquisa_id = '".$pesquisa_id."' AND B.cidade_id = '".$cidade."'";
	
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	
	while($row = mysqli_fetch_array($res))
	{
		$tot_var_mensal = $row['variacao_mensal'];
		$tot_var_semestral = $row['variacao_semestral'];
		$tot_var_anual = $row['variacao_anual'];
	}

$include_head[]=<<<EOS
<link rel="stylesheet" type="text/css" href="estilo/forms_selects.css" media="screen"/>
EOS;
require("cabecalho.php");
?>
<body>
<div class="caixa_principal">

	<?php require("topo.php"); ?>
	
	<div class="menu_superior">
	</div>
	
	<div class="conteudo_sem_menu">
			
	<h1 id="Mcaption" style="text-align:left">Cesta B&aacute;sica - Boletim <?php echo($mes_atual." / ".$pesquisa_ano); ?> </h1>

	<h2 id="subtitulos">Custo da Cesta B&aacute;sica (em <?php echo($salario_simbolo); ?>)</h2>
	<table cellspacing="0" id="listTable" class="tabela_resultados" style="width:740px;">
		<colgroup>
			<col id="codigo" />
			<col id="gastos" />
			<col id="tempo_trabalho" />
		</colgroup>		
		<thead>
			<tr>
				<th scope="col" class="tdboderCod">M&ecirc;s</th>
				<th scope="col" class="tdboderCod">Gasto Mensal</th>
				<th scope="col" class="tdboderCenter">Varia&ccedil;&atilde;o Mensal % </th>
				<th scope="col" class="tdboderCenter">Varia&ccedil;&atilde;o Semestral % </th>
				<th scope="col" class="tdboderCenter">Varia&ccedil;&atilde;o Anual  % </th>
				<th scope="col" class="tdboderCod">C.F.A</th>
				<th scope="col" class="tdboderCod">Q.V.S</th>
				<th scope="col" class="tdboderCod">C.R.L ( % )</th>
 				<th scope="col" class="tdboderCenter">Tempo de Trabalho</th>
			</tr>
		</thead>
		<?php
		
			for($i=0;$i<$tam;$i++)
			{
				if($l_cor == '')
					$l_cor = "par";
				else
					$l_cor = "";
					
		?>
			<tr class="<?php echo ($l_cor);?>">
				 <td class="tdboderCod">
				 	<?php echo($mes[$i]); ?>
				</td>
				 <td class="tdboderCod">
				 	<?php echo(isNull(formata_numero($gasto_mensal[$i]))); ?>
				</td>
				<td class="tdboderCenter" <?php echo(isNegative($variacao_mensal[$i]));?> >
					<?php echo(isNull(formata_numero($variacao_mensal[$i]))); ?>
				</td>
				<td class="tdboderCenter" <?php echo(isNegative($variacao_semestral[$i]));?> >
					<?php echo(isNull(formata_numero($variacao_semestral[$i]))); ?></td>
				<td class="tdboderCenter" <?php echo(isNegative(formata_numero($variacao_anual[$i])));?> >
					<?php echo(isNull(formata_numero($variacao_anual[$i]))); ?>
				</td>
				<td class="tdboderCod"><?php echo(formata_numero(calcula_cfa($gasto_mensal[$i])));?>
				</td>
				<td class="tdboderCod">
				<?php echo(formata_numero(calcula_qvs(calcula_cfa($gasto_mensal[$i]),$salario_bruto)));?>
				</td>
				<td class="tdboderCod">
					<?php echo(formata_numero($crl[$i])); ?>
				</td>
				<td class="tdboder" >
					<?php echo(converte_horas($tempo_trabalho[$i])); ?>
				</td>
			</tr>
			<?php	   
			
			}//do for
			
			?>		
	</table>
	
	<table cellspacing="0" id="listTable" class="tabela_resultados" style="width:740px;">
		<colgroup>
			<col id="nome" />
		</colgroup>			
		<thead>
			<tr>
				<th scope="col" rowspan="2" class="tdboder">Produto</th>
				<th scope="col" rowspan="2" class="tdboderCenter">Qtde.</th>
				<th scope="col" colspan="2" class="tdboderCenter">Pre&ccedil;o M&eacute;dio</th>
				<th scope="col" colspan="2" class="tdboderCenter">Gasto Mensal</th>
				<th scope="col" rowspan="1" colspan="3" class="tdboderCenter">Varia&ccedil;&atilde;o (%) </th>
				<th scope="col" rowspan="2" class="tdboderCenter">Tempo de Trabalho</th>
			</tr>
			<tr>
				<th class="tdboderCenter"><?php echo($pesquisa_mes_anterior['mes']); ?></th>
				<th class="tdboderCenter"><?php echo($mes_atual);?></th>
				<th class="tdboderCenter"><?php echo($pesquisa_mes_anterior['mes']); ?></th>
				<th class="tdboderCenter"><?php echo($mes_atual);?></th>
				<th class="tdboderCenter">Mensal</th>
				<th class="tdboderCenter">Semestral</th>
				<th class="tdboderCenter">Anual</th>
			</tr>
		</thead>
			
		<?php
			
			for($i=0;$i<$tam_produtos;$i++)
			{
				if($l_cor == '')
					$l_cor = "par";
				else
					$l_cor = "";
		?>
			<tr class="<?php echo ($l_cor);?>">
				<td class="tdboder">
					<?php echo($produto[$i]); ?>
				</td>
				<td class="tdboder">
					<?php echo(formata_numero($quantidade[$i])); ?>
				</td>
				<td class="tdboderCod">
					<?php echo(formata_numero($preco_medio_anterior[$i])); ?>
				</td>
				<td class="tdboderCod">
					<b><?php echo(formata_numero($preco_medio[$i])); ?></b>
				</td>
				<td class="tdboderCod">
					<?php echo(formata_numero($gasto_mensal_anterior[$i])); ?>
				</td>
				<td class="tdboderCod">
					<b><?php echo(formata_numero($produto_gasto_mensal[$i])); ?></b>
				</td>
				<td class="tdboderCenter" <?php echo(isNegative($produto_variacao_mensal[$i]));?> >
					<?php echo(isNull(formata_numero($produto_variacao_mensal[$i]))); ?>
				</td>
				<td class="tdboderCenter" <?php echo(isNegative($produto_variacao_semestral[$i]));?> >
					<?php echo(isNull(formata_numero($produto_variacao_semestral[$i]))); ?>
				</td>
				<td class="tdboderCenter" <?php echo(isNegative($produto_variacao_anual[$i]));?> >
					<?php echo(isNull(formata_numero($produto_variacao_anual[$i]))); ?>
				</td>
				<td class="tdboder">
					<?php echo(converte_horas($produto_tempo_trabalho[$i])); ?>
				</td>
			</tr>
			
			<?php	   
				}//do for
			?>
			<tr style="border:#FFFFFF;background-color:#CCCCCC;">
			<td colspan="3" align="center">Total:</td>
			<td colspan="1" align="center"><?php echo(formata_numero($prod_gasto_total_anterior));?></td>
			<td colspan="2" align="right"><?php echo(formata_numero($prod_gasto_total));?></td>
			<td colspan="1" align="center"><?php echo(isNull(formata_numero($tot_var_mensal)));?></td>
			<td colspan="1" align="center"><?php echo(isNull(formata_numero($tot_var_semestral)));?></td>
			<td colspan="1" align="center"><?php echo(isNull(formata_numero($tot_var_anual)));?></td>
			<td colspan="2" align="right"><?php echo(converte_horas($tmp_trabalho));?></td>
			</tr>	
		</table>
		<table cellpadding="0" cellspacing="0" style="border:none;">
		<tr>
		<td>
		<h2 id="subtitulos">Acompanhamento das Varia&ccedil;&otilde;es Mensais dos Produtos</h2>
		</td>
		</tr>
		<tr>
		<td align="center">
		
		<table cellspacing="0" id="listTable" class="tabela_resultados" style="width:600px;">
			<colgroup>
				<col id="nome" />
			</colgroup>		
			
			<thead>
				<tr>
					<th scope="col" rowspan="2" class="tdboderCenter">Produto</th>
					<th scope="col" colspan="<?php echo($qtd_acomp_mensal+2); ?>" class="tdboder">Varia&ccedil;&atilde;o ( % )</th>
				</tr>
				<tr>
					<th scope="col" rowspan="2" class="tdboderCenter"><?php echo($mes_atual);?></th>
					<th scope="col" rowspan="2" class="tdboderCenter"><?php echo($pesquisa_mes_anterior['mes']); ?></th>
					<?php for($i=0;$i<$qtd_acomp_mensal;$i++)
					{
					?>
					<th scope="col" rowspan="2" class="tdboderCenter"><?php echo($pes_aux[$i]['mes']); ?></th>
					<?php } ?>
				</tr>
			</thead>
			
			<?php
			
				for($i=0;$i<$tam_produtos;$i++)
				{
					if($l_cor == '')
						$l_cor = "par";
					else
						$l_cor = "";
						
			?>
			<tr class="<?php echo ($l_cor);?>">
				<td class="tdboder"><?php echo($produto[$i]); ?></td>
				<td class="tdboderCenter" <?php echo(isNegative($produto_variacao_mensal[$i]));?> >
					<?php echo(isNull(formata_numero($produto_variacao_mensal[$i]))); ?></td>
				<td class="tdboderCenter" <?php echo(isNegative($prod_mensal_anterior[$i]));?> ><?php echo(formata_numero(isNull($prod_mensal_anterior[$i]))); ?></td>
				<?php for($j=0;$j<$qtd_acomp_mensal;$j++)
					{
					?>
				<td class="tdboderCenter" <?php echo(isNegative($analise_prod[$j][$i]['variacao_mensal']));?> ><?php echo(isNull(formata_numero($analise_prod[$j][$i]['variacao_mensal']))); ?></td>
				<?php } ?>
			</tr>
			
			<?php
			
					//}//do if	   
				}//do for
			?>
		</table>
		</td>
		</tr>
		</table>
		
		<fieldset style="width:529px;">
		<legend>Informa&ccedil;&otilde;es Adicionais</legend>
		<p>
		<b>Sal&aacute;rio Bruto: </b>&nbsp;R$&nbsp; <?php echo(formata_numero($salario_bruto)); ?>
		</p>
		<p>
		<b>Sal&aacute;rio L&iacute;quido:</b>&nbsp;R$&nbsp; <?php echo(formata_numero($salario_liquido)); ?>
		</p>
		<p>
		<b>Delimitador:</b>&nbsp;<?php echo($delimitador); ?>
		</p>
		<p>
		<b>Fonte:</b>&nbsp;Projeto de Extens&atilde;o - Acompanhamento de Custo da Cesta B&aacute;sica
		</p>
		</fieldset>
		
		
		</div>	
		<div class="clearer"><span></span></div>
</div>
</body>