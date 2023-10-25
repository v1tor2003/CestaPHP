<?php

	$cits = preg_split("/\//",$_REQUEST['hcit']);
	$cit_nome = array();
	$qt_cit = count($cits);
	$produtos = preg_split("/\//",$_REQUEST['hprod']);
	$qt_prod = count($produtos);
	$dt = preg_split("/\//",$_REQUEST['hdt']."/");
	$dt_bd = $dt[1]."/".$dt[0];
	$tipo_dado = $_REQUEST['htd'];
	$str_tipo = "";
	$str_aux = "";
	$vetor_info = array();
	$vetor_periodo = array();
		
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
	
	$str_aux = "(";
	for( $i = 0 ; $i<$qt_prod-1 ; $i++)
	{
		$str_aux .= $produtos[$i].",";
		
	}
	$str_aux .= $produtos[$i].")";
	
	for( $i = 0 ; $i<$qt_cit ; $i++)
	{
		$strsql = "SELECT PRP.".$tipo_dado.",EXTRACT(MONTH FROM P.pesquisa_data) AS mes,EXTRACT(YEAR FROM P.pesquisa_data) AS ano FROM tabela_pesquisa_resultados_produtos PRP NATURAL JOIN tabela_pesquisas P WHERE PRP.produto_id IN ".$str_aux." AND PRP.cidade_id = '".$cits[$i]."' AND P.pesquisa_data = '".$dt_bd."/00' ORDER BY PRP.produto_id";
		$res = mysql_query($strsql) or die(mysql_error());
		
		$j = 0;
		while($row = mysql_fetch_array($res))
		{
			$vetor_info[$i][$j] = $row[$tipo_dado];
			$vetor_periodo[$j++] = $row['mes']."/".$row['ano'];
		}
		
	}
	
	$str_aux_cit = "(";
	for( $i = 0 ; $i<$qt_cit-1 ; $i++)
	{
		$str_aux_cit .= $cits[$i].",";
		
	}
	$str_aux_cit .= $cits[$i].")";

	$strsql = "SELECT C.cidade_nome FROM tabela_cidades C WHERE C.cidade_id IN ".$str_aux_cit." ORDER BY C.cidade_id";
	$res = mysql_query($strsql) or die(mysql_error());
	while($row = mysql_fetch_array($res))
	{
		$cit_nome[] = $row['cidade_nome'];
	}
	
	$strsql = "SELECT P.produto_nome_visualizacao FROM tabela_produtos P WHERE P.produto_id IN ".$str_aux." ORDER BY P.produto_id";
	$res = mysql_query($strsql) or die(mysql_error());
	while($row = mysql_fetch_array($res))
	{
		$prod_nome[] = $row['produto_nome_visualizacao'];
	}
$include_head[] = <<<EOS
<link rel="stylesheet" type="text/css" href="estilo/forms_selects.css" media="screen">
EOS;
require("cabecalho.php");
?>
<body>
<div class="caixa_principal">
	
	<div class="conteudo_sem_menu">
	
	<h1 align="center" id="Mcaption" style="padding-left:120px;">Consulta Por Data Refer&ecirc;ncia</h1>
	<h2 id="subtitulos">Cesta B&aacute;sica Nacional<br />
	<?php echo($str_tipo." - ".$cidade_nome." ( ".$_REQUEST['hdt']." )"); ?></h2>
	<br />
	<table cellspacing="0" id="listTable" style="width: <?php echo(11.44*$qt_prod); ?>%; font-size:9.7px;" f>
		<colgroup>
			<col id="codigo" />
			<col id="gastos" />
			<col id="tempo_trabalho" />
		</colgroup>		
		<thead>
			<tr>
				<th scope="col" class="tdboderCenter">Cidade</th>
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
		
			for($i=0;$i<$qt_cit;$i++)
			{
				if($l_cor == '')
					$l_cor = "par";
				else
					$l_cor = "";
					
		?>
			<tr class="<?php echo ($l_cor);?>">
				<td class="tdboderCenter">
				 	<?php echo($cit_nome[$i]); ?>
				</td>
				<?php
					
					if($tp_dado != 2)
					{
						for($j=0;$j<$qt_prod;$j++)
						{ 
				?>
 						<td class="tdboderCenter"><?php echo($vetor_info[$i][$j]); ?></td>
				<?php
						}
					}
					else
					{
						for($j=0;$j<$qt_prod;$j++)
						{ 
				?>
						<td class="tdboderCenter"><?php echo(converte_horas($vetor_info[$i][$j])); ?></td>
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
		    <b>Fonte: </b>Departamento de Ci&ecirc;ncia Econ&ocirc;micas - UESC
		<br />
			<b><i><a href="javascript: void(0)" onClick="valores_moedas();">Valor em moeda da &eacute;poca</a></i></b>
		</p>
		</fieldset>
		
	</div>	
	<div class="clearer"><span></span></div>
</div>
</body>