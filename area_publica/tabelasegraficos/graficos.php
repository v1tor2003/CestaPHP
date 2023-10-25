<?php
//<h4> Em constru&ccedil;&atilde;o , dentro de alguns dias esta op&ccedil;&atilde;o estar&aacute; dispon&iacute;vel .</h4>
?>
<script type="text/javascript" src="../javascript/Highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="../javascript/Highcharts/js/themes/grid.js"></script>
<script type="text/javascript" src="../javascript/Highcharts/js/modules/exporting.js"></script>
<script type="text/javascript">

    var mon = [
        'Janeiro',
        'Fevereiro',
        'Março',
        'Abril',
        'Maio',
        'Junho',
        'Julho',
        'Agosto',
        'Setembro',
        'Outubro',
        'Novembro',
        'Dezembro'
    ];

    Highcharts.setOptions({
        lang: {
            months: mon,
            weekdays: [
                'Domingo',
                'Segunda',
                'Terça',
                'Quarta',
                'Quinta',
                'Sexta',
                'Sabado'
            ]
        },
        colors: [
            '#058DC7',
            '#50B432',
            '#ED561B',
            '#2020c4',
            '#24CBE5',
            '#64E572',
            '#df0c0c',
            '#FFF263',
            '#6AF9C4',
            '#cc2ee4',
            '#4a1856',
            '#a29b95',
            '#ba9c40'
        ]
    });
</script>


<?php
$cont = -1;
foreach ($info as $c_nome => $p) {
    $cont++;
?>

    <sub>Passe o mouse sobre o gráfico para obter mais informações</sub>
    <div  id="container<?php echo $cont; ?>"  class="graficoCidade"></div>
    <script type="text/javascript">
        $(document).ready(function(){
            var chart = new Highcharts.Chart({
                chart: {
                    renderTo: '<?php echo 'container' . $cont ?>',
                    defaultSeriesType: 'column'

                },
                plotOptions:{
                    column:{
                        pointWidth: 5
                    }
                },

                credits:{
                    enabled:false
                },
                title: {
                    text: '<?php echo $c_nome ?>'
                },

                tooltip: {
                    formatter:function(){
                        var d = new Date(this.x);
                        var produtoinfo = '<br/><strong>'+this.series.name+':</strong> '+<?php
                            if ($f['tipoconsulta'] == 'produto_tempo_trabalho')
                                echo "parseInt(this.y) +' horas e '+parseInt((this.y-parseInt(this.y))*60)+' minutos'";
                            else {
                                echo "'$salario_simbolo '+(new Number(this.y).toFixed(2)+'').replace(\".\",\",\")";
                            }
                        ?>;
                        var mes = parseInt(d.getMonth());
                        var ano = d.getFullYear();
                        return mon[mes]+' de '+ ano + produtoinfo;
                      }
                  },//fim tooltip
                  legend:{
                      //margin:'40px'
                   },
                   xAxis: {
                       labels:{
                             //rotation: -90,
                           align: 'center'
                       },
                     
                                      type:"datetime",
                                      dateTimeLabelFormats :{
                                          month:"%b<br/>%y"
                                      },
                                      //startOnTick:true,
                                      //showFirstLabel:true,
                                      //showLastLabel:true,
                                      tickInterval: <?php $meses = ($f['periodoMeses']) * 30 * 3600000 * 24;  echo $meses; ?>,
                                      maxZoom: <?php echo $meses; ?>
                                  },
                                  yAxis:{
                                      title:{
                                          text:" <?php echo ($f['tipoconsulta'] == 'produto_tempo_trabalho') ? 'Valores em Horas' : 'Valores em ' . $salario_simbolo; ?>"
                                      },
                                      labels:{
                                          formatter: function(){
                                              //var val = (new Number(this.value).toFixed(2)+'').replace(".",",");
                                              var val=  <?php
                                                    if ($f['tipoconsulta'] == 'produto_tempo_trabalho')
                                                        echo "parseInt(this.value)+ ' h';" .
                                                        "if(mins = parseInt((this.value-parseInt(this.value))*60)){val+=' e '+mins+ ' min'}";
                                                    else {
                                                        echo /* "'$salario_simbolo '+ */"(new Number(this.value).toFixed(2)+'').replace(\".\",\",\")";
                                                    }
                                                ?>;

                                                     return val;
                                               }//fim formatter labels
                                             }//fim labels

                                         },//fim yAxis
                                         //Date.UTC(ano,mes,dia,hora,minuto,segundo,milisegundos),  0 =< mes <=11 (zero based)
                                         series: [ <?php
                                                    //unset($dados_graph);
                                                    //unset($jsonarray);
                                                    $dados_graph = array();
                                                    $jsonarray = array();

                                                    foreach ($p as $periodo => $produto) {
                                                        foreach ($produto as $prod_nome => $vlr) {
                                                            list ($mes, $ano) = extrai_mes_ano_form($periodo);
                                                            $dados_graph[$prod_nome][] = "[Date.UTC($ano," . ($mes-1) . ",1),$vlr]";
                                                        }
                                                    }
                                                    foreach ($dados_graph as $p_nome => $vlr) {
                                                        $jsonarray [] = "{name: '$p_nome', data:[" . implode(',', $vlr) . "]}";
                                                    }
                                                    echo implode(',', $jsonarray);
                                                ?>  ]//fim series
                            })//fim new HighCharts(...)
                        });//fim (document).ready(...);

    </script>



<?php
}//fim do grafico de  1 cidade (foreach($info...))
?>
