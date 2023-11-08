<?php
ini_set('display_errors', '0'); 
/*
 * 	P�gina escrita em ISO-8859-1 ou Latin1 em compatibilidade com o padr�o do PHP 5.3
 */
    require_once 'PHPRtfLite.php';
    // register PHPRtfLite class loader
	PHPRtfLite::registerAutoloader();
	$pesquisa_id = $_REQUEST['hid'];
/********************************************************************************************************************************
*
*	Dados da primeira tabela do boletim que mostra o gasto e varia��o mensal de cada cidade
*
********************************************************************************************************************************/
	
	$strsql = "SELECT B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano,B.mes_id FROM tabela_pesquisas A, tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_id = '".$pesquisa_id."'";

	
	$pesquisas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
								
	if ($pesquisas && mysqli_num_rows($pesquisas)>0)
	{
		$row = mysqli_fetch_array($pesquisas);
		$mes_atual = utf8_encode($row['mes_nome']);
		$pesquisa_mes = $row['mes_id'];
		$pesquisa_ano = $row['pesquisa_ano'];
		$data_mes_anterior = formata_mes_anterior($pesquisa_mes,$pesquisa_ano);

		

		$pesquisa_mes_anterior = retorna_pesquisa($data_mes_anterior[0],$data_mes_anterior[1]);
	}
	
	
	$strsql = "SELECT A.cidade_id,B.cidade_nome FROM tabela_pesquisas_cidades A,tabela_cidades B WHERE A.pesquisa_id = '".$pesquisa_id."' AND A.cidade_id = B.cidade_id ORDER BY B.cidade_nome";
		
	$gasto_mes_atual = array();
	$variacao_mensal = array();
	$gasto_mes_anterior = array();
	
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	if($res && mysqli_num_rows($res)>0)
	{
	
		while($row = mysqli_fetch_array($res))
		{
			$cidade_id[] = $row['cidade_id'];
			$cidade_nome[] = $row['cidade_nome'];
		}
		$tam = count($cidade_id);
		
		for($i=0;$i<$tam;$i++)
		{
			$strsql = "SELECT A.pesquisa_id,A.gasto_mensal_cesta,A.variacao_mensal,C.cidade_id FROM tabela_pesquisas_cidades A,tabela_pesquisas B,tabela_cidades C WHERE A.cidade_id = C.cidade_id AND A.pesquisa_id = '".$pesquisa_id."' AND A.pesquisa_id = B.pesquisa_id AND A.cidade_id = '".$cidade_id[$i]."'";
			
			$res1 = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
			
			while($row1 = mysqli_fetch_array($res1))
			{				
				array_push($gasto_mes_atual,str_replace(".", ",", isNull($row1['gasto_mensal_cesta'])));
				array_push($variacao_mensal,str_replace(".", ",", isNull($row1['variacao_mensal']))); 
			}
			
			$strsql = "SELECT A.pesquisa_id,A.cidade_id,A.gasto_mensal_cesta FROM tabela_pesquisas_cidades A,tabela_pesquisas B WHERE A.pesquisa_id = B.pesquisa_id AND B.pesquisa_id = '".$pesquisa_mes_anterior['pesquisa_id']."'";
			
			$res1 = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
			
			while($row1 = mysqli_fetch_array($res1))
				array_push($gasto_mes_anterior,isNull($row1['gasto_mensal_cesta']));
			
		}
	}
	
	
/**********************************************************************************************************
*
*	Pequisa para a tabela com o G.M., Varia��es e CRL de todos os meses do ano da pesquisa solicitada.
*
**********************************************************************************************************/

	for($a=0;$a<$tam;$a++)
	{
	
		$strsql = "SELECT * FROM tabela_pesquisas_cidades A,tabela_pesquisas B,tabela_mes C,tabela_salarios D WHERE A.cidade_id = '".$cidade_id[$a]."' AND EXTRACT(YEAR FROM B.pesquisa_data) = '".$pesquisa_ano."' AND EXTRACT(MONTH FROM B.pesquisa_data) = C.mes_id AND A.pesquisa_id = B.pesquisa_id AND B.salario_id = D.salario_id ORDER BY C.mes_id DESC";
		
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		while($row = mysqli_fetch_array($res))
		{
			$mes[] = $row['mes_nome'];
			$gasto_mensal[$a][] = str_replace(".", ",", isNull($row['gasto_mensal_cesta']));
			$acmp_var_mensal[$a][] = str_replace(".", ",", isNull($row['variacao_mensal']));
			
		}
	
	}
	
	$qt_mes = count($mes);
	

/**********************************************************************************************************
*
*	Dados de cada produto em cada cidade
*
**********************************************************************************************************/
	
	for($j=0;$j<$tam;$j++)
	{
		$strsql = "SELECT * FROM tabela_pesquisa_resultados_produtos A, tabela_pesquisas B,tabela_produtos C WHERE  A.pesquisa_id = B.pesquisa_id AND A.pesquisa_id = '".$pesquisa_id."' AND A.cidade_id = '".$cidade_id[$j]."' AND A.produto_id = C.produto_id AND C.produto_cesta = '1' ORDER BY A.produto_id";

		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		$i=0;
		while($row = mysqli_fetch_array($res))
		{
			$produto_id[$j][] = $row['produto_id'];
			$produto[$j][] = $row['produto_nome_visualizacao'];
			$preco_medio[$j][] = str_replace(".", ",", $row['produto_preco_medio']);
			$produto_gasto_mensal[$j][] = str_replace(".", ",", $row['produto_preco_total']);
			$produto_variacao_mensal[$j][] = str_replace(".", ",", isNull($row['produto_variacao_mensal']));
			$produto_variacao_semestral[$j][] = str_replace(".", ",", isNull($row['produto_variacao_semestral']));
			$produto_variacao_anual[$j][] = str_replace(".", ",", isNull($row['produto_variacao_anual']));
			$produto_tempo_trabalho[$j][] = converte_horas($row['produto_tempo_trabalho']);
			$i++;
		}

		
		$tam_produtos = $i;
		$prod_buscar = "(";
		
		for($i=0;$i<$tam_produtos;$i++)
		{
			if($i != $tam_produtos-1)
				$prod_buscar =  $prod_buscar.$produto_id[$j][$i].","; 
			else
				$prod_buscar = $prod_buscar.$produto_id[$j][$i].")";
		}
		
		$strsql = "SELECT * FROM tabela_racao_minima A,tabela_delimitador_racao B,tabela_unidade_medidas C WHERE A.delimitador_id = B.delimitador_id AND B.delimitador_em_uso = '1' AND A.produto_id IN ".$prod_buscar." AND A.racao_minima_medida = C.medida_id GROUP BY A.produto_id";
	
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		while($row = mysqli_fetch_array($res))
		{
			$quantidade[] = str_replace(".", ",", $row['racao_minima_quantidade']);
			$prod_medida[] = $row['medida_simbolo'];
		}
		
		$strsql = "SELECT * FROM tabela_pesquisa_resultados_produtos A, tabela_pesquisas B,tabela_produtos C WHERE A.pesquisa_id = B.pesquisa_id AND A.pesquisa_id = '".$pesquisa_mes_anterior['pesquisa_id']."' AND A.cidade_id = '".$cidade_id[$j]."' AND A.produto_id = C.produto_id AND C.produto_cesta = '1' ORDER BY A.produto_id";
		
		//die($strsql);
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		while($row = mysqli_fetch_array($res))
		{
			$gasto_mensal_anterior[$j][] = str_replace(".", ",", isNull($row['produto_preco_total']));
			$preco_medio_anterior[$j][] = str_replace(".", ",", isNull($row['produto_preco_medio']));
			$prod_mensal_anterior[$j][] = str_replace(".", ",", isNull($row['produto_variacao_mensal']));
		}
		
		$strsql = "SELECT * FROM tabela_pesquisas A NATURAL JOIN tabela_pesquisas_cidades B WHERE A.pesquisa_id = '".$pesquisa_id."' AND B.cidade_id = '".$cidade_id[$j]."'";
		//die($strsql);
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		while($row = mysqli_fetch_array($res))
		{
			$preco_mensal_total[$j] = str_replace(".", ",", $row['gasto_mensal_cesta']);
			$tot_var_mensal[$j] = str_replace(".", ",", isNull($row['variacao_mensal']));
			$tot_var_semestral[$j] = str_replace(".", ",", isNull($row['variacao_semestral']));
			$tot_var_anual[$j] = str_replace(".", ",", isNull($row['variacao_anual']));
			$tot_tempo_trabalho[$j] = $row['tempo_trabalho'];
		}

		$sql = "SELECT pesquisa_id FROM tabela_pesquisas WHERE YEAR(pesquisa_data) = $pesquisa_ano AND MONTH(pesquisa_data) = 1 ORDER BY pesquisa_data";
		$res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$row = mysqli_fetch_array($res);
		$pesquisaIdJaneiro = $row['pesquisa_id'];

		$sql = "SELECT a.pesquisa_id,gasto_mensal_cesta,pesquisa_data from tabela_pesquisas_cidades a, tabela_pesquisas b where a.pesquisa_id=b.pesquisa_id AND ((a.pesquisa_id=".$pesquisa_id." OR a.pesquisa_id= ".$pesquisaIdJaneiro.") AND cidade_id=".$cidade_id[$j].")";
		$res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

		
		while($row = mysqli_fetch_array($res)){
			$preco_total_cesta[$j][]= $row['gasto_mensal_cesta'];
		}
	
		$strsql = "SELECT * FROM tabela_pesquisa_resultados_produtos A, tabela_pesquisas B,tabela_produtos C WHERE  A.pesquisa_id = B.pesquisa_id AND A.pesquisa_id = '".$pesquisa_id."' AND A.cidade_id = '".$cidade_id[$j]."' AND A.produto_id = C.produto_id AND C.produto_cesta = '1' ORDER BY A.produto_id";

		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		if ($pesquisa_mes == 12) {
			while($row = mysqli_fetch_array($res))
			{
				$precoMedioDezembro[$j][] = floatval($row['produto_preco_medio']);
			}
		
		
			$strsql = "SELECT * FROM tabela_pesquisa_resultados_produtos A, tabela_pesquisas B,tabela_produtos C WHERE  A.pesquisa_id = B.pesquisa_id AND A.pesquisa_id = '".$pesquisaIdJaneiro."' AND A.cidade_id = '".$cidade_id[$j]."' AND A.produto_id = C.produto_id AND C.produto_cesta = '1' ORDER BY A.produto_id";

			$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
			
			while($row = mysqli_fetch_array($res))
			{
				$preco_medio_janeiro[$j][] = floatval($row['produto_preco_medio']);
			}

			for($i=0;$i<$tam_produtos;$i++){
				
				$produto_variacao_do_ano[$j][$i] = str_replace(".", ",", round((($precoMedioDezembro[$j][$i]/$preco_medio_janeiro[$j][$i]) -1)*100,2));
			}
			$tot_variacaoDoAno[$j] = str_replace(".", ",", round((($preco_total_cesta[$j][1]/$preco_total_cesta[$j][0]) -1)*100,2));
		}	
		

		
		
	}

/************************************************************************************************************************/

	$strsql = "SELECT * FROM tabela_pesquisas A,tabela_salarios B WHERE A.salario_id = B.salario_id AND A.pesquisa_id = '".$pesquisa_id."'";
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($res);
	$salario_simbolo = $row['salario_simbolo'];
	$salario_liquido = $row['salario_valor_liquido'];
	$salario_bruto = $row['salario_valor_bruto'];
	
	$strsql = "SELECT * FROM tabela_pesquisas A,tabela_delimitador_racao B WHERE A.pesquisa_id = '".$pesquisa_id."' AND A.delimitador_id = B.delimitador_id";
	
	$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($res);
	$delimitador = $row['delimitador_descricao'];


	$legenda_tabelas = utf8_encode("Cesta B�sica de acordo com o Decreto-Lei n� 399 de 30 de abril de 1938, que instituiu as Comiss�es do Sal�rio M�nimo.<br>Fonte: Projeto de extens�o Acompanhamento do Custo da Cesta B�sica - ACCB/UESC.");
	//Varia��o Mensal Cesta B�sica Pre�o M�dio
	
	//////////////
	//Font formats
	$font1 = new PHPRtfLite_Font(12, 'Bookman Old Style', '#111111');
	$font1_bold = new PHPRtfLite_Font(12, 'Bookman Old Style', '#111111');
	$red_font = new PHPRtfLite_Font(12, 'Bookman Old Style', '#111111'); // Tabelas na cor preta
	$font_notas = new PHPRtfLite_Font(10, 'Bookman Old Style', '#111111');
        $font_titulo_tb = new PHPRtfLite_Font(12, 'Bookman Old Style', '#111111');
	
	$formato_borda = new PHPRtfLite_Border_Format(1, '#111111');
	
	$paragrafo_legenda = new PHPRtfLite_ParFormat('justify');
	$paragrafo_legenda->setSpaceBefore(0);
	
	//////////////
	//Paragraph formats
	$parFC = new PHPRtfLite_ParFormat('center');
	$parFC->setSpaceBefore(0);
	
	$parFL = new PHPRtfLite_ParFormat('left');
	
	$parFR = new PHPRtfLite_ParFormat('right');
	//////////////
	//Rtf document
	$rtf = new PHPRtfLite();
	$null = null;
	//section
	
	//head
	$header = $rtf->addHeader('first');
	$header->addImage('images/titulo_boletim.png', new PHPRtfLite_ParFormat());
	
	//$rtf->setMargins(3, 3, 2, 2);
	
	
	$sect = $rtf->addSection();
	
	$sect->writeText(utf8_encode('Cesta B�sica<br>'), new PHPRtfLite_Font(22, 'Bookman Old Style'), new PHPRtfLite_ParFormat('center'));
	
	$sect->writeText(utf8_encode('Boletim '.$mes_atual.' - '.$pesquisa_ano.'<br><br>'), new PHPRtfLite_Font(22, 'Bookman Old Style'), new PHPRtfLite_ParFormat('center'));
	
	$count = $tam;
	$countCols = 4;
	$countRows = $count + 1;
	
	$colWidth = ($sect->getLayoutWidth() - 5) / $countCols; 
	
	
	$legend = utf8_encode('Tabela 1 - Custo da Cesta B�sica (em '.$salario_simbolo.') nas cidades de ');
	
	for($i=0;$i<$tam;$i++)
		if($i!=$tam-2)
			$legend = $legend.$cidade_nome[$i];
		else
			$legend = $legend.$cidade_nome[$i].' e ';
			
	$legend = $legend.', '.$pesquisa_ano;
			
	$sect->writeText($legend, $font_titulo_tb, new PHPRtfLite_ParFormat('justify'));
	$count = $qt_mes/2;
	$countCols = 3;
	$countRows = $count + 1;
	
	$colWidth = ($sect->getLayoutWidth() - 5) / $countCols;
	$colWidth -=0.132;
	$table = $sect->addTable();
	$table->addRows(1,0);
	$table->addColumn($colWidth-0.82);
	$table->writeToCell(1,1, utf8_encode('M�s'), $font1_bold,new PHPRtfLite_ParFormat('center'));
	
        
	for($i=1;$i<=$tam;$i++)
	{
		$table->addColumn(($colWidth*2)-0.1);
		$table->writeToCell(1,$i+1, $cidade_nome[$i-1], $font1_bold, new PHPRtfLite_ParFormat('center'));
	}
	
        $table-> setBordersForCellRange($formato_borda, 1, 1, 1,$countCols,false,true,false,false);

	$table = $sect->addTable();
	$format_var = new PHPRtfLite_ParFormat('right');
	$format_var->setIndentRight(1);
        
	for($j=1;$j<=$count+1;$j++)
	{
		$table->addRows(1);
		$table->addColumn($colWidth-0.82);
		
		if($j>1)
		$table->writeToCell($j,1, $mes[$j-2], $font1,new PHPRtfLite_ParFormat('left'));
		
		if($j==1)
		{
			for($i=1;$i<=$tam;$i++)
			{ 
				$table->addColumn($colWidth-0.05);
				$table->writeToCell($j,2*$i, 'Gasto Mensal '.$salario_simbolo, $font1_bold,$parFC); 
				$table->addColumn($colWidth-0.05);
				$table->writeToCell($j,2*$i+1, utf8_encode('Varia��o Mensal %'), $font1_bold,$parFC);
			}
		}
		else
		{
			for($i=1;$i<=$tam;$i++)
			{ 
				$table->addColumn($colWidth-0.05);
				$table->writeToCell($j,2*$i,$gasto_mensal[$i-1][$j-2],$font1,$format_var); 
				$table->addColumn($colWidth-0.05);
				$table->writeToCell($j,2*$i+1,$acmp_var_mensal[$i-1][$j-2],$font1, $format_var);
			}
			
		}
	}
	
        
	$table1 = $sect->addTable();
	$table1->addRows(1,0);
	$table1->addColumn((5*$colWidth)-1.2);
	$table1->writeToCell(1,1,$legenda_tabelas, $font_notas,new PHPRtfLite_ParFormat('justify'));
	$table1-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1, 1,1,false,true,false,false);
	
	//$sect->writeText($legenda_tabelas, $font_notas,$paragrafo_legenda);
	
	//$formato_borda = new PHPRtfLite_Border_Format(1, '#111111');
	//$table->mergeCells(1, 1, 3, 1); 
	//$table->setBorders($left=false,$top = true,$right = false,$bottom = true);
	//$table-> setBordersForCellRange($formato_borda, 1,1,1,5,true,false,false,false);
	//$table-> setBordersForCellRange($formato_borda, 1, 1, 1,5,false,true,false,false);
	$table-> setBordersForCellRange($formato_borda, 1, 2, 1,5,false,true,false,false);
        $table-> setBordersForCellRange($formato_borda, 1, 1, 1,5,false,false,false,true);
	/*$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 3, 1,3,true,true,true,true);
	$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 4, 1,4,true,true,true,true);
	$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 5, 1,5,true,true,true,true);*/
	$table-> setBordersForCellRange($formato_borda, $countRows, 1, $countRows,5,false,false,false,true);
	//$table->setBackGroundOfCells('#FFFFFD',2, 1, $countRows, 2*$tam+1);

	
	
	//$table->setBackGroundOfCells('#CCCCCC', 1, 1, 1, 2*$tam+1);

	$colWidth -= 0.1218;
	//fim da segunda tabela
	
	//inicio da terceira tabela
	
	for($a=0;$a<$tam;$a++)
	{
	
		$sect->writeText('<br>', new PHPRtfLite_Font(32, 'Bookman Old Style'), new PHPRtfLite_ParFormat('center'));
		
		$legend = utf8_encode('Tabela '.(2+($a*2)).' - Pre�o M�dio, Gasto Mensal e tempo de trabalho necess�rio, Cesta B�sica, ').$cidade_nome[$a].', Bahia.';	
		$sect->writeText($legend, $font_titulo_tb, new PHPRtfLite_ParFormat('justify'));
		
		$table = $sect->addTable();
		$table->addRows(1,1.5);
		$table->addColumn($colWidth+0.1);
		$table->writeToCell(1,1, 'Produtos',$font1_bold,new PHPRtfLite_ParFormat('center'));
		$table->addColumn(($colWidth-0.3)*2);
		$table->writeToCell(1,2, utf8_encode('Pre�o M�dio ('.$salario_simbolo.')'),$font1_bold,new PHPRtfLite_ParFormat('center'));
		$table->addColumn($colWidth-1.7);
		$table->writeToCell(1,3, 'Qtde.', $font1_bold,new PHPRtfLite_ParFormat('center'));
		$table->addColumn($colWidth-0.7);
		$table->writeToCell(1,4, 'Gasto Mensal '.$mes_atual.' ('.$salario_simbolo.')', $font1_bold,new PHPRtfLite_ParFormat('center'));
		$table->addColumn($colWidth-0.24);
		$table->writeToCell(1,5, utf8_encode('Tempo de Trabalho Necess�rio'), $font1_bold,new PHPRtfLite_ParFormat('center'));
		
		$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1, 1,5,false,true,false,false);
		//$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 2, 1,6,false,true,false,false);
		//$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 2, 2, 1,5,false,true,false,true);
		//$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 2, 1,5);
		//$table->setBackGroundOfCells('#CCCCCC', 1, 1, 1, 5);
		
		$table = $sect->addTable();
		$table->addRows(1,-0.5);
		$table->addColumn($colWidth+0.1);
		$table->writeToCell(1,1, '', $font1,new PHPRtfLite_ParFormat('center'));
		$table->addColumn($colWidth-0.3);
		$table->writeToCell(1,2, $pesquisa_mes_anterior['mes'], $font1,new PHPRtfLite_ParFormat('center'));
		$table->addColumn($colWidth-0.3);
		$table->writeToCell(1,3, $mes_atual, $font1,new PHPRtfLite_ParFormat('center'));
		$table->addColumn($colWidth-1.9);
		$table->writeToCell(1,4, '', $font1,new PHPRtfLite_ParFormat('center'));
		$table->addColumn($colWidth-0.2);
		$table->writeToCell(1,5, '', $font1,new PHPRtfLite_ParFormat('center'));
		$table->addColumn($colWidth-0.76);
		$table->writeToCell(1,6, '', $font1,new PHPRtfLite_ParFormat('center'));
		
		$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 2, 1,3,false,true,false,false);
                $table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1, 1,6,false,false,false,true);
		//$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 2, 1,6);
		
		$table->addRows($tam_produtos,-0.5);	
		
		for($j=1;$j<=$tam_produtos;$j++)
		{
			
			$table->addColumn($colWidth+0.1);
			$table->writeToCell($j+1,1," ".$produto[$a][$j-1]." (".$prod_medida[$j-1].")",$font1,$format_var);
			$table->addColumn($colWidth-0.3);
			$table->writeToCell($j+1,2,$preco_medio_anterior[$a][$j-1], $font1,$format_var);
			$table->addColumn($colWidth-0.3);
			$table->writeToCell($j+1,3,$preco_medio[$a][$j-1], $font1,$format_var);
			$table->addColumn($colWidth-1.52);
			$table->writeToCell($j+1,4, $quantidade[$j-1], $font1,new PHPRtfLite_ParFormat('right'));
			$table->addColumn($colWidth-0.2);
			$table->writeToCell($j+1,5, $produto_gasto_mensal[$a][$j-1], $font1,$format_var);
			$table->addColumn($colWidth-0.2);
			$table->writeToCell($j+1,6,$produto_tempo_trabalho[$a][$j-1], $font1,new PHPRtfLite_ParFormat('right'));	
			
			
		}
		///$table->setBackGroundOfCells('#CCCCCC', 1, 1, 1, 6);
		//$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1,$tam_produtos+1,6,false,true,false,true);
		//$table->setBackGroundOfCells('#FFFFFD',2, 1, $tam_produtos+1, 6);
		$table = $sect->addTable();
		$table->addRows(1,0);
		$table->addColumn(($colWidth+0.1) + (($colWidth-0.3)*2 ) + ($colWidth-1.9));
		$table->writeToCell(1,1, '   Total', $font1,new PHPRtfLite_ParFormat('left')); 
		//$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1, 1,1,false,true,false,true);
		
		//$table->addRows(1,0);
		$table->addColumn($colWidth-0.2);
		$table->writeToCell(1,2,$preco_mensal_total[$a], $font1,$format_var); 
		
		
		//$table->addRows(1,0);
		$table->addColumn($colWidth-0.76);
		$table->writeToCell(1,3,converte_horas($tot_tempo_trabalho[$a]), $font1,new PHPRtfLite_ParFormat('right')); 
		$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'),1,1,1,3,false,true,false,true);
                
		$table1 = $sect->addTable();
		$table1->addRows(1,0);
		$table1->addColumn((5*$colWidth)-0.38);
		$table1->writeToCell(1,1,$legenda_tabelas, $font_notas,new PHPRtfLite_ParFormat('justify'));
                
		//$sect->writeText($legenda_tabelas, $font_notas, new PHPRtfLite_ParFormat('justify'));
		//$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 3,1,3);
		
		//$table->setBackGroundOfCells('#EEEEEE', 1, 1, 1, 3);

		$sect->writeText('<br>', new PHPRtfLite_Font(32, 'Bookman Old Style'), new PHPRtfLite_ParFormat('center'));
		
		$legend = utf8_encode('Tabela '.(3+($a*2)).' - Varia��es mensal, semestral e anual, Cesta B�sica, ').$cidade_nome[$a].', Bahia.';	
		$sect->writeText($legend, $font_titulo_tb, new PHPRtfLite_ParFormat('left'));
		
		$table1 = $sect->addTable();
		$table1->addRows(1,-1.0);
		$table1->addColumn($colWidth+0.1);
		$table1->writeToCell(1,1, 'Produtos',$font1_bold,new PHPRtfLite_ParFormat('center'));
		$table1->addColumn($colWidth-1.81);
		$table1->writeToCell(1,2, 'Qtde.', $font1_bold,new PHPRtfLite_ParFormat('center'));
		$table1->addColumn($colWidth);
		$table1->writeToCell(1,3, utf8_encode('Varia��o Mensal* %'),$font1_bold,new PHPRtfLite_ParFormat('center'));
		$table1->addColumn($colWidth);
		$table1->writeToCell(1,4, utf8_encode('Varia��o Semestral** %'), $font1_bold,new PHPRtfLite_ParFormat('center'));
		$table1->addColumn($colWidth);
		$table1->writeToCell(1,5, utf8_encode('Varia��o Anual*** %'), $font1_bold,new PHPRtfLite_ParFormat('center'));
		
		if ($pesquisa_mes == 12) {
			$table1->addColumn($colWidth);
			$table1->writeToCell(1,6, utf8_encode('Varia��o do Ano**** %'), $font1_bold,new PHPRtfLite_ParFormat('center'));
			$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1, 1,6,false,true,false,false);
		}
		else{
			$table-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1, 1,5,false,true,false,false);	
		}
		$format_var = new PHPRtfLite_ParFormat('right');
		$format_var->setIndentRight(1);

		$table1->addRows($tam_produtos,-0.5);
		
		for($j=1;$j<=$tam_produtos;$j++)
		{
			
			$table1->addColumn($colWidth+0.1);
			$table1->writeToCell($j+1,1," ".$produto[$a][$j-1]." (".$prod_medida[$j-1].")",$font1,new PHPRtfLite_ParFormat('left'));
			
			$table1->addColumn($colWidth-1.81);
			$table1->writeToCell($j+1,2,$quantidade[$j-1], $font1,new PHPRtfLite_ParFormat('right'));
			
			$table1->addColumn($colWidth);
			$aux_font = ((str_replace(",", ".",$produto_variacao_mensal[$a][$j-1])<0)?$red_font:$font1);
			$table1->writeToCell($j+1,3,$produto_variacao_mensal[$a][$j-1],$aux_font,$format_var);
			
			$table1->addColumn($colWidth);
			$aux_font = ((str_replace(",", ".",$produto_variacao_semestral[$a][$j-1])<0)?$red_font:$font1);
			$table1->writeToCell($j+1,4,$produto_variacao_semestral[$a][$j-1],$aux_font,$format_var);
			
			$table1->addColumn($colWidth);
			$aux_font = ((str_replace(",", ".",$produto_variacao_anual[$a][$j-1])<0)?$red_font:$font1);
			$table1->writeToCell($j+1,5,$produto_variacao_anual[$a][$j-1],$aux_font,$format_var);

			if ($pesquisa_mes == 12) {
				$table1->addColumn($colWidth);
				$aux_font = ((str_replace(",", ".",$produto_variacao_do_ano[$a][$j-1])<0)?$red_font:$font1);
				$table1->writeToCell($j+1,6,$produto_variacao_do_ano[$a][$j-1],$aux_font,$format_var);
			}
		}
		if ($pesquisa_mes == 12) {
			$table1-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1,1,6,false,true,false,true);
		}
		else{
			$table1-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1,1,5,false,true,false,true);
		}
		
		//$table1->setBackGroundOfCells('#FFFFFD',3, 1, $tam_produtos+1, 5);
		
		$table1 = $sect->addTable();
		
		$table1->addRows(1,0);
		
		$table1->addColumn(($colWidth+0.1) + ($colWidth-1.81));
		$table1->writeToCell(1,1, '   Total', $font1,new PHPRtfLite_ParFormat('left')); 
		if ($pesquisa_mes == 12) {
			$table1-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1,1,6,false,true,false,true);
		}
		else{
			$table1-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'), 1, 1,1,5,false,true,false,true);
		}
		


		$table1->addColumn($colWidth);
		$aux_font = (($tot_var_mensal[$a]<0)?$red_font:$font1);
		$table1->writeToCell(1,2,$tot_var_mensal[$a],$aux_font,$format_var); 
		
		$table1->addColumn($colWidth);
		$aux_font = (($tot_var_semestral[$a]<0)?$red_font:$font1);
		$table1->writeToCell(1,3,$tot_var_semestral[$a],$aux_font,$format_var); 
		
		$table1->addColumn($colWidth);
		$aux_font = (($tot_var_anual[$a]<0)?$red_font:$font1);
		$table1->writeToCell(1,4,$tot_var_anual[$a],$aux_font,$format_var); 
		if ($pesquisa_mes == 12) {
			$table1->addColumn($colWidth);
			$aux_font = (($tot_var_anual[$a]<0)?$red_font:$font1);
			$table1->writeToCell(1,5,$tot_variacaoDoAno[$a],$aux_font,$format_var); 

			$table1-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'),1,1,1,5,false,true,false,true);
		}
		else{
			$table1-> setBordersForCellRange(new PHPRtfLite_Border_Format(1, '#111111'),1,1,1,4,false,true,false,true);
		}
		
		//$table1->setBackGroundOfCells('#EEEEEE', 1, 1, 1, 4);
		
		
		/*$strsql = "SELECT * FROM tabela_mes WHERE mes_id"
		$pesquisa_mes = $row['mes_id'];
		$pesquisa_ano = $row['pesquisa_ano'];*/
		
		//$sect->writeText($legenda_tabelas, $font_notas, new PHPRtfLite_ParFormat('justify'));
	
		$table1 = $sect->addTable();
		$table1->addRows(1,0);
		$table1->addColumn((5*$colWidth)-0.38);
		$table1->writeToCell(1,1,$legenda_tabelas, $font_notas,new PHPRtfLite_ParFormat('justify'));
	}
	
	//fim da terceira tabela
	$rtf->sendRtf('boletim'.$mes_atual.$pesquisa_ano.'.rtf');
	//$rtf->save('D:/testeBoletim.rtf');
?>