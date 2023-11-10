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
					<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?><a href="cadastro_pesquisas.php"><img style=" float:right; border:none; margin-right:5px;" src="images/seta_azul.png" ></a></h1>
					<h1 id="Mcaption" style="text-align:left">Cadastro de Coletas</h1>
						<table  border="0px" id="link_table_add"  style="width:642px"><tr><td>[<a href="add_varias_coletas.php?pesquisa_id=<?php echo($pesquisa_id);?>" id="link_adicionar" >Adicionar Coletas</a>]&nbsp;&nbsp;&nbsp;[<a href="add_coletas.php?pesquisa_id=<?php echo($pesquisa_id);?>" id="link_adicionar" >Adicionar</a>]</td></tr></table>
					
					<?php } ?>
					<?php 
						  	$strsql = "SELECT * FROM tabela_coletas A, tabela_estabelecimentos B,tabela_cidades C,tabela_bairros D WHERE A.pesquisa_id = '".$pesquisa_id."' AND (A.estabelecimento_id = B.estabelecimento_id) AND (B.bairro_id = D.bairro_id) AND (D.cidade_id = C.cidade_id) ORDER BY A.coleta_id,B.estabelecimento_nome";
							
							$coletas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
							$qtd_prod = mysqli_num_rows($coletas);
							
					if ($coletas && mysqli_num_rows($coletas)>0){	
					?>
					<form method="post">
					<table cellspacing="0" id="listTable" summary="Tabela de Coletas" style="width:630px;">
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
							<th scope="col" class="tdboder">Estabelecimento &nbsp;&nbsp;</th>
							<th scope="col" class="tdboderCod">Bairro</th>
							<th scope="col" class="tdboderCod">Cidade</th>
							<th scope="col" class="tdboder">Data</th>
							<th scope="col" class="tdboder">Pre&ccedil;o M&eacute;dio</th>
							<th scope="col" colspan="5" class="tdboderCod">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($coletas)){
								if(!isset($l_cor)) $l_cor = '';
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";
								
								$id_editar = $row['coleta_id']."edit";
								$id_excluir = $row['coleta_id']."del";
								$id_situacao = $row['coleta_id']."sit"; 
								$id_carrinho = $row['coleta_id']."car";
								$id_visualizar = $row['coleta_id']."vis";
								$url = 'edita_coleta.php';
								
								
								//coleta situacao = 0 no banco de dados quer dizer que a coleta está aberta
																
								if($row['coleta_fechada'] == 0)
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
						  

<tr class="<?php echo ($l_cor);?>">
	<td class="tdboderCod"><?php echo($row['coleta_id']); ?></td>
	<td class="tdboder"><?php echo($row['estabelecimento_nome']); ?></td>
	<td class="tdboder"><?php echo($row['bairro_nome']); ?></td>
	<td class="tdboder"><?php echo($row['cidade_nome']); ?></td>
	<td class="tdboder"><?php echo(formata_data($row['coleta_data'],1)); ?></td>
	<td class="tdboderCod"><?php echo($row['coleta_preco_cesta']==0?" - ":$row['coleta_preco_cesta']); ?></td>
	
	<td class="tdboderCod"><a href="javascript: void(0)" onClick="return submit_Action_Check('<?php echo($row['coleta_id']); ?>','edit', '','','add_coletas.php?pesquisa_id=<?php echo($pesquisa_id); ?>','<?php echo("Coleta no estabelecimento: ".$row['estabelecimento_nome']." de ".formata_data($row['coleta_data'],1)." está fechada!");?>');" id="<?php echo $id_editar?>" ><img src="<?php echo $img_editar; ?>" border="0"></a></td>
<td class="tdboderCod"><a href="javascript: void(0)" onClick="return submit_Action_Check('<?php echo($row['coleta_id']); ?>','del','<?php echo($row['estabelecimento_nome']." de ".formata_data($row['coleta_data'],1)); ?>','Deseja apagar a coleta ','<?php echo("add_coletas.php?pesquisa_id=".$pesquisa_id); ?>','<?php echo("Coleta no estabelecimento: ".$row['estabelecimento_nome']." de ".formata_data($row['coleta_data'],1)." está fechada!");?>');" id="<?php echo $id_excluir?>" ><img src="<?php echo $img_excluir; ?>" border="0"></a></td> 
	<td class="tdboderCod"><a href="javascript: void(0)" id="<?php echo $id_carrinho; ?>" onClick="return submit_Action_Check('<?php echo($row['coleta_id']); ?>','','','','cadastro_coletas_precos.php?coleta_id=<?php echo($row['coleta_id']); ?>&pid=<?php echo($pesquisa_id); ?>','<?php echo("Coleta no estabelecimento: ".$row['estabelecimento_nome']." de ".formata_data($row['coleta_data'],1)." está fechada!");?>');"><img src="<?php echo $img_carrinho; ?>" border="0"></a></td>
 	<td class="tdboderCod" ><a id="<?php echo $id_visualizar; ?>" onClick="return submit_Action_Check1('<?php echo($row['coleta_id']); ?>','','','','visualizacao_coleta_precos.php?coleta_id=<?php echo($row['coleta_id']); ?>&pid=<?php echo($pesquisa_id); ?>','<?php echo("Coleta no estabelecimento: ".$row['estabelecimento_nome']." de ".formata_data($row['coleta_data'],1)." ainda não foi fechada!");?>');"><img src="<?php echo $img_visualizar; ?>" border="0"></a></td>
	<td class="tdboderCod" id="<?php echo $id_situacao; ?>" ><a href="javascript: void(0)" onClick="muda_cadeado('<?php echo $mudar_cadeado; ?>','<?php echo $row['coleta_id']; ?>','<?php echo $url; ?>',' a coleta: <?php echo($row['estabelecimento_nome']." de ".formata_data($row['coleta_data'],1)); ?> ?')" ><img src="<?php echo $img_cadeado; ?>" border="0"></a><?php if($mudar_cadeado == 1) { ?><a id="flag"></a><?php }?></td>


 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>
						 </form>	
						 <p class="legenda_tabela">
						<b>Quantidade: (<?php echo($qtd_prod); ?>)</b>
						</p>
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem coletas cadastradas</h1>
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