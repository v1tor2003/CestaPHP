<?php
include_once("jpgraph.php");
include_once("jpgraph_line.php");
// CRIANDO A MATRIZ DE PONTOS

// CRIANDO O GRÁFICO
$grafico = new Graph(300,300);
// ESCALA AUTOMATICA
$grafico->SetScale("textint");

//-------------------CONSTRUINDO AS LINHAS GRÁFICO---------------------//
// JOGA OS DADOS DA MATRIZ E PLOTA EM UM GRAFICO LINEAR
$line = new LinePlot($gasto_mensal);
// MOSTRA OS PONTOS (LINHAS)
$line->value->Show();
// MOSTRA COR DA LINHA
$line->value->SetColor("blue");
// SETA FONTE E ESTILO DA FONTE
$line->value->SetFont(FF_FONT1,FS_BOLD);
//----------------------------------------------------------------------------------//

// ADCIONA AS LINHAS NO GRÁFICO
$grafico->Add($line);

//-----------------------PROPRIEDADES DA IMAGEM--------------------------//
// DEFINE AS MARGENS DA IMAGEM
$grafico->img->SetMargin(40,40,40,40);
// DEFINE O TÍTULO DA IMAGEM
$grafico->title->Set("Gráfico JPGRAPH");
// DEFINE O TÍTULO DO EIXO X
$grafico->xaxis->title->Set("Variação do Gasto Mensal");
// DEFINE O TÍTULO DO EIXO Y
$grafico->yaxis->title->Set("Preço");
//---------------------------------------------------------------------------------//

// MOSTRANDO O GRÁFICO NO BROWSER
$grafico->Stroke();
?>