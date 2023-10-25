<?php

	require_once('Charts/FusionCharts.php');
	
	$cities = preg_split("/\//",$_REQUEST['hcit']);
	$qt_cit = count($cities);
	$produto = $_REQUEST['hp'];
	$dt_ini = preg_split("/\//",$_REQUEST['hdti']."/");
	$dti = $dt_ini[1]."/".$dt_ini[0];
	$dt_fim = preg_split("/\//",$_REQUEST['hdtf']."/");
	$dtf =  $dt_fim[1]."/".$dt_fim[0];
	
	$strsql = "SELECT * FROM tabela_cidades ORDER BY cidade_id";
	$res = mysql_query($strsql) or die(mysql_error());
	while($row = mysql_fetch_array($res))
	{
		$cit_cesta[] = $row['cidade_nome'];
		$cit_cesta_id[] = $row['cidade_id'];
	}
	
	$qt_cit_cesta = count($cit_cesta_id);
	
	for( $i = 0 ; $i<$qt_cit_cesta ; $i++)
	{
		$strsql = "SELECT PRP.produto_preco_total,EXTRACT(MONTH FROM P.pesquisa_data) AS mes,EXTRACT(YEAR FROM P.pesquisa_data) AS ano FROM tabela_pesquisa_resultados_produtos PRP NATURAL JOIN tabela_pesquisas P WHERE PRP.cidade_id = '".$cit_cesta_id[$i]."' AND PRP.produto_id = '".$produto."' AND P.pesquisa_data BETWEEN '".$dti."/00' AND '".$dtf."/00' ORDER BY P.pesquisa_data";

		$res = mysql_query($strsql) or die(mysql_error());
		
		$j = 0;
		while($row = mysql_fetch_array($res))
		{
			$vetor_info[$i][$j] = $row['produto_preco_total'];
			$vetor_periodo[$j++] = $row['mes']."/".$row['ano'];
			
			if($max > $vetor_info[$i][$j])
				$max = $vetor_info[$i][$j];
		}
		
	}
	
	$qt = count($vetor_periodo);
	
	/*for($i=0;$i<$qt_cit_cesta;$i++)
	{
		for($j=0;$j<$qt;$j++)
		echo(" - ".$vetor_info[$i][$j]." ");
		
		echo("<br>");
	}*/
	
		
	/*$str_aux = "(";
	for( $i = 0 ; $i<$qt_cit-1 ; $i++)
	{
		$str_aux .= $cities[$i].",";
		
	}
	$str_aux .= $cities[$i].")";
	

	$strsql = "SELECT C.cidade_nome FROM tabela_cidades C WHERE C.cidade_id IN ".$str_aux." ORDER BY C.cidade_id";
	$res = mysql_query($strsql) or die(mysql_error());
	while($row = mysql_fetch_array($res))
	{
		$cit_nome[] = $row['cidade_nome'];
	}
	*/
	$strsql = "SELECT P.produto_nome_visualizacao FROM tabela_produtos P WHERE P.produto_id = '".$produto."'";
	$res = mysql_query($strsql) or die(mysql_error());
	$row = mysql_fetch_array($res);
	
	$produto_nome = $row['produto_nome_visualizacao'];

        $include_head[] = <<<EOS
<link rel="stylesheet" type="text/css" href="estilo/forms_selects.css" media="screen">\n
<script language="javascript" type="text/javascript" src="../javascript/FusionCharts.js"></script>
EOS;
require("cabecalho.php");
?>
<body>
<div class="caixa_principal">
	
	<div class="conteudo_sem_menu">
	
	<h1 align="center" id="Mcaption" style="padding-left:100px;">Consulta Por Cidade</h1>
	<h1 id="Mcaption">Cesta B&aacute;sica Nacional</h1>
	<fieldset style="width:810px;">
	<legend>Gr&aacute;ficos - Consulta (Gasto Mensal)</legend>
	<table style="border:none;" align="center">
	<tr>
	<td align="left">
		<form name="productSelector" id="productSelector" method="post" >
		<table style="border:none; margin-right:40px;" cellpadding="0" cellspacing="0">
		<tr>
			<p>
			<h5>Selecione os produtos:</h5>
			<?php 
				
				$strsql = "SELECT C.cidade_nome,C.cidade_id FROM tabela_cidades C ORDER BY C.cidade_id";
				$res = mysql_query($strsql) or die(mysql_error());
				
				$i=0;
				$j=0;
				while($row = mysql_fetch_array($res))
				{
				
					if($i == 4)
					{
						echo("</tr><tr>");
						$i=0;
					}
					$i++;
			?>
					<td><input type="checkbox" id="cidade<?php echo($j++)?>"  <?php if(busca_chave($row['cidade_id'],$cities)!= -1){?> checked <?php } ?>  onClick="JavaScript:updateChart('chart1Id');" /><?php echo($row['cidade_nome']); ?> </td>
			<?php
					
				}
			
			?>
				</tr>
			</table>
			</p>
			</td>
			<td align="left">
			<p> 
			<h5> Selecione o tipo de gr&aacute;fico que voc&ecirc; deseja visualizar:</h5>
			<select id="tipo_grafico" onChange="JavaScript:updateChart('chart1Id');">
				<option value="FCF_MSLine.swf">Linha Simples</option>
				<option value="FCF_MSColumn2D.swf">Coluna 2D</option>
				<option value="FCF_MSColumn3D.swf">Coluna 3D</option>
				<option value="FCF_MSBar2D.swf">Coluna 2D Horizontal</option>
				<option value="FCF_StackedColumn2D.swf">Coluna 2D Empilhada</option>
				<option value="FCF_StackedColumn3D.swf">Coluna 3D Empilhada</option>
				<option value="FCF_MSArea2D.swf">&Aacute;rea Simples 2D</option>
				<option value="FCF_StackedArea2D.swf">&Aacute;rea Empilhada 3D</option>
			</select>
			<h5>Mostrar N&uacute;meros:</h5>
			<select id="mostraNumeros"  onChange="JavaScript:updateChart('chart1Id');">
				<option value="1">Sim</option>
				<option value="2">N&aacute;o</option>
			</select>
			</p>
		</form>
		</td>
		</tr>
		</table>
		<div id="chart1div">
		</div>
		<p>
			<b>Fonte: </b>Departamento de Ci&ecirc;ncias Econ&ocirc;micas - UESC
			<br />
			<b>
				<i>
					<a href="javascript: void(0);" onClick="valores_moedas();">
						Valor em moeda da &eacute;poca
					</a>
				</i>
			</b>
		</p>
		
		</fieldset>
		
	</div>	
	<div class="clearer"><span></span></div>
</div>
</body>
<script language="JavaScript">
	
		var data = new Array();
		
		<?php
			for($i=0;$i<$qt_cit_cesta;$i++)
			{ 
		?>
				data[<?php echo($i); ?>] = new Array();
				data[<?php echo($i); ?>].push(<?php echo("'".$cit_cesta[$i]."'"); ?>);
			<?php
			
				for($j=0;$j<$qt;$j++)
				{	 
			?>
				data[<?php echo($i); ?>].push(<?php echo("'".($vetor_info[$i][$j])."'"); ?>);
			<?php
				}	
			}
		?>
		
		var colors=new Array("AFD8F8", "FFFF00", "8BBA00", "FF8E46","BBFCFF","EADEDA","40E0D0","EED2EE","D8BFD8","F4A460","00FA9A","FFD700","97FFFF","43CD80");
		
		function updateChart(domId){			
		//updateChartXML(domId,generateXML());
		var tipo_grafico = document.getElementById("tipo_grafico").value;
		var chart1 = new FusionCharts("../Charts/"+tipo_grafico+"?ChartNoDataText=Nenhum produto foi selecionado","chart1Id","780","400");		   
		var strXML=generateXML();
		chart1.setDataXML(strXML);
		chart1.render("chart1div");
				
		}

		function generateXML(){			
			//Variable to store XML
			var strXML="";
			
			var mostraNumeros = document.getElementById("mostraNumeros").value;

			strXML = "<graph decimalPrecision='2' decimalSeparator=',' numdivlines='5' rotateNames='1' divLineAlpha='80' xaxisname='Periodo' caption='Acompanhamento - <?php echo($produto_nome);?>' subcaption='Periodo <?php echo(" (".$_REQUEST['hdti']." - ".$_REQUEST['hdtf'].")"); ?>' showAlternateHGridColor='1' AlternateHGridColor='FFF5EE' divLineColor='FF7256' showValues='"+mostraNumeros+"'>";

			//Store <categories> and child <category> elements
			strXML = strXML + "<categories>";
			
			<?php
				for($i=0;$i<$qt;$i++)
				{
			?>
				strXML = strXML + "<category name='<?php echo($vetor_periodo[$i]); ?>' />";
			<?php 
				}
			?>
			strXML = strXML + "</categories>";

			<?php
				for($i=0;$i<$qt_cit_cesta;$i++)
				{
			?>
				var cit = document.getElementById("cidade<?php echo($i)?>");
				
				if(cit.checked)
				strXML = strXML + getProductXML(<?php echo($i)?>);
			<?php 
				}
			?>

			strXML = strXML + "</graph>";

			return strXML;
		}
		
		function getProductXML(productIndex){		
			var productXML;
			//Create <dataset> element taking data from 'data' array and color vaules from 'colors' array defined above
			productXML = "<dataset seriesName='" + data[productIndex][0] + "' color='"+ colors[productIndex]  +"' >";			
			//Create set elements
			for (var i=1; i<=<?php echo($qt); ?>; i++){
				productXML = productXML + "<set value='" + data[productIndex][i] + "' />";
			}
			//Close <dataset> element
			productXML = productXML + "</dataset>";
			//Return dataset data
			return productXML;			
		}
		
		var tipo_grafico = document.getElementById("tipo_grafico").value;
		var chart1 = new FusionCharts("../Charts/"+tipo_grafico+"?ChartNoDataText=Nenhuma cidade foi selecionado","chart1Id","800","450");		   
		var strXML=generateXML();
		chart1.setDataXML(strXML);
		chart1.render("chart1div");
		
</SCRIPT>