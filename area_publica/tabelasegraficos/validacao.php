<?php
/*******************************************************************************
 * ** ** ** ** ** ** **   VALIDACAO DO FORMULARIO * ** ** ** ** ** ** ** ** ** *
 *******************************************************************************/
/*
 * Muda os caracteres retornados pelas funções do
 * mysql para utf8, default é latin1
 */
ini_set('default_charset',"UTF-8");
mysqli_set_charset($conn,'utf8');

/*
 * Classe Data
 */
include_once CLASS_DATA_PATH;
/*
 * Array padroes e definicoes
 */

 $__TIPO_EXIBICAO =  array('tabela','grafico');
 $__TIPO_CONSULTA = array(
    'produto_preco_total'=>'Gasto Mensal',
    'produto_tempo_trabalho'=>'Tempo de Trabalho',
    'produto_preco_medio'=>'Preco M&eacute;dio',
    'cesta_custo_total'=>'Custo Total da Cesta'
    );
/**
 * Numero maximo de meses à pesquisar
 */
 define("GRAFICOS_MAX_MESES",12);
/*
 * Valida a data inicial e a data final do periodo
 */
$data = new Data();
function valida_data_ini_final($data){
     list($mes,$ano) = extrai_mes_ano_form($data);

	    if($mes < 1 || $ano < 1900 || $mes>12 || $ano > date('Y')){
		return false;
	    }
	    return true;

 }
 /*
  * Retorna array no formato [0] = mes, [1] = ano
  */
 function extrai_mes_ano_form($data){
     list($mes,$ano) = preg_split('/(\/|\\'.decoct(ord('-')).'|\.)/',$data);
     $mes = (int) $mes;
     $ano = (int) $ano;
     return array($mes,$ano);
 }
 /*
  * Verifica se o tipo de consulta é permitido, retorna true, caso afirmativo.
  */
 function valida_tipo_consulta($campo , &$f){
	global  $__TIPO_CONSULTA;
	$__tipos = &$__TIPO_CONSULTA;
	if(!array_key_exists($campo, $__tipos)) return false;
	$f['tipoconsulta_str'] = $__tipos[$campo];
	return true;
}
/*
 * Verifica se o tipo de exibicao é permitido,
 *  retorna true caso afirmativo.
 */
function valida_tipo_exibicao($campo, &$f){
    global  $__TIPO_EXIBICAO;
    $__tipos = &$__TIPO_EXIBICAO;
    if(in_array($campo, $__tipos)) return true;
    return false;
}


/*
  * Regras de validação
  *	Required  - Campo obrigatorio
  *	maxlength - Tamanho/quantidade maximo(a)
  *	Minlength - Tamanho/quantidade minimo(a)
  *	func	  - Funcao de validação, parametros $campo =campo_do_formulario e &$f=formulario,
  */
$rules = array(
    'tipoexibicao'=>array(
        'required'=>true,
        'func'=>'valida_tipo_exibicao'
    ),
    'tipoconsulta'=>array(
        'required'=>true,
        'func'=>'valida_tipo_consulta'
    ),
    'p_inicio'=>array(
        'required'=>true,
        'maxlength'=>7,
        'minlength'=>6,
        'func'=>'valida_data_ini_final'
    ),
    'p_final'=>array(
        'required'=>true,
        'maxlength'=>7,
        'minlength'=>6,
        'func'=>'valida_data_ini_final'
    ),
    'cidades'=>array(
        'required'=>true,
        'minlength'=>1
    ),
    'produtos'=>array(
        'required'=>true,
        'minlength'=>1
    ),
    //'campo1' => array('required' => true, 'maxlength' => 50, 'minlength' => 2),
    //'campo2' => array('required' => true, 'maxlength' => 100),
);
/*
 * Mensagens de validação
 *  Para cada regra de validacao (acima) uma mensagem de atenção(warning)
 */
$messages = array(
    'tipoexibicao'=>array(
        'required'=>'Deve escolher entre gr&aacute;fico ou tabela',
        'func'=>'Tipo de exibicao inv&aacute;lido'
    ),
    'tipoconsulta'=>array(
        'required'=>'Deve escolher um dos tipos de consultas',
        'func'=>'Tipo de consulta inv&aacute;lido'
    ),
    'p_inicio'=>array(
        'required'=>'Data inicial &eacute obrigat&oacute;ria',
        'maxlength'=>'Data inicial inv&aacute;lida',
        'minlength'=>'Data inicial inv&aacute;lida',
        'func'=>'Data inicial inv&aacute;lida'
    ),
    'p_final'=>array(
        'required'=>'Data final &eacute obrigat&oacute;ria',
        'maxlength'=>'Data final inv&aacute;lida',
        'minlength'=>'Data final inv&aacute;lida',
        'func'=>'Data final inv&aacute;lida'
    ),
    'cidades'=>array(
        'required'=>'Cidade(s) n&atilde;o selecionada(s)',
        'minlength'=>'Deve escolher ao menos '.$rules['cidades']['minlength'].' cidade(s)'
    ),
    'produtos'=>array(
        'required'=>'Produto(s) n&atilde;o selecionado(s)',
        'minlength'=>'Deve escolher ao menos '.$rules['produtos']['minlength'].' produto(s)'
    )
);
?>
