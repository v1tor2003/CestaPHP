<?php
error_reporting(E_ERROR | E_PARSE);
	$prod = preg_split("/\//",$_REQUEST['hprod']);
	$qt_prod = count($prod);
	$cidade = $_REQUEST['hc'];
	$dt_ini = preg_split("/\//",$_REQUEST['hdti']."/");
	$dti = $dt_ini[1]."/".$dt_ini[0];
	$dt_fim = preg_split("/\//",$_REQUEST['hdtf']."/");
	$dtf =  $dt_fim[1]."/".$dt_fim[0];
	$tipo_dado = $_REQUEST['htd'];
	$str_tipo = "";
	$str_aux = "";
	$qt = 0;
	$tp_dado = $tipo_dado;
	
	switch($tipo_dado)
	{
		case 1: 	$tipo_dado = "produto_preco_total";
					$str_tipo = "Gasto Mensal";
					break;
		case 2:		$tipo_dado = "produto_tempo_trabalho";
					$str_tipo = "Tempo de Trabalho";
					break;
		case 3:		$tipo_dado = "produto_preco_medio";
					$str_tipo = "Preco M&eacute;dio";
	}
	
	for( $i = 0 ; $i<$qt_prod ; $i++)
	{
		$strsql = "SELECT PRP.".$tipo_dado.",EXTRACT(MONTH FROM P.pesquisa_data) AS mes,EXTRACT(YEAR FROM P.pesquisa_data) AS ano FROM tabela_pesquisa_resultados_produtos PRP NATURAL JOIN tabela_pesquisas P WHERE PRP.produto_id = '".$prod[$i]."' AND PRP.cidade_id = '".$cidade."' AND P.pesquisa_data BETWEEN '".$dti."/00' AND '".$dtf."/00' ORDER BY P.pesquisa_data";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		$j = 0;
		while($row = mysqli_fetch_array($res))
		{
			$vetor_info[$i][$j] = $row[$tipo_dado];
			$vetor_periodo[$j++] = $row['mes']."/".$row['ano'];
		}
		
	}
	
	$qt = count($vetor_periodo);
	
	$str_aux = "(";
	for( $i = 0 ; $i<$qt_prod-1; $i++)
	{
		$str_aux .= $prod[$i].",";
		
	}
	$str_aux .= $prod[$i].")";
	
	$strsql = "SELECT P.produto_nome_visualizacao FROM tabela_produtos P WHERE P.produto_id IN ".$str_aux." ORDER BY P.produto_id";
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($res))
	{
		$prod_nome[] = $row['produto_nome_visualizacao'];
	}
	
	$strsql = "SELECT C.cidade_nome FROM tabela_cidades C WHERE C.cidade_id = '".$cidade."'";
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($res);
	
	$cidade_nome = $row['cidade_nome'];
	
	$strsql = "SELECT * FROM tabela_salarios  WHERE salario_em_uso = '1'";
	
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($res);
	$salario_simbolo = $row['salario_simbolo'];

$include_head[] = <<<EOS
<link rel="stylesheet" type="text/css" href="estilo/forms_selects.css" media="screen">
EOS;
require("cabecalho.php");
?>
<body>
<div class="caixa_principal">
	
	<div class="conteudo_sem_menu">
	
	<h1 align="center" id="Mcaption" style="padding-left:100px;">Consulta Por Cidade</h1>
	<h2 id="subtitulos">Cesta B&aacute;sica Nacional <br />
	<?php  
		if($tp_dado != 2)
		{ 
			echo($str_tipo." ( ".$salario_simbolo." ) "." - ".$cidade_nome." ( ".$_REQUEST['hdti']." - ".$_REQUEST['hdtf']." )");
		}
		else
		{
			echo($str_tipo." - ".$cidade_nome." ( ".$_REQUEST['hdti']." - ".$_REQUEST['hdtf']." )");
		}
	?>
	</h2>
	<br />
	<table cellspacing="0" id="listTable" style="width: <?php echo(($qt_prod <= 6)?(22*$qt_prod):(11.2*$qt_prod)); ?>%; font-size:9.7px;">
		<colgroup>
			<col id="codigo" />
			<col id="gastos" />
			<col id="tempo_trabalho" />
		</colgroup>		
		<thead>
			<tr>
				<th scope="col" class="tdboderCenter">Per&iacute;odo</th>
				<?php
					
					for($i=0;$i<$qt_prod;$i++)
					{ 
				?>
 				<th scope="col" class="tdboderCenter"><?php echo($prod_nome[$i]); ?></th>
				<?php
					}
				?>
			</tr>
		</thead>
		<?php
		
			for($i=0;$i<$qt;$i++)
			{
				if($l_cor == '')
					$l_cor = "par";
				else
					$l_cor = "";
					
		?>
			<tr class="<?php echo ($l_cor);?>">
				<td class="tdboderCenter">
				 	<?php echo($vetor_periodo[$i]); ?>
				</td>
				<?php
					
					if($tp_dado != 2)
					{
						for($j=0;$j<$qt_prod;$j++)
						{ 
				?>
 						<td class="tdboderCenter"><?php echo(formata_numero($vetor_info[$j][$i])); ?></td>
				<?php
						}
					}
					else
					{
						for($j=0;$j<$qt_prod;$j++)
						{ 
				?>
						<td class="tdboderCenter"><?php echo(converte_horas($vetor_info[$j][$i])); ?></td>
				<?php
						}
					}
				?>
				
			</tr>
			<?php	   
			
			}//do for
			
			?>		
	
		</table>
		<fieldset style="width:330px;">
		<legend>Informa&ccedil;&otilde;es Adicionais</legend>
		<p>
			<b>Fonte: </b>Departamento de Ciência Econômicas - UESC
		<br />
			<b><i><a href="javascript: void(0);" onClick="valores_moedas();">Valor em moeda da &eacute;poca</a></i></b>
		</p>
		</fieldset>
		
	</div>	
	<div class="clearer"><span></span></div>
</div>
</body>