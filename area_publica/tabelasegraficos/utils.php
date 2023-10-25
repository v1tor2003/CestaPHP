<?php
/**
 * Formulario oculto com os parametros para retornar ao formulario anterior
 */
function goback($f){
	global $thisFile;	    ?>
<form action="<?php echo $thisFile;?>" name="goback" id="goback" class="goback"  method="POST">
			    <?php foreach($f as $campo => $vlr):
				    if(is_array($vlr)){
					foreach($vlr as $vlrArr){
					    echo "<input type='hidden' name='".$campo."[]' value='$vlrArr' />";
					}
				    }
				    else{
						echo "<input type='hidden' name='$campo' value='$vlr' />";
				    }
				endforeach;
			    ?>
			    <input type='submit' style="width:150px;margin-bottom:20px;" value="Voltar ao Formul&aacute;rio"/>
			</form>
			<?php
}
/**
 * Array com os nomes dos meses em portugues e com a primeira letra maiuscula
 */
$nomeMeses=array(
		"Janeiro",
		"Fevereiro",
		"Mar&ccedil;o",
		"Abril",
		"Maio",
		"Junho",
		"Julho",
		"Agosto",
		"Setembro",
		"Outubro",
		"Novembro",
		"Dezembro");
/**
 * formata uma data do vetor de periodos com o formato mes(3 letras) - ano(4 digitos) exemplo: Jan - 2011
 */
function formata_data2tabela($form_data){
	global $nomeMeses;
	list($mes,$ano) = extrai_mes_ano_form($form_data);
	return substr($nomeMeses[$mes-1],0,3)." - ".$ano;
}

/**
 * verifica se todos os valores do vetor $needle est&atilde;o em $array 
 */
function in_array_indexed($needle,$array){
	$retorno=false;
	if(count($needle) > 0) $retorno=true;
	foreach ($needle as $needleValue){
		if(! in_array($needleValue,$array) ) return false;
	}
	
	return $retorno;
}
/**
 * Varia&ccedil;&atilde;o de implode(array,separator), que podem ser definidos
 * caracteres para ficar ao redor dos valores do array
 * @example
 * <br/>&lt;?php <br/>
 *    echo '&lt;ul>'.array_implode(array('casa','carro','mesa'),'','&lt;li>','&lt;/li>').'&lt;/ul>';
 * <br/>?&gt;
 * <br/> Vai gerar:<br/>
 * &lt;ul><br/>
 * &lt;li>casa&lt;/li><br/>
 * &lt;li>carro&lt;/li><br/>
 * &lt;li>mesa&lt;/li><br/>
 * &lt;/ul>
 */
function array_implode($array, $separator , $ini='',$end=''){
	$str="";
	foreach($array as $vlr){
		$str .= $ini.$vlr.$end.$separator;
	}
	return substr($str,0,strrpos($str,$separator));
	
}
/**
 * Divide um array em suas keys
 * @see array_implode();
 */
function array_implode_keys($array, $separator , $ini='',$end=''){
	return array_implode(array_keys($array),$separator , $ini,$end);
}
?>