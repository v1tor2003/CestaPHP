<?php
	$pesquisa_id = $_REQUEST['pesquisa_id'];

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
			
			<div class="conteudo_pagina">
			<!-- Contedo referente a esta pgina -->
					<?php 
					$strsql = "SELECT B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano FROM tabela_pesquisas A, tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_id = '".$pesquisa_id."'";
					$pesquisas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($pesquisas && mysqli_num_rows($pesquisas)>0){
						$row = mysqli_fetch_array($pesquisas)	
					?>
					<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?><a href="cadastro_pesquisas_antigas.php"><img style=" float:right; border:none; margin-right:100px;" src="images/seta_azul.png" ></a></h1>
					<h1 id="Mcaption" style="text-align:left">Cadastro de Cidades</h1>
						<table  border="0px" id="link_table_add"  style="width:550px"><tr><td>[<a href="add_coletas_cidades.php?pesquisa_id=<?php echo($pesquisa_id);?>" id="link_adicionar" >Adicionar</a>]</td></tr></table>
					
					<?php } ?>
					<?php 
						  	$strsql = "SELECT * FROM tabela_cidades A JOIN tabela_pesquisas_cidades B ON A.cidade_id = B.cidade_id  WHERE B.pesquisa_id = '".$pesquisa_id."' ORDER BY B.pesquisa_id";
							
							$coletas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
							$qtd_prod = mysqli_num_rows($coletas);
							
					if ($coletas && mysqli_num_rows($coletas)>0){	
					?>
					<form method="post" style="padding-left:50px;">
					<table cellspacing="0" id="listTable" summary="Tabela de Coletas" style="width:500px;">
					<colgroup>
						<col id="codigo" />
						<col id="estabelecimento" />
						<col id="cidade" />
						<col id="data" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Cidade</th>
							<th scope="col" colspan="2" class="tdboderCod">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($coletas)){
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";							
						 ?>
						  

<tr class="<?php echo ($l_cor);?>">
	<td class="tdboderCod"><?php echo($row['pesquisa_id']); ?></td>
	<td class="tdboder"><?php echo($row['cidade_nome']); ?></td>
		<td class="tdboderCod"><a href="javascript: void(0)" onClick="return submit_Action('<?php echo($row['cidade_id']); ?>','','','','<?php echo("add_precos.php?hid=".$row['cidade_id']."&pid=".$pesquisa_id); ?>')" ><img src="images/carrinho.gif" border="0"></a></td>
	<td class="tdboderCod"><a href="javascript: void(0)" onClick="return submit_Action('<?php echo($row['cidade_id']); ?>','del','<?php echo($row['cidade_nome']); ?>','Deseja apagar a pesquisa em ','<?php echo("add_coletas_cidades.php?pesquisa_id=".$pesquisa_id); ?>')" ><img src="images/botao_deletar.png" border="0"></a></td>
 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>
						 </form>	
						 <p class="legenda_tabela" style="padding-left:50px;">
						<b>Quantidade: (<?php echo($qtd_prod); ?>)</b>
						</p>
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem Cidades Cadastradas</h1>
						 <?php }?>
						 
							<br /><br />
			
	</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>

</div>
	
</body>
</html>

<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
</form>