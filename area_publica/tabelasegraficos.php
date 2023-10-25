<?php

//error_reporting(E_ALL&~E_NOTICE);
/**
 * Variavel com o nome do arquivo atual.
 */
$thisFile = basename(__FILE__);
/**
 * Variavel com o nome da pasta de recursos para esse arquivo
 */
$path_recursos = "./tabelasegraficos/";
@require_once($path_recursos.'utils.php');
@require_once($path_recursos.'validacao.php');

$form = array_merge($_POST,$_GET);
$f = &$form;

/*
 * Gera data inicial do periodo baseado na quantidade maxima
 * de meses possiveis para os gráficos (GRAFICOS_MAX_MESES)
 */
$dataAtual = new Data();
$mesA  = $dataAtual->getMes();
$anoA  = $dataAtual->getAno();

$mcalc = $mesA - GRAFICOS_MAX_MESES - 1;
if($mcalc <= 0){
	$mcalc = 12 + $mcalc + 2;
	$acalc = $anoA - 1;

	if($mcalc > 12 ){
	$mcalc = $mcalc - 12;
	$acalc +=1;
	}

	if($mcalc<10){
	$mcalc = "0".$mcalc;
	}
}

$f_default_values = array(
		'tipoexibicao' => array('value'=>array(0)),//array com posição dos elementos ativos
		'p_inicio'     => array('value'=> $mcalc."/".$acalc),
		'p_final'      => array('value'=>$dataAtual->format("@Mes/@Ano")),
		'periodoMeses' => array('value'=>'1'),
		'tipoconsulta' => array('value'=>array(0)),//array com posição dos elementos ativos
		'cidades'	   => array('value'=>array(0)),//array com posição dos elementos ativos
		'produtos'	   => array('value'=>array(0)) //array com posição dos elementos ativos
);

/*
 * Motor de validação
 *  Utiliza os vetores $rules e $messages para validar o
 *  formulario e informar ao usuario caso haja algum erro.
 *  $rules e $messages estão em tabelasegrafivos/validacao.php
 */
if($form){
    foreach($rules as $campo => $vlr){
	if($vlr['required'] && ($form[$campo]=='' || !isset($form[$campo]))){
	    $erro[$campo] = $messages[$campo]['required'];
	}
	else if($vlr['maxlength'] && (is_array ($form[$campo])?count($form[$campo]):strlen($form[$campo]))>$vlr['maxlength']){
	    $erro[$campo] = $messages[$campo]['maxlength'];
	}
	else if($vlr['minlength'] && (is_array ($form[$campo])?count($form[$campo]):strlen($form[$campo]))<$vlr['minlength']){
	    $erro[$campo] = $messages[$campo]['minlength'];
	}
	else if(isset($vlr['func']) && !$vlr['func']($form[$campo],$form)){
	    $erro[$campo] = $messages[$campo]['func'];
	}
    }

    if(!$erro['p_inicio'] && !$erro['p_final'] && $f['tipoexibicao']=='grafico'){
	list($mi,$ai)= extrai_mes_ano_form($f['p_inicio']);
	list($mf,$af)= extrai_mes_ano_form($f['p_final']);
	$datai = new Data(1, $mi, $ai);
	$dataf = new Data(1,$mf,$af);

	$totalMeses = Data::diffMeses($datai, $dataf, (int)$f['periodoMeses']);
	if($totalMeses){
	    if($totalMeses>30)
		$erro['periodo_maximo'] = "$totalMeses meses, Você excedeu o tamanho máximo para o período de consulta que é de 30 meses";
	}
	else
	    $erro['validacaoPeriodo'] ="Não foi possível calcular o período de meses, contacte o administrador do sistema.";
    }
}
/*
 * Cabeçalhos desta pagina
 *  Estes serão incluidos dentro da tag <head/> da pagina (x)html
 */
$include_head[]='<link href="boletim.css" type="text/css" rel="stylesheet"/>';
$include_head[]= '<link href="tabelasegraficos.css" type="text/css" rel="stylesheet"/>';
$include_head[]= '<script type="text/javascript" src="../javascript/jquery.js"></script>';
include ('cabecalho.php'); ?>
<div id="main" class="noMainPage">
    <div id="inner">
		<h1 class="title">Tabelas e Gr&aacute;ficos</h1>
    
    <?php
    /*
     * Campo de exibição de erros durante a validação
     */
     if(isset($erro)){
	?>
    <p class="error">
    <?php
	    echo '<span>Foram encontrados os seguintes erros:';
	    foreach($erro as $campo => $vlr){
		echo '<br/>'.$vlr;
	    }
	?>
    </p>
    <?php }// fim if $erro
    if(!$form || $form['noexibe']=="true" || isset($erro)){
/*******************************************************************************
 * ** ** ** ** ** ** ** **  ** ** FORMULARIO  ** ** ** ** ** ** ** ** ** ** ** *
 *******************************************************************************/
       @require ($path_recursos.'form.php');
     
	}//fim exibe opções do formulario
	 else if(!$erro){
		$f['noexibe']='true';
/*******************************************************************************
 * ** ** ** ** ** ** ** **  ** ** RESULTADOS  ** ** ** ** ** ** ** ** ** ** ** *
 *******************************************************************************/
		    ?>
	<div id="result">
		<?php
                    @require($path_recursos.'extractBD.php');
                ?>
			
			<a href="javascript: document.getElementById('goback').submit(); void(0);" > Voltar ao formul&aacute;rio </a>
			<h2 class="title"><?php
			    echo $f['tipoconsulta_str'];//.
			    if($f['tipoconsulta']!='produto_tempo_trabalho'){
				echo ', valores em '.$salario_simbolo." ";
			    }
			    echo ' &mdash; Per&iacute;odo de '.$f['p_inicio'].' at&eacute; '.$f['p_final'];
			?></h2>
			<?php
                        if($info){
    /***************************************************************************
     * ** ** ** ** ** ** ** ** ** ** ** ** TABELAS  ** ** ** ** ** ** ** ** ** *
     ***************************************************************************/
				if($f['tipoexibicao']=='tabela'){
			    
                                    @require($path_recursos.'tabelas.php');
			
				}//end tipoexibicao tabela
    /***************************************************************************
     * ** ** ** ** ** ** ** ** ** ** ** * GRAFICOS  ** ** ** ** ** ** ** ** ** *
     ***************************************************************************/
                                elseif(($f['tipoexibicao']=='grafico')&& ($f['tipoconsulta'] != 'cesta_custo_total')){
                                    @require($path_recursos.'graficosCategorias.php');
                                }
                                else{
                                	@require($path_recursos.'graficosCustoTotal.php');
                                }
                        }
			else{
			    echo 'Nenhum resultado encontrado para esse per&iacute;odo!';
			}

                     //botao com a opção de retornar para o preenchimento do formulario
		     goback($f); ?>
		</div><!--Fim #result-->
		<?php
		}//fim if !erro
		
	    ?>

    </div>
</div><!--end div #main-->
<?php require("rodape.php");

		    ?>