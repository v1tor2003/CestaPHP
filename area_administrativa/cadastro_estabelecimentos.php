<?php

require("cabecalho.php");
?>
<body>
	
	<div class="caixa_principal">

	<?php require("topo.php"); ?>
			
	<div class="menu_superior">
		<?php require("menu_superior.php"); ?>
	</div>
			
	<div class="menu_lateral">
		<?php require("menu_lateral_cadastros.php"); ?>
	</div>
			
	<div class="conteudo_pagina">
	<!-- Conteúdo referente a esta página -->
	<h1 id="Mcaption" style="text-align:left">Cadastro de Estabelecimentos</h1>
	<table  border="0px" id="link_table_add"  style="width:623px"><tr><td>[<a href="add_estabelecimentos.php" id="link_adicionar" >Adicionar</a>]</td></tr></table>
		<?php 
			
			$strsql = "SELECT * FROM tabela_estabelecimentos A,tabela_bairros B,tabela_cidades C WHERE A.bairro_id = B.bairro_id AND B.cidade_id = C.cidade_id ORDER BY A.estabelecimento_id";
			$estabelecimentos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
			if ($estabelecimentos && mysqli_num_rows($estabelecimentos)>0)
			{	
		?>
		
		<table cellspacing="0" id="listTable" summary="Tabela de Estabelecimentos" style="width:623px;">
		
			<colgroup>
				<col id="codigo" />
				<col id="nome" />
				<col id="cidade"/>
				<col id="bairro" />
                                <col id="ativo"/>
				<col id="acoes" />
			</colgroup>		
			
			<thead>
				<tr>
					<th scope="col" class="tdboderCod">C&oacute;digo</th>
					<th scope="col" class="tdboder">Nome</th>
					<th scope="col" class="tdboder">Cidade</th>
					<th scope="col" class="tdboder">Bairro</th>
                                        <th scope="col" class="tdborder">Ativo</th>
					<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
				</tr>
			</thead>
			
			<?php
				
				while ($row = mysqli_fetch_array($estabelecimentos))
				{
					if(!isset($l_cor)) $l_cor = '';
					if($l_cor == '') $l_cor = "par"; else $l_cor = "";
			 ?>
			 
			 <tr class="<?php echo ($l_cor);?>">
				 <td class="tdboderCod"><?php echo($row['estabelecimento_id']); ?></td>
				 <td class="tdboder"><?php echo($row['estabelecimento_nome']); ?></td>
				 <td class="tdboder"><?php echo($row['cidade_nome']); ?></td>
				 <td class="tdboder"><?php echo($row['bairro_nome']); ?></td>
                                 <td class="tdboder"><?php echo ($row['estabelecimento_ativo'])?"Sim":"N&atilde;o"; ?></td>
				 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['estabelecimento_id']); ?>','edit', '','','add_estabelecimentos.php');"><img src="images/botao_editar.png" border="0"></a></td>
				 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['estabelecimento_id']); ?>','del','<?php echo($row['estabelecimento_nome']); ?>','Deseja apagar o estabelecimento ','add_estabelecimentos.php');"><img src="images/botao_deletar.png" border="0"></a></td>
			</tr>
			
			<?php	   
				 
				 }//do while
			?>
		</table>	
		
		<?php }else{ ?>
		
		<h1 id="Mcaption" style="text-align:left">Sem estabelecimentos cadastrados</h1>
		
		<?php }?>
						 
						
		<br />
		<br />
			
	</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>
	
</div>
<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
</form>

</body>
</html>