<?php 
	error_reporting(E_ERROR | E_PARSE);
	$action = $_REQUEST['haction'];
	
	if($action == 'filtrar')
	{
		$campo_filtrado = $_REQUEST['campo_filtrado'];
		
		switch($campo_filtrado)
		{
			case "mes": $campo_filtrado = 'EXTRACT(MONTH FROM A.pesquisa_data)';
						break;
			case "ano": $campo_filtrado = 'EXTRACT(YEAR FROM A.pesquisa_data)';
						break;
			default: $campo_filtrado = 'A.pesquisa_id';
						
		}
		
		$valor_filtro = $_REQUEST['valor_filtro'];
		$operador_filtro = $_REQUEST['operador_filtro'];
		
		$ordenar_por = preg_split ('/[\/]/', $_REQUEST['ordenar_por']);
		
		switch($ordenar_por[0])
		{
			case "mes": $ordenar_por[0] = 'EXTRACT(MONTH FROM A.pesquisa_data)';
						break;
			case "ano": $ordenar_por[0] = 'EXTRACT(YEAR FROM A.pesquisa_data)';
						break;
			default: $ordenar_por[0] = 'A.pesquisa_id';
						
		}
		
		$strsql = "SELECT A.pesquisa_id,B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano,pesquisa_fechada,S.salario_valor_bruto,S.salario_valor_liquido FROM tabela_pesquisas A LEFT OUTER JOIN tabela_salarios S ON A.salario_id = S.salario_id,tabela_mes B WHERE A.pesquisa_detalhada = '0' AND EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND ".$campo_filtrado." ".$operador_filtro." '".$valor_filtro."' ORDER BY ".$ordenar_por[0]." ".$ordenar_por[1];
		
	}
	else
	{
		$strsql = "SELECT A.pesquisa_id,B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano,pesquisa_fechada,S.salario_valor_liquido,S.salario_valor_bruto FROM tabela_pesquisas A LEFT OUTER JOIN tabela_salarios S ON A.salario_id = S.salario_id,tabela_mes B WHERE A.pesquisa_detalhada = '0' AND EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id ORDER BY A.pesquisa_data DESC";
	}
	
	  $records_per_page = 10;		
	  $start_rec = ($_REQUEST['hp']!='') ? $_REQUEST['hp'] : 0;  
	  $pesquisas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	  $total_rec = mysqli_num_rows($pesquisas);
		if ($start_rec>=$total_rec) $start_rec -= $records_per_page;
		if ($start_rec<0) $start_rec=0;	
	  $last_rec = ($start_rec + $records_per_page > $total_rec) ? $total_rec : $start_rec + $records_per_page;  
	  $back_rec = $start_rec - $records_per_page;
	  $pages = floor($total_rec/$records_per_page);
	  $start_last_page = ($pages*$records_per_page==$total_rec) ? ($pages-1)*$records_per_page : $pages*$records_per_page;
	 
	  $pagina_atual = floor(($start_rec == 0 )? 1 : (($start_rec/$records_per_page)+1));

?>			
<table  border="0px" id="link_table_add"  style="width:590px"><tr><td>[<a href="add_pesquisas_det.php" id="link_adicionar" >Adicionar</a>]</td></tr></table>
		
			<!-- Contedo referente a esta pgina -->
					<?php 
							$strsql = $strsql." LIMIT ".$start_rec.",".$records_per_page; 
							
							$pesquisas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($pesquisas && mysqli_num_rows($pesquisas)>0){	
					?>
					<table cellspacing="0" id="listTable" summary="Tabela de Pesquisas" style="width:582px;">
					<colgroup>
						<col id="codigo" />
						<col id="mes" />
						<col id="ano" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">M&ecirc;s</th>
							<th scope="col" class="tdboder">Ano</th>
							<th scope="col" class="tdboder">Sal&aacute;rio Bruto</th>
							<th scope="col" class="tdboder">Sal&aacute;rio L&iacute;quido</th>
							<th scope="col" colspan="6" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($pesquisas))
							{
								if(!isset($l_cor)) $l_cor = '';
								if($l_cor == '') 
									$l_cor = "par";
								else 
									$l_cor = "";
								
								$id_editar = $row['pesquisa_id']."edit";
								$id_excluir = $row['pesquisa_id']."del";
								$id_situacao = $row['pesquisa_id']."sit"; 
								$id_carrinho = $row['pesquisa_id']."car";
								$id_visualizar = $row['pesquisa_id']."vis";
								$url = 'edita_pesquisa_n_det.php';
								
								
								//coleta situacao = 0 no banco de dados quer dizer que a coleta est� aberta
																
								if($row['pesquisa_fechada'] == 0)
								{
									$img_editar = "images/botao_editar.png";
									$img_excluir = "images/botao_deletar.png";
									$img_cadeado = "images/padlock_unlocked.gif";
									$img_carrinho = "images/carrinho.gif";
									$img_visualizar = "images/imprimir_apagado.png";
									$mudar_cadeado = 1;
								}
								else
								{
									$img_editar = "images/botao_editar_apagado.png";
									$img_cadeado = "images/padlock.gif";
									$img_excluir = "images/botao_deletar_apagado.png";
									$img_carrinho = "images/carrinho_apagado.png";
									$img_visualizar = "images/imprimir.png";
									$mudar_cadeado = 0;
									
								}
					
							
						  ?>
							   <tr class="<?php echo ($l_cor);?>" id="<?php echo($id_row); ?>">
								 <td class="tdboderCod"><?php echo($row['pesquisa_id']); ?></td>
								 <td class="tdboder"><?php echo($row['mes_nome']); ?></td>
								<td class="tdboder"><?php echo($row['pesquisa_ano']); ?></td>
								<td class="tdboderCod"><?php echo(isNull($row['salario_valor_bruto'])); ?></td>
								<td class="tdboderCod"><?php echo(isNull($row['salario_valor_liquido'])); ?></td>
<td class="tdboderCod">
	<a href="javascript: void(0)" onClick="return submit_Action_Check('<?php echo($row['pesquisa_id']); ?>','edit','','','add_pesquisas_det.php','A pesquisa de <?php echo($row['mes_nome']."/".$row['pesquisa_ano']); ?> esta fechada!');" id="<?php echo($id_editar); ?>" ><img src="<?php echo $img_editar; ?>" border="0" /></a>
</td>

<td class="tdboderCod">
	<a href="javascript: void(0)" onClick="return submit_Action_Check('<?php echo($row['pesquisa_id']); ?>','del','<?php echo($row['mes_nome']."/".$row['pesquisa_ano']); ?>','Deseja apagar a pesquisa ','add_pesquisas_det.php','A pesquisa de <?php echo($row['mes_nome']."/".$row['pesquisa_ano']); ?> esta fechada!');" id="<?php echo($id_excluir); ?>" ><img src="<?php echo($img_excluir); ?>" border="0"/></a>
</td> 

<td class="tdboderCod">
	<a href="javascript: void(0)" id="<?php echo($id_carrinho); ?>" onClick="return submit_Action_Check('<?php echo($row['pesquisa_id']); ?>','','','','cadastro_precos_cidade.php?pesquisa_id=<?php echo($row['pesquisa_id']); ?>','A pesquisa de <?php echo($row['mes_nome']."/".$row['pesquisa_ano']); ?> esta fechada!');" ><img src="<?php echo($img_carrinho); ?>" border="0"/></a>
</td>

<td class="tdboderCod">
<a id="<?php echo($id_visualizar); ?>" onClick="return submit_Action_Check1('<?php echo($row['pesquisa_id']); ?>','','','','visualizacao_pesquisa_det.php?pesquisa_id=<?php echo($row['pesquisa_id']); ?>','A pesquisa de <?php echo($row['mes_nome']."/".$row['pesquisa_ano']); ?> ainda nao foi fechada!');" ><img src="<?php echo($img_visualizar); ?>" border="0"/></a>
</td>
<td class="tdboderCod" id="<?php echo($id_situacao); ?>" >
	<a href="javascript: void(0)" onClick="muda_cadeado('<?php echo($mudar_cadeado); ?>','<?php echo $row['pesquisa_id']; ?>','<?php echo($url); ?>',' a pesquisa de <?php echo($row['mes_nome']."/".$row['pesquisa_ano']); ?> ?');" ><img src="<?php echo $img_cadeado; ?>" border="0"/></a>
	<?php if($mudar_cadeado == 1) { ?><a id="flag"></a><?php }?>
</td>

								 </tr>
						
								 <?php	   
								 }//do while
						 ?>
						 </table>
						 <?php } ?>
			<table align="left" style="border:0; margin-top:10px;" width="590px;">
			<tr>
			<td align="left">&nbsp;
			<?php if ($start_rec!=0) {?>
			<span class="pag_links"><a href="javascript: void(0)" onClick="atualiza_tabela('0');" >Primeira</a></span>&nbsp;&nbsp;|&nbsp;<?php }else{ ?> <span class="pag_links">Primeira</span>&nbsp;&nbsp;|&nbsp;<?php }?>
			<?php if ($back_rec>=0) {?>
			<span class="pag_links"><a href="javascript: void(0)" onClick="atualiza_tabela('<?php echo($back_rec); ?>')">Anterior</a></span>
			<?php }else{?>
			<span class="pag_links">Anterior</span>
			<?php } ?>
			</td>
			<td align="center">
			P&aacute;gina: <b><?php echo($pagina_atual); ?></b> &nbsp;&nbsp; Resultados: <b><? echo((($total_rec) ? $start_rec+1 : 0)." - ".$last_rec." ") ?></b> de <b><?php  echo($total_rec); ?></b>
			</td>
			<td align="right">
			<?php if ($last_rec<$total_rec) {?>
			<span class="pag_links"><a href="javascript:void(0)" onClick="atualiza_tabela('<?php echo($last_rec); ?>')" >Pr&oacute;xima</a></span>&nbsp;&nbsp;|&nbsp;<?php }else {?>
			<span class="pag_links">Pr&oacute;xima</span>&nbsp;&nbsp;|&nbsp;
			<?php } ?>
			<?php if ($start_rec+$records_per_page<$total_rec) {?>
			<span class="pag_links"><a href="javascript: void(0)" onClick="atualiza_tabela('<?php echo($start_last_page); ?>')">&Uacute;ltima</a></span>
			<?php }else{ ?>
				<span class="pag_links">&Uacute;ltima</span>
			<?php } ?>
</td>
</tr>
</table>