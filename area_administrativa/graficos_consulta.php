<?php
	$pesquisa_id = $_REQUEST['hid'];
        require("cabecalho.php");
?>
<body>
 
		
		<div class="caixa_principal">

			<?php require("topo.php"); ?>
			
			<div class="menu_superior">
			<?php require("menu_superior.php"); ?>
			</div>
			
			<div class="menu_lateral">
			<?php  require("menu_lateral_boletim.php"); ?>
			</div>
			
			<div class="conteudo_pagina">	
			<?php 
				
				$strsql = "SELECT A.pesquisa_id,B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano,B.mes_id FROM tabela_pesquisas A, tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_id = '".$pesquisa_id."'";
				$pesquisas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($pesquisas && mysqli_num_rows($pesquisas)>0)
					{
						$row = mysqli_fetch_array($pesquisas);
						
					$mes_atual = $row['mes_nome'];
					$pesquisa_mes = $row['mes_id'];
					$pesquisa_ano = $row['pesquisa_ano'];
					$data_mes_anterior = formata_mes_anterior($pesquisa_mes,$pesquisa_ano);
					
					$pesquisa_mes_anterior = retorna_pesquisa($data_mes_anterior[0],$data_mes_anterior[1]);
					}

			?>
					<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?><a href="boletim.php"><img style=" float:right; border:none; margin-right:39px;" src="images/seta_azul.png" ></a></h1><br />
<br />
<fieldset style="width:600px;">
<legend>Gr&aacute;ficos</legend>
<form name="form">
			<p>
			<label style="color:#003366">Cidade:</label>
			<select name="cidade" style="color:#003366" id="cidade_id">
				
						<option value="0"><--------   Escolha a Cidade  --------></option>
					
						<?php
							
							$strsql = "SELECT * FROM tabela_cidades";
							$cidades = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
							if ($cidades && mysqli_num_rows($cidades)>0)	
								while($row = mysqli_fetch_array($cidades))
								{
						?>
						
						<option style="color: #003366" value="<?php echo($row['cidade_id']); ?>" > <?php echo ($row['cidade_nome']);?></option>
					
						<?php
						}	 	
						?>	
										
			</select>
		</p>
		<p>
		<label style="color:#003366">Gr&aacute;fico:</label>
		<select style="color:#003366" name="grafico" id="grafico">
				
						<option value="0"><--------   Escolha o Gr&aacute;fico  --------></option>
						<option value="gra_custo_cesta">Custo da Cesta B&aacute;sica</option>	
						<option value="gra_preco_produtos">Pre&ccedil;o dos Produtos</option>									
			</select>
			&nbsp;&nbsp;&nbsp;<input type="button" onClick="visualizacao_grafica('<?php echo($pesquisa_ano); ?>','<?php echo($pesquisa_id); ?>');" value="Gerar"/>
		</p>
		</form>
		<div id="info_pesquisa">
		</div>	

		</fieldset>
		
</div>
			
			
			<div class="clearer"><span></span></div>

			<div class="rodape">&nbsp </div>

</div>
	
</body>
</html>
<script type="text/javascript" language="javascript">

	
	function visualizacao_grafica(ano,pesquisa)
	{
		var cidade = document.getElementById("cidade_id");
		var cidade_id = cidade.value;
		
		var url = document.getElementById("grafico").value;
		
		var info_pesquisa = document.getElementById("info_pesquisa");
			
		if(cidade != 0 && url != 0)
		{
			url += '.php';
			info_pesquisa.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/carregando.gif"/>';
			url += '?cit=' + cidade_id + '&ano=' + ano + '&hid=' + pesquisa;
			new Ajax.Updater('info_pesquisa',url, {method: 'post',asynchronous:true});
		}
		else
			alert('Para geração de gráfico com sucesso\nescolha uma cidade e um gráfico!');
	}
</script>