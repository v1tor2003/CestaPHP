<?php 
	$pesquisa_id = $_REQUEST['pesquisa_id'];
        $include_head[] = <<<EOS
        <link rel="stylesheet" type="text/css" href="estilos/ajaxtabs/ajaxtabs.css" media="screen" />\n
        <script type="text/javascript" language="javascript" src="../javascript/ajaxtabs/ajaxtabs.js"></script>	
EOS;
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
					<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?> <a href="cadastro_pesquisas.php"><img style=" float:right; border:none; margin-right:20px;" src="images/seta_azul.png" ></a></h1>
					<?php } ?>

			<form name="form_cidade">
			<p>
			<label style="color:#003366">Cidade:</label>
			<select id="cidade_id" style="color:#003366" onchange="visualiza_pesquisa()">
				
						<option  style="color: #003366" value="0"><--------   Escolha a Cidade  --------></option>
					
						<?php
							
							$strsql = "SELECT * FROM tabela_cidades";
							$cidades = mysql_query($strsql) or die(mysql_error());
					
							if ($cidades && mysql_num_rows($cidades)>0)	
								while($row = mysql_fetch_array($cidades))
								{
						?>
						
						<option style="color: #003366" value="<?php echo($row['cidade_id']); ?>" > <?php echo ($row['cidade_nome']);?></option>
					
						<?php
						}	 	
						?>	
										
			</select>
			<input type="hidden" name="hid" value="<?php echo($pesquisa_id); ?>" id="hid" />
		</p>
		</form>
			
					<div id="info_pesquisa">
					</div>	
					<br />				
				
			</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>

</div>
</body>
</html>
<script type="text/javascript" language="javascript">
function visualiza_pesquisa()
{
	var cidade = document.getElementById("cidade_id");
	var cidade_id = cidade.value;
	var hidden = document.getElementById("hid");
	var hid = hidden.value;
	var info_pesquisa = document.getElementById("info_pesquisa");
	var url = '';
	//var params= 'cidade_id='+$F(cidade_id)+'&hid='+$F(hid);
	
	if(cidade_id != 0)
	{
		info_pesquisa.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/carregando.gif"/>';
		url = 'info_pesquisa.php?cidade_id='+escape(cidade_id)+'&hid='+escape(hid);
		//url1 = 'filtro_pesquisa.php';
		new Ajax.Updater('info_pesquisa',url, {method: 'post',asynchronous:true, evalScripts:true});
		//new Ajax.Updater('filtro',url1, {method: 'post',asynchronous:true, evalScripts:true});
	}
}
</script>

