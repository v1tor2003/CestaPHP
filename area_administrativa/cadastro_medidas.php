<?php
	error_reporting(E_ERROR | E_PARSE);
  $medida_id = $_REQUEST['hid'];
	$medida_descricao = $_REQUEST['medida_descricao'];
	$medida_simbolo = $_REQUEST['medida_simbolo'];
	$action = $_REQUEST['haction'];
	$herr = '';

	if ($action=='edit'){	  
	  	$strsql = "SELECT * FROM tabela_unidade_medidas WHERE medida_id = '".$medida_id."'";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$medida_id = $res['medida_id'];
		$medida_descricao = $res['medida_descricao'];
		$medida_simbolo = $res['medida_simbolo'];
 
	}
	
	if ($action=='save'){	  
	  
	  $strsql = "SELECT * FROM tabela_unidade_medidas WHERE medida_simbolo= '".$medida_simbolo."' AND medida_id <> '".$medida_id."'";
	  $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 		
		
		if ($res && mysqli_num_rows($res)>0)
		  $herr = "Existe outra medida com o mesmo símbolo.";
		else{
		  if ($medida_id!='')//Vamos a atualizar
		    $strsql = "UPDATE tabela_unidade_medidas SET medida_descricao = '".$medida_descricao."',medida_simbolo = '".$medida_simbolo."' WHERE medida_id = '".$medida_id."'";  		
		  else//Vamos a insertar
		    $strsql = "INSERT INTO tabela_unidade_medidas (medida_descricao,medida_simbolo) VALUES ('".$medida_descricao."','".$medida_simbolo."')";
			
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			$medida_id = '';
			$medida_descricao = '';
			$medida_simbolo = '';
	    	$action = '';
		}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del'){
	  $strsql = "DELETE FROM tabela_unidade_medidas WHERE medida_id = '".$medida_id."'";
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
			<h1 id="Mcaption" style="text-align:left">Cadastro de Unidades de Medidas</h1>
			<!-- Conteúdo referente a esta página -->
					<?php 
						  	$strsql = "SELECT * FROM tabela_unidade_medidas";
							$medidas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($medidas && mysqli_num_rows($medidas)>0){	
					?>
					<table cellspacing="0" id="listTable" summary="Tabela de Cidades" style="width:563px;">
					<colgroup>
						<col id="codigo" />
						<col id="descricao" />
						<col id="simbolo" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Descri&ccedil;&atilde;o</th>
							<th scope="col" class="tdboder">S&iacute;mbolo</th>
							<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($medidas)){
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";
						  ?>
							   <tr class="<?php echo ($l_cor);?>">
								 <td class="tdboderCod"><?php echo($row['medida_id']); ?></td>
								 <td class="tdboder"><?php echo($row['medida_descricao']); ?></td>
								 <td class="tdboder"><?php echo($row['medida_simbolo']); ?></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['medida_id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['medida_id']); ?>','del','<?php echo($row['medida_simbolo']); ?>','Deseja apagar a unidade de medida','');"><img src="images/botao_deletar.png" border="0"></a></td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>	
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem medidas cadastradas</h1>
						 <?php }?>
						 
								
		<fieldset>
			
			<legend>
				<?php if($medida_id) {?>Editar<?php } else {?>Adicionar<?php }?> Medida</legend>
						
						
			
			<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:540px;">
			<?php if($medida_id) {?>
			<p>
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($medida_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($medida_id); ?>">
			</p>
			<?php } ?>
			
			<p>
				<label for="nome">Descri&ccedil;&atilde;o:</label>
				<input type="text" maxlength="100" name="medida_descricao" size="40" value="<?php echo($medida_descricao); ?>" />
				<span class="obrig">*</span>
				<input type="hidden" name="haction" value="save"/>
			</p>
			<p>
				<label for="nome">S&iacute;mbolo:</label>
				<input type="text" maxlength="100" name="medida_simbolo" size="40" value="<?php echo($medida_simbolo); ?>" />
				<span class="obrig">*</span>
				<input type="hidden" name="haction" value="save"/>
			</p>
			<p>
				<input type="submit" align="left" class="botao_submit" value="<?php if($medida_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($medida_id != ''){?>
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_medidas.php');"/>
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
	
</body>
</html>

<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
</form>
<script language="javascript" type="text/javascript">
	var frm_validator = new Validator("form_cadastro");
	frm_validator.addValidation("medida_descricao","required","O campo DESCRICÃO não pode ficar em branco!");
	frm_validator.addValidation("medida_simbolo","required","O campo SÍMBOLO não pode ficar em branco!");
	frm_validator.addValidation("medida_simbolo","final","Corrija todo(s) o(s) erro(s)!!!");
</script>
</body>
</html>
