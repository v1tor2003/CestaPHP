/**
 * Nome dos meses
 */
var mon, months;
months = mon = [
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
/**
 *Opções gerais do highcharts
 */
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
    plotOptions:{
        area: {
            stacking: 'normal'
        },
        areaspline:{
            stacking: 'normal'
        }
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

/**
 * Envia o tipo de grafico para a pagina
 */
function submit_chartType( type){
    document.goback.noexibe.value  = 'false';
    document.goback.chartType.value=type;
    document.goback.submit();
}
/**
 * gera um array cujos valores sao os indeces de var need
 */
function array_keys_index(need){
    var keys = [];
    for(var i=0;i<need.length;i++){
        keys.push(i);
    }
}
/**
 * Recebe como entrada um numero float representado as horas
 * @return string texto com a hora formatada
 */
function parseStringHora(floatVal){
    var val='';

    var hora = parseInt(floatVal);
	
    var minsFloat = (floatVal-hora)*60;
    var mins      = parseInt(minsFloat);
    var seg  = parseInt((minsFloat - mins)*60);
    if(hora){
        val+=hora + ' h';
    }
    if(mins){
        if(hora){
            val+=' e';
        }
        val+=' '+mins+ ' min';
    }
    /*
	if(seg){
		if(mins||horas){
			val+=' e';
		}
		val+= ' '+seg+ ' seg';
	}//*/
	
    return val||"0 min";
}
/**
 * Retorna o numero do mes em str
 * @param str string com uma data na forma mes/ano
 * @return @type int
 */
function getMes(str){
    var bar = str.lastIndexOf('/');
    if(bar >= 1 && bar <=2){
        var mes = parseInt(str.substr(0,bar));
    //var ano = parseInt(str.substr(bar+1));
    }else{
        return str;
    }
	
    return mes;
}

/**
 * Retorna o numero do ano em str
 * @param str string com uma data na forma mes/ano
 * @return @type int
 */
function getAno(str){
    var bar = str.lastIndexOf('/');
    if(bar >= 1 && bar <=2){
        //var mes = parseInt(str.substr(0,bar));
        var ano = parseInt(str.substr(bar+1));
    }else{
        return str;
    }
	
    return ano;
}
/**
 * recebe um float como parametro returna na forma %,.2f
 */
function parseStringMonetaria(floatValue){
    return (new Number(floatValue).toFixed(2)+'').replace([".",","],[",","."]);
}