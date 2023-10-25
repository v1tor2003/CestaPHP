<?php 
	$pesquisa_id = $_REQUEST['hid'];
	$cidade_id = $_REQUEST['cidade_id'];
	$info_aux = array();
	$info = array();
	
	$strsql = "SELECT cidade_nome FROM tabela_cidades WHERE cidade_id = '".$cidade_id."'";
	$cidade = mysql_query($strsql) or die(mysql_error());
	$row = mysql_fetch_array($cidade);
?>
<h2 id="Mcaption" style="text-align:left"><?php echo("Cesta B&aacute;sica: ".$row['cidade_nome']); ?>&nbsp;&nbsp;<label style="font-family:Arial, Helvetica, sans-serif; color:#003366; font-size:11px;">(Pre&ccedil;o M&eacute;dio,Gasto Mensal,Varia&ccedil;&otilde;es,Tempo de Trabalho Necess&aacute;rio)</label></h2>
<table cellspacing="0" id="listTable" style="width:630px;">
	<colgroup>
		<col id="codigo" />
		<col id="nome" />
		<col id="cidade" />
	</colgroup>		
	
	<thead>
		<tr>
			<th scope="col" class="tdboder">Produto</th>
			<th scope="col" class="tdboder">Quant.</th>
			<th scope="col" class="tdboder">Pre&ccedil;o M&eacute;dio</th>
			<th scope="col" class="tdboder">Gasto Mensal</th>
			<th scope="col" class="tdboder">Varia&ccedil;&atilde;o Mensal</th>
			<th scope="col" class="tdboder">Varia&ccedil;&atilde;o Semestral</th>
			<th scope="col" class="tdboder">Varia&ccedil;&atilde;o Anual</th>
			<th scope="col" class="tdboder">Tempo de Trabalho</th>
		</tr>
	</thead>
<?
	
	$strsql = "SELECT A.produto_id,B.produto_nome_visualizacao, A.produto_preco_medio, A.produto_preco_total, C.racao_minima_quantidade,D.medida_simbolo,A.produto_tempo_trabalho,A.produto_variacao_mensal,A.produto_variacao_semestral,A.produto_variacao_anual FROM tabela_pesquisa_resultados_produtos A, tabela_produtos B, tabela_racao_minima C,tabela_unidade_medidas D WHERE A.pesquisa_id ='".$pesquisa_id."' AND A.produto_id = B.produto_id AND B.produto_cesta = '1' AND B.produto_id = C.produto_id AND C.racao_minima_medida = D.medida_id AND A.cidade_id = '".$cidade_id."' GROUP BY A.produto_id";
	//die($strsql);
	$pesquisa = mysql_query($strsql) or die(mysql_error());
	$gasto_mensal = 0;
	$total_horas = 0;
	while($row = mysql_fetch_array($pesquisa))
	{
		if($l_cor == '') $l_cor = "par"; else $l_cor = "";
		$gasto_mensal += $row['produto_preco_total'];
?>
		
		<tr class="<?php echo($l_cor);?>">
		<td class="tdboder"><?php echo($row['produto_nome_visualizacao']); ?></td>
		<td class="tdboder"><?php echo($row['racao_minima_quantidade']." (".$row['medida_simbolo'].")"); ?></td>
		<td class="tdboderCod"><?php echo($row['produto_preco_medio']); ?></td>
		<td class="tdboderCod"><?php echo($row['produto_preco_total']); ?></td>
		<td class="tdboderCod" <?php if($row['produto_variacao_mensal']<0){?> style="color:#FF3300;" <?php }?> ><?php if($row['produto_variacao_mensal'] == '')echo(" - ");else echo($row['produto_variacao_mensal']); ?></td>
		<td class="tdboderCod" <?php if($row['produto_variacao_semestral']<0){?> style="color:#FF3300;" <?php }?> ><?php if($row['produto_variacao_semestral'] == NULL)echo("  - ");else echo($row['produto_variacao_semestral']); ?></td>
		<td class="tdboderCod" <?php if($row['produto_variacao_anual']<0){?> style="color:#FF3300;" <?php }?> ><?php if($row['produto_variacao_anual'] == NULL)echo("  - ");else echo($row['produto_variacao_anual']); ?></td>
		<?php 
		
			$total_horas += $row['produto_tempo_trabalho'];
			
			$tempo_trabalho = converte_horas($row['produto_tempo_trabalho']);
		?>
		<td style="text-align:left;"><?php echo($tempo_trabalho); ?></td>
		</tr>				
	
<?php	   
}//do while
?>
<tr>
		<td colspan="3" align="center" style="background-color: #CCCCCC;">
		Total:
		</td>
		<td style="border:#FFFFFF; text-align:left; font-size:14px; background-color:#CCCCCC;" align="left">
		<?php echo($gasto_mensal); ?>
		</td>
		
		<?php 
		$strsql = "SELECT variacao_mensal,variacao_anual,variacao_semestral FROM tabela_pesquisas_cidades WHERE pesquisa_id = '".$pesquisa_id."' AND cidade_id = '".$cidade_id."'";
			
		$res = mysql_query($strsql) or die(mysql_error());
		$row = mysql_fetch_array($res);
	
		$variacao_mensal = ($row['variacao_mensal']=!''?$row['variacao_mensal']:'-');
		$variacao_semestral = ($row['variacao_semestral']!=''?$row['variacao_semestral']:'-');
		$variacao_no_ano = ($row['variacao_anual']!=''?$row['variacao_anual']:'-');
		
		
	
	?>
		<td align="center" style="border:#FFFFFF; background-color:#CCCCCC;"><?php echo($variacao_mensal); ?></td>
		<td align="center" style="border:#FFFFFF;background-color:#CCCCCC;"><?php echo($variacao_semestral); ?></td>
		<td align="center" style="border:#FFFFFF;background-color:#CCCCCC;"><?php echo($variacao_anual); ?></td>
		<td align="center" style="border:#FFFFFF;background-color:#CCCCCC;"><?php echo(converte_horas($total_horas));?></td>
		</tr>	
</table>