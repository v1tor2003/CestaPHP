<?php

	require_once('Charts/FusionCharts.php');
		 
	$pesquisa_ano = $_REQUEST['ano']; 
	$cidade = $_REQUEST['cit'];
	
	$strsql = "SELECT MAX(gasto_mensal_cesta) AS max,MIN(gasto_mensal_cesta) AS min,mes_nome,gasto_mensal_cesta,mes_id FROM tabela_pesquisas_cidades A,tabela_pesquisas B,tabela_mes C,tabela_salarios D WHERE A.cidade_id = '".$cidade."' AND EXTRACT(YEAR FROM B.pesquisa_data) = '".$pesquisa_ano."' AND EXTRACT(MONTH FROM B.pesquisa_data) = C.mes_id AND A.pesquisa_id = B.pesquisa_id AND B.salario_id = D.salario_id GROUP BY C.mes_id ORDER BY C.mes_id";
	
	$res = mysql_query($strsql) or die(mysql_error());
	
	$qtde = mysql_num_rows($res);

	while($row = mysql_fetch_array($res))
	{
	    
		$mes [] = $row['mes_nome'];
		$gasto_mensal [] = $row['gasto_mensal_cesta'];
		$max = $row['max'];
		$min = $row['min'];
		
	}
	
	$max += 40;
	$min -= 40;
	 
	 $strXML = "<graph caption='Custo da Cesta Básica' subcaption='Ano ".$pesquisa_ano."' xAxisName='Mês' yAxisMinValue='".$min."' yAxisMaxValue='".$max."' yAxisName='Custo' decimalPrecision='2' formatNumberScale='0' numberPrefix='$' showNames='1' showValues='0' showAlternateHGridColor='1' AlternateHGridColor='0099CC' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5'>";
	 
	 	
/**********************************************************************************************************
*
*	Pequisa para a tabela com o G.M., Variações e CRL de todos os meses do ano da pesquisa solicitada.
*
**********************************************************************************************************/

	for( $i=0 ; $i<$qtde ; $i++ )
	{
	    
		$strXML.= "<set name='".$mes[$i]."' value='".$gasto_mensal[$i]."' />";

	}
	
	//Finally, close <chart> element
	$strXML.= "</graph>";
	
	echo renderChartHTML("../Charts/FCF_Line.swf", "",$strXML, "graph1", 600, 300, false);
	
?>