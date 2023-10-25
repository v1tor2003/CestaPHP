
<!--<base href='../'/>-->
<script type="text/javascript" src="../javascript/jquery.js"></script>
<script type="text/javascript" src="../javascript/Highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="../javascript/Highcharts/js/themes/grid.js"></script>
<script type="text/javascript" src="../javascript/Highcharts/js/modules/exporting.js"></script>
<script type="text/javascript" src="tabelasegraficos/utils.js"></script>
<?php
$chartTypes['line'] = "linhas";
$chartTypes['spline'] = "linhas suaves";
$chartTypes['area'] = "areas";
$chartTypes['areaspline'] = "areas com linhas suaves"; //grafico fica por cima do outro, dificultando a visao
$chartTypes['column'] = 'colunas verticais';
//$chartTypes['bar'] ='colunas horizontais';//grafico com erros
//$chartTypes['pie'] ='pizza';//grafico faltando parametros
$chartTypes['scatter'] = 'somente pontos';

//tipo do grafico atual
if ($f['chartType']) {
    $chartType = $f['chartType'];
} else {
    $chartType = 'column';
}
?>
<form action="" method="">
    <label> Tipo de Gr&aacute;fico: <select name="chartType" onchange="submit_chartType(this.value);">
        <?php
        foreach ($chartTypes as $ind => $vlr) {
            echo "<option value='{$ind}' " . ($ind == $chartType ? 'selected="selected"' : '') . ">" . $vlr . "</option>";
        }
        ?>
    </select></label>
</form>
<?php
@require_once('tabelasegraficos/utils.php');

$cont = -1;

/* //Exemplos de entradas para o programa
  $info['cidade']['1/2010']['arroz'] = '1';
  $info['cidade']['1/2010']['feijao'] = '2';
  $info['cidade']['1/2010']['leite'] = '3';
  $info['cidade']['1/2010']['macarao'] = '4';
  $info['cidade']['2/2010']['arroz'] = '1';
  $info['cidade']['2/2010']['feijao'] = '2';
  $info['cidade']['2/2010']['leite'] = '3';
  $info['cidade']['2/2010']['macarao'] = '4';
  $info['cidade']['3/2010']['arroz'] = '1';
  $info['cidade']['3/2010']['feijao'] = '2';
  $info['cidade']['3/2010']['leite'] = '3';
  $info['cidade']['3/2010']['macarao'] = '4';
 */



$isTempoTrabalho = $f['tipoconsulta'] == 'produto_tempo_trabalho';
//legenda do eixo Y
$yAxisText = ($isTempoTrabalho) ? 'Valores em Horas' : 'Valores em ' . $salario_simbolo;
//legenda do eixo X
$xAxisText = ""; //"Meses";

foreach ($info as $c_nome => $p) {
    $cont++; //gera codigo do id deste container
    //nome da cidade
    $cidade = $c_nome;
    //id do container do grafico
    $container_id = "container_" . $cont;
    //variavel do grafico
    $chartVar = "chart" . ucfirst($container_id);

    //array com todos os periodos
    $periodos = array_keys($p);
    //array de categorias para o eixo x do gráfico
    $xAxisCategories = array_implode($periodos, " ,", "'", "'");

    //gera array com os nomes de todos os produtos(não repetidos) para esta cidade
    $valores = array();
    foreach ($p as $prod) {
        $keys_prod = array_keys($prod);
        if (!in_array_indexed($keys_prod, $valores))
            $valores = array_merge($valores, $keys_prod);
    }

    
    $series = array();

    $data = "";
    foreach ($valores as $valores => $valor) {
        $data .= "{$valor}";
        $data .= " ,";
    }
    $data = "{ name: 'Custo Total da Cesta Básica', data: [ " . substr($data, 0, strrpos($data, ',')) . " ] }";
    $series[] = $data;
    
    // print_r($series);
    // die();

    $chartSeries = "series: [ " . implode($series, ',') . " ]";
    ?>
    <sub>Passe o mouse sobre o gr&aacute;fico para obter mais informa&ccedil;&otilde;es</sub>
    <div  id="<?php echo $container_id; ?>"  class="graficoCidade"></div>
    <script type="text/javascript">
        jQuery(function($){
            <?php echo "var " . $chartVar . " = //"; ?>  ;
            new Highcharts.Chart(
            {
             chart: {
                 renderTo: '<?php echo $container_id ?>',
                 defaultSeriesType: '<?php echo $chartType ?>'
             },
             credits:{
                 enabled:false
             },
             title: {
                 text: '<?php echo $cidade; ?>'
             },
             tooltip:{
                 formatter:function(){
                     return "<strong>"+this.series.name+":</strong>"+<?php if ($isTempoTrabalho) {
                        echo 'parseStringHora(this.y)+';
                    } else {
                        echo "'{$salario_simbolo} '+parseStringMonetaria(this.y)+";
                    }; ?>"<br/>"+months[getMes(this.x)-1]+" de "+getAno(this.x);
                }
                             },//*/
                             xAxis:{
                                 categories: [ <?php echo $xAxisCategories; ?> ],
                                 //, title: { text: <?php echo "'" . $xAxisText . "'"; ?> }
                                 labels:{
                                     formatter:function(){
                                         return months[getMes(this.value)-1].slice(0,3)+" - "+getAno(this.value);
                                     }
                                 }
                             },
                             yAxis:{
                                 title: {text: '<?php echo $yAxisText; ?>'},
                                 labels: {
                                     formatter:function(){
                                        <?php
                                        if ($isTempoTrabalho) {
                                            echo 'return parseStringHora(parseFloat(this.value));';
                                        } else {
                                            echo 'return parseStringMonetaria(parseFloat(this.value));';
                                        }
                                        ?>
                                    }
                                }
                            }
                            <?php if ($chartSeries)
                            echo "," . $chartSeries; ?>

                           });//*///fim new HighCharts(...)
                  });//fim jQuery(function($){});
</script>



<?php
}//fim do grafico de  1 cidade (foreach($info...))
?>
