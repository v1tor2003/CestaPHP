<?php 
	$cidade_id = $_REQUEST['hid'];
	$pesquisa_id = $_REQUEST['pid'];
	
	$codigo = $_REQUEST['codigo'];
	$produto_selecionados = $_REQUEST['produtos_selecionados'];
	$produto_id = $_REQUEST['produto_id'];
	$action = $_REQUEST['haction'];
	$precos = $_REQUEST['precos_produto'];
	$flag = false;
	$herr_flag = false;
	$records_per_page = $_REQUEST['rpp'];
	$aux = array();
	
	
	if($action == 'salvar')
	{
	
		$strsql = "DELETE FROM tabela_pesquisa_resultados_produtos WHERE pesquisa_id = ".$pesquisa_id." AND cidade_id = ".$cidade_id;
		
		$res = mysql_query($strsql) or die(mysql_error());
		
		for($i=0;$i<count($codigo);$i++)
		{
			$strsql = "INSERT INTO tabela_pesquisa_resultados_produtos(produto_id,produto_preco_medio,pesquisa_id,cidade_id) VALUES ('".$codigo[$i]."','".$precos[$i]."','".$pesquisa_id."','".$cidade_id."')";	
			
			$res = mysql_query($strsql) or die(mysql_error());
	
		}
		
		
		$herr="Precos salvos com sucesso!";
		$herr_flag = true;
		$action = '';
	}

	
	if($action == 'adicionar_produto')
	{
		
		for($i=0;$i<count($produto_selecionados);$i++)
		{
			$strsql = "INSERT INTO tabela_pesquisa_resultados_produtos (produto_id,cidade_id,pesquisa_id) VALUES ('".$produto_selecionados[$i]."','".$cidade_id."','".$pesquisa_id."')";	
			$res = mysql_query($strsql) or die(mysql_error());
						
		}
		
		$action = '';
		$medida = NULL;
		$produto_nome = NULL;
	}
	
	if ($action=='del')
	{	
		
		$strsql = "DELETE FROM tabela_pesquisa_resultados_produtos WHERE cidade_id = '".$cidade_id."' AND produto_id = '".$produto_id."' AND pesquisa_id = '".$pesquisa_id."'";
	
		mysql_query($strsql) or die(mysql_error());	
		header("Location:".$_SERVER['PHP_SELF']."?hid=".$cidade_id."&rpp=".$records_per_page."&pid=".$pesquisa_id);	
				
	}
	
	
	if($records_per_page == '')
		$records_per_page = 12;		
  	$start_rec = ($_REQUEST['hp']!='') ? $_REQUEST['hp'] : 0;
	
	$strsql = "SELECT * FROM tabela_produtos B NATURAL JOIN tabela_pesquisa_resultados_produtos P WHERE P.pesquisa_id = '".$pesquisa_id."' AND P.cidade_id = '".$cidade_id."'  LIMIT ".$start_rec.",".$records_per_page;
 
  	$produtos = mysql_query($strsql) or die(mysql_error());
  	$total_rec = mysql_num_rows($produtos);
	if ($start_rec>=$total_rec) $start_rec -= $records_per_page;
	if ($start_rec<0) $start_rec=0;	
  	$last_rec = ($start_rec + $records_per_page > $total_rec) ? $total_rec : $start_rec + $records_per_page;  
  	$back_rec = $start_rec - $records_per_page;
  	$pages = floor($total_rec/$records_per_page);
  	$start_last_page = ($pages*$records_per_page==$total_rec) ? ($pages-1)*$records_per_page : $pages*$records_per_page;
  	$pagina_atual = floor(($start_rec == 0 )? 1 : (($start_rec/$records_per_page)+1));
  
	
require("cabecalho.php");

?>
<body>
 
		
		<div class="caixa_principal">

			<?php require("topo.php"); ?>
			
			<div class="menu_superior">
			<?php require("menu_superior.php"); ?>
			</div>
			
			<div class="menu_lateral">
			<?php require("menu_lateral_coletas.php"); ?>
			</div>
			
			
			<div id="principal" class="conteudo_pagina">
			<!-- Contedo referente a esta pgina -->
			
			<?php 
			
			$strsql = "SELECT B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano FROM tabela_pesquisas A, tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_id = '".$pesquisa_id."'";
			
			$pesquisas = mysql_query($strsql) or die(mysql_error());
			if ($pesquisas && mysql_num_rows($pesquisas)>0)
			{
				$row = mysql_fetch_array($pesquisas);
				
				$detalhes = $row['mes_nome']." / ".$row['pesquisa_ano']; 
			}	
					
			$strsql = "SELECT * FROM tabela_cidades WHERE cidade_id = '".$cidade_id."' ";
					
			$coletas = mysql_query($strsql) or die(mysql_error());
							
			if ($coletas && mysql_num_rows($coletas)>0)
			{
				$row = mysql_fetch_array($coletas);		
				$cidade_nome = $row['cidade_nome'];
						
			}
			?>
			<a href="<?php echo('cadastro_precos_cidade.php?pesquisa_id='.$pesquisa_id); ?>"><img style=" float:right; border:none;" src="images/seta_azul.png" ></a>
			<h1 id="Mcaption" style="text-align:left">Cidade: <?php echo ($cidade_nome); ?></h1>
			<h1 id="Mcaption" style="text-align:left">Data: <?php echo ($detalhes); ?></h1>
			<hr />
			<h1 id="Mcaption" style="text-align:left">Produtos Cadastrados</h1>
			<!--<table  border="0px" id="link_table_add"  style="width:590px"><tr><td>[<a href="" id="link_adicionar" >Adicionar Produtos</a>]</td></tr></table>-->
			<?php 
					
				$strsql = "SELECT * FROM tabela_produtos B NATURAL JOIN tabela_pesquisa_resultados_produtos P WHERE P.pesquisa_id = '".$pesquisa_id."' AND P.cidade_id = '".$cidade_id."'  LIMIT ".$start_rec.",".$records_per_page;
				
				
				$produtos = mysql_query($strsql) or die(mysql_error());
				if ($produtos && mysql_num_rows($produtos)>0){
				
				$qtd_prod = mysql_num_rows($produtos);
			?>
			<form method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>">
			<a href="<?php echo('cadastro_coletas.php?pesquisa_id='.$pesquisa_id); ?>"> </a>
			<table cellspacing="0" id="listTable" summary="Tabela de Preços das Coletas" style="width:585px;">
				<colgroup>
					<col id="codigo" />
					<col id="produto" />
					<col id="medida" />
					<col id="preco" />
				</colgroup>		
			
				<thead>
					<tr>
						<th scope="col" class="tdboderCod">C&oacute;digo</th>
						<th scope="col" class="tdboder">Produto</th>
						<th scope="col" class="tdboder">Pre&ccedil;o M&eacute;dio</th>
						<th scope="col" class="tdboder">A&ccedil;&atilde;o</th>
					</tr>
				</thead>
				<?php
						while ($row = mysql_fetch_array($produtos))
						{
			
							if($l_cor == '') 
								$l_cor = "par";
							else
								$l_cor = "";
				?>
				<tr class="<?php echo ($l_cor);?>">
					<td class="tdboderCod"><?php echo($row['produto_id']); ?></td>
					<td class="tdboder"><?php echo($row['produto_nome_visualizacao']); ?></td>
					<input type="hidden" name="codigo[]" value="<?php echo($row['produto_id']);?>"  />
					<td class="tdboder"  width="50" align="center"><input type="text" size="4" name="precos_produto[]" onkeypress="mascara(this,soNumeros)" maxlength="5" value="<?php echo($row['produto_preco_medio']==0?" ":$row['produto_preco_medio']);?>"  /></td>
					<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action2('<?php echo($row['cidade_id']); ?>','<?php echo($row['medida_id']); ?>','del','<?php echo($row['produto_nome_visualizacao']); ?>','Deseja apagar o produto ','<?php echo($_SERVER['PHP_SELF']."?produto_id=".$row['produto_id']."&pid=".$pesquisa_id); ?>')"><img src="images/botao_deletar.png" border="0"></a></td>
				</tr>
				
				<?php	   
					}//do while
				?>
			</table>
			<input type="hidden" value="<?php echo($cidade_id); ?>" name="hid" />
			<input type="hidden" value="<?php echo($pesquisa_id);?>" name="pid" />
			<input type="hidden" value="<?php echo($records_per_page);?>" name="rpp" />
			<input type="hidden" value="<?php echo($start_rec); ?>" name="hp" />
			<input type="hidden" value="salvar" name="haction" />
			<p class="legenda_tabela">
			<b>Quantidade: (<?php echo($total_rec); ?>)</b>
			</p>
			<p style="width:570px;">
			&nbsp;&nbsp;&nbsp;Listagem por p&aacute;gina: <a href="<?php echo($_SERVER['PHP_SELF']."?hid=".$cidade_id."&rpp=5&pid=".$pesquisa_id); ?>">5</a> <a href="<?php echo($_SERVER['PHP_SELF']."?hid=".$cidade_id."&rpp=10&pid=".$pesquisa_id); ?>">10</a> <a href="<?php echo($_SERVER['PHP_SELF']."?hid=".$cidade_id."&rpp=12&pid=".$pesquisa_id); ?>"a>12</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" value="Salvar" />
			</p>
			
			</form>
			 <table align="left" style="border:0; margin-top:10px;" width="570px;">
			<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if ($start_rec!=0) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=0&hid=".$cidade_id."&pid=".$pesquisa_id); ?>" >Primeira</a></span>&nbsp;&nbsp;|&nbsp;<?php }else{ ?> <span class="pag_links">Primeira</span>&nbsp;&nbsp;|&nbsp;<?php }?>
			<?php if ($back_rec>=0) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($back_rec)."&hid=".$cidade_id."&pid=".$pesquisa_id); ?>">Anterior</a></span>
			<?php }else{?>
			<span class="pag_links">Anterior</span>
			<?php } ?>
			</td>
			<td align="center">
			P&aacute;gina: <b><?php echo($pagina_atual); ?></b> &nbsp;&nbsp; Resultados: <b><? echo((($total_rec) ? $start_rec+1 : 0)." - ".$last_rec);?></b> de <b><?php  echo($total_rec); ?></b>
			</td>
			<td align="left">
			<?php if ($last_rec<$total_rec) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($last_rec)."&cidade_id=".$cidade_id."&pid=".$pesquisa_id); ?>">Pr&oacute;xima</a></span>&nbsp;&nbsp;|&nbsp;<?php }else {?>
			<span class="pag_links">Pr&oacute;xima</span>&nbsp;&nbsp;|&nbsp;
			<?php } ?>
			<?php if ($start_rec+$records_per_page<$total_rec) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($start_last_page)."&hid=".$coleta_id."&pid=".$pesquisa_id); ?>">&Uacute;ltima</a></span>
			<?php }else{ ?>
				<span class="pag_links">&Uacute;ltima</span>
			<?php } ?>
			
			</td>
			</tr>
			</table>
			<br /><br /><br />
			<?php }else{ ?>
				 <h1 id="Mcaption" style="text-align:left">&nbsp;&nbsp;Sem produtos cadastrados nesta cidade</h1>
			<?php }?>	
					
			<hr />	
			<h1 id="Mcaption" style="text-align:left">Produtos N&atilde;o Cadastrados nesta Coleta</h1>
			<form id="form_produto" name="form_produto" method="post" style="padding-left:170px;">
			<table>
			<tr>
			<td>
					<select name="produtos_selecionados[]" multiple="multiple" size="12" style="width:180px;" >
					<?php
						
					$strsql = "SELECT * FROM tabela_produtos A WHERE (A.produto_id) NOT IN (SELECT produto_id FROM tabela_pesquisa_resultados_produtos WHERE pesquisa_id = '".$pesquisa_id."' AND cidade_id = '".$cidade_id."') ORDER BY A.produto_id";
					
					$produtos = mysql_query($strsql) or die(mysql_error());
					
					if ($produtos && mysql_num_rows($produtos)>0)	
						while($row = mysql_fetch_array($produtos))
								{
					?>
						
					<option value="<?php echo($row['produto_id']); ?>" >
						 <?php echo ($row['produto_nome_visualizacao']);?>
					</option>
					
					<?php
					}	 	
					?>	
										
					</select>
					<input type="hidden" value="adicionar_produto" name="haction"/>
					<input type="hidden" value="<?php echo($cidade_id); ?>" name="hid" />
					<input type="hidden" value="<?php echo($pesquisa_id);?>" name="pid" />
					<input type="hidden" value="<?php echo($records_per_page);?>" name="rpp" />
					<input type="hidden" value="<?php echo($start_rec); ?>" name="hp" />
				</td>
				<td>
					<input type="submit" value="Adicionar" />
				</td>
				</tr>
				</table>
				</form>
			
		</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>

</div>

<?php if($herr_flag == true){ ?>
			
			<script type="text/javascript" language="javascript">
			
			alert('<?php echo($herr);?>');
			
			</script>
			
			
<?php } ?> 
<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
<input type="hidden" name="hid1" value="">
</form>	
</body>
</html>