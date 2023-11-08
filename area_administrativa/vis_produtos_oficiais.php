<?php
	$total_oficiais = 0;
	$coleta_id = $_REQUEST['coleta_id'];
	$records_per_page = 12;		
	$start_rec = ($_REQUEST['hp']!='') ? $_REQUEST['hp'] : 0;
	$strsql = "SELECT COUNT(A.produto_id) AS QT,SUM(C.precos_total) AS TOT FROM tabela_produtos A, tabela_unidade_medidas B,tabela_precos C WHERE (A.produto_id = C.produto_id AND B.medida_id = C.medida_id) AND C.coleta_id = '".$coleta_id."' AND A.produto_cesta = '1' GROUP BY C.coleta_id";   
	
 	$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($produtos);
	$total_precos = $row['TOT'];
 	$total_rec = $row['QT'];
	
	
	if ($start_rec>=$total_rec) $start_rec -= $records_per_page;
	if ($start_rec<0) $start_rec=0;	
  	
	$last_rec = ($start_rec + $records_per_page > $total_rec) ? $total_rec : $start_rec + $records_per_page;  
  	$back_rec = $start_rec - $records_per_page;
  	$pages = floor($total_rec/$records_per_page);
  	$start_last_page = ($pages*$records_per_page==$total_rec) ? ($pages-1)*$records_per_page : $pages*$records_per_page;
 	$pagina_atual = floor(($start_rec == 0 )? 1 : (($start_rec/$records_per_page)+1));
	
	$strsql = "SELECT * FROM tabela_produtos A, tabela_unidade_medidas B,tabela_precos C WHERE (A.produto_id = C.produto_id AND B.medida_id = C.medida_id) AND coleta_id = '".$coleta_id."' AND A.produto_cesta = '1' ORDER BY A.produto_id LIMIT ".$start_rec.",".$records_per_page;
						
	$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	
	if ($produtos && mysqli_num_rows($produtos)>0)
	{
			
		$strsql = "SELECT MAX(quantidade) as maximo FROM (SELECT precos_id, count(*) as quantidade FROM tabela_auxiliar_precos GROUP BY precos_id) As table1";

		$res= mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 
		$res = mysqli_fetch_array($res);
		$qt = $res['maximo'];
	?>
	<table cellspacing="0" id="listTable" summary="Tabela de Preços das Coletas" style="width:655px;">
		<colgroup>
			<col id="codigo" />
			<col id="produto" />
			<col id="medida" />
			<col id="preco" />
			<col id="media" />
		</colgroup>		
		<thead>
			<tr>
				<th scope="col" class="tdboderCod">C&oacute;digo</th>
				<th scope="col" class="tdboder">Produto</th>
				<th scope="col" class="tdboder">Medida</th>
				<?php for($i=0;$i<$qt;$i++){ ?>
				<th scope="col" class="tdboder"><?php echo("Pre&ccedil;o ".($i +1));?></th>
				<?php } ?>
				<th scope="col" class="tdboder">M&eacute;dia Obs.</th>
				<th scope="col" class="tdboder">M&eacute;dia</th>
				<th scope="col" class="tdboder">Total</th>
			</tr>
		</thead>
		<?php
			while ($row = mysqli_fetch_array($produtos))
			{
				if($l_cor == '') $l_cor = "par"; else $l_cor = "";
		?>
			<tr class="<?php echo ($l_cor);?>">
				<td class="tdboderCod"><?php echo($row['produto_id']); ?></td>
				<td class="tdboder"><?php echo($row['produto_nome_visualizacao']); ?></td>
				<td class="tdboder"><?php echo($row['medida_simbolo']); ?></td>
				<?php
									 
				$strsql = "SELECT * FROM tabela_auxiliar_precos WHERE precos_id = '".$row['precos_id']."'";
				$precos_produto = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 
										
				for($i=0;$i<$qt;$i++)
				{
					$row1 = mysqli_fetch_array($precos_produto);						
				?>
					<td class="tdboder"  width="50" align="center"><?php echo("&nbsp;".isEmpty($row1['preco_produto'])); ?></td>
				<?php 
				}
				$strsql = "SELECT * FROM tabela_precos WHERE coleta_id = '".$coleta_id."' AND produto_id = '".$row['produto_id']."'";
					
				$resultados = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 
				$res = mysqli_fetch_array($resultados);
				$total_oficiais += $res['precos_total'];
						
				?>
				<td class="tdboderCod"><?php echo $res['precos_media_observado']; ?></td>
				<td class="tdboderCod"><?php echo $res['precos_media']; ?></td>
				<td class="tdboderCod"><?php echo $res['precos_total']; ?></td>

			</tr>		
			<?php	   
				}//do while
			?>
			<tr style="background-color: #C9CBCB">
				<td colspan="2" align="center">Sub-Total:</td>
				<td align="center" colspan="<?php echo($qt+4);?>">
				<?php echo ($total_oficiais); ?>
				</td>
			</tr>
			<tr style="background-color: #A4A4A4">
				<td colspan="2" align="center">Total:</td>
				<td align="center" colspan="<?php echo($qt+4);?>">
				<?php echo ($total_precos); ?>
				</td>
			</tr>
	</table>
	<table align="left" style="border:0; margin-top:10px;" width="585px;">
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if ($start_rec!=0) {?>
			<span class="pag_links"><a href="javascript: void(0)" onClick="atualiza_tabela('0','produtos_oficiais',<?php echo($coleta_id); ?>);" >Primeira</a></span>&nbsp;&nbsp;|&nbsp;<?php }else{ ?> <span class="pag_links">Primeira</span>&nbsp;&nbsp;|&nbsp;<?php }?>
			<?php if ($back_rec>=0) {?>
			<span class="pag_links"><a href="javascript: void(0)" onClick="atualiza_tabela('<?php echo($back_rec); ?>','produtos_oficiais',<?php echo($coleta_id); ?>)">Anterior</a></span>
			<?php }else{?>
			<span class="pag_links">Anterior</span>
			<?php } ?>
			</td>
			<td align="center">
			P&aacute;gina: <b><?php echo($pagina_atual); ?></b> &nbsp; Resultados: <b><? echo((($total_rec)?$start_rec+1:0)." - ".$last_rec); ?></b> de <b><?php  echo($total_rec); ?></b>
			</td>
			<td align="right">
			<?php if ($last_rec<$total_rec) {?>
			<span class="pag_links"><a href="javascript:void(0)" onClick="atualiza_tabela('<?php echo($last_rec); ?>','produtos_oficiais',<?php echo($coleta_id); ?>)" >Pr&oacute;xima</a></span>&nbsp;&nbsp;|&nbsp;<?php }else {?>
			<span class="pag_links">Pr&oacute;xima</span>&nbsp;&nbsp;|&nbsp;
			<?php } ?>
			<?php if ($start_rec+$records_per_page<$total_rec) {?>
			<span class="pag_links"><a href="javascript: void(0)" onClick="atualiza_tabela('<?php echo($start_last_page); ?>','produtos_oficiais',<?php echo($coleta_id); ?>)">&Uacute;ltima</a></span>
			<?php }else{ ?>
				<span class="pag_links">&Uacute;ltima</span>
			<?php } ?>
</td>
</tr>
</table>
<?php 
}
else
{
?>
	<h3 style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;Sem produtos oficiais cadastrados</h3>
<?php 
}
?>
