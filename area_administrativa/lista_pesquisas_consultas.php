<?php 
	$action = $_REQUEST['haction'];
	
	if($action == 'filtrar')
	{
		$campo_filtrado = $_REQUEST['campo_filtrado'];
		
		switch($campo_filtrado)
		{
			case "mes": $campo_filtrado = 'EXTRACT(MONTH FROM A.pesquisa_data)';
						break;
			case "ano": $campo_filtrado = 'A.pesquisa_data';//antes...EXTRACT(YEAR FROM A.pesquisa_data)
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
			case "ano": $ordenar_por[0] = 'A.pesquisa_data';//antes...EXTRACT(YEAR FROM A.pesquisa_data)
						break;
			default: $ordenar_por[0] = 'A.pesquisa_id';
						
		}
		
		$strsql = "SELECT A.pesquisa_id,B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano,pesquisa_fechada FROM tabela_pesquisas A,tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_fechada = '1' AND ".$campo_filtrado." ".$operador_filtro." '".$valor_filtro."' ORDER BY ".$ordenar_por[0]." ".$ordenar_por[1];
		
	}
	else
	{
		$strsql = "SELECT A.pesquisa_id,B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano,pesquisa_fechada FROM tabela_pesquisas A,tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_fechada = '1' ORDER BY A.pesquisa_data DESC";
	}
	
	  $records_per_page = 10;		
	  $start_rec = ($_REQUEST['hp']!='') ? $_REQUEST['hp'] : 0;  
	  $pesquisas = mysql_query($strsql) or die(mysql_error());
	  $total_rec = mysql_num_rows($pesquisas);
		if ($start_rec>=$total_rec) $start_rec -= $records_per_page;
		if ($start_rec<0) $start_rec=0;	
	  $last_rec = ($start_rec + $records_per_page > $total_rec) ? $total_rec : $start_rec + $records_per_page;  
	  $back_rec = $start_rec - $records_per_page;
	  $pages = floor($total_rec/$records_per_page);
	  $start_last_page = ($pages*$records_per_page==$total_rec) ? ($pages-1)*$records_per_page : $pages*$records_per_page;
	 
	  $pagina_atual = floor(($start_rec == 0 )? 1 : (($start_rec/$records_per_page)+1));

      $strsql = $strsql." LIMIT ".$start_rec.",".$records_per_page; 
							
	  $pesquisas = mysql_query($strsql) or die(mysql_error());
							
	  if ($pesquisas && mysql_num_rows($pesquisas)>0){	
					?>
					<table cellspacing="0" id="listTable" summary="Tabela de Pesquisas" style="width:580px;">
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
							<th scope="col" colspan="3" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysql_fetch_array($pesquisas))
							{
							
								if($l_cor == '') 
									$l_cor = "par";
								else 
									$l_cor = "";
							
						  ?>
							   <tr class="<?php echo ($l_cor);?>" id="<?php echo($id_row); ?>">
								 <td class="tdboderCod"><?php echo($row['pesquisa_id']); ?></td>
								 <td class="tdboder"><?php echo($row['mes_nome']); ?></td>
								<td class="tdboder"><?php echo($row['pesquisa_ano']); ?></td>
								<td class="tdboderCod"><a href="javascript: void(0)" onClick="return submit_Action('<?php echo($row['pesquisa_id']);?>','','','','resultados_pesquisa.php');"><img src="images/b_view.png" border="0" title="Pr&eacute; - Visualiza&ccedil;&atilde;o" /></a></td>
								<td class="tdboderCod"><a href="javascript: void(0)" onClick="return submit_Action('<?php echo($row['pesquisa_id']);?>','','','','boletim_rtf.php');"><img src="images/word_icon.png" border="0" title="Exportar para WORD" /></a></td>
								<td class="tdboderCod"><a href="javascript: void(0)" onClick="return submit_Action('<?php echo($row['pesquisa_id']);?>','','','','graficos_consulta.php');"><img src="images/icon_graph.gif" border="0" title="Imprimir" /></a></td>
								 </tr>
						
								 <?php	   
								 }//do while
						 ?>
						 </table>
						 <?php } ?>
			<table align="left" style="border:0; margin-top:10px;" width="555px;">
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
			P&aacute;gina: <b><?php echo($pagina_atual); ?></b> &nbsp; Resultados: <b><? echo((($total_rec)?$start_rec+1:0)." - ".$last_rec); ?></b> de <b><?php  echo($total_rec); ?></b>
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