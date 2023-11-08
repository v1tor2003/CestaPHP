<?php
  	
	$salario_id = $_REQUEST['hid'];
	$salario_nome = $_REQUEST['salario_nome'];
	$salario_simbolo = $_REQUEST['salario_simbolo'];
	$salario_valor_bruto = $_REQUEST['salario_valor_bruto'];
	$salario_valor_liquido = $_REQUEST['salario_valor_liquido'];
	$salario_em_uso = $_REQUEST['salario_em_uso'];
	$action = $_REQUEST['haction'];
	$flag = true;
	$herr = '';
	
	if ($action=='edit'){	  
	  	$strsql = "SELECT * FROM tabela_salarios WHERE salario_id = '".$salario_id."'";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$salario_id= $res['salario_id'];
		$salario_nome = $res['salario_nome'];
		$salario_simbolo = $res['salario_simbolo'];
		$salario_valor_bruto = $res['salario_valor_bruto']; 
		$salario_valor_liquido = $res['salario_valor_liquido'];
		$desconto = (100 - round((($salario_valor_liquido*100)/$salario_valor_bruto),2));
		$salario_em_uso = $res['salario_em_uso'];
	}
	
	if ($action=='save'){	  
	  
	  $strsql = "SELECT * FROM tabela_salarios WHERE ((salario_valor_bruto = '".$salario_valor_bruto."' AND salario_valor_liquido = '".$salario_valor_liquido."')) AND (salario_id <> '".$salario_id."')";
	  $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 		
		
		if ($res && mysqli_num_rows($res)>0)
		  	$herr = "Existe outro salario com a mesmo valor bruto e líquido!";
		else{	
		
			if($salario_em_uso == 1)
			{
				$strsql = "SELECT * FROM tabela_salarios WHERE (salario_em_uso= '1' AND salario_id <> '".$salario_id."') ";
				
				//die($strsql);
				$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 
				
				
				if ($res && mysqli_num_rows($res)>0)
				{
				  $herr = "Existe outro salário em uso!";
				  $flag = false;
				}
			}
			
			if($flag)
			{
		
		  if ($salario_id!='')//Vamos a atualizar
		    $strsql = "UPDATE tabela_salarios SET salario_valor_bruto = '".$salario_valor_bruto."', salario_valor_liquido = '".$salario_valor_liquido."',salario_em_uso = '".$salario_em_uso."',salario_nome = '".$salario_nome."',salario_simbolo = '".$salario_simbolo."' WHERE salario_id = '".$salario_id."'";  		
		  else//Vamos a insertar
		    $strsql = "INSERT INTO tabela_salarios (salario_valor_bruto,salario_valor_liquido,salario_data_registro,salario_em_uso,salario_nome,salario_simbolo) VALUES ('".$salario_valor_bruto."','".$salario_valor_liquido."','".date('Y-m-d')."','".$salario_em_uso."','".$salario_nome."','".$salario_simbolo."')";
			
			//die($strsql);
			
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			
			$salario_id = '';
			$salario_nome = '';
			$salario_simbolo = '';
			$desconto = '';
			$salario_valor_bruto = '';
			$salario_valor_liquido = '';
			$salario_em_uso = '';
	    	$action = '';
			}
		}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del'){
	  $strsql = "DELETE FROM tabela_salarios WHERE salario_id= '".$salario_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		header("Location:".$_SERVER['PHP_SELF']);
		die();
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
						  	$strsql = "SELECT * FROM tabela_salarios";
							$salarios = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($salarios && mysqli_num_rows($salarios)>0){	
					?>
					<h1 id="Mcaption" style="text-align:left">Cadastro de Sal&aacute;rios</h1>
					<table cellspacing="0" id="listTable" summary="Tabela de Delimitadores" style="width:543px;">
					<colgroup>
						<col id="codigo" />
						<col id="valor_bruto" />
						<col id="valor_liquido"/>
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Descri&ccedil;&atilde;o Monet&aacute;ria</th>
							<th scope="col" class="tdboder">S&iacute;mbolo</th>
							<th scope="col" class="tdboder">Valor Bruto</th>
							<th scope="col" class="tdboder">Valor L&iacute;quido</th>
							<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($salarios)){
							
								if($row['salario_em_uso']==1)
								{
									$l_cor = "row_destaque";
								}
								else
								{
									if($l_cor == '') 
										$l_cor = "par"; 
									else $l_cor = "";
								}
						  ?>
							   <tr class="<?php echo ($l_cor);?>">
								 <td class="tdboderCod"><?php echo($row['salario_id']); ?></td>
								 <td class="tdboder"><?php echo($row['salario_nome']); ?></td>
								 <td class="tdboder"><?php echo($row['salario_simbolo']); ?></td>
								 <td class="tdboder"><?php echo($row['salario_simbolo']." ".$row['salario_valor_bruto']); ?></td>
								 <td class="tdboder"><?php echo($row['salario_simbolo']." ".$row['salario_valor_liquido']); ?></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['salario_id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo $row['salario_id']; ?>','del','<?php echo $row['salario_valor_bruto']; ?>','Deseja apagar o salário mínimo ','');"><img src="images/botao_deletar.png" border="0"></a></td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>	
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem sal&aacute;rios cadastrados</h1>
						 <?php }?>
						 
								
		<fieldset>
			
			<legend>
				<?php if($salario_id) {?>Editar<?php } else {?>Adicionar<?php }?> Sal&aacute;rio</legend>
						
						
			
			<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:520px;">
			<?php if($salario_id) {?>
			<p>
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($salario_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($salario_id); ?>">
			</p>
			<?php } ?>
			<p>
				<label for="nome">Descri&ccedil;&atilde;o:</label>
<input type="text" name="salario_nome" maxlength="20" size="30" value="<?php echo($salario_nome); ?>" />
				<span class="obrig">*</span>
			</p>
			<p>
				<label for="nome">S&iacute;mbolo:</label>
<input type="text" name="salario_simbolo" maxlength="20" size="30" value="<?php echo($salario_simbolo); ?>" />
				<span class="obrig">*</span>
			</p>
			<p>
				<label for="nome">Sal&aacute;rio:</label>
<input type="text" name="salario_valor_bruto" onkeypress="mascara(this,soNumerosDSD)" onblur="calcula_salario()"  maxlength="7" size="30" value="<?php echo($salario_valor_bruto); ?>" />
				<input type="hidden" name="haction" value="save" />
				<span class="obrig">*</span>
			</p>
			<p>
				<label for="nome">Desconto:</label>
			<input type="text" name="desconto" value="<?php echo($desconto);?>" onkeypress="mascara(this,soNumerosDSD1)" onblur="calcula_salario()" maxlength="7" size="30" value="" />
				<span class="obrig">*</span>
			</p>
			<p>
				<label for="nome">Valor L&iacute;quido:</label>
				<input type="text" maxlength="6" name="salario_valor_liquido" class="inactive" readonly size="30" value="<?php echo($salario_valor_liquido); ?>" />
			</p>
			<p><label for="cesta">Em uso:</label>
			<input type="checkbox" name="salario_em_uso" value="1" <?php if ($salario_em_uso == 1){ ?> checked="checked" <?php } ?> style="vertical-align:bottom;" />
			<br /></p>
			<p>
				<input type="submit" class="botao_submit" value="<?php if($salario_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($salario_id != ''){?>
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('<?php echo($_SERVER['PHP_SELF']); ?>');"/>
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
<script language="javascript" type="text/javascript">
	var frm_validator = new Validator("form_cadastro");
	frm_validator.addValidation("salario_nome","required","O campo NOME não foi preenchido!");
	frm_validator.addValidation("salario_simbolo","required","O campo SÍMBOLO não foi preenchido!");
	frm_validator.addValidation("salario_valor_bruto","required","O campo SALÁRIO VALOR BRUTO não foi preenchido!");
	frm_validator.addValidation("desconto","required","O campo DESCONTO não foi preenchido!");
	frm_validator.addValidation("desconto","final","Corrija todo(s) o(s) erro(s)!!!");
	
	
	function calcula_salario()
	{
	
		var salario = document.form_cadastro.salario_valor_bruto.value;
		var desconto = document.form_cadastro.desconto.value;
		
		var salario_liquido = Math.round((salario * (1-(desconto/100)))*100)/100;
		
		document.form_cadastro.salario_valor_liquido.value = salario_liquido;
	}
</script>

</body>
</html>