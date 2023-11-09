<?php
	error_reporting(E_ERROR | E_PARSE);
  $tipo_id = $_REQUEST['hid'];
	$tipo_nome = $_REQUEST['tipo_nome'];
	$action = $_REQUEST['haction'];
	$herr = '';

	if ($action=='edit'){	  
	  	$strsql = "SELECT * FROM tabela_tipos_produtos WHERE tipo_id = '".$tipo_id."'";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$tipo_id = $res['tipo_id'];
		$tipo_nome = $res['tipo_nome'];
	}
	
	if ($action=='save'){	  
	  
	  $strsql = "SELECT * FROM tabela_tipos_produtos WHERE tipo_nome= '".$tipo_nome."' AND tipo_id <> '".$tipo_id."'";
	  $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 		
		
		if ($res && mysqli_num_rows($res)>0)
		  $herr = "Existe outro tipo de produto com a mesma descrição.";
		else{
		  
		  if ($tipo_id!='')
		    $strsql = "UPDATE tabela_tipos_produtos SET tipo_nome = '".$tipo_nome."' WHERE tipo_id = '".$tipo_id."'";  		
		  else
		    $strsql = "INSERT INTO tabela_tipos_produtos (tipo_nome) VALUES ('".$tipo_nome."')";
			
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			$tipo_id = '';
			$tipo_nome = '';
	    	$action = '';
		}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del'){
	  $strsql = "DELETE FROM tabela_tipos_produtos WHERE tipo_id = '".$tipo_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		header("Location:".$_SERVER['PHP_SELF']);
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
						  	$strsql = "SELECT * FROM tabela_tipos_produtos";
							$tipos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($tipos && mysqli_num_rows($tipos)>0){	
					?>
					<h1 id="Mcaption" style="text-align:left">Cadastro de Tipos de Produtos</h1>
					<table cellspacing="0" id="listTable" summary="Tabela de Tipos" style="width:533px;">
					<colgroup>
						<col id="codigo" />
						<col id="Descricao" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Descri&ccedil;&atilde;o</th>
							<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($tipos)){
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";
						  ?>
							   <tr class="<?php echo ($l_cor);?>">
								 <td class="tdboderCod"><?php echo($row['tipo_id']); ?></td>
								 <td class="tdboder"><?php echo($row['tipo_nome']); ?></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['tipo_id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['tipo_id']); ?>','del','<?php echo($row['tipo_nome']); ?>','Deseja apagar o tipo de produto ','');"><img src="images/botao_deletar.png" border="0"></a></td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>	
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem tipos de produtos cadastrados</h1>
						 <?php }?>
						 
								
		<fieldset>
			
			<legend>
				<?php if($tipo_id) {?>Editar<?php } else {?>Adicionar<?php }?> Tipo de Produto</legend>
						
						
			
			<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:510px;">
			<?php if($tipo_id) {?>
			<p>
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($tipo_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($tipo_id); ?>">
			</p>
			<?php } ?>
			
			<p>
				<label for="nome">Descri&ccedil;&atilde;o:</label>
				<input type="text" maxlength="100" name="tipo_nome" size="40" value="<?php echo($tipo_nome); ?>" />
				<span class="obrig">*</span>
				<input type="hidden" name="haction" value="save"/>
			</p>
			<p>
				<input type="submit" class="botao_submit" value="<?php if($tipo_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($tipo_id != ''){?>
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_tipos_produtos.php');"/>
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
	frm_validator.addValidation("tipo_nome","required","O campo DESCRICAO nao pode ficar em branco!");
	frm_validator.addValidation("tipo_nome","final","Corrija todo(s) o(s) erro(s)!!!");
</script>
</body>
</html>

