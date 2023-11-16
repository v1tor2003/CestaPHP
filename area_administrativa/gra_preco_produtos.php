<?php
	require_once('../libs/Charts/FusionCharts.php');


	$pesquisa_ano = $_REQUEST['ano']; 
	$cidade = $_REQUEST['cit'];
	$pesquisa_id = $_REQUEST['hid'];

   //Fetch all factory records
   $strsql = "SELECT * FROM tabela_pesquisa_resultados_produtos A, tabela_pesquisas B,tabela_produtos C,tabela_cidades D WHERE  A.pesquisa_id = B.pesquisa_id AND A.pesquisa_id = '".$pesquisa_id."' AND A.cidade_id = '".$cidade."' AND C.produto_cesta = '1' AND A.produto_id = C.produto_id AND A.cidade_id = D.cidade_id ORDER BY A.produto_id";
   
   $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
   $qtde = mysqli_num_rows($res);
   
   while($row = mysqli_fetch_array($res))
	{
		 $produto []  = $row['produto_nome_visualizacao'];
		 $preco [] = $row['produto_preco_total'];
		 $cidade_nome = $row['cidade_nome'];
	}
   
   //$strXML will be used to store the entire XML document generated
   //Generate the graph element
   $strXML = "<graph caption='Pre&ccedil;o dos Produtos' subCaption='".$cidade_nome."' decimalPrecision='2' numberPrefix='$' showNames='1' pieYScale='80' pieRadius='130' animation='1' pieFillAlpha='70' pieSliceDepth='15' showValues='1' formatNumberScale='0'>";
		
	for( $i=0 ; $i<$qtde ; $i++) 
	{
		 $strXML .= "<set name='".$produto[$i]."' value='".$preco[$i]."' />";
	}
		
   //Finally, close <graph> element
   $strXML .= "</graph>";

   //Create the chart - Pie 3D Chart with data from $strXML
   echo renderChartHTML("../Charts/FCF_Pie3D.swf","",$strXML,"graph1", 600, 350, false);
?>