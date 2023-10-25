<?php
/**
 * Classe para representar um modelo de data.
 *
 * @author Vluzrmos
 * @version 07/03/2011 - 09/03/2011
 */


class Data  {
    /**
     * @var int $dia
     */
    private  $dia;
    /**
     * @var int $ano
     */
    private  $ano;
    /**
     * @var int $mes
     */
    private  $mes;
    const GMT_BRA = 10800;
    const GMT = self::GMT_BRA;
    
   /**
    * Construtor da classe Data
    * @param int $d é um inteiro que representa o dia
    * @param int $m é um inteiro que representa o mes
    * @param int $a é um inteiro que representa o ano
    */
     function  __construct($d=null,  $m=null,  $a=null){
	if($d!=null && $m!=null && $a!=null){
            if(self::validaData($d,$m,$a)){
                $this->dia=$d;
                $this->mes=$m;
                $this->ano=$a;
            }
        }
	else if ($m!=null && $a!=null){
	    if(self::validaData(1,$m,$a)){
                $this->setData(1, $m, $a);
            }
	}
        else {
            $nDias = (int)((time()-Data::GMT)/86400);
            $this->setData(1,1,1970);
            $this->incrementaDias($nDias);
        }
    }
    /**
     * Inicializa os campos da instancia com dia=$d mes=$m e ano=$a.
     * @param int $d
     * @param int $m
     * @param int $a
     */

    private function setData($d,$m,$a){
        $this->dia=(int)$d;
        $this->mes=(int)$m;
        $this->ano=(int)$a;
    }


    /**
     * @return int dia da instancia atual
     */
    public function getDia(){
	return $this->dia;
    }
    /**
     * @return int mes da instancia atual
     */
    public function getMes(){
	return $this->mes;
    }
    /**
     *
     * @return int ano da instancia atual
     */
    public function getAno(){
	return $this->ano;
    }
    /**
     * Método mágico que retorna uma intância da classe convertida para String
     * @return String com a data da  instância no formato dd/mm/aaaa.
     */
    public function  __toString() {
	return sprintf("%02d/%02d/%04d",$this->dia,  $this->mes,  $this->ano);
    }
    /**
     * @return String retorna uma string formatada
     * @example
     * <pre>
     * <?php 
     *	    $f = "@dia/@mes/@ano";
     *	    $d = new Date(1,1,2010);
     *	    echo $d->format($f);
     * ?>
     * </pre>
     *  saida:<br/> 01/01/2010
     */
    public function format($f){
	if(!$f)
	    return $this."";
	
	$d = sprintf("%02d",$this->dia);
	$m = sprintf("%02d",$this->mes);
	$a = sprintf("%04d",$this->ano);

	$f = preg_replace(array("/@dia/i","/@mes/i","/@ano/i"),array($d,$m,$a),$f);
	return $f;
    }
    /**
     * Método mágico que cria nova instância a partir da atual.
     * @return Data nova instância com as mesmas informações da atual.
     */
    public function  __clone() {
	return new self($this->dia,$this->mes,$this->ano);
    }

    /**
     * Método estatico que compara duas  duas Data's
     * @param Data $d1 instância da classe Data
     * @param Data $d2 instância da classe Data
     * @return long tempo em segundos com a diferenca d1-d2.
     */
    static function cmp(Data $d1, Data $d2){
	return self::diff($d1, $d2);
    }

    /**
     * Retorna a diferença entre Data $d1 e Data $d2 em segundos
     * @see Data::cmp(Data $d1, Data $d2);
     */
    public static  function diff(Data $d1, Data $d2){
	    return $d1->toTime() - $d2->toTime();
    }
    /**
     * Retorna a diferença entre Data $d1 e Data $d2 em dias
     * @param Data $d1 instância da classe Data
     * @param Data $d2 instância da classe Data
     * @return int Retorna o numero de dias entre a $d1 e $d2 (negativo indica que a segunda é maior).
     */
    public static function diffdias(Data $d1, Data $d2){
	return (int)(self::diff($d1,$d2)/86400);/*86400 equivale a 1 dia em segundos*/
    }
    /**
     * Calcula a diferença de meses entre duas datas de forma que sempre retorna valores >= 1 (primeiro mês sempre está incluso)
     * se a segunda data estiver no intervalo, será contada.
     * @param Data $d1
     * @param Data $d2
     * @param int $intervalMeses intervalo entre os meses. Default é 1.
     * @return int Número de meses entre as duas datas, considerando o intervalo indicado. Obs.: Zero, indica que o intervalo enviado por parametro é menor ou igual a zero.
     */
    public static function diffMeses(Data $d1, Data $d2, $intervalMeses = 1 ){
	$i = 0;
	$d = $d1->dia;
	$m = $d1->mes ;
	$a = $d1->ano;
	$sinal = 1;
	$d0aux = null;

	if($intervalMeses<=0){
	    return $i;
	}
	
	if(self::diff($d1, $d2)>=0){
	    $d0aux = $d1;
	    $d1 = $d2;
	    $d2 = $d0aux;
	    $sinal = -1;
	}
	


	do{
	  $i++;
	  $m+=$intervalMeses;
	  if($m>12){
	    $m = $m - 12;
	    $a += 1;
	  }
	  
	}while(self::diff(new self($d,$m,$a), $d2)<=0);

	return $i*$sinal;
    }
    /**
     * Retorna a instância da data atual em segundos
     * @return long tempo de 1/1/1970 gmt em segundos
     */
    public function toTime(){
	return gmmktime(0,0,0,$this->mes,$this->dia,$this->ano);
    }

    /**
     * Verifica se a instância atual é igual à instância em $d
     * @param Data $d instância da classe Data.
     * @return boolean returna um booleano true se forem iguais, e false
     * caso contrario.
     */
    public function equalTo(Data $d){
	return !self::diff($this,$d);

    }

    /**
     * @param int $d dia.
     * @param int $m mês.
     * @param int $a ano.
     * @return boolean
     */
    public static function validaData($d, $m,$a){
        if($d<=0 || $d > 31){
           return false;
       }
       else if($m < 1 || $m > 12){
           return false;
       }

        switch($m){
            case 2:
                return self::validaFevereiro($d,$a);

            case 4:
            case 6:
            case 9:
            case 11:
                if ($d>30){
                    return false;
                }

        }
        return true;
   }
   /**
    * Verifica se o ano em questao é bissexto, se for, 
    * verifica se o mês é fevereiro e se tem mais de 28 dias.
    * @param int $d dia.
    * @param int $a ano.
    * @return boolean
    */
   public static function validaFevereiro($d, $a){
        if($d > 29){
            return false;
        }

        /* Verifica ano  bissexto*/
	$bissexto = self::isAnoBissexto($a);

        if (!($bissexto)){
           if($d>28){
                return false;
            }
        }

        return true;
   }
   /**
    * Retorna aquantidade de dias num mês levando em consideração
    * se o ano é bissexto.
    * @return int quantidade de dias num mês
    */
   public function qtdDiasNoMes(){
       if($this->mes==4||$this->mes==6||$this->mes==9||$this->mes==11){
           return 30;
       }
       else if($this->mes==2){
            if(self::isAnoBissexto($this->ano)){
                return 29;
            }
            else {
                return 28;
            }
       }
       else
           return 31;

   }

   public function incrementaDias($qtdDias){
       $diaFinal = $this->dia + (int)$qtdDias;
       $diasDoMes = $this->qtdDiasNoMes();
       while($diaFinal > $diasDoMes){
           $diaFinal -= $diasDoMes;
           $this->mes++;
           if($this->mes > 12){
               $this->mes = 1;
               $this->ano++;
           }
           $diasDoMes = $this->qtdDiasNoMes();
       }
       $this->dia = $diaFinal;
   }
   /**
    * Retorna um boolean que indica se o ano passado por parametro é bissexto
    * @param int/long $a
    * @return boolean true se o parametro $a (ano) for bissexto.
    */
   public static function isAnoBissexto($a){
       return ($a%4==0 && $a%100!=0) ||($a%400==0);
   }
} 
?>
