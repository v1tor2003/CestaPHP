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
				$pesquisas = mysql_query($strsql) or die(mysql_error());
							
					if ($pesquisas && mysql_num_rows($pesquisas)>0)
					{
						$row = mysql_fetch_array($pesquisas);
						
					$mes_atual = $row['mes_nome'];
					$pesquisa_mes = $row['mes_id'];
					$pesquisa_ano = $row['pesquisa_ano'];
					$data_mes_anterior = formata_mes_anterior($pesquisa_mes,$pesquisa_ano);
					
					$pesquisa_mes_anterior = retorna_pesquisa($data_mes_anterior[0],$data_mes_anterior[1]);

					?>
					<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?><a href="boletim.php"><img style=" float:right; border:none; margin-right:39px;" src="images/seta_azul.png" ></a></h1><br />
			<?php 
			
			}// if do titulo da pesquisa 
				
				$strsql = "SELECT A.salario_id,B.pesquisa_id,A.salario_simbolo FROM tabela_salarios A,tabela_pesquisas B WHERE A.salario_id = B.salario_id AND B.pesquisa_id = '".$pesquisa_id."'";
				$salario = mysql_query($strsql) or die(mysql_error());
				$row = mysql_fetch_array($salario);
			?>
<h2 id="subtitulos" style="text-align:left">Custo da Cesta B&aacute;sica &nbsp;&nbsp;(<?php echo("em ".$row['salario_simbolo']); ?> )</h2>

<?php
	
	$strsql = "SELECT A.cidade_id,B.cidade_nome,A.pesquisa_id FROM tabela_pesquisas_cidades A,tabela_cidades B WHERE A.pesquisa_id = '".$pesquisa_id."' AND A.cidade_id = B.cidade_id";
	
	$gasto_mes_atual = array();
	$variacao_mensal = array();
	$gasto_mes_anterior = array();
	
	$res = mysql_query($strsql) or die(mysql_error());
	if($res && mysql_num_rows($res)>0)
	{
	
		while($row = mysql_fetch_array($res))
		{
			$cidade_id[] = $row['cidade_id'];
			$cidade_nome[] = $row['cidade_nome'];
		}
	
		$tam = count($cidade_id);
		
		for($i=0;$i<$tam;$i++)
		{
			$strsql = "SELECT A.pesquisa_id,A.gasto_mensal_cesta,A.variacao_mensal,C.cidade_id FROM tabela_pesquisas_cidades A,tabela_pesquisas B,tabela_cidades C WHERE A.cidade_id = C.cidade_id AND A.pesquisa_id = '".$pesquisa_id."' AND A.pesquisa_id = B.pesquisa_id AND A.cidade_id = '".$cidade_id[$i]."'";
			
			$res1 = mysql_query($strsql) or die(mysql_error());
			
			while($row1 = mysql_fetch_array($res1))
			{				
				array_push($gasto_mes_atual,formata_numero($row1['gasto_mensal_cesta']));
				array_push($variacao_mensal,formata_numero($row1['variacao_mensal'])); 
			}
			
			$strsql = "SELECT A.pesquisa_id,A.cidade_id,A.gasto_mensal_cesta FROM tabela_pesquisas_cidades A,tabela_pesquisas B WHERE A.pesquisa_id = B.pesquisa_id AND B.pesquisa_id = '".$pesquisa_mes_anterior['pesquisa_id']."'";
			
			$res1 = mysql_query($strsql) or die(mysql_error());
			
			while($row1 = mysql_fetch_array($res1))
				array_push($gasto_mes_anterior,formata_numero($row1['gasto_mensal_cesta']));
			
		}
?>
<table cellspacing="0" id="listTable" style="width:368px;">
	<colgroup>
		<col id="cidades" />
		<col id="mes" />
		<col id="variacao" />
	</colgroup>		
	<thead>
		<tr>
			<th scope="col" class="tdboder">Cidade</th>
			<th scope="col" class="tdboder"><?php echo($pesquisa_mes_anterior['mes']); ?></th>
			<th scope="col" class="tdboder"><?php echo($mes_atual); ?></th>
			<th scope="col" class="tdboder">Varia&ccedil;&atilde;o Mensal&nbsp;(&nbsp;%&nbsp;)</th>
		</tr>
	</thead>
	<?php
	for($i=0;$i<$tam;$i++)
	{
		if($l_cor == '') $l_cor = "par"; else $l_cor = "";
?>
		
		<tr class="<?php echo($l_cor);?>">
			<td class="tdboder"><?php echo($cidade_nome[$i]); ?></td>
			<td class="tdboderCod"><?php echo($gasto_mes_anterior[$i]); ?></td>
			<td class="tdboderCod"><?php echo($gasto_mes_atual[$i]); ?></td>
			<td class="tdboder" align="center" <?php if($variacao_mensal[$i]<0){?> style="color:#FF3300;" <?php }?>><?php echo($variacao_mensal[$i]); ?></td>
		</tr>				
	
<?php	   
	}//do for
?>
</table>
<?php }//do if ta tabela....se tem resultados -> lista dados ?>

<br />
<form name="form_cidade">
			<p>
			<label style="color:#003366">Cidade:</label>
			<select style="color:#003366" id="cidade_id">
				
						<option value="0"><--------   Escolha a Cidade  --------></option>
					
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
			<input type="button" onClick="visualizacao_grafica('<?php echo($pesquisa_ano); ?>','<?php echo($pesquisa_id); ?>');" value="Gerar"/>
		</p>
		</form>

</div>
			
			
			<div class="clearer"><span></span></div>

			<div class="rodape">&nbsp </div>

</div>
	
</body>
</html>
<script type="text/javascript" language="javascript">


	function visualizacao_grafica(ano,pesquisa)
	{
		var cidade = document.getElementById("cidade_id").value;
		var url = 'tabelas_boletim.php';
		
		if(cidade != 0)
		{
			url += '?cit=' + cidade + '&ano=' + ano + '&hid=' + pesquisa;
			//'width=800,height=900'
			window.open(url, '_blank','width=1024,height=900,scrollbars=1,resizable=1'); 
			return false;
		}
		else
			alert('Escolhe uma cidade!');
	}
	
</script>