<?php
  error_reporting(E_ERROR | E_PARSE);
	$ano_array = array();
	$codigo= array();
	$action = $_REQUEST['haction'];
	$cronograma_id = $_REQUEST['hid'];
	$ano = $_REQUEST['ano'];
	
	for( $i = 0 ; $i<12 ; $i++ )
	{
		$data_inicio[] = $_REQUEST['data_inicio'.$i];
		$data_fim[]= $_REQUEST['data_fim'.$i];
	}
	
	$qt_dtini = count($data_inicio);
	$qt_dtfim = count($data_fim);
	
	if($action == 'edit')
	{
		//Pega todas informações do cronograma de coletas de um determinado ano
		$strsql = "SELECT * FROM tabela_cronograma_coletas NATURAL JOIN tabela_auxiliar_cronograma NATURAL JOIN tabela_mes WHERE cronograma_id = '".$cronograma_id."' ORDER BY mes_id";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		$i = 0;
		while($row = mysqli_fetch_array($res))
		{
			$cronograma_id = $row['cronograma_id'];
			$ano = $row['ano'];
			$data_inicio[$i] = formata_data($row['inicio_coleta'],1);
			$data_fim[$i++] = formata_data($row['fim_coleta'],1);
			$mes_nome[] = $row['mes_nome'];
			
		}
	}
	else
		if($action == 'save')
		{
		
			 $strsql = "SELECT * FROM tabela_cronograma_coletas WHERE ano= '".$ano."' AND cronograma_id <> '".$cronograma_id."'";
			 $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 
			 
			if ($res && mysqli_num_rows($res)>0)
				$herr = "Existe outra cronograma para '".$ano."'.";
			else
			{
			
				for($i=0;$i<$qt_dtini;$i++)
				{
					$data_inicio[$i] = formata_data($data_inicio[$i],2);
					$data_fim[$i] = formata_data($data_fim[$i],2);
				}
		
			  
			  	if ($cronograma_id!='')
			  	{
					$strsql = "UPDATE tabela_cronograma_coletas SET ano = '".$ano."' WHERE cronograma_id = '".$cronograma_id."'";  	
					mysqli_query($conn, $strsql) or dir(mysqli_error($conn));
				
					$strsql = "DELETE FROM tabela_auxiliar_cronograma WHERE cronograma_id = '".$cronograma_id."'";
					mysqli_query($conn, $strsql) or dir(mysqli_error($conn));
				
			  	}			
			  	else
			  	{
					$strsql = "INSERT INTO tabela_cronograma_coletas (ano) VALUES ('".$ano."')";
					mysqli_query($conn, $strsql) or die(mysqli_error($conn));
				
					$strsql = "SELECT * FROM tabela_cronograma_coletas WHERE ano = '".$ano."'";
					$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					$row = mysqli_fetch_array($res);
					$cronograma_id = $row['cronograma_id'];
				}
			  
				for($i=0;$i<$qt_dtini;$i++)
				{
				
					 $strsql = "INSERT INTO tabela_auxiliar_cronograma (cronograma_id,mes_id,inicio_coleta,fim_coleta) VALUES ('".$cronograma_id."','".($i+1)."','".$data_inicio[$i]."','".$data_fim[$i]."');";
					  
					mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
				}
				
				$cronograma_id = '';
				$ano = '';
				$action = '';
				$data_inicio = array();
				$data_fim = array();
				
			}//do else de num_rows > 0	
		}
		else 
			if($action == 'del')
			{
				$strsql = "DELETE FROM tabela_cronograma_coletas WHERE cronograma_id = '".$cronograma_id."'";
				mysqli_query($conn, $strsql) or die(mysqli_error($conn));
				header("Location:".$_SERVER['PHP_SELF']);
			}
			
	$strsql = "SELECT * FROM tabela_cronograma_coletas";
	$cronograma = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$qtd_crono =  mysqli_num_rows($cronograma);
								
	if ($cronograma && $qtd_crono>0)
	{	
		while ($row = mysqli_fetch_array($cronograma))
		{
			$codigo[] = $row['cronograma_id'];
			$ano_array[] = $row['ano'];
		}
	}
	
	$aux_ano = date('Y') + 1;
	
	if($qtd_crono>0)
	{	
		while($aux_ano-- > 1998)
		{
			if(!in_array($aux_ano,$ano_array))
			{
				$ano_select[] = $aux_ano;
			}
		}
	}
	else
	{
	
		while($aux_ano-- > 1998)
		{
			$ano_select[] = $aux_ano;

		}
		
	}
	
	// Select possui apenas anos ainda não cadastrados
	if($action == 'edit')
	{
		
		if(in_array($ano,$ano_array))
		array_push($ano_select,$ano);	
		
	}
	
	rsort($ano_select);
	
	$qt_select = count($ano_select);
	
	$strsql = "SELECT * FROM tabela_mes";
	$mes = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$qt_mes = mysqli_num_rows($mes);	
	
	if ($mes && mysqli_num_rows($mes)>0)
	{
		while($row = mysqli_fetch_array($mes))
		{
			$mes_nome[] = $row['mes_nome'];
		}
	}	


require("cabecalho.php");
?>
<body>
		<div class="caixa_principal">

			<?php require("topo.php"); ?>
			
			<div class="menu_superior">
			<?php require("menu_superior.php"); ?>
			</div>
			
			<div class="menu_lateral">
			<?php require("menu_lateral_cadastros.php"); ?>
			</div>
			
			<div class="conteudo_pagina">
			
				<!-- Conteúdo referente a esta página -->
				<?php 
						  							
				if ($qtd_crono>0)
				{	
				?>
				<h1 id="Mcaption" style="text-align:left">Cadastro do Cronograma de Coletas</h1>
				<table cellspacing="0" id="listTable" summary="Tabela de Cidades" style="width:613px;">
					<colgroup>
						<col id="codigo" />
						<col id="nome" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Ano</th>
							<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
					<?php
								
						for($i=0;$i<$qtd_crono;$i++)
						{
						  if(!isset($l_cor)) $l_cor ='';
							if($l_cor == '') $l_cor = "par"; else $l_cor = "";
					?>
						<tr class="<?php echo ($l_cor);?>">
							<td class="tdboderCod"><?php echo($codigo[$i]); ?></td>
							<td class="tdboder"><?php echo($ano_array[$i]); ?></td>
							<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($codigo[$i]); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
							<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($codigo[$i]); ?>','del','<?php echo($ano_array[$i]); ?>','Deseja apagar o cronograma  ','');"><img src="images/botao_deletar.png" border="0"></a></td>
						</tr>
					<?php	   
						}//do while
					?>
				</table>	
				<?php
					 }
					 else
					 { 
				?>
					<h1 id="Mcaption" style="text-align:left">Sem cronogramas de coletas cadastradas</h1>
				<?php }?>
						 
			<!-- Início do Formulário da página -->
						 
			<fieldset style="width:600px;">
			<legend>
				<?php if($cronograma_id) {?>Editar<?php } else {?>Adicionar<?php }?> Cronograma</legend>
						
				<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:600px;">
				
				<?php if($cronograma_id) {?>
				<p>
					<label for="codigo">C&oacute;digo:</label> 
					<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($cronograma_id); ?> "/>
					<input type="hidden" name="hid" value="<?php echo($cronograma_id); ?>">
				</p>
				<?php } ?>
				
				<input type="hidden" name="hid" value="<?php echo($cronograma_id); ?>">
				<input type="hidden" name="haction" value="save">
				<p>
					<label for="ano">Ano:</label>
					<select name="ano">
						<option value="0"><---   Escolha o Ano  ---></option>
						<?php 
							
						for($i=0;$i<$qt_select;$i++)
						{
						?>
							<option value="<?php echo($ano_select[$i]); ?>" <?php if($ano_select[$i]==$ano){?> selected="selected" <?php } ?>)>
								<?php echo($ano_select[$i]); ?>
							</option>
						<?php 
						}
						?>				
					</select>
					<span class="obrig">*</span>
				</p>
				<hr />
				<table cellpadding="0" cellspacing="0" style="border:none;width:600px;">
					<caption style="padding-bottom:20px;">Cronograma de Meses</caption>
					<?php				
					
						for($i=0;$i<$qt_mes/2;$i++)
						{
								
					?>
					<tr style="padding-top:20px;">
						<td>
							<?php echo($mes_nome[2*$i]);?>
						</td>
						<td>
						<input type="text" name="data_inicio<?php echo(2*$i); ?>" readonly="readonly" size="7" value="<?php echo($data_inicio[2*$i]);?>" />
						<a onclick="displayCalendar(document.forms[0].data_inicio<?php echo(2*$i); ?>,'dd/mm/yyyy',this)"><img src="images/b_calendar.png" /></a>
						</td>
						<td>
						<input type="text" name="data_fim<?php echo(2*$i); ?>" size="7" value="<?php echo($data_fim[2*$i]);?>"/>
						<a onclick="displayCalendar(document.forms[0].data_fim<?php echo(2*$i); ?>,'dd/mm/yyyy',this)"><img src="images/b_calendar.png" /></a>
						</td>
						<td>
							<?php echo($mes_nome[2*$i+1]);?>
						</td>
						<td>
							<input type="text" name="data_inicio<?php echo(2*$i+1); ?>" size="7" value="<?php echo($data_inicio[2*$i+1]);?>" />
							<a onclick="displayCalendar(document.forms[0].data_inicio<?php echo(2*$i+1); ?>,'dd/mm/yyyy',this)"><img src="images/b_calendar.png" /></a>
						</td>
						<td>
							<input type="text" name="data_fim<?php echo(2*$i+1); ?>" size="7" value="<?php echo($data_fim[2*$i+1]);?>" />
							<a onclick="displayCalendar(document.forms[0].data_fim<?php echo(2*$i+1); ?>,'dd/mm/yyyy',this)"><img src="images/b_calendar.png" /></a>
						</td>
					</tr>
					<?php
						} 
					?>
				</table>
			<hr />
			<p>
				<input type="submit" class="botao_submit" value="<?php if($cronograma_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($cronograma_id != ''){?>
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_cronograma.php');"/>
				<?php }?>
			</p>
			<p class="obrig" align="right" style="color:#FF0000";margin-left:10px;>* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>			
			</form> 
		</fieldset>				
		</div>
			
			
		<div class="clearer"><span></span></div>

		<div class="rodape">&nbsp </div>
	
			<?php if($herr != ''){ ?>
			<script type="text/javascript" language="javascript">
				alert('<?php echo($herr);?>');
			</script>
			<?php } ?> 

</div>
<form name="frm_send_data" method="post" action="">
	<input type="hidden" name="haction" value="">
	<input type="hidden" name="hid" value="">
</form>
</body>
</html>
<script type="text/javascript" language="javascript">
				
	var frm_validator = new Validator("form_cadastro");
	var i = 0;
	
	var meses = new Array("JANEIRO","FEVEREIRO","MAR&Ccedil;O","ABRIL","MAIO","JUNHO","JULHO","AGOSTO","SETEMBRO","OUTUBRO","NOVEMBRO","DEZEMBRO");
	
	for(i = 0 ; i<12 ; i++)
	{
		frm_validator.addValidation("data_inicio"+i,"required","O início da coleta em " + meses[i]+ " não pode ficar em branco!");
	}
	
	i--;
	
	for(i = 0 ; i<12 ; i++)
	{
		frm_validator.addValidation("data_fim"+i,"required","O término da coleta em " + meses[i]+ " não pode ficar em branco!");
	}
	
	i--;
	
	frm_validator.addValidation("data_inicio"+i,"final","Corrija todo(s) o(s) erro(s)!!!");

</script>