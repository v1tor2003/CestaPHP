<?php
  	$cidade_id = $_REQUEST['hid'];
	$cidade_nome = $_REQUEST['cidade_nome'];
	$action = $_REQUEST['haction'];
	$herr = '';

	if ($action=='edit'){	  
	  	$strsql = "SELECT * FROM tabela_cidades WHERE cidade_id = '".$cidade_id."'";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$cidade_id = $res['cidade_id'];
		$cidade_nome = $res['cidade_nome'];
	}
	
	if ($action=='save'){	  
	  
	  $strsql = "SELECT * FROM tabela_cidades WHERE cidade_nome= '".$cidade_nome."' AND cidade_id <> '".$cidade_id."'";
	  $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 		
		
		if ($res && mysqli_num_rows($res)>0)
		  $herr = "Existe outra cidade com o mesmo nome.";
		else{
		
		  $data = date('Y-m-d');
		  
		  if ($cidade_id!='')
		    $strsql = "UPDATE tabela_cidades SET cidade_nome = '".$cidade_nome."' WHERE cidade_id = '".$cidade_id."'";  		
		  else
		    $strsql = "INSERT INTO tabela_cidades (cidade_nome,cidade_data) VALUES ('".$cidade_nome."','".$data."')";
			
			
			//die($strsql);
			
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			$cidade_id = '';
			$cidade_nome = '';
	    	$action = '';
		}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del'){
	  $strsql = "DELETE FROM tabela_cidades WHERE cidade_id = '".$cidade_id."'";
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
						  	$strsql = "SELECT * FROM tabela_cidades";
							$cidades = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($cidades && mysqli_num_rows($cidades)>0){	
					?>
					<h1 id="Mcaption" style="text-align:left">Cadastro de Cidades</h1>
					<table cellspacing="0" id="listTable" summary="Tabela de Cidades" style="width:533px;">
					<colgroup>
						<col id="codigo" />
						<col id="nome" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Nome</th>
							<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($cidades)){
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";
						  ?>
							   <tr class="<?php echo ($l_cor);?>">
								 <td class="tdboderCod"><?php echo($row['cidade_id']); ?></td>
								 <td class="tdboder"><?php echo($row['cidade_nome']); ?></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['cidade_id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['cidade_id']); ?>','del','<?php echo($row['cidade_nome']); ?>','Deseja apagar a cidade ','');"><img src="images/botao_deletar.png" border="0"></a></td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>	
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem cidades cadastradas</h1>
						 <?php }?>
						 
								
		<fieldset>
			
			<legend>
				<?php if($cidade_id) {?>Editar<?php } else {?>Adicionar<?php }?> Cidade</legend>
						
						
			
			<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:510px;">
			<?php if($cidade_id) {?>
			<p>
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($cidade_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($cidade_id); ?>">
			</p>
			<?php } ?>
			
			<p>
				<label for="nome">Nome:</label>
				<input type="text" maxlength="100" name="cidade_nome" size="40" value="<?php echo($cidade_nome); ?>" />
				<span class="obrig">*</span>
				<input type="hidden" name="haction" value="save"/>
			</p>
			<p>
				<input type="submit" class="botao_submit" value="<?php if($cidade_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($cidade_id != ''){?>
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_cidades.php');"/>
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
	frm_validator.addValidation("cidade_nome","required","O campo NOME não pode ficar em branco!");
	frm_validator.addValidation("cidade_nome","final","Corrija todo(s) o(s) erro(s)!!!");
</script>
</body>
</html>

