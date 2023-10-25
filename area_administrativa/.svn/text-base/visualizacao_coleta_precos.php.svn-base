<?php 
	$coleta_id = $_REQUEST['coleta_id'];
	$produto_id = $_REQUEST['hid'];
	$action = $_REQUEST['haction'];
	$pesquisa_id = $_REQUEST['pid'];
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
			<!-- Contedo referente a esta página -->
			<?php
			
			$strsql = "SELECT B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano FROM tabela_pesquisas A, tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_id = '".$pesquisa_id."'";
			$pesquisas = mysql_query($strsql) or die(mysql_error());
							
			if ($pesquisas && mysql_num_rows($pesquisas)>0){
				$row = mysql_fetch_array($pesquisas)	
			?>
			<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?><a href="cadastro_coletas.php?coleta_id=<?php echo($coleta_id);?>&pesquisa_id=<?php echo($pesquisa_id);?>"><img style=" float:right; border:none; margin-right:10px;" src="images/seta_azul.png" ></a></h1>
			<?php
			}
			
			$strsql = "SELECT B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano FROM tabela_pesquisas A, tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_id = '".$pesquisa_id."'";
			$pesquisas = mysql_query($strsql) or die(mysql_error());
							
			if ($pesquisas && mysql_num_rows($pesquisas)>0)
			{
				$row = mysql_fetch_array($pesquisas);
		
					
				$strsql = "SELECT * FROM tabela_coletas A, tabela_estabelecimentos B,tabela_bairros C,tabela_cidades D  WHERE (A.estabelecimento_id = B.estabelecimento_id) AND coleta_id = '".$coleta_id."' AND C.bairro_id = B.bairro_id AND C.cidade_id = D.cidade_id";	
				$coletas = mysql_query($strsql) or die(mysql_error());
							
				if ($coletas && mysql_num_rows($coletas)>0)
				{
					$row = mysql_fetch_array($coletas);		
					$estabelecimento_nome = $row['estabelecimento_nome'];
					$total_coleta = $row['coleta_preco_cesta'];
					$data = formata_data($row['coleta_data'],1);
					$bairro = $row['bairro_nome'];
					$cidade = $row['cidade_nome'];
							
				}
			?>
			<h3 style="text-align:left">Local:<?php echo ($cidade." - ".$bairro); ?> 
			<br />Coleta: <?php echo ($estabelecimento_nome." - ".$data); ?>
			</h3>
			<?php 
			}
			?>
			<h3 style="text-align:left">Produtos Oficiais</h3>
			<div id="produtos_oficiais">	
				<?php include("vis_produtos_oficiais.php"); ?>
			</div>
			<br /><br /><br />
			<h3 style="text-align:left">Produtos N&atilde;o Oficiais</h3>
			<div id="produtos_n_oficiais">	
				<?php include("vis_produtos_n_oficiais.php"); ?>
			</div>


		</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>

</div>
</body>
</html>
<script language="javascript" type="text/javascript">
		
	function atualiza_tabela(pag,div,coleta)
	{
	
		var url = 'vis_produtos_oficiais.php?coleta_id=' + escape(coleta) + '&hp=' + escape(pag);
		div.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/carregando.gif"/>';
		new Ajax.Updater(div,url, {method: 'post',asynchronous:true});
	}
</script>